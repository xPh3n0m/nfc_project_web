<?php
$header = 'E. List all cities which hosted the Olympics more than once.';

$sql = 'select distinct g.host_city
from games g
group by g.host_city
having count(*) > 1';

$columns = array('City');
?>