<h3>E. List all cities which hosted the Olympics more than once.</h3>

<pre>
select distinct g.host_city
from games g
group by g.host_city
having count(*) > 1
</pre>

<table class="table table-striped">
  <thead>
    <tr>
      <th>City</th>
    </tr>
  </thead>

	<?php

	$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

	$stid = oci_parse($conn, "select distinct g.host_city
		from games g
		group by g.host_city
		having count(*) > 1");
	        
	if (!$stid) {
	    $e = oci_error($conn);
	    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$r = oci_execute($stid);
	if (!$r) {
	    $e = oci_error($stid);
	    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$table = array();
	$num_results = 0;
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		array_push($table, $row);
	    $num_results++;
	}

	echo '<span class="label label-info">'.$num_results.' results found</span>';

	echo "<tbody>\n";
	while (!empty($table)) {
		$row = array_shift($table);
	      echo "<tr>\n";
	      foreach ($row as $item) {
	        echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
	      }
	      echo "</tr>\n";
	}
	echo "</tbody>\n";

	oci_free_statement($stid);
	oci_close($conn);
	?>
</table>