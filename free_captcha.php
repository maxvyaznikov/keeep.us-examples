<?php
/*
 * All answers in json
 */

$secret = 'f3724572-2393-4043-9f83-816bc9accc2e';

header("Access-Control-Allow-Origin: https://keeep.us");

function get_captcha() {
    global $secret;
    $captcha_uid = 'error';
    $log = 'error';
    if ($curl = curl_init()) { // Создаем подключение
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 0')); 
        curl_setopt($curl, CURLOPT_URL, "https://keeep.us/captcha/init/{$secret}/");

        /* Option turn off SSL verification between your site and keeep.us
         * To avoid it and save the privacy, you need to change this line on something like this:
         *
         * curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, true);
         * curl_setopt ($curl, CURLOPT_CAINFO, "pathto/cacert.pem");
         *
         * More details here: http://www.php.net/manual/en/book.curl.php#99979
         */
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($curl); // Скачиваем
        $log = $out;
        $captcha = json_decode($out, true);
        if (NULL !== $captcha && !isset($captcha['error'])) {
            $captcha_uid = $captcha['uid'];
        }
        curl_close($curl);
    }
    $captcha_url = 'https://'. (isset($captcha['image_server']) 
                                    ? $captcha['image_server'] 
                                    : 'keeep.us');
    if ($captcha_uid != 'error')
        $captcha_url .= "/static/cache/captcha-{$captcha_uid}.png";
    else 
        $captcha_url .= "/static/images/captcha-error.png";
    
    return array(
                'captcha_url' => $captcha_url,
                'captcha_uid' => $captcha_uid,
                'log' => $log
                );
}

function check_captcha($captcha_uid, $vcode) {
    if (empty($captcha_uid)) {
        $msg = 'Неверный captcha_uid';
        $cls = 'warning';
    } else {
        $curl = curl_init();

        $args = array(
            'captcha_answer' => $vcode,
            'uid' => $captcha_uid
        );
        $args_str = '';
        foreach($args as $k=>$v) {
            $args_str .= "{$k}={$v}&";
        }
        curl_setopt($curl, CURLOPT_URL, "https://keeep.us/captcha/check/f3724572-2393-4043-9f83-816bc9accc2e/");
        curl_setopt($curl, CURLOPT_POST, strlen($args_str));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $args_str);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));

        /* Option turn off SSL verification between your site and keeep.us
         * To avoid it and save the privacy, you need to change this line on something like this:
         *
         * curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, true);
         * curl_setopt ($curl, CURLOPT_CAINFO, "pathto/cacert.pem");
         *
         * More details here: http://www.php.net/manual/en/book.curl.php#99979
         */
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($curl);

        $result = json_decode($out, true);

        if ($result['msg'] == 'VERIFICATION') {
            $msg = 'Проверка ';
            if ($result['is_success']) {
                $msg .= 'пройдена';
                $cls = 'success';
            } else {
                $msg .= 'не пройдена';
                $cls = 'error';
            }
        } else {
            $msg = 'Ошибка';
            $cls = 'error';
        }
        curl_close($curl);
    }
    return array(
                'msg' => $msg,
                'cls' => $cls
                );
}

$DATA = $_POST;

$out = "";

if (!empty($DATA['action'])) {
    switch($DATA['action']) {
        case 'get':
            $out = get_captcha();
            break;
        case 'check':
            $out = check_captcha($DATA['uid'], $DATA['vcode']);
            break;
    }
}

echo json_encode($out);

?>