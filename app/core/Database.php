<?php
trait Database {
  private function connect() {
    $string = "mysql:host=".DBHOST.";dbname=".DBNAME.";";
    return $con = new PDO($string, DBUSER, DBPASSWORD);
  }
  //we are going to use parameters to avoid query injections by the users that can manipulate our database

  
  public function query($query, $data = []) {
    $con = $this->connect();
    //query specification 
    $stm = $con->prepare($query);
    //query execution: $check is a boolean that returns either true or false
    $check = $stm->execute($data);
    if ($check) {
      $result = $stm->fetchAll(PDO::FETCH_OBJ);
      if (is_array($result) && count($result)) {
        return $result;
      }
    }
    if (is_array($result) && count($result)) {
      return $result;
    }

    return false;
  }

  public function get_row($query, $data = []) {
    $con = $this->connect();
    //query specification 
    $stm = $con->prepare($query);
    //query execution: $check is a boolean that returns either true or false
    $check = $stm->execute($data);
    if ($check) {
      $result = $stm->fetchAll(PDO::FETCH_OBJ);
      if (is_array($result) && count($result)) {
        return $result;
      }
    }
    if (is_array($result) && count($result)) {
      return $result[0];
    }

    return false;
  }
 }

