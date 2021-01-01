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
$datas = '';
$sql = 'SELECT * FROM Chat WHERE id > :id';
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(':id', $_SESSION['number'], PDO::PARAM_INT);
$stmt -> execute();
$result = $stmt -> fetchAll();
if($result != null){
    foreach($result as $row){
        $date = explode(':', $row['time']);
        $time = strval(intval($date[3])).':'.$date[4];
        $day = $date[0].'年'.strval(intval($date[1])).'月'.strval(intval($date[2])).'日';
        if($day!==$_SESSION['date']){
            $datas .= "<div class='dbox'><p class='date'>".$day.'</p></div>';
            $_SESSION['date']=$day;
        }
        if($_SESSION['name']!==$row['name']){
            $datas .= "<div class='box'><p class='name'>".$row['name']."</p><div class='flex'><div class='others'><p class='comment'>".$row['comment']."</p></div><div class='time'>".$time.'</div></div></div>';
        }else{
            $datas .= "<div class='mybox'><div class='mytime'>".$time."</div><div class='myself'><p class='comment'>".$row['comment'].'</p></div></div>';
        }
        $_SESSION['number'] = intval($row['id']);
    }
    echo $datas;
    exit;
}
$pdo = null;
?>