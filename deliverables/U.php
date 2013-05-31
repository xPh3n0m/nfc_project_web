<?php
$header = 'U. List names of all events and Olympic Games for which the individual or team has defended a title from
the previous games.';

$sql = 'select t.sport, t.disciplines, t.ol2 as Olympics
from (select t1.sport, t1.disciplines, t2.olympics as ol1, t1.olympics as ol2, t1.n
      from (select e.sport, e.disciplines, e.olympics, row_number() over (partition by e.sport, e.disciplines order by e.olympics) as n
            from events e) t1,
           (select e.sport, e.disciplines, e.olympics, row_number() over (partition by e.sport, e.disciplines order by e.olympics) as n
            from events e) t2
      where t1.sport=t2.sport and t1.disciplines=t2.disciplines and t1.n-1=t2.n) t
where 0=(select count(*) from medals m where m.medal='Gold medal' and m.sport=t.sport and m.disciplines=t.disciplines and m.olympics=t.ol2 and m.aid not in (select distinct aid from medals m where m.medal='Gold medal' and m.sport=t.sport and m.disciplines=t.disciplines and m.olympics=t.ol1))+
        (select count(*) from medals m where m.medal='Gold medal' and m.sport=t.sport and m.disciplines=t.disciplines and m.olympics=t.ol1 and m.aid not in (select distinct aid from medals m where m.medal='Gold medal' and m.sport=t.sport and m.disciplines=t.disciplines and m.olympics=t.ol2))
     and (select count(*) from medals m where m.medal='Gold medal' and m.sport=t.sport and m.disciplines=t.disciplines and m.olympics=t.ol2) >0';

$columns = array('Sport', 'Disciplines', 'Olympics');
?>