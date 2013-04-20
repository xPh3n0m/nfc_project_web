<h3>H. List all countries which didn’t ever win a medal.</h3>

<pre>
select c.name
from countries c
where c.name not in (select distinct m.country
			from medals m)
order by c.name
</pre>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Country</th>
    </tr>
  </thead>

	<?php

	$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

	$stid = oci_parse($conn, "select c.name
		from countries c
		where c.name not in (select distinct m.country
					from medals m)
		order by c.name");
	        
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
	echo "</tbody>\n";

	oci_free_statement($stid);
	oci_close($conn);
	?>
</table>