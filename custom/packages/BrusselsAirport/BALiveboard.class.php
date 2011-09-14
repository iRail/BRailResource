<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author Jan Vansteenlandt <jan aŧ iRail.be>
 * @author Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */

class BALiveboard extends AResource{

     private $lang;
     private $system;
     private $time;
     private $direction;

     public function __construct(){
	  $this->time = date("H:i");	  
	  $this->direction = "departures";
     }

     public static function getParameters(){
	  return array("direction" => "Do you want to have the arrivals or the departures. Values: ARR or DEP, default = DEP",
		       "lang" => "Language for the stations fr ,nl"
	       );
     }

     public static function getRequiredParameters(){
	  return array();
     }

     public function setParameter($key,$val){
	  if($key == "lang"){
	       $this->lang = $val;
	  }

	  if($key == "direction" && $val != ""){
	       $this->direction = $val;
	  }
     }

     public function call(){
         //todo - time parameters
         $dayminone = date("d")-1;
         $hourminone = date("h") -1;
         
         $url = "http://www.pathfinder-xml.com/development/xml?info.flightHistoryGetRecordsRequestedData.csvFormat=false&info.specificationDateRange.departureDateTimeMax=". urlencode(date("Y") ."-". date("m") ."-". date("d") ."T". date("h") .":". date("i") ) . "&info.flightHistoryGetRecordsRequestedData.codeshares=true&login.guid=34b64945a69b9cac%3A31589bfe%3A12ac91d6cf3%3A-6e16&info.specificationDepartures[0].airport.airportCode=BRU&Service=FlightHistoryGetRecordsService&info.specificationDateRange.departureDateTimeMin=". urlencode(date("Y")."-" . date("m") ."-". date("d") ."T" . $hourminone .":". date("i") );
         
	  
	  
	  $request = TDT::HttpRequest($url);
          if(isset($request->error)){
              throw new HttpOutTDTException($url);
          }
          
	  $object = simplexml_load_string($request->data);
	  
          $result = new stdClass();

	  return $object;
     }  

     public static function getAllowedPrintMethods(){
	  return array("xml", "json", "php", "jsonp");
     }

     public static function getDoc(){
	  return "Liveboard will return the next arrivals or departures in a station.";
     }
}

?>
