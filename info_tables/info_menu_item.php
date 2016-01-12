<?php
if(!isset($isReferencing)) header('Location: ../index.php');
if(!isset($_GET['id'])) header('Location: index.php');
$iid=pg_escape_string($_GET['id']);
$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

?>

<header class="jumbotron subhead" id="overview">

<?php
	$query = "SELECT mi.name, mi.description, mi.price FROM menu_items mi WHERE mi.iid = " . $iid;

	$result = pg_query($query); 
	if (!$result) { 
		$errormessage = pg_last_error(); 
		exit(); 
	} 

	$row = pg_fetch_row($result);
	echo "<h2>" . $row[0] . "</h2>";

?>
</header>

<p>Menu item ID: <?php echo $iid; ?></p>

<!-- Description & Price -->
<?php
	echo "<p>Description: " . $row[1] . "</p>";
	echo "<p>Unit Price: " . $row[2] . "</p>";
?>

<!-- WHERE CAN THIS ITEM BE FOUND? -->
<h4>Find this item in the following catering stands</h4>
<table class="table table-striped">
	<thead>
	  <tr>
		<th>ID</th>
		<th>Name</th>
		<th>Description</th>
		<th>Remove</th>
	  </tr>
	</thead>

	<?php

	$query = "SELECT c.gpid, c.name, c.description FROM catering_group c, group_items gi WHERE c.gpid = gi.gpid AND gi.iid = " . $iid;

	$result = pg_query($query); 
	if (!$result) { 
		$errormessage = pg_last_error(); 
		exit(); 
	} 

	$table = array();
	$num_results = 0;
	while ($row = pg_fetch_row($result)) {
	  array_push($table, $row);
	  $num_results++;
	}
	
	echo "<tbody>\n";
	while (!empty($table)) {
		$row = array_shift($table);
		echo "<tr>\n";
		foreach ($row as $item) {
		  echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
		}
		//echo "<td><i class='icon-remove'></i></td>\n";
		echo "<td><a href='remove/remove_group_item.php?gpid=" . 
	    $row[0] . "&amp;iid=" . $iid . "&amp;location=menu_item" .
	    "'><i class='fa fa-times'></i></a></td>\n";
		echo "</tr>\n";
	}
	echo "</tbody>\n";

	?>
</table>




<?php
	pg_close();
?>