<?php
require_once("vendor/autoload.php");
use Mike42\WordPuzzles\FindAWord;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo htmlspecialchars($page_title); ?></title>
        <script type="text/javascript" src="assets/js/word-puzzles.min.js"></script>
        <link rel="stylesheet" href="assets/css/word-puzzles.css">
    </head>
    <body>
    <div class="container-panel">
        <div class="left-panel">
        <?php
        $fw = new FindAWord();

        if ($page_gen_left) {
            $fw -> loadWords(array("search", "word", "puzzle", "michael", "find"), 0);
        } else {
            $fw -> loadWords(array(), 0);
        }
            $fw -> width   = 10;
            $fw -> height  = 10;
            $fw -> reverse = "never";
            $fw -> calculate('en');
            echo $fw -> outpTableKey();
            unset($fw);
        ?>
        <center>
        <ul class="word-list">
            <li><a href="index.php">Word Search</a></li>
            <li><a href="scramble.php">Scrambler</a></li>
            <li><a href="cipher.php">Cipher</a></li>
            <li><a href="index.php?action=info">Info</a></li></ul>
        </center>
        </div><div class="right-panel"><?php include("page-".$page_script.".php"); ?>
        </div>
    </div>
    </body>
</html>
