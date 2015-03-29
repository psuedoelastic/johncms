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

    $cat = intval($_GET['cat']); // Каталог который надо проверить
   echo'<div class="phdr">'.$lng_dl['clean_base'].'</div>';
   $zap = mysql_query("SELECT * FROM `downpath` WHERE `refid` = '" . $cat . "'");
   $counp = 0;
   $counf = 0;
   while ($zap2 = mysql_fetch_array($zap))
   {
        $listf = mysql_query("SELECT * FROM `downfiles` WHERE `way` LIKE '" . $zap2['way'] ."%' ");
        $listp = mysql_query("SELECT * FROM `downpath` WHERE `way` LIKE '" . $zap2['way'] ."%';");
        while ($delf1 = mysql_fetch_array($listf))
        {   if (!is_file("$loadroot/$delf1[way]"))
            {
                if($_GET['ver'])
                mysql_query("DELETE FROM `downfiles` WHERE `id` = '".$delf1['id']."' LIMIT 1");
                $counf++;
            }
        }
        while ($delp1 = mysql_fetch_array($listp))
        {
            if (!is_dir("$loadroot/$delp1[way]"))
            {
                if($_GET['ver'])
                mysql_query("DELETE FROM `downpath` WHERE `id` = '".$delp1['id']."' LIMIT 1");
                $counp++;
            }
        }
    }
    if($_GET['ver'])
    {
        echo '<div class="gmenu">'.$lng_dl['deleted_files'].': '.$counf.'</div>';
        echo '<div class="gmenu">'.$lng_dl['deleted_sections'].': '.$counp.'</div>';
        auto_clean_cache(); // Чистим кэш счётчиков
    }
    else
    {
        echo '<div class="gmenu">'.$lng_dl['not_found_files'].': '.$counf.'</div>';
        echo '<div class="gmenu">'.$lng_dl['not_found_sections'].': '.$counp.'</div>';
        if($counf+$counp > 0)
        {
            echo'<div class="menu">'.$lng_dl['really_delete'].'</div>';
            echo '<div class="gmenu"><a href="admin.php?act=update&amp;cat='.$cat.'&amp;ver=1">'.$lng_dl['yes_delete'].'</a> | <a href="admin.php">'.$lng_dl['not_delete'].'</a></div>';
        }
    }
    echo '<div class="gmenu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
