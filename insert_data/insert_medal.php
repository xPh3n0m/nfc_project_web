<?php
$msg = 3;
if(isset($_POST['aid']) && isset($_POST['country']) && isset($_POST['olympics']) && isset($_POST['sport']) && isset($_POST['medal']) && isset($_POST['discipline'])) {
	$aid = str_replace("'", "''", $_POST['aid']);
	$country = str_replace("'", "''", $_POST['country']);
	$olympics = str_replace("'", "''", $_POST['olympics']);
	$sport = str_replace("'", "''", $_POST['sport']);
	$medal = str_replace("'", "''", $_POST['medal']);
	$discipline = str_replace("'", "''", $_POST['discipline']);
	if($aid != '' && $country != '' && $olympics != '' && $sport != '' && $medal != '' && $discipline != ''){

		$conn = oci_connect('db2013_g014_select', 'selectonly', '//icoracle.epfl.ch:1521/srso4.epfl.ch');
		if (!$conn) {
		  $e = oci_error();
		  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		// alter session
		$stid = oci_parse($conn, "alter session set current_schema=db2013_g14");

		if (!$stid) {
		  $e = oci_error($conn);
		  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$r = oci_execute($stid);
		if (!$r) {
		  $e = oci_error($stid);
		  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		// end alter session

		$get_event = oci_parse(
		  $conn, "SELECT * FROM Events e WHERE e.olympics = '" . $olympics . "' AND e.disciplines = '" . $discipline . "' AND e.sport = '" . $sport . "'"
		);

		if (!$get_event) {
		  $e = oci_error($conn);
		  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$r = oci_execute($get_event);
		if ($r) {
			$nb_event = oci_fetch_all($get_event, $res);
			oci_free_statement($get_event);

			// Create an event
			if($nb_event < 1) {
				$conn0 = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');
				if (!$conn0) {
				  $e = oci_error();
				  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}

				$set_event = oci_parse(
					$conn0, "INSERT INTO Events (disciplines, olympics, sport) VALUES ('" . $discipline . "', '" . $olympics . "', '" . $sport . "')"
				);

				if (!$set_event) {
					$e = oci_error($conn0);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}

				$r = oci_execute($set_event);
				if (!$r) {
					$msg = 10;
				}

				oci_free_statement($set_event);
				oci_close($conn0);
			}

			if($msg != 10){
				$get_part = oci_parse(
					$conn, "SELECT * FROM Participants p WHERE p.aid = " . $aid . " AND p.olympics = '" . $olympics . "' AND p.sport = '" . $sport . "' AND p.country = '" . $country . "'"
				);

				if (!$get_part) {
				  $e = oci_error($conn);
				  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}

				$r = oci_execute($get_part);
				if ($r && oci_fetch_all($get_part, $res) > 0) {
					if($medal === "Gold medal" || $medal === "Silver medal" || $medal === "Bronze medal") {
						$conn0 = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');
						if (!$conn0) {
							$e = oci_error();
							trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}

						$stid = oci_parse(
						  $conn0, "INSERT INTO Medals (aid, country, olympics, sport, disciplines, medal)
						  VALUES('" . $aid . "', '" . $country . "', '" . $olympics . "', '" . $sport . "', '" . $discipline . "', '" . $medal . "')"
						);

						if (!$stid) {
						  $e = oci_error($conn0);
						  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}

						$r = oci_execute($stid);
						if ($r) {
							$msg = 2;
						}

						oci_free_statement($stid);
						oci_close($conn0);
					}
				} else {
					// Participant not found
					$msg = 9;
				}
				oci_free_statement($get_part);
			}
		} else {
			// Event not found
			$msg = 8;
		}

		oci_close($conn);
	}
}
header('Location: ../index.php?p=insert&m=' . $msg);
?>