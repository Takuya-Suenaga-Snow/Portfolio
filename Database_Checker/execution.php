<?php
session_start();  // セッションの開始
if(!isset($_SESSION['username'])){  // ログイン状態の確認
    header('Location: Database_Checker.php');  // ログインページに移動
    exit;
}else{
    if(isset($_SESSION['password'])){  // セッションの確認
        $username = $_SESSION['username'];
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

<?php
try{  // DB接続設定
    $dsn = 'mysql:dbname='.$username.'db;host=localhost';
    $pdo = new PDO($dsn, $username, $password);
// 接続失敗
}catch(PDOException $e){
    exit;
}

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
    if($table===''){
        $error1 = '！テーブル名を入力して下さい！';
    }else{
        $error1 = '！テーブル名が不正です！';
        $sql ='SHOW TABLES';
        $result = $pdo -> query($sql);
        foreach ($result as $row){
            if($row[0]==$table){
                $sql = 'DROP TABLE '.$table;
                $stmt = $pdo->query($sql);
                $error1 = '';
            }
        }
    }
}

// データの参照
if(isset($_POST['data_check'])){
    $table = $_POST['table'];
    if($table===''){
        $error2 = '！テーブル名を入力して下さい！';
    }else{
        $error2 = '！テーブル名が不正です！';
        $sql ='SHOW TABLES';
        $result = $pdo -> query($sql);    
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
}

// データの削除
if(isset($_POST['data_delete'])){
    $table = $_POST['table'];
    $id = $_POST['id'];
    if($table===''){
        $error3 = '！テーブル名を入力して下さい！';
    }
    if($id===''){
        $error4 = '！idを入力して下さい！';
    }
    if(!isset($error3) && !isset($error3)){
        $error3 = '！テーブル名が不正です！';
        $error4 = '！idが不正です！';
        $sql ='SHOW TABLES';
        $result = $pdo -> query($sql);
        foreach ($result as $row){
            if($row[0]==$table){
                $error3 = '';
                $sql = 'SELECT * FROM '.$table;
	            $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
	            foreach($results as $row){
	            	if($id===$row['id']){
                        $error4 = '';
                        $sql = 'DELETE FROM '.$table.' WHERE id=:id';
	                    $stmt = $pdo->prepare($sql);
	                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                    $stmt->execute();
                    }
                }
            }
        }
    }
}

// データの編集
if(isset($_POST['data_edit'])){
    $table = $_POST['table'];
    if($table===''){
        $error5 = '！テーブル名を入力して下さい！';
    }else{
        $sql ='SHOW TABLES';
        $result = $pdo -> query($sql);
        $_SESSION['table'] = $table;    
        $error5 = '！テーブル名が不正です！';
        foreach ($result as $row){
            if($row[0]==$table){
                $error5 = '';
                $sql ='SHOW CREATE TABLE '.$table;
                $result = $pdo -> query($sql);
                foreach ($result as $row){
                    $list = explode('PRIMARY', $row[1]);
                    $num = substr_count($list[0], '`');
                    $p_keys = explode('`', $list[1]);
                    $p_key = $p_keys[1];
                    $num = ($num-2)/2;
                    $items = explode('`', $list[0]);
                    for($i=0; $i<$num; $i++){
                        $value = $items[2*$i+4];
                        $key = $items[2*$i+3];
                        $value = str_replace('(','',substr(trim($value), 0, 4));
                        if($p_key===$key){
                            $Key = array($key, $value);
                        }else{
                            $columns[$key] = $value;
                        }
                    }
                }
                $_SESSION['key'] = $Key;
                $_SESSION['columns'] = $columns;
                $form = "<form action='' method='post'>";
                if($Key[1]==='int'){
                    $form .= "<input type='number' name='".$Key[0]."' placeholder='".$Key[0]."'> ";
                }else{
                    $form .= "<input type='text' name='".$Key[0]."' placeholder='".$Key[0]."'> ";
                }
                foreach($columns as $key => $value){
                    if($value==='int'){
                        $form .= "<input type='number' name='".$key."' placeholder='".$key."'> ";
                    }else{
                        $form .= "<input type='text' name='".$key."' placeholder='".$key."'> ";
                    }
                }
                $form .= "<input type='submit' name='edit' value='変更'></form>";
            }
        }
    }
}

// データの編集2
if(isset($_POST['edit'])){
    $Keys = $_SESSION['key'];
    $Key = $Keys[0];
    $p_key = $_POST[$Key];
    if($p_key!==''){
        $table = $_SESSION['table'];
        $columns = $_SESSION['columns'];
        foreach($columns as $key => $value){
            if(!isset($str)){
                $str = ' SET '.$key.'=:'.$key;
            }else{
                $str .= ','.$key.'=:'.$key;
            }
        }
        $sql = 'UPDATE '.$table.$str.' WHERE '.$Key.'=:'.$Key;
        $stmt = $pdo->prepare($sql);
        $Param = ':'.$Key;
        if($Keys[1]==='int'){
            $stmt->bindParam($Param, $p_key, PDO::PARAM_INT);
        }else{
            $stmt->bindParam($Param, $p_key, PDO::PARAM_STR);
        }
        $i=0;
        foreach($columns as $key => $value){
            $param = ':'.$key;
            $item[$i] = $_POST[$key];
            if($value==='int'){
	            $stmt->bindParam($param, $item[$i], PDO::PARAM_INT);
            }else{
	            $stmt->bindParam($param, $item[$i], PDO::PARAM_STR);
            }
            $i += 1;
        }
        $stmt->execute();
    }
}

$pdo = null;
?>
<!doctype html>
<html lang='ja'>

<head>
<title>login_page</title>
<link rel='stylesheet' href='style.css'>
</head>

<body>

<div class='all'>
    <div id='box'>
        <div id='head'><?php echo $username; ?>のデータベース</div>
        <div class='form'>
            <form action='' method='post'>
            <input type='submit' name='table_check' value='テーブルの参照'>
            </form>
        </div>
        <div class='form'>
            <form action='' method='post'>
            <input type='submit' name='logout' value='ログアウト'>
            </form>
        </div>
    </div>
    <div id='display'>
        <?php
        if(isset($error1)){echo $error1.'<br>';}
        if(isset($error2)){echo $error2.'<br>';}
        if(isset($error3)){echo $error3.'<br>';}
        if(isset($error4)){echo $error4.'<br>';}
        if(isset($error5)){echo $error5.'<br>';}
        if(isset($display)){echo $display;}
        ?>
    </div>
    <div id='form'>
        <div class='form'>
            <form action='' method='post'>
            <input type='text' name='table' placeholder='テーブル名'><br>
            <input type='submit' name='delete' value='テーブルの削除'>
            </form>
        </div>
        <div class='form'>
            <form action='' method='post'>
            <input type='text' name='table' placeholder='テーブル名'><br>
            <input type='submit' name='data_check' value='データの参照'>
            </form>
        </div>
        <div class='form'>
            <form action='' method='post'>
            <input type='text' name='table' placeholder='テーブル名'><br>
            <input type='number' name='id' placeholder='id'><br>
            <input type='submit' name='data_delete' value='データの削除'>
            </form>
        </div>
        <div class='form'>
            <form action='' method='post'>
            <input type='text' name='table' placeholder='テーブル名'><br>
            <input type='submit' name='data_edit' value='データの編集'>
            </form>
        </div>
    </div>
    <div id='edit'>
        <?php
        if(isset($form)){
            echo $form;
        }
        ?>
    </div>
</div>

</body>
</html>