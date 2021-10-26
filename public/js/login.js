/* --------------------------------------------------------------------- CSS ---------------------------------------------------------- */
/* --------------------------------------------------------------------- CSS ---------------------------------------------------------- */

const emailContainer = document.querySelector('.email-field');
const email = document.querySelector('.email');
// For the email field BOX SHADOW
email.addEventListener('click', ()=> {
    emailContainer.style.boxShadow = '0 0 3px 1px rgb(217, 185, 236)';
});
email.addEventListener('focusout', () => {
    emailContainer.style.boxShadow = 'none';
});


const passwordContainer = document.querySelector('.password-field');
const password = document.querySelector('.password');
// For the password field BOX SHADOW
password.addEventListener('click', ()=> {
    passwordContainer.style.boxShadow = '0 0 3px 1px rgb(217, 185, 236)';
});

password.addEventListener('focusout', () => {
    passwordContainer.style.boxShadow = 'none';
});



/* -------------------------------------------------------------------- XHR -------------------------------------------------- */
/* -------------------------------------------------------------------- XHR -------------------------------------------------- */

let form = document.forms['login-form'];
let btn = document.querySelector('.submit-btn');
let error = document.querySelector('.error');
let data = {};

btn.addEventListener('click', (e)=>{
    e.preventDefault();

    error.innerHTML = '';


    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../app/api/login.api.php', true);

    xhr.onload = () => {
        data = JSON.parse(xhr.response);
        if(data['status'] == true) {
            document.cookie = 'user_id=' + data['credential'][':user_id'] + ';expires=Thu, 18 Dec 2022 UTC;path=/';
            document.cookie =  'token=' + data['credential'][':token'] + ';expires=Thu, 18 Dec 2022 UTC;path=/';
            window.location.href = 'index.php';
        }
        else {
            error.innerHTML = data['error'];
        }
    }

    let formData = new FormData(form);
    xhr.send(formData);
});