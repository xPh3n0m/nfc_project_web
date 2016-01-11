<?php
if(!isset($isReferencing)) header('Location: index.php');
?>

<script src="https://cdn.socket.io/socket.io-1.4.3.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
<script>
  $( document ).ready(function() {
      var socket = io.connect('http://localhost:3000');
      var sessionid = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);
      $('#sid').html(sessionid);
      socket.emit('subscribe', sessionid);

      socket.on('nfc_card_connected_message', function(msg){
        $('#wid').val(msg.message);
      });
      socket.on('nfc_card_disconnected_message', function(msg){
        $('#wid').val('');
      });
  });
</script>
<body>

<h4>Session id: <div id="sid"></div></h4>
<section class="container content">
  <fieldset>
    <legend>NFC Application</legend>
    <div class="control-group">
      <label class="control-label" for="wid">Current Wristband ID</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" name="wid" id="wid">
      </div>
    </div>
  </fieldset>
</section>
</body>

