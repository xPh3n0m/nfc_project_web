<?php $searchkey = $_POST['keyword']; ?>

<?php include("search.php"); ?>

<div id="data">
<ul class="nav nav-tabs">
  <li><a href="#athlete" data-toggle="tab">Athletes</a></li>
  <li><a href="#olympics" data-toggle="tab">Olympics</a></li>
  <li><a href="#countries" data-toggle="tab">Countries</a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane active" id="athlete">
  
<?php include('athletes_table.php'); ?>

		</div>
  <div class="tab-pane active" id="olympics">
<?php include('olympics_table.php'); ?>
</div>
<div class="tab-pane active" id="countries">
<?php include('countries_table.php'); ?>
</div>

<div class="tab-pane active" id="events">
</div>
</div>
</div>

