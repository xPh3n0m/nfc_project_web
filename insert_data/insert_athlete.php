<?php
if(!isset($isReferencing)) header('Location: ../index.php');
$athlete_name = $_POST['name'];

echo $athlete_name;

$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

if (!$conn) {
  $e = oci_error();
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse(
  $conn, "INSERT INTO Athletes
  VALUES((SELECT MAX(aid) FROM Athletes A) + 1, '" . $athlete_name . "')" 
);

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

oci_free_statement($stid);
oci_close($conn);

header('Location: ../index.php?p=insert');
?>