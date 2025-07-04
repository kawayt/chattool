<?php
session_start();
require('backends/dbconnect.php');

// フォームが送信されたか確認
if (!empty($_POST)) {
  // 名前の空欄チェック
  if ($_POST['name'] == "") {
    $error['name'] = 'blank';
  }

  // メールアドレスの空欄チェック
  if ($_POST['email'] == "") {
    $error['email'] = 'blank';
  } else {
    // メールアドレスのバリデーション
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $error['email'] = 'out_of_format';
    } else {
      // メールアドレスの重複チェック
      $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email = ?');
      $member->execute(array($_POST['email']));
      $record = $member->fetch();
      if ($record['cnt'] > 0) {
        $error['email'] = 'duplicate';
      }
    }
  }

  // パスワードの空欄チェック
  if ($_POST['password'] == "") {
    $error['password'] = 'blank';
  }
  if ($_POST['password2'] == "") {
    $error['password2'] = 'blank';
  }

  // パスワードは半角英数字で8桁以上16桁以下
  if (!empty($_POST['password']) && !preg_match("/\A\w{8,16}\z/", $_POST['password'])) {
    $error['password'] = 'format';
  }

  // 再入力したパスワードが一致するか
  if ($_POST['password'] !== $_POST['password2'] && $_POST['password2'] !== "") {
    $error['password2'] = 'difference';
  }

  // エラーがなければ確認ページへ
  if (empty($error)) {
    $_SESSION['join'] = $_POST; // 入力情報をセッションに保存
    header('Location: confirm.php');
    exit();
  }
}

// 書き直し（戻る）機能対応：セッションのデータをフォームに反映
if (isset($_SESSION['join']) && isset($_GET['action']) && $_GET['action'] == 'rewrite') {
  $_POST = $_SESSION['join'];
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require 'components/head.php'; ?>
  <title>アカウントを作成</title>
  <link rel="stylesheet" href="styles/login.css">
  <link rel="stylesheet" href="styles/form.css">
</head>

<body>
  <div class="container">
    <div class="container flex">
      <div id="artwork" class="flex">
        <img id="logo-icon" src="icons/fluent-color--people-chat-48.svg">
      </div>
      <div id="login" class="flex">
        <div>
          <h1>アカウントを作成</h1>

          <form action="" method="post" class="signup-form">

            <!-- メールアドレス -->
            <label>
              <span class="label-text">メールアドレス<span class="red">*</span></span>
              <input type="text" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? "", ENT_QUOTES); ?>" class="<?php (!empty($error['email'])) ? print 'input-error' : print ''; ?>">
              <?php if (!empty($error['email']) && $error['email'] === 'blank'): ?>
                <p class="error">メールアドレスを入力してください。</p>
              <?php endif; ?>
              <?php if (!empty($error['email']) && $error['email'] === 'out_of_format'): ?>
                <p class="error">不正な形式のメールアドレスです。</p>
              <?php endif; ?>
              <?php if (!empty($error['email']) && $error['email'] === 'duplicate'): ?>
                <p class="error">既に使用されているメールアドレスです。</p>
              <?php endif; ?>
            </label>

            <!-- パスワード -->
            <label>
              <span class="label-text">パスワード<span class="red">*</span></span>
              <input type="password" name="password" id="password" class="<?php (!empty($error['password'])) ? print 'input-error' : print ''; ?>">
              <?php if (!empty($error['password']) && $error['password'] === 'blank'): ?>
                <p class="error">パスワードを入力してください。</p>
              <?php endif; ?>
              <?php if (!empty($error['password']) && $error['password'] === 'format'): ?>
                <p class="error">パスワードは半角英数字6文字以上、16文字以下で入力してください。</p>
              <?php endif; ?>
            </label>

            <!-- パスワード（再入力） -->
            <label>
              <span class="label-text">パスワード（再入力）<span class="red">*</span></span>
              <input type="password" name="password2" id="password2" class="<?php (!empty($error['password2'])) ? print 'input-error' : print ''; ?>">
              <?php if (!empty($error['password2']) && $error['password2'] === 'blank'): ?>
                <p class="error">もう一度パスワードを入力してください。</p>
              <?php endif; ?>
              <?php if (!empty($error['password2']) && $error['password2'] === 'difference'): ?>
                <p class="error">パスワードが一致していません。</p>
              <?php endif; ?>
            </label>

            <!-- 名前 -->
            <label>
              <span class="label-text">名前<span class="red">*</span></span>
              <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? "", ENT_QUOTES); ?>" class="<?php (!empty($error['name'])) ? print 'input-error' : print ''; ?>">
              <?php if (!empty($error['name']) && $error['name'] === 'blank'): ?>
                <p class="error">名前を入力してください。</p>
              <?php endif; ?>
            </label>

            <div class="btn flex">
              <input type="submit" value="作成する" class="button">
              <a href="login.php" class="text-link">ログインページに戻る</a>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</body>

</html>