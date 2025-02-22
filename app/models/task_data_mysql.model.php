<?php

class Task_data {
  use Database;
  public $task_array = array();
  public $task_data_xml = '';

  function __construct() {

    $query_string = "SELECT * FROM tasks";
    $array = $this->query($query_string);
    $this->task_array = array (
      'task' => $array
    );
  } 

  function __destruct() {
    $mysqli = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);

    if ($mysqli->connect_errno) {
      throw new Exception("MYSQL connection error: ".$mysqli->connect_error, 500);
    }

    if (
      !$mysqli->query("DROP TABLE IF EXISTS tasks") ||
      !$mysqli->query("CREATE TABLE IF NOT EXISTS tasks (
        tasktitle varchar(25), 
        taskdescription varchar(100), 
        taskduedate date, 
        taskpriority enum('low','medium','high')
      )") 
    ) {
      throw new Exception("Table can't be created or deleted".$mysqli->error, 500);
    }

    foreach ($this->task_array as $array_key=>$array_value) {
      foreach($array_value as $task_index => $taskvalue) {
        $taskTitle = $taskvalue['tasktitle'];
        $taskDescription = $taskvalue['taskdescription'];
        $taskDuedate = $taskvalue['taskduedate'];
        $taskPriority = $taskvalue['taskpriority'];

        $query_string = "
          INSERT INTO tasks (tasktitle, taskdescription, taskduedate, taskpriority) VALUES (
            '$taskTitle',
            '$taskDescription',
            '$taskDuedate',
            '$taskPriority'
            )
          ";
        if (!$mysqli->query($query_string)) {

          throw new Exception("Can't add new records: ". $mysql->error, 500);
        }  
      }
    }

    $mysqli->close();
  }

  function createRecord($records_array) {
    $task_array_size = count($this->task_array['task']);

    for ($J=0;$J<count($records_array);$J++) {
      $this->task_array['task'][$task_array_size+$J] = $records_array[$J];
    }
    return $newTaskIndex = count($this->task_array['task'])-1;
  }

  function readRecord($recordNumber) {

    if ($recordNumber === 'ALL') {
      return $this->task_array['task'];
    } else {
      return $this->task_array['task'][$recordNumber];
    }
  }

  function updateRecords($records_array) {

    foreach ($records_array as $records => $record_value) {

      $this->task_array['task'][$records] = $records_array[$records];
    }
  }

  function deleteRecord($recordNumber) {

    $oldArray = $this->task_array['task'];
    foreach ($this->task_array as $tasks=>&$tasks_value) {
      for ($J =$recordNumber; $J<count($tasks_value)-1;$J++) {
        foreach ($tasks_value[$J] as $column => $column_value) {
          $tasks_value[$J][$column] = $tasks_value[$J+1][$column];
        }
      }
      unset($tasks_value[count($tasks_value)-1]);
    }
    $newArray = $this->task_array['task'];

    if (!($oldArray[$recordNumber+1] == $newArray[$recordNumber])) {
      throw new Exception('deleteRecord is not working properly', 500);
    } 
  }

  function processRecords(string $crud_type, array|int|string $records_value) {

    switch ($crud_type) {
      case "insert":
        return $this->createRecord($records_value);
        break;
      case "select":
        return $this->readRecord($records_value);
        break;
      case "update":
        $this->updateRecords($records_value);
        break;
      case "delete":
        return $this->deleteRecord($records_value);
        break;
      default:
        throw new Exception("Invalid CRUD operation type: $crud_type", 401);
    }
  }
}