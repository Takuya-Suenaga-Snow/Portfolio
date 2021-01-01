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
$sql = 'SELECT * FROM UserData';
$stmt = $pdo -> query($sql);
$results = $stmt -> fetchAll();
foreach ($results as $row){
    if($row['email']===$_SESSION['email']){
        $_SESSION['error1'] = '！このメールアドレスは登録済みです！';
        unset($_SESSION['name']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        header('Location: register_page.php');
        exit;
    }
}
if(!isset($_SESSION['error1'])){
    $security_key = bin2hex(random_bytes(8));
    $sql = $pdo -> prepare('INSERT INTO UserData (name, email, password, security_key) VALUES (:name, :email, :password, :security_key)');
    $sql -> bindParam(':name', $_SESSION['name'], PDO::PARAM_STR);
    $sql -> bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
    $sql -> bindParam(':password', $_SESSION['password'], PDO::PARAM_STR);
    $sql -> bindParam(':security_key', $security_key, PDO::PARAM_STR);
    $sql -> execute();
}
$pdo = null;
$_SESSION = array();
header('Location: login_page.php');
exit;
?>