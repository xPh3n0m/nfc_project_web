<?php
$header = 'O. For all disciplines, compute the country which waited the most between two successive medals.';

$sql = 'select t.sport, t.disciplines, t.country, t.interval
from (select t1.sport, t1.disciplines, t1.country, t2.year-t1.year as interval, row_number() over (partition by t1.sport, t1.disciplines order by t2.year-t1.year desc) as n
      from (select sport, disciplines, country, year, row_number() over (partition by sport, disciplines, country order by year) as n
            from (select distinct m.sport, m.disciplines, m.country, substr(m.olympics,1,4) as year
                  from (select distinct medal, olympics, country, sport, disciplines from medals) m)) t1,
           (select sport, disciplines, country, year, row_number() over (partition by sport, disciplines, country order by year) as n
            from (select distinct m.sport, m.disciplines, m.country, substr(m.olympics,1,4) as year
                  from (select distinct medal, olympics, country, sport, disciplines from medals) m)) t2       
      where t1.sport=t2.sport and t1.disciplines=t2.disciplines and t1.country=t2.country and t1.n+1=t2.n) t
where n=1';

$columns = array('Sport', 'Discipline', 'Country', 'Interval (Years)');
?>

