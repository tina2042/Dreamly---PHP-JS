<?php
require_once "/app/autoloader.php";

$path = trim($_SERVER['REQUEST_URI'], '/');


Routing::get('', 'DefaultController');
Routing::post('login', 'SecurityController');
Routing::post('register', 'SecurityController');
Routing::get('dashboard', 'DefaultController');
Routing::get('main', 'DefaultController');
Routing::get('adding_dream', 'DefaultController');

Routing::run($path);

?>