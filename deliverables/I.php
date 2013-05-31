<?php
$olympic = 'specific Olympic Games supplied by the user';

$header = 'I. Compute medal table for the '.$olympic.'. Medal table should contain 
country’s IOC code followed by the number of gold, silver, bronze and total medals. It should first be 
sorted by the number of gold, then silvers and finally bronzes.';

$sql = 'select m.country, 
	sum(case m.medal when \'Gold medal\' then 1 else 0 end) as Gold_medal, 
	sum(case m.medal when \'Silver medal\' then 1 else 0 end) as Silver_medal, 
	sum(case m.medal when \'Bronze medal\' then 1 else 0 end) as Bronze_medal, 
	count(*) as Total
from (select distinct medal, olympics, country, sport, disciplines from medals) m
group by m.country
order by Gold_medal desc, Silver_medal desc, Bronze_medal desc';

$columns = array('Country', 'Gold Medals', 'Silver Medals', 'Bronze Medals', 'Total');
?>