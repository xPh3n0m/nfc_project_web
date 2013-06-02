<?php
// Security measure
$nb_athletes = 67261;
$aid = '';

if(isset($_GET['aid'])){
	$aid=$_GET['aid'];
	if($aid>$nb_athletes && isset($_GET['g']) && isset($_GET['c']) && isset($_GET['s'])){
		$game=urldecode($_GET['g']);
		$country=urldecode($_GET['c']);
		$sport=urldecode($_GET['s']);

	    $conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

		if (!$conn) {
		  $e = oci_error();
		  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$stid = oci_parse(
		  $conn, "DELETE FROM Participants p
		  WHERE (p.aid = " . $aid . " 
		  	AND p.olympics = '" . $game . "' 
		  	AND p.country = '" . $country . "' 
		  	AND p.sport = '" . $sport . "')"
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