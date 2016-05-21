<?php

namespace Mike42\WordPuzzles;

/* Generalised, configurable find-a-word class. */

/* Class for generating find-a-words */
class FindAWord
{
    /* Configuration options. These may be over-ridden by CLI, but the web interface wont touch them. */
    public $c_min_size   = 5;  /* Min and max dimensions of a board */
    public $c_max_size   = 49;
    public $c_min_words  = 0;  /* Min and max numer of words */
    public $c_max_words  = 249;

    /* Variables configured by users at run-time (set defaults) */
    public $width       = 0;
    public $height      = 0;
    public $fast        = false; /* Attempt fast execution */
    public $diagonal    = "half"; /* Half, sometimes (as result of difficult word), or never for these two. */
    public $reverse     = "half";

    /* Variables set by this class */
    private $dictionary = array();  /* The loaded dictionary for choosing words */
    public $words       = array();  /* Actual words to be included in the puzzle */
    public $key             = array();  /* Words arranged in 2D array -- [$x][$y] */
    public $puzzle      = array();  /* Puzzle filled in with random letters */
    public $failure         = array();  /* List of failed words. */

    /* Timer results */
    public $dict_time;
    public $calc_time;
    public $bug_out = false;

    /* Load the dictionary for a language */
    function load_dictionary($lang_code)
    {
        $fw_lang = self::supported_languages();
        $start_time = microtime(true);

        /* First check that the language code exists */
        if (!isset($fw_lang[$lang_code])) {
            return false;
        }

        $ignore = ($lang_code == 'en');
        if ($fw_lang[$lang_code] -> dict_cache_path != "") {
            $path = $fw_lang[$lang_code] -> dict_cache_path;
            /* Fast escape with cached (pre-filtered) dictionary, rather than all that checking. */
            if ($dict_txt = file_get_contents($path)) {
                $this -> dictionary = explode("\n", $dict_txt);
                return true;
            }
        }

        /* The path for the dictionary. */
        $path = $fw_lang[$lang_code] -> dict_path;

        /* Load the dictionary file */
        if (!$dict_txt = file_get_contents($path)) {
            return false;
        }

        /* Set up variables */
        $w_ignore = false;
        $i = 1;
        $count_accepted = 0;
        $count_ignored = 0;
        $this -> dictionary = array();
        $dict_arr = explode("\n", $dict_txt);

        /* Loop through the dictionary and pick suitable words for a find-a-word */
        foreach ($dict_arr as $w) {
            $w_ignore = false;
            if ($ignore) {
                if ($w != mb_strtolower($w)) {
                    $w_ignore = true;       /* Ignore proper nouns and acronyms */
                }
                if (mb_strlen($w) > 2 && !$w_ignore) {
                    if (mb_substr($w, mb_strlen($w) - 2, 1) == "'") {
                        $w_ignore = true;   /* Ignore words where the second-last letter is an apostrophe. */
                    }
                } elseif (mb_strlen($w) <= 2) {
                    $ignore = true; /* Cut out short words */
                }
            }
            if (!$w_ignore && $w != "") {
                $this -> dictionary[$i] = $w;
                $i++;
                $count_accepted++;
            } else {
                $count_ignored++;
            }
        }

        if ($fw_lang[$lang_code] -> dict_cache_path != "") {
            $this -> save_dict_cache($lang_code);
        }

        $end_time = microtime(true);
        $this -> dict_time = ($end_time - $start_time);
        return true;
    }

    function save_dict_cache($lang_code)
    {
        global $fw_lang;
        $filename = $fw_lang[$lang_code] -> dict_cache_path;
        if ($fn = fopen($filename, "w")) {
            $fat_str = join("\n", $this -> dictionary);
            fwrite($fn, $fat_str);
            fclose($fn);
            echo "<br/>Dictionary cache written for $lang_code.<br/>";
        } else {
            die("Writing out to $lang_code's dictionary cache failed. Please check permissions/paths or turn off the cache.");
        }
    }

