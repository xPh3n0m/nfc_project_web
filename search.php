<?php
$keyword='';
$isKeywordSet=False;
if(isset($_GET['k'])){
  if($_GET['k']!=''){
    $keyword=$_GET['k'];
    $isKeywordSet=True;
  }
}
?>

<header class="jumbotron subhead" id="overview">
  <h2>Search</h2>
  <form class="form-horizontal" action="index.php?p=search" method="get">
    <?php
    $keys = array('p');
    foreach($keys as $name) {
      if(!isset($_GET[$name])) {
        continue;
      }
      $value = htmlspecialchars($_GET[$name]);
      $name = htmlspecialchars($name);
      echo '<input type="hidden" name="'. $name .'" value="'. $value .'">';
    }
    ?>
    <fieldset>
      <legend>Query the database with a keyword</legend>
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
</header>

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