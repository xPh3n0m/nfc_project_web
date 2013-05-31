<?php
$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');
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

$athletes = array();
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
  array_push($athletes, $row);
}
?>
<section class="container content">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#athlete" data-toggle="tab">Athlete</a></li>
      <li class=""><a href="#participation" data-toggle="tab">Participation</a></li>
      <li class=""><a href="#team" data-toggle="tab">Team</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="athlete">
        <form class="form-horizontal" id="player-form" action="insert_data/insert_athlete.php" method="post">
          <fieldset>
            <legend>Insert an athlete</legend>
            <input type="hidden" value="athlete" name="type">
            <div class="control-group">
              <label class="control-label" for="name">Athlete's full name</label>
              <div class="controls">
                <input type="text" class="input-xxlarge required" name="name" id="name" placeholder="Jerry Golay">
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="tab-pane" id="participation">
        <form class="form-horizontal" action="insert_data/insert_participation.php" method="POST">
          <fieldset>
            <legend>Insert a participation</legend>
            <input type="hidden" name="type" value="coach">
            <div class="control-group">
              <label class="control-label" for="aid">Athlete name</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="aid" id="aid">
                <p class="help-block">Athlete name is required!</p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="country">Country</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="country" id="country">
                <p class="help-block">Country is required!</p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="olympics">Olympics</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="olympics" id="olympics">
                <p class="help-block">Olympics is required</p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="sport">Sport</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="sport" id="sport">
                <p class="help-block">Sport is required</p>
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save changes</button>
              <button class="btn">Cancel</button>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="tab-pane" id="team">
        <form class="form-horizontal" action="insert.php" method="POST">
          <fieldset>
            <legend>Add a team</legend>
            <input type="hidden" name="type" value="team">
            <div class="control-group">
              <label class="control-label" for="name">Team ID (3 letters)</label>
              <div class="controls">
                <input type="text" class="input-small required" name="tid" id="tid" placeholder="AAA">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="name">Team name</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="name" id="name">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="location required">Location</label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="location" id="location">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="league">League</label>
              <div class="controls">
                <select name="league" class="required" id="league">
                  <option value="nba">NBA</option>
                  <option value="aba">ABA</option>
                </select>
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save changes</button>
              <button class="btn">Cancel</button>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </section>