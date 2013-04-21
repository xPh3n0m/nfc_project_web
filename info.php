<?php
$info = array('athlete', 'country', 'game');
if(isset($_GET['about'])){
	$about=$_GET['about'];
	if(in_array($about, $info)){
		include('info_tables/info_'.$about.'.php');
	}
}
?>