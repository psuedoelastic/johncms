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
/////// Чистильщик кэша /////////
/////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');

$viewf = intval($_GET['view']);
$file = mysql_query("SELECT * FROM `downfiles` WHERE `id` = '" . $viewf . "'");
$file2 = mysql_query("SELECT * FROM `downfiles` WHERE `pathid` = '" . $viewf . "' AND `type` = 1");
if (mysql_num_rows($file) != 0)
{
$file = mysql_fetch_array($file);
$dopway = str_replace(basename($file['way']), '', $file['way']);
$textl = 'Скачать ' . $file['name'] . ' бесплатно!';

if (!$file['size'])
{
    $siz = filesize($loadroot.'/'.$file[way]);
    mysql_query("UPDATE `downfiles` set `size` = '" . $siz . "' WHERE `id` = '" . $viewf . "'");
}
else
{
    $siz = $file['size'];
}
$filtime = date("d.m.Y", $file['time']);
$nadir = $file[pathid];
$pat = '';
while ($nadir != '') {
$dnew = mysql_query("select * from `downpath` where id = '" . $nadir . "';");
$dnew1 = mysql_fetch_array($dnew);
$pat = '<a href="admin.php?act=folder&amp;cat=' . $dnew1['id'] . '">' . $dnew1['name'] . '</a> &gt;  ' . $pat . '';
$nadir = $dnew1[refid];
}
$tf = pathinfo($file['way'], PATHINFO_EXTENSION);

$namee = explode('||||', $file['name']);
echo '<div class="phdr"><a href="admin.php?act=folder">Корень</a> ' . $pat . $namee[0] . ' [' . size_convert($siz) . ']</div>';

$gol = explode('|', $file['gol']);
    echo '<div class="menu">Рейтинг: ' . $rating = $file['rating'] ? $file['rating'] : '0';
    echo '&nbsp;Оценок: ' . $gol1 = $file['rating'] ? count($gol) : '0';
    echo '<br/>';
    echo rat_star($file['rating']) . '</div>';

echo '<div class="menu"><b>Тип файла:</b> ' . $tf . '</div>';

//// Обработка скрина если таковой имеется////////////
$scr = mysql_result(mysql_query("SELECT COUNT(*) FROM `downscreen` WHERE `fileid` = '" . $viewf . "'"), 0);
if ($scr) {
echo '<div class="menu">';
$screen = mysql_query("SELECT * FROM `downscreen` WHERE `fileid` = '" . $viewf . "'");
$i = 1;
while ($screen1 = mysql_fetch_array($screen)) {
if ($i == 1) {
if ($down_setting['screenshot'])
echo '<img src="graftemp/' . $screen1[way] . '" alt="Скриншот..."/><br/>';
echo 'Скриншот: ';
}

            if (!is_file('graftemp/' . $screen1[way])) {
                $img = new ImageEdit($screenroot . '/' . $screen1['way'], $down_setting['scr_size']);
                $img->setQuality(90);
                if($down_setting['scr_copy'])
                $img->setCopy($down_setting['scr_copy_size'], $down_setting['scr_copy_text']);
                $img->saveImage('graftemp/' . $screen1[way]);
            }
            echo '<a href="getthumb.php?file=screens/' . $screen1[way] .
                '&amp;size=0&amp;q=100&amp;copy=' . $down_setting['scr_copy_text'] . '">' . $i .
                '</a> ';
            $i++;
        }
        echo '<br/>';
        echo '</div>';
    }
                echo '<div class="menu"><b>Добавлен:</b> ' . $filtime . '</div>';
                if ($file['login']) {
                    echo '<div class="menu"><b>Добавил:</b> ' . $file['login'] . '</div>';
                }

    echo '<div class="menu"><b>Скачан:</b> ' . $file['count'] . ' раз</div>';
    echo '<div class="menu">';
    if ($file['desc']) {
        echo functions::checkout($file['desc'], 1, 1);
    } else {
        echo 'Описания нет!';
    }
    echo '</div>';

echo '<div class="menu"><img src="img/save.png" alt="."/> <a href="loadfile.php?down=' . $file['way'] . '">Скачать основной файл</a> [' . $file['count'] . ']</div>';
if ($rights >= 9) {
if ($tf == 'zip'){
echo '<div class="menu"><img src="img/rar.png" alt="."/> <a href="admin.php?act=zip&amp;file='. $file[way] .'&amp;file_id=' . $viewf .
'">Открыть архив</a></div>';
}
}


if (mysql_num_rows($file2)) {
while ($file22 = mysql_fetch_array($file2)) {
$tf = pathinfo($file22['way'], PATHINFO_EXTENSION);

echo '<div class="menu"><img src="img/save.png" alt="."/> <a href="loadfile.php?down=' .
$file22['way'] . '">Скачать ' . $file22['name'] . '</a> [' . $file22['count'] .
']</div>';

if ($rights >= 9) {

if ($tf == 'zip') {
echo '<div class="menu"><img src="img/rar.png" alt="."/> <a href="admin.php?act=zip&amp;file='.$file22[way].'&amp;file_id=' . $viewf .
'">Открыть архив</a></div>';
}
}
echo '<div class="menu">[<a href="admin.php?act=delete&amp;op=file&amp;id=' . $file22['id'] . '">Уд.</a>]
[<a href="admin.php?act=edit&amp;file=' . $file22['id'] . '">Изм.</a>]</div>';
}
}




echo '<div class="phdr"><a href="admin.php?act=folder&amp;cat=' . $file['pathid'] . '">Назад</a></div><div class="func">';      
if ($file['themeid'] <= 0)
echo '<a href="admin.php?act=createtheme&amp;fid=' . $viewf . '">Создать тему для обсуждения</a><br/>';
else
echo '<a href="admin.php?act=delete&amp;op=theme&amp;id=' . $viewf . '">Удалить тему для обсуждения</a><br/>';

echo '<a href="admin.php?act=upscreen&amp;file=' . $viewf . '">Выгрузить скриншот</a><br/>
<a href="admin.php?act=upload&amp;cat=' . $file['pathid'] . '&amp;file=' . $viewf . '">Выгрузить доп. файл</a><br/>
<a href="admin.php?act=import&amp;cat=' . $file['pathid'] . '&amp;file=' . $viewf . '">Имп. доп. файл</a></div>';

} else
echo '<div class="rmenu">А нет такого файла!</div>';


echo'<div class="menu"><a href="admin.php">Админка</a></div>';

?>