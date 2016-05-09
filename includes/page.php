<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><? echo htmlspecialchars($page_title); ?></title>
		<script type="text/javascript" src="style/script.js"></script>
		<link rel="stylesheet" href="style/style.css">
	</head>
	<body>
	<div class="container-panel">
		<div class="left-panel">
		<?
			$fw = new cls_find_a_word;

			if($page_gen_left) {
				$fw -> load_words(Array("search", "word", "puzzle", "michael", "find"), 0);
			} else {
				$fw -> load_words(Array(), 0);
			}
			$fw -> width   = 10;
			$fw -> height  = 10;
			$fw -> reverse = "never";
			$fw -> calculate('en');
			echo $fw -> outp_table_key();
			unset($fw);
		?>
		<center>
		<ul class="word-list">
			<li><a href="index.php">Word Search</a></li>
			<li><a href="scramble.php">Scrambler</a></li>
			<li><a href="cipher.php">Cipher</a></li>
			<li><a href="index.php?action=info">Info</a></li></ul>
		</center>
		</div><div class="right-panel"><? include("page-".$page_script.".php"); ?>
		</div>
	</div>
	</body>
</html>
