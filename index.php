<?php
require_once 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
//ath = parse_url( $path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::post('login', 'SecurityController');
Routing::post('register', 'SecurityController');
Routing::get('dashboard', 'DefaultController');
Routing::get('main', 'DefaultController');

Routing::run($path);

?>