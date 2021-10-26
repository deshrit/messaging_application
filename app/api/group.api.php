<?php
require_once '../core/Database.php';
require_once '../core/Functions.php';
require_once '../models/Index.php';
require_once '../models/Group.php';


if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_COOKIE['user_id']) && isset($_COOKIE['token'])) {

        $index = new Index;
        $group = new Group;

        if($index->verify_user($_COOKIE['user_id'], $_COOKIE['token'])) {
            
            // xhr memeber search
            if(isset($_POST['users'])) {
                $result = $group->get_users_to_insert($_COOKIE['user_id']);
                if($result) {
                    $data = '';
                    // echo json_encode(['status'=>true, 'data'=>$data]);
                    foreach($result as $user) {
                        $data .= "
                        <div class=active-user>
                            <img src=../app/uploads/{$user['profile_img_name']} alt=user-icon class=user-img>
                            <div class=active-user-name><b>{$user['user_name']}</b></div>
                            <input name=id[{$user['user_id']}] type=checkbox>
                        </div>
                        ";
                    }
                    echo $data;
                }
            }
            else {

                // Signup Errors
                $creating_error = [];

                // User Inputs
                $grpname = trim($_POST['grpname']);
                $members = isset($_POST['id']) ? $_POST['id'] : '';
                $grp_img_name = '';

                // User Input Validation
                if(!empty($grpname) && !empty($members)) {
                    if(!validate_username($grpname)) {
                        $creating_error = 'Username can have a-z, A-Z, 0-9, _ and be greater than 5 characters';
                    }
                }
                else {
                    $creating_error = 'Fields incomplete';
                }

                // Validated Input Upload
                if(empty($creating_error)) {
                    // Uploading profile image
                    $image_upload = handle_grp_image_upload($_FILES['grpphoto']);
                    if($image_upload['status']) {
                        $grp_img_name = $image_upload['name'];
                        // Upload user to database
                        $grp_data = [$grpname, $_COOKIE['user_id'], 1, $grp_img_name];
                    
                        if($group->create_grp($grp_data)) {
                            
                            $grp_id = $group->get_grp_id($grpname)['group_id'];
                            $n = 1;
                            $group->add_member($grp_id, $_COOKIE['user_id']);
                            foreach($members as $key => $value) {
                                $group->add_member($grp_id, $key);
                                $n++;
                            }
                            $group->update_members_count($n, $grp_id);
                            
                            echo json_encode(['status'=>true, 'group_id'=>$grp_id]);
                        }
                        else {
                            // Delete image if user could not be inserted
                            unlink($_SERVER['DOCUMENT_ROOT'].'/messenger/app/uploads/'.$grp_img_name);
                            $signup_error[] = 'user could not be created';
                            // Response
                            echo json_encode(['status'=>false, 'message'=>'group could not be created something went wrong :(']);
                        }
                    }
                    else {
                        $grp_create_error = $image_upload['err'];
                        // Response
                        echo json_encode(['status'=>false, 'error'=>$grp_create_error]);
                    }
                }
                else {
                    echo json_encode(['status'=>false, 'error'=>$creating_error]);
                }
            }
            
        }
        else {
            header('Location: login.php');
            die();
        }
    }
    else {
        header('Location: login.php');
        die();
    }

}