    /* Either select random words, or store a list of words for the puzzle */
    function load_words($words = array(), $num = 0)
    {
        $this -> words = array();
        if (count($words) == 0) {
            $i = 1;
            if ($num < 0) {
                $num = 0;
            }
            if (count($this -> dictionary) == 0) {
                return false;
            }
            while ($num > ($i - 1)) {
                $id                     = rand(1, count($this->dictionary));
                $this -> words[$this->dictionary[$id]]  = $this->dictionary[$id];
                $i++;
            }
        } else {
            foreach ($words as $word) {
                $this -> words[$word] = $word;
            }
        }

        foreach ($this -> words as $id => $word) {
            /* The dictionary is UTF-8, but this script does not properly support it yet. Convert back to ISO */
            //$this -> words[$id] = iconv("UTF-8", "ISO-8859-1", $word);
        }

        ksort($this -> words); /* Words come out alphabetically */
        return true;
    }

    /* Select a random letter from the given alphabet */
    function alphabet_soup($alphabet)
    {
        $id = rand(0, count($alphabet)-1);
        return $alphabet[$id];
    }

    /* The main show: Generate a find-a-word */
    function calculate($lang_code)
    {
        $fw_lang = self::supported_languages();
        $abort = false;
        $start_time = microtime(true);

        /* Get some config options */
        $alphabet = $fw_lang[$lang_code] -> alphabet;

        $d = 0; /* Half-and-half diagonal */
        if ($this -> diagonal == "never") {
            $d = 1;
        }
        if ($this -> diagonal == "sometimes") {
            $d = -1;
        }
        $r = 0; /* Half-and-half reverse */
        if ($this -> reverse == "never") {
            $r = 1;
        }
        if ($this -> reverse == "sometimes") {
            $r = -1;
        }

        /* Make blank board */
        $this -> failure = array(); /* Clear errors */
        for ($x = 1; $x <= $this -> width; $x++) {
            for ($y = 1; $y <= $this -> height; $y++) {
                $this -> key[$x][$y] = "";
            }
        }

        /* Todo: Sorting words by length here will make sure the super-long words get a place. */

        /* Loop words */
        foreach ($this -> words as $word) {
            /* Spin and reverse as allowed */
            if ($d == 0) {
                $dir = rand(0, 2);
                $c1 = 3;
            } else {
                $dir = rand(0, 1);
                $c1 = 2;
            }
            if ($r == 0) {
                $rev = rand(0, 1);
                $c2 = 2;
            } else {
                $rev = 0;
                $c2 = 1;
            }

            $loop = 0;
            $fail = -1;
            $comb = $c1 * $c2; /* Number of combinations for this thing */
            while ($fail != 0 && $loop < $comb) {
                if ($fail > 0) {
                    /* Todo: This should depend on the mod of c1 and c2 or somesuch */
                    if ($d != 1 && $comb % $c2 == 0) {
                        $dir++;
                    } /* Flip if allowed but wait for $rev if it has 2 modes. */
                    if ($dir == 3) {
                        $dir = 0;
                    }
                    if ($r != 1) {
                        $rev++;
                    }
                    if ($rev == 2) {
                        $rev = 0;
                    }
                }
                $fail = 0;

                /* See where we can put this word. */
                $word_2d = $this -> doWord_square($word, $dir, $rev);
                $options = $this -> enum_possibilities($word_2d);
                if (isset($options[2])) { /* Use joining options first always */
                    $this -> paste_word($word_2d, explode(",", $options[2][rand(0, count($options[2]) - 1)]));
                    $fail = 0;
                } elseif (isset($options[1])) { /* Next non-joining options (a lone word) */
                    $this -> paste_word($word_2d, explode(",", $options[1][rand(0, count($options[1]) - 1)]));
                    $fail = 0;
                } else { /* No options! shucks */
                    if ($this -> bug_out) {
                        $abort = true;
                    }
                    if ($loop == 5) {
                        $this -> failure[] = $word;
                    }
                    if ($fail == -1) {
                        $fail = 0;
                    }
                    $fail++;
                }
                $loop++;
                if ($abort) {
                    break;
                } /* Part of optional fast-bug-out mode */
            }
            if ($abort) {
                break;
            } /* Part of optional fast-bug-out mode */
        }

        /* Fill in with alphabet soup */
        for ($x = 1; $x <= $this -> width; $x++) {
            for ($y = 1; $y <= $this -> height; $y++) {
                if ($this -> key[$x][$y] == "") {
                    $this -> puzzle[$x][$y] = $this -> alphabet_soup($alphabet);
                } else {
                    $this -> puzzle[$x][$y] = $this -> key[$x][$y];
                }
            }
        }

        $end_time = microtime(true);
        $this -> calc_time = $end_time - $start_time;
        return true;
    }

