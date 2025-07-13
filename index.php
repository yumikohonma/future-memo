<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

$today = date('Y-m-d');
$max_date = date('Y-m-d', strtotime('+3 months')); // ← これが重要！

// 表示対象（今日までに届くもの）
$stmt1 = $pdo->prepare("SELECT * FROM future_memos WHERE future_date <= ? ORDER BY future_date DESC");
$stmt1->execute([$today]);
$memos_arrived = $stmt1->fetchAll();

// 非表示対象（未来に届くもの）
$stmt2 = $pdo->prepare("SELECT COUNT(*) FROM future_memos WHERE future_date > ?");
$stmt2->execute([$today]);
$future_count = $stmt2->fetchColumn();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>未来のわたしへ</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
  <style>
    body {
      background: radial-gradient(circle at center, #0f2027, #203a43, #2c5364);
      font-family: 'Orbitron', sans-serif;
      color: #ffffff;
      padding: 30px;
      max-width: 700px;
      margin: auto;
    }
    h1, h2 {
      text-align: center;
      color: #00e5ff;
    }
    form {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid #00e5ff;
      border-radius: 10px;
      padding: 30px;
      margin-bottom: 40px;
      box-shadow: 0 0 10px #00e5ff33;
    }
    textarea, input[type="date"] {
      width: 100%;
      margin-bottom: 10px;
      padding: 10px;
      background: #1a1a1a;
      border: 1px solid #00e5ff;
      color: white;
      border-radius: 5px;
    }
    button {
      width: 100%;
      padding: 10px;
      box-sizing: border-box;
      background-color: #00e5ff;
      color: black;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #00bcd4;
    }
    .memo {
      position: relative;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid #ffffff22;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 8px;
      box-shadow: 0 0 10px #00e5ff22;
    }
    .memo-buttons {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 10px;
    }
    .memo-buttons form {
      margin: 0;
    }
    .memo button {
      padding: 5px 12px;
      background-color: #00e5ff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      color: #000;
      font-size: 0.85em;
      font-family: 'Orbitron', sans-serif;
      width: auto;
    }
    .memo button:hover {
      background-color: #00bcd4;
    }
    .date {
      font-size: 0.9em;
      color: #aaa;
    }
  </style>
</head>
<body>

<h1>未来のわたしへ</h1>

<form action="insert.php" method="post">
  <textarea name="content" rows="4" placeholder="未来の自分にメッセージを…" required></textarea>
  <input type="date" name="future_date" min="<?= $today ?>" max="<?= $max_date ?>" required>
  <button type="submit">🚀 メッセージを届ける</button>
</form>

<h2>届いたメッセージ</h2>
<?php foreach ($memos_arrived as $memo): ?>
  <div class="memo">
    <div class="date">📅 <?= htmlspecialchars($memo['future_date']) ?></div>
    <div><?= nl2br(htmlspecialchars($memo['content'])) ?></div>

    <div class="memo-buttons">
      <!-- 編集ボタン -->
      <form action="edit.php" method="get">
        <input type="hidden" name="id" value="<?= $memo['id'] ?>">
        <button type="submit">✏ 編集</button>
      </form>

      <!-- 削除ボタン -->
      <form action="delete.php" method="post" onsubmit="return confirm('本当に削除しますか？');">
        <input type="hidden" name="id" value="<?= $memo['id'] ?>">
        <button type="submit">🗑 削除</button>
      </form>
    </div>
  </div>
<?php endforeach; ?>

<?php if ($future_count > 0): ?>
  <p>✉ <?= $future_count ?>件のメッセージが、これから届きます。</p>
<?php endif; ?>

</body>
</html>
