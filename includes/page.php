<?php
require_once("vendor/autoload.php");
use Mike42\WordPuzzles\FindAWord;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="assets/css/word-puzzles.css" />
    <link rel="icon" type="image/png" href="favicon.png" />
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php"> <img alt="Word Puzzles"
                    src="favicon.png" width="24px" />
                </a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="index.php">Find</a></li>
                <li><a href="scramble.php">Scramble</a></li>
                <li><a href="cipher.php">Cipher</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php?action=info">About</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="main-panel"><?php include("page-".$page_script.".php"); ?>
        </div>
    </div>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/word-puzzles.min.js"></script>
</body>
</html>
