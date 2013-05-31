<?php
// Security measure
$nb_athletes = 67261;
$k = '';

if(isset($_GET['k'])){
	$k=$_GET['k'];
	if(isset($_GET['aid'])){
		$aid=$_GET['aid'];
		if($aid>$nb_athletes && $k!=''){
		    
		    $conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

			if (!$conn) {
			  $e = oci_error();
			  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			$stid = oci_parse(
			  $conn, "DELETE FROM Athletes a
			  WHERE a.aid = " . $aid
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
}
	
header('Location: ../index.php?p=search&k=' . $k);  
?>