<?php 

/*
    Plugin Name: Ghostly Games - Travel Cost Plugin
    Description: to calculate the travel cost from Hemmant to clients places
    Version: 0.0.02
    Author: Mike Mikic
    Author URI: https://www.ghostlygames.com.au
*/

/**
 * Define the plugin version
 */
 
define("GGE_TRAVELCOST_VERSION", "1.0.12");
define( 'GGE_TRAVELCOST_PATH', plugin_dir_path( __FILE__ ));
define( 'GGE_TRAVELCOST_URL_PATH', plugin_dir_url( __FILE__ ));


//Defining the DB version - same as the GGE_TRAVELCOST_VERSION
global $gge_travel_db_version;
$gge_travel_db_version = GGE_TRAVELCOST_VERSION;

if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

if (!class_exists('GgeTravelCost')) {
    class GgeTravelCost {
        function __construct() {

        }

        public function initialize()
        {
            //Include any standard util functions we need
            include_once GGE_TRAVELCOST_PATH . 'inc/gge_travelcost_functions.php';
            
            //Create Admin
            include_once GGE_TRAVELCOST_PATH . 'admin/admin.php';
            new GgeTravelCostAdmin();
            
            //Create routes
            include_once GGE_TRAVELCOST_PATH . 'routes/route_google_api_travel_cost.php';
            new RouteGoogleApiTravelCost();

            //Front end logic
            include_once GGE_TRAVELCOST_PATH . 'frontend/travel-cost-frontend-main.php';
            new TravelCostFrontEndMain();
        }


    }

    function gge_travel_cost_init() {
		global $gge_travel_cost_init;

		// Instantiate only once.
		if ( ! isset( $gge_travel_cost_init ) ) {
			$gge_travel_cost_init = new GgeTravelCost();
			$gge_travel_cost_init->initialize();
		}
		return $gge_travel_cost_init;
	}

	// Instantiate.
	gge_travel_cost_init();

}

//initialise 
$ggeTravelCostObj = new GgeTravelCost();