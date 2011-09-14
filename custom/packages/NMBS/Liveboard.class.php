<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author Jan Vansteenlandt <jan aŧ iRail.be>
 * @author Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */
include_once("modules/NMBS/Stations.class.php");
include_once("modules/iRailTools.class.php");

class Liveboard extends AResource{

     private $lang;
     private $system;
     private $time;
     private $direction;
     private $date;
     private $station;     

     public function __construct(){
	  $this->time = date("H:i");	  
	  $this->direction = "departures";
     }

     public static function getParameters(){
	  return array("station" => "This is a name of a station or an ID as specified by the iRail API: example: BE.NMBS.0942484",
		       "time" => "time of the requested liveboard concatenated. The 3pm would be: 1500",
		       "direction" => "Do you want to have the arrivals or the departures. Values: ARR or DEP, default = DEP",
		       "lang" => "Language for the stations fr ,nl",
		       "system" => "The name of the public transport company: for instance De Lijn, or NMBS",
		       "date" => "date of depart/arrival - mmddyy"
	       );
     }

     public static function getRequiredParameters(){
	  return array("station");
     }

     public function setParameter($key,$val){
	  if($key == "lang"){
	       $this->lang = $val;
	  }

	  if($key == "system" && in_array($val, iRailTools::ALLOWED_SYSTEMS)){
	       $this->system = $val;
	  }

	  if($key == "time" && $val != ""){
	       $this->time = $val;
	  }

	  if($key == "direction" && $val != ""){
	       $this->direction = $val;
	  }
	  
	  if($key=="station" && $val != ""){
	       $this->station = $val;
	  }

	  if($key == "date" && $val != ""){
	       $this->date = $val;
	  }
     }

     public function call(){
	  $url = "http://api.irail.be/liveboard/?";
	  $url =$url."1=1";
	  if($this->station != "" || ! is_null($this->station)){
	       $url.="&station=".$this->station;
	       
	  }
	  
	  $request = TDT::HttpRequest($url);
          if(isset($request->error)){
              throw new HttpOutTDTException($url);
          }
          
	  $object = simplexml_load_string($request->data);
	  
	  return $object;
	  
	  
	  /*
	  $dummyresult = new stdCall();
	  $body = "";
	  $time = $this->time;
          //we want data for 1 hour. But we can only retrieve 15 minutes per request
	  for($i=0; $i < 4; $i++){
	       $scrapeUrl = "http://www.railtime.be/mobile/SearchStation.aspx";
//	       $scrapeUrl .= "?l=EN&tr=". $time . "-15&s=1&sid=" . stations::getRTID($station, $lang) . "&da=" . $this->direction . "&p=2";
	       $body .= TDT::HttpRequest($scrapeUrl);
	       $time = iRailTools::addQuarter($time);
	  }
//	  return $this->parse($body);
return $dummyresult;*/
     }

/**
 * It will remove the duplicates from an array the php way. Since a PHP array will need to recopy everything to be reindexed, I figured this would go faster if we did the deleting when copying.
 */
     private static function removeDuplicates($nodes){
	  $newarray = array();
	  for($i = 0; $i < sizeof($nodes); $i++){
	       $duplicate = false;
	       for($j = 0; $j < $i; $j++){
		    if($nodes[$i]->vehicle == $nodes[$j]->vehicle){
			 $duplicate = true;
			 continue;
		    }
	       }
	       if(!$duplicate){
		    $newarray[sizeof($newarray)] = $nodes[$i];
	       }
	  }
	  return $newarray;
     }
     

     public static function getAllowedPrintMethods(){
	  return array("xml", "json", "php", "jsonp");
     }

     public static function getDoc(){
	  return "Liveboard will return the next arrivals or departures in a station.";
     }
}

?>
