<?php
session_start();
require('backends/dbconnect.php');
require('backends/login_check.php');

// フォームが送信されたか確認
if (!empty($_POST)) {
  $name = htmlspecialchars($_POST['chname'], ENT_QUOTES);
  $desc = htmlspecialchars($_POST['chdesc'], ENT_QUOTES);
  $adminonly = isset($_POST['adminonly']) ? 1 : 0;
  $id = $_POST['chid'];

  // チャンネル名は必須項目
  if ($_POST['chname'] == "") {
    $error['chname'] = 'blank';
  } else {
    // チャンネル名は30文字まで
    if (!empty($_POST['chname']) && strlen($_POST['chname']) >= 30) {
      $error['chname'] = 'length';
    }
  }

  // チャンネルの説明は100文字まで
  if (!empty($_POST['chdesc']) && strlen($_POST['chdesc']) >= 100) {
    $error['chdesc'] = 'length';
  }

  // エラーがなければデータベースに保存
  if (empty($error)) {
    $sql = $db->prepare('UPDATE channels
      SET name = :name, description = :description, admin_only = :admin_only WHERE id = :id');
    $sql->execute([
      ':name' => $name,
      ':description' => $desc,
      ':admin_only' => $adminonly,
      ':id' => $id
    ]);

    $_SESSION['toast'] = 'update'; // トースト用フラグ
    header('Location: main.php?channel=' . $id);
    exit();
  }
} else if (!empty($_GET)) {
  $chs = $db->prepare('SELECT * FROM channels WHERE id=?');
  $chs->execute(array($_GET['channel']));
  $ch = $chs->fetch();

  $name = $ch['name'];
  $desc = $ch['description'];
  $adminonly = $ch['admin_only'] === 1 ? 1 : 0;
  $id = $ch['id'];
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require 'components/head.php'; ?>
  <title>チャンネルを編集: <?= $name; ?></title>
  <link rel="stylesheet" href="styles/main.css">
  <link rel="stylesheet" href="styles/form.css">
</head>

<body>
  <div class="container flex">
    <div id="sidebar">
      <?php require 'components/sidebar.php' ?>
    </div>
    <div id="content">
      <div id="titlebar">
        <h1 id="page-name" class="ellipsis-one-line">チャンネルを編集: <?= $name; ?></h1>
      </div>
      <div class="scrollable">
        <form action="" method="post" class="main-form">

          <!-- チャンネル名 -->
          <label>
            <span class="label-text">チャンネル名<span class="red">*</span></span>
            <input type="text" name="chname" value="<?= $name; ?>" class="<?php (!empty($error['chname'])) ? print 'input-error' : print ''; ?>">
            <?php if (!empty($error['chname'])): ?>
              <?php if ($error['chname'] === 'blank'): ?>
                <p class="error">チャンネル名を入力してください。</p>
              <?php elseif ($error['chname'] === 'length'): ?>
                <p class="error">チャンネル名は30文字以内で入力してください。</p>
              <?php endif; ?>
            <?php endif; ?>
          </label>

          <!-- 説明 -->
          <label>
            <span class="label-text">説明</span>
            <textarea name="chdesc" rows="5" class="<?php (!empty($error['chdesc'])) ? print 'input-error' : print ''; ?>"><?= $desc; ?></textarea>
            <?php if (!empty($error['chdesc']) && $error['chdesc'] === 'length'): ?>
              <p class="error">説明は100文字以内で入力してください。</p>
            <?php endif; ?>
          </label>

          <!-- 管理者のみアクセス可能 -->
          <span class="label-text">管理者のみアクセス可能にする</span>
          <label class="toggleButton">
            <input type="checkbox" name="adminonly" value="1" class="toggleButton__checkbox" <?php $adminonly === 1 ? print 'checked' : print ''; ?> />
          </label>

          <input type="hidden" name="chid" value="<?= $id ?>">

          <div class="btn flex">
            <input type="submit" value="変更する" class="button">
          </div>

          <br />
          <p class="head-border">高度な操作</p>
          <br />
          <button type="button" id="openButton" class="button delete-btn">チャンネルを削除</button>
        </form>
      </div>

      <!-- 削除確認用モーダル -->
      <dialog id="modalDialog" class="dialog">
        <div id="dialogInputArea">
          <div id="dialog-container">
            <header>
              <h3 id="dialog-heading">“<?= $name; ?>” チャンネルを削除しますか？</h3>
            </header>
            <div>
              <p id="dialog-caption">このチャンネルに送信されたすべてのメッセージも削除されます。</p>
            </div>
            <form action="backends/delete_channel.php" method="post">
              <input type="hidden" name="chid" value="<?= $id ?>">
              <div id="dialog-select">
                <button type="button" id="closeButton" class="text-link">
                  <p>キャンセル</p>
                </button>
                <button type="submit" class="button delete-btn">チャンネルを削除</button>
              </div>
            </form>
          </div>
        </div>
      </dialog>

    </div>
  </div>

  <script src="scripts/modal.js"></script>
</body>