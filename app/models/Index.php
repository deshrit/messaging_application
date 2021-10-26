<?php

class Index extends Database
{
    public function verify_user($user_id, $token):bool {
        $query = "SELECT * FROM `login_tokens` WHERE user_id=? && token=? LIMIT 1";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$user_id, $token]);
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result['user_id']==$user_id && $result['token']==$token) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function get_logged_in_user($user_id) {
        $query = "SELECT * FROM `users` WHERE user_id = ? LIMIT 1";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$user_id]);
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }

    public function make_active($current_time, $user_id) {
        $query = "UPDATE  `users` SET last_online=? WHERE user_id=?";
        $stmt = $this->connection()->prepare($query);
        if($stmt->execute([$current_time, $user_id])) {
            return true;
        }
        return false;
    }

    public function get_all_inbox_users($user_id) {
        $query = "SELECT u.user_id, u.user_name, u.profile_img_name, u.last_online, p.recent_message
                    FROM users u 
                    LEFT JOIN private_inbox p ON u.user_id=p.sender_id
                    WHERE p.user_id = ? ORDER BY p.timestamp DESC;";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$user_id]);
        if($result) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }

    public function get_all_groups_imin($user_id) {
        $query = "SELECT group_id FROM `group_members` WHERE user_id = ?";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$user_id]);
        if($result) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }

    public function get_a_inbox_group($group_id) {
        $query = "SELECT a.group_id, a.group_name, a.group_img_name, b.sender_id, b.recent_message
                    FROM `group` a LEFT JOIN group_inbox b ON a.group_id = b.group_id
                    WHERE a.group_id = ? LIMIT 1";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$group_id]);
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }


    public function get_active_users($current_time, $user_id) {
        $query = "SELECT user_id, user_name, profile_img_name
                    FROM users
                    WHERE (last_online > ?) && (user_id != ?)";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$current_time, $user_id]);
        if($result) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }

    public function search($like) {
        $query = "SELECT user_id, user_name, profile_img_name, last_online FROM users WHERE (user_name LIKE ?)";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute(['%'.$like.'%']);
        if($result) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }


    public function logout($user_id, $token):bool {
        $query = "DELETE FROM login_tokens WHERE user_id=? && token=?";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$user_id, $token]);
        if($result) {
            return true;
        }
        return false;
    }
}