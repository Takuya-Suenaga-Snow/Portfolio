<?php
session_start();  // セッションの開始
if(isset($_SESSION['error1'])){
    $error1 = $_SESSION['error1'];
    unset($_SESSION['error1']);
}
if(isset($_POST['register'])){  // 登録ボタンが押された場合
    $name = $_POST['last_name'].' '.$_POST['first_name'];  // 入力内容の受け取り
    $email = $_POST['email'];
    $password = $_POST['password'];
    if($email === ''){  // 未入力項目の確認
        $error1 = '!Eメールアドレスを入力して下さい!';
    }
    if($password === ''){
        $error2 = '！パスワードを入力して下さい！';
    }
    if($_POST['last_name']==='' or $_POST['first_name']===''){
        $error3 = '！氏名を入力して下さい！';
    }
    if(isset($_SESSION['error1'])){
        $error1 = $_SESSION['error1'];
        unset($_SESSION['error1']);
    }
    if(!isset($error1)&&!isset($error2)&&!isset($error3)){
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        echo "<h1>成功！</h1>";
        header('Location: register.php');
        exit;
    }
}
?>

<!doctype html>
<html lang='ja'>

<head>
<title>login_page</title>
</head>

<body>

<h1>新規登録</h1>  <!-- 登録フォーム -->
<form action='' method='post'>
<label>Eメールアドレス(ログインIDとして使用します)<br><input type='text' name='email' placeholder='example@mail.jp'></label>
<?php if(isset($error1)){echo $error1;}?><br>
<label>パスワード<br><input type='text' name='password' placeholder='半角20字以内'></label>
<?php if(isset($error2)){echo $error2;}?><br>
<label>氏名<br><input type='text' name='last_name' placeholder='赤坂'> <input type='text' name='first_name' placeholder='太郎'></label>
<?php if(isset($error3)){echo $error3;}?><br>
<input type='submit' name='register' value='登録'><br>
</form><br><br>

</body>
</html>