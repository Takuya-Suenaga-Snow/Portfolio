<?php
session_start();  // セッションの開始
$_SESSION['number'] = 0;
$_SESSION['date'] = '';
if(!isset($_SESSION['name'])){  // ログイン状態の確認
    $_SESSION = array();
    session_destroy();
    header('Location: login_page.php');  // ログインページに移動
    exit;
}else{
    $name = $_SESSION['name'];
}
if(isset($_POST['logout'])){
    $_SESSION = array();
    session_destroy();
    header('Location: login_page.php');  // ログインページに移動
    exit;
}
?>

<!doctype html>
<html lang='ja'>

<head>
<title>Tech-Chat</title>
<script type='text/javascript' src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
<script type='text/javascript' src='data_send.js'></script>
<script type='text/javascript' src='data_recieve.js'></script>
<link rel='stylesheet' href='style.css'>
</head>

<body>

<div id='container'>
    <div id='header'>
        <div id='title'>Tech-Chat</div>
        <div id='username'>ユーザー名：<?php echo $name;?></div>
        <form action='' method='post' id='form'>
            <input type='submit' name='logout' value='ログアウト'>
        </form>
    </div>
    <div id='chat_area'>
        <div class='talk'>
        </div>
    </div>
    
    <div id='message_area'>
        <div id='text_area'>
            <textarea id='comment' placeholder='メッセージを入力'></textarea>
        </div>
        <div id='button_area'>
            <button id='submit'>送信</button>
        </div>
    </div>
</div>

</body>
</html>