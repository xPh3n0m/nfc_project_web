<!DOCTYPE html>
<html lang="en">
  <?php include("menu.php"); ?>


    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.js"></script>

<header class="jumbotron subhead" id="overview">
        <h2>Olympic Game</h2>
</header>

<table class="table table-striped">

<?php

$game = $_GET['id'];

$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, "SELECT DISTINCT g.name as game, g.host_country, g.host_city
			  FROM games g
			  WHERE g.name = '" . $game . "'");
			  
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

echo "<tbody>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
			echo "<tr><td>Game</td><td>" . $row['GAME'] . "</td></tr>";
			echo "<tr><td>Host Country</td><td>" . $row['HOST_COUNTRY'] . "</td></tr>";
			echo "<tr><td>Host City</td><td>" . $row['HOST_CITY'] . "</td></tr>";
}
echo "</tbody></table>\n";

?>

<div class="tabbable">
<ul class="nav nav-tabs">
  <li class="active"><a href="#countries" data-toggle="tab">Medals per country</a></li>
  <li><a href="#medals" data-toggle="tab">Medals</a></li>
  <li><a href="#disciplines" data-toggle="tab">Disciplines</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="countries">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Country</th>
              <th># of medals</th>
            </tr>
          </thead>

          <?php

            $stid = oci_parse($conn, "SELECT *
                FROM (
                        SELECT m.country, count(*) as nb
                        FROM medals m
                        WHERE m.olympics = '" . $game . "'
                        GROUP BY m.country
                )
                ORDER BY nb DESC");
                    
            if (!$stid) {
                $e = oci_error($conn);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }

            $r = oci_execute($stid);
            if (!$r) {
                $e = oci_error($stid);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }

            echo "<tbody>\n";
            while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                  echo "<tr>\n";
                  foreach ($row as $item) {
                    echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
                  }
                  echo "</tr>\n";
            }
            echo "</tbody></table>\n";

            ?>

    </div>

    <!-- MEDALS TAB -->

  <div class="tab-pane" id="medals">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Country</th>
          <th>Discipline</th>
          <th>Sport</th>
          <th>Medal</th>
          <th>Athlete</th>
        </tr>
      </thead>

    <?php

$stid = oci_parse($conn, "SELECT DISTINCT m.country, m.disciplines, m.sport, m.medal, a.name
        FROM medals m, athletes a
        WHERE m.olympics = '" . $game . "'
        AND m.aid = a.aid
        ORDER BY m.country
        ");
        
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

echo "<tbody>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
      echo "<tr>\n";
      foreach ($row as $item) {
        echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
      }
      echo "</tr>\n";
}
echo "</tbody></table>\n";

?>

</div>

        <!-- DISCIPLINES TAB -->
  <div class="tab-pane" id="disciplines">

<table class="table table-striped">
      <thead>
        <tr>
          <th>Sport</th>
          <th>Discipline</th>
        </tr>
      </thead>

<?php

$stid = oci_parse($conn, "SELECT DISTINCT e.sport, e.disciplines
        FROM events e, games g
        WHERE g.name = '" . $game . "'
        AND e.olympics = g.name
        ORDER BY e.sport ASC");
        
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

echo "<tbody>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
      echo "<tr>\n";
      foreach ($row as $item) {
        echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
      }
      echo "</tr>\n";
}
echo "</tbody></table>\n";

?>

</div>
</div>
</div>

<?php 
oci_free_statement($stid);
oci_close($conn);
?>

