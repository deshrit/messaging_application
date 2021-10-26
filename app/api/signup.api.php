<?php

header('Content-Type: application/json');

require_once '../core/Database.php';
require_once '../models/Signup.php';
require_once '../core/Functions.php';


if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Database Connection
    $signup = new Signup;

    // Signup Errors
    $signup_error = [];

    // User Inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $profile_img_name = '';

    // User Input Validation
    if(!empty($username) && !empty($email) && !empty($password)) {
        if(!validate_username($username)) {
            $signup_error[] = 'Username can have a-z, A-Z, 0-9, _ and be greater than 5 characters';
        }
        if(validate_email($email)) {
            if($signup->email_exists($email)) {
                $signup_error[] = 'Email already in use';
            }
        }
        if(!validate_email($email)) {
            $signup_error[] = 'Invalid Email';
        }
        if(!validate_password($password)) {
            $signup_error[] = 'Password must be greater than 5 chars';
        }
    }
    else {
        $signup_error[] = 'Input fields cannot be empty';
    }

    // Validated Input Upload
    if(empty($signup_error)) {
        // Uploading profile image
        $image_upload = handle_image_upload($_FILES['image']);
        if($image_upload['status']) {
            $profile_img_name = $image_upload['name'];
            // Upload user to database
            $signup_data = [$username, $email, md5($password), $profile_img_name, time()];
            if($signup->insert_user($signup_data)) {
                // Response
                echo json_encode(['status'=>true, 'message'=>'user created']);
            }
            else {
                // Delete image if user could not be inserted
                unlink($_SERVER['DOCUMENT_ROOT'].'/messenger/app/uploads/'.$profile_img_name);
                $signup_error[] = 'user could not be created';
                // Response
                echo json_encode(['status'=>false, 'message'=>'user could not be created something went wrong :(']);
            }
        }
        else {
            $signup_error = $image_upload['err'];
            // Response
            echo json_encode(['status'=>false, 'error'=>$signup_error]);
        }
    }
    else {
        // Response
        echo json_encode(['status'=>false, 'error'=>$signup_error]);
    }
}