<?php
$DATA = $_POST;

// Get global variables
$session_uid = $DATA['uid'];
$vcode = $DATA['captcha_answer'];
$msg = $DATA['message'];

// Choose token in case of language
$lang = $_GET['lang'];
if ($lang === 'java') {
    $site_token = 'f3333333-2393-4043-9f83-816bc9accc2e';
} else {
    $lang = 'flash';
    $site_token = 'f259558b-2393-4043-9f83-816bc9accc2e';
}

// It is for Django dev-server testing
// $host = 'keeep.us';
$host = $_SERVER['SERVER_NAME'];
if ($host != 'keeep.us' && $host != 'www.keeep.us')
    $host .= ':8000';

?>