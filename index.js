let signup_button;
let password = document.getElementById('password').value
let confirm_password = document.getElementById('confirm_password').value
document.getElementById('signup_button').onclick = function(){
    if(password != confirm_password){
        window.alert("Password does not match!")
    }
}