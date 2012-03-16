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
        if($key == "municipal"){
            $this->municipal = strtoupper($val);
        }
    }


    public function call() {
        date_default_timezone_set("Europe/Brussels");
        $arguments = array(":municipal" => urldecode($this->municipal));
        $result = R::getAll("select * from DL_stops where STOPPARENTMUNICIPAL like :municipal or STOPMUNICIPAL like :municipal and STOPISPUBLIC = 'true'",$arguments);
        $results = array();
        foreach($result as &$row){
            $station = array();
            $station["id"] = $row["STOPIDENTIFIER"];
            $station["name"] = $row["STOPDESCRPTION"];
            $coord = tools::LambertToWGS84($row["STOPCORDINATEX"],$row["STOPCORDINATEY"]);
            $station["longitude"] = $coord[1];
            $station["latitude"] = $coord[0];
            $station["departures"] = Config::$HOSTNAME . Config::$SUBDIR . "DL/Liveboard/" . $station["id"] . "/" . date("Y") . "/" . date("m"). "/" .date("d") . "/" . date("H") . "/" .date ("i");
            $results[] = $station;
        }
        date_default_timezone_set("UTC");
        return $results;
    }
    
    public static function getStationFromName($name, $lang = "en") {
        $arguments = array(":name" => urldecode($name));
        $result = R::getAll("select STOPIDENTIFIER from DL_stops where STOPDESCRPTION like '%:name%'",$arguments);
        if(isset($result[0])){
            return $result[0]["STOPIDENTIFIER"];
        }
        return 0;
    }

    public static function getStationFromId($id, $lang = "en") {
        $arguments = array(":id" => $id);
        $result = R::getAll("select STOPDESCRPTION, STOPCORDINATEX, STOPCORDINATEY from DL_stops where STOPIDENTIFIER = :id",$arguments);
        foreach($result as $r){
            $res = array();
            $res["name"] = $r["STOPDESCRPTION"];
            $location = tools::LambertToWGS84($r["STOPCORDINATEX"],$r["STOPCORDINATEY"]);
            $res["longitude"] = $location[1];
            $res["latitude"] = $location[0];
            return $res;
        }
        return "";
    }
}

?>