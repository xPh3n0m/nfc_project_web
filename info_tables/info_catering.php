<?php
if(!isset($isReferencing)) header('Location: ../index.php');
if(!isset($_GET['id'])) header('Location: index.php');
$gpid=pg_escape_string($_GET['id']);
$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

?>

<header class="jumbotron subhead" id="overview">

<?php
	$query = "SELECT c.name, c.description FROM catering_group c WHERE c.gpid = " . $gpid;

	$result = pg_query($query); 
	if (!$result) { 
		$errormessage = pg_last_error(); 
		exit(); 
	} 

	$row = pg_fetch_row($result);
	echo "<h2>" . $row[0] . "</h2>";

?>
</header>

<p>Catering Company ID: <?php echo $gpid; ?></p>

<!-- Description -->
<?php
	echo "<p>Description: " . $row[1] . "</p>";
?>

<!-- FOOD & DRINKS MENU -->
<h4>Food & Drinks Menu</h4>
<table class="table table-striped">
	<thead>
	  <tr>
		<th>ID</th>
		<th>Name</th>
		<th>Unit Price</th>
		<th>Description</th>
		<th>Remove</th>
	  </tr>
	</thead>

	<?php

	$query = "SELECT mi.iid, mi.name, mi.price, mi.description FROM menu_items mi, group_items gi WHERE mi.iid = gi.iid AND gi.gpid = " . $gpid;

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
	    $gpid . "&amp;iid=" . $row[0] . "&amp;location=catering" .
	    "'><i class='icon-remove'></i></a></td>\n";
		//echo "<td><a href='index.php?p=info&amp;a=catering&amp;id=" . $row[0] . "' class='player-link' data-id='" . $row[0] . "' data-task='more'><i class='icon-plus'></i></a></td>";
		echo "</tr>\n";
	}
	echo "</tbody>\n";

	?>
</table>


<!-- LOAD FOOD & DRINKS MENU -->
<?php

// get menu items
$query = "SELECT mi.iid, mi.name FROM menu_items mi WHERE mi.iid > 1 AND mi.iid NOT IN (SELECT gi.iid FROM group_items gi WHERE gi.gpid = " . $gpid .")";

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

?>

<!-- DISPLAY FOOD & DRINKS MENU -->
<form class="form-horizontal" action="insert_data/insert_item_catering.php" method="POST">
	<fieldset>
	  <legend>Add an item to the menu</legend>
	  <div class="control-group">
		<label class="control-label" for="iid">Menu Item</label>
		<div class="controls">
			<input type='hidden' id="gpid" name='gpid' value='<?php echo $gpid?>' />
			<input type='hidden' id="location" name='location' value='<?php echo "catering"?>' />
			<select id="iid" name="iid">
				<?php
				while (!empty($table)) {
					$row = array_shift($table);
					echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
				}
				?>
			</select>
		</div>
	  </div>
	  <div class="form-actions">
		<button type="submit" class="btn btn-primary">Insert</button>
	  </div>
	</fieldset>
</form>

<?php
	pg_close();
?>