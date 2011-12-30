<?php
/**
 * @copyright (C) 2011 by iRail vzw/asbl
 * @author  Jens Segers
 * @license  AGPLv3
 *
 * Lists all connections between 2 stations
 */

include_once ("custom/packages/iRailTools.class.php");
include_once ("custom/packages/AbstractiRailResource.class.php");

class iRailConnections extends AbstractiRailResource {
    
    protected $to;
    protected $from;
    protected $timeSel;
    
    public function __construct() {
        parent::__construct();
        $this->timeSel = "arrival";
    }
    
    public static function getParameters() {
        return array("from" => "Station from",
                     "to" => "Station to",
                     "year"=> "YYYY - 4 digits describing the year of the connection",
                     "month"=> "MM - 2 digits describing the month of the connection",
                     "day"=> "DD - 2 digits describing the day of the connection",
                     "hour"=> "HH - 2 digits describing the hour of the connection",
                     "minutes"=> "II - 2 digits describing the minutes of the connection",
                     "timeSel"=>"Interpret time as time of departure or arrival");    
    }
    
    public static function getRequiredParameters() {
        return array("from", "to", "year","month","day","hour","minutes");
    }
    
    public function setParameter($key, $val) {
        if ($key == "to" && $val != "") {
            $this->to = $val;
        }elseif ($key == "from" && $val != "") {
            $this->from = $val;
        }elseif ($key == "timeSel" && $val != "") {
            $this->timeSel = $val;
        }else{
            $this->$key = $val;
        }
        
    }
    
    public static function getDoc() {
        return "Get connections between 2 stations. You can use it like this: data.iRail.be/Company/Connections/station1/station2/YYYY/MM/DD/HH/II/";
    }
}

?>