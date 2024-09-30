//Main Class to import
class Messages {
    //Initial set up of the class
    constructor(productId, timeout = 3500){
        this.timeOutCounter
        this.messagebox
        if (document.querySelector('.message-alert[data-product-id="' + productId + '"]') != undefined) {
            this.messagebox = document.querySelector('.message-alert[data-product-id="' + productId + '"]')
        } else {
            this.messagebox = document.querySelector('.message-alert')
        }
        this.messageLine
        this.timeoutSetting = timeout
    }
    //Events - use this area to handle event calls

    //Methods - use this area to apply logic in your code

    activateMessage(messageType,msg,xlink,arialable) {
        this.messageLine = this.messagebox.querySelector('.innerMessage')
        this.messageLine.innerHTML = msg;
        clearTimeout(this.timeOutCounter)
        this.clearAndSetTimeOut(messageType,arialable,xlink)
    }

    clearAndSetTimeOut (messageType,arialable, xlink) {
        this.messagebox.firstElementChild.setAttribute('aria-label', arialable)
        this.messagebox.firstElementChild.firstElementChild.setAttributeNS('http://www.w3.org/1999/xlink','href', xlink)
        this.messagebox.classList.remove('show')
        this.messagebox.classList.remove('success')
        this.messagebox.classList.remove('alert-success')
        this.messagebox.classList.remove('info')
        this.messagebox.classList.remove('alert-primary')
        this.messagebox.classList.remove('warning')
        this.messagebox.classList.remove('alert-warning')
        this.messagebox.classList.remove('fail')
        this.messagebox.classList.remove('alert-danger')
        this.messagebox.classList.add(messageType)
        this.messagebox.classList.add('show')
        this.timeOutCounter = setTimeout(() => {
            this.messagebox.classList.remove('show')
            this.messagebox.classList.remove(messageType)
        }, this.timeoutSetting);
    }

    successMessage(msg) {
        //this.activateMessage('success', msg);
        this.activateMessage('alert-success', msg, '#check-circle-fill', 'Success:');
    }

    infoMessage(msg) {
        //this.activateMessage('info', msg);
        this.activateMessage('alert-primary', msg, '#info-fill', 'Info:');
    }

    failMessage(msg) {
        //this.activateMessage('fail', msg);
        this.activateMessage('alert-danger', msg, '#exclamation-triangle-fill', 'Fail:');
    }

    warningMessage(msg) {
        //this.activateMessage('warning', msg);
        this.activateMessage('alert-warning', msg, '#exclamation-triangle-fill', 'Warning:');
    }
}
//Required to export the class to the js index file
export default Messages;