<?php
class Dog {

  function insertdog() {
    try {
      
      if((isset($_POST['dog_app']))) {
        if (isset($_POST['dog_name'])&&isset($_POST['dog_weight'])&&isset($_POST['dog_breed'])&&isset($_POST['dog_color'])) {
          $container=new Dog_container(filter_var($_POST['dog_app'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
          if (isset($container)) {
            $dog_name=filter_var($_POST['dog_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $dog_color=filter_var($_POST['dog_color'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $dog_weight=filter_var($_POST['dog_weight'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $dog_breed=filter_var($_POST['dog_breed'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
            $properties=array(
              $dog_name,
              $dog_breed,
              $dog_color,
              $dog_weight
            );
            //dog properties
            $lab = $container->create_object($properties);
          } else {
            throw new Exception('"dog_interface.php" Dog_container instance not created');
          }
      
          if ($lab != false) {
            list($name_error, $breed_error, $color_error, $weight_error) = explode(',', $lab->to_string());
/*             show(explode(',', $lab->to_string())); */
            $name_update = $name_error == 'true' ? true : false;
            $breed_updated = $breed_error == 'true' ? true : false;
            $color_update = $color_error == 'true' ? true : false;
            $weight_update = $weight_error = 'true' ? true : false;
            
            $dogs_array['errors'] = array (
              0 => $name_update,
              1 => $breed_updated,
              2 => $color_update,
              3 => $weight_update
            );

            $dogs_array['credentials'] = array (
              0 => $lab->get_dog_name(),
              1 => $lab->get_dog_breed(),
              2 => $lab->get_dog_color(),
              3 => $lab->get_dog_weight(),
            );

            $_SESSION['created-dog'] = $dogs_array;
            
            if (file_exists(__DIR__."//..//views//table.view.php")) {
              require(__DIR__."//..//views//table.view.php");
              $tableInstance = new Table_view($dogs_array);
              $table = $tableInstance->createTableDog();

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
          } else {
            throw new Exception('"dog_interface.php" dog not created');
          }
        } else {
          http_response_code(401);
          headers('Content-Type: text/plain');
          echo 'Missing or invalid parameters. Please go back to the dog home page to enter valid information. Dog Creation Page';
          throw new Exception('missing parameters', 401);
        }
      } else {
        http_response_code(401);
        headers('Content-Type: text/plain');
        echo 'Request is not valid';
        throw new Exception('request not valid', 401);
        
      } 
      } catch (Exception $e) {
        require_once(__DIR__ ."//..//models//logs.model.php");
        $exception = new Logs_model($e->getMessage(), 'exception');
        $last_log_message = $exception->logException();
        unset($exception);
        $exceptionCode = $e->getCode();
        if ($exceptionCode >= 500) {
          http_response_code(500);
          header("Content-Type: text/plain");
          echo "Error 500: We are sorry, we are going to resolve the issue as soon as possible";
        } else {
          http_response_code($exceptionCode);
          header("Content-Type: text/plain");
          echo $e->getMessage();
        }
      } 
  }

  function getbreeds() {
    try {
      if (isset($_GET['type'])) {
        $selectBox_request=$_GET['type'];
        $container=new Dog_container($selectBox_request);
        $breeds=$container->create_object($selectBox_request);
    
        if ($breeds!=false) {
          $properties=__DIR__."//..//config//breeds.xml";
          $selectBox=$breeds->get_select($properties);
          if ($selectBox != strip_tags($selectBox)) {
            http_response_code(200);
            header('Content-Type: text/html');
            echo $selectBox;
          } else {
            throw new Exception('breeds html not created', 500);
          }
        } else {
          throw new Exception('breeds file not found', 500);  
        }
      } else {
        throw new Exception('bad request', 401);
      }
    } catch (Exception $e) {
      require_once(__DIR__ ."//..//models//logs.model.php");
      $exception = new Logs_model($e->getMessage(), 'exception');
      $last_log_message = $exception->logException();
      unset($exception);
      $exceptionCode = $e->getCode();
      if ($exceptionCode >= 500) {
        http_response_code(500);
        header("Content-Type: text/plain");
        echo "Error 500: We are sorry, we are going to resolve the issue as soon as possible";
      } else if ($exceptionCode >= 400 && $exceptionCode < 500) {
        http_response_code(401);
        header("Content-Type: text/plain");
        echo "Requested file must be specified";
      }
    }
  }

}

