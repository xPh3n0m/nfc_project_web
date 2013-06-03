<?php
$msg = 3;
if(isset($_POST['aid']) && isset($_POST['country']) && isset($_POST['olympics']) && isset($_POST['sport'])) {
	$aid = str_replace("'", "''", $_POST['aid']);
	$country = str_replace("'", "''", $_POST['country']);
	$olympics = str_replace("'", "''", $_POST['olympics']);
	$sport = str_replace("'", "''", $_POST['sport']);
	if($aid != '' && $country != '' && $olympics != '' && $sport != ''){

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

		$get_aid = oci_parse(
		  $conn, "SELECT a.aid FROM Athletes a WHERE a.aid = " . $aid
		);

		if (!$get_aid) {
		  $e = oci_error($conn);
		  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$r = oci_execute($get_aid);
		if ($r && oci_fetch_all($get_aid, $res) == 1) {
			//The aid exists
			$get_country = oci_parse(
		  		$conn, "SELECT c.name FROM Countries c WHERE c.name = '" . $country . "'"
			);

			if (!$get_country) {
			  $e = oci_error($conn);
			  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			$r = oci_execute($get_country);
			if ($r && oci_fetch_all($get_country, $res) == 1) {
				//The country exists
				$get_olympics = oci_parse(
					$conn, "SELECT g.name FROM Games g WHERE g.name = '" . $olympics . "'"
				);

				if (!$get_olympics) {
				  $e = oci_error($conn);
				  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}

				$r = oci_execute($get_olympics);
				if ($r && oci_fetch_all($get_olympics, $res) == 1) {
					//The olympics exists
					$get_sports = oci_parse(
				  		$conn, "SELECT s.name FROM Sports s WHERE s.name = '" . $sport . "'"
					);

					if (!$get_sports) {
					  $e = oci_error($conn);
					  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					}

					$r = oci_execute($get_sports);
					if ($r && oci_fetch_all($get_sports, $res) == 1) {
						$conn0 = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

						if (!$conn0) {
						  $e = oci_error();
						  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}

						if(isset($_POST['teamname'])) {
							$teamname = str_replace("'", "''", $_POST['teamname']);

							$stid = oci_parse(
						  	$conn0, "INSERT INTO Participants (aid, country, olympics, sport, team_name)
						  		VALUES('" . $aid . "', '" . $country . "', '" . $olympics . "', '" . $sport . "', '" . $teamname . "')" 
							);
						} else {
							$stid = oci_parse(
							$conn0, "INSERT INTO Participants (aid, country, olympics, sport)
							  	VALUES('" . $aid . "', '" . $country . "', '" . $olympics . "', '" . $sport . "')" 
							);
						}

						if ($stid) {
							$r = oci_execute($stid);
							if ($r) {	// Success
								$msg = 1;
							}
						}

						oci_free_statement($stid);
						oci_close($conn0);
					} else {
						// Sport not found
						$msg = 7;
					}
					oci_free_statement($get_sports);
				} else {
					// Olympics not found
					$msg = 6;
				}
				oci_free_statement($get_olympics);
			} else {
				// Country not found
				$msg = 5;
			}
			oci_free_statement($get_country);
		} else {
			// aid not found
			$msg = 4;
		}

		oci_free_statement($get_aid);
		oci_close($conn);
	}
}
header('Location: ../index.php?p=insert&m=' . $msg);
?>