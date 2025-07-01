<?php
session_start();
require('backends/dbconnect.php');

if (!empty($_POST)) {
  // 空欄チェック
  if ($_POST['email'] != '' && $_POST['password'] != '') {
    // メールアドレスでDB検索
    $login = $db->prepare('SELECT * FROM members WHERE email=?');
    $login->execute(array($_POST['email']));
    $member = $login->fetch();

    // ユーザーが存在し、かつパスワードが一致すればログイン処理
    if ($member != false && password_verify($_POST['password'], $member['password'])) {
      $_SESSION['id'] = $member['id'];
      $_SESSION['email'] = $member['email'];
      $_SESSION['name'] = $member['name'];
      $_SESSION['bio'] = $member['bio'];
      $_SESSION['admin'] = $member['admin'];
      $_SESSION['time'] = time();

      $_SESSION['toast'] = 'login'; // トースト用フラグ
      
      header('Location: main.php');
      exit();
    } else {
      $error['login'] = 'failed';
    }
  } else {
    $error['login'] = 'blank';
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require 'components/head.php'; ?>
  <title>ログイン</title>
  <link rel="stylesheet" href="styles/login.css">
  <link rel="stylesheet" href="styles/form.css">
</head>

<body>
  <?php require 'components/toast.php'; ?>
  <div class="container flex">
    <div id="artwork" class="flex">
      <img id="logo-icon" src="icons/fluent-color--people-chat-48.svg">
    </div>
    <div id="login" class="flex">
      <div>
        <h1>ログイン</h1>

        <form action="" method="post">

          <label>
            <span class="label-text">メールアドレス</span>
            <input type="text" name="email"
              value="<?= htmlspecialchars($_POST['email'] ?? "", ENT_QUOTES); ?>">
          </label>
          <?php if (isset($error['login']) && $_POST['email'] == ''): ?>
            <p class="error">メールアドレスを入力してください。</p>
          <?php endif; ?>

          <label>
            <span class="label-text">パスワード</span>
            <input type="password" name="password">
          </label>
          <?php if (isset($error['login']) && $_POST['password'] == ''): ?>
            <p class="error">パスワードを入力してください。</p>
          <?php endif; ?>

          <?php if (isset($error['login']) && $error['login'] == 'failed'): ?>
            <p class="error">メールアドレスまたはパスワードが正しくありません。</p>
          <?php endif; ?>

          <br />

          <div class="btn flex">
            <input type="submit" value="ログインする" class="button">
            <a href="register.php" class="text-link">アカウントを作成</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>