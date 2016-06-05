<?php
require_once("vendor/autoload.php");
use Mike42\WordPuzzles\Scrambler;
use Mike42\WordPuzzles\FindAWord;

$scramble = new Scrambler;

/* Interface here largely copied from from index.php */
if (has('submit')) {
    if (has('word_source') && has('word_count') && has('lang')) {
        $req_lang     = take('lang');
        $req_word_count  = (int)take('word_count');
        if (($req_word_count < $scramble -> c_min_words) || ($req_word_count > $scramble -> c_max_words)) {
            die("Number of words out of range.");
        }
        $req_word_source = take('word_source');
        $do = "words"; /* Time to find out word info. */
    } elseif (has('word_list')) {
        $req_word_list = take('word_list');
        $req_word_list = str_replace($req_word_list, "\r\n", "\n"); /* Switch lines to \n only */
        $req_word_list_arr = explode("\n", take('word_list')); /* Try \n first, then change to ',' if its all on one line */
        if (count($req_word_list_arr) == 1) {
            $req_word_list_arr = explode(",", take('word_list'));
        }
        /* Process list. Trim and remove blanks */
        foreach ($req_word_list_arr as $key => $val) {
            $req_word_list_arr[$key] = trim($req_word_list_arr[$key]);
            if ($req_word_list_arr[$key] == "") {
                unset($req_word_list_arr[$key]);
            }
        }
        if (count($req_word_list_arr) < $scramble -> c_min_words || count($req_word_list_arr) > $scramble -> c_max_words) {
            die("Too many or not enough words!");
        }
        $word_list_str = join(",", $req_word_list_arr);
        $do = "generate"; /* All info to make puzzle. */
    } else {
        $do = "nothing";
    }
} else {
    $do = "nothing";
}

$page_title    = "Create Word Scramble";
switch ($do) {
    case "nothing":
        $page_script = "scramble-main";
        break;
    case "words":
        /* Use find-a-word dict code */
        $find_a_word = new FindAWord();
        if ($req_word_source == "dict") {
            if (!$find_a_word -> loadDictionary($req_lang)) { /* Load dictionary */
                die("Could not load the dictionary for that language.");
            }
            $find_a_word -> loadWords(null, $req_word_count);
        } else {
            $find_a_word -> loadWords(array(), 0);
        }
        $word_list_str = join("\n", $find_a_word -> words);
        unset($find_a_word); /* Done! */
        $page_script = "scramble-words";
        break;
    case "generate":
        $page_title    = "Word Scramble";
        $scramble -> scramble($req_word_list_arr);
        $page_script = "scramble-generate";
        break;
    default:
        die("Didn't recognise: $do");
}
$page_gen_left = true;
include("includes/page.php");

/* Shorthand for getting request data */
function has($var)
{
    return isset($_REQUEST[$var]);
}
function take($var)
{
    return $_REQUEST[$var];
}
