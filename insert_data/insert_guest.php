<?php
$msg = 2;
if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])) {
	$first_name = pg_escape_string($_POST['first_name']); 
	$last_name = pg_escape_string($_POST['last_name']); 
	$email = pg_escape_string($_POST['email']);
	
	if($first_name != '' && $last_name != '' && $email != ''){
		$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

		$query = "INSERT INTO guest(first_name, last_name, email, anonymous) VALUES('" . $first_name . "', '" . $last_name . "', '" . $email . "', false)"; 
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
header('Location: ../index.php?p=guest&m=' . $msg);
?>