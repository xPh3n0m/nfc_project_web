<?php include("search.php"); ?>

<?php

$searchkey = $_POST['keyword'];

$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, "SELECT DISTINCT a.name, p.country, p.olympics, p.sport 
			  FROM athletes a, participants p 
			  WHERE a.aid = p.aid 
				AND (p.country LIKE '%" . $searchkey . "%'
				OR a.name LIKE '%" . $searchkey . "%'
				OR p.olympics LIKE '%" . $searchkey . "%'
				OR p.sport LIKE '%" . $searchkey . "%')");
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

echo "<table>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?>

<?php include("tables.php"); ?>

