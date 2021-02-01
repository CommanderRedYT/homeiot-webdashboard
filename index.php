<?php
if(($_SERVER['SERVER_PORT'] == 443) && !(defined("isHTTPS"))) {
define("isHTTPS", true);
}
if (session_status() == PHP_SESSION_NONE) {
    if(defined("isHTTPS"))
    session_set_cookie_params([
        'secure' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

function console_warn($output, $with_script_tags = true)
{
    $js_code = 'console.warn(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
?>

<?php
if ((!isset($_SESSION['legitUser']) || $_SESSION['legitUser'] != 'admin')) {
    define('SITE', 'login');
    define('TITLE', 'Login');
} elseif (isset($_SESSION['legitUser']) && $_SESSION['legitUser'] == 'admin') {
    define('SITE', 'dashboard');
    define('TITLE', 'Dashboard');
}
require_once('includes/header.php');
?>

<?php

if ((!isset($_SESSION['legitUser']) || $_SESSION['legitUser'] != 'admin')) {
    require_once('login.php');
} elseif (isset($_SESSION['legitUser']) && $_SESSION['legitUser'] == 'admin') {
    require_once('dashboard.php');
} else {
    echo ('<div class="parentcontainer"><h1>ERROR</h1>');
    echo ('<p>Cookies may not be activated. This website needs cookies to work properly!</p></div>');
    http_response_code(500);
    console_warn("Error with cookies. Please check if cookies are enabled");
    echo '<script>setTimeout(function(){window.location.href = "/";}, 5000);</script>';
}
?>
<?php require_once('includes/footer.php') ?>