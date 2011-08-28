<?php
/**
 * @copyright (C) 2011 by iRail vzw/asbl
 * @author  Pieter Colpaert <pieter aŧ iRail.be>
 * @license  AGPLv3
 *
 * Lists all stations for a certain system
 */

class Stations extends AResource{

    private $lang = "EN";

    /**
     * If language has not been given, just return the english ones
     */
    public static function getParameters(){
	return array("lang" => "Language for the stations");
    }

    /**
     * No required parameters for the stations resource
     */
    public static function getRequiredParameters(){
	return array();
    }

    public function setParameter($key,$val){
	if($key == "lang"){
	    $this->lang = $val;
	}
    }

    /**
     * This function should get all stations from the NMBS/SNCB and return them.
     * Can't we get all information from DBPedia?
     * Does Open Street Map has a SPARQL end-point?
     */
    public function call(){
        return new StdClass();
    }

    public static function getAllowedPrintMethods(){
	return array("xml","json","php", "jsonp","html", "kml");
    }

    public static function getDoc(){
	return "Stations will return a list of all known stops of a system";
    }
}

?>