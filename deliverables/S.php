<?php
$header = 'S. List names of all athletes who won medals both in individual and team sports.';

$sql = 'select a.name
from athletes a
where a.aid in (select distinct m.aid
                from medals m
                where (m.aid, m.country, m.olympics, m.sport) in (select aid, country, olympics, sport
                                                                  from participants
                                                                  where team_name is null)
                intersect
                select distinct m.aid
                from medals m
                where (m.aid, m.country, m.olympics, m.sport) in (select aid, country, olympics, sport
                                                                  from participants
                                                                  where team_name is not null))';

$columns = array('Name');
?>