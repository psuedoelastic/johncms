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

if ($_POST['submit'])
{
    $f = functions::check($_POST['name']);
    include_once 'classes/pclzip.lib.php';
    echo '<div class="phdr">'.$lng_dl['mass_file_del'].'</div>';

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
            $ext = $zip->delete(PCLZIP_OPT_BY_NAME, $f);
            if (!$ext)
            {
                echo '<div class="rmenu">'.$lng_dl['file_not_delete_from_archive'].' ' . $loadroot . '/' . $zap2['way'] . '</div>';
            }
        }
    }
    echo '<div class="gmenu">'.$lng_dl['delete_from_archive_completed'].' ' . $ok . '</div>';

}
else
{
    echo '<div class="phdr">'.$lng_dl['mass_file_del'].'</div>';
    echo '<form action="admin.php?act=zipdel&amp;cat=' . $cat . '" method="post">
    <div class="menu">
    '.$lng_dl['file_name_in_archive'].':<br/>
    <input type="text" name="name"/></div><div class="menu">
    <input type="submit" name="submit" value="'.$lng_dl['next'].'"/></div></form>';

}
echo '<div class="gmenu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
