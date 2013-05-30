<?php
$header = 'K. Compute which country in which Olympics has benefited the most from playing in front of the home 
crowd. The benefit is computed as the number of places it has advanced its position on the medal table 
compared to its average position for all Olympic Games. Repeat this computation separately for winter 
and summer games.';

$sql = 'select *
from (select g.name, g.host_country, r.rank, a.avg as avg_rank
      from (select m.olympics, m.country, row_number() over (partition by m.olympics order by count(*) desc) as rank
            from (select distinct medal, olympics, country, sport, disciplines from medals) m
            where olympics like \'%Summer%\'
            group by m.olympics, m.country
            order by m.olympics asc, rank asc) r,
            (select country, avg(rank) as avg
            from (select m.olympics, m.country, row_number() over (partition by m.olympics order by count(*) desc) as rank
            from (select distinct medal, olympics, country, sport, disciplines from medals) m
            where olympics like \'%Summer%\'
            group by m.olympics, m.country
            order by m.olympics asc, rank asc)
            group by country) a, games g
      where r.olympics = g.name and g.host_country = r.country and a.country = g.host_country and olympics like \'%Summer%\'
      union
      select g.name, g.host_country, r.rank, a.avg as avg_rank
      from (select m.olympics, m.country, row_number() over (partition by m.olympics order by count(*) desc) as rank
            from (select distinct medal, olympics, country, sport, disciplines from medals) m
            where olympics like \'%Winter%\'
            group by m.olympics, m.country
            order by m.olympics asc, rank asc) r,
            (select country, avg(rank) as avg
            from (select m.olympics, m.country, row_number() over (partition by m.olympics order by count(*) desc) as rank
            from (select distinct medal, olympics, country, sport, disciplines from medals) m
            where olympics like \'%Winter%\'
            group by m.olympics, m.country
            order by m.olympics asc, rank asc)
            group by country) a, games g
      where r.olympics = g.name and g.host_country = r.country and a.country = g.host_country and olympics like \'%Winter%\')
order by (avg_rank-rank) desc';

$columns = array('Olympics', 'Host Country', 'Rank', 'Average rank');
?>