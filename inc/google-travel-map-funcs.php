<?php 


if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly
    class GoogleMapTravelFunctions {


    //Constructor - 
    public function __construct() {
        
    }

    public static function findPlaceIdFromAddress($addressDetails = ""){
        //Place the address details that was inserted into the checkout form
        //Encoded address - 68%20Bremen%20Street,,Hemmant,4174,QLD,Australia"
        //$updatedAddress = str_replace(" ","%20", $addressDetails['address1'] . ', ' . $addressDetails['address2'] . ', ' . $addressDetails['suburb'] . ', ' . $addressDetails['postCode'] . ', ' . $addressDetails['state'] . ',Australia');
        $updatedAddress = $addressDetails['address1'] . ', ' . $addressDetails['address2'] . ', ' . $addressDetails['suburb'] . ', ' . $addressDetails['postCode'] . ', ' . $addressDetails['state'] . ', Australia';

       //Calling find place Id via JSON Call
        $getParameters = array("input" => $updatedAddress, "inputtype" => "textquery","fields" => "formatted_address,name,place_id",  "key" => get_option('gge-travel-cost-google-maps-api-key', ''));
        $endpoint = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json';
        $placeData = GoogleMapFunctions::callCurl($endpoint,$getParameters);
        //test google maps via curl
        return json_decode($placeData, true);
    }

    /**
     * We are sending over Place ID's to use the Google Matrix to calculate distance between two areas
     */
    public static function calcDistanceFromTwoPlaceIdsInKms($origin, $destination)
    {
        //Information from https://developers.google.com/maps/documentation/distance-matrix/distance-matrix#avoid
        //Calling Distance Matrix using two place ID's via JSON Call
        //$getParameters = array("origins" => "place_id:" . $origin, "destinations" => "place_id:" . $destination, "avoid" => "tolls", "key" => get_option('gge-travel-cost-google-maps-api-key', ''));
        $getParameters = array("origins" => "place_id:" . $origin, "destinations" => "place_id:" . $destination, "key" => get_option('gge-travel-cost-google-maps-api-key', ''));
        $endpoint = 'https://maps.googleapis.com/maps/api/distancematrix/json';
        $placeData = GoogleMapFunctions::callCurl($endpoint,$getParameters);            
        $ob = json_decode($placeData, true);
        //We'll need to have a try catch on this one...NOTE
        //This will get the distance via meters
        $distance = $ob['rows'][0]['elements'][0]['distance']['value'];
        //Convert it to KMs
        $distance = $distance / 1000;
        return $distance;
    }

    public static function callCurl($endpoint, $getParameters)
    {
        //Using curl to call external api's
        $ch = curl_init();
        $url = $endpoint . '?' . http_build_query($getParameters);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $placeData = curl_exec($ch);

        curl_close($ch);

        $ob = json_decode($placeData, true);
        //Issue with the Server IP Address on Google Maps
        if ($ob['status'] == 'REQUEST_DENIED' ) {
            $sendError = 'Please check Google maps API Server IP (go and perform a curl test on terminal) Error Message: ' .$ob['error_message'];
            return $sendError;
        }


        return $placeData; 
    }

}


?>