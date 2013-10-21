<?php
include 'includes/heder.php';
session_destroy();
header('Location: index.php');
exit;
?>
