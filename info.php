<?php
if(!isset($isReferencing)) header('Location: index.php');
$info = array('athlete', 'country', 'game');
if(isset($_GET['a'])){
	$about=$_GET['a'];
	if(in_array($about, $info)){
		include('info_tables/info_'.$about.'.php');
	}
}
?>