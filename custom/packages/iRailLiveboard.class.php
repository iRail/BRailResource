<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author Jens Segers
 * @license AGPLv3
 *
 */
include_once (dirname(__FILE__) . "/iRailTools.class.php");

abstract class iRailLiveboard extends AResource {
    
    // parameters
    protected $lang;
    protected $date;
    protected $time;
    protected $timerange;
    protected $direction;
    protected $location;
    
    protected $startdatetime; // DateTime
    protected $enddatetime; // DateTime
    
    public function __construct() {
        $this->time = date("H:i\+Z");
        $this->date = date("Y-m-d");
        $this->lang = "en";
        $this->direction = "departures";
        
        $this->timerange = new DateInterval("PT1H0M"); // 1 hour time interval
        $this->startdatetime = new DateTime($this->date."T".$this->time);
        $this->enddatetime = clone $this->startdatetime;
        $this->enddatetime->add($this->timerange);
    }
    
    public static function getParameters() {
        return array("location" => "Name of the location", "time" => "Time of the requested liveboard in hh:mm", "timerange"=>"A timerange for results in hh:mm", "direction" => "Do you want to have the 'arrivals' or the 'departures' (default)", "lang" => "Language", "date" => "date of depart/arrival - mm.dd.yyyy");
    }
    
    public static function getRequiredParameters() {
        return array("location");
    }
    
    public function setParameter($key, $val) {
        if ($key == "lang") {
            $this->lang = $val;
        }
        
        elseif ($key == "time" && $val != "") {
            $val = str_replace(array("-","/"), ":", $val);
            $val = str_replace(" ", "+", $val);
            $this->time = $val;
            
            $this->updateTime();
        }
        
        elseif ($key == "date" && $val != "") {
            $val = str_replace(array("-","."), "/", $val);
            $this->date = $val;
            
            $this->updateTime();
        }
        
        elseif ($key == "timerange" && $val != "") {
            try {
                $pieces = explode(":",$val);
                if(count($pieces) >= 2)
                    $this->timerange = new DateInterval("PT".$pieces[0]."H".$pieces[1]."M");
                else
                    $this->timerange = new DateInterval("PT".$pieces[0]."H00M");
            }
            catch(Exception $e) {
                throw new Exception("Your timerange parameter was not correctly formatted");
            }
            
            $this->updateTime();
        }
        
        elseif ($key == "direction" && $val != "") {
            $allowed = array("departures", "arrivals");
            $this->direction = $val;
            
            if(!in_array($val, $allowed))
                throw new Exception("The direction parameter was not correctly formatted");
        }
        
        elseif ($key == "location" && $val != "") {
            $this->location = $val;
        }
    }
    
    protected function updateTime() {
        try {
            $this->startdatetime = new DateTime($this->date."T".$this->time);
            //$this->startdatetime->setTimezone(new DateTimeZone("UTC"));
            $this->enddatetime = clone $this->startdatetime;
            $this->enddatetime->add($this->timerange);
        }
        catch(Exception $e) {
            throw new Exception("The time/date parameter was not correctly formatted");
        }
    }
    
    public function call() {}
    
    public static function getAllowedPrintMethods() {
        return array("xml", "json", "php", "jsonp");
    }
    
    public static function getDoc() {
        return "Liveboard will return the next arrivals or departures in a specific station.";
    }
}

?>
