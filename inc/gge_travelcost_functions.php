<?php 


//GGE Travel cost util function calls

function gge_travel_cost_version_id() {
	if ( WP_DEBUG )
		return time();
	return GGE_TRAVELCOST_VERSION;
}


?>