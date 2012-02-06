<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author  Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */

include_once ("custom/packages/iRailStations.class.php");

class DLStations extends iRailStations {
    
    public static function getParameters() {
        return array("municipal" => "The region to request the De Lijn stops from");
    }
    
    /**
     * No required parameters for the stations resource
     */
    public static function getRequiredParameters() {
        return array("municipal");
    }
    
    public function setParameter($key, $val) {
        if($key== "municipal"){
            $this->municipal = $val;
        }
    }


    public function call() {
        $arguments = array(":municipal" => $this->municpal);
        $result = R::getAll("select * from delijn_stops where STOPPARENTMUNICIPAL like :municipal",$arguments);
        return $result;
        
    }
    
    public static function getStationFromName($name, $lang = "en") {
    }
    
    public static function getStationIdFromName($name, $lang = "en") {
    }
    
    public static function getStationIdsFromName($names, $lang = "en") {

    }
    
    public static function getStationsFromName($names, $lang = "en") {
    }
}

?>
