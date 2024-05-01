<?php

/*
use a trait to define the methods required to gather all object data and communicate with the dbEngine
 */

 require_once "dbEngine.php";

trait POAPS {

  protected $POAPSclassName;

  public function save() {
    $this->POAPSclassName = get_class($this);
    //Pass it all on to the dbEngine
    $db = new dbEngine('testdb.db');
    $x = $db->store($this);
  }

  public function read(){

  }

  

  private function getAllProperties() {
      //$this->POAPSclass = get_class($this);
      $a = get_object_vars($this);
      //$this->POAPScrc = $this->calcCRC($a);

      return $a;
  }

  private function prepare2Save(){
    
    //$this->POAPScrc = $this->calcCRC();
  }
}
