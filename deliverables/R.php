<?php
$header = 'R. For all individual sports, compute the most top 10 countries according to their success score. Success
score of a country is sum of success points of all its medalists: gold medal is worth 3 points, silver 2
points, and bronze 1 point. Shared medal is worth half the points of the non-shared medal.';

$sql = 'select *
from (select t.country, sum(t.medal/t.n) as point
      from (select case medal when '\Gold medal\' then 3 when \'Silver medal\' then 2 else 1 end as medal, olympics, aid, sport, disciplines, country, 2 as n
            from (select m.medal, m.olympics, m.aid, m.sport, m.disciplines, m.country
                  from medals m
                  where (m.aid, m.country, m.olympics, m.sport) in (select aid, country, olympics, sport
                                                                    from participants
                                                                    where team_name is null))
            where (medal, olympics, sport, disciplines) in (select medal, olympics, sport, disciplines from medals group by medal, olympics, sport, disciplines having count(*)>1)
            union
            select case medal when \'Gold medal\' then 3 when \'Silver medal\' then 2 else 1 end as medal, olympics, aid, sport, disciplines, country, 1
            from (select m.medal, m.olympics, m.aid, m.sport, m.disciplines, m.country
                  from medals m
                  where (m.aid, m.country, m.olympics, m.sport) in (select aid, country, olympics, sport
                                                                    from participants
                                                                    where team_name is null))
            where (medal, olympics, sport, disciplines) in (select medal, olympics, sport, disciplines from medals group by medal, olympics, sport, disciplines having count(*)=1)) t
      group by t.country
      order by point desc)
where rownum <=10';

$columns = array('Country', 'Point');
?>