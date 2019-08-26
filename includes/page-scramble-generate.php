    <h2><?php echo htmlspecialchars($page_title); ?></h2>
    <form action="scramble.php" method="post">
    <?php
    echo "<div style=\"padding:1em\">";
    echo "<p>The letters in these words have been <b>scrambled</b>. Try to put them back in the correct order:</p>";
    echo "<div id=\"solution\" class=\"toggle-hidden\">";
    echo $scramble -> outpSolution();
    echo "</div>";
    echo "<div id=\"solution-sub\">";
    echo $scramble -> outpProblem();
    echo "</div>";
    echo "<dl><dt>Possible answers:</dt><dd>";
    echo join(", ", $scramble -> words);
    echo "</dd>";
    echo "</div>";  ?>

    <div class="form-group">
        <div id="solution-show">
            <input type="submit" name="submit" value="Regenerate" /><input type="button" onClick="toggle('solution');" value="Show solution" />
        </div>
        <div id="solution-hide" class="toggle-hidden">
            <input type="submit" name="submit" value="Regenerate" /><input type="button" onClick="toggle('solution');" value="Hide solution" />
        </div>
    </div>

    <?php echo field("word_list", join(",", $req_word_list_arr)); ?>
    </form>
<?php function field($field, $value)
{
    return "<input type=\"hidden\" name=\"$field\" value=\"".htmlspecialchars($value)."\" />\n";
}?>
