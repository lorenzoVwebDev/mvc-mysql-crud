<?php
session_start();
require "../app/core/init.php";
//we are using the DEBUG constant defined in app/core/config.php to se error diplaying on or off
if (DEBUG) {
  // ini_set() determinest whether errors have to be shown or not
  ini_set('display_errors', 1);
} else {
  ini_set('display_errors', 0);
}
  
$load = new App;
$load->loadController();


