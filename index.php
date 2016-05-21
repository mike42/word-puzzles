<?php
require_once("vendor/autoload.php");
use Mike42\WordPuzzles\FindAWord;

header("Content-Type: text/html; charset=utf-8;");
$find_a_word = new FindAWord();

/* Look at the information, validate, and decide on what to do */
if (has('submit')) { /* Everything which involves a submit */
    if (has('height') && has('width') && has('lang') && has('diagonal') && has('reverse')) {
        /* Get checkbox fields */
        $req_fast = has('fast'); /* Run fast mode */
        $req_slow = has('slow'); /* Kill time limit (or attempt) */

        $req_height = (int)take('height');
        $req_width  = (int)take('width');
        if (($req_height < $find_a_word -> c_min_size) || ($req_height > $find_a_word -> c_max_size)) {
            die("Puzzle height out of range.");
        }
        if (($req_width < $find_a_word -> c_min_size) || ($req_width > $find_a_word -> c_max_size)) {
            die("Puzzle width out of range.");
        }
        $req_lang     = take('lang');
        $req_diagonal = take('diagonal');
        $req_reverse  = take('reverse');

        if (has('word_list')) {
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
            if (count($req_word_list_arr) < $find_a_word -> c_min_words || count($req_word_list_arr) > $find_a_word -> c_max_words) {
                die("Word quantity out of range.");
            }
            $do = "generate"; /* All info to make puzzle. */
        } elseif (has('word_count') && has('word_source')) {
            $req_word_count  = (int)take('word_count');
            if (($req_word_count < $find_a_word -> c_min_words) || ($req_word_count > $find_a_word -> c_max_words)) {
                die("Number of words out of range.");
            }
            $req_word_source = take('word_source');
            $do = "words"; /* Time to find out word info. */
        } else {
            $do = "nothing"; /* Some broken submit. */
        }
    } else { /* Some (more) broken request */
        $do = "nothing";
    }
} elseif (has('action')) { /* Non-submit, stuff like info and view source or the main page follows. */
    switch (take('action')) {
        case "info":
            $do = "info";
            break;
        default:
            $do = "nothing";
    }
} else { /* Default */
    $do = "nothing";
}

/* Calls to the $find_a_word object under here: */
$page_title = "Mike's Word-Search Generator";
switch ($do) {
    case "nothing":
        $page_script = "main";
        $page_gen_left = true;
        break;
    case "info":
        $page_script = "info";
        $page_gen_left = true;
        break;
    case "words":
        if ($req_word_source == "dict") {
            if (!$find_a_word -> load_dictionary($req_lang)) { /* Load dictionary */
                die("Could not load the dictionary for that language.");
            }
            $find_a_word -> load_words(null, $req_word_count);
        } else {
            $find_a_word -> load_words(array(), 0);
        }
        $word_list_str = join("\n", $find_a_word -> words);
        $page_script = "words";
        $page_gen_left = true;
        break;
    case "generate":
        if ($req_width > 30 || $req_height > 30 || count($req_word_list_arr) > 75) {
         /* The algorithm can reliably place 75 words in a 30x30 grid in about 3 seconds on my 1.6ghz atom.
			but becomes very slow for large grids due to the explosion of the number of possibilities.
			Here we force the 'fast' mode for larger puzzles. This mode tells the algorithm to stop at the
			first viable square, rather than enumerating all possibilities and selecting from them. */
            $req_fast = true;
         /* If you feel like switching this, consider using the CLI instead. This is important to the
			web interface to avoid minutes-long executions for users who turn all of the settings to maximum.
			I imagine that most practical word-searches are much smaller than this! */
        }

        if ($req_slow) { /* Long query expected. Turn to 3 mins at user's request. */
            ini_set('max_execution_time', 180);
        }

        $find_a_word -> fast    = $req_fast;
        $find_a_word -> width    = $req_width;
        $find_a_word -> height   = $req_height;
        $find_a_word -> diagonal = $req_diagonal;
        $find_a_word -> reverse  = $req_reverse;
        $find_a_word -> load_words($req_word_list_arr, 0);
        $find_a_word -> calculate($req_lang);
        $page_script   = "generate";
        $page_gen_left = false; /* Working too hard for silliness */
        break;
    default:
        die("Didn't recognise: $do");
}

include("includes/page.php");

/* Shorthand for getting request data */
function has($var)
{
    return isset($_REQUEST[$var]);
}
function take($var)
{
    return stripslashes($_REQUEST[$var]);
}
