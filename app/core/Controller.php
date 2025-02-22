<?php
//this file will contain all the common controllers' functions

class Controller {
  public function view($name) {
/*     $URL = $this->splitURL(); */
    //ucfirst is used to capitalize the firs letter of a string
    $filename = "../app/views/".$name.".view.php";
    if (file_exists($filename)) {
      require($filename);
    } else {
      $filename = '../App/views/404.view.php';
      require($filename);
/*       throw new Exception($filename.'controller not found'); */
    }
  }
}