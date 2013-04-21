<h3>F. List names of all athletes who competed for more than one country.</h3>

<pre>
select a.name
from athletes a
where a.aid in (select aid
			from (select distinct p.aid, p.country
				from participants p)
			group by aid
			having count(*) > 1)
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
		where a.aid in (select aid
					from (select distinct p.aid, p.country
						from participants p)
					group by aid
					having count(*) > 1)");
	        
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