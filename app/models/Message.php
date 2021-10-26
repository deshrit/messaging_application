<?php

class Message extends Database
{
    // PRIVATE MESSAGING
    public function verify_receiver($receiver_id) {
        $query = "SELECT user_id FROM users WHERE user_id=? LIMIT 1";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$receiver_id]);
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }
    
    public function get_receiver_data($receiver_id) {
        $query = "SELECT user_id, user_name, email, user_created_at, profile_img_name
                    FROM users
                    WHERE user_id=?";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$receiver_id]);
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }

    public function insert_private_message(array $data):bool {
        $query = "INSERT INTO `private_message`(`sender_id`, `receiver_id`, `message`)
                    VALUES(:sender_id, :receiver_id, :message)";
        $stmt = $this->connection()->prepare($query);
        if($stmt->execute($data)) {
            return true;
        }
        return false;
    }

    public function get_private_messages($sender_id, $receiver_id) {
        $query = "SELECT sender_id, receiver_id, message, timestamp 
                    FROM `private_message` 
                    WHERE (sender_id=:sender_id && receiver_id=:receiver_id) || (sender_id=:receiver_id && receiver_id=:sender_id)";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([':sender_id'=>$sender_id, ':receiver_id'=>$receiver_id]);
        if($result) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }

    // GROUP MESSAGING
    public function verify_group($grp_id) {
        $query = "SELECT group_id FROM `group` WHERE group_id = ? LIMIT 1";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$grp_id]);
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }

    public function verify_user_in_group($grp_id, $user_id) {
        $query = "SELECT group_id, user_id FROM group_users WHERE group_id=?, user_id=?";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$grp_id, $user_id]);
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }

    public function get_group_data($grp_id) {
        $query = "SELECT group_id, group_name, admin_id, total_members, group_img_name, group_created_at 
                    FROM `group`
                    WHERE group_id=?";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$grp_id]);
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }

    public function get_admin($user_id) {
        $query = "SELECT user_name
                    FROM `users`
                    WHERE user_id=?";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$user_id]);
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return $user_id;
    }

    public function insert_group_message(array $data):bool {
        $query = "INSERT INTO `group_message`(`group_id`, `sender_id`, `message`) VALUES(:group_id, :sender_id, :message)";
        $stmt = $this->connection()->prepare($query);
        if($stmt->execute($data)) {
            return true;
        }
        return false;
    }

    public function get_group_messages($group_id) {
        $query = "SELECT g.group_id, g.sender_id, g.message, g.timestamp, u.user_name, u.profile_img_name 
                    FROM group_message g
                    LEFT JOIN users u ON g.sender_id = u.user_id 
                    WHERE group_id=?";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$group_id]);
        if($result) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }
}