<!DOCTYPE html>
<html lang="en">
  <?php include("menu.php"); ?>

<header class="jumbotron subhead" id="overview">
        <h2>Country</h2>
</header>

<table class="table table-striped">

<?php

$country = $_GET['id'];

$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, "SELECT DISTINCT c.name, c.ioc_code
			  FROM countries c
			  WHERE c.name = '" . $country . "'");
			  
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
			echo "<tr><td>Country name</td><td>" . $row['NAME'] . "</td></tr>";
			echo "<tr><td>IOC Code</td><td>" . $row['IOC_CODE'] . "</td></tr>";
}
echo "</tbody></table>\n";

oci_free_statement($stid);
oci_close($conn);

?>
