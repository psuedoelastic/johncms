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
require_once '../incfiles/core.php';
require_once 'functions.php';
$textl = 'Рейтинг пользователей';
require_once '../incfiles/head.php';
echo'<div class="phdr">Рейтинг пользователей</div>';
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
       'sub' => 'Всего файлов: '.$countt);
       echo functions::display_user($res, $arg) . '</div>';
   }

    echo'<div class="phdr">Всего: '.$total.'</div>';
    if($total > $kmess)
    {
        echo'<div class="menu">';
        echo '' . functions::display_pagination('top_user.php?', $start, $total, $kmess) . '';
        echo '<form action="top_user.php" method="get"><input type="text" name="page" size="2"/><input type="submit" value="К странице &gt;&gt;"/></form>';
        echo'</div>';
    }
}
else
{
    echo'<div class="rmenu">Не найдено пользователей для постороения рейтинга...</div>';
}
echo '<div class="gmenu"><a href="index.html">В загруз-центр</a></div>';
require_once ('../incfiles/end.php');
?>