	<h2><?php echo htmlspecialchars($page_title); ?></h2>
<p>Solve this cryptogram:</p>
<table><tr><td>
<?php	echo "<div id=\"solution\" class=\"hidden\">";
	echo $cipher -> outp_html(true);
	echo "</div>";
	echo "<div id=\"solution-sub\">";
	echo $cipher -> outp_html(false);
	echo "</div>";  ?>
</td></tr><tr><td>
	<form action="cipher.php" method="post">
		<div id="solution-show">
			<input type="submit" name="submit" value="Regenerate" /><input type="button" onClick="toggle('solution');" value="Show solution" />
		</div>
		<div id="solution-hide" class="hidden">
			<input type="submit" name="submit" value="Regenerate" /><input type="button" onClick="toggle('solution');" value="Hide solution" />
		</div>
	<?php	echo field("text", $req_text);
		echo field("lang", $req_lang);
		echo field("hint", $req_hint);
		echo field("symbol", $req_symbol); ?>
	</form>
</td></tr></table>

	</form>
<?php function field($field, $value) {
	return "<input type=\"hidden\" name=\"$field\" value=\"".htmlspecialchars($value)."\" />\n";
}?>
