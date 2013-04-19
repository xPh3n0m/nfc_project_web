
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
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="index.php">Olympics 14</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="index.php">Home</a></li>
              <li class="active"><a href="search.php">Search</a></li>
              <li><a href="modify.php">Modify data</a></li>
              <li><a href="exec.php">Execute query</a></li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Deliverable queries <b class="caret"></b></a>
                <ul class="dropdown-menu" role="menu">
                  <li class="dropdown-submenu">
                    <a href="#">Deliverable 2</a>
                    <ul class="dropdown-menu">
                      <li><a href="#">A</a></li>
                      <li><a href="#">B</a></li>
                      <li><a href="#">C</a></li>
                      <li><a href="#">D</a></li>
                      <li><a href="#">E</a></li>
                      <li><a href="#">F</a></li>
                      <li><a href="#">G</a></li>
                      <li><a href="#">H</a></li>
                    </ul>
                  </li>
                  <li class="divider"></li>
                  <li class="dropdown-submenu">
                    <a href="#">Deliverable 3</a>
                    <ul class="dropdown-menu">
                      <li><a href="#">I</a></li>
                      <li><a href="#">J</a></li>
                      <li><a href="#">K</a></li>
                      <li><a href="#">L</a></li>
                      <li><a href="#">M</a></li>
                      <li><a href="#">N</a></li>
                      <li><a href="#">O</a></li>
                      <li><a href="#">P</a></li>
                      <li><a href="#">Q</a></li>
                      <li><a href="#">R</a></li>
                      <li><a href="#">S</a></li>
                      <li><a href="#">T</a></li>
                      <li><a href="#">U</a></li>
                      <li><a href="#">V</a></li>
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

      <header class="jumbotron subhead" id="overview">
        <h2>Search</h2>
        <form class="form-horizontal">
          <fieldset>
            <legend>Query the database with a keyword</legend>
            <div class="control-group">
              <label class="control-label" for="keyword">Keyword <b style="color:red;">*</b></label>
              <div class="controls">
                <input type="text" class="input-xlarge" id="keyword">
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" id="btn-search" class="btn btn-primary">Search</button>
              <button class="btn">Cancel</button>
            </div>
          </fieldset>
        </form>
      </header>

      <div id="data">
        
      </div>

    </div> <!-- /container -->

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
    $(document).ready(function() {
      /** When coming back from other page **/
      var hash = window.location.hash.slice(1);

      if(hash) {
        $.ajax({
          type: 'POST',
          url: 'search_keyword.php',
          data: { keyword: hash },
          success: function(data) {
            $('#data').html(data);
            console.log('Load was performed.');
          }
        });
      }

      /** Click on submit **/


      /** Click on submit **/
      $('#btn-search').click(function(e) {
        if($('#keyword').val()) {
          window.location.hash = $('#keyword').val();
          $.ajax({
            type: 'POST',
            url: 'search_keyword.php',
            data: { keyword: $('#keyword').val() },
            success: function(data) {
              $('#data').html(data);
              console.log('Load was performed.');
              $('.player-link[data-task="remove"]').click(function(f) {
                var entry_id = $(this).attr('data-id');
                var delTable = $(this).attr('data-table');
                if(confirm('Do you really want to delete the entry with ID: '+entry_id)) {
                  $.ajax({
                    type: 'POST',
                    url: 'delete.php',
                    data: { id: entry_id,
                            table: delTable },
                    success: function(data) {
                      $('#overview').append(data);
                      console.log('Delete was performed.');
                    }
                  });
                }
                f.preventDefault();
              });
            }
          });
        }
        e.preventDefault();
      });
    });
  </script>
  </body>
</html>
