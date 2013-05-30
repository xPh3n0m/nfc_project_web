<section class="container content">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#athlete" data-toggle="tab">Athlete</a></li>
      <li class=""><a href="#participation" data-toggle="tab">Participation</a></li>
      <li class=""><a href="#team" data-toggle="tab">Team</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="athlete">
        <form class="form-horizontal" id="player-form" action="insert.php?type=player" method="post">
          <fieldset>
            <legend>Insert an athlete</legend>
            <input type="hidden" value="athlete" name="type">
            <div class="control-group">
              <label class="control-label" for="name">Athlete's full name</label>
              <div class="controls">
                <input type="text" class="input-xxlarge required" name="name" id="name" placeholder="John Doe">
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="tab-pane" id="participation">
        <form class="form-horizontal" action="insert.php?type=Coach" method="POST">
          <fieldset>
            <legend>Insert a participation</legend>
            <input type="hidden" name="type" value="coach">
            <div class="control-group">
              <label class="control-label" for="cid">Coach ID</label>
              <div class="controls">
                <input type="text" class="input-small required" name="cid" id="cid" placeholder="XXXXXXX01">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="firstname">First name</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="firstname" id="firstname">
                <p class="help-block">First name is required!</p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="lastname">Last name</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="lastname" id="lastname">
                <p class="help-block">Last name is required</p>
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save changes</button>
              <button class="btn">Cancel</button>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="tab-pane" id="team">
        <form class="form-horizontal" action="insert.php" method="POST">
          <fieldset>
            <legend>Add a team</legend>
            <input type="hidden" name="type" value="team">
            <div class="control-group">
              <label class="control-label" for="name">Team ID (3 letters)</label>
              <div class="controls">
                <input type="text" class="input-small required" name="tid" id="tid" placeholder="AAA">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="name">Team name</label>
              <div class="controls">
                <input type="text" class="input-xlarge required" name="name" id="name">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="location required">Location</label>
              <div class="controls">
                <input type="text" class="input-xlarge" name="location" id="location">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="league">League</label>
              <div class="controls">
                <select name="league" class="required" id="league">
                  <option value="nba">NBA</option>
                  <option value="aba">ABA</option>
                </select>
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save changes</button>
              <button class="btn">Cancel</button>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </section>