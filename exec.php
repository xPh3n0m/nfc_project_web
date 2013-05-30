<?php
if(!isset($isReferencing)) header('Location: index.php');
$query='';
$isQuerySet=False;
if(isset($_POST['q'])){
  if($_POST['q']!=''){
    $query=$_POST['q'];
    $isQuerySet=True;
  }
}
?>

<header class="jumbotron subhead" id="overview">
  <h2>Execute a query</h2>
</header>
<form class="form-horizontal" action="index.php?p=exec" method="post">
  <fieldset>
    <legend>Ask and I shall execute</legend>
    <div class="control-group">
      <label class="control-label" for="query">Query:</label>
      <div class="controls">
        <textarea class="field span6" name="q" id="query" rows="10" ><?php echo $query; ?></textarea>
      </div>
    </div>
    <div class="form-actions">
      <button id="btn-query" type="submit" class="btn btn-primary">Execute query</button>
    </div>
  </fieldset>
</form>

<?php
if($isQuerySet){
  $conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

  if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }

  $stid = oci_parse($conn, $query);

  if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }

  $r = oci_execute($stid);
  if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }

  if(stripos(trim($query), "select") === 0){

    $table = array();
    $num_results = 0;
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
      array_push($table, $row);
      $num_results++;
    }
    echo '<span class="label label-info">'.$num_results.' results found</span>';

    echo "<table class='table table-striped'><thead><tr><th>&nbsp;</th></tr></thead><tbody>\n";
    while (!empty($table)) {
      $row = array_shift($table);
      echo "<tr>\n";
      foreach ($row as $item) {
        echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
      }
      echo "</tr>\n";
    }
    echo "</tbody></table>\n";
  }

  oci_free_statement($stid);
  oci_close($conn);
}
?>