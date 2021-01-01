<?php
session_start();
$email = '******@gmail.com';
$password = '******';
if($email===$_SESSION['admin_mail']){
    if($password===$_SESSION['admin_pass']){
        header('Location: admin_page.php');
        exit;
    }else{
        $_SESSION['error2'] = '！パスワードが不正です！';
    }
}else{
    $_SESSION['error1'] = '！メールアドレスが不正です！';
}
$pdo = null;
unset($_SESSION['admin_mail']);
unset($_SESSION['admin_pass']);
header('Location: admin_login_page.php');
exit;
?>