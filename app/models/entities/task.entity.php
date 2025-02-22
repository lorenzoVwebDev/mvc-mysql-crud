<?php 
class Task_entity {

  private $task_title = "";
  private $task_description = "";
  private $task_duedate = "";
  private $task_priority = "";
  private $error_message = "";
  private $allowed_priorities = array (
    'low',
    'medium',
    'high'
  );

  function __construct($properties_array) {
    $error_title = $this->set_task_title($properties_array['title']) == true ? 'true,' : 'false,';
    $error_description = $this->set_task_description($properties_array['description']) == true ? 'true,' : 'false,';
    $error_duedate = $this->set_task_duedate($properties_array['duedate']) == true ? 'true,' : 'false,';
    $error_priority = $this->set_task_priority($properties_array['priority']) == true ? 'true,' : 'false,';

    $this->error_message = $error_title.$error_description.$error_duedate.$error_priority;
  }

  public function insert_data($type) {

      $container = new Container('task_data_model_xml');
      $data = $container->create_object();
      $methods_array = get_class_methods($data);
      $last_position = count($methods_array) - 1;
      $method_name = $methods_array[$last_position];
      $records_array = array(array(
        'tasktitle' => $this->task_title,
        'taskdescription' => $this->task_description,
        'taskduedate' => $this->task_duedate,
        'taskpriority' => $this->task_priority
      ));
      $newTaskIndex = $data->$method_name($type, $records_array); 
      unset($data);
      return $newTaskIndex;
  }

  //returns the error message
  public function to_string() {
    return $this->error_message;
  }
  //setMethods
  public function set_task_title(string $value):bool {
    $error_message = true;
    (ctype_alpha($value) && (strlen($value) <= 20)) ? $this->task_title = $value : $error_message = false;
    return $error_message;
  }

  public function set_task_description(string $value):bool {
    $error_message = true;
    ((is_string($value)&&(strlen($value)>0 && strlen($value)<100)) || $value === 'none') ? $this->task_description=$value:$error_message=false;
    return $error_message;
  }

  public function set_task_duedate(string $date):bool {
      $error_message = true;
      $setDate = date_create($date);
      $logDate = date_format($setDate, "Ymd");
      $dateBool = true;
      if ($logDate < date("Ymd")) {
        $dateBool = false;
      } 
      $dateBool ? $this->task_duedate = $date : $error_message = false;
      return $error_message;
  }

  public function set_task_priority($value) {
    $error_message = true;
    $priorities_array=$this->allowed_priorities;

    foreach($priorities_array as $priority) {
      if ((strcasecmp($value, $priority)==0)) {
        $this->task_priority=$value;
        return $error_message;
      }
    }
    $error_message=false;
    return $error_message;
  }

  //breed validator method

  function get_task_title() {
    return $this->task_title;
  }

  function get_task_description() {
    return $this->task_description;
  }

  function get_task_duedate() {
    return $this->task_duedate;
  }

  function get_task_priority() {
    return $this->task_priority;
  }

  function get_properties() {
    return "$this->task_title"." "."$this->task_description"." "."$this->task_duedate"." "."$this->task_priority.";
  }
  
}
