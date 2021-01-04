<?php
session_start();  // セッションの開始
$_SESSION = array();
if(isset($_POST['connect'])){  // 登録ボタンが押された場合
    $dbname = $_POST['dbname'];
    $password = $_POST['password'];
    if($dbname == ''){
        $error1 = '!データベース名を入力して下さい!';
    }
    if($password == ''){
        $error2 = '！パスワードを入力して下さい！';
    }
    if(!isset($error1) && !isset($error2)){  // セッションの保存
        $_SESSION['dbname'] = $dbname;
        $_SESSION['password'] = $password;
    }
}
if(isset($_SESSION['password'])){  // 実行ページへ移動
    header('Location: execution.php');
    exit;
}
?>

<!doctype html>
<html lang='ja'>

<head>
<title>login_page</title>
</head>

<body>

<h1>データベース接続</h1>
<form action='' method='post'>
<label>データベース名<br><input type='text' name='dbname'></label>
<?php if(isset($error1)){echo $error1;}?><br>
<label>パスワード<br><input type='text' name='password'></label>
<?php if(isset($error2)){echo $error2;}?><br>
<input type='submit' name='connect' value='接続'><br>
</form><br><br>

</body>
</html>
