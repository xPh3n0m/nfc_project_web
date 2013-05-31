<?php
$header = 'Q. For each Olympic Games, list the name of the country which scored the largest percentage of the 
medals.';

$sql = 'select t1.olympics, t2.country, t1.m/t1.s as percentage
from (select t.olympics, max(t.medals) as m, sum(t.medals) as s
      from (select m.olympics, count(*) as medals, row_number() over (partition by m.olympics order by count(*) desc) as n
            from (select distinct medal, olympics, country, sport, disciplines from medals) m
            group by m.olympics, m.country) t
      group by olympics) t1,
     (select m.olympics, m.country, count(*) as medals
      from (select distinct medal, olympics, country, sport, disciplines from medals) m
      group by m.olympics, m.country) t2
where t1.m=t2.medals and t1.olympics=t2.olympics
order by t1.olympics';

$columns = array('Olympics', 'Country', 'Percentage');
?>