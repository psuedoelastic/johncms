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
   echo'<div class="phdr">'.$lng_dl['file_size_check'].'</div>';
   $zap = mysql_query("SELECT * FROM `downfiles`");
   $i = 0;
   $bad = 0;
   while ($zap2 = mysql_fetch_array($zap))
   {
        if(is_file("$loadroot/$zap2[way]"))
        {
            $siz = filesize("$loadroot/$zap2[way]");
            if($siz != $zap2['size'])
            mysql_query("UPDATE `downfiles` set `size` = '" . $siz . "' WHERE `id` = '" . $zap2['id'] . "'");
        }
        else
        {
            $bad++;
        }
        $i++;
   }
    echo '<div class="gmenu">'.$lng_dl['file_size_checked'].': '.$i.' '.$lng_dl['files_not_found'].': '.$bad.'</div>';
    echo '<div class="gmenu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';

