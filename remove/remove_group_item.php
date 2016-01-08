<?php
if(isset($_GET['location']) && isset($_GET['gpid']) && isset($_GET['iid'])){
	$gpid=pg_escape_string($_GET['gpid']);
	$iid=pg_escape_string($_GET['iid']);
	
	$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 
	
	$query = "DELETE FROM group_items gi WHERE gi.gpid = " . $gpid . " AND gi.iid = " . $iid; 
	$result = pg_query($query); 
	if (!$result) { 
		$errormessage = pg_last_error(); 
		exit(); 
	}
	pg_close();
	
	
}
	
header('Location: ../index.php?p=info&a=' . $_GET['location'] . '&id=' . $gpid);
?>