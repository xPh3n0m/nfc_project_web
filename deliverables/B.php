<?php
$header = "B. Print the names of gold medalists in sports which appeared only once at the Olympics.";

$sql = "select a.name
from athletes a
where a.aid in (select m.aid 
		from medals m
		where m.medal like 'Gold%'
		and m.sport in (select sport
			from events
			group by sport
			having count(*)=1
		)
	)";

$columns = array("Name");
?>