<?php
if(!isset($isReferencing)) header('Location: ../index.php');
if(!isset($_GET['id'])) header('Location: index.php');
$tid=pg_escape_string($_GET['id']);
$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

?>

<header class="jumbotron subhead" id="overview">

<?php
	$query = "SELECT t.tid, t.gid, g.first_name, g.last_name, g.email, t.amount, t.prev_balance, t.new_balance, t.timestamp, t.wid FROM transaction t, guest g  WHERE g.gid = t.gid AND t.tid = " . $tid;

	$result = pg_query($query); 
	if (!$result) { 
		$errormessage = pg_last_error(); 
		exit(); 
	} 

	$row = pg_fetch_row($result);
	echo "<h2>Transaction ID:" . $row[0] . "</h2>";

?>
</header>

<!-- Guest information -->
<?php
	echo "<p>Guest id: " . $row[1] . "</p>";
	echo "<p>Guest name: " . $row[2] . " " . $row[3] . "</p>";
	echo "<p>Guest email: " . $row[4] . "</p>";
	echo "<p>Wristband id: " . $row[9] . "</p>";
?>

<!-- TRANSACTION INFO -->
<h4>Transaction details</h4>
<?php
	if($row[5] > 0) {
		echo "<p>Debit transaction: " . $row[5] . " CHF</p>";
	} else {
		echo "<p>Credit transaction: " . -$row[5] . " CHF</p>";
	}
	echo "<p>Previous balance: " . $row[6] . "</p>";
	echo "<p>New balance: " . $row[7] . "</p>";
	echo "<p>Timestamp: " . $row[8] . "</p>";
?>


<!-- ORDERS INFO -->
<table class="table table-striped">
	<thead>
	  <tr>
		<th>Order ID</th>
		<th>Item Name</th>
		<th>Item Description</th>
		<th>Quantity</th>
		<th>Unit price</th>
		<th>Total price</th>
	  </tr>
	</thead>

	<?php

	$query = "SELECT o.oid, mi.name, mi.description, o.iid, o.num_item, o.item_price FROM orders o, menu_items mi WHERE o.iid = mi.iid AND o.tid = " . $tid;

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
		echo "  <td>".($row[0] !== null ? htmlentities($row[0], ENT_QUOTES) : "&nbsp;")."</td>\n";
		echo "  <td>".($row[1] !== null ? htmlentities($row[1], ENT_QUOTES) : "&nbsp;")."</td>\n";
		echo "  <td>".($row[2] !== null ? htmlentities($row[2], ENT_QUOTES) : "&nbsp;")."</td>\n";
		echo "  <td>".($row[4] !== null ? htmlentities($row[4], ENT_QUOTES) : "&nbsp;")."</td>\n";
		echo "  <td>".($row[5] !== null ? htmlentities($row[5], ENT_QUOTES) : "&nbsp;")."</td>\n";
		echo "  <td>".($row[5]*$row[4] !== null ? htmlentities($row[5]*$row[4], ENT_QUOTES) : "&nbsp;")."</td>\n";

		//echo "<td><i class='icon-remove'></i></td>\n";
		//echo "<td><a href='remove/remove_group_item.php?gpid=" . 
	    //$row[0] . "&amp;iid=" . $iid . "&amp;location=menu_item" .
	    "'><i class='icon-remove'></i></a></td>\n";
		echo "</tr>\n";
	}
	echo "</tbody>\n";

	?>
</table>




<?php
	pg_close();
?>