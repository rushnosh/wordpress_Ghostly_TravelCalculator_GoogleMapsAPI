
import axios from "axios";
import Messages from "./messages";

//Main Class to import
class TravelCostLogic {
    //Initial set up of the class
    constructor(){
        //error messages
        this.messages = new Messages("", 10000)
        //this.addressForm = document.querySelector('.travelcostaddressform')
        axios.defaults.headers.common["X-WP-Nonce"] = gge_travel_stored_data.nonce

        this.travelCostContainer = document.querySelector('.travelCostContainer')

        this.inputAddress = document.getElementById('travel_address1')
        this.inputAddress2 = document.getElementById('travel_address2')
        this.inputSuburb = document.getElementById('travel_Suburb')
        this.inputState = document.getElementById('travel_State')
        this.inputPostCode = document.getElementById('travel_PostCode')
        this.submitBtn = document.getElementById('travel-submit-btn')
        //Google Recapture widget area
        this.grecaptcha = document.querySelector('.g-recaptcha')

        this.errors = []
        //this.testInputArea()

        this.event()
    }

    //To autopupulate the input fields
    testInputArea(){
        //Test area
        //auto populate with an address
        //Close < 10kms NO TOLLS
        /*this.inputAddress.value = '21 Berrimilla St'
        this.inputAddress2.value = '' 
        this.inputSuburb.value = 'Manly West'
        this.inputPostCode.value = '4179'
        this.inputState.value = 'QLD'
        

        //Close < 15Kms With TOLLS
        this.inputAddress.value = '16 Dorames St'
        this.inputAddress2.value = '' 
        this.inputSuburb.value = 'Hendra'
        this.inputPostCode.value = '4011'
        this.inputState.value = 'QLD'
        */
        //Close < 15Kms With TOLLS
        
        this.inputAddress.value = '7 Monique Ct'
        this.inputAddress2.value = '' 
        this.inputSuburb.value = 'Deception Bay'
        this.inputPostCode.value = '4508'
        this.inputState.value = 'QLD'
        
        //Close < 100Kms With TOLLS
        /*
        this.inputAddress.value = '35 Ladds Ridge Rd'
        this.inputAddress2.value = '' 
        this.inputSuburb.value = 'Burleigh Heads'
        this.inputPostCode.value = '4220'
        this.inputState.value = 'QLD'*/
        
    }

    event(){
        this.submitBtn.addEventListener('click', (e) => this.validateForm(e), true)
    }

    disableSubmitButton(){
        this.submitBtn.innerHTML = "Processing..."
        this.submitBtn.setAttribute("disabled", "")
        this.submitBtn.classList.remove("btn-primary")
        this.submitBtn.classList.add("btn-secondary")
    }

    enableSubmitButton(){
        this.submitBtn.innerHTML = "Submit"
        this.submitBtn.removeAttribute("disabled", "")
        this.submitBtn.classList.remove("btn-secondary")
        this.submitBtn.classList.add("btn-primary")
    }

    validateForm(elm) {
        try {
            elm.preventDefault()
            this.disableSubmitButton()
            //add validation logic here
            //If fail display a message to the client
            this.validateInputFields()    
            //If pass - call the Google Maps Distance Matrix API via php backend.
            this.processAddressViaGoogleMapsAPI()
            
        } catch (e) {
            this.messages.failMessage('Sorry ' + e)
            this.enableSubmitButton()
        }
    }




    async processAddressViaGoogleMapsAPI() {
        //Get form data
        let formData = this.getFormData();

        //Send
        try {
            let response = await axios.post(gge_travel_stored_data.root_url + "/wp-json/gge_travel_cost/v1/process-travel-address-cost-route", formData)
            if (response.data.Error) {
                this.messages.failMessage('Sorry ' + response.data.Error)
                this.enableSubmitButton()
            } else {
                this.populateWithData(response.data)
            }
            //console.log(response.data)    
        } catch (error) {
            throw error
        }
    }

    populateWithData(data){
        this.travelCostContainer.innerHTML = `${data.htmlOutput}`
    }

    getFormData(){
        let formData = new FormData();
        formData.append('address1',this.inputAddress.value)
        formData.append('address2',this.inputAddress2.value)
        formData.append('suburb',this.inputSuburb.value)
        formData.append('state',this.inputState.value)
        formData.append('postCode',this.inputPostCode.value)
        formData.append('g-recaptcha-response', grecaptcha.getResponse())
        return formData
    }

    validateInputFields() {
        let invalidColor = '#ff848499'
        //validate email address

        let validAddress = /^[a-zA-Z0-9 ]*$/
        let validNameRegex = /^[a-zA-Z-' ]*$/

        if (!this.inputAddress.value.match(validAddress)) {
            this.inputAddress.previousElementSibling.style.backgroundColor = invalidColor
            throw "Only letters and white space allowed in Address name"
        }
        if (this.inputAddress.value.length < 1) {
            this.inputAddress.previousElementSibling.style.backgroundColor = invalidColor
            throw  "You need to have the Address Filled"
        }

        if (!this.inputAddress2.value.match(validAddress)) {
            this.inputAddress2.previousElementSibling.style.backgroundColor = invalidColor
            throw "Only letters and white space allowed in Address2 name"
        }

        if (!this.inputSuburb.value.match(validNameRegex)) {
            this.inputSuburb.previousElementSibling.style.backgroundColor = invalidColor
            throw "Only letters and white space allowed in City name"
        }

        let validState = /^\w{2,3}$/
        if (!this.inputState.value.match(validState)) {
            throw "Please select a state."
        }
        if (this.inputState.value.length < 1) {
            this.inputState.previousElementSibling.style.backgroundColor = invalidColor
            throw  "You need to have the State Filled"
        }

        let validPostCode = /^\d{4}$/
        if (!this.inputPostCode.value.match(validPostCode)) {
            throw "Please provide a postcode."
        }

    }

}

//Required to export the class to the js index file
export default TravelCostLogic;
