#!/usr/bin/php
<?php /* Script to render *massive* find-a-words by sub-blocking */
require_once("common/find-a-word.php");
$find_a_word = new cls_find_a_word();
$req_lang     = "en";
$req_width    = 30;
$req_height   = 30;
$req_diagonal = true;
$req_reverse  = true;
$req_fast	  = false;

if(!$find_a_word -> load_dictionary($req_lang)) {
	die("Could not load the dictionary for that language.");
}
while(1) {
	$find_a_word -> load_words(NULL, 1500);
	$find_a_word -> fast    = $req_fast;
	$find_a_word -> width    = $req_width;
	$find_a_word -> height   = $req_height;
	$find_a_word -> diagonal = $req_diagonal;
	$find_a_word -> reverse  = $req_reverse;
	$find_a_word -> bug_out  = true;
	$find_a_word -> calculate($req_lang);
	echo $find_a_word -> outp_block($find_a_word -> key);
}

?>
