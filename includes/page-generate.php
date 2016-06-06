	<h2><?php echo htmlspecialchars($page_title); ?></h2>
    <form action="index.php" method="post">
    <?php
    foreach ($find_a_word -> failure as $eek) {
        /* Knock failed words off the main list */
        unset($find_a_word -> words[$eek]);
    }
    ?>

    <?php
    echo "<table><tr><td valign=\"top\" style=\"padding:1em\">";
    echo "<div id=\"solution\" class=\"toggle-hidden\">";
    echo $find_a_word -> outpTableKey();
    echo "</div>";
    echo "<div id=\"solution-sub\">";
    echo $find_a_word -> outpTable($find_a_word -> puzzle);
    echo "</div>";
    echo "</td><td><ul class=\"word-list\">";
    foreach ($find_a_word -> words as $word) {
        echo "<li>".htmlspecialchars($word)."</li>";
    }
    echo "</ul></td>";
    echo "</tr></table>";   ?>

    <div id="solution-show">
        <input type="submit" name="submit" value="Regenerate" /><input type="button" onClick="toggle('solution');" value="Show solution" />
    </div>
    <div id="solution-hide" class="toggle-hidden">
        <input type="submit" name="submit" value="Regenerate" /><input type="button" onClick="toggle('solution');" value="Hide solution" />
    </div>
    <hr/>
    <?php	if (count($find_a_word -> failure) > 0) {
            echo "<p>We couldn't fit all of those words on the puzzle!<ul style=\"color: #f00\">";
        foreach ($find_a_word -> failure as $eek) {
            echo "<li>".$eek."</li>";
        }
            echo "</ul>Press 'regenerate to try again.</p>";
}

        echo "<p class=\"gen-time\"><small>Puzzle generated in ".$find_a_word -> calc_time." seconds.</small></p>";

        /* All fields here */
        echo field("word_list", join(",", $req_word_list_arr));
        echo field("width", $req_width);
        echo field("height", $req_height);
        echo field("lang", $req_lang);
        echo field("diagonal", $req_diagonal);
        echo field("reverse", $req_reverse);
        /* Check-box fields, value indicated by presence/absence */
if ($req_fast) {
    echo field("fast", 1);
}
if ($req_slow) {
    echo field("slow", 1);
} ?>
        </form>
<?php function field($field, $value)
{
    return "<input type=\"hidden\" name=\"$field\" value=\"".htmlspecialchars($value)."\" />\n";
}?>
