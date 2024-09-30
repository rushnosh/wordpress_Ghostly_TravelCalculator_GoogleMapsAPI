<?php 

if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

if (!class_exists('TravelCostFrontEndMain')) {
    class TravelCostFrontEndMain {
        function __construct() {
            //Enquue the scripts
            add_action( 'wp_enqueue_scripts', array($this, 'travel_cost_frontend_scripts') );
            add_filter( 'script_loader_tag', array($this, 'travel_cost_google_add_async'), 10, 2);
            //For the store - done
            add_shortcode( 'ggetravelcostshcode', array($this, 'frontendTravelCostInit'));
        }

        public function travel_cost_frontend_scripts()
        {
            if ( has_shortcode(get_post()->post_content, 'ggetravelcostshcode') || is_page('booking')){
                //Google Maps Api key update
                wp_enqueue_script('gge-travel-google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . get_option('gge-travel-cost-google-maps-api-key', ''), array(), gge_travel_cost_version_id(), true);
                wp_enqueue_style( 'gge-travel-cost-frontend-style', GGE_TRAVELCOST_URL_PATH . 'frontend/frontend-scripts/build/style-index.css', array(), gge_travel_cost_version_id(), 'all' );
                wp_enqueue_script( 'gge-travel-cost-frontend-script', GGE_TRAVELCOST_URL_PATH . 'frontend/frontend-scripts/build/index.js', array(), gge_travel_cost_version_id(), true );

                wp_localize_script('gge-travel-cost-frontend-script', 'gge_travel_stored_data', array(
                    'nonce' => wp_create_nonce('wp_rest'),
                    'root_url' => get_site_url()
                ));

            }
        }

        //This will add the ASYNC attribute to the google api key call (allowing the page to continue loading)
        public function travel_cost_google_add_async($tag, $handle)
        {
            if ( 'gge-travel-google-maps-api' !== $handle ) {
                return $tag;
            }       
            //return str_replace( ' src', ' defer src', $tag ); // defer the script
            return str_replace( ' src', ' async src', $tag ); // OR async the script
            //return str_replace( ' src', ' async defer src', $tag ); // OR do both!
        }

        public function frontendTravelCostInit($atts)
        {
            //If there are any attributes for this shortcode
            $a = shortcode_atts(array(
                'sel_tax_slug' => ''
            ), $atts);

            ob_start();
            //Start of store container div
            ?>
            <div id="gge_travelCost" class="travelCostContainer mb-4" style="text-align: center">
                <div class="jumbotron gge-travel-drop-shadow">

                    <?php 
                        include GGE_TRAVELCOST_PATH . 'frontend/views/title-description-area.php'
                    ?>
                    <?php 
                        include GGE_TRAVELCOST_PATH . 'frontend/views/address-form.php';
                    ?>

                </div>
            </div>
            <?php

            //Ensure you reset the post data once you have finished off with the custom query
            wp_reset_postdata();

            //This will enqueue the required front end scripts for the store
            //wp_enqueue_style( 'gge-travel-cost-frontend-style' );
            wp_enqueue_script( 'gge-travel-cost-frontend-script' );
            return ob_get_clean ();

        }


    }


}

