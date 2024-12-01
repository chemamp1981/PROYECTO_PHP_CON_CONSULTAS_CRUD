const check_password = document.getElementById("check_password");
const password_input = document.getElementById("fpassword");

check_password.addEventListener("click", function(){
    showHide_password(password_input);
});

function showHide_password(p_iput){
    if(p_iput.type === "password"){
        p_iput.type = "text";
    }else{
        p_iput.type = "password";
    }
}