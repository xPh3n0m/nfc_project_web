<?php
$header = 'N. List all nations whose first medal was gold, all nations whose first medal was silver and all nations 
whose first medal was bronze. If nation won more than one medal at the first Olympics it won a medal, 
consider that it won the “shinier” medal first. For example if a country didn’t win any medals before 
games in 1960 and then it won a gold and a bronze, then its first medal is a gold.';

$sql = 'select country, olympics, medal
	from (select m.country, m.olympics, m.medal, row_number() over (partition by m.country order by m.olympics, case m.medal when \'Gold medal\' then 1 when \'Silver medal\' then 2 else 3 end asc) as n
	from medals m)
	where n=1';

$columns = array('Country', 'Olympics', 'Medal');
?>