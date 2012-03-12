<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author Jens Segers
 * @author  Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */

include_once("custom/packages/NMBS/Stations.class.php");
include_once("custom/packages/iRailConnections.class.php");

class NMBSConnections extends iRailConnections {
    
    private $typeOfTransport;
    private $results;
    
    public function __construct() {
        parent::__construct();
        //standard number of results to fetch from Hafas
        $this->results = 6;
    }
    public function call() {
        return $this->connectionsBetween($this->from, $this->to);
    }
    
    public function connectionsBetween($from, $to){
        $stations = NMBSStations::getStationsFromName(array($from, $to));
        $url = "http://hari.b-rail.be/Hafas/bin/extxml.exe";
        $trainsonly = "0111111000000000";
        
        if ($this->timeSel == "departure") {
            $timeSel = 0;
        } else if ($this->timeSel == "arrival") {
            $timeSel = 1;
        } else {
            $timeSel = 1;
        }
        $post = '<?xml version="1.0 encoding="iso-8859-1"?>
            <ReqC ver="1.1" prod="iRail" lang="' . $this->lang . '">
            <ConReq>
            <Start min="0">
            <Station externalId="' . $stations[0]->id . '" distance="0">
            </Station>
            <Prod prod="' . $trainsonly . '">
            </Prod>
            </Start>
            <Dest min="0">
            <Station externalId="' . $stations[1]->id . '" distance="0">
            </Station>
            </Dest>
            <Via>
            </Via>
            <ReqT time="' . $this->hour . ":" . $this->minutes . '" date="' . $this->year . $this->month . $this->day . '" a="' . $timeSel . '">
            </ReqT>
            <RFlags b="' . $this->results * $timeSel . '" f="' . $this->results * - ($timeSel - 1) . '">
            </RFlags>
            <GISParameters>
            <Front>
            </Front>
            <Back>
            </Back>
            </GISParameters>
            </ConReq>
            </ReqC>';
        
        $options = array("method" => "POST", "data" => $post, "cache-time" => 60);//cache for 1 minute
        $request = TDT::HttpRequest($url, $options);
        
        $xml = new SimpleXMLElement($request->data);
        
        // clean station information 
        unset($stations[0]->id);
        unset($stations[0]->type);
        unset($stations[1]->id);
        unset($stations[1]->type);
        

        if(isset($xml->ConRes) && isset($xml->ConRes->ConnectionList)){
            $data = $xml->ConRes->ConnectionList;
            $connections = array();
            foreach($data->Connection as $con){
                $trips = array();
                foreach($con->ConSectionList as $tr){
                    $trips[] = $tr->ConSection;
                }
                $connections[] = $trips;
            }
            return $connections;
        }
        else{
            throw new Exception("Could not calculate your route. Please try again later or correct the URI.", 500);
        }
        
    }
}

?>
