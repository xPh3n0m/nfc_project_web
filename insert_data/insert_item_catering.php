<?php
if(isset($_POST['iid']) && isset($_POST['gpid'])) {
	
	$iid = pg_escape_string($_POST['iid']); 
	$gpid = pg_escape_string($_POST['gpid']);
	
	if($iid != '' && $gpid != ''){
		$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

		$query = "INSERT INTO group_items (iid, gpid) VALUES (" . $iid . ", " . $gpid . ")";
		$result = pg_query($query); 
		if (!$result) { 
			$errormessage = pg_last_error();
			exit(); 
		} 
		pg_close();
	}
}
header('Location: ../index.php?p=info&a=' . $_POST['location'] . '&id=' . $gpid);
?>