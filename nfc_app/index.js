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

var sock;
io.on('connection', function(socket){
  sock=socket;
  socket.on('subscribe', function(room) {
    console.log('joining room', room);
    socket.join(room);
  });

  socket.on('nfc_card_connected', function(data) {
    console.log('New NFC Card read from ', data.room);
    console.log('NFC UID: ', data.uid);

    pg.connect(connectionString, function(err, client, done) {
      if(err) {
        return console.error('error fetching client from pool', err);
      }
      process.nextTick(function() {
        var selectWristbandQuery = 'SELECT w.wid, w.balance, w.status, w.uid, w.gid FROM wristband w WHERE w.uid = $1::bytea';
        client.query(selectWristbandQuery, [data.uid], function(err, result) {
          if(err) return rollback(client, done);

          var wristband = {};
          //Check if the wristband is already logged in the database
          if(typeof result.rows[0] != 'undefined') {
            wristband=result.rows[0];
            console.log("NFC UID Present in database. WID: "+wristband.wid);
            if(wristband.gid>0) {
              console.log("Wristband registered with a guest");
              var selectGuestQuery = 'SELECT g.first_name, g.last_name, g.email, g.anonymous FROM guest g WHERE g.gid = $1';
              client.query(selectGuestQuery, [wristband.gid], function(err, res) {
                if(err) return rollback(client, done);
                if(typeof res.rows[0] != 'undefined') {
                  guest=res.rows[0];
                  console.log("Guest GID: " + wristband.gid);
                }

                guest.gid=wristband.gid;
                data.wristband=wristband;
                data.wristband.uid=data.uid;
                data.guest=guest;
                console.log(data);
                socket.broadcast.to(data.room).emit('nfc_card_connected_message', data);
              });
            } else {
                console.log("Wristband NOT registered with a guest");
                data.wristband=wristband;
                data.wristband.uid=data.uid;
                socket.broadcast.to(data.room).emit('nfc_card_connected_message', data);
            }
          } else {
            console.log("Unregistered wristband");
            data.wristband={};
            data.wristband.uid=data.uid;
            socket.broadcast.to(data.room).emit('nfc_card_connected_message', data);
          }
        });
      });
    });
  });

  socket.on('nfc_card_disconnected', function(data) {
    console.log('sending room post', data.room);

    socket.broadcast.to(data.room).emit('nfc_card_disconnected_message', data);
  });

  socket.on('register_wristband', function(data) {
    var regData = registerWristband(data);
  });

  socket.on('unregister_wristband', function(data) {
    var unregData = unregisterWristband(data);
  });

  socket.on('register_guest', function(data) {
    var regData = registerGuest(data);
  });

  socket.on('unregister_guest', function(data) {
    var regData = unregisterGuest(data);
  });

  socket.on('update_guest', function(data) {
    var updateData = updateGuest(data);
  });

  socket.on('process_transaction', function(data){
    console.log("Processing new transaction");
    console.log(data);
    var amount = 0.0;

    var i;
    for(i=0; i<data.orders.length;i++) {
      amount+=data.orders[i].unit_price*data.orders[i].amount;
    }

    console.log("Transaction amount: " + amount);

    pg.connect(connectionString, function(err, client, done) {
      if(err) {
        return console.error('error fetching client from pool', err);
      }
      client.query('BEGIN', function(err) {
        if(err) return rollback(client, done);
        process.nextTick(function() {
          var new_balance = (data.wristband.balance - amount);
          var newTransactionQuery = 'INSERT INTO transaction (wid, gid, amount, prev_balance, new_balance, gpid) VALUES ($1, $2, $3, $4, $5, $6) RETURNING tid;';
          client.query(newTransactionQuery, [data.wristband.wid, data.guest.gid, amount, data.wristband.balance, new_balance, data.catering.gpid], function(err, result) {
            if(err) return rollback(client, done);

            if(typeof result.rows[0] != 'undefined') {
              var tid=result.rows[0].tid;
              console.log("Transaction id " + tid);
              var newOrdersQuery = "";
              for(i=0; i<data.orders.length;i++) {
                var order = data.orders[i];
                newOrdersQuery += "INSERT INTO orders (tid, iid, num_item, item_price) VALUES ("+tid+", "+order.iid+", "+order.unit_price+", "+order.amount+");"; 
              }
              newOrdersQuery += 'UPDATE wristband SET balance = '+ new_balance +' WHERE wid = '+ data.wristband.wid +';'
              console.log("Orders query: " + newOrdersQuery);
              client.query(newOrdersQuery, function(err, result) {
                if(err) return rollback(client, done);
                client.query('COMMIT', done);

                data.transaction={};
                data.transaction.tid=tid;
                data.wristband.balance=new_balance;
                console.log("Transaction succesfully processed: " + tid);
                console.log("New balance: " + data.wristband.balance);
                io.sockets.in(data.room).emit('process_transaction_succesful', data);
              });
            }
          });
        });
      });
    });
  });

  function updateGuest(data) {
    console.log("Data received" + data.guest);
    pg.connect(connectionString, function(err, client, done) {
    if(err) {
      return console.error('error fetching client from pool', err);
    }
      process.nextTick(function() {
        var updateGuestQuery = 'UPDATE guest SET first_name = $1, last_name = $2, email = $3, anonymous = $4 WHERE gid = $5;';
        client.query(updateGuestQuery, [data.guest.first_name, data.guest.last_name, data.guest.email, data.guest.anonymous, data.guest.gid], function(err, result) {
          if(err) return rollback(client, done);
          
          io.sockets.in(data.room).emit('update_guest_succesful', data);
          return data;
        });
      });
    });
  }

//TODO: Add a BEGIN/COMMIT for the database call
  function registerGuest(data) {
  console.log("Guest to register " + data.guest.first_name + " on wristband id: " + data.wristband.wid);
  pg.connect(connectionString, function(err, client, done) {
    if(err) {
      return console.error('error fetching client from pool', err);
    }
      process.nextTick(function() {
        var registerGuestQuery = 'INSERT INTO guest (first_name, last_name, email, anonymous) VALUES ($1, $2, $3, $4) RETURNING gid;';
        client.query(registerGuestQuery, [data.guest.first_name, data.guest.last_name, data.guest.email, data.guest.anonymous], function(err, result) {
          if(err) return rollback(client, done);
          if(typeof result.rows[0] != 'undefined') {
            data.guest.gid = result.rows[0].gid;
            var regGuestWithWristband = 'UPDATE wristband SET status = $1, gid = $2 WHERE wid = $3;';
            client.query(regGuestWithWristband, ['A', data.guest.gid, data.wristband.wid], function(err, res) {
              if(err) return rollback(client, done);
              
              io.sockets.in(data.room).emit('register_guest_succesful', data);
              return data;
            });
          }
        });
      });
    });
  }

  function unregisterGuest(data) {
    console.log(data);
    //console.log("Guest to unregister " + data.guest.first_name + " from wristband id: " + data.wristband.wid);
    pg.connect(connectionString, function(err, client, done) {
      if(err) {
        return console.error('error fetching client from pool', err);
      }
        process.nextTick(function() {
          var unregisterGuestQuery = 'UPDATE wristband SET gid=-1 WHERE wid = $1;';
          client.query(unregisterGuestQuery, [data.wristband.wid], function(err, result) {
            if(err) return rollback(client, done);
            if(typeof result.rows[0] != 'undefined') {
              delete data.guest;
              console.log("Sending: " + data);
              io.sockets.in(data.room).emit('unregister_guest_succesful', data);
            }
          });
        });
      });
  }

  function registerWristband(data) {
    console.log("Data to register: " + data.wristband.uid);

    pg.connect(connectionString, function(err, client, done) {
      if(err) {
        return console.error('error fetching client from pool', err);
      }
      client.query('INSERT INTO wristband (uid, balance, gid, status) VALUES ($1::bytea, $2, $3, $4) RETURNING wid;', [data.uid, data.wristband.balance, data.wristband.gid, data.wristband.status], function(err, result) {
        //call `done()` to release the client back to the pool
        done();

        if(err) {
          return console.error('error running query', err);
        }
        console.log(result.rows[0]);

        data.wristband.wid = result.rows[0].wid;
        console.log('Succesfully registered new wristband ' + data.wristband);

        io.sockets.in(data.room).emit('register_wristband_succesful', data);

        return data;
      });
    });
  }

  function unregisterWristband(data) {
    console.log("Unregistering wristband wid = " + data.wristband.wid);
    pg.connect(connectionString, function(err, client, done) {
        if(err) {
          return console.error('error fetching client from pool', err);
        }
        client.query('DELETE FROM wristband WHERE wid=$1;', [data.wristband.wid], function(err) {
          //call `done()` to release the client back to the pool
          done();

          if(err) {
            return console.error('error running query', err);
          }

          delete data.wristband;

          console.log('Succesfully unregistered wristband ' + data.uid);

          io.sockets.in(data.room).emit('unregister_wristband_succesful', data);
          return data;
        });
      });
    }

});

http.listen(3000, function(){
  console.log('listening on *:3000');
});