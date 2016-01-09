<?php
if(!isset($isReferencing)) header('Location: index.php');
$info = array('guest', 'catering', 'menu_item', 'transaction');
if(isset($_GET['a'])){
	$about=$_GET['a'];
	if(in_array($about, $info)){
		include('info_tables/info_'.$about.'.php');
	}
}
?>