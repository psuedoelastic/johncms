<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/
/////////////////////////////////
/////// Чистильщик кэша /////////
/////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');

$op = $_GET['op'];
echo'<div class="phdr">Очистка кэша ';
if($op == 'screen'){ 
    echo 'скриншотов';
    $path = 'graftemp/';
    }else{
    echo 'счётчиков';
    $path = 'cache/';
    }
echo '</div>';
$dir = scandir($path);
// Смотрим папку
$ii = count($dir);
// Считаем элементы
for($i = 3; $i<$ii; $i++){
if (is_file($path . $dir[$i]))
unlink($path . $dir[$i]);
// Перебираем и удаляем если файл.
}

$i = $i-3;
echo'<div class="gmenu">Удалено: '.$i.' файл(ов)</div>';
echo'<div class="menu"><a href="admin.php">Админка</a></div>';



?>