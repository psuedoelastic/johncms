<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
Сайт: http://symbos.su
R866920725287
Z117468354234
*/
/////////////////////////////////////////////////////
// Массовое добавление стандартного файла в архивы //
/////////////////////////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');

$cat = intval($_GET['cat']);
$ver = intval($_GET['ver']);
if ($ver) {
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
            $zip = new PclZip(ROOTPATH . $loadroot . '/' . $zap2['way']);
            $add = $zip->add($down_setting['zipfile'], PCLZIP_OPT_ADD_PATH, $sdf, PCLZIP_OPT_REMOVE_ALL_PATH);
            if (!$add)
            {
                echo '<div class="rmenu">Файл не добавлен в архив! ' . $loadroot . '/' . $zap2['way'] . '</div>';
            }
        }
    }
    echo '<div class="gmenu">Раскидка по архивам окончена! Обработано успешно: ' . $ok . '</div>';
}
else
{
    echo '<div class="menu">Добавить стандартный текстовый файл во все архивы?<br/>
    ВНИМАНИЕ! ДЛИТЕЛЬНАЯ ОПЕРАЦИЯ С ПОВЫШЕНОЙ НАГРУЗКОЙ ПРИ БОЛЬШЁМ КОЛИЧЕСТВЕ АРХИВОВ!</div>
    <div class="gmenu"><a href="admin.php?act=zipman&amp;ver=1&amp;cat=' . $cat . '">Да</a> | <a href="admin.php">Нет</a></div>';
}

echo '<div class="gmenu"><a href="admin.php">Админка</a></div>';