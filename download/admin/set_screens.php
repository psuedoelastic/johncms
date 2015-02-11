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
    echo'<div class="phdr">Настройки скриншотов</div>';
    if (isset($_POST['submit']))
    {
        $down_scr = array();
        $down_scr = file_get_contents('set.dat');
        $down_scr = unserialize($down_scr);
        $down_scr['scr_load_img'] = intval($_POST['scr_load_img']);
        $down_scr['scr_copy'] = intval($_POST['scr_copy']);
        $down_scr['copy_position'] = intval($_POST['copy_position']);
        $down_scr['screencache'] = intval($_POST['screencache']);
        $down_scr['screenshot'] = intval($_POST['screenshot']);
        $down_scr['screenlist'] = intval($_POST['screenlist']);
        $down_scr['screenview'] = intval($_POST['screenview']);
        $down_scr['screenvideo'] = intval($_POST['screenvideo']);
        $down_scr['scrlistvideo'] = intval($_POST['scrlistvideo']);
        $down_scr['scr_size'] = intval($_POST['scr_size']);
        $down_scr['scr_size_list'] = intval($_POST['scr_size_list']);
        $down_scr['scr_copy_size'] = intval($_POST['scr_copy_size']);
        $down_scr['scr_copy_listsize'] = intval($_POST['scr_copy_listsize']);
        $down_scr['scr_copy_text'] = functions::check($_POST['scr_copy_text']);

        if($arr = fopen('set.dat', "w"))
        {
            fwrite($arr, serialize($down_scr));
            fclose($arr);
                echo'<div class="gmenu">Настройки успешно сохранены!</div>';
        }
        else
        {
            echo'<div class="rmenu">Не удалось открыть файл настроек! Проверьте права доступа!</div>';
        }

        echo'<div class="menu"><a href="admin.php?act=set_screens">Настройки скриншотов</a></div><div class="menu"><a href="admin.php">Админка</a></div>';

    }
    else
    {
        $down_scr = file_get_contents('set.dat');
        $down_scr = unserialize($down_scr);
        echo '<form action="admin.php?act=set_screens" method="post">';

        echo '<div class="menu">Нанесение копирайта при скачивании картинки:<br/>Вкл.';
        radio_check($down_scr['scr_load_img'], 'scr_load_img');
        echo 'Выкл.</div>';

        echo '<div class="menu">Нанесение копирайта на скриншоты:<br/>Вкл.';
        radio_check($down_scr['scr_copy'], 'scr_copy');
        echo 'Выкл.</div><div class="menu">';

        echo 'Позиция копирайта:<br/>Левый верхний угол.';
        radio_check($down_scr['copy_position'], 'copy_position');
        echo 'Правый нижний угол.</div><div class="menu">';

        echo 'Кэширование скриншотов:<br/>Вкл.';
        radio_check($down_scr['screencache'], 'screencache');
        echo 'Выкл.</div><div class="menu">';

        echo 'Просмотр скриншотов в описании:<br/>Вкл.';
        radio_check($down_scr['screenshot'], 'screenshot');
        echo 'Выкл.</div><div class="menu">';

        echo 'Скрины к темам в списке файлов:<br/>Вкл.';
        radio_check($down_scr['screenlist'], 'screenlist');
        echo 'Выкл.</div><div class="menu">';

        echo 'Скрины к темам при просмотре 1 файла:<br/>Вкл.';
        radio_check($down_scr['screenview'], 'screenview');
        echo 'Выкл.</div><div class="menu">';
        if (!extension_loaded('ffmpeg'))
        echo'<b><span class="red">Необходимо выключить!</span></b><br/>';
        echo 'Скрин при просмотре 1 видео:<br/>Вкл.';
        radio_check($down_scr['screenvideo'], 'screenvideo');
        echo 'Выкл.</div><div class="menu">';
        if (!extension_loaded('ffmpeg'))
        echo'<b><span class="red">Необходимо выключить!</span></b><br/>';
        echo 'Скрин в списке видео файлов:<br/>Вкл.';
        radio_check($down_scr['scrlistvideo'], 'scrlistvideo');
        echo 'Выкл.</div><div class="menu">';

        echo 'Размер скриншота:<br/>
        <input type="number" name="scr_size" value="'.$down_scr['scr_size'].'"/> px.<br/>
        <small>Скриншот будет сжиматься пропорционально. 0 - Выключить сжатие</small></div>
        <div class="menu">';

        echo 'Размер скриншота в списке файлов:<br/>
        <input type="number" name="scr_size_list" value="'.$down_scr['scr_size_list'].'"/> px.<br/>
        <small>Скриншот будет сжиматься пропорционально. 0 - Выключить сжатие</small></div>
        <div class="menu">';

        echo 'Размер копирайта при просмотре одного файла:<br/>
        <input type="number" name="scr_copy_size" value="'.$down_scr['scr_copy_size'].'"/></div>
        <div class="menu">';

        echo 'Размер копирайта в списке файлов:<br/>
        <input type="number" name="scr_copy_listsize" value="'.$down_scr['scr_copy_listsize'].'"/></div>
        <div class="menu">';

        echo 'Текст копирайта:<br/>
        <input type="text" name="scr_copy_text" value="'.$down_scr['scr_copy_text'].'"/></div>
        <div class="menu">';

        echo '<input type="submit" name="submit" value="Сохранить"/></div>
        </form>';
        echo'<div class="gmenu"><a href="admin.php">Админка</a></div>';
    }
}else
    echo'Вы не имеете необходимых прав для доступа к данному разделу!';
?>