    function paste_word($word, $coords)
    {
        $x = $coords[0];
        $y = $coords[1];

        foreach ($word as $x_sub => $col) {
            foreach ($col as $y_sub => $char) {
                $x_dest = $x + $x_sub - 1;
                $y_dest = $y + $y_sub - 1;
                if ($y_dest > ($this -> height) || $x_dest > ($this -> width)) {
                    /* Error here? should have been checked already by enum_possibilities. */
                } elseif ($this -> key[$x_dest][$y_dest] == "") {
                    $this -> key[$x_dest][$y_dest] = $char;
                } else {
                }
            }
        }
    }

    function doWord_square($word, $direction = 0, $reverse = 0)
    {
        /* Put a word in a 2D array, optionally reversing it etc */
        switch ($direction) {
            case 0: /* Horiz. */
                $orientation['x'] = 1;
                $orientation['y'] = 0;
                break;
            case 1: /* Vert. */
                $orientation['x'] = 0;
                $orientation['y'] = 1;
                break;
            default: /* Diag. */
                $orientation['x'] = 1;
                $orientation['y'] = 1;
                break;
        }

        /* Reverse orientation if allowed */
        if ($reverse == 1) {
            $orientation['x'] = -$orientation['x'];
            $orientation['y'] = -$orientation['y'];
        }

        /* Adjust orientations and reverse word to keep in boundaries. */
        if ($orientation['y'] == -1 && $orientation['y'] == -1) {
            $orientation['y'] = 1;
            $orientation['x'] = 1;
            $word = $this -> reverse($word);
        } elseif ($orientation['y'] == -1) {
            $orientation['y'] = 1;
            $word = $this -> reverse($word);
        } elseif ($orientation['x'] == -1) {
            $orientation['x'] = 1;
            $word = $this -> reverse($word);
        }

        /* Pasting letters into 2D array. */
        $next['x'] = 1;
        $next['y'] = 1;
        $letters = 0;

        for ($i = 0; $i < mb_strlen($word); $i++) {
            $temp[$next['x']][$next['y']] = mb_strtoupper(mb_substr($word, $i, 1));
            $next['x'] = $next['x'] + $orientation['x'];
            $next['y'] = $next['y'] + $orientation['y'];
        }

        return $temp;
    }

    function enum_possibilities($board_struct)
    {
        /* List possible locations for this word (outputs list of joining possibilities and non-joining ones) */
        $possibilities = array();
        for ($y = 1; $y <= $this -> height; $y++) {
            for ($x = 1; $x <= $this -> width; $x++) {
                /* For each cell on the board... Attempt the word */
                $possible = 1;
                foreach ($board_struct as $x_sub => $col) {
                    foreach ($col as $y_sub => $char) {
                        if ($possible != 0) {
                            $x_check = $x + $x_sub - 1;
                            $y_check = $y + $y_sub - 1;
                            if (!isset($this -> key[$x_check][$y_check])) {
                                $possible = 0; /* Can't paste off the board */
                            } else {
                                /* Pasting over a character */
                                if ($this -> key[$x_check][$y_check] == "") {
                                    /* For blank squares, no change */
                                } elseif ($this -> key[$x_check][$y_check] == $char) {
                                    $possible = 2; /* Found a join! */
                                } else {
                                    $possible = 0; /* Can't paste over existing (different) letters. */
                                }
                            }
                        }
                    }
                }

                /* All possible starting places */
                if ($possible != 0) {
                    $possibilities[$possible][] = "$x,$y";
                    if ($this -> fast) {
                        return $possibilities;
                    }
                }
            }
        }
        return $possibilities;
    }

