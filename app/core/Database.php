<?php

class Database
{
    private $host = 'localhost';
    private $dbname = 'messenger';
    private $user = 'app_user';
    private $pwd = 'app_user_password';

    protected function connection() {
        $pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pwd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}