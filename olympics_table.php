<table class="table table-striped">
      <thead>
        <tr>
          <th>Olympic Game</th>
          <th>Host Country</th>
          <th>Host City</th>
          <th>Remove</th>
          <th>Show more</th>
        </tr>
      </thead>
            
<?php

$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, "SELECT DISTINCT g.name as game, g.host_country, g.host_city
			  FROM games g
				WHERE (g.name LIKE '%" . $searchkey . "%'
				OR g.host_country LIKE '%" . $searchkey . "%'
				OR g.host_city LIKE '%" . $searchkey . "%')");
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
    echo "<td><i class='icon-remove'></i></td>\n";
    echo "<td><a href='search_olympics.php?id=" . $row['GAME'] . "' class='player-link' data-id='" . $row['GAME'] . "' data-task='more'><i class='icon-plus'></i></a></td>\n";
    echo "</tr>\n";
}
echo "</tbody></table>\n";

oci_free_statement($stid);
oci_close($conn);
?>