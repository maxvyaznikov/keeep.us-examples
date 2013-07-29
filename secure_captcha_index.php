<!DOCTYPE html><?php require('secure_captcha.inc.php'); ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF8" />
    <link type="text/css" href="https://keeep.us/static/fonts/faces.css" rel="stylesheet" />
    <link type="text/css" href="https://keeep.us/tools/style.css" rel="stylesheet" />
    <script>
        var KEEEPUS_CAPTCHA_VARS = {
            host: 'keeep.us',
            site_token: <?php echo "'{$site_token}'"; ?>,
            use: <?php echo "'{$lang}'"; ?>,
            ids: {
                captcha_answer: 'captcha_answer',
                submit_button: 'submit-btn'
            },
            messages: {
                has_no_java: 'Для прохождения верификации необходимо установить:<br>'+
                            '<a href="http://ru.wikipedia.org/wiki/Java-%D0%B0%D0%BF%D0%BF%D0%BB%D0%B5%D1%82">Java</a> '+
                            '(<a href="http://www.java.com/getjava/">приступить</a>)<br>'+
                            'For the passage of verification must install:<br>'+
                            '<a href="http://en.wikipedia.org/wiki/Java_applet">Java</a> '+
                            '(<a href="http://www.java.com/getjava/">install</a>)',
                has_no_flash: 'Для прохождения верификации необходимо установить<br>'+
                            '<a href="http://ru.wikipedia.org/wiki/Adobe_Flash">Flash</a> '+
                            '(<a href="http://get.adobe.com/ru/flashplayer/">приступить</a>)<br>'+
                            'For the passage of verification must install:<br>'+
                            '<a href="http://en.wikipedia.org/wiki/Adobe_Flash">Flash</a> '+
                            '(<a href="http://get.adobe.com/en/flashplayer/">install</a>)',
                init_error: 'Произошла ошибка инициализации (There are initializing error)'
            }
        };
    </script>
    <script src="https://keeep.us/static/js/captcha_init.js"></script>
</head>
<body class="captcha-example">
    <p>Ниже представлена форма тестирования реализации симбиоза алгоритма автоматического теста Тьюринга (CAPTCHA) и методов раскрытия анонимности пользователей или детектирования факта использования средств скрывающих реальный IP-адрес клиента. После ввода текста сообщения, кода верификации (CAPTCHA) и нажатия кнопки "Отправить", вам будут представлены записи с полученной информацией о вашем компьютере.</p>
    <p>Here is the form of testing the implementation of symbiosis algorithm automatically Turing test (CAPTCHA) and practices of disclosure anonymous users or detecting the fact of using tools to hide the real IP-address. After entering message, verification code (for CAPTCHA) and clicking "Submit" you will be presented with the information obtained about you.</p>
    <form id="check-form" action="secure_captcha_reply.php?lang=<? echo $lang ?>" method="post">
        <table>
            <tr><th>Сообщение (Message):</th><td><textarea name="message" cols=60 rows=4>Текст сообщения по умолчанию (Default message text). Ради демонстрации будет приведен на результирующей странице после отправки формы (It'll be on result page for demonstration)</textarea>
            <tr><th>Введите цифры (Enter a digits):</th><td>
                <div id="captcha_container">Ошибка загрузки (Loading error)</div>
                <input type="hidden" name="uid" id="uid" value="" />
            <tr><th>&nbsp;</th><td><input autofocus type="text" name="captcha_answer" id="captcha_answer" />
            <tr><td colspan=2 class="text-right"><input type="submit" id="submit-btn" value="Отправить (Submit)" />
        </table>
    </form>
</body>
</html>