<?php
include "settings.php";
$connection = mysqli_connect($host, $user, $password, $database);
if (!$connection) {
    echo 'Провери връзка с базата данни';
    exit;
}
mysqli_set_charset($connection, 'utf8');
?>