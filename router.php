<?php

class Router
{

  private $routes;

  public function __construct()
  {
    $routesPath = ROOT . '../routes.php';
    $this->routes = include($routesPath);
  }




	private function getUri()
  {
    if (!empty($_SERVER['REQUEST_URI'])) {
      return trim($_SERVER['REQUEST_URI'], '');
    }
  }

  public function run()
  {
    if (!empty($_SERVER['REQUEST_URI'])) {
      $uri = trim($_SERVER['REQUEST_URI'], '');

      $uri = $this->getUri();
      foreach ($this->routes as $uriPattern => $path) {
        // echo "<br>$uriPattern->$path";
        if (preg_match("~$uriPattern~", $uri)) {
          //echo '<br>PLUS';
          //определям какой контроллер и action обрабатывают запрос
          $segments = explode('/', $path);
          $controllerName = array_shift($segments) . 'Controller';
          $controllerName = ucfirst($controllerName);
          $actionName = 'action' . ucfirst(array_shift($segments));

          echo "<br>CLASS: " . $controllerName;
          echo "<br>METHOD: " . $actionName;

          //пожключить файл класса контроллер
          $controllerFile = $controllerName . '.php';
          if (file_exists($controllerFile)) {
            include_once($controllerFile);
          }

          //создать объект вызвать метод
          $controllerObject = new $controllerName;
          $result = $controllerObject->$actionName();
          if ($result != null) {
            break;
          }

        }
      }
      // echo $uri;

    }


  }
}
?>


