/* --------------------------------------------------------------------- CSS ---------------------------------------------------------- */
/* --------------------------------------------------------------------- CSS ---------------------------------------------------------- */

// Search From Container
const searchForm = document.querySelector('.search-form');
const searchInput = document.querySelector('.search-input');
// Box shadow
searchInput.addEventListener('focus', ()=>{ // Click event because input:focus conflicts
    searchForm.style.boxShadow = '0 0 3px 1px rgb(217, 185, 236)';
});
searchInput.addEventListener('focusout', () => {
    searchForm.style.boxShadow = 'none';

    window.setTimeout(()=> {
        search.style.display = 'none';
        searchInput.value = '';
    }, 100);
});

// Messaging sending form
const messageSendFrom = document.querySelector('.message-send-container');
const messageSendInput = document.querySelector('.message-send-input');
// Box shadow
messageSendInput.addEventListener('focus', ()=>{ // Click event because input:focus conflicts
    messageSendFrom.style.boxShadow = '0 0 3px 1px rgb(217, 185, 236)';
});

messageSendInput.addEventListener('focusout', () => {
    messageSendFrom.style.boxShadow = 'none';
});

// Scrolll to bottom
const messageMainContainer = document.querySelector('.message-main-container');
messageMainContainer.scrollTop = messageMainContainer.scrollHeight;


/* -------------------------------------------------------------------- XHR -------------------------------------------------- */
/* -------------------------------------------------------------------- XHR -------------------------------------------------- */


let post_send_var = window.location.search.substr(1).split('=');

// reciever_id is a global variable
const convoUsersBlock = document.querySelector('.converstion-users-block');
const activeUserBlock = document.querySelector('.active-users-container');

function get_conversations() {
    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        convoUsersBlock.innerHTML = this.response;
    }
    xhr.open('POST', '../app/api/index.api.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('get_conversations=true');
}

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

// Calling
if(document.cookie.indexOf('user_id') > -1) {
    setInterval(make_active_get_active_users, 3000);
    setInterval(get_conversations, 5000);
}


// Send message xhr
const messageInput = document.querySelector('.message-send-input');
const sendBtn = document.querySelector('.message-send-btn');

sendBtn.addEventListener('click', (e) => {
    e.preventDefault();

    if(messageInput.value.trim() != '' && messageInput.value.trim() != null) {
        let params = post_send_var[0] + '_id=' + post_send_var[1] +'&message=' + messageInput.value;

        const xhr = new XMLHttpRequest();
        xhr.onload = function() {
            // console.log(this.response);
            let res = JSON.parse(this.response);
            if(res.status == true) {
                if(post_send_var[0] == 'receiver') {
                    displayMessageResponse(res.messages);
                }
                else if(post_send_var[0] == 'group') {
                    displayGroupMessageResponse(res.messages);
                }
            }
        }
        xhr.open('POST', '../app/api/message.api.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(params);

        messageInput.value = '';
    }

});

// Fetch private message xhr
if(post_send_var[0] == 'receiver') {
    window.setInterval(fetchMessages, 5000, window.receiver_id);    // Receiver_id is the sender in this case of xhr
}
else if(post_send_var[0] == 'group') {
    window.setInterval(fetchGroupMessages, 5000, window.group_id);
}


// fetch private message
function fetchMessages(sender) {
    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        // console.log(this.response);
        displayMessageResponse(JSON.parse(this.response));
    }
    xhr.open('POST', '../app/api/message.api.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('sender_id='+sender);
}


// fetch group message
function fetchGroupMessages(group) {
    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        // console.log(this.response);
        displayGroupMessageResponse(JSON.parse(this.response));
    }
    xhr.open('POST', '../app/api/message.api.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('sender_group_id='+group);
}



// Display actual private conversation get from the server
function displayMessageResponse(messages) {
    let responseDisplay = '';
    messages.forEach(element => {
        if(element.sender_id == window.receiver_id) {
            responseDisplay += `
                <div class="message-receive-div">
                    <div class="message-from-sender">
                        <img src="${window.receiver_profile_img}" alt="friend-user" class="message-from-sender-img">
                        <div class="message-rows-container-receive">
                            <div class="message-row-from-sender"><div class="actual-message">${element.message}</div></div>
                        </div>
                    </div>
                </div>
            `;
        }
        else if(element.sender_id == window.user_id) {
            responseDisplay += `
                <div class="message-send-div">
                    <div class="message-rows-container-send">
                        <div class="message-row-to-send"><div class="actual-message">${element.message}</div></div>
                    </div>
                </div>
            `;
        }
    });
    messageMainContainer.innerHTML = responseDisplay;
    messageMainContainer.scrollTop = messageMainContainer.scrollHeight;
}



// Display actual group conversation get from the server
function displayGroupMessageResponse(messages) {
    let responseDisplay = '';
    messages.forEach(element => {

        if(element.sender_id == window.user_id) {
            responseDisplay += `
                <div class="message-send-div">
                    <div class="message-rows-container-send">
                        <div class="message-row-to-send"><div class="actual-message">${element.message}</div></div>
                    </div>
                </div>
            `;
        }
        else {
            responseDisplay += `
                <div class="message-receive-div">
                    <div class="message-from-sender">
                        <img src="../app/uploads/${element.profile_img_name}" alt="friend-user" class="message-from-sender-img">
                        <div class="message-rows-container-receive">
                            <div><span style="font-size: .6em; padding-left: 10px; color: #888;">${element.user_name}</span></div>
                            <div class="message-row-from-sender"><div class="actual-message">${element.message}</div></div>
                        </div>
                    </div>
                </div>
            `;
        }
    });
    messageMainContainer.innerHTML = responseDisplay;
    messageMainContainer.scrollTop = messageMainContainer.scrollHeight;
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