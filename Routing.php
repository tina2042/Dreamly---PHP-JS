<?php

require_once "/app/autoloader.php";

class Routing{
    public static $routes;

  public static function get($url, $view) {
    self::$routes[$url] = $view;
  }
  public static function post($url, $view) {
    self::$routes[$url] = $view;
  }

  public static function run ($url) {
    $action = explode("/", $url)[0];
    if (!array_key_exists($action, self::$routes)) {
        $errorController = new ErrorController();
        $errorController->showErrorPage(404); 
        return;
    }

    $controller = self::$routes[$action];
    $object = new $controller;
    $action = $action ?: 'dashboard';

    $object->$action();
  }
}