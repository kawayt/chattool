<?php
session_start();
require('backends/dbconnect.php');

// セッションに登録情報がない場合、登録ページにリダイレクト
if (!isset($_SESSION['join'])) {
  header('Location: register.php');
  exit();
}

// フォームが送信されたかどうかチェック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // パスワードをハッシュ化
  $hash = password_hash($_SESSION['join']['password'], PASSWORD_BCRYPT);
  // データベースに登録
  $statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, created=NOW()');
  $statement->execute(array(
    $_SESSION['join']['name'],
    $_SESSION['join']['email'],
    $hash
  ));

  // ログイン処理
  $login = $db->prepare('SELECT * FROM members WHERE email=?');
  $login->execute(array($_SESSION['join']['email']));
  $member = $login->fetch();

  $_SESSION['id'] = $member['id'];
  $_SESSION['email'] = $member['email'];
  $_SESSION['name'] = $member['name'];
  $_SESSION['bio'] = $member['bio'];
  $_SESSION['admin'] = $member['admin'];
  $_SESSION['avatar'] = $member['avatar'];
  $_SESSION['time'] = time();

  unset($_SESSION['join']); // セッション削除
  $_SESSION['toast'] = 'signup'; // トースト用フラグ

  header('Location: main.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require 'components/head.php'; ?>
  <title>ユーザー登録確認画面</title>
  <link rel="stylesheet" href="styles/login.css">
  <link rel="stylesheet" href="styles/form.css">
  <style>
    .check {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <?php require 'components/toast.php'; ?>
  <div class="container flex">
    <div id="artwork" class="flex">
      <img id="logo-icon" src="icons/fluent-color--people-chat-48.svg">
    </div>
    <div id="login" class="flex">
      <div>
        <h1>アカウントを作成しますか？</h1>

        <form action="" method="post">
          <!-- hiddenフィールドを使って POST データであることを明示 -->
          <input type="hidden" name="action" value="submit">

          <p>
            メールアドレス：
            <span class="check"><?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?></span>
          </p>
          <p>
            パスワード：
            <span class="check">[非表示]</span>
          </p>
          <p>
            名前：
            <span class="check"><?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?></span>
          </p>
          <br />

          <div class="button-container">
            <!-- 「修正する」ボタン：register.php に戻る -->
            <input
              type="button"
              onclick="event.preventDefault(); location.href='register.php?action=rewrite'"
              value="修正する"
              name="rewrite">

            <!-- 「登録する」ボタン：POSTでこのconfirm.phpに送信され、DB登録 -->
            <input type="submit" value="作成する" name="registration">
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>