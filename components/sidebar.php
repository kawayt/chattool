<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// DBからすべてのチャンネルを取得
$stmt = $db->query('SELECT * FROM channels');
$channels = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="top" class="flex ai-center">
  <img id="logo-icon" src="icons/fluent-color--people-chat-48.svg">
  <p id="service-name">Chat</p>
</div>

<div id="nav-container">
  <div class="nav">
    <div class="category flex ai-center">
      <p class="category-name ellipsis-one-line">チャンネル</p>
      <a href="create_ch.php" id="add-link" class="flex ai-center">
        <img id="add-icon" src="icons/mingcute--add-line.svg">
      </a>
    </div>
    <ul>
      <?php if (!empty($channels)): ?>
        <?php foreach ($channels as $channel): ?>
          <li class="nav-item">
            <div class="nav-div flex ai-center">
              <img class="hash-icon" src="icons/mingcute--hashtag-line.svg">
              <a class="nav-a1 of-hidden" href="main.php?channel=<?= $channel['id'] ?>">
                <p class="ellipsis-one-line"><?= $channel['name']; ?></p>
              </a>
              <a class="nav-a2 flex jc-center ai-center" href="edit_ch.php?channel=<?= $channel['id'] ?>">
                <img class="more-icon" src="icons/mingcute--settings-3-line.svg">
              </a>
            </div>
          </li>
        <?php endforeach; ?>
      <?php else : ?> <!-- チャンネルがない場合 -->
        <li class="nav-item">
          <div class="nav-div flex ai-center">
            <img class="hash-icon" src="icons/mingcute--add-line.svg">
            <a class="nav-a1" href="create_ch.php">
              <p>チャンネルを作成</p>
            </a>
          </div>
        </li>
      <?php endif ?>
    </ul>

  </div>

  <div class="nav">
    <div class="category flex ai-center">
      <p class="category-name">アカウント</p>
    </div>
    <ul>
      <li class="nav-item">
        <div class="nav-div flex ai-center">
          <img class="hash-icon" src="icons/mingcute--information-line.svg">
          <a class="nav-a1 of-hidden" href="edit_profile.php">
            <p class="ellipsis-one-line">プロフィール</p>
          </a>
        </div>
      </li>
      <li class="nav-item">
        <div class="nav-div flex ai-center">
          <img class="hash-icon" src="icons/mingcute--mail-line.svg">
          <a class="nav-a1 of-hidden" href="edit_email.php">
            <p class="ellipsis-one-line">メールアドレス</p>
          </a>
        </div>
      </li>
      <li class="nav-item">
        <div class="nav-div flex ai-center">
          <img class="hash-icon" src="icons/mingcute--key-2-line.svg">
          <a class="nav-a1 of-hidden" href="edit_pw.php">
            <p class="ellipsis-one-line">パスワード</p>
          </a>
        </div>
      </li>
      <li class="nav-item">
        <div class="nav-div flex ai-center">
          <img class="hash-icon" src="icons/mingcute--safe-alert-line.svg">
          <a class="nav-a1 of-hidden" href="advanced_settings.php">
            <p class="ellipsis-one-line">高度な設定</p>
          </a>
        </div>
      </li>
      <li class="nav-item">
        <div class="nav-div flex ai-center">
          <img class="hash-icon" src="icons/mingcute--open-door-line.svg">
          <a class="nav-a1 of-hidden" href="logout.php">
            <p class="ellipsis-one-line">ログアウト</p>
          </a>
        </div>
      </li>
    </ul>
  </div>

  <div class="nav">
    <div class="category flex ai-center">
      <p class="category-name ellipsis-one-line">このアプリについて</p>
    </div>
    <ul>
      <li class="nav-item">
        <div class="nav-div flex ai-center">
          <img class="hash-icon" src="icons/mingcute--github-line.svg">
          <a class="nav-a1 of-hidden" href="https://github.com/kawayt/chattool" target="_blank" rel="noopener noreferrer">
            <p class="ellipsis-one-line">リポジトリ</p>
          </a>
        </div>
      </li>
    </ul>
  </div>
</div>

<div id="bottom">
  <div class="flex ai-center gap-5">
    <img class="avatar" src="uploads/<?= $_SESSION['avatar'] ?>">
    <div class="of-hidden">
      <p id="my-name" class="ellipsis-one-line"><?= $_SESSION['name'] ?></p>
      <p id="my-id" class="ellipsis-one-line">#<?= $_SESSION['id'] ?></p>
    </div>
  </div>
</div>

<script>
  const links = document.querySelectorAll(".nav-a1");

  // カレント表示
  links.forEach(function(link) {
    if (link.href === location.href) {
      link.closest(".nav-item").classList.add("current");
    }
  });
</script>