<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/
/////////////////////////////////
////// Настройки загруза ////////
/////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');

if ($rights >= 9)
{
    echo'<div class="phdr">Настройки загруз центра</div>';
    if (isset($_POST['submit']))
   {
        $down_setting = array();
        $down_setting = file_get_contents('set.dat');
        $down_setting = unserialize($down_setting);

        $down_setting['guest'] = intval($_POST['gues']);
        $down_setting['komm'] = intval($_POST['komclose']);
        $down_setting['kmess'] = intval($_POST['kol']);
        $down_setting['priv'] = intval($_POST['privinf']);
        $down_setting['priv_user'] = intval($_POST['priv_user']);
        $down_setting['infvideo'] = intval($_POST['infvideo']);
        $down_setting['zipview'] = intval($_POST['zipview']);
        $down_setting['mp3info'] = intval($_POST['mp3info']);
        $down_setting['tmini'] = intval($_POST['tmini']);
        $down_setting['vmini'] = intval($_POST['vmini']);
        $down_setting['bb'] = intval($_POST['bb']);
        $down_setting['filesize'] = intval($_POST['filesize']);
        $down_setting['cachetime'] = intval($_POST['cachetime']);
        $down_setting['newtime'] = intval($_POST['newtime']);
        if(is_file(functions::check(trim($_POST['zipfile']))))
        $down_setting['zipfile'] = functions::check(trim($_POST['zipfile']));
        else
        echo'<div class="rmenu">Файл для архивов не найден!</div>';

        if($arr = fopen('set.dat', "w"))
        {
        fwrite($arr, serialize($down_setting));
        fclose($arr);
            echo'<div class="gmenu">Настройки успешно сохранены!</div>';
        }
        else
        {
            echo'<div class="rmenu">Не удалось открыть файл настроек! Проверьте права доступа!</div>';
        }

        echo'<div class="menu"><a href="admin.php?act=setting">Настройки</a></div><div class="menu"><a href="admin.php">Админка</a></div>';

    }
    else
    {
        $down_setting = file_get_contents('set.dat');
        $down_setting = unserialize($down_setting);
        echo "<form action='admin.php?act=setting' method='post'>

        <div class='menu'>Скачивание гостям:<br/>Вкл.";
        radio_check($down_setting['guest'], 'gues');
        echo "Выкл.</div><div class='gmenu'>

        Комментарии:<br/>Вкл.";
        radio_check($down_setting['komm'], 'komclose');
        echo"Выкл.</div><div class='menu'>

        Уведомления о комментариях в приват:<br/>Вкл.";
        radio_check($down_setting['priv'], 'privinf');
        echo"Выкл.<br/>

        ID (админа) пользователя которому отправлять уведомления:<br/>";
        echo'<input type="number" name="priv_user" value="'.$down_setting['priv_user'].'"/>
        </div><div class="gmenu">';

        if (!extension_loaded('ffmpeg'))
        echo'<b><span class="red">Необходимо выключить!</span></b><br/>';
        echo'Информация о видео файле:<br/>Вкл.';
        radio_check($down_setting['infvideo'], 'infvideo');
        echo "Выкл.</div><div class='menu'>";

        echo'Просмотр zip:<br/>Вкл.';
        radio_check($down_setting['zipview'], 'zipview');
        echo "Выкл.</div><div class='gmenu'>";

        echo'Вывод информации о mp3 файлах:<br/>Вкл.';
        radio_check($down_setting['mp3info'], 'mp3info');
        echo "Выкл.</div><div class='menu'>";

        echo'Сокращённый вывод списка тем:<br/>Вкл.';
        radio_check($down_setting['tmini'], 'tmini');
        echo "Выкл.<br/><small>Выводится только скрин и ссылка на скачивание и подробности</small></div><div class='gmenu'>";

        if (!extension_loaded('ffmpeg'))
        echo'<b><span class="red">Необходимо выключить!</span></b><br/>';
        echo'Сокращённый вывод списка видео:<br/>Вкл.';
        radio_check($down_setting['vmini'], 'vmini');
        echo "Выкл.<br/><small>Выводится только скрин и ссылка на скачивание и подробности</small></div><div class='menu'>";

        echo'Быстрая вставка ББ-кодов:<br/>Вкл.';
        radio_check($down_setting['bb'], 'bb');
        echo "Выкл.</div>";

        echo '<div class="gmenu">
        Max. размер файла для пользователей:<br/>
        <input type="number" name="filesize" value="'.$down_setting['filesize'].'"/> кб.</div>
        <div class="menu">
        Время на которое кэшируются счётчики:<br/>
        <input type="number" name="cachetime" value="'.$down_setting['cachetime'].'"/> час(ов).</div>
        <div class="gmenu">
        Время, в течении которого файл считается новым:<br/>
        <input type="number" name="newtime" value="'.$down_setting['newtime'].'"/> дня/дней.</div>
        <div class="menu">
        Стандартный файл для архивов:<br/>
        <input type="text" name="zipfile" value="'.$down_setting['zipfile'].'"/>
        <div class="sub">Файл должен быть в папке с загруз центром! Регистр файла важен!</div></div>
        <div class="gmenu">
        <input type="submit" name="submit" value="Сохранить"/></div>
        </form>';
        echo'<div class="gmenu"><a href="admin.php">Админка</a></div>';
    }
}
else
    echo'Вы не имеете необходимых прав для доступа к данному разделу!';
