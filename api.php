<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('session.php');

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "POST") {
    $request_type = $_SERVER["HTTP_TYPE"];
    if ($request_type == "credentials") {
        $request_username = $_SERVER["HTTP_USERNAME"];
        $request_credentials = $_SERVER["HTTP_CREDENTIALS"];
        if (($request_username != "") && ($request_credentials != "")) {
            $request_username .= "\n";
            $request_username .= $request_credentials;
            file_put_contents(".api", $request_username);
        } else {
            http_response_code(400);
        }
    }
} elseif ($method == "GET") {
    $file = file_get_contents('.api');
    $strings = explode("\n", $file);
    $username = $strings[0];
    $credentials = $strings[1];
    $username = htmlspecialchars($username);
    $credentials = htmlspecialchars($credentials);
    header("Username: $username");
    header("Credentials: $credentials");
}
