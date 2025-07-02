<?php
session_start();
require('backends/dbconnect.php');
require('backends/login_check.php');

// フォームが送信されたか確認
if (!empty($_POST)) {
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

  // トグルスイッチがオンなら1、オフなら0
  $adminonly = isset($_POST['adminonly']) ? 1 : 0;

  // エラーがなければデータベースに保存
  if (empty($error)) {
    $stmt = $db->prepare('INSERT INTO channels SET name=?, description=?, admin_only=?');
    $stmt->execute(array(
      $_POST['chname'],
      $_POST['chdesc'],
      $adminonly,
    ));

    $id = $db->lastInsertId(); // チャンネルIDを取得

    $_SESSION['toast'] = 'chadd'; // トースト用フラグ
    header('Location: main.php?channel=' . $id);
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require 'components/head.php'; ?>
  <title>チャンネルを作成</title>
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
        <h1 id="page-name" class="ellipsis-one-line">チャンネルを作成</h1>
      </div>
      <div class="scrollable">
        <form action="" method="post" class="main-form">

          <!-- チャンネル名 -->
          <label>
            <span class="label-text">チャンネル名<span class="red">*</span></span>
            <input type="text" name="chname" value="<?php echo htmlspecialchars($_POST['chname'] ?? "", ENT_QUOTES); ?>" class="<?php (!empty($error['chname'])) ? print 'input-error' : print ''; ?>">
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
            <textarea name="chdesc" rows="5" class="<?php (!empty($error['chdesc'])) ? print 'input-error' : print ''; ?>"><?= htmlspecialchars($_POST['chdesc'] ?? "", ENT_QUOTES); ?></textarea>
            <?php if (!empty($error['chdesc']) && $error['chdesc'] === 'length'): ?>
              <p class="error">説明は100文字以内で入力してください。</p>
            <?php endif; ?>
          </label>

          <!-- 管理者のみアクセス可能 -->
          <span class="label-text">管理者のみアクセス可能にする</span>
          <label class="toggleButton">
            <input type="checkbox" name="adminonly" value="1" class="toggleButton__checkbox" />
          </label>

          <div class="btn flex">
            <input type="submit" value="作成する" class="button">
          </div>

        </form>
      </div>
    </div>
  </div>
</body>