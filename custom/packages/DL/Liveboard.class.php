<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author  Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */

include_once("custom/packages/DL/Stations.class.php");
include_once("custom/packages/iRailLiveboard.class.php");

class DLLiveboard extends IRailLiveboard {
    
    public function call() {
        $locid = $this->location;
        //check if we have to resolve the location to an id ourself
        if(!is_numeric($locid)){
            $locid = DLStations::getStationFromName($this->location);
        }
        $this->locid = $locid;
        $data = array();
        
        $data = $this->getLiveboardFromDB($locid);
        $rtdata= array();
        if($this->realtime_needed()){
//            $rtdata = $this->scrapeDeLijnRealtime($locid);
        }
        $data = $this->combineRT($data,$rtdata);
        $data["location"] = DLStations::getStationFromId($locid);
        return $data;
    }

    /**
     * Returns true if given time is in a window of 3 hours around the current time
     */
    private function realtime_needed(){
        date_default_timezone_set("Europe/Brussels");
        $a = $this->day == date("d") && $this->hour >= date("H")-1 && $this->hour <= date("H")+1  && $this->year == date("Y") && $this->month == date("m");
        date_default_timezone_set("UTC");
        return $a;
    }

    /**
     * Returns static data for a certain date/time
     */
    private function getLiveboardFromDB($locid){
        /*
         * We need to get it from the database.
         * we'll do the request on segments first: where all the stopid's match, and where the time is in the hour interval we need
         * then we're going to join tables for extra information: trips for ids, calendar is needed, and we need a where clausule for the date
         */
        date_default_timezone_set("Europe/Brussels");
        
        $starthour = $this->hour;
        $endhour = $this->hour + 3; // for next 3 hours
        if($endhour<10){
            $endhour = "0" . $endhour;
        }
        
        $arguments = array(":stopid" => $locid,
                           ":starttime"=> mktime($starthour,$this->minutes,0,$this->month,$this->day,$this->year)
        );
        //var_dump($arguments);
        
        $results = R::getAll("SELECT STOPIDENTIFIER,ROUTEPUBLICIDENTIFIER,SEGMENTSTART,SEGMENTEND,ROUTEDESCRPTION,ROUTEIDENTIFIER,VSCDATE FROM DL_segments s JOIN DL_trips t ON s.TRIPID = t.TRIPID JOIN DL_routes r on r.ROUTEID = t.ROUTEID JOIN DL_calendar c on t.VSID = c.VSID JOIN DL_stops stops ON s.STOPID = stops.STOPID WHERE STOPIDENTIFIER = :stopid AND STR_TO_DATE(CONCAT_WS(' ',c.VSCDATE, s.SEGMENTSTART),'%Y/%m/%d %h:%i') > from_unixtime(:starttime) ORDER BY STR_TO_DATE(CONCAT_WS(' ',c.VSCDATE, s.SEGMENTSTART),'%Y/%m/%d %h:%i') LIMIT 30", $arguments); //
        $stops = array();
        foreach($results as &$result){
            $stop = array();
            $stop["line"] = $result["ROUTEPUBLICIDENTIFIER"];
            $stop["vehicle"] = $result["ROUTEIDENTIFIER"];
            $stop["direction"] = $result["ROUTEDESCRPTION"];
            
            preg_match("/(....)\/(..)\/(..)/smi",$result["VSCDATE"],$matches);
            $y = $matches[1];
            $m = $matches[2];
            $d = $matches[3];
            preg_match("/(..):(..)/smi",$result["SEGMENTSTART"],$matches);
            $h = $matches[1];
            $i = $matches[2];
            $stop["time"] = mktime($h,$i,0,$m,$d,$y);
            $stop["iso8601"] = date("c",$stop["time"]);
            $stops[] = $stop;
            
        }
        
        date_default_timezone_set("UTC");
//        var_dump($results);
        
        return $stops;
    }

    /**
     * Gets real-time information from De Lijn website
     */
    private function scrapeDeLijnRealtime($locid){
        $data = TDT::HttpRequest("http://reisinfo.delijn.be/realtime/halte/" . $locid);
        $data = $data->data;
        preg_match_all("/<tr class=\".*?\"(.*?)(?=<tr class=\".*?\")/smi",$data,$matches);
        $results = array();
        $results["station"] = DLStations::getStationFromId($this->locid);
        $results["departures"] = array();
        date_default_timezone_set("Europe/Brussels");
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
                $time = $match[1];
                preg_match("/(\d+)'/smi",$time,$match);
                if(!isset($match[1])){
                    preg_match("/(\d\d):(\d\d)/smi",$time,$match);
                    if(isset($match[1]) && isset($match[2])){
                        $time = mktime($match[1],$match[2],0,$this->month,$this->day,$this->year);
                    }
                }else{
                    $time = mktime(date("H"), date("i") + $match[1],0,$this->month,$this->day,$this->year);
                }
            }else{
                $time = date("U");
            }
            
            $dep["time"] = $time;
            $dep["iso8601"] = date("c",$time);
            
            //DEV:$dep["dump"] = $row;
            $results["departures"][] = $dep;
        }
        date_default_timezone_set("UTC");
        return $results;
    }

    private function combineRT($data,$rtdata){
        return array("departures" => $data, "real-time" => $rtdata);
    }
}

?>
