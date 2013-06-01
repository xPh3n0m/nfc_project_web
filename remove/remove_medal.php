<?php
// Security measure
$nb_athletes = 67261;
$aid = '';

if(isset($_GET['aid'])){
	$aid=$_GET['aid'];
	if($aid>$nb_athletes && isset($_GET['g']) && isset($_GET['c']) && isset($_GET['s']) && isset($_GET['d'])){
		$game=$_GET['g'];
		$country=$_GET['c'];
		$sport=$_GET['s'];
		$discipline=$_GET['d'];

	    $conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

		if (!$conn) {
		  $e = oci_error();
		  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$stid = oci_parse(
		  $conn, "DELETE FROM Medals m
		  WHERE m.aid = " . $aid .
		  " AND m.olympics = " . $game .
		  " AND m.country = " . $country .
		  " AND m.sport = " . $sport .
		  " AND m.disciplines" . $discipline
		);

		if (!$stid) {
		  $e = oci_error($conn);
		  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$r = oci_execute($stid);
		if (!$r) {
		  $e = oci_error($stid);
		  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		oci_free_statement($stid);
		oci_close($conn);
	}
}
	
header('Location: ../index.php?p=info&a=athlete&id=' . $aid);  
?>