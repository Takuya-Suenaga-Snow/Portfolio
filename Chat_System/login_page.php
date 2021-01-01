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
if(isset($_POST['login'])){  // ログインボタンが押された場合
    $email = $_POST['email'];  // 入力内容の受け取り
    $password = $_POST['password'];
    if($email === ''){  // 未入力項目の確認
        $error1 = '!Eメールアドレスを入力して下さい!';
    }
    if($password === ''){
        $error2 = '！パスワードを入力して下さい！';
    }
    if(isset($_SESSION['error1'])){
        $error1 = $_SESSION['error1'];
        unset($_SESSION['error1']);
    }
    if(!isset($error1)&&!isset($error2)){
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['password'] = $_POST['password'];
        header('Location: check.php');
        exit;
    }
}
?>
<!doctype html>
<html lang='ja'>

<head>
<title>ログインページ</title>
<style>
a{text-decoration:none;}
a:hover{text-decoration:underline;}
</style>
</head>

<body>

<h1>Tech-Chat</h1>  <!-- ログインフォーム -->
<h2>ログイン</h2>
<form action='' method='post'>
<label>ログインID<br><input type='text' name='email' placeholder='example@mail.jp'></label>
<?php if(isset($error1)){echo $error1;}?><br>
<label>パスワード<br><input type='text' name='password'></label>
<?php if(isset($error2)){echo $error2;}?><br>
<input type='submit' name='login' value='ログイン'><br>
</form><br><br>

<a href='register_page.php'>新規登録はこちらから</a><br><br>
<a href='admin_login_page.php'>管理者ページ</a><br><br>

</body>
</html>