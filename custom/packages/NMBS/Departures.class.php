<?php
/**
 * Copyright (C) 2011 by iRail vzw/asbl
 *
 * @author  Pieter Colpaert <pieter aŧ iRail.be>
 * @license AGPLv3
 *
 */

include_once("custom/packages/NMBS/Liveboard.class.php");

class NMBSDepartures extends NMBSLiveboard {
    
    public function __construct(){
        parent::__construct();
        $this->direction = "departures";
    }    
}

?>
