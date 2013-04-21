<?php
$keyword='';
$isKeywordSet=False;
if(isset($_POST['keyword'])){
  $keyword=$_POST['keyword'];
  $isKeywordSet=True;
}
?>

<header class="jumbotron subhead" id="overview">
  <h2>Search</h2>
  <form class="form-horizontal" action="index.php?page=search" method="post">
    <fieldset>
      <legend>Query the database with a keyword</legend>
      <div class="control-group">
        <label class="control-label" for="keyword">Keyword <b style="color:red;">*</b></label>
        <div class="controls">
          <input type="text" class="input-xlarge" name="keyword" value="<?php echo $keyword; ?>">
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
  $searchkey = $_POST['keyword'];
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
        <?php include('search_tables/athletes_table.php'); ?>
      </div>
        <div class="tab-pane" id="olympics">
        <?php include('search_tables/olympics_table.php'); ?>
        </div>
        <div class="tab-pane" id="countries">
        <?php include('search_tables/countries_table.php'); ?>
        </div>
    </div>
</div>

<?php
if(!$isKeywordSet){
  echo '-->';
}
?>