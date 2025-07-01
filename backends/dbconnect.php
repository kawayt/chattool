<?php
// データベースに接続
try {
  $db = new PDO('mysql:dbname=2025chat;host=127.0.0.1; charset=utf8', 'root', '');
} catch (PDOException $e) {
  echo 'データベースに接続できませんでした。' . $e->getMessage();
}
