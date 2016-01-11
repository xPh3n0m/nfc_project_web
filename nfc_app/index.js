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

app.get('/nfc_app', function(req, res){
  res.sendFile(__dirname + '/index.html');
});

io.on('connection', function(socket){
  socket.on('nfc_card_connected', function(msg){
  	console.log("NFC Card connected. UID: " + msg);
    io.emit('nfc_card_connected', msg);
  });

  socket.on('nfc_card_disconnected', function (msg) {
    console.log("NFC Card disconnected. UID: " + msg);
    io.emit('nfc_card_disconnected', msg);
  });
});

http.listen(3000, function(){
  console.log('listening on *:3000');
});