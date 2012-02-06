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
        if($key == "municipal"){
            $this->municipal = strtoupper($val);
        }
    }


    public function call() {
        $arguments = array(":municipal" => urldecode($this->municipal));
        $result = R::getAll("select * from delijn_stops where STOPPARENTMUNICIPAL like :municipal",$arguments);
        return $result;
    }
    
    public static function getStationFromName($name, $lang = "en") {
        $arguments = array(":name" => urldecode($name));
        $result = R::getAll("select STOPIDENTIFIER from delijn_stops where STOPDESCRPTION like '%:name%'",$arguments);
        if(isset($result[0])){
            return $result[0]["STOPIDENTIFIER"];
        }
        return 0;
    }

    public static function getStationFromId($id, $lang = "en") {
        $arguments = array(":id" => $id);
        $result = R::getAll("select STOPDESCRPTION from delijn_stops where STOPIDENTIFIER = :id",$arguments);
        if(isset($result[0])){
            return $result[0]["STOPDESCRPTION"];
        }
        return "";
    }
}

?>
