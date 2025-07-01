<?php
session_start();
require('dbconnect.php');

if (!empty($_GET['message']) && !empty($_GET['channel'])) {
  $stmt = $db->prepare("DELETE FROM messages WHERE msid=?");
  $stmt->execute([$_GET['message']]);

  $_SESSION['toast'] = 'message_delete'; // トースト用フラグ
  header('Location: ../main.php?channel=' . $_GET['channel']);
  exit();
}