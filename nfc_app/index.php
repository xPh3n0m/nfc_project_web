<?php
if(!isset($isReferencing)) header('Location: index.php');
?>

<script src="https://cdn.socket.io/socket.io-1.4.3.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
<script>
var socket;
var currentMessage;
var sessionid = "sessionid";
  $( document ).ready(function() {
      socket = io.connect('http://localhost:3000');

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
        handleNfcDisconnectionMessage(msg.message);
        handleNfcConnectionMessage(msg.message);
      });

      socket.on('unregister_wristband_succesful', function(msg){
        delete currentMessage;
        handleNfcDisconnectionMessage(msg.message);
        handleNfcConnectionMessage(msg.message);
      });

      socket.on('register_guest_succesful', function(msg){
        currentMessage.guest=msg.message;
        handleNfcDisconnectionMessage(msg.message);
        handleNfcConnectionMessage(msg.message);
      });

      socket.on('update_guest_succesful', function(msg){
        currentMessage.guest=msg.message;
        handleNfcDisconnectionMessage(msg.message);
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
    <div class="tabbable">
    <ul class="nav nav-pills">
      <li class="disabled"><a href="#">Select app:</a></li>
      <li class="active"><a href="#registration" data-toggle="tab">Registration</a></li>
      <li><a href="#cash" data-toggle="tab">Cash Handler</a></li>
    </ul>
    <ul class="tab-content">
      <li class="tab-pane active" id="registration"> 
        <?php
          include("registration.html");
        ?>
      </li>
      <li class="tab-pane" id="cash">
        <?php
          include("cash_handler.html");
        ?>
      </li>
    </div>
  </fieldset>
</section>


</body>


<script>
  function registerGuest() {
    var data = {};
    data.wid=currentMessage.wid;

    if($('#anonymous').is(":checked")) {
      data.anonymous=true;
    } else {
      data.anonymous=false;
      data.first_name=$('#first_name').val();
      data.last_name=$('#last_name').val();
      data.email=$('#email').val();
    }

    data.room = sessionid;
    socket.emit('register_guest', data);
  }

  function unregisterGuest() {
    var data = {};
    data.wid=currentMessage.wid;
    data.gid=currentMessage.gid;

    if($('#anonymous').is(":checked")) {
      data.anonymous=true;
    } else {
      data.anonymous=false;
      data.first_name=$('#first_name').val();
      data.last_name=$('#last_name').val();
      data.email=$('#email').val();
    }

    data.room = sessionid;
    socket.emit('register_guest', data);
  }

  function updateGuest() {
    var data = {};
    data.gid=currentMessage.gid;

    if($('#anonymous').is(":checked")) {
      data.anonymous=true;
    } else {
      data.anonymous=false;
      data.first_name=$('#first_name').val();
      data.last_name=$('#last_name').val();
      data.email=$('#email').val();
    }

    data.room = sessionid;
    socket.emit('update_guest', data);
  }

  function registerWristband() {
    var data = {};
    data.gid=-1;
    data.balance=0.0;
    data.uid=currentMessage.uid;
    data.status='I';
    data.room = sessionid;
    socket.emit('register_wristband', data);
  }

  function unregisterWristband() {
    var data = {};
    data=currentMessage;
    data.room = sessionid;
    socket.emit('unregister_wristband', data);
  }

  function handleNfcConnectionMessage(message) {
    $('#uid').html(message.uid);
    if (typeof message.wid == 'undefined') {
      $('#reg_wristband_button').removeAttr('disabled');
    } else {
      $('#wid').val(message.wid);
      $('#balance').val(message.balance);

      if(message.gid <= 0) {
        $('#reg_guest_button').removeAttr('disabled');
        $('#unreg_wristband_button').removeAttr('disabled');
      } else {
        $('#first_name').val(message.first_name);
        $('#last_name').val(message.last_name);
        $('#email').val(message.email);
        if(message.anonymous) {
          $('#anonymous').attr('checked', true);
        }

        $('#update_guest_button').removeAttr('disabled');
      }
      $('#first_name').removeAttr('disabled');
      $('#last_name').removeAttr('disabled');
      $('#email').removeAttr('disabled');
      $('#first_name').removeAttr('disabled');
      $('#anonymous').removeAttr('disabled');
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


