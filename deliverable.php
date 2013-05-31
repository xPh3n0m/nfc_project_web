<?php
if(!isset($isReferencing)) header('Location: index.php');
$no_resultnb = array('D');
$letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V');
$letter='A';
if(isset($_GET['l'])){
	$letter=strtoupper($_GET['l']);
	if(!in_array($letter, $letters)){
		$letter='A';
	}
}

$header = NULL;
$sql = NULL;
$columns = NULL;

// Here you need to provide $header, $sql and $columns.
include("deliverables/".$letter.".php");

if($header && $sql && $columns){
	echo "<h3>".$header."</h3>";
	echo "<pre>\n".$sql."\n</pre>";

	$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');
	$stid = oci_parse($conn, $sql);
	
	if (!$stid) {
		$e = oci_error($conn);
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime;
	$r = oci_execute($stid);
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$endtime = $mtime; 
	$totaltime = ($endtime - $starttime);

	if (!$r) {
		$e = oci_error($stid);
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$table = array();
	$num_results = 0;
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		array_push($table, $row);
		$num_results++;
	}

	if(!in_array($letter, $no_resultnb)){
		echo '<span class="label label-info">'.$num_results.' results found</span>&nbsp;';
	}
	echo '<span class="label label-success">in '.number_format($totaltime, 3).' seconds</span>';

	echo '<table class="table table-striped">';
	echo '	<thead>';
	echo '	<tr>';
	foreach ($columns as $col) {
		echo "<th>".$col."</th>\n";
	}
	echo '	</tr>';
	echo '	</thead>';

	echo "<tbody>\n";
	while (!empty($table)) {
		$row = array_shift($table);
			echo "<tr>\n";
			foreach ($row as $item) {
			echo "	<td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
			}
			echo "</tr>\n";
	}
	echo "</tbody>\n";

	oci_free_statement($stid);
	oci_close($conn);
	echo "</table>";
}
?>