<?php
$header = 'F. List names of all athletes who competed for more than one country.';

$sql = 'select a.name
from athletes a
where a.aid in (select aid
			from (select distinct p.aid, p.country
				from participants p)
			group by aid
			having count(*) > 1)';

$columns = array('Name');
?>