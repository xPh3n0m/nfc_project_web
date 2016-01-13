<!-- LOAD CATERING GROUPS -->
<?php
$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

// get menu items
$query = "SELECT cg.gpid, cg.name FROM catering_group cg";

$result = pg_query($query); 
if (!$result) { 
	$errormessage = pg_last_error(); 
	exit(); 
} 

$cg_table = array();
$num_results = 0;
while ($row = pg_fetch_row($result)) {
  array_push($cg_table, $row);
  $num_results++;
}

?>

<!-- LOAD MENU_ITEMS GROUPS -->
<?php

// get menu items
$query = "SELECT mi.iid, mi.name, mi.description, cg.gpid, cg.name FROM menu_items mi, group_items gi, catering_group cg WHERE mi.iid=gi.iid AND gi.gpid=cg.gpid ORDER BY cg.gpid";

$result = pg_query($query); 
if (!$result) { 
	$errormessage = pg_last_error(); 
	exit(); 
} 

$mi_table = array();
$num_results = 0;
while ($row = pg_fetch_row($result)) {
  array_push($mi_table, $row);
  $num_results++;
}

?>



<!-- DISPLAY LIST OF CATERING -->
<form class="form-horizontal">
	<fieldset>
	  <legend>Select the catering group</legend>
	  	<select id="catering_selector" class="form-control">
	  		<option value=""></option>
			<?php
			while (!empty($cg_table)) {
				$row = array_shift($cg_table);
				echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
			}
			?>
		</select>
</form>

<script>
$('#catering_selector').change(function() {
  $('[name="table_gpid"]').attr('hidden','hidden');
  var gpid = $( "#catering_selector option:selected" ).val();
  $('#table_gpid'+gpid).removeAttr('hidden');
});
</script>

<table class="table table-striped">
  <thead>
    <tr>
      <th>IID</th>
      <th>Item Name</th>
	  <th>Item Description</th>
	  <th>GPID</th>
	  <th>Catering Group name</th>
    </tr>
  </thead>

  <?php
	$gpid=0;
	$start=true;
	while (!empty($mi_table)) {


		$row = array_shift($mi_table);
		if($gpid != $row[3]) {
			$gpid=$row[3];
			if(start) {
				$start=false;
			} else {
				echo "</tbody>\n";
			}
			echo "<tbody hidden='hidden' name='table_gpid' id='table_gpid".$gpid."'>";
		}
		echo "<tr>\n";
		echo "  <td>".$row[0]."</td>\n";
		echo "  <td>".$row[1]."</td>\n";
		echo "  <td>".$row[2]."</td>\n";
		echo "  <td>".$row[3]."</td>\n";
		echo "  <td>".$row[4]."</td>\n";
		echo "</tr>\n";
	}
	echo "</tbody>\n";

	?>

  <?php
  /*echo "<tbody>\n";
  while (!empty($mi_table)) {
    $row = array_shift($mi_table);
    echo "<tr>\n";
    foreach ($row as $item) {
      echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
    }
    echo "</tr>\n";
  }
  echo "</tbody>\n";

  pg_close()*/
  ?>
</table>

<?php
	pg_close();
?>