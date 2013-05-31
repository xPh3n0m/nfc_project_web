<?php
//if(!isset($isReferencing)) header('Location: ../index.php');
$aid = $_GET['aid'];
$country = $_GET['country'];
$olympics = "2012 Summer Olympics";
$sport = $_GET['sport'];

$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

if (!$conn) {
  $e = oci_error();
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$get_aid = oci_parse(
  $conn, "SELECT a.aid FROM Athletes a WHERE a.aid = " . $aid
);

if (!$get_aid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($get_aid);
if (!$r) {
  $e = oci_error($get_aid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$nb_aid = oci_fetch_all($get_aid, $res);
oci_free_statement($get_aid);

if($nb_aid == 1) {
	//The aid exists
	$get_country = oci_parse(
  		$conn, "SELECT c.name FROM Countries c WHERE c.name = '" . $country . "'"
	);

	if (!$get_country) {
	  $e = oci_error($conn);
	  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$r = oci_execute($get_country);
	if (!$r) {
	  $e = oci_error($get_country);
	  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$nb_country = oci_fetch_all($get_country, $res);
	oci_free_statement($get_country);

	if($nb_country == 1) {
		//The country exists
		$get_olympics = oci_parse(
	  		$conn, "SELECT g.name FROM Games g WHERE g.name = '" . $olympics . "'"
		);

		if (!$get_olympics) {
		  $e = oci_error($conn);
		  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$r = oci_execute($get_olympics);
		if (!$r) {
		  $e = oci_error($get_olympics);
		  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$nb_olympics = oci_fetch_all($get_olympics, $res);
		oci_free_statement($get_olympics);

		if($nb_olympics == 1) {
			//The olympics exists
			$get_sports = oci_parse(
		  		$conn, "SELECT s.name FROM Sports s WHERE s.name = '" . $sport . "'"
			);

			if (!$get_sports) {
			  $e = oci_error($conn);
			  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			$r = oci_execute($get_sports);
			if (!$r) {
			  $e = oci_error($get_sports);
			  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			$nb_sports = oci_fetch_all($get_sports, $res);
			oci_free_statement($get_sports);

			if($nb_sports == 1) {
				echo "OKOK";

				$stid = oci_parse(
				  $conn, "INSERT INTO Participants (aid, country, olympics, sport)
				  VALUES('" . $aid . "', '" . $country . "', '" . $olympics . "', '" . $sport . "')" 
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
	}

}

oci_close($conn);

//header('Location: ../index.php?p=insert');
?>