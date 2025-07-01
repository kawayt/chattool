<?php if (isset($_SESSION['toast'])): ?>
  <!-- ログイン時 -->
  <?php if ($_SESSION['toast'] == 'login'): ?>
    <div class="toast flex ai-center gap-5">
      <img src="icons/mingcute--check-circle-line.svg">
      <p class="toast-text">ログインしました</p>
    </div>
  <!-- アカウント作成時 -->
  <?php elseif ($_SESSION['toast'] == 'signup'): ?>
     <div class="toast flex ai-center gap-5">
      <img src="icons/mingcute--check-circle-line.svg">
      <p class="toast-text">アカウントを作成しました</p>
    </div>
  <!-- チャンネル作成時 -->
  <?php elseif ($_SESSION['toast'] == 'chadd'): ?>
     <div class="toast flex ai-center gap-5">
      <img src="icons/mingcute--check-circle-line.svg">
      <p class="toast-text">チャンネルを作成しました</p>
    </div>
  <!-- 設定変更時 -->
  <?php elseif ($_SESSION['toast'] == 'update'): ?>
     <div class="toast flex ai-center gap-5">
      <img src="icons/mingcute--check-circle-line.svg">
      <p class="toast-text">設定を変更しました</p>
    </div>
  <!-- メッセージ削除時 -->
  <?php elseif ($_SESSION['toast'] == 'message_delete'): ?>
     <div class="toast flex ai-center gap-5">
      <img src="icons/mingcute--check-circle-line.svg">
      <p class="toast-text">メッセージを削除しました</p>
    </div>
  <!-- チャンネル削除時 -->
  <?php elseif ($_SESSION['toast'] == 'chdel'): ?>
     <div class="toast flex ai-center gap-5">
      <img src="icons/mingcute--check-circle-line.svg">
      <p class="toast-text">チャンネルを削除しました</p>
    </div>
  <!-- セッションタイムアウト時 -->
  <?php elseif ($_SESSION['toast'] == 'timeout'): ?>
     <div class="toast flex ai-center gap-5">
      <img src="icons/mingcute--close-circle-line.svg">
      <p class="toast-text">セッションがタイムアウトしました</p>
    </div>
  <!-- 未ログイン時 -->
  <?php elseif ($_SESSION['toast'] == 'unauthorized'): ?>
     <div class="toast flex ai-center gap-5">
      <img src="icons/mingcute--close-circle-line.svg">
      <p class="toast-text">不正なアクセスです</p>
    </div>
  <?php endif; ?>
  <?php unset($_SESSION['toast']); // フラグ消去 ?>
<?php endif; ?>