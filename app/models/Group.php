<?php

class Group extends Database
{
    public function get_users_to_insert($user_id) {
        $query = "SELECT user_id, user_name, profile_img_name FROM users WHERE user_id != ?";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$user_id]);
        if($result) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }

    public function create_grp(array $grp_data) {
        $query = "INSERT INTO `group`(group_name, admin_id, total_members, group_img_name) VALUES(?, ?, ?, ?)";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute($grp_data);
        if($result) {
            return true;
        }
        return false;
    }

    public function get_grp_id($grp_name) {
        $query = "SELECT group_id FROM `group` WHERE group_name = ? LIMIT 1";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$grp_name]);
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }

    public function add_member($grp_id, $member_id) {
        $query = "INSERT INTO group_members(group_id, user_id) VALUES(?, ?)";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$grp_id, $member_id]);
        if($result) {
            return true;
        }
        return false;
    }

    public function update_members_count($total_members, $group_id) {
        $query = "UPDATE `group` SET total_members = ? WHERE group_id = ?";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute([$total_members, $group_id]);
        if($result) {
            return true;
        }
        return false;
    }
}