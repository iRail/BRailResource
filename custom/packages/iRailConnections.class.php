<?php
/**
 * @copyright (C) 2011 by iRail vzw/asbl
 * @author  Jens Segers
 * @license  AGPLv3
 *
 * Lists all connections between 2 stations
 */

include_once (dirname(__FILE__) . "/iRailTools.class.php");

class iRailConnections extends AResource {
    
    // parameters
    protected $lang;
    protected $time;
    protected $date;
    protected $to;
    protected $from;
    protected $timeSel;
    
    protected $datetime; // DateTime
    
    public function __construct() {
        $this->time = date("H:i\+Z");
        $this->date = date("Y-m-d");
        $this->lang = "en";
        $this->timeSel = "arrival";
        
        $this->datetime = new DateTime($this->date."T".$this->time);
    }
    
    public static function getParameters() {
        return array("from" => "Station from", "to" => "Station to", "time"=>"Time of arrival or departure", "date"=>"Date of arrival or departure", "lang"=>"Language", "timeSel"=>"Interpret time as time of departure or arrival");
    }
    
    public static function getRequiredParameters() {
        return array("from", "to");
    }
    
    public function setParameter($key, $val) {
        
        if ($key == "time" && $val != "") {
            $val = str_replace(array("-","/"), ":", $val);
            $this->time = $val;
            
            // update datetime
            $this->datetime = new DateTime($this->date."T".$this->time);
        }
        
        elseif ($key == "date" && $val != "") {
            $val = str_replace(array("-","."), "/", $val);
            $this->date = $val;
            
            // update datetime
            $this->datetime = new DateTime($this->date."T".$this->time);
        }

        elseif ($key == "to" && $val != "") {
            $this->to = $val;
        } 

        elseif ($key == "from" && $val != "") {
            $this->from = $val;
        } 

        elseif ($key == "lang" && $val != "") {
            $this->lang = $val;
        }

        elseif ($key == "timeSel" && $val != "") {
            $this->timeSel = $val;
        }
    }
    
    public function call() {}
    
    public static function getAllowedPrintMethods() {
        return array("xml", "json", "php", "jsonp", "html");
    }
    
    public static function getDoc() {
        return "Get connections between 2 stations";
    }
}

?>