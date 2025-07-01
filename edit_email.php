<?php
session_start();
require('backends/dbconnect.php');
require('backends/login_check.php');

// フォームが送信されたか確認
if (!empty($_POST)) {
  $email = htmlspecialchars($_POST['email'], ENT_QUOTES);

  // メールアドレスの空欄チェック
  if ($email == "") {
    $error['email'] = 'blank';
  } else {
    // メールアドレスのバリデーション
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $error['email'] = 'out_of_format';
    } else {
      // メールアドレスの重複チェック
      $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email = ?');
      $member->execute(array($email));
      $record = $member->fetch();
      if ($record['cnt'] > 0) {
        $error['email'] = 'duplicate';
      }
    }
  }

  // エラーがなければデータベースを更新
  if (empty($error)) {
    $sql = $db->prepare('UPDATE members
      SET email = :email WHERE id = :id');
    $sql->execute([
      ':email' => $email,
      ':id' => $_SESSION['id']
    ]);

    $_SESSION['email'] = $email;
    $_SESSION['toast'] = 'update'; // トースト用フラグ
    unset($email);
  }
}

$current_email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require 'components/head.php'; ?>
  <title>メールアドレスを変更</title>
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
        <h1 id="page-name">メールアドレスを変更</h1>
      </div>
      <div class="scrollable">
        <form action="" method="post" class="main-form">

          <!-- メールアドレス -->
          <label>
            <span class="label-text">現在のメールアドレス</span>
            <span><?= $current_email; ?></span>

            <span class="label-text">新しいメールアドレス<span class="red">*</span></span>
            <input type="text" name="email" value="<?= $email ?? ""; ?>" class="<?php (!empty($error['email'])) ? print 'input-error' : print ''; ?>">
            <?php if (!empty($error['email'])): ?>
              <?php if ($error['email'] === 'blank'): ?>
                <p class="error">メールアドレスを入力してください。</p>
              <?php elseif ($error['email'] === 'out_of_format'): ?>
                <p class="error">不正な形式のメールアドレスです。</p>
              <?php elseif ($error['email'] === 'duplicate'): ?>
                <p class="error">既に使用されているメールアドレスです。</p>
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