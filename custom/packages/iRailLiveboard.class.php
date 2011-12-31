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
                     "hour"=> "HH - 2 digits describing the hour of the liveboard.",
                     "minutes"=> "II - 2 digits describing the minutes of the liveboard",
                     "direction" => "Do you want to have the 'arrivals' or the 'departures' (default)"
        );
    }
    
    public static function getRequiredParameters() {
        return array("location","year","month","day","hour","minutes");
    }
    
    public function setParameter($key, $val) {
        $this->$key=$val;
    }
    
    public static function getDoc() {
        return "Liveboard will return the next arrivals or departures in a specific station.";
    }
}

?>
