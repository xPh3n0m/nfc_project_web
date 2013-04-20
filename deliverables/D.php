<h3>D. Print the name of the country which won the most medals in summer Olympics and the country 
	which won the most medals in winter Olympics.</h3>

<pre>
select 'Summer Olympic', country
from (select m.country, count(*)
		from (select distinct medal, olympics, country, sport, disciplines from medals) m
		where m.olympics like '%Summer%'
		group by country
		order by count(*) desc)
where rownum=1
union
select 'Winter Olympic', country
from (select m.country, count(*)
		from (select distinct medal, olympics, country, sport, disciplines from medals) m
		where m.olympics like '%Winter%'
		group by country
		order by count(*) desc)
where rownum=1
</pre>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Season</th>
      <th>Country</th>
    </tr>
  </thead>

	<?php

	$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

	$stid = oci_parse($conn, "select 'Summer Olympic', country
		from (select m.country, count(*)
				from (select distinct medal, olympics, country, sport, disciplines from medals) m
				where m.olympics like '%Summer%'
				group by country
				order by count(*) desc)
		where rownum=1
		union
		select 'Winter Olympic', country
		from (select m.country, count(*)
				from (select distinct medal, olympics, country, sport, disciplines from medals) m
				where m.olympics like '%Winter%'
				group by country
				order by count(*) desc)
		where rownum=1");
	        
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