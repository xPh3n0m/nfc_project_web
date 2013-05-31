<?php
$header = 'A. Print the names of athletes who won medals at both summer and winter Olympics.';

$sql = 'select a.name
from athletes a
where a.aid in (
	select m.aid
	from medals m
	where m.olympics like \'%Summer%\'
intersect
	select m.aid
	from medals m
	where m.olympics like \'%Winter%\'
)';

$columns = array('Name');
?>