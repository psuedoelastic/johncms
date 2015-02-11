<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

define('_IN_JOHNCMS', 1);
$headmod = 'loadtop';
$textl = 'Загруз-Центр / Топ файлов';
require_once '../incfiles/core.php';
require_once 'functions.php';
$cat = intval($_GET['cat']);

$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

require_once '../incfiles/head.php';
echo'<div class="phdr">Топ файлов</div>';

$cat_inf = mysql_query("SELECT * FROM `downpath` WHERE `id` = '" . $cat . "' LIMIT 1");

if(mysql_num_rows($cat_inf)){
    $cat_inf = mysql_fetch_assoc($cat_inf);
}else{
    $cat_inf = array('way' => '');
}

$totalfile = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles`  WHERE `type` != 1 AND `status` = 1 && `way` LIKE '" . $cat_inf['way'] ."%'"), 0);


        if($sort){
        $_SESSION['downsor'] = $sort;
        }else{
        $sort = isset($_SESSION['downsor']) ? $_SESSION['downsor'] : "";
        }
         switch ($sort){
         case "rating":
         $zap = mysql_query("SELECT * FROM `downfiles` WHERE `type` != 1 AND `status` = 1 && `way` LIKE '" . $cat_inf['way'] ."%' ORDER BY `rating` DESC LIMIT " . $start . "," . $kmess);
         echo'<div class="menu">Сортировка: <a href="top.html?cat='.$cat.'&amp;sort=count">Скачивания</a> | <b>Рейтинг</b></div>';
         break;
         default:
         $zap = mysql_query("SELECT * FROM `downfiles` WHERE `type` != 1 AND `status` = 1 && `way` LIKE '" . $cat_inf['way'] ."%' ORDER BY `count` DESC LIMIT " . $start . "," . $kmess);
         echo'<div class="menu">Сортировка: <b>Скачивания</b> | <a href="top.html?cat='.$cat.'&amp;sort=rating">Рейтинг</a></div>';
         break;
         }

while ($zap2 = mysql_fetch_array($zap))
{
    echo ($i % 2) ? '<div class="list1">' : '<div class="list2">';
    ++$i;
    $tf = pathinfo($zap2['way'], PATHINFO_EXTENSION); // Тип файла
    if($tf == 'mp3'){
        $set_view = array('variant' => 1, 'way_to_path' => 1);
    }elseif((($tf == 'thm' or $tf == 'nth') && $down_setting['tmini']) || (($tf == '3gp' or $tf == 'mp4' or $tf == 'avi') && $down_setting['vmini']) || ($tf == 'jpg' or $tf == 'png' or $tf == 'jpeg' or $tf == 'gif')){
        $set_view = array('link_download' => 1, 'div' => 1, 'way_to_path' => 1);
    }else{
        $set_view = array('variant' => 1,
        'size' => 1, 'desc' => 1, 'count' => 1, 'div' => 1,
        'comments' => 1, 'add_date' => 1, 'rating' => 1, 'way_to_path' => 1);
    }
    echo f_preview($zap2, $set_view, $tf);
    echo '</div>';
}

if ($totalfile > $kmess)
{
    echo '<div class="phdr">' . functions::display_pagination('top.html?cat='.$cat.'&amp;', $start, $totalfile, $kmess) . '</div>';
    echo '<div class="menu"><form action="top.html" method="get"><input type="hidden" name="cat" value="'.$cat.'"/><input type="text" name="page" size="2"/><input type="submit" value="К странице &gt;&gt;"/></form></div>';
}

echo '<div class="menu"><a href="index.html">К категориям</a></div>';

require_once '../incfiles/end.php';
?>