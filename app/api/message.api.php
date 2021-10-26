<?php

require_once '../core/Database.php';
require_once '../models/Index.php';
require_once '../models/Message.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_COOKIE['user_id']) && isset($_COOKIE['token'])) {

        // Models Object
        $index = new Index;
        $message = new Message;
        
        if($index->verify_user($_COOKIE['user_id'], $_COOKIE['token'])) {

            // Fetch message
            if(isset($_POST['sender_id'])) {
                $result = $message->get_private_messages($_POST['sender_id'], $_COOKIE['user_id']);
                if($result) {
                    echo json_encode($result);
                }
                else {
                    echo json_encode(['status'=>false, 'error'=>'something went wrong resend :(']);
                }
            }
            // Insert message
            else if(isset($_POST['receiver_id']) && isset($_POST['message'])) {
                if($message->verify_receiver($_POST['receiver_id'])) {
                    $data = [':sender_id'=>$_COOKIE['user_id'], ':receiver_id'=>$_POST['receiver_id'], ':message'=>htmlspecialchars($_POST['message'])];
                    if($message->insert_private_message($data)) {
                        echo json_encode(['status'=>true, 'messages'=>$message->get_private_messages($_POST['receiver_id'], $_COOKIE['user_id'])]);
                    }
                    else {
                        echo json_encode(['status'=>false, 'error'=>'text could not be send']);
                    }
                }
                else {
                    echo json_encode(['status'=>false, 'error'=>'invalid receiver']);
                }

            }
            // Fetch group message
            else if(isset($_POST['sender_group_id'])) {
                $result = $message->get_group_messages($_POST['sender_group_id']);
                if($result) {
                    echo json_encode($result);
                }
                else {
                    echo json_encode(['status'=>false, 'error'=>'something went wrong resend :(']);
                }
            }
            // Insert group message
            else if(isset($_POST['group_id']) && isset($_POST['message'])) {
                
                if($message->verify_group($_POST['group_id'])) {
                    $data = [':group_id'=>$_POST['group_id'], ':sender_id'=>$_COOKIE['user_id'], ':message'=>htmlspecialchars($_POST['message'])];

                    if($message->insert_group_message($data)) {
                        echo json_encode(['status'=>true, 'messages'=>$message->get_group_messages($_POST['group_id'])]);
                    }
                    else {
                        echo json_encode(['status'=>false, 'error'=>'text could not be send']);
                    }
                }
                else {
                    echo json_encode(['status'=>false, 'error'=>'invalid group']);
                }
            }

        }
    }

}