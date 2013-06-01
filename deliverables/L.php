<?php
$header = 'L. List top 10 nations according to their success in team sports. Use average number of medalists for each 
medal awarded to a particular nation.';

$sql = 'select * 
from (select m1.country, m1.nm/m2.nm as medal_per_athletes
	from (select m.country, count(*) as nm
	      from medals m
	      group by m.country) m1,
	     (select m.country, count(*) as nm
	      from (select distinct medal, olympics, country, sport, disciplines from medals) m
	      group by m.country) m2
	where m1.country = m2.country
	order by medal_per_athletes desc)
where rownum<11';

$columns = array('Country', 'Medal per athletes');
?>