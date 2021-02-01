<?php
if (($_SERVER['SERVER_PORT'] == 443) && !(defined("isHTTPS"))) {
     define("isHTTPS", true);
}
if (session_status() == PHP_SESSION_NONE) {
     if (defined("isHTTPS"))
          session_set_cookie_params([
               'secure' => true,
               'samesite' => 'Lax'
          ]);
     session_start();
}
if (!isset($_SESSION['legitUser']) || $_SESSION['legitUser'] != 'admin') /*{} else */ {
     header("Location:/");
     die();
}