    /* Output the find-a-word to a HTML table */
    function outp_table($puzzle)
    {
        $str = "<table class=\"find-a-word\" cellspacing=0>\n";
        for ($y = 1; $y <= $this -> height; $y++) {
            $str .= "	<tr>\n";
            for ($x = 1; $x <= $this -> width; $x++) {
                if ($puzzle[$x][$y] == "") {
                    $str .= "		<td>&nbsp;</td>\n";
                } else {
                    $str .= "		<td>".$puzzle[$x][$y]."</td>\n";
                }
            }
            $str .= "	</tr>\n";
        }
        $str .= "</table>";
        return $str;
    }

    /* Formatted table for showing the answers */
    function outp_table_key()
    {
        $str = "<table class=\"find-a-word\" cellspacing=0>\n";
        for ($y = 1; $y <= $this -> height; $y++) {
            $str .= "	<tr>\n";
            for ($x = 1; $x <= $this -> width; $x++) {
                if ($this -> puzzle[$x][$y] == $this -> key[$x][$y]) {
                    $str .= "		<td class=\"word-here\">".$this -> puzzle[$x][$y]."</td>\n";
                } else {
                    $str .= "		<td>".$this -> puzzle[$x][$y]."</td>\n";
                }
            }
            $str .= "	</tr>\n";
        }
        $str .= "</table>";
        return $str;
    }

    /* Plaintext block of letters */
    function outp_block($puzzle)
    {
        $str = "";
        for ($y = 1; $y <= $this -> height; $y++) {
            for ($x = 1; $x <= $this -> width; $x++) {
                if ($puzzle[$x][$y] == "") {
                    $str .= "  ";
                } else {
                    $str .= $puzzle[$x][$y]." ";
                }
            }
            $str .= "\n";
        }
        return $str;
    }

    function reverse($str)
    {
        $res = "";
        for ($i = 0; $i < mb_strlen($str); $i++) {
            $res .= mb_substr($str, (mb_strlen($str) - 1 - $i), 1);
        }
        return $res;
    }
    
    static function supported_languages()
    {
        /* English */
        $fw_lang['en'] = new cls_find_a_word_lang();
        $fw_lang['en'] -> code = "en";
        $fw_lang['en'] -> name = "English (United States)";
        $fw_lang['en'] -> alphabet = array(     "A", "B", "C", "D", "E", "F", "G", "H", "I",
            "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z");
        $fw_lang['en'] -> dict_path         = "src/dict/en-us.txt";
        $fw_lang['en'] -> dict_cache_path   = "src/dict/en-us.cache.txt";
        
        /* Samoan */
        $fw_lang['sm'] = new cls_find_a_word_lang();
        $fw_lang['sm'] -> code = "sm";
        $fw_lang['sm'] -> name = "Samoan";
        $fw_lang['sm'] -> alphabet = array(     "A", "E", "I", "O", "U", "F", "G", "L", "M",
            "N", "P", "S", "T", "V", "H", "K", "R");
        $fw_lang['sm'] -> dict_path         = "src/dict/sm.txt";
        $fw_lang['sm'] -> dict_cache_path   = "src/dict/sm.cache.txt";
        return  $fw_lang;
    }
}

/* Structure for language settings */
class cls_find_a_word_lang
{
    public $name            = "";
    public $alphabet        = array();
    public $dict_path       = "";
    public $dict_cache_path= "";
}
