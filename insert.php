<?php
require 'db.php';

$content = $_POST['content'];
$deliver_date = $_POST['deliver_date'];
$today = date('Y-m-d');
$max_date = date('Y-m-d', strtotime('+3 months'));

if ($content === '' || $deliver_date === '') {
    exit('未入力項目があります');
}

if ($deliver_date < $today || $deliver_date > $max_date) {
    exit('日付は本日から3ヶ月以内で指定してください');
}

$stmt = $pdo->prepare("INSERT INTO future_memos (content, deliver_date) VALUES (?, ?)");
$stmt->execute([$content, $deliver_date]);

header('Location: index.php');
exit;
?>
