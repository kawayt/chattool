<?php
// ログインチェック
if (isset($_SESSION['id'])) {
  if ($_SESSION['time'] + 1800 > time()) {
    $_SESSION['time'] = time(); // 活動時間を更新
    // ログイン中のユーザー情報を取得
    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();

  } else {
    // セッションタイムアウト時
    $_SESSION['toast'] = 'timeout'; // トースト用フラグ
    header('Location: login.php');
    exit();
  }
  
} else {
  // 未ログイン時
  $_SESSION['toast'] = 'unauthorized'; // トースト用フラグ
  header('Location: login.php');
  exit();
}
?>