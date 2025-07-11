<?php
require 'db.php';

if (!isset($_POST['id'])) {
    exit('IDが指定されていません');
}

$id = $_POST['id'];

$stmt = $pdo->prepare("DELETE FROM future_memos WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
?>
