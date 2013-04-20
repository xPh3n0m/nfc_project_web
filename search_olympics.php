<!DOCTYPE html>
<html lang="en">
  <?php include("menu.php"); ?>

<header class="jumbotron subhead" id="overview">
        <h2>Olympic Game</h2>
</header>

<table class="table table-striped">

<?php

$game = $_GET['id'];

$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, "SELECT DISTINCT g.name as game, g.host_country, g.host_city
			  FROM games g
			  WHERE g.name = '" . $game . "'");
			  
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

echo "<tbody>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
			echo "<tr><td>Game</td><td>" . $row['GAME'] . "</td></tr>";
			echo "<tr><td>Host Country</td><td>" . $row['HOST_COUNTRY'] . "</td></tr>";
			echo "<tr><td>Host City</td><td>" . $row['HOST_CITY'] . "</td></tr>";
}
echo "</tbody></table>\n";

oci_free_statement($stid);
oci_close($conn);

?>
