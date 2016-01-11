<?php
if(!isset($isReferencing)) header('Location: index.php');
?>

<script src="https://cdn.socket.io/socket.io-1.4.3.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
<script>
var socket;
var currentMessage;
  $( document ).ready(function() {
      socket = io.connect('http://localhost:3000');
      var sessionid = "sessionid";

      $('#sid').html(sessionid);
      socket.emit('subscribe', sessionid);

      socket.on('nfc_card_connected_message', function(msg){
          currentMessage = msg.message;
          handleNfcConnectionMessage(msg.message);
        });

      socket.on('nfc_card_disconnected_message', function(msg){
          delete currentMessage;
          handleNfcDisconnectionMessage(msg.message);
      });

      socket.on('register_wristband_succesful', function(msg){
        currentMessage = msg.message;
        handleNfcConnectionMessage(msg.message);
      });

      socket.on('unregister_wristband_succesful', function(msg){
        delete currentMessage;
        handleNfcConnectionMessage(msg.message);
      });
  });
</script>
<body>

<h4>Session id: <div id="sid"></div></h4>
<section class="container content">
  <fieldset>
    <legend>NFC Application</legend>
    <div visibility="hidden" id="uid"></div>
    <div class="control-group">
      <label class="control-label" for="wid">Wristband ID</label>
      <div class="controls">
        <input type="text" disabled="disabled" class="input-xlarge required" name="wid" id="wid">
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="balance">Balance</label>
      <div class="controls">
        <input type="text" disabled="disabled" class="input-xlarge required" name="balance" id="balance">
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="first_name">First Name</label>
      <div class="controls">
        <input type="text" disabled="disabled" class="input-xlarge required" name="first_name" id="first_name">
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="last_name">Last Name</label>
      <div class="controls">
        <input type="text" disabled="disabled" class="input-xlarge required" name="last_name" id="last_name">
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="email">Email</label>
      <div class="controls">
        <input type="text" disabled="disabled" class="input-xlarge required" name="email" id="email">
      </div>
    </div>
    <div class="control-group">
      <input type="checkbox" disabled="disabled" id="anonymous" value="anonymous"> Anonymous?<br>
    </div>
  </fieldset>
</section>

<button onclick="registerWristband()" class="btn btn-primary" disabled="disabled" type="button" id="reg_wristband_button">Register Wristband</button>
<button class="btn btn-primary" disabled="disabled" type="button" id="reg_guest_button">Register new Guest</button>
<button class="btn btn-primary" disabled="disabled" type="button" id="update_guest_button">Update Guest</button>
<button onclick="unregisterWristband()" class="btn btn-primary" disabled="disabled" type="button" id="unreg_wristband_button">Unregister Wristband</button>
</body>


<script>
  function registerWristband() {
    var data = {};
    data.gid=-1;
    data.balance=0.0;
    data.uid=currentMessage.uid;
    data.status='I';
    socket.emit('register_wristband', data);
  }

  function unregisterWristband() {
    socket.emit('unregister_wristband', currentMessage);
  }

  function handleNfcConnectionMessage(message) {
    $('#uid').html(message.uid);
    if (typeof message.wid == 'undefined') {
      $('#reg_wristband_button').removeAttr('disabled');
    } else {
      $('#wid').val(message.wid);
      $('#balance').val(message.balance);

      if(typeof message.gid == 'undefined') {
        $('#reg_guest_button').removeAttr('disabled');
        $('#unreg_wristband_button').removeAttr('disabled');
      } else {
        $('#first_name').val(message.first_name);
        $('#last_name').val(message.last_name);
        $('#email').val(message.email);
        if(message.anonymous) {
          $('#anonymous').attr('checked', true);
        }

        $('#first_name').removeAttr('disabled');
        $('#last_name').removeAttr('disabled');
        $('#email').removeAttr('disabled');
        $('#first_name').removeAttr('disabled');
        $('#anonymous').removeAttr('disabled');

        $('#update_guest_button').removeAttr('disabled');
      }
    }
  }

  function handleNfcDisconnectionMessage(message) {
    $('#uid').val('');
    $('#wid').val('');
    $('#balance').val('');
    $('#first_name').val('');
    $('#last_name').val('');
    $('#email').val('');
    $('#anonymous').attr('checked', false);

    $('#first_name').attr('disabled','disabled');
    $('#last_name').attr('disabled','disabled');
    $('#email').attr('disabled','disabled');
    $('#first_name').attr('disabled','disabled');
    $('#anonymous').attr('disabled','disabled');

    $('#reg_wristband_button').attr('disabled','disabled');
    $('#unreg_wristband_button').attr('disabled','disabled');
    $('#reg_guest_button').attr('disabled','disabled');
    $('#update_guest_button').attr('disabled','disabled');
  }
</script>


