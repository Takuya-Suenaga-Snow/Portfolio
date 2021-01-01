<?php
session_start();
$dsn = '******';
$username = '******';
$password = '******';
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

try{
    $pdo = new PDO($dsn, $username, $password, $options);
// 接続失敗
}catch(PDOException $e){
    exit;
}

// ユーザ登録用テーブルの作成
$sql = 'CREATE TABLE IF NOT EXISTS UserData(
        id INT AUTO_INCREMENT PRIMARY KEY,
        name char(20),
        email TEXT,
        password char(20),
        security_key char(16)
        )ENGINE = InnoDB default charset = utf8mb4';
$stmt = $pdo -> query($sql);
// チャット履歴用テーブルの作成
$sql = 'CREATE TABLE IF NOT EXISTS Chat(
        id INT AUTO_INCREMENT PRIMARY KEY,
        name char(20),
        comment TEXT,
        time char(20)
        )ENGINE = InnoDB default charset = utf8mb4';
$stmt = $pdo -> query($sql);
// データの参照
if(isset($_POST['data_check'])){
    $table = $_POST['table'];
    $sql ='SHOW TABLES';
    $result = $pdo -> query($sql);
    $error2 = '！テーブル名を入力して下さい！';
    foreach ($result as $row){
        if($row[0]===$table){
            $error2 = '';
            $sql ='SHOW CREATE TABLE '.$table;
            $result = $pdo -> query($sql);
            foreach ($result as $row){
                $list = explode('PRIMARY', $row[1]);
                $num = substr_count($list[0], '`');
                $num = ($num-2)/2;
                $item = explode('`', $list[0]);
                for($i=0; $i<$num; $i++){
                    if($i===0){
                        $columns[$i] = $item[3];
                        $str = 'select '.$item[3];
                        $str2 = "\t<tr><th>".$item[3].'</th>';
                    }else{
                        $columns[$i] = $item[2*$i+3];
                        $str = $str.', '.$columns[$i];
                        $str2 = $str2.'<th>'.$columns[$i].'</th>';
                    }
                }
            }
            $sql = $str.' from '.$table;
            $stmt = $pdo->query($sql);
            $str2 = $str2."</tr>\n";
            $display = "<table border='1' style='border-collapse:collapse'>\n";
            $display .= $str2;
            while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                $display .= "\t<tr>\n";
                foreach($columns as $column){
                    $display .= "\t\t<td align='center'>{$result[$column]}</td>\n";
                }
                $display .= "\t</tr>\n";
            }
            $display .= "</table>\n";
        }
    }
    
}
$pdo = null;
?>

<!doctype html>
<html lang='ja'>

<head>
<title>管理者ページ</title>
</head>

<body>
<h1>管理者ページ</h1><br>

<form action='' method='post'>
<input type='text' name='table' placeholder='テーブル名'>
<?php if(isset($error2)){echo $error2;}?><br>
<input type='submit' name='data_check' value='データの参照'>
</form><br><br>

<form action='' method='post'>
<input type='submit' name='logout' value='ログアウト'>
</form><br><hr>

<?php
if(isset($display)){
    echo $display;
}
?>

</body>
</html>