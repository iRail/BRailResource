<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 * @author Pieter Colpaert
 * @author Jens Segers
 * @license AGPLv3
 *
 */

include_once("custom/packages/iRailTools.class.php");
include_once("custom/packages/AbstractiRailResource.class.php");
class iRailVehicle extends AbstractiRailResource {
    
    protected $id;
    protected $lang;
    
    public function __construct() {
        parent::__construct();
    }
    
    public static function getParameters() {
        return array("id" => "Specify the vehicle id. This should be according the iRail specification",
                     "year"=> "YYYY - 4 digits describing the year of the connection",
                     "month"=> "MM - 2 digits describing the month of the connection",
                     "day"=> "DD - 2 digits describing the day of the connection",
                     "hour"=> "HH - 2 digits describing the hour of the connection",
                     "minutes"=> "II - 2 digits describing the minutes of the connection",
        );
    }
    
    public static function getRequiredParameters() {
        return array("id","year","month","day","hour","minutes");
    }
    
    public function setParameter($key, $val) {
        if ($key == "id") {
            $this->id = $val;
        }else{
            $this->$key = $val;
        }
        
    }
    
    public static function getDoc() {
        return "Return vehicle information";
    }
}
?>
