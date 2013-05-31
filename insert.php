<?php
$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');
$stid = oci_parse($conn, "select * from athletes order by name desc");

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

?>
<script type="text/javascript">
$('#aid').typeahead({
  source:function(query, process){
    athletes = [];
    map = {};

    var athletes = [
    <?php
    $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    echo '{"aid": "'.$row['aid'].'", "name": "'.$row['name'].'"}';
    /*while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
      echo ', {"aid": "'.$row['aid'].'", "name": "'.$row['name'].'"}';
    }*/
    ?>
    ];

  },
  updater: function (item) {
      // implementation
  },
  matcher: function (item) {
      // implementation
  },
  sorter: function (items) {
      // implementation
  },
  highlighter: function (item) {
     // implementation
  }
});
</script>

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
                <input type="text" class="input-xlarge required" name="country" id="country">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="olympics">Olympics</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="olympics" id="olympics">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="sport">Sport</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="sport" id="sport">
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
              <label class="control-label" for="aid">Athlete's ID</label>
              <div class="controls">
                <input type="text" class="input-small required" name="aid" id="aid">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="country">Country</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="country" id="country">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="olympic">Olympic</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="olympics" id="olympic">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="sport">Sport</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="sport" id="sport">
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