<?php

$plainPassword = "rinrin0616";

$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

echo "ハッシュ化されたパスワード：<br>";
echo "<textarea rows='3' cols='80' readonly>$hashedPassword</textarea>";
?>
