<?php
session_start();  // セッションの開始
if(!isset($_SESSION['dbname'])){  // ログイン状態の確認
    header('Location: Database_Editer.php');  // ログインページに移動
    exit;
}else{
    if(isset($_SESSION['password'])){  // セッションの確認
        $dbname = $_SESSION['dbname'];
        $password = $_SESSION['password'];
    }
}
if(isset($_POST['logout'])){
    header('Location: Database_Checker.php');  // ログインページに移動
    $_SESSION = array();
    session_destroy();
    exit;
}
?>

<!doctype html>
<html lang='ja'>

<head>
<title>login_page</title>
</head>

<body>
<?php
try{
    // DB接続設定
    $dsn = 'mysql:dbname='.$dbname.'db;host=localhost';
    $pdo = new PDO($dsn, $dbname, $password);

    // テーブルの参照
    if(isset($_POST['table_check'])){
        $display = '<テーブル一覧><br>';
        $sql ='SHOW TABLES';
        $result = $pdo -> query($sql);
        foreach ($result as $row){
            $display .= $row[0].'<br>';
        }
    }

    // テーブルの削除
    if(isset($_POST['delete'])){
        $table = $_POST['table'];
        $sql ='SHOW TABLES';
        $result = $pdo -> query($sql);
        $error1 = '！テーブル名を入力して下さい！';
        foreach ($result as $row){
            if($row[0]==$table){
                $sql = 'DROP TABLE '.$table;
                $stmt = $pdo->query($sql);
                $error1 = '';
            }
        }
    }

    // データの参照
    if(isset($_POST['data_check'])){
        $table = $_POST['table'];
        $sql ='SHOW TABLES';
        $result = $pdo -> query($sql);
        $error2 = '！テーブル名を入力して下さい！';
        foreach ($result as $row){
            if($row[0]==$table){
                $error2 = '';
                $sql ='SHOW CREATE TABLE '.$table;
                $result = $pdo -> query($sql);
                foreach ($result as $row){
                    $list = explode('PRIMARY', $row[1]);
                    $num = substr_count($list[0], '`');
                    $num = ($num-2)/2;
                    $item = explode('`', $list[0]);
                    for($i=0; $i<$num; $i++){
                        if($i==0){
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

// 接続失敗
}catch(PDOException $e){
    exit;
}
$pdo = null;
?>

<h1><?php echo $dbname; ?></h1><br>

<form action='' method='post'>
<input type='submit' name='table_check' value='テーブルの参照'>
</form><br><br>

<form action='' method='post'>
<input type='text' name='table' placeholder='テーブル名'>
<?php if(isset($error1)){echo $error1;}?><br>
<input type='submit' name='delete' value='テーブルの削除'>
</form><br><br>

<form action='' method='post'>
<input type='text' name='table' placeholder='テーブル名'>
<?php if(isset($error2)){echo $error2;}?><br>
<input type='submit' name='data_check' value='データの参照'>
</form><br><br>

<form action='' method='post'>
<input type='submit' name='logout' value='接続終了'>
</form><br><hr>

<?php
if(isset($display)){
    echo $display;
}
?>

</body>
</html>