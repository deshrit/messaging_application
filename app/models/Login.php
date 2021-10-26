<?php

class Login extends Database
{
    private function get_user(array $login_data) {
        $query = "SELECT * FROM `users` WHERE (email=:email && password=:password) LIMIT 1";
        $stmt = $this->connection()->prepare($query);
        if($stmt->execute($login_data)) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result) {
                return $result;
            }
            return false;
        }
        return false;
    }

    public function insert_cookie(array $data) {
        $query = "INSERT INTO `login_tokens`(`token`, `user_id`) VALUES(:token, :user_id)";
        $stmt = $this->connection()->prepare($query);
        if($stmt->execute($data)) {
            return true;
        }
        return $false;
    }

    public function user_credentials(array $login_data) {
        $result = $this->get_user($login_data);
        if($result) {
            if($result['email'] == $login_data[':email']) {
                if($result['password'] == $login_data[':password']) {
                    // Creating and inserting cookie
                    $credential = [];
                    $credential[':user_id'] = $result['user_id'];
                    $credential[':token'] = bin2hex(random_bytes(random_int(10, 20)));;

                    if($this->insert_cookie($credential)) {
                        return ['status'=>true, 'credential'=>$credential];
                    }
                    return ['status'=>false, 'error'=>'Something went wrong try again :('];
                }
                return ['status'=>false, 'error'=>'Invalid password'];
            }
        }
        return ['status'=>false, 'error'=>'Invalid username or password'];
    }
}