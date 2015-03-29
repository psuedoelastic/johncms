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


$file = intval($_GET['file']);
echo'<div class="phdr">'.$lng_dl['moderation_files'].'</div>';
    if($_GET['accept'])
    {
        if($file)
        {
            mysql_query("UPDATE `downfiles` set `status` = '1' WHERE `id` = '".$file."'");
            echo '<div class="gmenu">'.$lng_dl['file_accepted_success'].'</div>';
        }
        else
            echo '<div class="rmenu">'.$lng_dl['file_not_selected'].'</div>';
    }

$totalfile = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `status` = 0"), 0);
$zap = mysql_query("SELECT * FROM `downfiles` WHERE `status` = 0 ORDER BY `time` DESC LIMIT " . $start . "," . $kmess);
    if($totalfile)
    {
        while ($zap2 = mysql_fetch_array($zap))
        {
            echo ceil(ceil($isssss / 2) - ($isssss / 2)) == 0 ? '<div class="list1">' : '<div class="list2">';
            ++$isssss;
            if (!$zap2['size'])
            {
                $siz = filesize("$loadroot/$zap2[way]");
                mysql_query("UPDATE `downfiles` set `size` = '".$siz."' WHERE `id` = '".$zap2['id']."'");
            }
            else
            {
                $siz = $zap2['size'];
            }
            $namee = explode('||||', $zap2['name']);
            echo '<img src="img/file.gif" alt="."/> <a href="admin.php?act=file&amp;view='.$zap2['id'].'">' . $namee[0] . '</a><div class="sub">';
            echo'[<a href="admin.php?act=mod&amp;accept=ok&amp;file='.$zap2['id'].'">'.$lng_dl['accept'].'</a>][<a href="admin.php?act=delete&amp;op=file&amp;id='.$zap2['id'].'">'.$lng['delete'].'</a>][<a href="admin.php?act=edit&amp;file='.$zap2['id'].'">'.$lng['edit'].'</a>][<a href="loadfile.php?down='.$zap2['way'].'">'.size_convert($siz).'</a>]';
            echo'</div></div>';
            }
        echo'<div class="phdr">'.$lng_dl['all_files'].': '.$totalfile.'</div>';
        if ($totalfile > $kmess)
        {
            echo '<div class = "phdr">' . functions::display_pagination('admin.php?act=mod&amp;', $start, $totalfile, $kmess) . '';
            echo '</div><form action="admin.php" method="get"><input type="hidden" name="act" value="mod"/><input type="text" name="page" size="2"/><input type="submit" value="'.$lng_dl['to_page'].' &gt;&gt;"/></form>';
        }
    }else{
        echo'<div class="gmenu">'.$lng['list_empty'].'</div>';
    }


    echo'<div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';

