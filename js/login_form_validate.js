function signup_form_validate(form) {
    
    valid = true;

    form.login.value = form.login.value.trim(); 
    form.email.value = form.email.value.trim();
    valid = (form.login.value != "") &&
            (form.email.value != "");
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