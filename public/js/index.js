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

// Left side bar
const convoUsersBlock = document.querySelector('.converstion-users-block');
const activeUserBlock = document.querySelector('.active-users-container');

// Get CONVERSATION users
function get_conversations() {
    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        convoUsersBlock.innerHTML = this.response;
    }
    xhr.open('POST', '../app/api/index.api.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('get_conversations=true');
}

// Get active users
function make_active_get_active_users() {
    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        activeUserBlock.innerHTML = "";
        activeUserBlock.innerHTML = this.response;
    }
    xhr.open('POST', '../app/api/index.api.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('make_active=true&get_active_users=true');
}

if(document.cookie.indexOf('user_id') > -1) {
    setInterval(make_active_get_active_users, 3000);
    setInterval(get_conversations, 5000);
}

// xhr SEARCH
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