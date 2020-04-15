function login_form_validate(form) {
    
    valid = true;
    text = "";
    valid = (form.login.value      != "") &&
            (form.password.value   != "");

    if( !valid ) {
        alert("Пожалуйста, заполните все поля формы!" );
        return false;
    }

    return valid;
}