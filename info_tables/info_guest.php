<?php
if(!isset($isReferencing)) header('Location: ../index.php');
if(!isset($_GET['id'])) header('Location: index.php');
$gid=pg_escape_string($_GET['id']);
$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

?>

<header class="jumbotron subhead" id="overview">

<?php
	$query = "SELECT g.first_name, g.last_name FROM guest g WHERE g.gid = " . $gid;

	$result = pg_query($query); 
	if (!$result) { 
		$errormessage = pg_last_error(); 
		exit(); 
	} 

	$row = pg_fetch_row($result);
	echo "<h2>" . $row[0] . " " . $row[1] . "</h2>";

?>
</header>

<p>Guest ID: <?php echo $gid; ?></p>

<!-- EMAIL -->
<?php

	$query = "SELECT g.email FROM guest g WHERE g.gid = " . $gid;

	$result = pg_query($query); 
	if (!$result) { 
		$errormessage = pg_last_error(); 
		exit(); 
	} 

	$row = pg_fetch_row($result);
	
	echo "<p>Email address: " . $row[0] . "</p>";
?>

<!-- WRISTBAND -->
<?php

	$query = "SELECT w.wid, w.uid, w.balance FROM wristband w WHERE w.gid = " . $gid;

	$result = pg_query($query); 
	if (!$result) { 
		$errormessage = pg_last_error(); 
		exit(); 
	} 

	$row = pg_fetch_row($result);
	
	echo "<p>Wristband id : " . $row[0] . "</p>";
	echo "<p>Wristband unique identifier : " . $row[1] . "</p>";
	echo "<p>Balance : " . $row[2] . "</p>";
?>


<?php
	pg_close();
?>