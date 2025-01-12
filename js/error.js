class OutputError {
    constructor(Element, errorCode) {
        this.Element = document.getElementById(Element);
        this.errorCode = errorCode

        this.displayError();
    }

    displayError() {
        let errDesc
        if (this.errorCode == '404') {
            errDesc = "Page not found."
        } else if (this.errorCode == '204') {
            errDesc = "No content found."
        } else if (this.errorCode == '401') {
            errDesc = "User not authorized."
        } else if (this.errorCode == '422') {
            errDesc = "Invalid input data." 
        } else {
            errDesc = "Something went wrong."
        }

        this.Element.innerHTML = '<div class="flex-col-center"><h1 class="massive-text">Error '+ this.errorCode +'</h1><h2>'+ errDesc +'</h2></div>'
        console.error('Error: ', this.errorCode);
    }
}
export default OutputError;