<?php
if(!isset($isReferencing)) header('Location: ../index.php');
$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

$query = "SELECT t.tid, t.gid, t.amount, t.prev_balance, t.new_balance, t.timestamp FROM transaction t"; 
$result = pg_query($query); 
if (!$result) { 
	$errormessage = pg_last_error(); 
	$msg=2; 
	exit(); 
} 

$debit_table = array();
$credit_table = array();
$num_results = 0;
while ($row = pg_fetch_row($result)) {
	if($row[2]>0) {
		array_push($debit_table, $row);
	} else {
		array_push($credit_table, $row);
	}
  $num_results++;
}

echo '<span class="label label-info">'.$num_results.' results found</span>&nbsp;';

;

?>

<div class="tabbable">
  <ul class="nav nav-tabs">
	<li class="active"><a href="#debit" data-toggle="tab">Debit transaction</a></li>
	<li class="inactive"><a href="#credit" data-toggle="tab">Credit transactions</a></li>
  </ul>
  <div class="tab-content">
	<div class="tab-pane active" id="debit">
	  <table class="table table-striped">
		  <thead>
			<tr>
			  <th>ID</th>
			  <th>Guest ID</th>
			  <th>Amount</th>
			  <th>Previous balance</th>
			  <th>New balance</th>
			  <th>Timestamp</th>
			  <th>Show more</th>
			</tr>
		  </thead>

		  <?php
		  echo "<tbody>\n";
		  while (!empty($debit_table)) {
			$row = array_shift($debit_table);
			echo "<tr>\n";
			foreach ($row as $item) {
			  echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
			}
			echo "<td><a href='index.php?p=info&amp;a=transaction&amp;id=" . $row[0] . "' class='player-link' data-id='" . $row[0] . "' data-task='more'><i class='fa fa-plus'></i></a></td>";
			echo "</tr>\n";
		  }
		  echo "</tbody>\n";
		  ?>
		</table>
	</div>
	<div class="tab-pane inactive" id="credit">
	  <table class="table table-striped">
		  <thead>
			<tr>
			  <th>ID</th>
			  <th>Guest ID</th>
			  <th>Amount</th>
			  <th>Previous balance</th>
			  <th>New balance</th>
			  <th>Timestamp</th>
			  <th>Show more</th>
			</tr>
		  </thead>

		  <?php
		  echo "<tbody>\n";
		  while (!empty($credit_table)) {
			$row = array_shift($credit_table);
			echo "<tr>\n";
			foreach ($row as $item) {
			  echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
			}
			echo "<td><a href='index.php?p=info&amp;a=transaction&amp;id=" . $row[0] . "' class='player-link' data-id='" . $row[0] . "' data-task='more'><i class='fa fa-plus'></i></a></td>";
			echo "</tr>\n";
		  }
		  echo "</tbody>\n";
		  ?>
		</table>
	</div>
  </div>
</div>

<?php

pg_close()
?>