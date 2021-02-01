<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method != "POST") {
    http_response_code(405);
    echo "The server doesn't allow $method";
    header("Location:/");
    exit;
};

$file = file_get_contents('.credentials');
$strings = explode("\n", $file);
$hash = $strings[0];
$username = $strings[1];

if ($hash == "") {
    http_response_code(500);
    exit("File empty");
} else {
    $request_hash = $_SERVER["HTTP_HASH"];
    if (hash_equals($hash, $request_hash)) {
        http_response_code(200);
        $_SESSION['legitUser'] = 'admin';
    } else {
        http_response_code(403);
    }
}
