<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

///////////////////////////////////////////
///////////// Создание папки //////////////
///////////////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');
$cat = intval($_GET['cat']);
echo '<div class="phdr">Создание папки</div>';

if (isset($_POST['submit'])) {
    $dirftp = functions::check($_POST['dirftp']);
    $dirname = functions::check($_POST['dirname']);
    $dirdesc = functions::check($_POST['dirdesc']);
    $types = functions::check($_POST['types']);
    $dost = intval($_POST['dost']);
    $error = array();

    if (preg_match("/[^a-z0-9.()_-]/i", $dirftp))
        $error[] = 'В названии папки для фтп <b>' . $dirftp .
            '</b> присутствуют недопустимые символы<br/>Разрешены только латинские символы, цифры и некоторые знаки ( .()_- )';

    if (!$dirftp)
        $error[] = 'Не заполнено название папки для фтп!';

    if (!$dirname)
        $error[] = 'Не заполнено Название папки для отображения!';

    if ($dost) {
        if (!$types)
            $error[] = 'Должен быть минимум 1 тип файла разрешённый к загрузке!';
    }

    if ($cat) {
        $cat1 = mysql_query("select * from `downpath` where id = '" . $cat . "';");
        $adrdir = mysql_fetch_array($cat1);
        $droot = $loadroot . '/' . $adrdir[way];
        $dirr = $adrdir['way'] . $dirftp . '/';
    } else {
        $droot = $loadroot . '/';
        $dirr = $dirftp . '/';
    }

    if (is_dir($droot . $dirftp))
        $error[] = 'Такая папка уже существует в фтп! Измените имя и повторите операцию!';

    if ($error) {
        echo '<div class="rmenu">Обнаружены ошибки!</div>';
        foreach ($error as $val) {
            echo '<div class="rmenu">' . $val . '</div>';
        }
        echo '<div class="menu"><a href="admin.php?act=folder">Управление файлами и папками</a><br/>';
        echo '<a href="admin.php">Админка</a></div>';
        require_once ('../incfiles/end.php');
        exit;
    }


    if (mkdir($droot . $dirftp, 0777)) {
        chmod($droot . $dirftp, 0777);
        mysql_query("INSERT INTO `downpath` SET `refid` = '" . $cat . "', `way` = '" . $dirr .
            "', `name` = '" . $dirname . "', `desc` = '" . $dirdesc .
            "', `position` = '0', `dost` = '" . $dost . "', `types` = '" . $types . "';");
        echo '<div class="gmenu">Папка успешно создана!</div>';
    }
} else {
    echo "<form action='admin.php?act=createdir&amp;cat=" . $_GET['cat'] .
        "' method='post'>
<div class='menu'>Название папки для фтп:<br/>
<input type='text' name='dirftp'/></div><div class='menu'>
Название для отображения:<br/>
<input type='text' name='dirname'/></div><div class='menu'>
Описание папки:<br/>
<input type='text' name='dirdesc'/><br/>
<small>Необязательный параметр</small></div><div class='menu'>
Допустимые типы файлов:<br/>
<input type='text' name='types'/><br/>
<small>Заполнять через запятую: mp3,gif,zip,sis,exe (all - разрешить любые типы файлов *)</small></div><div class='menu'>
<input type='checkbox' name='dost' value='1'/> Добавление файлов юзерами
</div><div class='menu'>
<input type='submit' name='submit' value='Создать'/></div>
</form>";

echo '<div class="rmenu">* Внимание! Если Ваш хостинг некорректно воспринимает htaccess, есть риск что Вам зальют php скрипт. 
Убедитесь, что выгруженные через форму php файлы не исполняются!</div>';

}
echo '<div class="menu"><a href="admin.php?act=folder">Управление файлами и папками</a></div>';

echo '<div class="menu"><a href="admin.php">Админка</a></div>';

?>