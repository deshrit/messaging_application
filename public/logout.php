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

    if($index->logout($_COOKIE['user_id'], $_COOKIE['token'])) {
        setcookie('token', '0', time()-86400);
        setcookie('user_id', '0', time()-86400);
        header('Location: login.php');
    }
    else {
        header('Location: logout.php');
    }
    
}
else {
    header('Location: login.php');
    die();
}

?>