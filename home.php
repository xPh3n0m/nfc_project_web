<?php
if(!isset($isReferencing)) header('Location: index.php');
?>
<h2>Welcome to the NFC Project!</h2>
<p>
This web front-end application serves the purpose of interacting with NFC wristbands .<br>
Thierry Nyfeler, January 2016
</p>

 <p>
  <applet archive="applets/RegistrationApp.jar" width="740" height="400"></applet>
 </p>
 <p>
  <object codetype="application/java" archive="applets/RegistrationApp.jar" width="740" height="400"></object>
 </p>