<h3>G. For each Olympic Games print the name of the country with the most participants.</h3>

<pre>
select p.olympics, p.country,count(*)
from participants p
group by p.olympics, p.country
having count(*) >= all (select count(*) 
			from participants p2
			where p.olympics = p2.olympics
			group by p2.olympics, p2.country)
order by p.olympics desc
</pre>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Game</th>
      <th>Country</th>
      <th># of participants</th>
    </tr>
  </thead>

	<?php

	$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

	$stid = oci_parse($conn, "select p.olympics, p.country,count(*)
		from participants p
		group by p.olympics, p.country
		having count(*) >= all (select count(*) 
					from participants p2
					where p.olympics = p2.olympics
					group by p2.olympics, p2.country)
		order by p.olympics desc");
	        
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