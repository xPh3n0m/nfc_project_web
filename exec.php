<header class="jumbotron subhead" id="overview">
  <h2>Execute a query</h2>
</header>
<form class="form-horizontal">
  <fieldset>
    <legend>Type in your query you would like to execute</legend>
    <div class="control-group">
      <label class="control-label">Query type</label>
      <div class="controls">
        <label class="radio">
          <input type="radio" name="query_type" id="select" value="select" checked="">
          Select query
        </label>
        <label class="radio">
          <input type="radio" name="query_type" id="insert" value="insert">
          Insert query
        </label>
        <label class="radio">
          <input type="radio" name="query_type" id="delete" value="delete">
          Delete query
        </label>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="query">Query</label>
      <div class="controls">
        <textarea name="query" id="query" cols="30" rows="10"></textarea>
      </div>
    </div>
    <div class="form-actions">
      <button id="btn-query" type="submit" class="btn btn-primary">Execute query</button>
      <button class="btn">Cancel</button>
    </div>
  </fieldset>
</form>
<article id="data">
  
</article>