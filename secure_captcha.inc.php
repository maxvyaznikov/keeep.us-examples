<?php

$DATA = $_POST;

function fetch($var_name, $default=null) {
    global $DATA;
    if (isset($DATA[$var_name])) {
        return $DATA[$var_name];
    } else {
        return $default;
    }
}

// Get global variables
$session_uid = fetch('uid');
$vcode = fetch('captcha_answer');
$msg = fetch('message');

// Choose token in case of language
$lang = fetch('lang');
if ($lang === 'java') {
    $site_token = 'f3333333-2393-4043-9f83-816bc9accc2e';
} else {
    $lang = 'flash';
    $site_token = 'f259558b-2393-4043-9f83-816bc9accc2e';
}

header("Access-Control-Allow-Origin: https://keeep.us/");

?>