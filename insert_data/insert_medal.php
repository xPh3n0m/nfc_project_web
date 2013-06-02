<?php
if(isset($_POST['aid']) && isset($_POST['country']) && isset($_POST['olympics']) && isset($_POST['sport']) && isset($_POST['medal']) && isset($_POST['discipline'])) {
	$aid = $_POST['aid'];
	$country = $_POST['country'];
	$olympics = $_POST['olympics'];
	$sport = $_POST['sport'];
	$medal = $_POST['medal'];
	$discipline = $_POST['discipline'];

	$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');
	if (!$conn) {
	  $e = oci_error();
	  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}


	$get_event = oci_parse(
	  $conn, "SELECT * FROM Events e WHERE e.olympics = '" . $olympics . "' AND e.disciplines = '" . $discipline . "' AND e.sport = '" . $sport . "'"
	);

	if (!$get_event) {
	  $e = oci_error($conn);
	  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$r = oci_execute($get_event);
	if (!$r) {
	  $e = oci_error($get_event);
	  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$nb_event = oci_fetch_all($get_event, $res);
	oci_free_statement($get_event);

	if($nb_event != 1) {
		$set_event = oci_parse(
			$conn, "INSERT INTO Events (disciplines, olympics, sport) VALUES ('" . $discipline . "', '" . $olympics . "', '" . $sport . "')"
		);

		if (!$set_event) {
			$e = oci_error($conn);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$r = oci_execute($set_event);
		if (!$r) {
			$e = oci_error($set_event);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		oci_free_statement($set_event);
	}

	//The aid exists
	$get_part = oci_parse(
		$conn, "SELECT * FROM Participants p WHERE p.aid = " . $aid . " AND p.olympics = '" . $olympics . "' AND p.sport = '" . $sport . "' AND p.country = '" . $country . "'"
	);

	if (!$get_part) {
	  $e = oci_error($conn);
	  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$r = oci_execute($get_part);
	if (!$r) {
	  $e = oci_error($get_part);
	  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$nb_part = oci_fetch_all($get_part, $res);
	oci_free_statement($get_part);
	if($nb_part >= 1) {
		if($medal === "Gold medal" || $medal === "Silver medal" || $medal === "Bronze medal") {
			
			$stid = oci_parse(
			  $conn, "INSERT INTO Medals (aid, country, olympics, sport, disciplines, medal)
			  VALUES('" . $aid . "', '" . $country . "', '" . $olympics . "', '" . $sport . "', '" . $discipline . "', '" . $medal . "')"
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
		}
	}

	oci_close($conn);
}
header('Location: ../index.php?p=insert');
?>

				