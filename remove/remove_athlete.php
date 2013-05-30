<?php
$nb_athletes = 67261;


if(isset($_GET['aid']) && isset($_GET['k'])){
  if($_GET['aid']>$nb_athletes && $_GET['k']!=''){
    $aid=$_GET['aid'];
    $k=$_GET['k'];

    echo $aid;
    
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
	
header('Location: ../index.php?p=search&k=' . $k);  
?>