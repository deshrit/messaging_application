/* --------------------------------------------------------------------- CSS ---------------------------------------------------------- */
/* --------------------------------------------------------------------- CSS ---------------------------------------------------------- */

// Search From Container
const searchForm = document.querySelector('.search-form');
const searchInput = document.querySelector('.search-input');

// Box shadow
searchInput.addEventListener('focus', (e)=>{ // Click event because input:focus conflicts
    searchForm.style.boxShadow = '0 0 3px 1px rgb(217, 185, 236)';
});

searchInput.addEventListener('focusout', () => {
    searchForm.style.boxShadow = 'none';
    
    window.setTimeout(()=> {
        search.style.display = 'none';
        searchInput.value = '';
    }, 100);
});

/* -------------------------------------------------------------------- XHR -------------------------------------------------- */
/* -------------------------------------------------------------------- XHR -------------------------------------------------- */

// xhr SEARCH BAR
const search = document.querySelector('.searched-content-container');

searchInput.addEventListener('keyup', () => {

    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        search.style.display = 'flex';
        search.innerHTML = this.response;
    }
    xhr.open('POST', '../app/api/index.api.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('search='+searchInput.value);
});

// LOAD USERS DYNAMICALLY
(function(){
    const membersToAdd = document.querySelector('.members-to-add');

    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        membersToAdd.innerHTML = xhr.response;
    }
    xhr.open('POST', '../app/api/group.api.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('users=true');
})();

// XHR CREATE GROUP
let form = document.forms['create-grp-form'];
let btn = document.querySelector('.submit-btn');
let error = document.querySelector('.error');
let data = {};

btn.addEventListener('click', (e)=>{
    e.preventDefault();

    
    error.innerHTML = '';

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../app/api/group.api.php', true);

    xhr.onload = () => {
        console.log(xhr.response);
        data = JSON.parse(xhr.response);
        if(data['status'] == true) {
            window.location.href = 'message.php?group='+data['group_id'];
        }
        else {
            error.innerHTML = data['error'];
        }
    }

    let formData = new FormData(form);
    xhr.send(formData);
});