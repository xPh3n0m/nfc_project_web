<?php
$letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V');
$letter='A';
if(isset($_GET['letter'])){
  $letter=strtoupper($_GET['letter']);
  if(!in_array($letter, $letters)){
    $letter='A';
  }
}
include("deliverables/".$letter.".php");
?>