<?php
if(!isset($isReferencing)) header('Location: index.php');

$msgs = array('Successfully inserted new catering company', 'Catering company succesfully removed', 'There was a problem inserting the new catering company', 'Database problem', 'There was a problem removing the catering company');
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
<form class="form-horizontal" action="insert_data/insert_catering.php" method="POST">
        <fieldset>
          <legend>Add a catering company</legend>
          <div class="control-group">
            <label class="control-label" for="name">Name<font color="red">*</font></label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="name" id="name">
            </div>
          </div>
           <div class="control-group">
            <label class="control-label" for="description">Description</label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="description" id="description">
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
	<li class="active"><a href="#catering" data-toggle="tab">Catering companies</a></li>
  </ul>
  <div class="tab-content">
	<div class="tab-pane active" id="catering">
	  <?php include('search_tables/catering_table.php'); ?>
	</div>
  </div>
</div>