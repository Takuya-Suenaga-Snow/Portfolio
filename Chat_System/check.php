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
$sql = 'SELECT * FROM UserData';  // UserDataに一致するEmailがあるか確認
$stmt = $pdo -> query($sql);
$results = $stmt -> fetchAll();
foreach ($results as $row){
    if($row['email']===$_SESSION['email']){
        if($row['password']===$_SESSION['password']){
            $_SESSION['name'] = $row['name'];
            $pdo = null;
            header('Location: main_page.php');
            exit;
        }else{
            $_SESSION['error2'] = '！パスワードが違います！';
        }
    }else{
        $_SESSION['error1'] = '！このメールアドレスは登録されていません！';
    }
}
$pdo = null;
unset($_SESSION['email']);
unset($_SESSION['password']);
header('Location: login_page.php');
exit;
?>