<?php
namespace inquiry; 

class PDO {
  
  public $db;

  public function __construct() {
     $dsn = 'mysql:dbname=inquiry_db;host=localhost;charset=utf8mb4';
     $user = 'user';
     $password = 'ronaldo7';
    try {
      $this->db = new \PDO($dsn, $user, $password);
    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }
  
}