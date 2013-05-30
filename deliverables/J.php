<?php
$header = 'J. For each sport, list the 3 nations which have won the most medals';

$sql = 'select *
from (select m.sport, m.country, row_number() over (partition by m.sport order by count(*) desc) as rank
   	from (select distinct medal, olympics, country, sport, disciplines from medals) m
   	group by m.sport, m.country)
where rank <= 3
order by sport asc, rank asc';

$columns = array('Sport', 'Country', 'Rank');
?>