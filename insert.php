<?php
$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

// get country names
$stid = oci_parse($conn, "select name from countries order by name asc");

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$countries = "[\"";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
  $countries = $countries . "\", \"" . $row['NAME'];
}
$countries = $countries . "\"]";

// get olympics
$stid = oci_parse($conn, "select name from games order by name desc");

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$olympics = "[\"";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
  $olympics = $olympics . "\", \"" . $row['NAME'];
}
$olympics = $olympics . "\"]";

// get sports
$stid = oci_parse($conn, "select name from sports order by name asc");

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$sports = "[\"";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
  $sports = $sports . "\", \"" . $row['NAME'];
}
$sports = $sports . "\"]";

// get team names
$stid = oci_parse($conn, "select distinct team_name as name from participants order by name desc");

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$teams = "[\"";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
  $teams = $teams . "\", \"" . $row['NAME'];
}
$teams = $teams . "\"]";

oci_free_statement($stid);
oci_close($conn);

?>

<section class="container content">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#athlete" data-toggle="tab">Athlete</a></li>
    <li class=""><a href="#participation" data-toggle="tab">Participation</a></li>
    <li class=""><a href="#medal" data-toggle="tab">Medal</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="athlete">
      <form class="form-horizontal" id="player-form" action="insert_data/insert_athlete.php" method="post">
        <fieldset>
          <legend>Insert an athlete</legend>
          <div class="control-group">
            <label class="control-label" for="name">Athlete's full name</label>
            <div class="controls">
              <input type="text" class="input-xxlarge required" name="name" id="name" placeholder="Jerry Golay">
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </fieldset>
      </form>
    </div>
    <div class="tab-pane" id="participation">
      <form class="form-horizontal" action="insert_data/insert_participation.php" method="POST">
        <fieldset>
          <legend>Add a participation</legend>
          <div class="control-group">
            <label class="control-label" for="aid">Athlete's ID</label>
            <div class="controls">
              <input type="text" class="input-small required" name="aid" id="aid">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="country">Country</label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="country" id="country"
              autocomplete="off" data-items="10" data-provide="typeahead" data-source='<?php echo $countries; ?>'>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="olympics">Olympics</label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="olympics" id="olympics"
              autocomplete="off" data-items="10" data-provide="typeahead" data-source='<?php echo $olympics; ?>'>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="sport">Sport</label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="sport" id="sport"
              autocomplete="off" data-items="10" data-provide="typeahead" data-source='<?php echo $sports; ?>'>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="teamname">Team Name</label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="teamname" id="teamname"
              autocomplete="off" data-items="10" data-provide="typeahead" data-source='<?php echo $teams; ?>'>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </fieldset>
      </form>
    </div>
    <div class="tab-pane" id="medal">
      <form class="form-horizontal" action="insert_data/insert_medal.php" method="POST">
        <fieldset>
          <legend>Add a medal</legend>
          <div class="control-group">
            <label class="control-label" for="medal">Medal</label>
            <div class="controls">
              <input type="text" class="input required" name="medal" id="medal" placeholder="Gold medal"
              autocomplete="off" data-items="3" data-provide="typeahead" data-source='["Gold medal", "Silver medal", "Bronze medal"]'>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="aid">Athlete's ID</label>
            <div class="controls">
              <input type="text" class="input-small required" name="aid" id="aid">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="country">Country</label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="country" id="country"
              autocomplete="off" data-items="10" data-provide="typeahead" data-source='<?php echo $countries; ?>'>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="olympic">Olympic</label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="olympics" id="olympic"
              autocomplete="off" data-items="10" data-provide="typeahead" data-source='<?php echo $olympics; ?>'>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="sport">Sport</label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="sport" id="sport"
              autocomplete="off" data-items="10" data-provide="typeahead" data-source='<?php echo $sports; ?>'>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="discipline">Discipline</label>
            <div class="controls">
              <input type="text" class="input-xlarge required" name="discipline" id="discipline">
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</section>
<script src="/twitter-bootstrap/twitter-bootstrap-v2/docs/assets/js/bootstrap-typeahead.js"></script>
