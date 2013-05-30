<?php
$header = 'P. List all events for which all medals are won by athletes from the same country.';

$sql = 'select m.olympics, m.sport, m.disciplines
from medals m
group by m.disciplines, m.olympics, m.sport
having count(distinct m.country) = 1
order by m.olympics, m.sport, m.disciplines';

$columns = array('Games', 'Sport', 'Discipline');
?>