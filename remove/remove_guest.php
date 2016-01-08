<?php
$msg=4;
if(isset($_GET['gid'])){
	$gid=pg_escape_string($_GET['gid']);
	
	$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 
	
	$query = "DELETE FROM guest g WHERE g.gid = " . $gid; 
	$result = pg_query($query); 
	if (!$result) { 
		$errormessage = pg_last_error(); 
		exit(); 
	} 
	$msg=1;
	pg_close();
}
	
header('Location: ../index.php?p=guest&m=' . $msg);  
?>