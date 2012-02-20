<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author Jens Segers
 * @author  Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */

include_once (dirname(__FILE__) . "/../iRailStations.class.php");

class AirportsStations extends iRailStations {
    
    /**
     * This function should get all stations from the NMBS/SNCB and return them.
     * Can't we get all information from DBPedia?
     * Does Open Street Map has a SPARQL end-point?
     */
    public function call() {
        $result = R::getAll("select code,name from Airports_stops",array());
        $results = array();
        foreach($result as &$row){
            $station = array();
            $station["code"] = $row["code"];
            $station["name"] = $row["name"];
            $station["departures"] = Config::$HOSTNAME . Config::$SUBDIR . "Airports/Liveboard/" . $station["code"] . "/" . date("Y") . "/" . date("m"). "/" .date("d") . "/" . date("H") . "/" .date ("i");
            $results[] = $station;
        }
        return $results;
    }
    
    public static function getAirportFromCode($code) {
        $arguments = array(":code" => $code);
        $result = R::getAll("select name from Airports_stops where code = :code",$arguments);
        foreach($result as $r){
            $res = array();
            $res["name"] = $r["name"];
            $res["code"] = $code;
            return $res;
        }
        return "";
    }
}

?>
