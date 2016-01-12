<!-- LOAD FOOD & DRINKS MENU -->
<?php
$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

// get menu items
$query = "SELECT cg.gpid, cg.name FROM catering_group cg";

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

<!-- DISPLAY LIST OF CATERING -->
<form class="form-horizontal">
	<fieldset>
	  <legend>Select the catering group</legend>
	  <div class="control-group">
		<label class="control-label" for="gpid">Catering groups</label>
		<div class="controls">
			<select id="gpid" name="gpid">
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
		<button type="submit" class="btn btn-primary">Load</button>
	  </div>
	</fieldset>
</form>

<?php
	pg_close();
?>