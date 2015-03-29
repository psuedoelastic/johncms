<?php defined('_IN_JOHNCMS') or die('Error: restricted access');
/**
 * @package     JohnCMS
 * @link        http://johncms.com
 * @copyright   Copyright (C) 2008-2011 JohnCMS Community
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      http://johncms.com/about
 *
 * @var $lng
 * @var $lng_dl
 */

$files = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `type`=0"), 0); //// файлы
$dopfiles = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `type`=1"), 0); ///// Доп. файлы
$size = mysql_result(mysql_query("SELECT SUM(size) FROM `downfiles`"), 0); ///// Размер файлов
$count = mysql_result(mysql_query("SELECT SUM(count) FROM `downfiles`"), 0); ///// Скачивания файлов
$path = mysql_result(mysql_query("SELECT COUNT(*) FROM `downpath`"), 0); ///// Папки

echo'<div class="phdr">'.$lng_dl['statistic'].'</div>';
echo'<div class="menu">'.$lng_dl['all_files'].': '.$files.'</div>';
echo'<div class="menu">'.$lng_dl['all_additional_count'].': '.$dopfiles.'</div>';
echo'<div class="menu">'.$lng_dl['all_dirs'].': '.$path.'</div>';
echo'<div class="menu">'.$lng_dl['all_file_sizes'].': '.size_convert($size).'</div>';
echo'<div class="menu">'.$lng_dl['loads_count'].': '.$count.'</div>';
echo'<div class="phdr"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';

