<?php
$sql = 'SELECT * FROM channels';
$stmt = $db->query($sql);
$channels = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>