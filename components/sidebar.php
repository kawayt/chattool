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

<div class="scrollable" id="nav-container">
  <div class="nav">
    <div class="category flex ai-center">
      <p class="category-name">チャンネル</p>
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
              <a class="nav-a1" href="main.php?channel=<?= $channel['id'] ?>">
                <p><?= $channel['name']; ?></p>
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
      <p class="category-name"><?= $_SESSION['name'] ?> さん</p>
    </div>
    <ul>
      <li class="nav-item">
        <div class="nav-div flex ai-center">
          <img class="hash-icon" src="icons/mingcute--information-line.svg">
          <a class="nav-a1" href="edit_profile.php">
            <p>プロフィール</p>
          </a>
        </div>
      </li>
      <li class="nav-item">
        <div class="nav-div flex ai-center">
          <img class="hash-icon" src="icons/mingcute--mail-line.svg">
          <a class="nav-a1" href="edit_email.php">
            <p>メールアドレス</p>
          </a>
        </div>
      </li>
      <li class="nav-item">
        <div class="nav-div flex ai-center">
          <img class="hash-icon" src="icons/mingcute--key-2-line.svg">
          <a class="nav-a1" href="edit_pw.php">
            <p>パスワード</p>
          </a>
        </div>
      </li>
      <li class="nav-item">
        <div class="nav-div flex ai-center">
          <img class="hash-icon" src="icons/mingcute--safe-alert-line.svg">
          <a class="nav-a1" href="advanced_settings.php">
            <p>高度な設定</p>
          </a>
        </div>
      </li>
      <li class="nav-item">
        <div class="nav-div flex ai-center">
          <img class="hash-icon" src="icons/mingcute--open-door-line.svg">
          <a class="nav-a1" href="logout.php">
            <p>ログアウト</p>
          </a>
        </div>
      </li>
    </ul>
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