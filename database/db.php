<?php
$host = 'localhost';
$dbname = 'shopink';
$username = 'root';
$password = '';


try {
$pdo = new PDO('mysql:host=localhost;dbname=shopink', 'root', '');  
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){
  echo 'Connection Failed: ' . $e->getMessage();
}
?>