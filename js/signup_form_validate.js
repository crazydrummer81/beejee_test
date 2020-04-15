function signup_form_validate(form) {
    
    valid = true;
    text = "";
    valid = (form.login.value      != "") &&
            (form.email.value      != "") &&
            (form.password.value   != "") &&
            (form.password_2.value != "");

    if( !valid ) {
        alert("Пожалуйста, заполните все поля формы!" );
        return false;
    }
    else if ( form.password.value != form.password_2.value ) {
            alert("Подтверждение пароля не совпадает, пожалуйста, повторите ввод");
            valid = false;
        }
    return valid;
}