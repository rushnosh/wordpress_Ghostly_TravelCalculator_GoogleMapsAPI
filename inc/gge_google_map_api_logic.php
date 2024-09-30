<?php 

include_once GGE_TRAVELCOST_PATH . 'inc/google-travel-map-funcs.php';
include_once GGE_TRAVELCOST_PATH . 'inc/gge_google_recapture_logic.php';

if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

if (!class_exists('GgeTravelCostGoogleMapAPILogic')) {
    class GgeTravelCostGoogleMapAPILogic {

        protected $destinationData = array("price" => 0, "description" => "", "distance" => 0,
             "travelTitle" => "", 
             "afterTextTop" => "",
             "afterTextBottom" => "",
             "htmlOutput" => ""
            );
        protected $dataObs;
        protected $googleRecapObj;

        function __construct() {
        }
        
        //Method Call from Router 
        public function processTravelAddressCost($data){
            $this->dataObs = $data->get_params();
            $this->googleRecapObj = new GgeTravelCostGoogleRecaptureLogic();

            $res = $this->googleRecapObj->reCaptcha($this->dataObs['g-recaptcha-response']);
            if(!$res['success']){
                //The returned data back to the front end
                $sendData = array(
                    'Error' => 'Invalid Google reCapture result - try refreshing the page and try again.'
                );
                echo json_encode($sendData); 
                die();
            } 

            $this->populateTextData();
            $this->gatherTravelDistanceData();
        }

        protected function populateTextData()
        {
            $this->destinationData["travelTitle"] = get_option('gge-travel-cost-title-area', ''); 
            $this->destinationData["afterTextTop"] = get_option('gge-travel-cost-after-process', ''); 
            $this->destinationData["afterTextBottom"] = get_option('gge-travel-cost-after-second-area', ''); 
        }
        //Protected Functions below
        protected function gatherTravelDistanceData()
        {


            //Then gather the place IDs from the Google Maps API
            $gmplaceIdRes = GoogleMapTravelFunctions::findPlaceIdFromAddress($this->dataObs);
            if ($gmplaceIdRes['status'] != 'OK') {
                $sendData = array(
                    'Error' => 'We have encounted a maps error. Please check the address and try agian. Error: ' . $gmplaceIdRes['status'] 
                );
                echo json_encode($sendData); 
                die();
            }

            //Perform freight calculations
            $this->destinationData["distance"] = GoogleMapTravelFunctions::calcDistanceFromTwoPlaceIdsInKms(get_option("gge-travel-cost-origin-place-id"),$gmplaceIdRes['candidates'][0]['place_id']);

            if (is_numeric($this->destinationData["distance"])) {
                //Now that we have the distance - its time to calculate the costs
                $this->calculateTravelCosts($this->destinationData["distance"]);
                if ($this->destinationData["price"] != 0 || $this->destinationData["price"] == 'UNKNOWN') {
                    $this->generateHTMLOutput();
                } else {
                    //Free Travel Cost
                    $this->generateHTMLOutputForFreeTravelFee();
                }
    
                echo json_encode($this->destinationData); 
                die();
            }  else {
                $sendData = array(
                    'Error' => 'We have encounted a maps error. Please check the address and try agian. Error: ' . $this->destinationData["distance"]
                );
                echo json_encode($sendData); 
                die();
            }

        }

        protected function calculateTravelCosts($distance){
            //Here we set a limit condition list the distance and provide a price
            switch ($distance) {
                case $distance <= 30:
                    $this->destinationData["price"] = 0;
                    $this->destinationData["description"] = "This is within Catchment, no travel charge.";
                    break;
                case $distance <= 40:
                    $this->destinationData["price"] = 15;
                    $this->destinationData["description"] = "A $15 Travel Charge will be added to your booking.";
                    break;
                case $distance <= 50:
                    $this->destinationData["price"] = 30;
                    $this->destinationData["description"] = "A $30 Travel Charge will be added to your booking.";
                    break;
                case $distance <= 60:
                    $this->destinationData["price"] = 45;
                    $this->destinationData["description"] = "A $45 Travel Charge will be added to your booking.";
                    break;
                case $distance <= 70:
                    $this->destinationData["price"] = 65;
                    $this->destinationData["description"] = "A $65 Travel Charge will be added to your booking.";
                    break;
                case $distance <= 80:
                    $this->destinationData["price"] = 75;
                    $this->destinationData["description"] = "A $75 Travel Charge will be added to your booking.";
                    break;
                case $distance <= 90:
                    $this->destinationData["price"] = 95;
                    $this->destinationData["description"] = "A $95 Travel Charge will be added to your booking.";
                    break;
                case $distance <= 100:
                    $this->destinationData["price"] = 105;
                    $this->destinationData["description"] = "A $105 Travel Charge will be added to your booking.";
                    break;
                
                default:
                    $this->destinationData["price"] = 'UNKNOWN';
                    $this->destinationData["description"] = "You are outside of our Local Travel Zones. Please contact Ghostly Games Entertainment on <strong>info@ghostlygames.com.au</strong> so we can provide you with a custom quote.";
                    break;
            }
        }

        protected function generateHTMLOutputForFreeTravelFee()
        {
            $this->destinationData['htmlOutput'] = '
            <div class="jumbotron narrow p-5 gge-travel-drop-shadow">
                <h4 class="display-5"><i class="fa-solid fa-car-side"></i> ' . $this->destinationData["travelTitle"] . '</h1>
                <p class="lead">' . $this->destinationData["afterTextTop"] . '</p>
                <p>Distance calculated: ' . $this->destinationData["distance"] . ' Kms </p>
                <p class="lead"><i class="fa-regular fa-face-smile"></i> You are in our "Free Travel Catchment Zone".</p>
                <hr class="my-4">
                <p>We have calculated that your within our catchment zone, so no need to worry about travel costs for your booking.</p>
                <p class="lead">
                <button class="btn btn-primary" onClick="window.location.reload()" role="button">Retry Form</button>
                </p>
            </div>
            ';
        }
        
        protected function generateHTMLOutput()
        {
            //Show dollar or not
            $showDollar = is_numeric($this->destinationData["price"])? '<i class="fa-solid fa-money-bill-wave"></i> <strong> $' : '';
            $this->destinationData['htmlOutput'] = '
            <div class="jumbotron narrow p-5 gge-travel-drop-shadow">
                <h4 class="display-5"><i class="fa-solid fa-car-side"></i> ' . $this->destinationData["travelTitle"] . '</h1>
                <p class="lead">' . $this->destinationData["afterTextTop"] . '</p>
                <p>Distance calculated: <strong>' . $this->destinationData["distance"] . '</strong> Kms </p>
                <p style="font-size: 1.5rem;">Travel Fee: ' . $showDollar . $this->destinationData["price"] . '</strong></p>
                <p>' . $this->destinationData["description"] . '</p>
                <hr class="my-4">
                <p>' . $this->destinationData["afterTextBottom"] . '</p>
                <p class="lead">
                <button class="btn btn-primary" onClick="window.location.reload()" role="button">Retry Form</button>
                </p>
            </div>
            ';
        }

    }

}

?>
