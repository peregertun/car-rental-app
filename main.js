
    function validate(){
    var text1 = document.getElementById('text').value;

    if (text1.length > 0){
        alert("went through");
        return true;
    }
    alert("did not go through");
    return false;
    }
