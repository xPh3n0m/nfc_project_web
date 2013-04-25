<?php
$header = 'C. For each country print the place where it won its first medal.';

$sql = 'select distinct m.country, g.host_city
from medals m, games g
where g.name= (select min(m2.olympics)
                from medals m2
                where m2.country = m.country)
order by m.country';

$columns = array('Country', 'City');
?>