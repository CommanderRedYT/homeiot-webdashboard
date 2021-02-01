<?php
require('session.php');
$file = file_get_contents('.credentials');
$strings = explode("\n", $file);
$username = $strings[1];
if ($username == "") {
  http_response_code(403);
  header("Location:/");
  die();
}

define('SITE', 'settings');
define('TITLE', 'Settings');
require_once('includes/header.php');
?>
<h1>SETTINGS</h1>

<style>
  .send {
    color: white;
  }
</style>

<div class="input-group mb-3">
  <input id="apiusername" type="text" class="form-control border border-success apiusername" placeholder="Username" aria-label="Username" aria-describedby="button-addon2">
</div>


<div class="input-group mb-3">
  <input id="apicredentials" type="text" class="form-control border border-success apicredentials" placeholder="Credentials" aria-label="Credentials" aria-describedby="button-addon2">
  <button class="btn btn-outline-success bg-success send" onclick="saveAccountCredentials()" type="button" id="button-addon2">Save</button>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm">
      <div class="btn-group" role="group">
      <span class="input-group-text" id="inputGroup-sizing-lg">More detailed Statistics</span>
        <input type="radio" class="btn-check detailedInfo1" name="detailedInfo" id="detailedInfo1" autocomplete="off" checked>
        <label style="letter-spacing: 1px;" class="btn text-uppercase btn-outline-success" for="detailedInfo1"><b>Activate</b></label>

        <input type="radio" class="btn-check detailedInfo2" name="detailedInfo" id="detailedInfo2" autocomplete="off">
        <label style="letter-spacing: 1px;" class="btn text-uppercase btn-outline-danger" for="detailedInfo2"><b>Deactivate</b></label>
      </div>
    </div>
  </div>
</div>

<script>
  $(".detailedInfo1").on('change', function() {
    updateDetailed();
  });

  $(".detailedInfo2").on('change', function() {
    updateDetailed();
  });

  $(".apiusername").on('keyup', function(e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
      saveAccountCredentials();
    }
  });

  $(".apicredentials").on('keyup', function(e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
      saveAccountCredentials();
    }
  });
</script>

<script src="js/settings.js"></script>
<?php
if (file_exists(".api") == false) {
  echo "<br><h1>Please enter your credentials for the API bevor you can access the dashboard!</h1>";
}
require_once('includes/footer.php'); ?>