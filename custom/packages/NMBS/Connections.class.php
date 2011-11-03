<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author Jens Segers
 * @author  Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */

include_once (dirname(__FILE__) . "/Stations.class.php");
include_once (dirname(__FILE__) . "/../iRailConnections.class.php");

class Connections extends iRailConnections {
    
    private $typeOfTransport;
    private $results;
    
    public function __construct() {
        parent::__construct();
        $this->typeOfTransport = "train";
        $this->results = 0; // what does this do?
    }
    
    public static function getParameters() {
        $parameters = parent::getParameters();
        $parameters["typeOfTransport"] = "Allowed modes of transport separated by a semicolon eg: bus;train;taxi";
        return $parameters;
    }
    
    public function setParameter($key, $val) {
        if ($key == "typeOfTransport" && $val != "") {
            if (is_array($val))
                $this->typeOfTransport = implode(";", $val);
            else
                $this->typeOfTransport = $val;
        }
        else {
            parent::setParameter($key, $val);
        }
    }
    
    public function call() {
        $o = new stdClass();
        $o->connections = $this->connectionsBetween($this->from, $this->to, $this->lang, $this->datetime, $this->results, $this->timeSel, $this->typeOfTransport);
        return $o;
    }
    
    public static function connectionsBetween($from, $to, $lang, $datetime, $results, $timeSel, $typeOfTransport) {
        $stations = Stations::getStationsFromName(array($from, $to));
        
        $url = "http://hari.b-rail.be/Hafas/bin/extxml.exe";
        
        if ($typeOfTransport == "trains") {
            $trainsonly = "0111111000000000";
        } else if ($typeOfTransport == "all") {
            $trainsonly = "1111111111111111";
        } else {
            $trainsonly = "0111111000000000";
        }
        
        if ($timeSel == "departure") {
            $timeSel = 0;
        } else if ($timeSel == "arrival") {
            $timeSel = 1;
        } else {
            $timeSel = 1;
        }
        
        $post = '<?xml version="1.0 encoding="iso-8859-1"?>
            <ReqC ver="1.1" prod="iRail" lang="' . $lang . '">
            <ConReq>
            <Start min="0">
            <Station externalId="' . $stations[0]->id . '" distance="0">
            </Station>
            <Prod prod="' . $trainsonly . '">
            </Prod>
            </Start>
            <Dest min="0">
            <Station externalId="' . $stations[1]->id . '" distance="0">
            </Station>
            </Dest>
            <Via>
            </Via>
            <ReqT time="' . $datetime->format("H:i") . '" date="' . $datetime->format("Ymd") . '" a="' . $timeSel . '">
            </ReqT>
            <RFlags b="' . $results * $timeSel . '" f="' . $results * - ($timeSel - 1) . '">
            </RFlags>
            <GISParameters>
            <Front>
            </Front>
            <Back>
            </Back>
            </GISParameters>
            </ConReq>
            </ReqC>';
        
        $options = array("method" => "POST", "data" => $post);
        $request = TDT::HttpRequest($url, $options);
        
        $xml = new SimpleXMLElement($request->data);
        
        // clean station information
        unset($stations[0]->id);
        unset($stations[0]->type);
        unset($stations[1]->id);
        unset($stations[1]->type);
        
        $connection = array();
        $i = 0;
        if (isset($xml->ConRes->ConnectionList->Connection)) {
            foreach ($xml->ConRes->ConnectionList->Connection as $conn) {
                $connection[$i] = new stdClass();
                $connection[$i]->departure = new stdClass();
                $connection[$i]->arrival = new stdClass();
                $connection[$i]->duration = iRailTools::transformDuration($conn->Overview->Duration->Time);
                $connection[$i]->departure->station = $stations[0];
                $connection[$i]->departure->time = iRailTools::transformTime($conn->Overview->Departure->BasicStop->Dep->Time, $conn->Overview->Date);
                $connection[$i]->departure->direction = (trim($conn->Overview->Departure->BasicStop->Dep->Platform->Text));
                $connection[$i]->departure->platform = new stdClass();
                $connection[$i]->departure->platform->name = trim($conn->Overview->Departure->BasicStop->Dep->Platform->Text);
                $connection[$i]->arrival->time = iRailTools::transformTime($conn->Overview->Arrival->BasicStop->Arr->Time, $conn->Overview->Date);
                $connection[$i]->arrival->platform = new stdClass();
                $connection[$i]->arrival->platform->name = trim($conn->Overview->Arrival->BasicStop->Arr->Platform->Text);
                $connection[$i]->arrival->station = $stations[1];
                
                //Delay and platform changes
                //TODO: get Delay from railtime instead - much better information
                $delay0 = 0;
                $delay1 = 0;
                $platformChanged0 = false;
                $platformChanged1 = false;
                if ($conn->RtStateList->RtState["value"] == "HAS_DELAYINFO") {
                    
                    $delay0 = iRailTools::transformTime($conn->Overview->Departure->BasicStop->StopPrognosis->Dep->Time, $conn->Overview->Date) - $connection[$i]->departure->time;
                    if ($delay0 < 0) {
                        $delay0 = 0;
                    }
                    //echo "delay: " .$conn->Overview -> Departure -> BasicStop -> StopPrognosis -> Dep -> Time . "\n";
                    $delay1 = iRailTools::transformTime($conn->Overview->Arrival->BasicStop->StopPrognosis->Arr->Time, $conn->Overview->Date) - $connection[$i]->arrival->time;
                    if ($delay1 < 0) {
                        $delay1 = 0;
                    }
                    if (isset($conn->Overview->Departure->BasicStop->StopPrognosis->Dep->Platform->Text)) {
                        $platform0 = trim($conn->Overview->Departure->BasicStop->StopPrognosis->Dep->Platform->Text);
                        $platformChangedl0 = true;
                    }
                    if (isset($conn->Overview->Arrival->BasicStop->StopPrognosis->Arr->Platform->Text)) {
                        $platform1 = trim($conn->Overview->Arrival->BasicStop->StopPrognosis->Arr->Platform->Text);
                        $platformChanged1 = true;
                    }
                }
                $connection[$i]->departure->delay = $delay0;
                $connection[$i]->departure->platform->changed = $platformChanged0;
                $connection[$i]->arrival->delay = $delay1;
                $connection[$i]->arrival->platform->changed = $platformChanged1;
                
                $trains = array();
                $vias = array();
                $directions = array();
                $j = 0;
                $k = 0;
                $connectionindex = 0;
                //yay for spaghetti code.
                if (isset($conn->ConSectionList->ConSection)) {
                    foreach ($conn->ConSectionList->ConSection as $connsection) {
                        
                        if (isset($connsection->Journey->JourneyAttributeList->JourneyAttribute)) {
                            foreach ($connsection->Journey->JourneyAttributeList->JourneyAttribute as $att) {
                                if ($att->Attribute["type"] == "NAME") {
                                    $trains[$j] = str_replace(" ", "", $att->Attribute->AttributeVariant->Text);
                                    $j++;
                                } else if ($att->Attribute["type"] == "DIRECTION") {
                                    $directions[$k] = Stations::getStationFromName(trim($att->Attribute->AttributeVariant->Text), $lang)->name;
                                    $k++;
                                }
                            }
                            
                            if ($conn->Overview->Transfers > 0 && strcmp($connsection->Arrival->BasicStop->Station['name'], $conn->Overview->Arrival->BasicStop->Station['name']) != 0) {
                                //current index for the train: j-1
                                $departDelay = 0; //Todo: NYImplemented
                                $connarray = $conn->ConSectionList->ConSection;
                                $departTime = iRailTools::transformTime($connarray[$connectionindex + 1]->Departure->BasicStop->Dep->Time, $conn->Overview->Date);
                                $departPlatform = trim($connarray[$connectionindex + 1]->Departure->BasicStop->Dep->Platform->Text);
                                $arrivalTime = iRailTools::transformTime($connsection->Arrival->BasicStop->Arr->Time, $conn->Overview->Date);
                                $arrivalPlatform = trim($connsection->Arrival->BasicStop->Arr->Platform->Text);
                                $arrivalDelay = 0; //Todo: NYImplemented
                                

                                $vias[$connectionindex] = new stdClass();
                                $vias[$connectionindex]->arrival = new stdClass();
                                $vias[$connectionindex]->arrival->time = $arrivalTime;
                                $vias[$connectionindex]->arrival->platform = new stdClass();
                                $vias[$connectionindex]->arrival->platform->name = $arrivalPlatform;
                                $vias[$connectionindex]->arrival->platform->changed = false;
                                $vias[$connectionindex]->departure = new stdClass();
                                $vias[$connectionindex]->departure->time = $departTime;
                                $vias[$connectionindex]->departure->platform = new stdClass();
                                $vias[$connectionindex]->departure->platform->name = $departPlatform;
                                $vias[$connectionindex]->departure->platform->changed = false;
                                $vias[$connectionindex]->timeBetween = $departTime - $arrivalTime;
                                $vias[$connectionindex]->direction = $directions[$k - 1];
                                $vias[$connectionindex]->vehicle = "BE.NMBS." . $trains[$j - 1];
                                $station = Stations::getStationFromName($connsection->Arrival->BasicStop->Station['name'], $lang);
                                
                                // remove unwanted properties
                                unset($station->id);
                                unset($station->type);
                                
                                $vias[$connectionindex]->station = $station;
                                $connectionindex++;
                            }
                        }
                    }
                    if ($connectionindex != 0) {
                        $connection[$i]->via = $vias;
                    }
                
                }
                $connection[$i]->departure->vehicle = "BE.NMBS." . $trains[0];
                $connection[$i]->departure->direction = $directions[0];
                $connection[$i]->arrival->vehicle = "BE.NMBS." . $trains[sizeof($trains) - 1];
                $connection[$i]->arrival->direction = $directions[sizeof($directions) - 1];
                $i++;
            }
        } else {
            throw new Exception("We're sorry, we could not retrieve the correct data from our sources", 2);
        }
        return $connection;
    }
}

?>