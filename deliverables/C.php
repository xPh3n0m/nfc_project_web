<h3>C. For each country print the place where it won its first medal.</h3>

<pre>
select distinct m.country, g.host_city
from medals m, games g
where g.name= (select min(m2.olympics)
                from medals m2
                where m2.country = m.country)
order by m.country
</pre>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Country</th>
      <th>City</th>
    </tr>
  </thead>

	<?php

	$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

	$stid = oci_parse($conn, "select distinct m.country, g.host_city
		from medals m, games g
		where g.name= (select min(m2.olympics)
		                from medals m2
		                where m2.country = m.country)
		order by m.country desc");
	        
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
		$row = array_pop($table);
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