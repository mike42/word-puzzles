<?php
/* Quick class for encoding words with a cipher */

/* As these puzzles are designed to be solved by schoolchildren,
		I would like to make it clear here that this is not to be used for anything even remotely secret! */

namespace Mike42\WordPuzzles;

class Cipher
{
    public $c_max_len = 65536;
    public $lang  = "";
    public $key   = array();
    public $key_r = array();
    public $enc   = array();
    public $hint_word = -1;

    public $hint   = false;
    public $symbol = false;

    /* Substitute letters according to key */
    public function encode($text)
    {
        $text = trim(strtoupper($text));
        $result = array();
        for ($i = 0; $i < mb_strlen($text); $i++) {
            $c = mb_substr($text, $i, 1);
            if (isset($this -> key[$c])) {
                $result[] = $this -> key[$c];
            } elseif ($c == " ") {
                $this -> enc[] = $result;
                $result = array();
            } else {
                $result[] = $c;
            }
        }
        $this -> enc[] = $result;

        if ($this -> hint) {
            $this -> hint_word = rand(0, count($this -> enc) - 1);
        } else {
            $this -> hint_word = -1;
        }
        return true;
    }

    /* Make a key (randomise the alphabet of choice) */
    public function generateKey($lang = "en")
    {
        global $fw_lang;
        $this -> lang = $lang;
        $messed = $this -> unsort($fw_lang[$lang] -> alphabet); /* Huge help! */
        $i = 0;
        foreach ($messed as $letter) {
            $key[$fw_lang[$lang] -> alphabet[$i]] = $letter;
            $key_rev[$letter] = $fw_lang[$lang] -> alphabet[$i];
            $i++;
        }
        $this -> key = $key;
        $this -> key_rev = $key_rev;
        return $key;
    }

    /* Place an array in random order (wrecks keys!) -- Copied from scrambler.php */
    protected function unsort($array)
    {
        foreach ($array as $item) {
            /* Randomly choose a numeric ID */
            $id = rand(1, 65536);
            while (isset($rand[$id])) {
                $id = rand(1, 65536);
            }
            $rand[$id] = $item;
        }

        /* ksort the result */
        ksort($rand);
        return $rand;
    }

    /* Produce a series of left-floated HTML tables containing result */
    public function outpHtml($answer = false)
    {
        $res[] = "<div style=\"height: 5em\">";
        foreach ($this -> enc as $id => $word) {
            $res[] = "	<table style=\"float:left; padding-right: 1em;\">";
            $res[] = "		<tr>";
            foreach ($word as $letter) {
                if (isset($this -> key_rev[$letter])) {
                    if ($answer) {
                        $col = " color: #f00; background-color: #ff0;";
                    } else {
                        $col = "";
                    }
                    $res[] = "		<td style=\"border: 1px solid #000; width: 1em; text-align: center;$col\">";
                    if ($answer || $id == $this -> hint_word) {
                        $res[] = "		".htmlentities($this -> key_rev[$letter])."</td>";
                    }
                } else {
                    $res[] = "		<td style=\"text-align: center;\">";
                    if ($answer) {
                        $res[] = "		".htmlentities($letter)."</td>";
                    }
                }
                if (!$answer) {
                        $res[] = "		"."&nbsp;</td>";
                }
            }
            $res[] = "		</tr><tr>";
            foreach ($word as $letter) {
                $res[] = "		<td style=\"text-align: center; width: 1em;\">$letter</td>";
            }
            $res[] = "		</tr>";
            $res[] = "</table>";
        }
        $res[] = "</div>";
        return join("\n", $res);
    }
}
