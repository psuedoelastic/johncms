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
 */

define('_IN_JOHNCMS', 1);
$headmod = 'loadtop';
require_once '../incfiles/core.php';
require_once 'functions.php';
$textl = $lng_dl['user_rating'];
require_once '../incfiles/head.php';
echo'<div class="phdr">'.$lng_dl['user_rating'].'</div>';
$query = mysql_query("SELECT * FROM `downfiles` WHERE `user_id` != 0 GROUP BY `user_id` ORDER BY COUNT(`user_id`)");
$total = mysql_num_rows($query);
$query = mysql_query("SELECT * FROM `downfiles` WHERE `user_id` != 0 GROUP BY `user_id` ORDER BY COUNT(`user_id`) DESC LIMIT " . $start . "," . $kmess);
if($total)
{
   while($arr = mysql_fetch_array($query))
   {
       echo ($i % 2) ? '<div class="list1">' : '<div class="list2">';
       $i++;
       $countt = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `user_id` = '".$arr['user_id']."'"), 0);
       $req = mysql_query("SELECT * from `users` where id = '".$arr['user_id']."';");
        $res = mysql_fetch_array($req);
       $arg = array ('stshide' => 1,
       'sub' => $lng_dl['all_files'].': '.$countt);
       echo functions::display_user($res, $arg) . '</div>';
   }

    echo'<div class="phdr">'.$lng_dl['all'].': '.$total.'</div>';
    if($total > $kmess)
    {
        echo'<div class="menu">';
        echo '' . functions::display_pagination('top_user.php?', $start, $total, $kmess) . '';
        echo '<form action="top_user.php" method="get"><input type="text" name="page" size="2"/><input type="submit" value="'.$lng_dl['to_page'].' &gt;&gt;"/></form>';
        echo'</div>';
    }
}
else
{
    echo'<div class="rmenu">'.$lng_dl['empty_list'].'</div>';
}
echo '<div class="gmenu"><a href="index.html">'.$lng['back'].'</a></div>';
require_once ('../incfiles/end.php');
