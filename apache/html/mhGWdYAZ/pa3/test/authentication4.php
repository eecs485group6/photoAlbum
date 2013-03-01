<?php
session_start();

$authorized = false;

if(isset($_GET['logout']) && ($_SESSION['auth'])) {
    $_SESSION['auth'] = null;
    session_destroy();
    echo "logging out...";
}

if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
    $user = 'test';
    $pass = 'test';
    if (($user == $_SERVER['PHP_AUTH_USER']) && ($pass == ($_SERVER['PHP_AUTH_PW'])) && (!empty($_SESSION['auth']))) {
        $authorized = true;
    }
}

if (isset($_GET["login"]) && (! $authorized)) {
    header('WWW-Authenticate: Basic Realm="Login please"');
    header('HTTP/1.0 401 Unauthorized');
    $_SESSION['auth'] = true;
    print('Login now or forever hold your clicks...');
    exit;
}

?>

<h1>you have <? echo ($authorized) ? '' : 'not'; ?> logged!</h1>
