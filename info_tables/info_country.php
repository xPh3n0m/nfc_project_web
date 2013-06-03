<?php
if(!isset($isReferencing)) header('Location: ../index.php');
if(!isset($_GET['id'])) header('Location: index.php');
$country = str_replace("'", "''", $_GET['id']);

$conn = oci_connect('db2013_g014_select', 'selectonly', '//icoracle.epfl.ch:1521/srso4.epfl.ch');

if (!$conn) {
  $e = oci_error();
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// alter session
$stid = oci_parse($conn, "alter session set current_schema=db2013_g14");

if (!$stid) {
  $e = oci_error($conn);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error($stid);
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
// end alter session

?>


<header class="jumbotron subhead" id="overview">

  <?php

  echo "<h2>" . $country . "</h2>";

  ?>
</header>

<div class="row">
  <div class="span4">
    <table class="table table-bordered">
      <tbody>
        <tr>
          <!-- NUMBER OF PARTICIPATIONS -->
          <?php

          $stid = oci_parse($conn, "SELECT COUNT(DISTINCT p.olympics) as count_part
            FROM countries c, participants p
            WHERE c.name = '" . $country . "'
            AND c.name = p.country");

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
          echo "<td><strong>Number of participations to Olympic Games</strong></td><td>" . $row['COUNT_PART'] . "</td>";

          ?>
        </tr>
        <tr>
          <!-- NUMBER OF MEDALS -->
          <?php

          $stid = oci_parse($conn, "SELECT COUNT(m.medal) as count_medals
            FROM countries c, (SELECT DISTINCT medal, olympics, country, sport, disciplines FROM medals) m
            WHERE c.name = '" . $country . "'
            AND c.name = m.country");

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
          echo "<td><strong>Number of medals</strong></td><td>" . $row['COUNT_MEDALS'] . "</td>";

          ?>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="span4">
    <table class="table table-bordered">
      <tbody>
        <tr>
          <!-- NUMBER OF GOLD MEDALS -->
          <?php

          $stid = oci_parse($conn, "SELECT COUNT(m.medal) as count_medals
            FROM countries c, (SELECT DISTINCT medal, olympics, country, sport, disciplines FROM medals) m
            WHERE c.name = '" . $country . "'
            AND c.name = m.country
            AND m.medal = 'Gold medal'");

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
          echo "<td><strong>Number of Gold medals</strong></td><td>" . $row['COUNT_MEDALS'] . "</td>";

          ?>
        </tr>

        <tr>
          <!-- NUMBER OF SILVER MEDALS -->
          <?php

          $stid = oci_parse($conn, "SELECT COUNT(m.medal) as count_medals
            FROM countries c, (SELECT DISTINCT medal, olympics, country, sport, disciplines FROM medals) m
            WHERE c.name = '" . $country . "'
            AND c.name = m.country
            AND m.medal = 'Silver medal'");

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
          echo "<td><strong>Number of Silver medals</strong></td><td>" . $row['COUNT_MEDALS'] . "</td>";

          ?>
        </tr>

        <tr>
          <!-- NUMBER OF BRONZE MEDALS -->
          <?php

          $stid = oci_parse($conn, "SELECT COUNT(m.medal) as count_medals
            FROM countries c, (SELECT DISTINCT medal, olympics, country, sport, disciplines FROM medals) m
            WHERE c.name = '" . $country . "'
            AND c.name = m.country
            AND m.medal = 'Bronze medal'");

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
          echo "<td><strong>Number of Bronze medals</strong></td><td>" . $row['COUNT_MEDALS'] . "</td>";

          ?>
        </tr>
      </tbody>
    </table>
  </div>
</div>

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
            <th>Sport</th>
          </tr>
        </thead>

        <?php

        $stid = oci_parse($conn, "SELECT DISTINCT p.olympics, g.host_country, g.host_city, p.sport
          FROM countries c, participants p, games g
          WHERE c.name = '" . $country . "'
          AND c.name = p.country
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
          </tr>
        </thead>


        <?php

        $stid = oci_parse($conn, "SELECT DISTINCT p.olympics, p.country, p.sport, m.disciplines, m.medal
          FROM countries c, participants p, medals m
          WHERE c.name = '" . $country . "'
          AND c.name = p.country
          AND m.country = c.name
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
