<?php
ob_start(); // Без тази функция изкарва предупреждения 
session_start();
mb_internal_encoding('UTF-8');
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= $Pagetitle ?></title>
        <link rel="stylesheet" href="<?= $style ?>" />
        <style type="text/css">
            h4 { font-family: verdana; color: #E42E6B; font-size: 15px; text-align:left}
        </style>
    </head>
    <body background="<?= $background ?>">
        <h1 style="font-family:verdana;font-size:20px">
            <?= $h1_tag ?>  
        </h1>
