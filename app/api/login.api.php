<?php

header('Content-Type: application/json');

require_once '../core/Database.php';
require_once '../models/Login.php';
require_once '../core/Functions.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Database connection
    $login = new Login;

    // Login  errors
    $login_error = '';

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate User Input
    if(!empty($email) && !empty($password)) {
        if(validate_email($email)) {
            if(!validate_password($password)) {
                $login_error = 'Invalid password';
            }
        }
        else {
            $login_error = 'Invalid email';
        }
    }
    else {
        $login_error = 'Input fields empty';
    }

    // Check Credentials
    if(empty($login_error)) {
        $result = $login->user_credentials([':email'=>$email, ':password'=>md5($password)]);
        if($result['status'] === true) {
            // Response
            echo json_encode($result);
        }
        else {
            // Response
            echo json_encode($result);
        }
    }
    else {
        // Response
        echo json_encode(['status'=>false, 'error'=>$login_error]);
    }

}