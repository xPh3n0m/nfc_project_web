<?php
$header = 'M. List all Olympians who won medals for multiple nations.';

$sql = 'select a.name
from athletes a
where a.aid in (select distinct m.aid
                from medals m
                group by m.aid
                having count(distinct m.country) > 1)';

$columns = array('Athlete');
?>