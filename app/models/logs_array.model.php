<?php
class Logs_array_model {
  private string $type;
  function __construct($type) {
    $this->type = $type;
  }

  function arrayException($date) {
    if ($date) {
      $setDate = date_create($date);
      $logDate = date_format($setDate, "mdy");
    } else {
      $logDate = date('mdy');
    }
    if (file_exists(LOGS."//exceptions//".$logDate.".log")) {
      $logFile = fopen(LOGS."//exceptions//".$logDate.".log", "r");
      $error_array[0] = array(
        0 => 'Date',
        1 => 'Type',
        2 => 'Message',
      );
      $row_count = 1;
      while (!feof($logFile)) {
        $error_array[$row_count] = explode(" | ", fgets($logFile));
        $row_count++;
      }
      fclose($logFile);
      unset($error_array[count($error_array)-1]);
      return $error_array;
    } else {
      return "log file not found";
    }
  }

  function arrayError($date) {
    if ($date) {
      $setDate = date_create($date);
      $logDate = date_format($setDate, "mdy");
    } else {
      $logDate = date('mdy');
    }
    if (file_exists(LOGS."//errors//".$logDate.".log")) {
      $logFile = fopen(LOGS."//errors//".$logDate.".log", "r");
      $error_array[0] = array(
        0 => 'Date',
        1 => 'Type',
        2 => 'Message',
      );
      $row_count = 1;
      while (!feof($logFile)) {
        $error_array[$row_count] = explode(" | ", fgets($logFile));
        $row_count++;
      }
      fclose($logFile);
      unset($error_array[count($error_array)-1]);
      return $error_array;
    } else {
      return "log file not found";
    }
  }

  function arrayAccess($date) {
    if ($date) {
      $setDate = date_create($date);
      $logDate = date_format($setDate, "mdy");
    } else {
      $logDate = date('mdy');
    }
    if (file_exists(LOGS."//access//".$logDate.".log")) {
      $logFile = fopen(LOGS."//access//".$logDate.".log", "r");
      $error_array[0] = array(
        0 => 'Date',
        1 => 'Type',
        2 => 'Message',
      );
      $row_count = 1;
      while (!feof($logFile)) {
        $error_array[$row_count] = explode(" | ", fgets($logFile));
        $row_count++;
      }
      fclose($logFile);
      unset($error_array[count($error_array)-1]);
      return $error_array;
    } else {
      return "log file not found";
    }
  }

  
  function arrayEmail($date) {
    if ($date) {
      $setDate = date_create($date);
      $logDate = date_format($setDate, "mdy");
    } else {
      $logDate = date('mdy');
    }
    if (file_exists(LOGS."//email//".$logDate.".log")) {
      $logFile = fopen(LOGS."//email//".$logDate.".log", "r");
      $error_array[0] = array(
        0 => 'Date',
        1 => 'Type',
        2 => 'email',
      );
      $row_count = 1;
      while (!feof($logFile)) {
        $error_array[$row_count] = explode(" | ", fgets($logFile));
        $row_count++;
      }
      fclose($logFile);
      unset($error_array[count($error_array)-1]);
      return $error_array;
    } else {
      return "log file not found";
    }
  }
}