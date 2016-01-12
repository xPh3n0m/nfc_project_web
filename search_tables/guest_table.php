<?php
if(!isset($isReferencing)) header('Location: ../index.php');
$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

$query = "SELECT gid, first_name, last_name, email FROM guest WHERE NOT anonymous = 't'"; 
$result = pg_query($query); 
if (!$result) { 
	$errormessage = pg_last_error(); 
	$msg=2; 
	exit(); 
} 

$table = array();
$num_results = 0;
while ($row = pg_fetch_row($result)) {
  array_push($table, $row);
  $num_results++;
}

echo '<span class="label label-info">'.$num_results.' results found</span>&nbsp;';

;

?>

<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>First name</th>
	  <th>Last name</th>
	  <th>Email</th>
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
    //echo "<td><a href='remove/remove_athlete.php?aid=" . $row['gid'] . "&amp;k=" . $searchkey . "'><i class='icon-remove'></i></a></td>\n";
    //echo "<td><a href='index.php?p=info&amp;a=athlete&amp;id=" . $row['gid'] . "' class='player-link' data-id='" . $row['gid'] . "' data-task='more'><i class='icon-plus'></i></a></td>\n";
	echo "<td><a href='remove/remove_guest.php?gid=" . $row[0] . "'><i class='fa fa-times'></i></a></td>";
	echo "<td><a href='index.php?p=info&amp;a=guest&amp;id=" . $row[0] . "' class='player-link' data-id='" . $row[0] . "' data-task='more'><i class='fa fa-plus'></i></a></td>";
    echo "</tr>\n";
  }
  echo "</tbody>\n";

  pg_close()
  ?>
</table>