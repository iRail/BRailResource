<?php
/**
 * The mother of all iRail resources. It sets the language and prepares some iRail specific variables
 * All iRail resources should extend this class
 *
 * @copyright (C) 2011 by iRail vzw/asbl
 * @author Pieter Colpaert
 * @license  AGPLv3
 *
 */

abstract class AbstractiRailResource extends AResource{
    
    protected $lang;

    public function __construct(){
        //Get the language through our HTTP Accept-Language header. Yeah baby.
        $this->lang= parent::getLang();
    }

    public function supportedLanguages(){
        //only support 4 languages
        return array("en","nl","fr","de");
    }

}
