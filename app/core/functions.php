<?php
function show($object) {
  echo "<pre>";
  print_r($object);
  echo "<pre/>";
}

function redirect($path) {
  header("Location: ".ROOT."/".$path);
  //die ensures that the script of the page that we were in before stops and doesn't affect the new page.
  die;
}

function server_name() {
  echo $_SERVER['SERVER_NAME'];
}

function checkUrlValidity($url) {
  // Use get_headers to fetch HTTP response headers from the URL
  $headers = @get_headers($url);

  // Check if we received a valid response (status code 200 OK)
  if ($headers && strpos($headers[0], "200") !== false) {
      return true;  // URL is valid
  } else {
      return false;  // URL is not valid
  }
}

function error_check_dog_app($lab) {
  list($name_error, $breed_error, $color_error, $weight_error) = explode(',', $lab->to_string());
  print $name_error == 'true' ? 'Name update successful<br/>' : 'Name update not successful <br/>';
  print $breed_error == 'true' ? ' Breed update successful<br/>' : 'Breed update not successful<br/>';
  print $color_error == 'true' ? 'Color update successful<br/>' : 'Color update not successful<br/>';
  print $weight_error = 'true' ? 'Weight update successful<br/>' : 'Weight update not successful<br/>';
}

function get_properties($lab) {
  print "Your dog's name is ".$lab->get_dog_name()."<br>";
  print "Your dog weights is ".$lab->get_dog_weight()."kg. <br>";
  print "Your dog's breed is ".$lab->get_dog_breed()."<br>";
  print "Your dog's color is ".$lab->get_dog_color()."<br>";
}