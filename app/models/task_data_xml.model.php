<?php

class Task_data {
  public $task_array = array();
  public $task_data_xml = '';

  function __construct() {
      libxml_use_internal_errors(true);
      $xmlDoc = new DOMDocument();
    if (file_exists(__DIR__."//..//config//applications.xml")) {
      $xmlDoc->load(__DIR__."//..//config//applications.xml");
      $nodeValueArray = $xmlDoc->getElementsByTagName('type');

      foreach ($nodeValueArray as $nodeValue) {
        $value = $nodeValue->getAttribute('ID'); 
        if ($value === 'task_data_storage') {
          $xmlLocation = $nodeValue->getElementsByTagName('location');
          $this->task_data_xml = $xmlLocation->item(0)->nodeValue;
          break;
        }
      }     
    } else {
      throw new Exception("application.xml not found", 500);
    }

    $xmlfile = file_get_contents(__DIR__.$this->task_data_xml);

    $xmlstring = simplexml_load_string($xmlfile);
    if ($xmlstring === false) {
      $errorString = "Failed loading XML: ";

      foreach(libxml_get_errors() as $error) {
        $errorString .= $error->message . " ";
      }

      throw new Exception($errorString, 500);
    }
    $json = json_encode($xmlstring);
    $decoded = json_decode($json, TRUE);
    $this->task_array = json_decode($json, TRUE); 
  } 

  function __destruct() {
    $xmlstring = "<?xml version='1.0' encoding='UTF-8'?>";
    $xmlstring .= "<tasks>"."\n";
    foreach ($this->task_array as $tasks=>$tasks_value) {
      foreach ($tasks_value as $task_index=>$task_array) {
        $xmlstring .= "<task>"."\n";
        foreach ($task_array as $task_spec => $task_spec_value) {
          if (is_array($task_spec_value)) {
            $xmlstring .= "<$task_spec>"."none"."</$task_spec>"."\n";
          } else {
            $xmlstring .= "<$task_spec>"."$task_spec_value"."</$task_spec>"."\n";
          }
        }
        $xmlstring.="</task>"."\n";
      }
    }
    $xmlstring .= "</tasks>";

    $new_valid_data_file = preg_replace('/[0-9]+/','', $this->task_data_xml);
    $oldxmldata = trim($new_valid_data_file, '.xml',).date('mdy').".xml";

    $renameBool = rename(__DIR__.$this->task_data_xml, __DIR__.$oldxmldata);
    if (!$renameBool) {
      throw new Exception("Backup file $oldxmldata could not be created", 500); 
    }

    $filecreation = file_put_contents(__DIR__.$new_valid_data_file, $xmlstring);

    if (!$filecreation) {
      throw new Exception("Can't create a new file", 500);
    }
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