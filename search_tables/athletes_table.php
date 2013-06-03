<?php
if(!isset($isReferencing)) header('Location: ../index.php');
$conn = oci_connect('db2013_g014_select', 'selectonly', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

if (!$conn) {
  $e = oci_error();
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse(
  $conn, "SELECT DISTINCT a.aid, a.name
  FROM athletes a
  WHERE (upper(a.aid) LIKE '%" . strtoupper($searchkey) . "%'
   OR upper(a.name) LIKE '%" . strtoupper($searchkey) . "%')
  ORDER BY a.aid ASC"
);

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;
$r = oci_execute($stid);
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$totaltime = ($endtime - $starttime);

if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$table = array();
$num_results = 0;
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
  array_push($table, $row);
  $num_results++;
}

echo '<span class="label label-info">'.$num_results.' results found</span>&nbsp;';
echo '<span class="label label-success">in '.number_format($totaltime, 3).' seconds</span>';
?>

<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Remove</th>
      <th>Show more</th>
    </tr>
  </thead>

  <?php
  echo "<tbody>\n";
  while (!empty($table)) {
    $row = array_shift($table);
    echo "<tr>\n";
    foreach ($row as $item) {
      echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
    }
    echo "<td><a href='remove/remove_athlete.php?aid=" . $row['AID'] . "&amp;k=" . $searchkey . "'><i class='icon-remove'></i></a></td>\n";
    echo "<td><a href='index.php?p=info&amp;a=athlete&amp;id=" . $row['AID'] . "' class='player-link' data-id='" . $row['AID'] . "' data-task='more'><i class='icon-plus'></i></a></td>\n";
    echo "</tr>\n";
  }
  echo "</tbody>\n";

  oci_free_statement($stid);
  oci_close($conn);
  ?>
</table>