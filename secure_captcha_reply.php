<?php require('secure_captcha.inc.php'); ?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF8" />
    <link type="text/css" href="https://keeep.us/static/fonts/faces.css" rel="stylesheet" />
    <link type="text/css" href="style.css" rel="stylesheet" />
    <script language="javascript" src="js/jsonreport.js"></script>
    <script language="javascript">
        function formatJson(id) {
            var el = document.getElementById(id);
            el.innerHTML = _.jsonreport(el.innerHTML);
        }
        function toggle(id) {
            var el = document.getElementById(id);
            el.style.display = (el.style.display == 'block' ? 'none' : 'block');
        }
    </script>
</head>
<body class="captcha-example" onload="formatJson('server_answer')">
    <h3>Исходное сообщение (Source message)</h3>
    <p><?php echo htmlspecialchars($msg) ?></p>

    <h3>Результат (Results)</h3>
    <?php

    if (empty($session_uid)) {
        echo 'Неверный session ID (There are incorrect session ID). Вероятно, устаревший (Session is old). ';
    }

    if ($curl = curl_init()) { // Creating connection
        $args = array(
            'uid' => $session_uid,
            'captcha_answer' => $vcode,
            'message' => urlencode($message)
        );
        $args_str = '';
        foreach($args as $k=>$v) {
            $args_str .= "{$k}={$v}&";
        }
        curl_setopt($curl, CURLOPT_URL, "https://keeep.us/captcha/check/{$site_token}/");
        curl_setopt($curl, CURLOPT_POST, strlen($args_str));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $args_str);

        /* Option turn off SSL verification between your site and keeep.us
         * To avoid it and save the privacy, you need to change this line on something like this:
         * curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, true);
         * curl_setopt ($curl, CURLOPT_CAINFO, "pathto/cacert.pem");
         * More details here: http://www.php.net/manual/en/book.curl.php#99979
         */
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/plain')); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($curl);
        curl_close($curl);
    }

    $data = json_decode($out, true);

    function is_public_ip_real($data) {
        return $data['public_ip'] == $data['tcp_ip']
                && $data['public_ip'] == $data['udp_ip'];
    }
    function get_real_ip($data) {
        if (is_public_ip_real($data))
            return $data['public_ip'];
        elseif ($data['tcp_ip'] == $data['udp_ip'])
            return $data['tcp_ip'];
        else
            return '';
    }

    if (NULL !== $data) {
        if (isset($data['public_ip']) 
                && isset($data['tcp_ip']) 
                && isset($data['udp_ip'])
                && isset($data['is_success'])) {
            
            // is CAPTCHA checking success?
            if ($data['is_success'])
                echo '<span class="success">Проверка пройдена (The test is successful)</span><br>';
            else
                echo '<span class="failure">Проверка не пройдена (The test is failed)</span><br>';
            
            if (is_public_ip_real($data)) {
                echo '<span class="success">Использован реальный IP адрес (Used real IP address)</span><br>';
            } else {
                echo '<span class="failure">Использован подставной IP адрес (Used fake IP address): '. $data['public_ip'] .'</span><br>';
            }
            
            $ip = get_real_ip($data);
            if (!empty($ip)) {
                echo '<span class="success">Найден реальный IP адрес (Found real IP address):</span> '. $ip .'<br>';
            } else {
                echo '<span class="unknown">Данные о реальном IP адресе неоднозначны (Information about IP addresses is ambiguous)</span><br>';
            }
            
        } elseif (isset($data['msg']) && FALSE === $data['is_success']) {
            echo 'Сервер выслал ошибку с кодом (Server sends) "'. $data['msg'] .'"';
        } else {
            echo "Ошибка интерпретации (Interpretation error):<br> {$out}";
        }
    } else {
        echo "Ошибка преобразования (Conversion error):<br> {$out}";
    }

    echo '<br><a href="#" onClick="toggle(\'server_answer\')">Исходный ответ сервера (Source server answer)</a><span id="server_answer" class="hidden jsonreport"> '. $out .'</span>';

    ?>
</body>
</html>
