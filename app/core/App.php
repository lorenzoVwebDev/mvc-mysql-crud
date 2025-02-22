<?php
class App {
  private $controller = 'Home';
  private $method = 'index';
  public function splitURL() {
    //.htaccess put every written url in the 'url' value of the _GET super global array
    $URL = $_GET['url'] ?? 'home';
    $URL = explode("/", $URL);
    return $URL;
  }
  
  public function loadController() {
    $URL = $this->splitURL();
    //ucfirst is used to capitalize the first letter of a string
/*     print_r($URL); */
    $filename = "../app/controllers/".ucfirst($URL[0]).".php";
    if (file_exists($filename)) {
      require($filename);
      $this->controller = ucfirst($URL[0]);
      unset($URL[0]);
/*       show($URL); */
    } else {
      $filename = '../app/controllers/_404.php';
      require($filename);
      $this->controller = "_404";
    }

    $controller = new $this->controller;
    //the second part of the url will be checked by the condition below and used as a method if so 
    if (!empty($URL[1])) {
      if (method_exists($controller, $URL[1])) {
        $this->method = $URL[1];
        unset($URL[1]);
      }
    }
    
    //this method below is used to call a function in the first argument (the array first value is the instance of the class, the second is the instance's method's name), the second argument is an array containing all of the called method's arguments
    call_user_func_array([$controller, $this->method], $URL);
  }
}

