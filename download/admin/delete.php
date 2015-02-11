<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

/////////////////////////////////////////
// Удаление скрина, темы, папки, файла //
/////////////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');

$id = intval($_GET['id']);
$msg = array('screen' => 'скриншота', 'theme' => 'темы', 'folder' => 'папки',
    'file' => 'файла');
echo '<div class="phdr">Удаление '.$msg[$_GET['op']].'</div>';
if ($_GET['very'])
{
    if ($rights < 9)
    {
        echo '<div class="rmenu">У вас нет прав на удаление! Возможно вы не администратор...</div>';
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
                echo '<div class="rmenu">Такого скриншота не существует в базе!</div>';
                require_once ('../incfiles/end.php');
                exit;
            }
            $adrfile = mysql_fetch_array($delfile);
            unlink("$screenroot/$adrfile[way]");
            mysql_query("DELETE FROM `downscreen` WHERE `id` = '".$id."' LIMIT 1");
            echo '<div class="gmenu">Скриншот успешно удалён!</div>';
            echo '<div class="menu"><a href="admin.php?act=file&amp;view='.$_GET['file'].'">К файлу</a></div>';
            
            break;


        case 'theme':
            // Удаление темы обсуждения //
            echo '<div class="phdr">Удаляем тему обсуждения</div>';
            if (mysql_query("UPDATE `downfiles` SET `themeid`='0' WHERE `id`='".$id."';"))
            {
                echo '<div class="gmenu">Тема обсуждения откреплена от файла! Удалите её на форуме если требуется</div>';
            }

            break;


        case 'folder':
            // Удаление папки //
            $delcat = mysql_query("select * from `downpath` where id = '".$id."';");
            $delcat1 = mysql_num_rows($delcat);
            if (!$delcat1)
            {
                echo '<div class="rmenu">Такой папки не существует в базе!</div>';
                echo '<div class="menu"><a href="admin.php?act=folder">Управление файлами и папками</a><br/>';
                echo '<a href="admin.php">Админка</a></div>';
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
            echo '<div class="gmenu">Выбранная папка успешно удалена!</div>';

            break;

        case 'file':
            // Удаление файла //
            $delfile = mysql_query("select * from `downfiles` where id = '".$id."';");
            $delfile1 = mysql_num_rows($delfile);
            if (!$delfile1)
            {
                echo '<div class="rmenu">Такого файла не существует в базе!</div>';
                require_once ('../incfiles/end.php');
                exit;
            }
            $adrfile = mysql_fetch_array($delfile);

            if (!unlink("$loadroot/$adrfile[way]"))
            {
                echo '<div class="rmenu">Не удалось удалить файл из файловой системы! Удалите его вручную! <b>'.
                    $loadroot.'/'.$adrfile[way].'</b></div>';
            }else{
                echo '<div class="gmenu">Удаление из файловой системы прошло успешно!</div>';
            }
            if (mysql_query("DELETE FROM `downfiles` WHERE `id` = '".$id."' LIMIT 1")) 
                    echo '<div class="gmenu">Удаление из базы прошло успешно!</div>';

            $delscreen = mysql_query("select * from `downscreen` where `fileid` = '".$id.
                "';");
            while ($adrscreen = mysql_fetch_array($delscreen))
            {
                unlink("$screenroot/$adrscreen[way]");
            }
            mysql_query("DELETE FROM `downscreen` WHERE `fileid` = '".$file."'");
            echo '<div class="gmenu">Скриншоты удалены!</div>';

            break;

    }

    echo '<div class="menu"><a href="admin.php?act=folder">Управление файлами и папками</a></div>';
    echo '<div class="menu"><a href="admin.php">Админка</a></div>';

} else
{
    echo '<div class="rmenu">Подтвердить удаление '.$msg[$_GET['op']].'?</div>';
    echo '<div class="menu"><a href="admin.php?act=delete&amp;id='.$id.
        '&amp;very=true&amp;op='.$_GET['op'].'&amp;file='.$_GET['file'].'">Да, удалить!</a> | ';
    echo '<a href="admin.php">Нет, не нужно!</a></div>';
    echo '<div class="menu"><a href="admin.php?act=folder">Управление файлами и папками</a><br/>';
    echo '<a href="admin.php">Админка</a></div>';
}

?>