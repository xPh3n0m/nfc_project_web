<?php
if(!isset($isReferencing)) header('Location: index.php');
?>

<script src="https://cdn.socket.io/socket.io-1.4.3.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
<script>
var socket;
var currentData;
var sessionid = "sessionid";
  $( document ).ready(function() {
      socket = io.connect('http://localhost:3000');

      $('#sid').html(sessionid);
      socket.emit('subscribe',sessionid);

      socket.on('nfc_card_connected_message', function(msg){
          currentData = msg;
          setFields(msg);
        });

      socket.on('nfc_card_disconnected_message', function(msg){
          delete currentData;
          resetFields(msg);
      });

      socket.on('register_wristband_succesful', function(msg){
        currentData = msg;
        resetFields(msg);
        setFields(msg);
      });

      socket.on('unregister_wristband_succesful', function(msg){
        currentData = msg;
        resetFields(msg);
        setFields(msg);
      });

      socket.on('register_guest_succesful', function(msg){
        currentData=msg;
        resetFields(msg);
        setFields(msg);
      });

      socket.on('unregister_guest_succesful', function(msg){
        currentData=msg;
        resetFields(msg);
        setFields(msg);
      });

      socket.on('update_guest_succesful', function(msg){
        currentData=msg;
        resetFields(msg);
        setFields(msg);
      });

      socket.on('process_transaction_succesful', function(msg) {
        currentData=msg;
        resetFields(msg);
        setFields(msg);
      })
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
      <li><a href="#catering" data-toggle="tab">Catering App</a></li>
    </ul>
    <ul class="tab-content">
      <li class="tab-pane active" id="registration"> 
        <?php
          include("nfc_app/registration.html");
        ?>
      </li>
      <li class="tab-pane" id="cash">
        <?php
          include("nfc_app/cash_handler.html");
        ?>
      </li>
      <li class="tab-pane" id="catering">
        <?php
          include("nfc_app/catering.php");
        ?>
      </li>
    </div>
  </fieldset>
</section>


</body>


<script>

  function creditWristband(amount) {
    // Create a new credit order
    var order = {};
    order.iid = 1;
    order.unit_price = 1;
    order.amount = -amount;

    var catering = {};
    catering.gpid=0;

    var data = currentData;
    data.orders = [];
    data.orders[0] = order;
    data.catering=catering;

    socket.emit('process_transaction', data);
  }


  function registerGuest() {
    var data = currentData;
    delete data.guest;
    data.guest={};

    if($('#anonymous').is(":checked")) {
      data.guest.anonymous=true;
    } else {
      data.guest.anonymous=false;
      data.guest.first_name=$('#first_name').val();
      data.guest.last_name=$('#last_name').val();
      data.guest.email=$('#email').val();
    }

    data.room = sessionid;
    socket.emit('register_guest', data);
  }

  function unregisterGuest() {
    var data = currentData;
    socket.emit('unregister_guest', data);
  }

  function updateGuest() {
    var data = currentData;

    delete data.guest;
    data.guest={};
    data.guest.gid=currentData.wristband.gid;

    if($('#anonymous').is(":checked")) {
      data.guest.anonymous=true;
    } else {
      data.guest.anonymous=false;
      data.guest.first_name=$('#first_name').val();
      data.guest.last_name=$('#last_name').val();
      data.guest.email=$('#email').val();
    }

    socket.emit('update_guest', data);
  }

  function registerWristband() {
    var data = currentData;

    delete data.wristband;
    data.wristband={};
    data.wristband.gid=-1;
    data.wristband.balance=0.0;
    data.wristband.uid=currentData.uid;
    data.wristband.status='I';
    data.room = sessionid;
    socket.emit('register_wristband', data);
  }

  function unregisterWristband() {
    var data=currentData;
    socket.emit('unregister_wristband', data);
  }

  function setFields(message) {
    if (typeof message.wristband.wid == 'undefined') {
      $('#reg_wristband_button').removeAttr('disabled');
    } else {
      $('#wid').val(message.wristband.wid);
      $('[name="balance"]').val(message.wristband.balance);

      if(typeof message.guest == 'undefined') {
        $('#reg_guest_button').removeAttr('disabled');
        $('#unreg_wristband_button').removeAttr('disabled');
      } else {
        $('#first_name').val(message.guest.first_name);
        $('#last_name').val(message.guest.last_name);
        $('#email').val(message.guest.email);
        if(message.guest.anonymous) {
          $('#anonymous').attr('checked', true);
        }

        $('#update_guest_button').removeAttr('disabled');
        $('#unreg_guest_button').removeAttr('disabled');
      }
      $('#first_name').removeAttr('disabled');
      $('#last_name').removeAttr('disabled');
      $('#email').removeAttr('disabled');
      $('#first_name').removeAttr('disabled');
      $('#anonymous').removeAttr('disabled');
    }
  }

  function resetFields(message) {
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
    $('#unreg_guest_button').attr('disabled','disabled');

    $('#amount').val("");
  }

</script>


