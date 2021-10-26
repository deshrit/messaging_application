<?php
require_once '../core/Database.php';
require_once '../models/Index.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_COOKIE['user_id']) && isset($_COOKIE['token'])) {

        // Models Object
        $index = new Index;
        $inbox_user_data = '';
        $active_user_data = '';
        $searched_data = '';
        
        if($index->verify_user($_COOKIE['user_id'], $_COOKIE['token']) == true) {
            

            // GET CONVERSATIONS
            if(isset($_POST['get_conversations'])) {
                // PRIVATE CONVERSATIONS
                $users = $index->get_all_inbox_users($_COOKIE['user_id']);

                foreach($users as $user) {
                    $status = ($user['last_online'] > time()) ? 'online' : 'offline';

                    $inbox_user_data .= "<a href=message.php?receiver={$user['user_id']} class=friend>
                    <div class=chat-user>
                        <img src=../app/uploads/{$user['profile_img_name']} alt='friend-user' class='chat-user-img'>
                        <div class=chat-username-message>
                            <div class=chat-username><b>{$user['user_name']}</b></div>
                            <div class=chat-message>{$user['recent_message']}</div>
                        </div>
                        <div class={$status}></div>
                    </div>
                    </a>";
                }


                // GROUP CONVERSATIONS INBOX
                $groups_imin = $index->get_all_groups_imin($_COOKIE['user_id']);
                $groups_data = array();
                foreach($groups_imin as $group_imin) {
                    $groups_data[] = $index->get_a_inbox_group($group_imin['group_id']);
                }
                
                foreach($groups_data as $group) {
                    $inbox_user_data .= "<a class=friend href=message.php?group={$group['group_id']}>
                    <div class=chat-user>
                        <img src=../app/uploads/{$group['group_img_name']} alt='group-photo' class='chat-user-img'>
                        <div class='chat-username-message'>
                            <div class='chat-username'><b>{$group['group_name']}</b></div>
                            <div class='chat-message'>{$group['recent_message']}</div>
                        </div>
                    </div>
                </a>";
                }


                if(!empty($inbox_user_data)) {
                    echo $inbox_user_data;
                }
                else {
                    echo "<br><h2 style='color: silver;'>Your conversations appear here...</h2>";
                }
                $inbox_user_data = '';
            }

            // MAKE ACTIVE AND GET ACTIVE URS
            if(isset($_POST['make_active']) && isset($_POST['get_active_users'])) {

                $index->make_active(time()+10, $_COOKIE['user_id']);
            
                $active_users = $index->get_active_users(time(), $_COOKIE['user_id']);

                foreach($active_users as $active_user) {
                    $active_user_data .= "<a href=message.php?receiver={$active_user['user_id']}>
                            <div class=active-user>
                                <img src=../app/uploads/{$active_user['profile_img_name']} alt=activer-user-icon class=active-user-img>
                                <div class=active-user-name><b>{$active_user['user_name']}</b></div>
                                <div class=online></div>
                            </div>
                        </a>
                    ";
                }

                if(!empty($active_user_data)) {
                    echo $active_user_data;
                }
                else {
                    echo "<br><h2 style='color: silver;'>No active users..</h2>";
                }
                $active_user_data = '';
            }

            // SEARCH
            if(!empty($_POST['search'])) {

                $searched_content = $index->search($_POST['search']);

                foreach($searched_content as $user) {

                    $status = ($user['last_online'] > time()) ? 'online' : 'offline';

                    $searched_data .= "<a href='message.php?receiver={$user['user_id']}'>
                        <div class='searched-user'>
                            <img src=../app/uploads/{$user['profile_img_name']} alt='searched-user-icon' class='searched-user-img'>
                            <div class='searched-user-name' ><b>{$user['user_name']}</b></div>
                            <div class={$status}></div>
                        </div>
                    </a>
                    ";
                }

                if(!empty($searched_data)) {
                    echo $searched_data;
                }
                else {
                    echo "<h3 style='color: silver;'>No users found..</h3>";
                }
                $searched_data = '';
            }

        }
    }

}