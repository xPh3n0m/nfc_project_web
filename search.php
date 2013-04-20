<!DOCTYPE html>
<html lang="en">
  <?php include("menu.php"); ?>

    <div class="container">

      <header class="jumbotron subhead" id="overview">
        <h2>Search</h2>
        <form class="form-horizontal" action="search_keyword.php" method="post">
          <fieldset>
            <legend>Query the database with a keyword</legend>
            <div class="control-group">
              <label class="control-label" for="keyword">Keyword <b style="color:red;">*</b></label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="keyword">
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" id="btn-search" class="btn btn-primary">Search</button>
            </div>
          </fieldset>
        </form>
      </header>

    </div> <!-- /container -->

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>