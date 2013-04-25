<?php
$header = 'G. For each Olympic Games print the name of the country with the most participants.';

$sql = 'select p.olympics, p.country,count(*)
from participants p
group by p.olympics, p.country
having count(*) >= all (select count(*) 
			from participants p2
			where p.olympics = p2.olympics
			group by p2.olympics, p2.country)
order by p.olympics desc';

$columns = array('Game', 'Country', '# of participants');
?>