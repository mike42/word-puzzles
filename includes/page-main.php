	<h2><? echo htmlspecialchars($page_title); ?></h2>
		<p>This is a <b>word-search</b> (a.k.a <b>find-a-word</b>) generator. Select from the options below, or read the <a href="?action=info">info page</a> if you would like to know how it works.</p>
		<form action="index.php" method="post">
			<p>My puzzle will be:</p>
			<ul class="radio-list">
			<?	for($i = $find_a_word -> c_min_words; $i <= $find_a_word -> c_max_words; $i++) { /* Contents of word select box */
					$sel_words[$i] = $i;
				}
				for($i = $find_a_word -> c_min_size; $i <= $find_a_word -> c_max_size; $i++) { /* Contents of size box */
					$sel_size[$i] = $i;
				}	?>
				<li><? echo select("width", $sel_size, 15); ?> x <? echo select("height", $sel_size, 15); ?> squares, with <? echo select("word_count", $sel_words, 15); ?> words.</li>
			</ul>
			<p>The words will come from:</p>
			<ul class="radio-list">
				<? unset($nums);
				for($i = 1; $i < 250; $i++) {
					$nums[$i] = $i;
				}?>
				<li><? echo radio("word_source", "dict",		"The dictionary <i>(default)</i>", 1);?></li>
				<li><? echo radio("word_source", "list","I will type in a list of words", 0);?></li>
			</ul>
			<div class="toggle" id="more-options-show">(<a href="JavaScript: void{}" onClick="toggle('more-options')">show</a>)</div>
			<div class="toggle hidden" id="more-options-hide">(<a href="JavaScript: void{}" onClick="toggle('more-options')">hide</a>)</div>
			<h3>Extra options</h3>
			<div id="more-options-sub">
				<dl><dd><i>Configure diagonal words, reverse words, languages, and advanced options.</i></dl>
			</div>
			<div id="more-options" class="hidden">
				<p>Word search language:</p>
				<?	foreach($fw_lang as $lang) {
						$sel_lang[$lang -> code] = $lang -> name;
					} ?>
				<ul class="radio-list">
					<li><? echo select("lang", $sel_lang, 'en'); ?>
				</ul>
				<p>Use diagonal words:</p>
				<ul class="radio-list">
					<li><? echo radio("diagonal", "half",		"Half the time <i>(default)</i>", 1);?></li>
					<li><? echo radio("diagonal", "sometimes",	"Only if you need to", 0);?></li>
					<li><? echo radio("diagonal", "never",		"Never", 0);?></li>
				</ul>
				<p>Reverse words:</p>
				<ul class="radio-list">
					<li><? echo radio("reverse", "half",		"Half the time <i>(default)</i>", 1);?></li>
					<li><? echo radio("reverse", "sometimes",	"Only if you need to", 0);?></li>
					<li><? echo radio("reverse", "never",		"Never", 0);?></li>
				</ul>
				<p>Advanced options: <sup><a href="?action=info#special">[info]</a></sup></p>
				<ul class="radio-list">
					<li><? echo checkbox("fast", "Use fast mode (for making large puzzles)", 0); ?></li>
					<li><? echo checkbox("slow", "Try to switch off PHP's time limit (".ini_get( 'max_execution_time')." seconds)", 0); ?></li>
				</ul>
			</div>
			<p><input type="submit" name="submit" value="Next step" /></p>
		</form>
<? function radio($field, $value, $caption, $selected = 0) {
	static $count = 0;
	$id = $field.$count++;
	if($selected) { $checked = " checked=\"checked\"";  } else { $checked = ""; }	
	return "<input type=\"radio\" name=\"$field\" value=\"$value\"$checked id=\"$id\"/> <label for=\"$id\">$caption</label>";
}
function checkbox($field, $caption, $selected = 0) {
	if($selected) { $checked = " checked=\"checked\"";  } else { $checked = ""; }
	return "<input type=\"checkbox\" name=\"$field\" $checked id=\"$field\" /> <label for=\"$field\">$caption</label>";
}
function select($field, $options, $selected = 0) {
	$str = "<select name=\"$field\">";
	foreach($options as $id => $caption) {
		if($id == $selected) { $sel = " selected=\"1\"";  } else { $sel = ""; }
		$str .= "	<option value=\"".htmlspecialchars($id)."\"$sel>".htmlspecialchars($caption)."</option>\n";
	}
	$str .= "</select>";
	return $str;
} ?>
