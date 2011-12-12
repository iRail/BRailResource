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

class AirportsLiveboard extends iRailLiveboard {
    
    public function call() {
        return $this->getLiveboard($this->location, $this->startdatetime, $this->enddatetime, $this->direction);
    }
    
    public static function getLiveboard($airport, $startdatetime, $enddatetime, $direction) {
		$o = new stdClass();
	
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
					
				if(!isset($o->location) || $o->location->code != $airport) {
                	$o->location = new stdClass();
                	$o->location->code = (string)$flight->Origin["AirportCode"];
                	$o->location->name = (string)$flight->Origin["Name"];
                }
				
				// use local info
				$destination = (string) $flight->Destination["Name"];
				
				// use external info
				//$airport = AirportsStations::getAirportFromCode((string) $flight->Destination["AirportCode"]);
				//$destination = $airport->name;
            }
            else {
                $published = new DateTime($flight["PublishedArrivalDate"]);
                if(isset($flight["PublishedArrivalDate"]))
                    $estimated = new DateTime($flight["EstimatedGateArrivalDate"]);
                elseif(isset($flight["EstimatedGateArrivalDate"]))
                    $estimated = new DateTime($flight["ScheduledGateArrivalDate"]);
                else
                    $estimated = $published;
					
				if(!isset($o->location) || $o->location->code != $airport) {
                	$o->location = new stdClass();
                	$o->location->code = (string)$flight->Destination["AirportCode"];
                	$o->location->name = (string)$flight->Destination["Name"];
                }
				
				
				// use local info
				$destination = (string) $flight->Origin["Name"];
				
				// use external info
				//$airport = AirportsStations::getAirportFromCode((string) $flight->Origin["AirportCode"]);
				//$destination = $airport->name;
            }
            
            $time = $published->getTimestamp();
            
            $delay = date_diff($estimated, $published);
            $delay = $delay->h*3600 + $delay->i*60 + $delay->s;
            if($estimated<$published)
                $delay *= -1;
            
            if(isset($flight->Airline["IATACode"]))
                $vehicle = $flight->Airline["IATACode"] . " " . $flight["FlightNumber"];
            else
                $vehicle = $flight->Airline["AirlineCode"] . " " . $flight["FlightNumber"];
            
                
            $item->time = $time;
            $item->delay = $delay;
            $item->direction = $destination;
            $item->vehicle = $vehicle;
                
            $liveboard[] = $item;
        }
		
		$o->{$direction} = $liveboard;
        
        return $o;
    }
}

?>
