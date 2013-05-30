<?php
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
                <input type="text" class="input-xlarge" name="k" value="<?php echo $keyword; ?>">
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
              <label class="control-label" for="query">Query:</label>
              <div class="controls">
                <textarea class="field span6" name="q" id="query" rows="10" placeholder="ex:                                      
                                                                                          SELECT *                                
                                                                                          FROM swagtable t, bazing a               
                                                                                          WHERE t.hisis > a.wesome"></textarea>
              </div>
            </div>
            <div class="form-actions">
              <button id="btn-query" type="submit" class="btn btn-primary">Run query</button>
            </div>
          </fieldset>
        </form>

        <?php
        if($isQuerySet){
          echo $query;
        }
        ?>
      </li>
    </ul>
  </div>
</header>