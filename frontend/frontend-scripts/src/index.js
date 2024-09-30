//This will auto build the Sassy CSS files
import "../css/style.scss"

//for the cart page - this will perform a REST API call to gain product data
import TravelCostLogic from "./modules/travel_cost_display";

//ensure we're on a travelCostContainer page
if (document.querySelector('.travelCostContainer')) {
    new TravelCostLogic()
}

