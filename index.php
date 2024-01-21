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
Routing::get('user_profile','DefaultController');
Routing::get('admin_profile','DefaultController');
Routing::post('delete_user', 'SecurityController');
Routing::get('get_dreams', 'DreamController');
Routing::post('search', 'DefaultController');
Routing::post('add_friend','DefaultController');
Routing::run($path);

