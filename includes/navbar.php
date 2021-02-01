<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$file = file_get_contents('.credentials');
$strings = explode("\n", $file);
$user = $strings[1];

$reload = '
<button style="margin-right: 8px; letter-spacing: 1px" type="button" onclick="location.reload()" class="text-uppercase btn btn-outline-warning"><b>Refresh</b></button>
';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">HomeIoT</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="container-fluid collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php
        function makeMenuItem($text, $url, $activename, $function)
        {
        ?>
          <li class="nav-item home">
            <a draggable="false" ondragstart="return false;" <?php echo htmlentities($function) ?> class="nav-link <?php echo htmlentities($activename);
                                                                                                                    if ($activename == SITE) echo " active" ?>" href="<?php echo htmlentities($url); ?>"><?php echo htmlentities($text); ?></a>
          </li>
        <?php
        }
        ?>
        <?php if (!isset($_SESSION['legitUser']) || $_SESSION['legitUser'] != 'admin') {
          makeMenuItem("Login", "./", "login", '');
        } ?>
        <?php if ((isset($_SESSION['legitUser']) && $_SESSION['legitUser'] == 'admin')) makeMenuItem("Dashboard", "./", "dashboard", ''); ?>
        <?php makeMenuItem("Info", "./info.php", "info", ''); ?>
        <?php if ((isset($_SESSION['legitUser']) && $_SESSION['legitUser'] == 'admin')) makeMenuItem("Settings", "./settings.php", "settings", ''); ?>
      </ul>
      <div class="container-fluid d-flex justify-content-end">
        <?php if ((isset($_SESSION['legitUser']) && $_SESSION['legitUser'] == 'admin') && basename($_SERVER['PHP_SELF']) == "index.php") echo $reload; ?>
        <?php if (isset($_SESSION['legitUser']) && $_SESSION['legitUser'] == 'admin') {
          echo '<button type="button" style="letter-spacing: 1px" class="text-uppercase btn btn-danger" onclick="logout()"><b>Logout</b></button>
      ';
        } ?>
      </div>
    </div>
  </div>
</nav>