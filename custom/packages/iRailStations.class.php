<?php
/**
 * @copyright (C) 2011 by iRail vzw/asbl
 * @author  Jens Segers
 * @license  AGPLv3
 *
 * Lists all stations for a certain system
 */

include_once (dirname(__FILE__) . "/iRailTools.class.php");

class iRailStations extends AResource {
    
    protected $lang;
    
    public function __construct() {
        $this->lang = "en";
    }
    
    public static function getParameters() {
        return array("lang" => "Language for the stations");
    }
    
    /**
     * No required parameters for the stations resource
     */
    public static function getRequiredParameters() {
        return array();
    }
    
    public function setParameter($key, $val) {
        if ($key == "lang") {
            $this->lang = $val;
        }
    }
    
    public function call() {}
    
    public static function getAllowedPrintMethods() {
        return array("xml", "json", "php", "jsonp", "html", "kml");
    }
    
    public static function getDoc() {
        return "Stations will return a list of all known stops of a system";
    }
}

?>