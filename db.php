<?php
              if ($_SERVER['HTTP_HOST'] === 'localhost') {
     // ローカルMAMP
   $db_name = 'future_memo';
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'root';
}
//$db_name = 'ooko_future-memo';              // データベース名（＝ユーザー名）
//$db_host = '';      // DBホスト
//$db_user = '';             // ユーザー名（DB名と同じ）
//$db_pass = '';        // パスワード

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";

try {
    $pdo = new PDO($dsn, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('DB接続エラー：' . $e->getMessage());
}
?>
