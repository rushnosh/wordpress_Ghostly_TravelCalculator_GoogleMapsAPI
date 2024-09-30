<?php 

//Add includes
include_once GGE_TRAVELCOST_PATH . 'inc/gge_google_map_api_logic.php';

if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

if (!class_exists('RouteGoogleApiTravelCost')) {
    class RouteGoogleApiTravelCost {

        //declare vars
        public $GoogleMapApiObj;

        function __construct() {
            $this->GoogleMapApiObj = new GgeTravelCostGoogleMapAPILogic;
            add_action('rest_api_init', array($this, 'routeGoogleApiRegisterCall'));  
        }
        
        public function routeGoogleApiRegisterCall(){
            register_rest_route('gge_travel_cost/v1', 'process-travel-address-cost-route', array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'processTravelAddressCostRoute'),
                'permission_callback' => '__return_true'
            ));
        }
        
        public function processTravelAddressCostRoute($data){
            $this->GoogleMapApiObj->processTravelAddressCost($data);
            
        }


        
    }


}


?>