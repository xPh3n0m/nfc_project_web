<h3>B. Print the names of gold medalists in sports which appeared only once at the Olympics.</h3>

<pre>
select a.name
from athletes a
where a.aid in (select m.aid 
		from medals m
		where m.medal like 'Gold%'
		and m.sport in (select sport
			from events
			group by sport
			having count(*)=1
		)
	)
</pre>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Name</th>
    </tr>
  </thead>

	<?php

	$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

	$stid = oci_parse($conn, "select a.name
from athletes a
where a.aid in (select m.aid 
                  from medals m
                  where m.medal like 'Gold%' and m.sport in (select sport
                                                              from events
                                                              group by sport
                                                              having count(*)=1))");
	        
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