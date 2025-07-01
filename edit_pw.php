<?php
session_start();
require('backends/dbconnect.php');
require('backends/login_check.php');

// フォームが送信されたか確認
if (!empty($_POST)) {
  $old_pw = $_POST['old_password'];
  $new_pw = $_POST['new_password'];
  $new_pw2 = $_POST['new_password2'];

  // 現在のパスワードの空欄チェック
  if ($old_pw == "") {
    $error['old_password'] = 'blank';
  } else {
    // DB検索
    $login = $db->prepare('SELECT password FROM members WHERE id=?');
    $login->execute(array($_SESSION['id']));
    $member = $login->fetch();

    // ユーザーが存在しないか、パスワードが一致しない場合はエラー
    if ($member == false || !password_verify($old_pw, $member['password'])) {
      $error['old_password'] = 'incorrect';
    }
  }

  // 新しいパスワードの空欄チェック
  if ($new_pw == "") {
    $error['new_password'] = 'blank';
  }
  if ($new_pw2 == "") {
    $error['new_password2'] = 'blank';
  }

  // パスワードは8文字以上
  if (!empty($new_pw) && strlen($new_pw) < 8) {
    $error['new_password'] = 'length';
  }

  // 再入力したパスワードが一致するか
  if ($new_pw !== $new_pw2 && $new_pw2 !== "") {
    $error['new_password2'] = 'difference';
  }

  // エラーがなければデータベースを更新
  if (empty($error)) {
    $hash = password_hash($new_pw, PASSWORD_BCRYPT);

    $sql = $db->prepare('UPDATE members
      SET password = :password WHERE id = :id');
    $sql->execute([
      ':password' => $hash,
      ':id' => $_SESSION['id']
    ]);

    unset($old_pw, $new_pw, $new_pw2);
    $_SESSION['toast'] = 'update'; // トースト用フラグ
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require 'components/head.php'; ?>
  <title>パスワードを変更</title>
  <link rel="stylesheet" href="styles/main.css">
  <link rel="stylesheet" href="styles/form.css">
</head>

<body>
  <?php require 'components/toast.php'; ?>
  <div class="container flex">
    <div id="sidebar">
      <?php require 'components/sidebar.php' ?>
    </div>
    <div id="content">
      <div id="titlebar">
        <h1 id="page-name">パスワードを変更</h1>
      </div>
      <div class="scrollable">
        <form action="" method="post" class="main-form">

          <!-- 現在のパスワード -->
          <label>
            <span class="label-text">現在のパスワード<span class="red">*</span></span>
            <input type="password" name="old_password" value="<?= $old_pw ?? ""; ?>" id="old-password" class="<?php (!empty($error['old_password'])) ? print 'input-error' : print ''; ?>">
            <?php if (!empty($error['old_password'])): ?>
              <?php if ($error['old_password'] === 'blank'): ?>
                <p class="error">現在のパスワードを入力してください。</p>
              <?php elseif ($error['old_password'] === 'incorrect'): ?>
                <p class="error">パスワードが間違っています。</p>
              <?php endif; ?>
            <?php endif; ?>
          </label>

          <!-- 新しいパスワード -->
          <label>
            <span class="label-text">新しいパスワード<span class="red">*</span></span>
            <input type="password" name="new_password" value="<?= $new_pw ?? ""; ?>" id="new-password" class="<?php (!empty($error['new_password'])) ? print 'input-error' : print ''; ?>">
            <?php if (!empty($error['new_password'])): ?>
              <?php if ($error['new_password'] === 'blank'): ?>
                <p class="error">新しいパスワードを入力してください。</p>
              <?php elseif ($error['new_password'] === 'length'): ?>
                <p class="error">パスワードは8文字以上で入力してください。</p>
              <?php endif; ?>
            <?php endif; ?>
          </label>

          <!-- パスワード（再入力） -->
          <label>
            <span class="label-text">新しいパスワード（再入力）<span class="red">*</span></span>
            <input type="password" name="new_password2" value="<?= $new_pw2 ?? ""; ?>" id="new-password2" class="<?php (!empty($error['new_password2'])) ? print 'input-error' : print ''; ?>">
            <?php if (!empty($error['new_password2'])): ?>
              <?php if ($error['new_password2'] === 'blank'): ?>
                <p class="error">もう一度新しいパスワードを入力してください。</p>
              <?php elseif ($error['new_password2'] === 'difference'): ?>
                <p class="error">パスワードが一致していません。</p>
              <?php endif; ?>
            <?php endif; ?>
          </label>

          <div class="btn flex">
            <input type="submit" value="変更する" class="button">
          </div>

        </form>
      </div>

    </div>
  </div>
</body>