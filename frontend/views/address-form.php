
<?php 

?>

  <div class="container">
    <form >
      <div class="travelcostaddressform">
        <label for="travel_address1">Address 1</label>
        <input type="text" class="form-control" id="travel_address1" aria-describedby="address1Help" placeholder="Address 1">
        <br>
        <label for="travel_address2">Address 2</label>
        <input type="text" class="form-control" id="travel_address2" aria-describedby="address2Help" placeholder="Address 2">
        <br>
        <label for="travel_Suburb">Suburb</label>
        <input type="text" class="form-control" id="travel_Suburb" aria-describedby="SuburbHelp" placeholder="Suburb">
        <br>
          <div class="row">
            <div class="col p-1">
              <label for="inputState">State</label>
              <select id="travel_State" class="form-control">
                  <option selected>Choose...</option>
                  <option value="ACT">ACT</option>
                  <option value="QLD">QLD</option>
                  <option value="NSW">NSW</option>
                  <option value="WA">WA</option>
                  <option value="NT">NT</option>
                  <option value="SA">SA</option>
                  <option value="VIC">VIC</option>
              </select>
            </div>
            <div class="col p-1">
              <label for="inputZip">Post Code</label>
              <input type="text" class="form-control" id="travel_PostCode">
            </div>
          </div>
      </div>
        <br>
        <!--Google Recapture widget-->
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <div class="g-recaptcha brochure__form__captcha" data-sitekey="<?php echo get_option('gge_travel_cost_google_recapture_site_key', '')?>"></div>
        <style>
        @media only screen and (max-width: 500px){
        #rc-imageselect, .g-recaptcha {transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;}
        }
        </style>
        <button type="submit" id="travel-submit-btn" class="btn btn-primary">Submit</button>
    </form>
  </div>

<?php 
  
  //Custom Messages on screen
	include GGE_TRAVELCOST_PATH . 'frontend/views/message-card.php';

?>