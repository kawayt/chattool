<?php
session_start();
require('dbconnect.php');
require('login_check.php');

if (!empty($_POST)) {
  // チャンネル削除
  $stmt_ch = $db->prepare("DELETE FROM channels WHERE id=?");
  $stmt_ch->execute([$_POST['chid']]);

  // メッセージ削除
  $stmt_ms = $db->prepare("DELETE FROM messages WHERE chid=?");
  $stmt_ms->execute([$_POST['chid']]);

  $_SESSION['toast'] = 'chdel'; // トースト用フラグ
}

header('Location: ../main.php');
exit();

?>