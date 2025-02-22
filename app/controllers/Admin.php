<?php
class Admin extends Controller {
  public function dashboard($name) {
    $this->view($name);
  } 

  function taskcrud($type) {
    try {
      $crudArray = array (
        'insert',
        'select',
        'update',
        'delete'
      );

      $needle = in_array(filter_var($type, FILTER_SANITIZE_FULL_SPECIAL_CHARS), $crudArray);
      if (isset($_POST['task'])&&$needle&&$type === 'insert') {    
          if (isset($_POST['title'])&&isset($_POST['duedate'])) {
            $newTask['title'] = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newTask['description'] = $_POST['description'] ? filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'none';
            $newTask['duedate'] = filter_var($_POST['duedate'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
            $newTask['priority'] = $_POST['priority'] ? filter_var($_POST['priority'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'none';
            $taskCrud = new Model();
            $inserted = $taskCrud->taskCrud($newTask, $type);
            if (is_array($inserted)) {
              http_response_code(200);
              header('Content-Type: application/json');
              $json = json_encode($inserted);
              echo $json; 
            } 

          } else {
            throw new Exception('title is missing', 401);
          }

      } else if ($type === 'select'&&$needle&&$_GET['id']) {
        $id['id'] = $_GET['id'];
        $taskCrud = new Model();
        $taskArray = $taskCrud->taskCrud($id, $type);
        if (is_array($taskArray)) {
          http_response_code(200);
          header('Content-Type: application/json');
          $json = json_encode($taskArray);
          echo $json; 
        } 
      } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $type==='delete'&&$needle) {
        $urlArray = explode('/',$_GET['url']);
        $id = $urlArray[count($urlArray)-1];
        if (preg_match('/^\d*$/', $id) === 1) {
          $idNum = (int)$id;
          $taskCrud = new Model();
          $deleted = $taskCrud->taskCrud($idNum, $type);
          if ($deleted === $idNum) {
            http_response_code(200);
            header('Content-Type: text/plain');
            echo $idNum;
          } 
        }
      } else if ($_SERVER['REQUEST_METHOD'] === 'PUT' && $type==='update'&&$needle) {
        $updatedTask = file_get_contents('php://input', true);
        if ($updatedTask === null) {
          throw new Exception('invalid data', 400);
        }
        $updatedTaskArray = json_decode($updatedTask, true);
        $newTask['tasktitle'] = filter_var($updatedTaskArray['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $newTask['taskdescription'] = $updatedTaskArray['description'] ? filter_var($updatedTaskArray['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'none';
        $newTask['taskduedate'] = filter_var($updatedTaskArray['duedate'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        $newTask['taskpriority'] = $updatedTaskArray['priority'] ? filter_var($updatedTaskArray['priority'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'none';
        $id = filter_var($updatedTaskArray['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $updatedArray = array(
          $id => $newTask
        );
        $taskCrud = new Model();
        $updated = $taskCrud->taskCrud($updatedArray, $type);

        if ($updated === 'updated') {
          http_response_code(200);
          headers('Content-Type: text/plain');
          echo 'ALL';
        }

      } else {
        throw new Exception('request not valid', 401);
      }
    } catch (Exception $e) {
      if ($e->getCode() >= 400 && $e->getCode() < 500) {
        http_response_code($e->getCode());
        header('Content-Type: application/json');
        $response['result'] = $e->getMessage();
        $response['status'] = $e->getCode();
/*         echo json_encode($response); */
        require_once(__DIR__ ."//..//models//logs.model.php");
        $exception = new Logs_model($e->getMessage()." ".$e->getFile(), 'exception');
        $last_log_message = $exception->logException();
        unset($exception);
      } else {
        http_response_code(500);
        header('Content-Type: application/json');
        $response['result'] = 'We are sorry! We are goin to fix that as soon as possible';
        $response['status'] = 500;
/*         echo json_encode($response); */
        require_once(__DIR__ ."//..//models//logs.model.php");
        $exception = new Logs_model($e->getMessage()." ".$e->getFile(), 'exception');
        $last_log_message = $exception->logException();
        unset($exception);
      }
    }

  } 
}