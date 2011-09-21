<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author Jens Segers
 * @author  Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */

include_once (dirname(__FILE__) . "/../iRailStations.class.php");

class Stations extends iRailStations {
    
    /**
     * This function should get all stations from the NMBS/SNCB and return them.
     * Can't we get all information from DBPedia?
     * Does Open Street Map has a SPARQL end-point?
     */
    public function call() {
        // TODO
        $o = new StdClass();
        return $o;
    }
    
    public static function getAirportFromCode($code) {
        $url = "http://www.webservicex.net/airport.asmx/getAirportInformationByAirportCode?airportCode=".$code;
        
        $request = TDT::HttpRequest($url);
        if (isset($request->error)) {
            throw new HttpOutTDTException($url);
        }
        
        $data = new SimpleXMLElement(html_entity_decode($request->data));
        $node = $data->NewDataSet->Table[0];
        
        $airport = new stdClass();
        $airport->code = (string) $node->AirportCode;
        $airport->country = (string) $node->Country;
        $airport->name = ucwords(strtolower($node->CityOrAirportName));
        $airport->latitude = (double) ($node->LatitudeDegree.".".$node->LatitudeMinute.$node->LatitudeSecond);
        $airport->longitude = (double) ($node->LongitudeDegree.".".$node->LongitudeMinute.$node->LongitudeSecond);
        
        if($node->LatitudeNpeerS == "Z")
            $airport->latitude *= -1;
        if($node->LongitudeEperW == "W")
            $airport->longitude *= -1;
        
        return $airport;
    }
}

?>