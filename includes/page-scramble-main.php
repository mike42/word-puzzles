	<h2><?php echo htmlspecialchars($page_title); ?></h2>
    <form action="scramble.php" method="post">
        <p>This is a <b>word scrambler</b>, which will randomly re-arrange words for use in puzzles.</p>

        <p>I want to scramble:</p>
        <?php for ($i = $scramble -> c_min_words; $i <= $scramble -> c_max_words; $i++) { /* Contents of word select box */
            $sel_words[$i] = $i;
} ?>
        <ul class="radio-list">
            <?php echo select("word_count", $sel_words, 10); ?> words.
        </ul>
        
        <p>The words will come from:</p>
        <ul class="radio-list">
            <?php unset($nums);
            for ($i = 1; $i < 250; $i++) {
                $nums[$i] = $i;
            }?>
            <li><?php echo radio("word_source", "dict", "The dictionary <i>(default)</i>", 1);?></li>
            <li><?php echo radio("word_source", "list", "I will type in a list of words", 0);?></li>
        </ul>
    
        <p>Please select language:</p>
<?php
use Mike42\WordPuzzles\FindAWord;

$fw_lang = FindAWord::supportedLanguages();
foreach ($fw_lang as $lang) {
    $sel_lang[$lang -> code] = $lang -> name;
} ?>
        <ul class="radio-list">
            <li><?php echo select("lang", $sel_lang, 'en'); ?>
        </ul>
        <div class="form-group">
            <button type="submit" name="submit">Next step <i class="glyphicon glyphicon-chevron-right"></i> </button>
        </div>
    </form>
<?php function radio($field, $value, $caption, $selected = 0)
{
    static $count = 0;
    $id = $field.$count++;
    if ($selected) {
        $checked = " checked=\"checked\"";
    } else {
        $checked = "";
    }
    return "<input type=\"radio\" name=\"$field\" value=\"$value\"$checked id=\"$id\"/> <label for=\"$id\">$caption</label>";
}
function checkbox($field, $caption, $selected = 0)
{
    if ($selected) {
        $checked = " checked=\"checked\"";
    } else {
        $checked = "";
    }
    return "<input type=\"checkbox\" name=\"$field\" $checked id=\"$field\" /> <label for=\"$field\">$caption</label>";
}
function select($field, $options, $selected = 0)
{
    $str = "<select name=\"$field\" id=\"select-$field\">";
    foreach ($options as $id => $caption) {
        if ($id == $selected) {
            $sel = " selected=\"1\"";
        } else {
            $sel = "";
        }
        $str .= "	<option value=\"".htmlspecialchars($id)."\"$sel>".htmlspecialchars($caption)."</option>\n";
    }
    $str .= "</select>";
    return $str;
} ?>
