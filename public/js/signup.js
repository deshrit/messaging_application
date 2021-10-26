/* --------------------------------------------------------------------- CSS ---------------------------------------------------------- */
/* --------------------------------------------------------------------- CSS ---------------------------------------------------------- */

const inputContainer = document.querySelector('.input-field-container');
const inputField = document.querySelector('.input-field');
// For the email field BOX SHADOW
inputField.addEventListener('focus', ()=> {
    inputContainer.style.boxShadow = '0 0 3px 1px rgb(217, 185, 236)';
});
inputField.addEventListener('focusout', () => {
    inputContainer.style.boxShadow = 'none';
});



/* -------------------------------------------------------------------- XHR -------------------------------------------------- */
/* -------------------------------------------------------------------- XHR -------------------------------------------------- */

let form = document.forms['signup-form'];
let btn = document.querySelector('.submit-btn');
let error = document.querySelector('.error');
let data = {};
let txt = '';

btn.addEventListener('click', (e)=>{
    e.preventDefault();

    error.innerHTML = '';
    txt = '';

    let xhr = new XMLHttpRequest();
    xhr.open('POST', '../app/api/signup.api.php', true);

    xhr.onload = () => {
        data = JSON.parse(xhr.response);
        if(data['status'] == true) {
            window.location.href = 'login.php';
        }
        else {
            data['error'].forEach(element => {
                txt += element + '<br>';  
            });
            error.innerHTML = txt;
        }
    }

    let formData = new FormData(form);
    xhr.send(formData);
});