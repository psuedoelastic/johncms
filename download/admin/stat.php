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
///////////// Статистика //////////////////
///////////////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');

$files = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `type`=0"), 0); //// файлы
$dopfiles = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `type`=1"), 0); ///// Доп. файлы
$size = mysql_result(mysql_query("SELECT SUM(size) FROM `downfiles`"), 0); ///// Размер файлов
$count = mysql_result(mysql_query("SELECT SUM(count) FROM `downfiles`"), 0); ///// Скачивания файлов
$path = mysql_result(mysql_query("SELECT COUNT(*) FROM `downpath`"), 0); ///// Папки

echo'<div class="phdr">Статистика Загруз-Центра</div>';
echo'<div class="menu">Всего файлов: '.$files.'</div>';
echo'<div class="menu">Всего дополнительных файлов: '.$dopfiles.'</div>';
echo'<div class="menu">Всего папок: '.$path.'</div>';
echo'<div class="menu">Общий размер файлов: '.size_convert($size).'</div>';
echo'<div class="menu">Всего скачиваний: '.$count.'</div>';
echo'<div class="phdr"><a href="admin.php">Админка</a></div>';

?>