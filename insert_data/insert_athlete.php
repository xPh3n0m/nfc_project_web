<?php
$msg = 3;
if(isset($_POST['name'])){
	$athlete_name = str_replace("'", "''", $_POST['name']);
	if($athlete_name != ''){

		$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

		if (!$conn) {
			$e = oci_error();
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$stid = oci_parse(
			$conn, "INSERT INTO Athletes
			VALUES((SELECT MAX(aid) FROM Athletes A) + 1, '" . $athlete_name . "')" 
		);

		if (!$stid) {
			$e = oci_error($conn);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$r = oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		} else {
			$msg = 0;
		}

		oci_free_statement($stid);
		oci_close($conn);
	}
}

header('Location: ../index.php?p=insert&m=' . $msg);
?>