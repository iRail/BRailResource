<?php
include_once("custom/packages/DL/tools.php");
class ParkingsBrussels extends AReader{
     public static function getRequiredParameters(){
	  return array(); 
     }

     public static function getParameters(){
	  return array();
     }

     public static function getDoc(){
	  return "This resource contains real-time information on parkings in Brussels thanks to irisnet.be";	  
     }
     
     public function setParameter($key,$val){
     }
     public function read(){
         $data = TDT::HttpRequest("http://www.mobielbrussel.irisnet.be/static/categories_files/parkings.json");
         $object = json_decode($data->data);
         foreach($object->features as &$element){
             $coords = tools::LambertToWGS84($element->geometry->coordinates[0],$element->geometry->coordinates[1]);
             $element->latitude = $coords[0];
             $element->longitude = $coords[1];
             $element->name_fr = $element->properties->nom;
             $element->name_nl = $element->properties->naam;
             $element->city_nl = $element->properties->stad;
             $element->city_fr = $element->properties->ville;
             $element->zipcode = (int)$element->properties->post_code;
             $element->address_nl =$element->properties->adres;
             $element->address_fr =$element->properties->adresse;
             $element->free_places = $element->properties->free_places;
             $element->total_places = $element->properties->total_places;
             unset($element->properties);
             unset($element->geometry);
             unset($element->type);
         }
         return $object->features;
     }
}
?>
