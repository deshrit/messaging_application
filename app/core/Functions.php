<?php

// User Image upload
function handle_image_upload(iterable $file):array {
    // File data
    $type = $file['type'];
    $type = explode('/', $type);

    // Returning data
    $uploaded = false;
    $err = [];

    if($file['error'] == 0) {
        
        if(in_array('image', $type)) {
            if($file['size'] > 1024 && $file['size'] < 2097152) {
                if(move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/messenger/app/uploads/'.$file['name'])) {
                    $uploaded = true;
                }
            }
            else {
                $err[] = 'File size only 1kb to 2mb';
            }
        }
        else {
            $err[] = 'Invalid file * must be image';
        }
    }
    else if($file['error'] == 4) {
        $file['name'] = 'dummy.png';    // Default image
        $uploaded = true;
    }

    else {
        $err[] = 'Error uploading image';
    }

    return ['status'=>$uploaded, 'name'=>$file['name'], 'err'=>$err];
}

// Group image upload
function handle_grp_image_upload(iterable $file):array {
    // File data
    $type = $file['type'];
    $type = explode('/', $type);

    // Returning data
    $uploaded = false;
    $err = [];

    if($file['error'] == 0) {
        
        if(in_array('image', $type)) {
            if($file['size'] > 1024 && $file['size'] < 2097152) {
                if(move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/messenger/app/uploads/'.$file['name'])) {
                    $uploaded = true;
                }
            }
            else {
                $err[] = 'File size only 1kb to 2mb';
            }
        }
        else {
            $err[] = 'Invalid file * must be image';
        }
    }
    else if($file['error'] == 4) {
        $file['name'] = 'dummy_grp.jpg';    // Default image
        $uploaded = true;
    }

    else {
        $err[] = 'Error uploading image';
    }

    return ['status'=>$uploaded, 'name'=>$file['name'], 'err'=>$err];
}


// Validate username
function validate_username($username):bool {
    $pattern = "/^[A-Za-z_]{1}[A-Za-z0-9._]{4,100}$/";
    if(preg_match($pattern, $username)) {
        return true;
    }
    return false;
}



// Validate email
function validate_email($email):bool {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}



// Validate password
function validate_password($password):bool {
    if(strlen($password) > 4) {
        return true;
    }
    return false;
}