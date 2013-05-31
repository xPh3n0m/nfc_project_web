<?php
$header = 'T. List names of all athletes who won gold in team sports, but only won silvers or bronzes individually.';

$sql = 'select a.name
from athletes a
where a.aid in (select distinct m.aid
                from medals m
                where (m.aid, m.country, m.olympics, m.sport) in (select aid, country, olympics, sport
                                                                  from participants
                                                                  where team_name is null)
                      and m.medal like \'Gold%\'
                intersect
                select distinct m.aid
                from medals m
                where (m.aid, m.country, m.olympics, m.sport) in (select aid, country, olympics, sport
                                                                  from participants
                                                                  where team_name is not null)
                      and m.medal not like \'Gold%\')';

$columns = array('Athlete Name');
?>