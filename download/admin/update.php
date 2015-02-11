<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/
///////////////////////////////////
// Проверка существования файлов //
///////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');

    $cat = intval($_GET['cat']); // Каталог который надо проверить
   echo'<div class="phdr">Очистка базы от несуществующего</div>';
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
        echo '<div class="gmenu">Удалено файлов: '.$counf.'</div>';
        echo '<div class="gmenu">Удалено папок: '.$counp.'</div>';
        auto_clean_cache(); // Чистим кэш счётчиков
    }
    else
    {
        echo '<div class="gmenu">Не найдено файлов: '.$counf.'</div>';
        echo '<div class="gmenu">Не найдено папок: '.$counp.'</div>';
        if($counf+$counp > 0)
        {
            echo'<div class="menu">Подтвердить удаление файлов/папок из базы?</div>';
            echo '<div class="gmenu"><a href="admin.php?act=update&amp;cat='.$cat.'&amp;ver=1">Да</a> | <a href="admin.php">Нет</a></div>';
        }
    }
    echo '<div class="gmenu"><a href="admin.php">Админка</a></div>';

?>