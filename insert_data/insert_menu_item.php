<?php
$msg = 2;
if(isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price'])) {
	$name = pg_escape_string($_POST['name']); 
	$description = pg_escape_string($_POST['description']);
	$price = pg_escape_string($_POST['price']);
	
	if($name != '' && $price != ''){
		$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

		$query = "INSERT INTO menu_items (name, description, price) VALUES ('" . $name . "', '" . $description . "', " .  $price .")";
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
header('Location: ../index.php?p=menu&m=' . $msg);
?>