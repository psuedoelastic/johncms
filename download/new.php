<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

$lng_dl = core::load_lng('downloads');

defined('_IN_JOHNCMS') or die('Error:restricted access');
$textl = $lng_dl['downloads'].' / '.$lng_dl['last_100_files'];
require_once '../incfiles/head.php';
echo '<div class="phdr"><img src="img/new.png" alt="."/> '.$lng_dl['last_100_files'].'</div>';
$cat = intval($_GET['cat']);

$cat_inf = mysql_query("SELECT * FROM `downpath` WHERE `id` = '" . $cat . "' LIMIT 1");

if(mysql_num_rows($cat_inf)){
    $cat_inf = mysql_fetch_assoc($cat_inf);
}else{
    $cat_inf = array('way' => '');
}

$totalfile = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles`  WHERE `type` != 1 AND `status` = 1 && `way` LIKE '" . $cat_inf['way'] ."%'"), 0);
if($totalfile > 100){ $totalfile = 100; }
$zap = mysql_query("SELECT * FROM `downfiles` WHERE `type` != 1 AND `status` = 1 && `way` LIKE '" . $cat_inf['way'] ."%' ORDER BY `time` DESC LIMIT " . $start . "," . $kmess);
while ($zap2 = mysql_fetch_array($zap)) {
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


if ($totalfile > $kmess){
    	echo '<div class = "phdr">' . functions::display_pagination('index.php?act=new&amp;cat='.$cat.'&amp;', $start, $totalfile, $kmess) . '';
    	echo '</div><div class="menu"><form action="index.php" method="get"><input type="hidden" name="act" value="new"/><input type="hidden" name="cat" value="'.$cat.'"/><input type="text" name="page" size="2"/><input type="submit" value="'.$lng_dl['to_page'].' &gt;&gt;"/></form></div>';}

echo '<div class="menu"><a href="index.html">'.$lng['back'].'</a></div>';

