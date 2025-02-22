<?php
trait Database {
  private function connect() {
    $string = "mysql:host=".DBHOST.";dbname=".DBNAME.";";
    return $con = new PDO($string, DBUSER, DBPASSWORD);
  }
  //we are going to use parameters to avoid query injections by the users that can manipulate our database

  
  public function query($query, array $data = []) {
    $con = $this->connect();
    //query specification 
    $stm = $con->prepare($query);
    //query execution: $check is a boolean that returns either true or false
    $check = $stm->execute($data);
    if ($check) {
      //fetch the previous query result into an array
      $result = $stm->fetchAll(PDO::FETCH_NAMED);
      if (is_array($result) && count($result)) {
        return $result;
      } else {
        return [];
      }
    } else {
      throw new Exception('can\'t validate the query'.$stm->errorInfo()[2], 500);
    }
    unset($con);
  }
 }

