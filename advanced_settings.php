<?php
session_start();
require('backends/dbconnect.php');
require('backends/login_check.php');

// フォームが送信されたか確認
if (!empty($_POST)) {
  $admin = isset($_POST['admin']) ? 1 : 0;

  $sql = $db->prepare('UPDATE members
      SET admin = :admin WHERE id = :id');
  $sql->execute([
    ':admin' => $admin,
    ':id' => $_SESSION['id']
  ]);

  $_SESSION['toast'] = 'update'; // トースト用フラグ
  header('Location: advanced_settings.php');
  exit();
  
} else {
  $stmt = $db->prepare('SELECT * FROM members WHERE id=?');
  $stmt->execute(array($_SESSION['id']));
  $mb = $stmt->fetch();

  $admin = $mb['admin'] === 1 ? 1 : 0;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require 'components/head.php'; ?>
  <title>高度な設定</title>
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
        <h1 id="page-name">高度な設定</h1>
      </div>
      <div class="scrollable">
        <form action="" method="post" class="main-form">

          <!-- 管理者 -->
          <span class="label-text">管理者モード</span>
          <label class="toggleButton">
            <input type="checkbox" name="admin" value="1" class="toggleButton__checkbox" <?php $admin === 1 ? print 'checked' : print ''; ?> />
          </label>

          <div class="btn flex">
            <input type="submit" value="変更する" class="button">
          </div>

          <br />
          <p class="head-border">高度な操作</p>
          <br />
          <button type="button" id="openButton" class="button delete-btn">アカウントを削除</button>
        </form>
      </div>

      <!-- 削除確認用モーダル -->
      <dialog id="modalDialog" class="dialog">
        <div id="dialogInputArea">
          <div id="dialog-container">
            <header>
              <h3 id="dialog-heading">アカウントを削除しますか？</h3>
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
                <button type="submit" class="button delete-btn">アカウントを削除</button>
              </div>
            </form>
          </div>
        </div>
      </dialog>

    </div>
  </div>

  <script>
    const openButton = document.getElementById('openButton');
    const modalDialog = document.getElementById('modalDialog');

    // モーダルを開く
    openButton?.addEventListener('click', async () => {
      modalDialog.showModal();
      // モーダルダイアログを表示する際に背景部分がスクロールしないようにする
      document.documentElement.style.overflow = "hidden";
    });

    const closeButton = document.getElementById('closeButton');

    // モーダルを閉じる
    closeButton?.addEventListener('click', () => {
      modalDialog.close();
      // モーダルを解除すると、スクロール可能になる
      document.documentElement.removeAttribute("style");
    });

    // 背景をクリックしてモーダルを閉じる
    modalDialog.addEventListener('click', (event) => {
      if (event.target.closest('#dialogInputArea') === null) {
        modalDialog.close();
      }
    });
  </script>
</body>