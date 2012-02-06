<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author  Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */

include_once ("custom/packages/iRailStations.class.php");

class MIVBStations extends iRailStations {
    


    public function call() {
        $arguments = array();
        date_default_timezone_set("Europe/Brussels");
        $result = R::getAll("select * from mivb_stops",$arguments);
        $results = array();
        foreach($result as &$row){
            $station = array();
            $station["id"] = $row["stop_id"];
            $station["name"] = $row["stop_name"];
            $station["longitude"] = $row["stop_lat"];
            $station["latitude"] = $row["stop_lon"];
            $station["departures"] = Config::$HOSTNAME . Config::$SUBDIR . "MIVB/Liveboard/" . $station["id"] . "/" . date("Y") . "/" . date("m"). "/" .date("d") . "/" . date("H") . "/" .date ("i");
            $results[] = $station;
        }
        
        date_default_timezone_set("UTC");
        return $results;
    }
    
    public static function getStationFromName($name, $lang = "en") {
        $arguments = array(":name" => urldecode($name));
        $result = R::getAll("select stop_id from mivb_stops where stop_name like '%:name%'",$arguments);
        if(isset($result[0])){
            return $result[0]["stop_id"];
        }
        return 0;
    }

    public static function getStationFromId($id, $lang = "en") {
        $arguments = array(":id" => $id);
        $result = R::getAll("select stop_name from mivb_stops where stop_id = :id",$arguments);
        if(isset($result[0])){
            return $result[0]["stop_name"];
        }
        return "";
    }
}

?>
