<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author  Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */

include_once("custom/packages/MIVB/Stations.class.php");
include_once("custom/packages/iRailLiveboard.class.php");

class MIVBLiveboard extends IRailLiveboard {
    
    public function call() {
        $locid = $this->location;
        //check if we have to resolve the location to an id ourself
        if(!is_numeric($locid)){
            $locid = MIVBStations::getStationFromName($this->location);
        }
        $this->locid = $locid;
        
        $data = TDT::HttpRequest("http://reisinfo.delijn.be/realtime/halte/" . $locid);
        $data = $this->parseMIVB($data->data);
        return $data;
    }

    private function parseMIVB($data){
        preg_match_all("/<tr class=\".*?\"(.*?)(?=<tr class=\".*?\")/smi",$data,$matches);
        $results = array();
        $results["station"] = MIVBStations::getStationFromId($this->locid);
        $results["departures"] = array();
        foreach($matches[1] as $row){
            $dep = array();
            preg_match("/<span id=\"form:haltebord:..?:lblPubliekNr\">(.*?)<\/span>/smi",$row,$match);
            
            if(isset($match[1])){
                $dep["line"] = $match[1];
            }
            
            preg_match("/<span id=\"form:haltebord:..?:lblBestemming\">(.*?)<\/span>/smi",$row,$match);
            if(isset($match[1])){
                $dep["direction"] = $match[1];
            }
            
            preg_match("/<span id=\"form:haltebord:..?:verwacht\">(.*?)<\/span>/smi",$row,$match);
            if(isset($match[1])){
                $dep["time"] = $match[1];
            }
            
            //DEV:$dep["dump"] = $row;
            
            $results["departures"][] = $dep;
            
            
        }
        return $results;
        

    }
}

?>
