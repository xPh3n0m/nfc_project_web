<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>NFC Project</title>
    <link rel="icon" href="img/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Use NFC wristbands in festivals">
    <meta name="author" content="">

    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <script src="http://code.jquery.com/jquery.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    
    <?php
    $isReferencing = True;
    $pages=array("home"=>"active", "info"=>"", "guest"=>"", "catering"=>"", "menu"=>"", "transaction"=>"", "nfc_app"=>"");
    $content='home';

    if(isset($_GET['p'])){
      $page=$_GET['p'];
      if(array_key_exists($page, $pages)){
        $pages['home']='';
        if($page == 'info'){
    			$info_page=$_GET['a'];
    			if($info_page=='menu_item') {
    				$pages['menu']='active';
    			} else {
    				$pages[$info_page]='active';
    			}
        } else {
          $pages[$page]='active';
        }

        if($page=='nfc_app') 
          $content='nfc_app/index';
        else
          $content=$page;
        
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
          <a class="brand" href="index.php">NFC Project</a>
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
              <li class="<?php page('home'); ?>"><a href="index.php?p=home">Home</a></li>
              <li class="<?php page('menu'); ?>"><a href="index.php?p=nfc_app">NFC App</a></li>
      			  <li class="<?php page('guest'); ?>"><a href="index.php?p=guest">Guest</a></li>
      			  <li class="<?php page('catering'); ?>"><a href="index.php?p=catering">Catering companies</a></li>
      			  <li class="<?php page('menu'); ?>"><a href="index.php?p=menu">Menu</a></li>
      			  <li class="<?php page('transaction'); ?>"><a href="index.php?p=transaction">Transactions</a></li>
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
