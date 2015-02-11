<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

defined('_IN_JOHNCMS') or die('Error: restricted access');

$file = intval($_GET['file']);
echo'<div class="phdr">Модерация файлов</div>';
    if($_GET['accept'])
    {
        if($file)
        {
            mysql_query("UPDATE `downfiles` set `status` = '1' WHERE `id` = '".$file."'");
            echo '<div class="gmenu">Файл успешно принят!</div>';
        }
        else
            echo '<div class="rmenu">Не выбран файл для принятия!</div>';
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
            echo'[<a href="admin.php?act=mod&amp;accept=ok&amp;file='.$zap2['id'].'">Принять</a>][<a href="admin.php?act=delete&amp;op=file&amp;id='.$zap2['id'].'">Уд.</a>][<a href="admin.php?act=edit&amp;file='.$zap2['id'].'">Изм.</a>][<a href="loadfile.php?down='.$zap2['way'].'">'.size_convert($siz).'</a>]';
            echo'</div></div>';
            }
        echo'<div class="phdr">Всего: '.$totalfile.'</div>';
        if ($totalfile > $kmess)
        {
            echo '<div class = "phdr">' . functions::display_pagination('admin.php?act=mod&amp;', $start, $totalfile, $kmess) . '';
            echo '</div><form action="admin.php" method="get"><input type="hidden" name="act" value="mod"/><input type="text" name="page" size="2"/><input type="submit" value="К странице &gt;&gt;"/></form>';
        }
    }else{
        echo'<div class="gmenu">Нет файлов на модерации!</div>';
    }


    echo'<div class="menu"><a href="admin.php">Админка</a></div>';

?>