<?php
$msg = 2;
if(isset($_POST['name']) && isset($_POST['description'])) {
	$name = pg_escape_string($_POST['name']); 
	$description = pg_escape_string($_POST['description']);
	
	if($name != ''){
		$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

		$query = "INSERT INTO catering_group (name, description) VALUES ('" . $name . "', '" . $description . "')"; 
		$result = pg_query($query); 
		if (!$result) { 
			$errormessage = pg_last_error(); 
			$msg=3; 
			exit(); 
		} 
		$msg=0;
		pg_close();
	}
}
header('Location: ../index.php?p=catering&m=' . $msg);
?>