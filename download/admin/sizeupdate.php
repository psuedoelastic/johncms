<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/
//////////////////////////////////////////////////
////// проверка размеров файлов и запись /////////
//////////////////////////////////////////////////

defined('_IN_JOHNCMS') or die('Error: restricted access');

    $cat = intval($_GET['cat']);
   echo'<div class="phdr">Проверяем размер файлов</div>';
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
    echo '<div class="gmenu">Размер обновлен! Проверено файлов: '.$i.' из них не найдено: '.$bad.'</div>';
    echo '<div class="gmenu"><a href="admin.php">Админка</a></div>';

