<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

/////////////////////////////////////
////// Настройки полей ввода ////////
/////////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');
if ($rights >= 9)
{
    echo'<div class="phdr">Настройки для JAVA</div>';
    if (isset($_POST['submit']))
    {
        $down_jar = array();
        $down_jar = file_get_contents('set.dat');
        $down_jar = unserialize($down_jar);
        $down_jar['jadgen'] = intval($_POST['jad']);
        $down_jar['jar_version'] = intval($_POST['jar_version']);
        $down_jar['jar_name'] = intval($_POST['jar_name']);
        $down_jar['jar_vendor'] = intval($_POST['jar_vendor']);
        $down_jar['jar_profile'] = intval($_POST['jar_profile']);
        $down_jar['jar_url'] = intval($_POST['jar_url']);

        if($arr = fopen('set.dat', "w"))
        {
            fwrite($arr, serialize($down_jar));
            fclose($arr);
                echo'<div class="gmenu">Настройки успешно сохранены!</div>';
        }
        else
        {
            echo'<div class="rmenu">Не удалось открыть файл настроек! Проверьте права доступа!</div>';
        }

        echo'<div class="menu"><a href="admin.php?act=set_jar">Настройки для JAVA</a></div><div class="menu"><a href="admin.php">Админка</a></div>';

    }
    else
    {
        $down_jar = file_get_contents('set.dat');
        $down_jar = unserialize($down_jar);
        echo '<form action="admin.php?act=set_jar" method="post">';
        echo'<div class="menu">Автоматическая генерация JAD к JAR:<br/>Вкл.';
        radio_check($down_jar['jadgen'], 'jad');
        echo 'Выкл.</div><div class="menu">';

        echo'Версия приложения при просмотре:<br/>Вкл.';
        radio_check($down_jar['jar_version'], 'jar_version');
        echo 'Выкл.</div><div class="menu">';

        echo'Название (из манифеста) при просмотре:<br/>Вкл.';
        radio_check($down_jar['jar_name'], 'jar_name');
        echo 'Выкл.</div><div class="menu">';

        echo'Производитель (из манифеста) при просмотре:<br/>Вкл.';
        radio_check($down_jar['jar_vendor'], 'jar_vendor');
        echo 'Выкл.</div><div class="menu">';

        echo'MIDP профиль(из манифеста) при просмотре:<br/>Вкл.';
        radio_check($down_jar['jar_profile'], 'jar_profile');
        echo 'Выкл.</div><div class="menu">';

        echo'Url(из манифеста) при просмотре:<br/>Вкл.';
        radio_check($down_jar['jar_url'], 'jar_url');
        echo 'Выкл.</div><div class="menu">';

        echo '<input type="submit" name="submit" value="Сохранить"/></div>
        </form>';
        echo'<div class="gmenu"><a href="admin.php">Админка</a></div>';
    }
}else
    echo'Вы не имеете необходимых прав для доступа к данному разделу!';
?>