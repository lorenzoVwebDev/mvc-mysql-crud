<?php
class Logs extends Controller {
  private $error_message = 'hello';

  function exception() {
    try {
      if (isset($_POST['exception-name']) && isset($_POST['type'])) {
        $message = filter_var($_POST['exception-name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $type = $_POST['type'];
        $model = new Model();
        $last_log_message = $model->logEvent($message, $type);
        if (isset($last_log_message) && $last_log_message === $message) {
          $response['status'] = 'completed';
          $response['logType'] = $type;
          $response['log'] = $last_log_message;
          http_response_code(200);
          header('Content-Type: application/json');
          echo json_encode($response);
          unset($model);
        }
      } else {
        http_response_code(401);
        header('Content-Type: text/plain');
        echo 'Bad request: No message has been submitted';
        throw new Exception('exception-name POST variable is empty');
      }
      } catch (Exception $e) {
        require_once(__DIR__ ."//..//models//logs.model.php");
        $exception = new Logs_model($e->getMessage(), 'exception');
        $last_log_message = $exception->logException();
        unset($exception);
      }
    } 

  function error() {
    try {
      if (isset($_POST['error-name']) && isset($_POST['type'])) {
        $message = filter_var($_POST['error-name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $type = $_POST['type'];
        $model = new Model();
        $last_log_message = $model->logEvent($message, $type);
        if (isset($last_log_message) && $last_log_message === $message) {
          $response['status'] = 'completed';
          $response['logType'] = $type;
          $response['log'] = $last_log_message;
          http_response_code(200);
          header('Content-Type: application/json');
          echo json_encode($response);
          unset($model);
        } else {
          throw new Exception($last_log_message[1], $last_log_message[0]);
        }
      } else {
        http_response_code(401);
        header('Content-Type: text/plain');
        echo 'Bad request: No message has been submitted';
        throw new Exception('error-name POST variable is empty');
      }
    } catch (Exception $e) {
      require_once(__DIR__ ."//..//models//logs.model.php");
      $exception = new Logs_model($e->getMessage(), 'exception');
      $last_log_message = $exception->logException();
      unset($exception);
      if ($exceptionCode === 500) {
        http_response_code(500);
        header("Content-Type: text/plain");
        echo "Error 500: We are sorry, we are going to resolve the issue as soon as possible";
      }
    }
  }

  function access() {
    try {
      if (isset($_POST['access-name'])&&isset($_POST['type'])) {
        $message = filter_var($_POST['access-name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $type = $_POST['type'];
        $model = new Model();
        $last_log_message = $model->logEvent($message, $type);
        if (isset($last_log_message)&&$last_log_message === $message) {
          $response['status'] = 'completed';
          $response['logType'] = $type;
          $response['log'] = $last_log_message;
          http_response_code(200);
          header('Content-Type: application/json');
          echo json_encode($response);
          unset($model);
        }
      } else {
        http_response_code(401);
        header('Content-Type: text/plain');
        echo 'Bad request: No message has been submitted';
        throw new Exception('access-name POST variable is empty');
      }

    } catch (Exception $e) {
      require_once(__DIR__ ."//..//models//logs.model.php");
      $exception = new Logs_model($e->getMessage(), 'exception');
      $last_log_message = $exception->logException();
      unset($exception);
    }
  }

  function table() {
    try {
      if (isset($_GET['type']) && isset($_GET['date'])) {
        $type = $_GET['type'];
        $date = $_GET['date'];
        $model = new Model();
        $event_array = $model->logsArray($type, $date);
        if ($event_array === "log file not found" || $event_array === 'logs_array.model.php not found') {
          http_response_code(401);
          header('Content-Type: application/json');
          $response['result'] = $event_array;
          $response['status'] = 401;
          echo json_encode($response);
        } else {
          if (file_exists(__DIR__."//..//views//table.view.php")) {
            require(__DIR__."//..//views//table.view.php");
            $tableInstance = new Table_view($event_array, $type);
            $table = $tableInstance->createTableLogs();
            if ($table != strip_tags($table)) {
              http_response_code(200);
              header("Content-Type: text/html");
              echo $table;
            } else {
              throw new Exception("Table not created", 500);
            }
          } else {
            throw new Exception('table.view.php not found', 500);
          }
        }
      }
    } catch (Exception $e) {
      require_once(__DIR__ ."//..//models//logs.model.php");
      $exception = new Logs_model($e->getMessage(), 'exception');
      $last_log_message = $exception->logException();
      unset($exception);
      $exceptionCode = $e->getCode();
      if ($exceptionCode === 500) {
        http_response_code(500);
        header("Content-Type: text/plain");
        echo "Error 500: We are sorry, we are going to resolve the issue as soon as possible";
      }
    }
  }

  function deletelog() {
    try {
    //to handle application/json requests you have to handle it like that
      $json = file_get_contents('php://input');
      $data = json_decode($json, true);
      if (isset($data['index']) && isset($data['type'])) {
        $index = $data['index'];
        $type = $data['type'];
        $model = new Model();
        $deleted_log = $model->logEvent($index, $type, 'delete');

        if ($deleted_log instanceof Exception) {
          throw new Exception();
        } else {

          http_response_code(200);
          header('Content-Type: application/json');
          $response['result'] = $deleted_log;
          $response['status'] = 200;
          echo json_encode($response);
        }
      } else {
        throw new Exception('deleted log index or data has not been specified');
      }
    } catch (Exception $e) {
      if ($e->getMessage() === 'deleted log index or data has not been specified') {
        http_response_code(401);
        header('Content-Type: application/json');
        $response['result'] = 'index and type must be specified';
        $response['status'] = 401;
        echo json_encode($response);
      } else {
        http_response_code(500);
        header('Content-Type: application/json');
        $response['result'] = $e->getMessage();
        $response['status'] = 500;
        echo json_encode($response);
      }

    }
  }

  function downloadlogs($type) {
    if (isset($_GET)) {
      if (isset($_GET['type']) && $_GET['type'] === $type && isset($_GET['date'])) {
        try {
          $date = $_GET['date'];

          if ($date) {
            $setDate = date_create($date);
            $logDate = date_format($setDate, "mdy");
          } else {
            $logDate = date('mdy');
          }
          
          switch ($type) {
            case 'exception':
              $log_file = LOGS."//exceptions//". $logDate .".log";
              break;
            case 'error':
              $log_file = LOGS."//errors//". $logDate .".log";
              break;
            case 'access':
              $log_file = LOGS."//access//". $logDate .".log";
              break;
          } 
    
          if (file_exists($log_file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($log_file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Length: '.filesize($log_file));
            ob_clean();
            flush();
            readfile($log_file);
            exit;
          } else {
            http_response_code(401);
            header('Content-Type: application/json');
            $response['result'] = 'File not found';
            $response['status'] = 401;
            echo json_encode($response);
        }
         } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            $response['result'] = 'We are sorry! We are goin to fix that as soon as possible';
            $response['status'] = 500;
            echo json_encode($response);
            require_once(__DIR__ ."//..//models//logs.model.php");
            $exception = new Logs_model($e->getMessage(), 'exception');
            $last_log_message = $exception->logException();
            unset($exception);
        } catch (Error $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            $response['result'] = 'We are sorry! We are goin to fix that as soon as possible';
            $response['status'] = 500;
            echo json_encode($response);
            require_once(__DIR__ ."//..//models//logs.model.php");
            $exception = new Logs_model($e->getMessage(), 'exception');
            $last_log_message = $exception->logException();
            unset($exception);
        }
    } 
  }
}

  function sendtable() {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    try {
    if (isset($data['form-hidden'])) {
      if (isset($data['name']) && isset($data['surname']) && isset($data['log-date']) && isset($data['type']) && isset($data['mail'])) {
        $log_array = new Model();
        $email_array = $log_array->logsArray('email');
        if (is_array($email_array)) {
          $count = 0;
          for ($i=0;$i<count($email_array)-1;$i++) {
            if (trim($email_array[$i][2]) == trim(filter_var($data['mail'], FILTER_SANITIZE_EMAIL))) {
              $count++;
            }
          }
          if ($count > 2) {
            throw new exception('email sent yet', 409);
          }
        } 
        $logEmail = new Model();
        $mailLogged = $logEmail->logEvent(filter_var($data['mail'], FILTER_SANITIZE_EMAIL), 'email');
        if (!(isset($mailLogged)&&$mailLogged === trim(filter_var($data['mail'], FILTER_SANITIZE_EMAIL)))) {
          throw new Error('email not logged', 500);
        }
        $model = new Model();
        $event_array = $model->logsArray(filter_var($data['type'], FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($data['log-date'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        if ($event_array === "log file not found" || $event_array === 'logs_array.model.php not found') {
          $error_message = "log file not found";
          throw new Exception($error_message, 401);
        } else {
          unset($model);
          if (file_exists(__DIR__."//..//views//table.view.php")) {
            require(__DIR__."//..//views//table.view.php");
            $tableInstance = new Table_view($event_array, filter_var($data['type'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $table = $tableInstance->createTableLogs();
            if ($table != strip_tags($table)) {
              $model = new Model();
              $send_mail = $model->sendMail([filter_var($data['form-hidden'], FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($data['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($data['surname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($data['log-date'], FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($data['type'], FILTER_SANITIZE_FULL_SPECIAL_CHARS), filter_var($data['mail'], FILTER_SANITIZE_EMAIL), $table]);
              if ($send_mail) {
                http_response_code(200);
                header('Contet-Type: application/json');
                $response['message'] = 'message delivered';
                echo json_encode($response);
              } else {
                throw new Exception('mail not sent', 500);
              }
            } else {
              throw new Exception("Table not created", 500);
            }
          } else {
            throw new Exception('table.view.php not found', 500);
          }
        }


      }
    }
    } catch (Exception $e) {
      if ($e->getCode() >= 400 && $e->getCode() < 500) {
        http_response_code($e->getCode());
        header('Content-Type: application/json');
        $response['result'] = $e->getMessage();
        $response['status'] = $e->getCode();
        echo json_encode($response);
        require_once(__DIR__ ."//..//models//logs.model.php");
        $exception = new Logs_model($e->getMessage(), 'exception');
        $last_log_message = $exception->logException();
        unset($exception);
      } else {
        http_response_code(500);
        header('Content-Type: application/json');
        $response['result'] = 'We are sorry! We are goin to fix that as soon as possible';
        $response['status'] = 500;
        echo json_encode($response);
        require_once(__DIR__ ."//..//models//logs.model.php");
        $exception = new Logs_model($e->getMessage(), 'exception');
        $last_log_message = $exception->logException();
        unset($exception);
      }
    }
  }
}