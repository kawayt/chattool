<?php
session_start();
$_SESSION = array();
session_destroy();

// ログインページへ
header('Location: ../login.php');
exit();
?>