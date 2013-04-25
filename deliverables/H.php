<?php
$header = "H. List all countries which didn’t ever win a medal.";

$sql = "select c.name
from countries c
where c.name not in (select distinct m.country
			from medals m)
order by c.name";

$columns = array("Country");
?>