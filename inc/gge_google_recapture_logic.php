<?php 

if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

if (!class_exists('GgeTravelCostGoogleRecaptureLogic')) {
    class GgeTravelCostGoogleRecaptureLogic {

        function __construct() {
        }

//Google reCatpcha check
        public function reCaptcha($recaptcha){
            $secret = get_option('gge_travel_cost_google_recapture_secret_key', '');
            $ip = $_SERVER['REMOTE_ADDR'];
          
            $postvars = array("secret"=>$secret, "response"=>$recaptcha, "remoteip"=>$ip);
            $url = "https://www.google.com/recaptcha/api/siteverify";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
            $data = curl_exec($ch);
            curl_close($ch);
            
            return json_decode($data, true);
        }
    }
}