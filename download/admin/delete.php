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


$id = intval($_GET['id']);
$msg = array(
    'screen' => $lng_dl['delete_screen'],
    'theme' => $lng_dl['delete_theme'],
    'folder' => $lng_dl['delete_section'],
    'file' => $lng_dl['delete_file']
);
echo '<div class="phdr">'.$msg[$_GET['op']].'</div>';
if ($_GET['very'])
{
    if ($rights < 9)
    {
        echo '<div class="rmenu">'.$lng_dl['access_denied'].'</div>';
        unset($_GET['op']);
    }
    switch ($_GET['op'])
    {
        case 'screen':
            // Удаление скриншота //
            $delfile = mysql_query("select * from `downscreen` where id = '".$id."';");
            $delfile1 = mysql_num_rows($delfile);
            if ($delfile1 == 0)
            {
                echo '<div class="rmenu">'.$lng_dl['screen_not_found'].'</div>';
                require_once ('../incfiles/end.php');
                exit;
            }
            $adrfile = mysql_fetch_array($delfile);
            unlink("$screenroot/$adrfile[way]");
            mysql_query("DELETE FROM `downscreen` WHERE `id` = '".$id."' LIMIT 1");
            echo '<div class="gmenu">'.$lng_dl['screen_deleted'].'</div>';
            echo '<div class="menu"><a href="admin.php?act=file&amp;view='.$_GET['file'].'">'.$lng_dl['back_to_file'].'</a></div>';
            
            break;


        case 'theme':
            // Удаление темы обсуждения //
            if (mysql_query("UPDATE `downfiles` SET `themeid`='0' WHERE `id`='".$id."';"))
            {
                echo '<div class="gmenu">'.$lng_dl['theme_deleted'].'</div>';
            }

            break;


        case 'folder':
            // Удаление папки //
            $delcat = mysql_query("select * from `downpath` where id = '".$id."';");
            $delcat1 = mysql_num_rows($delcat);
            if (!$delcat1)
            {
                echo '<div class="rmenu">'.$lng_dl['dir_not_found'].'</div>';
                echo '<div class="menu"><a href="admin.php?act=folder">'.$lng_dl['structure_manage'].'</a><br/>';
                echo '<a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
                require_once ('../incfiles/end.php');
                exit;
            }
            $adrdir = mysql_fetch_array($delcat);
            simba_delcat($loadroot.'/'.$adrdir[way]); // сносим папку
            mysql_query("DELETE FROM `downpath` WHERE `way` LIKE '".$adrdir[way]."%'"); //// Сносим папки принадлежащие удаляемой папке

            $file = mysql_query("SELECT * FROM `downfiles` WHERE `way` LIKE '".$adrdir[way].
                "%'"); //// Сносим файлы принадлежащие удаляемой папке
            while ($file2 = mysql_fetch_array($file))
            {
                $delfile = mysql_query("select * from `downscreen` where `fileid` = '".$file2['id'].
                    "';");
                while ($delfile1 = mysql_fetch_array($delfile))
                {
                    unlink("$screenroot/$delfile1[way]");
                    mysql_query("DELETE FROM `downscreen` WHERE `id` = '".$delfile1['id'].
                        "' LIMIT 1");
                }
                mysql_query("DELETE FROM `downfiles` WHERE `id` = '".$file2['id']."'");
            }
            echo '<div class="gmenu">'.$lng_dl['dir_deleted'].'</div>';

            break;

        case 'file':
            // Удаление файла //
            $delfile = mysql_query("select * from `downfiles` where id = '".$id."';");
            $delfile1 = mysql_num_rows($delfile);
            if (!$delfile1)
            {
                echo '<div class="rmenu">'.$lng_dl['file_not_found'].'</div>';
                require_once ('../incfiles/end.php');
                exit;
            }
            $adrfile = mysql_fetch_array($delfile);

            if (!unlink("$loadroot/$adrfile[way]"))
            {
                echo '<div class="rmenu">'.$lng_dl['file_not_deleted_from_fs'].' <b>'.
                    $loadroot.'/'.$adrfile[way].'</b></div>';
            }else{
                echo '<div class="gmenu">'.$lng_dl['file_deleted_success'].'</div>';
            }
            if (mysql_query("DELETE FROM `downfiles` WHERE `id` = '".$id."' LIMIT 1")) 
                    echo '<div class="gmenu">'.$lng_dl['deleted_from_database_success'].'</div>';

            $delscreen = mysql_query("select * from `downscreen` where `fileid` = '".$id.
                "';");
            while ($adrscreen = mysql_fetch_array($delscreen))
            {
                unlink("$screenroot/$adrscreen[way]");
            }
            mysql_query("DELETE FROM `downscreen` WHERE `fileid` = '".$file."'");
            echo '<div class="gmenu">'.$lng_dl['screens_deleted'].'</div>';

            break;

    }

    echo '<div class="menu"><a href="admin.php?act=folder">'.$lng_dl['structure_manage'].'</a></div>';
    echo '<div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';

} else
{
    echo '<div class="rmenu">'.$msg[$_GET['op']].'?</div>';
    echo '<div class="menu"><a href="admin.php?act=delete&amp;id='.$id.
        '&amp;very=true&amp;op='.$_GET['op'].'&amp;file='.$_GET['file'].'">'.$lng_dl['yes_delete'].'</a> | ';
    echo '<a href="admin.php">'.$lng_dl['not_delete'].'</a></div>';
    echo '<div class="menu"><a href="admin.php?act=folder">'.$lng_dl['structure_manage'].'</a><br/>';
    echo '<a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
}

?>