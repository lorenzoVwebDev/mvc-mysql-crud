<?php
class Home extends Controller {
  public function index() {
    $this->view('home');
  }

  public function edit($a = '', $b = '', $c = '') {
    show("from the edit function");
    $this->view('home');
  }

  public function logs() {
    if ($_POST['exception-name']) {
      echo 'exception form data received';
    }
  }
}
