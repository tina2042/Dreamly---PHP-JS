<?php
require_once "autoloader.php";

$path = trim($_SERVER['REQUEST_URI'], '/');


Routing::get('', 'DefaultController');
Routing::post('login', 'SecurityController');
Routing::post('logout','SecurityController');
Routing::post('register', 'SecurityController');
Routing::get('dashboard', 'DefaultController');
Routing::get('main', 'DefaultController');
Routing::post('adding_dream', 'DreamController');
Routing::get('calendar', 'DefaultController');

Routing::run($path);

