<?php
session_start();
$dsn = '******';
$username = '******';
$password = '******';
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
try{
    $pdo = new PDO($dsn, $username, $password, $options);
}catch(PDOException $e){
    exit;
}
$comment = $_POST['comment'];
$str = preg_replace('/( |　)/', '', $comment);
if($str !== ''){
    $sql = $pdo -> prepare('INSERT INTO Chat (name, comment, time) VALUES (:name, :comment, :time)');
    $sql -> bindParam(':name', $_SESSION['name'], PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':time', date('Y:m:d:H:i:s'), PDO::PARAM_STR);
    $sql -> execute();
    $pdo = null;
}
?>