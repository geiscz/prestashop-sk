<?php
/*
* (c) 2015 Geis CZ s.r.o.
*/
//require_once dirname(__FILE__) . '/GeispointWS/GeispointWSWsdlClass.php';
//require_once dirname(__FILE__) . '/GeispointWS/Service/GeispointWSServiceGet.php';
//require_once dirname(__FILE__) . '/GeispointWS/Service/GeispointWSServiceSearch.php';
//require_once dirname(__FILE__) . '/GeispointWS/GeispointWSClassMap.php';

require_once dirname(__FILE__) . '/GeispointWS/GeisPointSoapClientSk.php';

require_once dirname(__FILE__) . '/GeisPointModelSk.php';
ini_set('memory_limit','512M');
ini_set('display_errors',true);
error_reporting(-1);

class GeisPointWebServiceSk {
    private $geisPointSoapClient;

    public function __construct() {
        $this->geisPointSoapClient = new GeisPointSoapClientSk();
    }

    public function GetAllGeispoints() {
        $geispoints = $this->GetAllDetails();
        return $geispoints;
    }

    private function GetidRegions($countryCode)
    {
        $idRegions = array();
        $result = $this->geisPointSoapClient->getRegions($countryCode);
        $jsonResult = Tools::jsonDecode($result);
        foreach ($jsonResult as $idRegion) {
            $idRegion = $idRegion->idRegion;
            //$name = $region->name;
            $idRegions[]=$idRegion;
        }
        return $idRegions;
    }

    private function GetCities($countryCode,$idRegion)
    {
        $cities = array();
        $result = $result = $this->geisPointSoapClient->getCities($countryCode,$idRegion);
        $jsonResult = Tools::jsonDecode($result);
        foreach ($jsonResult as $idRegion) {
            //$idRegion = $region->idRegion;
            $city = $idRegion->city;
            $cities[]=$city;
        }

        return $cities;
    }

    private function GetDetails($cities)
    {
        $geisPoints = array();
        foreach ($cities as $city) {
            $result = $this->geisPointSoapClient->searchGP(null,$city,null,null);
            $jsonResult = Tools::jsonDecode($result);
            foreach ($jsonResult as $detail) {
                $geisPoint = new GeisPointModelSk($detail);
                $geisPoints[] = $geisPoint;
            }
        }
        return $geisPoints;
    }

    function cmp($a, $b)
    {
        return strcmp($a->city, $b->city);
    }
    
    private function GetAllDetails()
    {
        $geisPoints = array();
        
        $result = $this->geisPointSoapClient->searchGP('SK',null,null,null);
        $jsonResult = Tools::jsonDecode($result);
        usort($jsonResult, array('GeisPointWebServiceSk','cmp'));
        
        
        
       foreach ($jsonResult as $detail) {
            $geisPoint = new GeisPointModelSk($detail);
            $geisPoints[] = $geisPoint;
        }
        
        return $geisPoints;
    }

    public function GetDetail($idGP)
    {
        $result = $this->geisPointSoapClient->searchGP($idGP);
        $jsonResult = Tools::jsonDecode($result);
        foreach ($jsonResult as $detail) {
            return $detail;
        }

        return null;
    }
    
}

