<?php
$header = 'D. Print the name of the country which won the most medals in summer Olympics and the country 
which won the most medals in winter Olympics.';

$sql = 'select \'Summer Olympic\', country
from (select m.country, count(*)
	from (select distinct medal, olympics, country, sport, disciplines from medals) m
	where m.olympics like \'%Summer%\'
	group by country
	order by count(*) desc)
where rownum=1
union
select \'Winter Olympic\', country
from (select m.country, count(*)
	from (select distinct medal, olympics, country, sport, disciplines from medals) m
	where m.olympics like \'%Winter%\'
	group by country
	order by count(*) desc)
where rownum=1';

$columns = array('Season', 'Country');
?>