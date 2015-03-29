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


$cat = intval($_GET['cat']);
$ver = intval($_GET['ver']);
if ($ver) {
    include_once 'classes/pclzip.lib.php';
    echo '<div class="phdr">'.$lng_dl['add_file_to_archive'].'</div>';
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
                echo '<div class="rmenu">'.$lng_dl['file_not_added_to_arch'].' ' . $loadroot . '/' . $zap2['way'] . '</div>';
            }
        }
    }
    echo '<div class="gmenu">'.$lng_dl['file_add_operation_completed'].' ' . $ok . '</div>';
}
else
{
    echo '<div class="menu">'.$lng_dl['add_file_to_archive'].'<br/>'.$lng_dl['add_file_to_archive_warn'].'</div>
    <div class="gmenu"><a href="admin.php?act=zipman&amp;ver=1&amp;cat=' . $cat . '">'.$lng_dl['continue'].'</a> | <a href="admin.php">'.$lng_dl['abort'].'</a></div>';
}

echo '<div class="gmenu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';