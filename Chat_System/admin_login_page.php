<?php
session_start();  // セッションの開始
if(isset($_SESSION['error1'])){
    $error1 = $_SESSION['error1'];
    unset($_SESSION['error1']);
}
if(isset($_SESSION['error2'])){
    $error2 = $_SESSION['error2'];
    unset($_SESSION['error2']);
}
if(isset($_POST['admin_login'])){  // 管理者ログインボタンが押された場合
    $email = $_POST['admin_mail'];  // 入力内容の受け取り
    $password = $_POST['admin_pass'];
    if($email === ''){  // 未入力項目の確認
        $error1 = '!Eメールアドレスを入力して下さい!';
    }
    if($password === ''){
        $error2 = '！パスワードを入力して下さい！';
    }
    if(!isset($error1)&&!isset($error2)){
        $_SESSION['admin_mail'] = $email;
        $_SESSION['admin_pass'] = $password;
        header('Location: admin_check.php');
        exit;
    }
}
?>

<!doctype html>
<html lang='ja'>

<head>
<title>管理者ログインページ</title>
</head>

<body>

<h1>ログイン</h1>
<form action='' method='post'>
<label>Eメールアドレス<br><input type='text' name='admin_mail'></label>
<?php if(isset($error1)){echo $error1;}?><br>
<label>パスワード<br><input type='text' name='admin_pass'></label>
<?php if(isset($error2)){echo $error2;}?><br>
<input type='submit' name='admin_login' value='ログイン'><br>
</form><br><br>

</body>
</html>