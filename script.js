
    function validateName(){
    var customerName = document.getElementById('customerName').value;

    if (customerName.length > 3){
        return true;
    }
    alert("You must enter a full name!");
    return false;
    }
