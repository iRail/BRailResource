<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author Jens Segers
 * @author Pieter Colpaert
 * @license AGPLv3
 */
include_once("custom/packages/iRailTools.class.php");
include_once("custom/packages/AbstractiRailResource.class.php");

abstract class iRailLiveboard extends AbstractiRailResource {
    
    public function __construct() {
        parent::__construct();
        $this->direction = "departures";
    }
    
    public static function getParameters() {
        return array("location" => "Name of the location",
                     "year"=> "YYYY - 4 digits describing the year of the liveboard.",
                     "month"=> "MM - 2 digits describing the month of the liveboard",
                     "day"=> "DD - 2 digits describing the day of the liveboard",
                     "hour"=> "HH - 2 digits describing the hour of the liveboard.";
                     "minutes"=> "II - 2 digits describing the minutes of the liveboard",
                     "direction" => "Do you want to have the 'arrivals' or the 'departures' (default)"
        );
    }
    
    public static function getRequiredParameters() {
        return array("location","year","month","day","hour","minutes");
    }
    
    public function setParameter($key, $val) {
        if ($key == "timerange" && $val != "") {
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
        }elseif ($key == "direction" && $val != "") {
            $allowed = array("departures", "arrivals");
            $this->direction = $val;
            
            if(!in_array($val, $allowed))
                throw new Exception("The direction parameter was not correctly formatted");
        }elseif ($key == "location" && $val != "") {
            $this->location = $val;
        }else{
            $this->$key=$val;
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
    
    
    public static function getDoc() {
        return "Liveboard will return the next arrivals or departures in a specific station.";
    }
}

?>
