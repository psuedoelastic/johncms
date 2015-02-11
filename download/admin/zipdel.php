<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
Сайт: http://symbos.su
R866920725287
Z117468354234
*/
////////////////////////////////////
// Массовый снос файлов в архивах //
////////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');

$cat = intval($_GET['cat']);

if ($_POST['submit'])
{
    $f = functions::check($_POST['name']);
    include_once 'classes/pclzip.lib.php';
    echo '<div class="phdr">Работаем с архивами</div>';

    $delf1 = mysql_fetch_array(mysql_query("SELECT * FROM `downpath` WHERE `id` = '" . $cat . "';"));
    $zap = mysql_query("SELECT * FROM `downfiles` WHERE `way` LIKE '" . $delf1['way'] . "%' ");
    $ok = 0;

    while ($zap2 = mysql_fetch_array($zap))
    {
        if (pathinfo($zap2['way'], PATHINFO_EXTENSION) == 'zip')
        {
            $ok++;
            $loadroot = str_replace("..", "", $loadroot);
            $zip = new PclZip($_SERVER['DOCUMENT_ROOT'] . $loadroot . '/' . $zap2[way]);
            $ext = $zip->delete(PCLZIP_OPT_BY_NAME, $f);
            if (!$ext)
            {
                echo '<div class="rmenu">Не удалось удалить файл в след. архиве: ' . $loadroot . '/' . $zap2[way] . '</div>';
            }
        }
    }
    echo '<div class="gmenu">Удаление файла из архивов завершено! Обработано успешно: ' . $ok . '</div>';

}
else
{
    echo '<div class="phdr">Массовое удаление файлов в архивах</div>';
    echo '<form action="admin.php?act=zipdel&amp;cat=' . $cat . '" method="post">
    <div class="menu">
    Имя файла в архивах:<br/>
    <input type="text" name="name"/></div><div class="menu">
    <input type="submit" name="submit" value="Продолжить"/></div></form>';

}
echo '<div class="gmenu"><a href="admin.php">Админка</a></div>';



?>