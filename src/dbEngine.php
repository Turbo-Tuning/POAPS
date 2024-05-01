<?php
/* 
  This class manages the SQL generation and execution

  In SQL the following types are translated as:
  Null - NULL
  integer - INTEGER
  boolean - INTEGER
  string - TEXT
  float - REAL

*/

require_once "SQLite3DB.php";

class dbEngine{

  private $db;
  public static $parentId;

  //Create db statements
  private $sql1 = 'CREATE TABLE IF NOT EXISTS Objects ("Id" INTEGER,
                                  "parentId" INTEGER,
                                  "objName" TEXT,
                                  "objType" TEXT,
                                  "objData" TEXT,
                                  "chksum" INTEGER,
                                  "deleted" INTEGER,
                                  PRIMARY KEY ("Id" AUTOINCREMENT))';
  private $sql2 = 'CREATE TABLE Variables ("Id" INTEGER,
                                            "objId" INTEGER,
                                            "varType" TEXT,
                                            "varName" TEXT,
                                            "varData" TEXT,
                                            "deleted" INTEGER,
                                            PRIMARY KEY ("Id" AUTOINCREMENT))';

  public function __construct($dbFile){
    self::$parentId = 0;
    $this->db = new SQLite3DB($dbFile);
    $ret = $this->db->query($this->sql1);
  }

  public function store($object){
    $pp = $this->insertObject($object);
    return $pp;
  }

  public function retrieve($Id){
    $sql = 'SELECT * FROM Objects WHERE Id='.$Id;
    $row = $this->db->get_row($sql);
    $object = json_decode($row['objData']);
    $crc = $this->calcCRC($object);
    if($crc == $row['chksum']){
      return $object;
    } else {
      return -1;
    }
  }

  private function insertObject($object){
    $data = array('chksum' => $this->calcCRC($object),
                  'parentId' => self::$parentId,
                  'objType' => get_class($object),
                  'objName' => '',
                  'objData' => $this->getObjectData($object),
                  'deleted' => 0);
        
    $this->db->insert('Objects', $data);
    return $this->db->insert_id();
  }

  private function getObjectData($object){
    $b = get_object_vars($object);
    return json_encode($b);
  }

  private function calcCRC($a) {
    $ser = $this->getObjectData($a);
    $crc = hash('crc32', $ser);
    return $crc;
  }  
}
