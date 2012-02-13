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
        
        $data = TDT::HttpRequest("http://stibrt.be/labs/stib/service/getwaitingtimes.php?iti=1&halt=" . $locid . "&lang=". $this->lang) ;
        $data = $this->parseMIVB($data->data);
        return $data;
    }

    private function parseMIVB($data){
        preg_match_all("/<waitingtime>.*?<line>(.*?)<\/line>.*?<mode>(.*?)<\/mode>.*?<minutes>(.*?)<\/minutes>.*?<destination>(.*?)<\/destination>.*?<\/waitingtime>/si", $data,$matches);
        $nodes = array();
        for($i=1;$i<sizeof($matches[0]);$i++){
            $nodes[$i-1] = array();
            $nodes[$i-1]["vehicle"] = "BE.MIVB." . $matches[2][$i] . $matches[1][$i];
            $nodes[$i-1]["time"] = date("U") + $matches[3][$i]*60;
            $nodes[$i-1]["delay"] = 0;
            $nodes[$i-1]["station"] = $matches[4][$i];
        }
        return $nodes;
    }
}

?>
