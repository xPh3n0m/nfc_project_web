var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http); 

// must specify options hash even if no options provided!
var phpExpress = require('php-express')({
 
  // assumes php is in your PATH
  binPath: 'php'
});
 
// set view engine to php-express
app.set('views', './views');
app.engine('php', phpExpress.engine);
app.set('view engine', 'php');
 
// routing all .php file to php-express
app.all(/.+\.php$/, phpExpress.router);

// Connect to PG database
var pg = require('pg');
var connectionString = process.env.DATABASE_URL || 'postgresql://root:nfcproject@nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com:5432/nfcprojectdb';

var rollback = function(client, done) {
  client.query('ROLLBACK', function(err) {
    //if there was a problem rolling back the query
    //something is seriously messed up.  Return the error
    //to the done function to close & remove this client from
    //the pool.  If you leave a client in the pool with an unaborted
    //transaction weird, hard to diagnose problems might happen.
    return done(err);
  });
};

app.get('/nfc_app', function(req, res){
  res.sendFile(__dirname + '/index.html');
});

io.on('connection', function(socket){
  socket.on('subscribe', function(room) {
    console.log('joining room ', room);
    socket.join(room);
  });

  socket.on('nfc_card_connected', function(data) {
    console.log('sending room post', data.room);
    var results = [];
    console.log(data);

    pg.connect(connectionString, function(err, client, done) {
      if(err) {
        return console.error('error fetching client from pool', err);
      }
      client.query('BEGIN', function(err) {
        if(err) return rollback(client, done);
        process.nextTick(function() {
          var selectWristbandQuery = 'SELECT w.wid, w.balance, w.status, w.uid FROM wristband w WHERE w.uid = $1::bytea';
          client.query(selectWristbandQuery, [data.message], function(err, result) {
            if(err) return rollback(client, done);
            var wristband = {};
            //Check if the wristband is already logged in the database
            if(typeof result.rows[0] != 'undefined') {
              wristband=result.rows[0];
              if(wristband.gid>0) {
                var selectGuestQuery = 'SELECT g.first_name, g.last_name, g.email, g.anonymous FROM guest g WHERE g.gid = $1';
                client.query(selectGuestQuery, wristband.gid, function(err, res) {
                  if(err) return rollback(client, done);
                  if(typeof res.rows[0] != 'undefined') {
                    wristband.first_name=res.rows[0].first_name;
                    wristband.last_name=res.rows[0].last_name;
                    wristband.email=res.rows[0].email;
                    wristband.anonymous=res.rows[0].anonymous;
                  }

                });
              }
            } else {
              wristband = data;
              wristband.uid=data.message;
              delete wristband.message;
            }
            console.log("Wrisband sent to client: " + wristband.uid);
            socket.broadcast.to(data.room).emit('nfc_card_connected_message', {
              message: wristband
            });
          });
        });
      });
    });
  });

  socket.on('nfc_card_disconnected', function(data) {
    console.log('sending room post', data.room);
    socket.broadcast.to(data.room).emit('nfc_card_disconnected_message', {
        message: data.message
    });
  });

  socket.on('register_wristband', function(data) {
    var regData = registerWristband(data);
    socket.broadcast.to(data.room).emit('register_wristband_succesful', {
      message: regData
    });
  });

  socket.on('unregister_wristband', function(data) {
    var unregData = unregisterWristband(data);
    socket.broadcast.to(data.room).emit('unregister_wristband_succesful', {
      message: unregData
    });
  });
});

http.listen(3000, function(){
  console.log('listening on *:3000');
});

function registerWristband(data) {
    console.log("Data to register: " + data.gid);

    pg.connect(connectionString, function(err, client, done) {
      if(err) {
        return console.error('error fetching client from pool', err);
      }
      client.query('INSERT INTO wristband (uid, balance, gid, status) VALUES ($1::bytea, $2, $3, $4) RETURNING wid;', [data.uid, data.balance, data.gid, data.status], function(err, result) {
        //call `done()` to release the client back to the pool
        done();

        if(err) {
          return console.error('error running query', err);
        }
        console.log(result.rows[0]);

        data.wid = result.rows[0].wid;
        console.log('Succesfully registered new wristband ' + data);
        return data;
      });
    });
  }

function unregisterWristband(data) {
  console.log("Unregistering wristband wid = " + data.wid);
  pg.connect(connectionString, function(err, client, done) {
      if(err) {
        return console.error('error fetching client from pool', err);
      }
      client.query('DELETE FROM wristband WHERE wid=$1;', [data.wid], function(err) {
        //call `done()` to release the client back to the pool
        done();

        if(err) {
          return console.error('error running query', err);
        }

        unregData = {};
        unregData.uid = data.uid;
        console.log('Succesfully unregistered wristband ' + data.uid);
        return unregData;
      });
    });
}