<?php
/**
 * @package     JohnCMS
 * @link        http://johncms.com
 * @copyright   Copyright (C) 2008-2011 JohnCMS Community
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      http://johncms.com/about
 *
 * @var $lng_dl
 * @var $lng
 */

define('_IN_JOHNCMS', 1);
$textl = 'Закладки';
require_once '../incfiles/head.php';

$lng_dl = core::load_lng('downloads');

if(!$user_id){
    echo'<div class="rmenu">'.$lng_dl['only_authorized'].'</div>';
    echo'<div class="menu"><a href="index.php">'.$lng['back'].'</a></div>';
    include_once '../incfiles/end.php';
    exit;
}

$id = intval($_GET['id']);
$dejst = $_GET['dejst'];
switch($dejst){
    ///////////////////////////////////////////
    /////////// Создание закладок /////////////
    ///////////////////////////////////////////
    case'add':
    echo '<div class="phdr">'.$lng_dl['add_bookmark'].'</div>';
    mysql_query("INSERT INTO `down_bookmarks` SET `user` = '".$user_id."', `file` = '".$id."', `time` = '".time()."';");
    echo'<div class="gmenu">'.$lng_dl['bookmark_added'].'</div>';
    echo'<div class="menu"><a href="index.php">'.$lng['back'].'</a></div>';
    break;
    ///////////////////////////////////////////
    /////////// Удаление закладок /////////////
    ///////////////////////////////////////////
    case'del':
    echo '<div class="phdr">'.$lng_dl['del_bookmark'].'</div>';
    mysql_query("DELETE FROM `down_bookmarks` WHERE `file` = '".$id."' AND `user` = '".$user_id."'");
    echo'<div class="gmenu">'.$lng_dl['bookmark_deleted'].'</div>';
    echo'<div class="menu"><a href="index.php">'.$lng['back'].'</a></div>';
    break;
    ///////////////////////////////////////////
    /////////// Страница закладок /////////////
    ///////////////////////////////////////////
    default:
    echo '<div class="phdr">'.$lng_dl['bookmarks'].':</div>';
    $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `down_bookmarks` WHERE `user` = '".$user_id."'"), 0);
    if($total){
    $zap = mysql_query("SELECT * FROM `down_bookmarks` WHERE `user` = '".$user_id."' ORDER BY `time` DESC LIMIT " . $start . "," . $kmess);
    while ($zap2 = mysql_fetch_array($zap)) {
    echo ($i % 2) ? '<div class="list1">' : '<div class="list2">';
    $i++;

    $filename = mysql_query("select * from `downfiles` where `id` = '".$zap2['file']."';");
    $filename = mysql_fetch_array($filename);
    $filename = explode('||||', $filename['name']);
    $filtime = date("d.m.Y - H:i", $zap2['time']);
    echo $lng_dl['file'].': <a href="file_'.$zap2['file'].'.html">'.$filename[0].'</a><div class="sub">'.$lng_dl['added'].' '.$filtime.'<br/>';
    echo'<a href="index.php?act=bookmarks&amp;dejst=del&amp;id='.$zap2['file'].'">'.$lng_dl['del_bookmark'].'</a></div>';
    echo'</div>';
    }
    echo'<div class="phdr">'.$lng['total'].': '.$total.'</div>';
    if ($total > $kmess)
    {
    	echo '<div class = "phdr">' . functions::display_pagination('index.php?act=bookmarks&amp;', $start, $total, $kmess) . '';
    	echo '</div><div class="menu"><form action="index.php" method="get"><input type="hidden" name="act" value="bookmarks"/><input type="text" name="page" size="2"/><input type="submit" value="'.$lng_dl['to_page'].' &gt;&gt;"/></form></div>';}
    echo '<div class="menu"><a href="index.php">'.$lng['back'].'</a></div>';
    }
    else
    {
        ?>
        <div class="menu">
            <p>
                <?= $lng['list_empty'] ?><br>
                <a href="/download/"><?= $lng['back'] ?></a>
            </p>
        </div>
        <?
    }
    break;
    }
