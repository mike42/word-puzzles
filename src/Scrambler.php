<?php
/* Quick class for scrambling words */

namespace Mike42\WordPuzzles;

class Scrambler
{
    public $c_min_words = 1;
    public $c_max_words = 249;

    public $clean    = false; /* Do a 'clean scramle' (sort letters rather than scrambling) */
    public $words     = array();  /* List of words to work with */
    public $scrambled = array(); /* Assoc. array of scrambled words */

    function scramble($words = array())
    {
        $this -> words = $words;
        $words = $this -> unsort($words);

        foreach ($words as $word) {
            $letter = array();

            for ($i = 0; $i < mb_strlen($word); $i++) {
                $c = mb_substr($word, $i, 1);
                $letter[$c."-".$i] = $c;
            }

            if ($this -> clean) {
                ksort($letter);
            } else {
                $letter = $this -> unsort($letter);
            }

            $this -> scrambled[$word] = join("", $letter);
            unset($letter);
        }

        return $this -> scrambled;
    }

    /* Place an array in random order (wrecks keys!) */
    function unsort($array)
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

    function outp_solution()
    {
        $str = "<table>\n";
        $i = 0;
        foreach ($this -> scrambled as $orig => $messed) {
            if ($i % 2 == 0) {
                $str .= "	<tr>\n";
                $str .= "		<td>";
            } else {
                $str .= "		<td style=\"padding-left: 1em\">";
            }
            $str .= htmlspecialchars($messed)."</td>\n";
            $str .= "		<td> = </td>\n";
            $str .= "		<td style=\"min-width: 10em; color: #f00; text-align: center; \">".htmlspecialchars($orig)."</td>\n";
            if ($i % 2 != 0) {
                $str .= "	</tr>\n";
            }
            $i++;
        }
        if ($i % 2 != 0) {
            $str .= "	</tr>\n";
        }
        $str .= "</table>\n";
        return $str;
    }

    function outp_problem()
    {
        $str = "<table>\n";
        $i = 0;
        foreach ($this -> scrambled as $orig => $messed) {
            if ($i % 2 == 0) {
                $str .= "	<tr>\n";
                $str .= "		<td>";
            } else {
                $str .= "		<td style=\"padding-left: 1em\">";
            }
            $str .= htmlspecialchars($messed)."</td>\n";
            $str .= "		<td> = </td>\n";
            $str .= "		<td style=\"min-width: 10em;\">__________________</td>\n";
            if ($i % 2 != 0) {
                $str .= "	</tr>\n";
            }
            $i++;
        }
        if ($i % 2 != 0) {
            $str .= "	</tr>\n";
        }
        $str .= "</table>\n";
        return $str;
    }
}
