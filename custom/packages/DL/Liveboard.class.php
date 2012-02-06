<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author  Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */

include_once("custom/packages/DL/Stations.class.php");
include_once("custom/packages/iRailLiveboard.class.php");

class DLLiveboard extends IRailLiveboard {
    
    public function call() {
        $loc = $this->location;
        
    }
 
}

?>
