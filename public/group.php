<?php
require_once '../app/core/Database.php';
require_once '../app/models/Index.php';

if(isset($_COOKIE['user_id']) && isset($_COOKIE['token'])) {

    // Models Object     
    $index = new Index;

    if($index->verify_user($_COOKIE['user_id'], $_COOKIE['token']) == false) {
        header('Location: login.php');
        die();
    }

    $logged_in_user = $index->get_logged_in_user($_COOKIE['user_id']);
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
    <link rel="stylesheet" href="css/group.css">
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
        
        <form class="create-grp-form" name="create-grp-form">

            <div class="grp-header">Create Group</div>

            <div class="error"></div>

            <div class="grp-main-info">
                <input type="text" name="grpname" value="" placeholder="Group name ..." class="grp-name">
                <input type="file" name="grpphoto" class="grp-photo" accept="image/*" id="grp-photo">
                <label for="grp-photo" class="grp-photo-label">Choose group photo</label>
            </div>
            
            <div class="members-to-add-container">
                
                <input placeholder="search ..." class="search-members-input">
                
                <div class="members-to-add">
                    
                    <!-- <div class="active-user">
                        <img src="assets/user-icon.png" alt="activer-user-icon" class="active-user-img">
                        <div class="active-user-name"><b>username</b></div>
                        <input name="id[]" type="checkbox">
                    </div> -->

                </div>
            </div>

            <div class="submit-grp-info">
                <button type="submit" name="create" class="submit-btn">Create</button>
            </div>
        </form>

    </div>


<script src="js/group.js"></script>
</body>
</html>