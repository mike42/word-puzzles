<?php
require_once("common/cipher.php");
require_once("common/find-a-word.php");
$cipher = new cls_cipher;

/* Interface here largely copied from from index.php */
if (has('submit')) {
    if (has('text') && has('lang')) {
        if (has('hint')) {
            $req_hint = true;
        } else {
            $req_hint = false;
        }
        if (has('symbol')) {
            $req_symbol = true;
        } else {
            $req_symbol = false;
        }

        $req_text = take('text');
        if (mb_strlen($req_text) > $cipher -> c_max_len) {
            die("Can't make cryptogram that long.");
        }

        $req_lang= take('lang');
        if (!isset($fw_lang[$req_lang])) {
            die("Couldn't find that language!");
        }

        $do = "generate"; /* All info to make puzzle. */
    } else {
        $do = "nothing";
    }
} else {
    $do = "nothing";
}

$page_title    = "Mike's Word Cipher";
switch ($do) {
    case "nothing":
        $page_script = "cipher-main";
        break;
    case "generate":
        $cipher -> generate_key('en');
        $cipher -> hint = $req_hint;
        $cipher -> symbol = $req_symbol;
        $cipher -> encode($req_text);
        $page_script = "cipher-generate";
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
