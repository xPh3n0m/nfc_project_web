<?php
if(!isset($isReferencing)) header('Location: index.php');

$msgs = array('Successfully inserted new guest', 'Guest succesfully removed', 'There was a problem inserting the new guest', 'Database problem', 'There was a problem removing the guest');
if(isset($_GET['m'])){
  $m = $_GET['m'];
  if($m >= 0 && $m < count($msgs)){
    if($m < 2){ // Success
      echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>' .
      $msgs[$m] . '</div>';
    } else {  // Error
      echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>' .
      $msgs[$m] . '</div>';
    }
  }
}

?>


<section class="container content">
<form class="form-horizontal" action="insert_data/insert_guest.php" method="POST">
        <fieldset>
          <legend>Add a guest</legend>
          <div class="control-group">
            <label class="control-label" for="first_name">First name<font color="red">*</font></label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="first_name" id="first_name">
            </div>
          </div>
           <div class="control-group">
            <label class="control-label" for="last_name">Last name<font color="red">*</font></label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="last_name" id="last_name">
            </div>
          </div>           
		  <div class="control-group">
            <label class="control-label" for="email">Email<font color="red">*</font></label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="email" id="email">
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Insert</button>
          </div>
        </fieldset>
      </form>
</section>

<div class="tabbable">
  <ul class="nav nav-tabs">
	<li class="active"><a href="#guest" data-toggle="tab">Guests</a></li>
  </ul>
  <div class="tab-content">
	<div class="tab-pane active" id="guest">
	  <?php include('search_tables/guest_table.php'); ?>
	</div>
  </div>
</div>