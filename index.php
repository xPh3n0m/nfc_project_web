
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Olympics 14</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Olympics 14 is a Introduction to Database Systems project from group #14">
    <meta name="author" content="">
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    
    <?php
    $pages=array("home"=>"active", "search"=>"", "info"=>"", "insert"=>"", "exec"=>"", "deliverable"=>"");
    $content='home';

    if(isset($_GET['page'])){
      $page=$_GET['page'];
      if(array_key_exists($page, $pages)){
        if($page == 'info'){
          $pages['search']='active';
        } else {
          $pages[$page]='active';
        }
        $content=$page;
        $pages['home']='';
      }
    }

    function page($which){
      global $pages;
      echo $pages[$which];
    }
    ?>

  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="index.php">Olympics 14</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="<?php page('home'); ?>"><a href="index.php?page=home">Home</a></li>
              <li class="<?php page('search'); ?>"><a href="index.php?page=search">Search</a></li>
              <li class="<?php page('insert'); ?>"><a href="index.php?page=insert">Insert data</a></li>
              <li class="<?php page('exec'); ?>"><a href="index.php?page=exec">Execute query</a></li>
              <li class="dropdown <?php page('deliverable') ?>">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Deliverable queries <b class="caret"></b></a>
                <ul class="dropdown-menu" role="menu">
                  <li class="dropdown-submenu">
                    <a href="#">Deliverable 2</a>
                    <ul class="dropdown-menu">
                      <li><a href="index.php?page=deliverable&letter=A">A</a></li>
                      <li><a href="index.php?page=deliverable&letter=B">B</a></li>
                      <li><a href="index.php?page=deliverable&letter=C">C</a></li>
                      <li><a href="index.php?page=deliverable&letter=D">D</a></li>
                      <li><a href="index.php?page=deliverable&letter=E">E</a></li>
                      <li><a href="index.php?page=deliverable&letter=F">F</a></li>
                      <li><a href="index.php?page=deliverable&letter=G">G</a></li>
                      <li><a href="index.php?page=deliverable&letter=H">H</a></li>
                    </ul>
                  </li>
                  <li class="divider"></li>
                  <li class="dropdown-submenu">
                    <a href="#">Deliverable 3</a>
                    <ul class="dropdown-menu">
                      <li><a href="index.php?page=deliverable&letter=I">I</a></li>
                      <li><a href="index.php?page=deliverable&letter=J">J</a></li>
                      <li><a href="index.php?page=deliverable&letter=K">K</a></li>
                      <li><a href="index.php?page=deliverable&letter=L">L</a></li>
                      <li><a href="index.php?page=deliverable&letter=M">M</a></li>
                      <li><a href="index.php?page=deliverable&letter=N">N</a></li>
                      <li><a href="index.php?page=deliverable&letter=O">O</a></li>
                      <li><a href="index.php?page=deliverable&letter=P">P</a></li>
                      <li><a href="index.php?page=deliverable&letter=Q">Q</a></li>
                      <li><a href="index.php?page=deliverable&letter=R">R</a></li>
                      <li><a href="index.php?page=deliverable&letter=S">S</a></li>
                      <li><a href="index.php?page=deliverable&letter=T">T</a></li>
                      <li><a href="index.php?page=deliverable&letter=U">U</a></li>
                      <li><a href="index.php?page=deliverable&letter=V">V</a></li>
                    </ul>
                  </li>
                </ul>
              </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <?php include($content.'.php'); ?>

    </div> <!-- /container -->

  </body>
</html>
