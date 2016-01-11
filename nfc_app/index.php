<?php
if(!isset($isReferencing)) header('Location: index.php');
?>

<script src="https://cdn.socket.io/socket.io-1.4.3.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
<script>
  $( document ).ready(function() {
      var socket = io.connect('http://localhost:3000');
      socket.on('nfc_card_connected', function(msg){
        $('#wid').val(msg);
      });
      socket.on('nfc_card_disconnected', function(msg){
        $('#wid').val('');
      });
  });
</script>
<body>
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

