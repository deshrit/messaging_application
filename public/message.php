<?php

require_once '../app/core/Database.php';
require_once '../app/models/Index.php';
require_once '../app/models/Message.php';

// Global variables
$inbox_user_data = '';
$receiver_data = [];
$actual_messages = [];

$admin = [];
$receiver_group_data = [];
$actual_group_messages = [];

if(isset($_COOKIE['user_id']) && isset($_COOKIE['token'])) {

    // Models Object     
    $index = new Index;
    $message = new Message;

    $inbox_user_data = '';
    $active_user_data = '';

    if($index->verify_user($_COOKIE['user_id'], $_COOKIE['token']) == false) {
        header('Location: login.php');
        die();
    }

    $logged_in_user = $index->get_logged_in_user($_COOKIE['user_id']);

    // GROP CONVERSATIONS INBOX
    $users = $index->get_all_inbox_users($_COOKIE['user_id']);
    foreach($users as $user) {
        $status = ($user['last_online'] > time()) ? 'online' : 'offline';
        
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

    if(isset($_GET['receiver'])) {
        if($message->verify_receiver($_GET['receiver'])) {
            $receiver_data = $message->get_receiver_data($_GET['receiver']);
            if($receiver_data) {
                $actual_messages = $message->get_private_messages($_GET['receiver'], $_COOKIE['user_id']);
            }
            else {
                header('Location: index.php');
                die();
            }
        }
    }
    else if(isset($_GET['group'])) {
        if($message->verify_group($_GET['group'])) {
            $receiver_group_data = $message->get_group_data($_GET['group']);
            $admin = $message->get_admin($receiver_group_data['admin_id']);
            if($receiver_group_data) {
                $actual_group_messages = $message->get_group_messages($_GET['group']);
            }
            else {
                header('Location: index.php');
                die();
            }
        }
        else {
            header('Location: index.php');
            die();
        }
    }
    else {
        header('Location: index.php');
        die();
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
    <title>Message</title>
    <link rel="stylesheet" href="css/message.css">
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
                <img src="<?php echo htmlspecialchars('../app/uploads/'.$logged_in_user['profile_img_name']); ?>" alt="user-img">
            </div>
            <div class="user-log-name"><?php echo htmlspecialchars($logged_in_user['user_name']); ?></div>

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

                    </div>
                </div>
            </div>


        </div>

        <!-----------------------------------------------------------Middle Sidebar --------------------------------------------->

        <div class="main-content-bar">
            
            <?php
                
                ///////////////////////////////////// PRIVATE CONVERSATION BLOCK /////////////////////////////
                if(isset($_GET['receiver'])) {

                    ?>
                    <div class="main-content-bar-header">
                        <img src="<?php echo '../app/uploads/'.$receiver_data['profile_img_name']; ?>" alt="friend-user" class="main-bar-user-img">
                        <div class="main-bar-username"><h3><?php echo $receiver_data['user_name']; ?></h3></div>
                    </div>
        
                    <div class="message-main-container">
        
                        <?php 
                            foreach($actual_messages as $message) {
        
                                if($message['sender_id'] == $_GET['receiver']){
                                    ?>
        
                                    <div class="message-receive-div">
                                        <div class="message-from-sender">
                                            <img src="<?php echo '../app/uploads/'.$receiver_data['profile_img_name']; ?>" alt="friend-user" class="message-from-sender-img">
                                            <div class="message-rows-container-receive">
                                                <div class="message-row-from-sender">
                                                    <div class="actual-message">
                                                        <?php echo $message['message']; $flag = -1; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        
                                    <?php
                                }
        
                                else if($message['sender_id'] == $_COOKIE['user_id']) {
                                    
                                    ?>
                                    
                                    <div class="message-send-div">
                                        <div class="message-rows-container-send">
                                            <div class="message-row-to-send"><div class="actual-message"><?php echo $message['message']; ?></div></div>
                                        </div>
                                    </div>
                                    
                                    <?php
                                }
                            }
                        ?>

                    </div>

                <?php
                
                }
                ///////////////////////////////////// GROUP CONVERSATION BLOCK /////////////////////////////
                else if(isset($_GET['group'])) {

                    ?>
                    <div class="main-content-bar-header">
                        <img src="<?php echo '../app/uploads/'.$receiver_group_data['group_img_name']; ?>" alt="group_image" class="main-bar-user-img">
                        <div class="main-bar-username"><h3><?php echo $receiver_group_data['group_name']; ?></h3></div>
                    </div>
    
                    <div class="message-main-container">
        
                        <?php 
                            foreach($actual_group_messages as $message) {
        
                                if($message['sender_id'] == $_COOKIE['user_id']) {
                                    
                                    ?>
                                    
                                    <div class="message-send-div">
                                        <div class="message-rows-container-send">
                                            <div class="message-row-to-send"><div class="actual-message"><?php echo $message['message']; ?></div></div>
                                        </div>
                                    </div>
                                    
                                    <?php
                                }

                                else {
                                    ?>
        
                                    <div class="message-receive-div">
                                        <div class="message-from-sender">
                                            <img src="<?php echo '../app/uploads/'.$message['profile_img_name']; ?>" alt="group-user" class="message-from-sender-img">
                                            <div class="message-rows-container-receive">
                                                <div><span style="font-size: .6em; padding-left: 10px; color: #888;"><?php echo $message['user_name']; ?></span></div>
                                                <div class="message-row-from-sender">
                                                    <div class="actual-message">
                                                        <?php echo $message['message']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        
                                    <?php
                                }
                                
                            }
                        ?>
        
                    </div>
                    
                    <?php
               
                }
                        
                ?>
            
            <form class="message-send-container">
                <input type="text" name="message-input" class="message-send-input" value="" placeholder="Type text to send ..." autocomplete="off">
                <button type="submit" name="send-message" class="message-send-btn">
                    <svg width="40" height="28">
                        <polygon points="6,6 32,14 6,22 10,14" style="fill:none;stroke:black;stroke-width:2" />
                    </svg>
                </button>
            </form>
        </div>



        <!-----------------------------------------------------------Right Sidebar --------------------------------------------->

        <?php

            ///////////////////////////////////// PRIVATE RIGHT BLOCK /////////////////////////////
            if(isset($_GET['receiver'])) {

                ?>

                    <div class="right-sidebar">

                    <div class="right-sidebar-container">
                        <div class="right-bar-img-container">
                            <img src="<?php echo '../app/uploads/'.$receiver_data['profile_img_name']; ?>" alt="friend-user">
                        </div>
                        <div class="right-bar-user-data">
                            <b>Username</b>: <?php echo $receiver_data['user_name']; ?>
                        </div>
                        <div class="right-bar-user-data">
                            <b>Email</b>: <?php echo $receiver_data['email']; ?>
                        </div>
                        <div class="right-bar-user-data">
                            <b>Joined</b>: <?php echo substr($receiver_data['user_created_at'], 0, 10); ?>
                        </div>
                    </div>

                    </div>

                    <script>
                        window.user_id = "<?php echo $_COOKIE['user_id']; ?>";
                        window.receiver_id = "<?php echo $_GET['receiver']; ?>";
                        window.receiver_profile_img = "<?php echo '../app/uploads/'.$receiver_data['profile_img_name']; ?>";
                    </script>

                <?php

            }
            ///////////////////////////////////// PRIVATE RIGHT BLOCK /////////////////////////////
            else if(isset($_GET['group'])) {
                
                ?>

                    <div class="right-sidebar">

                        <div class="right-sidebar-container">
                            <div class="right-bar-img-container">
                                <img src="<?php echo '../app/uploads/'.$receiver_group_data['group_img_name']; ?>" alt="friend-user">
                            </div>
                            <div class="right-bar-user-data">
                                <b>Group name</b>: <?php echo $receiver_group_data['group_name']; ?>
                            </div>
                            <div class="right-bar-user-data">
                                <b>Admin</b>: <?php echo $admin['user_name']; ?>
                            </div>
                            <div class="right-bar-user-data">
                                <b>Created</b>: <?php echo substr($receiver_group_data['group_created_at'], 0, 10); ?>
                            </div>
                            <div class="right-bar-user-data">
                                <b>Total members</b>: <?php echo $receiver_group_data['total_members']; ?>
                            </div>
                        </div>

                    </div>

                    <script>
                        window.user_id = "<?php echo $_COOKIE['user_id']; ?>";
                        window.group_id = "<?php echo $receiver_group_data['group_id']; ?>";
                        window.group_profile_img = "<?php echo '../app/uploads/'.$receiver_group_data['group_img_name']; ?>";
                    </script>

                <?php
            }
            
        ?>

    </div>

<script src="js/message.js"></script>
</body>
</html>