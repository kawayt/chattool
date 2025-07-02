<?php
session_start();

require('backends/dbconnect.php');
require('backends/login_check.php');

// チャンネルIDがGET送信されているか確認
if (!empty($_GET)) {
  // メッセージがPOST送信されているか確認
  if (!empty($_POST)) {
    $send_message = htmlspecialchars($_POST['send_message'], ENT_QUOTES);

    if ($send_message == "") {
      $error['message'] = 'blank';
    } else {
      // メッセージは500文字まで
      if (!empty($send_message) && strlen($send_message) >= 500) {
        $error['message'] = 'length';
      }
    }

    // エラーがなければデータベースに保存
    if (empty($error)) {
      $stmt1 = $db->prepare('INSERT INTO messages SET content=?, acid=?, chid=?, send_time=NOW()');
      $stmt1->execute(array(
        $send_message,
        $_SESSION['id'],
        $_GET['channel'],
      ));

      header('Location: main.php?channel=' . $_GET['channel']);
      exit();
    }
  }

  // GET送信されたIDのチャンネル情報を取得
  $stmt2 = $db->prepare('SELECT * FROM channels WHERE id=?');
  $stmt2->execute(array($_GET['channel']));
  $ch = $stmt2->fetch();

  // GET送信されたIDのチャンネルのメッセージを取得（JOINで送信者の名前も）
  $stmt3 = $db->prepare('SELECT * FROM messages INNER JOIN members ON messages.acid = members.id WHERE messages.chid=?');
  $stmt3->execute(array($_GET['channel']));
  $ms = $stmt3->fetchAll();

} else {
  // GET送信されていない場合、先頭のチャンネルIDを取得
  $stmt4 = $db->prepare('SELECT * FROM channels ORDER BY id LIMIT 1');
  $stmt4->execute();
  $firstch = $stmt4->fetch();

  if (!empty($firstch)) {
    // 先頭のチャンネルにリダイレクト
    header('Location: main.php?channel=' . $firstch['id']);
    exit();
  } else {
    // チャンネルが1つもない場合、作成ページにリダイレクト
    header('Location: create_ch.php');
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require 'components/head.php'; ?>
  <title>チャンネル: <?= $ch['name']; ?></title>
  <link rel="stylesheet" href="styles/main.css">
</head>

<body>
  <?php require 'components/toast.php'; ?>
  <div class="container flex">
    <div id="sidebar">
      <?php require 'components/sidebar.php' ?>
    </div>
    <div id="content">
      <!-- タイトルバー -->
      <div id="titlebar" class="flex gap-10">
        <h1 id="page-name" class="ellipsis-one-line"><?= $ch['name']; ?></h1>
        <p id="description" class="ellipsis-one-line"><?= $ch['description']; ?></p>
      </div>

      <!-- メッセージ表示 -->
      <div class="scrollable">
        <ul id="message-list">
          <?php foreach ($ms as $message): ?>
            <li class="message-wrapper <?php $message['id'] === $_SESSION['id'] ? print 'my' : print ''; ?>">
              <div class="flex">
                <div class="v1">
                  <?php if ($message['id'] !== $_SESSION['id']): ?>
                    <img src="uploads/<?php isset($message['avatar']) ? print $message['avatar'] : print 'default.svg' ?>" class="avatar">
                  <?php endif ?>
                </div>
                <div class="v2">
                  <?php if ($message['id'] !== $_SESSION['id']): ?>
                    <p class="message-name"><?= $message['name'] ?></p>
                  <?php endif ?>
                  <p class="message-content"><?= nl2br($message['content']); ?></p>
                  <p class="message-time"><?= $message['send_time'] ?></p>
                </div>
              </div>
              <?php if ($_SESSION['admin'] === 1 || $message['id'] === $_SESSION['id']): ?>
                <div class="message-action flex ai-center">
                  <a href="backends/delete_message.php?message=<?= $message['msid'] ?>&channel=<?= $ch['id'] ?>">
                    <img id="test-icon" src="icons/mingcute--delete-2-line.svg">
                  </a>
                </div>
              <?php endif ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- メッセージ送信 -->
      <div id="m1">
        <div id="m2">
          <form action="" method="post" id="new-message">
            <textarea spellcheck=false name="send_message" id="message-text" placeholder="メッセージを入力…"></textarea>

            <div id="toolbox" class="flex ai-center">
              <button>
                <img id="image-icon" src="icons/mage--image-plus.svg">
              </button>
              <button type="submit" id="send-btn" class="ml-auto">
                <img id="send-icon" src="icons/mingcute--arrow-up-circle-fill.svg">
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>

  <script>
    // 初期スクロール位置を最下部にする
    const target = document.getElementById('message-list');
    target.scrollIntoView(false);
  </script>
</body>

</html>