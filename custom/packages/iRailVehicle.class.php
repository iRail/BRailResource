<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author Jens Segers
 * @license AGPLv3
 *
 */

include_once (dirname(__FILE__) . "/iRailTools.class.php");

class iRailVehicle extends AResource {
    
    protected $id;
    protected $lang;
    
    public function __construct() {
        $this->lang = "en";
    }
    
    public static function getParameters() {
        return array("id" => "Specify the vehicle id. This should be according the iRail specification", "lang"=>"Language");
    }
    
    public static function getRequiredParameters() {
        return array("id");
    }
    
    public function setParameter($key, $val) {
        if ($key == "id") {
            $this->id = $val;
        }
        
        elseif ($key == "lang") {
            $this->lang = $val;
        }
    }
    
    public function call() {}
    
    public static function getAllowedPrintMethods() {
        return array("xml", "json", "php", "jsonp");
    }
    
    public static function getDoc() {
        return "Return vehicle information";
    }
}

?>
