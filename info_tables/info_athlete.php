<?php
if(!isset($isReferencing)) header('Location: ../index.php');
if(!isset($_GET['id'])) header('Location: index.php');
$aid = $_GET['id'];

$conn = oci_connect('db2013_g14', 'gwathivin', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

if (!$conn) {
  $e = oci_error();
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

?>


<header class="jumbotron subhead" id="overview">

  <?php

  $stid = oci_parse($conn, "SELECT DISTINCT a.name as aname
    FROM athletes a
    WHERE a.aid = " . $aid);

  if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }

  $r = oci_execute($stid);
  if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }

  $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
  echo "<h2>" . $row['ANAME'] . "</h2>";

  ?>
</header>


<!-- NUMBER OF PARTICIPATIONS -->
<?php

$stid = oci_parse($conn, "SELECT COUNT(DISTINCT p.olympics) as count_part
  FROM athletes a, participants p
  WHERE a.aid = " . $aid . "
  AND a.aid = p.aid");

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
echo "<p>Number of participations to Olympic Games: " . $row['COUNT_PART'] . "</p>";

?>

<!-- NUMBER OF MEDALS -->
<?php

$stid = oci_parse($conn, "SELECT COUNT(m.medal) as count_medals
  FROM athletes a, medals m
  WHERE a.aid = " . $aid . "
  AND a.aid = m.aid");

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
echo "<p>Number of medals: " . $row['COUNT_MEDALS'] . "</p>";

?>

<!-- NUMBER OF GOLD MEDALS -->
<?php

$stid = oci_parse($conn, "SELECT COUNT(m.medal) as count_medals
  FROM athletes a, medals m
  WHERE a.aid = " . $aid . "
  AND m.medal = 'Gold medal'
  AND a.aid = m.aid");

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
echo "<p>Number of Gold medals: " . $row['COUNT_MEDALS'] . "</p>";

?>

<!-- NUMBER OF SILVER MEDALS -->
<?php

$stid = oci_parse($conn, "SELECT COUNT(m.medal) as count_medals
  FROM athletes a, medals m
  WHERE a.aid = " . $aid . "
  AND m.medal = 'Silver medal'
  AND a.aid = m.aid");

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
echo "<p>Number of Silver medals: " . $row['COUNT_MEDALS'] . "</p>";

?>

<!-- NUMBER OF BRONZE MEDALS -->
<?php

$stid = oci_parse($conn, "SELECT COUNT(m.medal) as count_medals
  FROM athletes a, medals m
  WHERE a.aid = " . $aid . "
  AND m.medal = 'Bronze medal'
  AND a.aid = m.aid");

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
echo "<p>Number of Bronze medals: " . $row['COUNT_MEDALS'] . "</p>";

?>

<div class="tabbable">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#participation" data-toggle="tab">Participation</a></li>
    <li><a href="#medals" data-toggle="tab">Medals</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="participation">

      <table class="table table-striped">
        <thead>
          <tr>
            <th>Olympics</th>
            <th>Host Country</th>
            <th>Host City</th>
            <th>Country</th>
            <th>Sport</th>
            <th>Remove</th>
          </tr>
        </thead>

        <?php

        $stid = oci_parse($conn, "SELECT DISTINCT p.olympics, g.host_country, g.host_city, p.country, p.country, p.sport
         FROM athletes a, participants p, games g
         WHERE a.aid = " . $aid . "
         AND a.aid = p.aid
         AND g.name = p.olympics
         ORDER BY p.olympics DESC");

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
          echo "<td><a href='remove/remove_participation.php?aid=" .
          $aid . "&amp;c=" . $row['COUNTRY'] . "&amp;g=" . $row['OLYMPICS'] . "&amp;s=" . $row['SPORT'] .
          "'><i class='icon-remove'></i></a></td>\n";
          echo "</tr>\n";
        }
        echo "</tbody>\n";

        ?>
      </table>

    </div>
    <div class="tab-pane" id="medals">

      <table class="table table-striped">
        <thead>
          <tr>
            <th>Olympics</th>
            <th>Country</th>
            <th>Sport</th>
            <th>Discipline</th>
            <th>Medal</th>
            <th>Remove</th>
          </tr>
        </thead>

        <?php

        $stid = oci_parse($conn, "SELECT DISTINCT p.olympics, p.country, p.country, p.sport, m.disciplines, m.medal
          FROM athletes a, participants p, medals m
          WHERE a.aid = " . $aid . "
          AND a.aid = p.aid
          AND m.aid = a.aid
          AND m.olympics = p.olympics
          AND m.sport = p.sport
          ORDER BY p.olympics DESC");


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
          echo "<td><a href='remove/remove_medal.php?aid=" .
          $aid . "&amp;g=" . $row['OLYMPICS'] . "&amp;c=" . $row['COUNTRY'] . "&amp;s=" . $row['SPORT'] . "&amp;d=" . $row['DISCIPLINES'] .
          "'><i class='icon-remove'></i></a></td>\n";
          echo "</tr>\n";
        }
        echo "</tbody>\n";

        ?>
      </table>

    </div>
  </div>
</div>

<?php 
oci_free_statement($stid);
oci_close($conn);
?>