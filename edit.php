<?php
require 'db.php';

$today = date('Y-m-d');
$max_date = date('Y-m-d', strtotime('+3 months'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 更新処理
    $id = $_POST['id'];
    $content = $_POST['content'];
    $deliver_date = $_POST['deliver_date'];

    if ($content === '' || $deliver_date === '') {
        exit('未入力項目があります');
    }

    if ($deliver_date < $today || $deliver_date > $max_date) {
        exit('日付は本日から3ヶ月以内で指定してください');
    }

    $stmt = $pdo->prepare("UPDATE future_memos SET content = ?, deliver_date = ? WHERE id = ?");
    $stmt->execute([$content, $deliver_date, $id]);

    header('Location: index.php');
    exit;
} else {
    // 編集フォーム表示
    $id = $_GET['id'] ?? '';
    if ($id === '') exit('IDが指定されていません');

    $stmt = $pdo->prepare("SELECT * FROM future_memos WHERE id = ?");
    $stmt->execute([$id]);
    $memo = $stmt->fetch();
    if (!$memo) exit('メモが存在しません');
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>メモを編集</title>
</head>
<body>
  <h1>メモを編集</h1>
  <form method="post">
    <input type="hidden" name="id" value="<?= $memo['id'] ?>">
    <textarea name="content" rows="4" required><?= htmlspecialchars($memo['content']) ?></textarea><br>
    <input type="date" name="deliver_date" value="<?= $memo['deliver_date'] ?>" min="<?= $today ?>" max="<?= $max_date ?>" required><br>
    <button type="submit">更新する</button>
  </form>
  <p><a href="index.php">← 戻る</a></p>
</body>
</html>
