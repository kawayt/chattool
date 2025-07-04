<?php
session_start();
require('backends/dbconnect.php');
require('backends/login_check.php');

// フォームが送信されたか確認
if (!empty($_POST)) {
  $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
  $bio = htmlspecialchars($_POST['bio'], ENT_QUOTES);
  $avatar_flag = false;

  // 名前は必須項目
  if ($_POST['name'] == "") {
    $error['name'] = 'blank';
  } else {
    // 名前は30文字まで
    if (!empty($_POST['name']) && mb_strlen($_POST['name'], 'UTF-8') >= 30) {
      $error['name'] = 'length';
    }
  }

  // 自己紹介は100文字まで
  if (!empty($_POST['bio']) && mb_strlen($_POST['bio'], 'UTF-8') >= 100) {
    $error['bio'] = 'length';
  } else {
    // 空欄の場合は NULL に置換
    if ($_POST['bio'] == "") {
      $_POST['bio'] = NULL;
    }
  }

  // プロフィール画像
  $upload_dir = 'uploads/';
  $max_size = 3 * 1024 * 1024; // 3MB
  $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['image']['tmp_name'];
    $original_name = basename($_FILES['image']['name']);
    $file_size = $_FILES['image']['size'];

    // 拡張子の確認
    $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
      exit('許可されていないファイル形式です。');
    }

    // ファイルサイズ確認
    if ($file_size > $max_size) {
      exit('ファイルサイズが大きすぎます。最大2MBまでです。');
    }

    // MIMEタイプ確認
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $tmp_name);
    finfo_close($finfo);

    $valid_mime_types = [
      'image/jpeg',
      'image/png',
      'image/webp',
    ];
    if (!in_array($mime_type, $valid_mime_types)) {
      exit('不正な画像形式です。');
    }

    // ランダムファイル名生成
    $new_name = uniqid('img_', true) . '.' . $ext;
    $destination = $upload_dir . $new_name;

    // 移動
    if (move_uploaded_file($tmp_name, $destination)) {
      $avatar_flag = true;
    } else {
      exit('ファイルの保存に失敗しました');
    }
  }

  // エラーがなければデータベースを更新
  if (empty($error)) {
    // プロフィール画像の更新を含む
    if ($avatar_flag === true) {
      $sql = $db->prepare('UPDATE members
      SET name = :name, bio = :bio, avatar = :avatar WHERE id = :id');
      $sql->execute([
        ':name' => $_POST['name'],
        ':bio' => $_POST['bio'],
        ':avatar' => $new_name,
        ':id' => $_SESSION['id']
      ]);

      $_SESSION['avatar'] = $new_name;
    } else {
      $sql = $db->prepare('UPDATE members
      SET name = :name, bio = :bio WHERE id = :id');
      $sql->execute([
        ':name' => $_POST['name'],
        ':bio' => $_POST['bio'],
        ':id' => $_SESSION['id']
      ]);
    }

    $_SESSION['name'] = $_POST['name'];
    $_SESSION['bio'] = $_POST['bio'];
    $_SESSION['toast'] = 'update'; // トースト用フラグ
  }
} else {
  $name = $_SESSION['name'];
  $bio = $_SESSION['bio'];
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require 'components/head.php'; ?>
  <title>プロフィールを変更</title>
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
        <h1 id="page-name" class="ellipsis-one-line">プロフィールを変更</h1>
      </div>
      <div class="scrollable">
        <form action="" method="post" class="main-form" enctype="multipart/form-data">

          <!-- 名前 -->
          <label>
            <span class="label-text">名前<span class="red">*</span></span>
            <input type="text" name="name" value="<?= $name; ?>" class="<?php (!empty($error['name'])) ? print 'input-error' : print ''; ?>">
            <?php if (!empty($error['name'])): ?>
              <?php if ($error['name'] === 'blank'): ?>
                <p class="error">名前を入力してください。</p>
              <?php elseif ($error['name'] === 'length'): ?>
                <p class="error">名前は30文字以内で入力してください。</p>
              <?php endif; ?>
            <?php endif; ?>
          </label>

          <!-- 自己紹介 -->
          <label>
            <span class="label-text">自己紹介</span>
            <textarea name="bio" rows="5" class="<?php (!empty($error['bio'])) ? print 'input-error' : print ''; ?>"><?= $bio; ?></textarea>
            <?php if (!empty($error['bio']) && $error['bio'] === 'length'): ?>
              <p class="error">説明は100文字以内で入力してください。</p>
            <?php endif; ?>
          </label>

          <!-- プロフィール画像 -->
          <label>
            <span class="label-text">プロフィール画像</span>
            <div id="preview"></div>
            <input type="file" name="image" class="upload" data-target-id="preview" data-classes="preview-img" onchange="previewer.setImgPreview(event);">
          </label>

          <div class="btn flex">
            <input type="submit" value="変更する" class="button">
          </div>

        </form>
      </div>
    </div>
  </div>

  <script src="scripts/imgPreviewer.js"></script>
  <script>
    // インスタンス化
    const previewer = new ImgPreviewer();
  </script>
</body>