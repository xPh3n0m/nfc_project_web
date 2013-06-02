<?php
if(!isset($isReferencing)) header('Location: index.php');
$query='';
$isQuerySet=False;
$keyword='';
$isKeywordSet=False;
if(isset($_GET['k'])){
  if($_GET['k']!=''){
    $keyword=$_GET['k'];
    $isKeywordSet=True;
  }
}elseif(isset($_POST['q'])){
  if($_POST['q']!=''){
    $query=$_POST['q'];
    $isQuerySet = True;
  }
}

// This function ensures that entered fields are correctly restored
function restore_fields(){
  $keys = array('p');
  foreach($keys as $name) {
    if(!isset($_GET[$name])) {
      continue;
    }
    $value = htmlspecialchars($_GET[$name]);
    $name = htmlspecialchars($name);
    echo '<input type="hidden" name="'. $name .'" value="'. $value .'">';
  }
}
?>

<header class="jumbotron subhead" id="overview">
  <h2>Search</h2>
  <div class="tabbable">
    <ul class="nav nav-pills">
      <li class="disabled"><a href="#">Input method:</a></li>
      <li <?php if(!$isQuerySet) echo "class=\"active\"" ?>><a href="#keyword" data-toggle="tab">Keyword</a></li>
      <li <?php if($isQuerySet) echo "class=\"active\"" ?>><a href="#query" data-toggle="tab">Query</a></li>
    </ul>
    <ul class="tab-content">
      <li class="tab-pane <?php if(!$isQuerySet) echo "active"; ?>" id="keyword">
        <form class="form-horizontal" action="index.php?p=search" method="get">
          <?php restore_fields(); ?>
          <fieldset>
            <legend>Search the database with a keyword</legend>
            <div class="control-group">
              <label class="control-label" for="k">Keyword <b style="color:red;">*</b></label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="k" id="k" value="<?php echo $keyword; ?>">
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" id="btn-search" class="btn btn-primary">Search</button>
            </div>
          </fieldset>
        </form>

        <?php
        if(!$isKeywordSet){
          echo '<!--';
        } else {
          $searchkey = $_GET['k'];
        }
        ?>

        <div class="tabbable">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#athlete" data-toggle="tab">Athletes</a></li>
            <li><a href="#olympics" data-toggle="tab">Olympics</a></li>
            <li><a href="#countries" data-toggle="tab">Countries</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="athlete">
              <?php if($isKeywordSet) include('search_tables/athletes_table.php'); ?>
            </div>
            <div class="tab-pane" id="olympics">
              <?php if($isKeywordSet) include('search_tables/olympics_table.php'); ?>
            </div>
            <div class="tab-pane" id="countries">
              <?php if($isKeywordSet) include('search_tables/countries_table.php'); ?>
            </div>
          </div>
        </div>

        <?php
        if(!$isKeywordSet){
          echo '-->'; 
        }
        ?>

      </li>
      <li class="tab-pane <?php if($isQuerySet) echo "active"; ?>" id="query">
        <form class="form-horizontal" action="index.php?p=search" method="post">
          <?php restore_fields(); ?>
          <fieldset>
            <legend>Here you can type a search query</legend>
            <div class="control-group">
              <label class="control-label" for="querytext">Query:</label>
              <div class="controls">
                <textarea class="field span6" name="q" id="querytext" rows="10" placeholder="ex:                                                                                                                                SELECT *                                                                                                                          FROM swagtable t, bazing a                                                                                                         WHERE t.hisis > a.wesome                                                                                          "
                  ><?php echo $query; ?></textarea>
              </div>
            </div>
            <div class="form-actions">
              <button id="btn-query" type="submit" class="btn btn-primary">Run query</button>
            </div>
          </fieldset>
        </form>

        <?php
        if($isQuerySet){
          
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

          // execute query
          $stid = oci_parse($conn, $query);

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
          // end execute query

          $table = array();
          $num_results = 0;
          while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            array_push($table, $row);
            $num_results++;
          }

          echo '<span class="label label-info">'.$num_results.' results found</span>&nbsp;';
          echo '<span class="label label-success">in '.number_format($totaltime, 3).' seconds</span>';

          echo "<table class='table table-striped'><thead><tr><th>&nbsp;</th></tr></thead><tbody>\n";
          while (!empty($table)) {
            $row = array_shift($table);
            echo "<tr>\n";
            foreach ($row as $item) {
              echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
            }
            echo "</tr>\n";
          }
          echo "</tbody></table>\n";

          oci_free_statement($stid);
          oci_close($conn);

        }
        ?>
      </li>
    </ul>
  </div>
</header>