<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author Jan Vansteenlandt <jan aŧ iRail.be>
 * @author Pieter Colpaert <pieter aŧ iRail.be>
 * @author Jens Segers
 * @license AGPLv3
 *
 */

include_once (dirname(__FILE__) . "/../iRailLiveboard.class.php");
include_once (dirname(__FILE__) . "/Stations.class.php");

class AirsportsLiveboard extends iRailLiveboard {
    
    public function call() {
        $o = new stdClass();
        
        $airport = Stations::getAirportFromCode($this->location);
        
        // remove unwanted properties
        unset($airport->code);
        unset($airport->country);

        $o->location = $airport;
        $o->{$this->direction} = $this->getLiveboard($this->location, $this->startdatetime, $this->enddatetime, $this->direction);
        
        return $o;
    }
    
    public static function getLiveboard($airport, $startdatetime, $enddatetime, $direction) {
        $url = "http://www.pathfinder-xml.com/development/xml?info.flightHistoryGetRecordsRequestedData.csvFormat=false&info.specificationDateRange.".substr($direction,0,-1)."DateTimeMax=" . urlencode($enddatetime->format("Y-m-d\TH:i")) . "&info.flightHistoryGetRecordsRequestedData.codeshares=true&login.guid=34b64945a69b9cac%3A31589bfe%3A12ac91d6cf3%3A-6e16&info.specification".ucfirst($direction)."[0].airport.airportCode=".$airport."&Service=FlightHistoryGetRecordsService&info.specificationDateRange.".substr($direction,0,-1)."DateTimeMin=" . urlencode($startdatetime->format("Y-m-d\TH:i"));
        
        $request = TDT::HttpRequest($url);
        if (isset($request->error)) {
            throw new HttpOutTDTException($url);
        }
        
        $xml = new SimpleXMLElement($request->data);
        
        $liveboard = array();
        
        foreach($xml->FlightHistory as $flight) {
            $item = new stdClass();
            
            if($direction == "departures") {
                $published = new DateTime($flight["PublishedDepartureDate"]);
                if(isset($flight["EstimatedGateDepartureDate"]))
                    $estimated = new DateTime($flight["EstimatedGateDepartureDate"]);
                elseif(isset($flight["ScheduledGateDepartureDate"]))
                    $estimated = new DateTime($flight["ScheduledGateDepartureDate"]);
                else
                    $estimated = $published;
            }
            else {
                $published = new DateTime($flight["PublishedArrivalDate"]);
                if(isset($flight["PublishedArrivalDate"]))
                    $estimated = new DateTime($flight["EstimatedGateArrivalDate"]);
                elseif(isset($flight["EstimatedGateArrivalDate"]))
                    $estimated = new DateTime($flight["ScheduledGateArrivalDate"]);
                else
                    $estimated = $published;
            }
            
            $time = $published->getTimestamp();
            
            $delay = date_diff($estimated, $published);
            $delay = $delay->h*3600 + $delay->i*60 + $delay->s;
            if($estimated<$published)
                $delay *= -1;
                
            // use local info
            //$direction = (string) $flight->Destination["Name"];
            
            // use external info
            $airport = Stations::getAirportFromCode((string) $flight->Destination["AirportCode"]);
            $direction = $airport->name;
            
            if(isset($flight->Airline["IATACode"]))
                $vehicle = $flight->Airline["IATACode"] . " " . $flight["FlightNumber"];
            else
                $vehicle = $flight->Airline["AirlineCode"] . " " . $flight["FlightNumber"];
            
                
            $item->time = $time;
            $item->delay = $delay;
            $item->direction = $direction;
            $item->vehicle = $vehicle;
                
            $liveboard[] = $item;
        }
        
        return $liveboard;
    }
}

?>
