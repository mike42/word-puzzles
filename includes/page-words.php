	<h2><?php echo htmlspecialchars($page_title); ?></h2>
<?php
use Mike42\WordPuzzles\FindAWord;

if ($req_word_source == "dict") {
    $fw_lang = FindAWord::supportedLanguages();
    echo "<p>Please check this list of words. These are from an <b>".$fw_lang[$req_lang] -> name."</b> dictionary.</p>";
} else {
    echo "<p>Please enter the list of words below, one per line:</p>";
} ?>
        <form action="index.php" method="post">
            <div style="width: 18em; text-align: right;">
                <textarea cols=35 rows=20 name="word_list"><?php echo htmlspecialchars($word_list_str); ?></textarea>
                <input type="submit" name="submit" value="Make puzzle" />
            </div>
            <?php echo field("width", $req_width);
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
    return "<input type=\"hidden\" name=\"$field\" value=\"".htmlspecialchars($value)."\" />";
}?>
