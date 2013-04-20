<!DOCTYPE html>
<html lang="en">
  <?php include("menu.php"); ?>

<!-- Initialize connection and aid of athlete -->
<?php

$country = $_GET['id'];

$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

?>


<header class="jumbotron subhead" id="overview">

<?php

echo "<h2>" . $country . "</h2>";

?>
</header>

<h3>Participation</h3>
<table class="table table-striped">
      <thead>
        <tr>
          <th>Olympics</th>
          <th>Host Country</th>
          <th>Host City</th>
          <th>Sport</th>
        </tr>
      </thead>

<?php

$stid = oci_parse($conn, "SELECT DISTINCT p.olympics, g.host_country, g.host_city, p.sport
        FROM countries c, participants p, games g
        WHERE c.name = '" . $country . "'
        AND c.name = p.country
        AND g.name = p.olympics
        ORDER BY p.olympics DESC");
        
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
      echo "<tr>\n";
      foreach ($row as $item) {
        echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
      }
      echo "</tr>\n";
}
echo "</tbody></table>\n";

?>


<h3>Medals</h3>
<table class="table table-striped">
      <thead>
        <tr>
          <th>Olympics</th>
          <th>Country</th>
          <th>Sport</th>
          <th>Discipline</th>
          <th>Medal</th>
        </tr>
      </thead>

<?php
/*
$stid = oci_parse($conn, "SELECT DISTINCT p.olympics, p.country, p.country, p.sport, m.disciplines, m.medal
        FROM athletes a, participants p, medals m
        WHERE a.aid = " . $aid . "
        AND a.aid = p.aid
        AND m.aid = a.aid
        AND m.olympics = p.olympics
        AND m.sport = p.sport");

        
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
      echo "<tr>\n";
      foreach ($row as $item) {
        echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
      }
      echo "</tr>\n";
}
echo "</tbody></table>\n";

oci_free_statement($stid);
oci_close($conn);
*/
?>

</html>