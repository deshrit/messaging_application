<?php

class Signup
{
    private $host = 'localhost';
    private $dbname = 'messenger';
    private $user = 'root';
    private $pwd = '';

    protected function connection() {
        $pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pwd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } 

    public function insert_user(array $signup_data):bool {
        $query = "INSERT INTO `users`(`user_name`, `email`, `password`, `profile_img_name`, `last_online`) VALUES(?, ?, ?, ?, ?)";
        $stmt = $this->connection()->prepare($query);
        if($stmt->execute($signup_data)) {
            return true;
        }
        return false;
    }

    public function email_exists($email):bool {
        $query = "SELECT `email` FROM `users` WHERE email=? LIMIT 1";
        $stmt = $this->connection()->prepare($query);
        $stmt->execute([$email]);
        if($stmt->rowCount()) {
            return true;
        }
        return false;
    }
}