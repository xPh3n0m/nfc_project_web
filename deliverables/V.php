<?php
$header = 'V. List top 10 countries according to their success on the events which appear at the Olympics for the first
time. Present the list in the form of the medal table (as described for query I).';

$sql = 'select *
from (select m.country, sum(case m.medal when \'Gold medal\' then 1 else 0 end) as Gold_medal, sum(case m.medal when \'Silver medal\' then 1 else 0 end) as Silver_medal, sum(case m.medal when \'Bronze medal\' then 1 else 0 end) as Bronze_medal, count(*) as Total
	from (select medal, olympics, country, sport, disciplines, rank() over (partition by sport, disciplines order by olympics) as n
	      from (select distinct medal, olympics, country, sport, disciplines from medals) m) m
	where m.n=1
	group by m.country
	order by Gold_medal desc, Silver_medal desc, Bronze_medal desc)
where rownum<11';

$columns = array('Country', 'Gold medal', 'Silver medal', 'Bronze medal', 'Total');
?>