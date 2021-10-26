<?php
require_once '../app/core/Database.php';
require_once '../app/models/Index.php';

if(isset($_COOKIE['user_id']) && isset($_COOKIE['token'])) {

    // Models Object     
    $index = new Index;
    $inbox_user_data = '';
    $active_user_data = '';

    if($index->verify_user($_COOKIE['user_id'], $_COOKIE['token']) == false) {
        header('Location: login.php');
        die();
    }

    $logged_in_user = $index->get_logged_in_user($_COOKIE['user_id']);

    // PRIVATE CONVERSATIONS INBOX
    $users = $index->get_all_inbox_users($_COOKIE['user_id']);
    foreach($users as $user) {
        $status = ($user['last_online'] > time()) ? 'online' : 'offline';
        $recent_message = "";
        if($user)
        
        $inbox_user_data .= "<a class=friend href=message.php?receiver={$user['user_id']}>
        <div class=chat-user>
            <img src=../app/uploads/{$user['profile_img_name']} alt='friend-user' class='chat-user-img'>
            <div class='chat-username-message'>
                <div class='chat-username'><b>{$user['user_name']}</b></div>
                <div class='chat-message'>{$user['recent_message']}</div>
            </div>
            <div class={$status}></div>
        </div>
    </a>";
    }

    // GROP CONVERSATIONS INBOX
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
}
else {
    header('Location: login.php');
    die();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

    <!-----------------------------------------------------------Nav bar --------------------------------------------->

    <nav>
        
        <div class="logo-container">
            <a href="index.php">APPLICATION</a>
        </div>
        
        <div class="search-from-container">

            <div class="search-form">
                <input type="text" placeholder="search ..." class="search-input">
                <button class="search-btn">
                    <svg width="28" height="28">
                        <circle cx="12" cy="12" r="8" stroke="black" stroke-width="2px" fill="none" />
                        <line x1="17" y1="17" x2="24" y2="24" stroke="black" stroke-width="2px" fill="none"/>
                    </svg>
                </button>
            </div>

            <div class="searched-content-container">
                    <!-- <a href="message.php?receiver=1">
                        <div class="searched-user">
                            <img src="assets/user-icon.png" alt="searched-user-icon" class="searched-user-img">
                            <div class="searched-user-name"><b>username</b></div>
                            <div class="online"></div>
                        </div>
                    </a> -->
            </div>

        </div>

        <!---- Logged in user ---->
        <div class="user-log-container">

            <div class="user-log">
                <img src="<?php echo '../app/uploads/'.$logged_in_user['profile_img_name']; ?>" alt="user-img">
            </div>
            <div class="user-log-name"><?php echo $logged_in_user['user_name']; ?></div>
            
            <div class="dropdown">
                <div class="create-group"><a href="group.php">Create Group</a></div>
                <div class="logout"><a href="logout.php">Logout</a></div>
            </div>
        </div>

    </nav>

    




    <div class="main-body">
    
        <!-----------------------------------------------------------Left Sidebar --------------------------------------------->

        <div class="left-sidebar">

            <div class="left-sidebar-header"><h3>Chats</h3></div>
            
            <div class="chat-block-users">
                <!------ Conversation Users ---->
                <div class="converstion-users-block">
                    <?php
                        if(!empty($inbox_user_data)) {
                            echo $inbox_user_data;
                        }
                        else {
                            echo "<br><h2 style='color: silver;'>Your conversations appear here...</h2>";
                        }
                    ?>
                </div>

                <!------ Active Users ------>
                <div class="active-users-block">
                    <div class="active-users-header"><h3>Active users</h3></div>

                    <div class="active-users-container">
                        
                        <?php
                            if(!empty($active_user_data)) {
                                echo  $active_user_data;
                            }
                            else {
                                echo "<br><h2 style='color: silver;'>No active users..</h2>";
                            }
                        ?>

                        <!-- <a href="message.php?receiver=1">
                            <div class="active-user">
                                <img src="assets/user-icon.png" alt="activer-user-icon" class="active-user-img">
                                <div class="active-user-name"><b>username</b></div>
                                <div class="online"></div>
                            </div>
                        </a> -->

                    </div>
                </div>
            </div>

        </div>

        <!-- ---------------------------------------------------------Middle Sidebar ------------------------------------------->

        <div class="main-content-bar">

            <div class="main-content-bar-header"></div>

            <div class="message-main-container">

                <div class="message-receive-div">
                    <div class="message-from-sender">
                        <div class="message-rows-container-receive">
                        </div>
                    </div>
                </div>

                <div class="message-send-div">
                    <div class="message-rows-container-send">
                    </div>
                </div>

            </div>
            
            <form class="message-send-container">
                <div type="text" class="message-send-input"></div>
                <div type="submit" name="send-message" class="message-send-btn">
                    <svg width="40" height="28">
                        <polygon points="6,6 32,14 6,22 10,14" style="fill:none;stroke:rgb(220, 220, 220);stroke-width:2" />
                    </svg>
                </div>
            </form>
        </div>



        <!-- -------------------------------------------------------Right Sidebar ----------------------------------------- -->
        <div class="right-sidebar">

            <div class="right-sidebar-container">
                <div class="right-bar-img-container">
                    <div class="image-frame"></div>
                </div>
                <div class="right-bar-user-data"></div>
                <div class="right-bar-user-data"></div>
                <div class="right-bar-user-data"></div>
            </div>

        </div>

    </div>

<script src="js/index.js"></script>
</body>
</html>