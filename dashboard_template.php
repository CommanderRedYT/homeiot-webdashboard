<?php
require_once('session.php');
$file = file_get_contents('.credentials');
$strings = explode("\n", $file);
$username = $strings[1];
if($username != "{username}") {http_response_code(403);header("Location:/");die();};
?>