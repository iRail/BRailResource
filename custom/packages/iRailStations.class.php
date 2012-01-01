<?php
/**
 * @copyright (C) 2011 by iRail vzw/asbl
 * @author  Jens Segers
 * @license  AGPLv3
 *
 * Lists all stations for a certain system
 */

include_once("custom/packages/iRailTools.class.php");
include_once("custom/packages/AbstractiRailResource.class.php");

class iRailStations extends AbstractiRailResource {
    
    public static function getParameters() {
        return array();
    }
    
    /**
     * No required parameters for the stations resource
     */
    public static function getRequiredParameters() {
        return array();
    }
    
    public function setParameter($key, $val) {
    }
    
    public function call() {}
    
    public static function getDoc() {
        return "Stations will return a list of all known stops of a system";
    }
}

?>