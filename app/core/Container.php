<?php
class Container {
  private string $app;
  private $dog_location;

  function __construct($value) {
      $this->app=$value;
  }

  public function set_app($value) {
    $this->app=$value;
  }

  public function get_application() {
    $xmlDoc=new DOMDocument();
    if (file_exists(__DIR__ . "//..//config//applications.xml")) {
      $xmlDoc->load(__DIR__ . "//..//config//applications.xml");
      $typeNodes=$xmlDoc->getElementsByTagName("type");
      foreach($typeNodes as $searchNodes) {
        $id=$searchNodes->getAttribute('ID');
        if($id==$this->app) {
          
          $location=$searchNodes->getElementsByTagName('location');
          $file=$location->item(0)->nodeValue;
          return $file;
        }
      }
    } else {
      throw new Exception('"Container.php" applications.xml not found', 500);
    }
  }

  function create_object($properties = []) {
    $loc=__DIR__.$this->get_application();
    if(($loc==false)||(!file_exists($loc))) {
      throw new Exception('"dog_container.php"'."$loc".'not found', 500);
      return false;
    } else {
      if (require_once($loc)) {
        $class_array=get_declared_classes();
        $last_position=count($class_array)-1;
        $class_name=$class_array[$last_position];
        $object=new $class_name($properties);
        return $object;
      } else {
        throw new Exception('"container.php"'."$loc".'cannot be retrieved', 500);
      }
    } 
  }
}
?>