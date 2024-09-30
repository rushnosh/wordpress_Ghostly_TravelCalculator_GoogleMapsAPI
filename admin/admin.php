<?php 


if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly


    class GgeTravelCostAdmin {

        public function __construct()
        {
            
            //Add misc menu items
            add_action('admin_init', array($this, 'ourSettings'));
            //add_action('admin_init', array($this, 'ourFreightSettings'));
            add_action('admin_menu', array($this, 'my_admin_menu'));

        }



        public function onActivate()
        {
            //On activation

        }

        public function onDeactivate()
        {
            //Dont Drop anything - if the user click on Uninstall - then DROP - using the uninstall.php
        }


        /*
        Sub Menu Options that we'll use to manage from within the gge-store (using CPT Custom Post Type)
        */
        public function my_admin_menu () {
            add_menu_page( 'GGE Travel Cost Admin', 'GGE Travel Cost', 'manage_options', 'gge-travel-cost-admin', array($this, 'optionsSubPage'), 'dashicons-car', 27);
            //add_submenu_page('edit.php?post_type=gge_product_post', 'Store Options', 'Store Options', 'manage_options', 'gge-store-options',array($this, 'optionsSubPage')); 

        }

        public function ourSettings()
        {
            //API Configurations
            //each field needs a section and a slug to add the fields to
            add_settings_section('gge-travel-google-maps-api-section',null,null,'gge-travel-cost-admin');
            register_setting('GGE Travel Cost Options Settings', 'gge-travel-cost-google-maps-api-key');
            register_setting('GGE Travel Cost Options Settings', 'gge-travel-cost-origin-place-id');
            register_setting('GGE Travel Cost Options Settings', 'gge_travel_cost_google_recapture_secret_key');
            register_setting('GGE Travel Cost Options Settings', 'gge_travel_cost_google_recapture_site_key');

            //Once the feilds are created, we then perform the add_settings_field action to add them in via a HTML template call
            add_settings_field('gge-travel-cost-google-maps-api-settings', 'Travel Place IDs and API settings', array($this, 'googleMapsApiSetting'), 'gge-travel-cost-admin', 'gge-travel-google-maps-api-section');

            //Front End Configurations
            //each field needs a section and a slug to add the fields to
            add_settings_section('gge-travel-front-end-config-section',null,null,'gge-travel-cost-admin');
            register_setting('GGE Travel Cost Options Settings', 'gge-travel-cost-title-area');
            register_setting('GGE Travel Cost Options Settings', 'gge-travel-cost-before-process');
            register_setting('GGE Travel Cost Options Settings', 'gge-travel-cost-after-process');
            register_setting('GGE Travel Cost Options Settings', 'gge-travel-cost-after-second-area');


            //Once the feilds are created, we then perform the add_settings_field action to add them in via a HTML template call
            add_settings_field('gge-travel-front-end-config-settings', 'Travel Place Front End settings', array($this, 'frontendSettings'), 'gge-travel-cost-admin', 'gge-travel-front-end-config-section');

        }


        //Settings HTML template - note you need to update the "Name" and get_option to the newly created field.
        public function googleMapsApiSetting()
        {
            ?>
                <p class="description">Enter in Place ID of the origin for distance calculations</p>
                <input type="text" name="gge-travel-cost-origin-place-id" value="<?php echo esc_attr(get_option('gge-travel-cost-origin-place-id', '')); ?>" placeholder="Place Id of the main origin location">
                <p class="description">Enter in the Google Map API Key</p>
                <input type="text" name="gge-travel-cost-google-maps-api-key" value="<?php echo esc_attr(get_option('gge-travel-cost-google-maps-api-key', '')); ?>" placeholder="Google Map API Key">
                <p class="description">Enter in the Google Secret Recapture Key</p>
                <input type="text" name="gge_travel_cost_google_recapture_secret_key" value="<?php echo esc_attr(get_option('gge_travel_cost_google_recapture_secret_key', '')); ?>" placeholder="Google Recapture Secret Key">
                <p class="description">Enter in the Google Site Recapture Key</p>
                <input type="text" name="gge_travel_cost_google_recapture_site_key" value="<?php echo esc_attr(get_option('gge_travel_cost_google_recapture_site_key', '')); ?>" placeholder="Google Recapture Site Key">

            <?php
        }
        //Settings HTML template - note you need to update the "Name" and get_option to the newly created field.
        public function frontendSettings()
        {
            ?>
                <p class="description">The Title to display on front end.</p>
                <input type="text" name="gge-travel-cost-title-area" value="<?php echo esc_attr(get_option('gge-travel-cost-title-area', '')); ?>" placeholder="Travel Cost Widget Title">
                <p class="description">Text to display before the client enters in address details.</p>
                <textarea cols="50" rows="6" name="gge-travel-cost-before-process"  placeholder="Before Process Text Area"><?php echo esc_attr(get_option('gge-travel-cost-before-process', '')); ?></textarea>
                <p class="description">Text to display after the client enters in address details and submits travel cost calculations at the TOP Section of the output.</p>
                <textarea cols="50" rows="6" name="gge-travel-cost-after-process"  placeholder="After Process Text Area"><?php echo esc_attr(get_option('gge-travel-cost-after-process', '')); ?></textarea>
                <p class="description">Text to display after the client enters in address details and submits travel cost calculations at the below section.</p>
                <textarea cols="50" rows="6" name="gge-travel-cost-after-second-area"  placeholder="After Process Text Area Second Area"><?php echo esc_attr(get_option('gge-travel-cost-after-second-area', '')); ?></textarea>

            <?php
        }

        public function optionsSubPage()
        {
            ?>
            <div class="wrap">
                <h1>GGE Travel Cost Settings: </h1>
                <form action="options.php" method="POST">
                    <?php
                        settings_errors(); 
                        settings_fields('GGE Travel Cost Options Settings');
                        do_settings_sections('gge-travel-cost-admin');
                        submit_button();
                    ?>
                </form>
            </div>
            <?php
        }

    }

?>