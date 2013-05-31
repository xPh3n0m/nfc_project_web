<?php
$olympics = array();
if(isset($_POST['o'])){
	$olympic = $_POST['o'];
	$header = 'I. Compute medal table for the '.$olympic.'. Medal table should contain 
	country’s IOC code followed by the number of gold, silver, bronze and total medals. It should first be 
	sorted by the number of gold, then silvers and finally bronzes.';

	$sql = 'select m.country, 
		sum(case m.medal when \'Gold medal\' then 1 else 0 end) as Gold_medal, 
		sum(case m.medal when \'Silver medal\' then 1 else 0 end) as Silver_medal, 
		sum(case m.medal when \'Bronze medal\' then 1 else 0 end) as Bronze_medal, 
		count(*) as Total
	from (select distinct medal, olympics, country, sport, disciplines from medals where olympics = \''.$olympic.'\') m
	group by m.country
	order by Gold_medal desc, Silver_medal desc, Bronze_medal desc';
}
$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');
$stid = oci_parse($conn, "select name from games order by name desc");

if (!$stid) {
	$e = oci_error($conn);
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
	$e = oci_error($stid);
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$olympics_array = "[\"";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$olympics_array = $olympics_array . "\", \"" . $row['NAME'];
}
$olympics_array = $olympics_array . "\"]";

$columns = array('Country', 'Gold Medals', 'Silver Medals', 'Bronze Medals', 'Total');
?>
<form class="form-horizontal" id="I-form" action="index.php?p=deliverable&amp;l=I" method="post">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="olympic">Select an olympic:</label>
			<div class="controls">
				<input type="text" class="input-xlarge required" name="o" id="olympic" placeholder="2012 Summer Olympics"
				autocomplete="off" data-items="10" data-provide="typeahead" data-source='<?php echo $olympics_array; ?>'>
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</div>
	</fieldset>
</form>
<?php if($header) echo "<!--"; ?>
<h3>I. Compute medal table for the specific Olympic Games supplied by the user. Medal table should contain 
country’s IOC code followed by the number of gold, silver, bronze and total medals. It should first be 
sorted by the number of gold, then silvers and finally bronzes.</h3>
<?php if($header) echo "-->"; ?>
<script src="/twitter-bootstrap/twitter-bootstrap-v2/docs/assets/js/bootstrap-typeahead.js"></script>