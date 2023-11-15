let checkBox = document.querySelector("#checkbox");

checkBox.addEventListener('click', function(){
    let passwordField = document.querySelector("#password");
    if(passwordField.type === "password"){
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    };
    
});


