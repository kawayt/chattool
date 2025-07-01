<?php
session_start();
require('dbconnect.php');
require('login_check.php');

if (!empty($_POST) && $_POST['password'] != '') {
  // メールアドレスでDB検索
  $login = $db->prepare('SELECT * FROM members WHERE id=?');
  $login->execute(array($_SESSION['id']));
  $member = $login->fetch();

  // ユーザーが存在し、かつパスワードが一致すれば削除処理
  if ($member != false && password_verify($_POST['password'], $member['password'])) {
    // チャンネル削除
    $stmt = $db->prepare("DELETE FROM members WHERE id=?");
    $stmt->execute([$_SESSION['id']]);

    // メッセージ削除
    $stmt_ms = $db->prepare("DELETE FROM messages WHERE acid=?");
    $stmt_ms->execute([$_SESSION['id']]);

    $_SESSION['toast'] = 'acdel'; // トースト用フラグ
    header('Location: ../login.php');
    exit();

  } else {
    $_SESSION['toast'] = 'acdel-error'; // トースト用フラグ
    header('Location: ../advanced_settings.php');
    exit();
  }
  
} else {
  $_SESSION['toast'] = 'acdel-error'; // トースト用フラグ
  header('Location: ../advanced_settings.php');
  exit();
}
