<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

// フォームデータの受け取り
$content = $_POST['content'] ?? '';
$future_date = $_POST['future_date'] ?? '';

// 日付オブジェクトの生成
$today = new DateTime();
$max_date = (clone $today)->modify('+3 months');
$input_date = DateTime::createFromFormat('Y-m-d', $future_date);

// 日付を時刻なしで比較（00:00:00 に揃える）
$today->setTime(0, 0, 0);
$max_date->setTime(0, 0, 0);
if ($input_date) {
    $input_date->setTime(0, 0, 0);
}

// バリデーション
if (trim($content) === '' || !$input_date) {
    exit('未入力項目があります');
}

if ($input_date < $today || $input_date > $max_date) {
    exit('日付は本日から3ヶ月以内で指定してください');
}

// DB登録
try {
    $stmt = $pdo->prepare("INSERT INTO future_memos (content, future_date) VALUES (?, ?)");
    $stmt->execute([$content, $future_date]);
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    exit('DBエラー: ' . $e->getMessage());
}
