<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author  Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */

include_once ("custom/packages/iRailStations.class.php");
include_once ("custom/packages/DL/tools.php");

class DLMunicipals extends iRailStations {
    
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


    public function call() {
        date_default_timezone_set("Europe/Brussels");
        $arguments = array();
        $result = R::getAll("SELECT DISTINCT STOPMUNICIPAL FROM DL_stops UNION SELECT DISTINCT STOPPARENTMUNICIPAL FROM DL_stops",$arguments);
        $results = array();
        foreach($result as &$row){
            $municipal = array();
            $municipal["name"] = $row["STOPMUNICIPAL"];
            $municipal["stations"] = Config::$HOSTNAME . Config::$SUBDIR . "DL/Stations/" . $municipal["name"];
            $results[] = $municipal;
        }
        date_default_timezone_set("UTC");
        return $results;
    }
}

?>
