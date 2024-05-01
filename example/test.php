<?php

require_once "../src/POAPS.php";

class test {
    use POAPS;

    var $myVar;
    public $boolean = false;
    var $integer = 1;
    var $string = "notavailable";
    var $float = 1.32343;
    var $arr = array("color" => "yellow", "address" => "44 east st.");
    var $t;
    public $obj1;

    public function __construct()
    {
        //$crc = $this->calcCRC();
        $this->t = time();
        $this->obj1 = new stdClass;
        $this->obj1->obj2 = new stdClass;
        $this->save();
        $t1 = microtime(true);
        echo $crc." crc time ". microtime(true) - $t1.PHP_EOL;
        
        $a = $this->getAllProperties();
        echo "processing time ".microtime(true)-$t1;
        
        //var_dump($a);
        foreach ($a as $k => $v){
            echo $k." ".""." is ".gettype($v).PHP_EOL;
        }
        sleep (1);
    }
}

$a = new test;
