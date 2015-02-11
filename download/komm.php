<?php
/*
Скрипт загруз центра под JohnCMS 3.0
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://simba-club.ru
R866920725287
Z117468354234
*/

define('_IN_JOHNCMS', 1);
$headmod = 'load';
require_once '../incfiles/core.php';
require_once 'functions.php';
$textl = 'Загруз-Центр / Комментарии!';
require_once '../incfiles/head.php';
$id = intval($_GET['id']);
$komid = intval($_GET['komid']);
$act = isset($_GET['act']) ? $_GET['act'] : '';
if (!$down_setting['komm'])
{
    echo '<div class="rmenu">Ошибка!<br/>
    Комментарии отключены!</div>
    <div class="gmenu"><a href="index.php?">К категориям</a></div>';
    include_once '../incfiles/end.php';
    exit;   
}
    
    
    
    switch ($act)
    {
        
    /////// Одобрение ///////////
    case 'yes':
    echo'<div class="phdr">Результат:</div>';
    $plus = mysql_query("select * from `downkomm` where id = '" . $komid . "';");
    $pluss = mysql_fetch_array($plus);
    $idd = explode('|',$pluss['golos']);
    
    if (!$user_id){
    echo '<div class="rmenu">Ошибка!<br/>
    Вы не авторизованы!</div>
    <div class="menu"><a href="index.php?">К категориям</a></div>';
    include_once '../incfiles/end.php';
    exit;
    }
    
    if(in_array($user_id, $idd)){
    echo'<div class="rmenu">Вы уже голосовали за данный комментарий!</div>';
    echo'<div class="menu"><a href="file_' . $id . '.html">К файлу</a></div>';
    include_once '../incfiles/end.php';
    exit;
        }
    
    if($user_id == $pluss['userid']){
    echo'<div class="rmenu">Вы не можете голосовать за свой комментарий!</div>';
    echo'<div class="menu"><a href="file_' . $id . '.html">К файлу</a></div>';
    include_once '../incfiles/end.php';
    exit;
        }
    
    $plusadin = $pluss['plus'];
    $plusadin++;
    if(!$pluss['golos']){
        $goll = $user_id;
    }else{ 
        $goll = $pluss['golos'].'|'.$user_id;
    }
    
    mysql_query("update `downkomm` set `plus` = '" . $plusadin . "', `golos` = '".$goll."' where `id` = '" . $komid . "';");
    echo'<div class="gmenu">Голос принят!</div>';
    echo'<div class="menu"><a href="file_' . $id . '.html">К файлу</a></div>';
    break;
    
    
    
    /////////// Против /////////////
    case 'no':
    
    echo'<div class="phdr">Результат:</div>';
    $plus = mysql_query("select * from `downkomm` where id = '" . $komid . "';");
    $pluss = mysql_fetch_array($plus);
    $idd = explode('|',$pluss['golos']);
    if (!$user_id){
    echo '<div class="rmenu">Ошибка!<br/>
    Вы не авторизованы!</div>
    <div class="menu"><a href="index.php?">К категориям</a></div>';
    include_once '../incfiles/end.php';
    exit;   
    }
    
    if(in_array("$user_id", $idd)){
    echo'<div class="rmenu">Вы уже голосовали за данный комментарий!</div>';
    echo'<div class="menu"><a href="file_' . $id . '.html">К файлу</a></div>';
    include_once '../incfiles/end.php';
    exit;
        }
    
    if($user_id == $pluss['userid']){
    echo'<div class="rmenu">Вы не можете голосовать за свой комментарий!</div>';
    echo'<div class="menu"><a href="file_' . $id . '.html">К файлу</a></div>';
    include_once '../incfiles/end.php';
    exit;
        }
    
    $plusadin = $pluss['minus'];
    $plusadin++;
    if(!$pluss['golos']){
        $goll = $user_id.'|';
    }else{ 
        $goll = $pluss['golos'].$user_id;
    }
    
    mysql_query("update `downkomm` set `minus` = '" . $plusadin . "', `golos` = '".$goll."' where `id` = '" . $komid . "';");
    echo'<div class="gmenu">Голос принят!</div>';
    echo'<div class="menu"><a href="file_' . $id . '.html">К файлу</a><div/>';
    
    break;
    
    ///////// Кто голосовал ////////////
    case 'who':
    $plus = mysql_query("select * from `downkomm` where id = '" . $komid . "';");
    $pluss = mysql_fetch_array($plus);
    if($pluss['golos'] != ""){
    $idd = explode('|',$pluss['golos']);
    $result = count($idd);
    }else{
    $result = 0;    
    }
    echo '<div class="phdr">Проголосовали '.$result.' человек</div><div class="menu">';
    
    for($i = 0; $i <= $result; $i++){
        $req = mysql_query("SELECT * from `users` where id = '".$idd[$i]."';");
        $res = mysql_fetch_array($req);
        if (!empty($user_id) && ($user_id != $res['id'])){
        //TODO:Под четвёрку переделать ссылку
        echo '<a href="../str/anketa.php?id=' . $res['id'] . '"><b>' . $res['name'] . '</b></a>, ';
        }else{
        
        echo '<b>' . $res['name'] . '</b>, ';
        }
    }
    
    echo '</div><div class="menu"><a href="file_' . $id . '.html">К файлу</a></div>';
    break;
    
    
    
    //////////////////// Добавление комментария ///////////
    case 'add':
    echo'<div class="phdr">Добавление комментария</div>';
    if (!$user_id)
    {
        echo '<div class="rmenu">Ошибка!<br/>
        Вы не авторизованы!</div>
        <div class="menu"><a href="index.php?">К категориям</a></div>';
        include_once '../incfiles/end.php';
        exit;
    }
    
    if (isset($_POST['submit']))
    {
        
    if(empty($_POST['txt']))
    {
        echo'<div class="rmenu">Вы не ввели комментарий!</div>';
        echo'<div class="menu"><a href="komm.php?id='.$id.'">К комментариям</a></div>';
        include_once '../incfiles/end.php';
        exit;
    }
    
    mysql_query("INSERT INTO `downkomm` (`id`, `fileid`, `time`, `userid`, `text`, `plus`, `minus`, `golos`) VALUES (NULL, '".$id."', '".time()."', '".$user_id."', '".mysql_real_escape_string($_POST['txt'])."', '0', '0', '');");    
    $req = mysql_query("SELECT * from `users` where id = '".$user_id."';");
    $res = mysql_fetch_array($req);
    $fpst = $res['komm'] + 1;
    mysql_query("UPDATE `users` SET `komm`='" . $fpst . "' WHERE `id`='" . $user_id . "';");


        if($down_setting['priv'] > 0)
        {
            $text_mail = 'К файлу в загрузках оставили комментарий. [url='.core::$system_set['homeurl'].'/download/komm.php?id='.$id.']Смотреть[/url]';
            mysql_query("INSERT INTO `cms_mail` SET
                    `user_id` = '0',
                    `from_id` = '" . $down_setting['priv_user'] . "',
                    `text` = '" . mysql_real_escape_string($text_mail) . "',
                    `time` = '" . time() . "',
                    `sys` = '1'") or die(mysql_error());
        }

    
    echo'<div class="gmenu">Комментарий успешно добавлен!</div>
    <div class="menu"><a href="komm.php?id='.$id.'">К комментариям</a></div>';
    
    }else{
        
    $set_download = unserialize($datauser['set_forum']);
    
    echo '<form action="komm.php?act=add&amp;id=' . $id . '" name="komm" method="post">
    <div class="menu">Текст комментария:<br/>';
    if(!$is_mobile)
    echo bbcode::auto_bb('komm', 'txt');
    echo '<textarea cols="' . $set_user['field_w'] . '" rows="' . $set_user['field_h'] . '" name="txt"></textarea></div>
    <div class="menu">
    <input type="submit" name="submit" value="Написать"/></div>
    </form>';
    echo'<div class="menu"><a href="komm.php?id='.$id.'">К комментариям</a></div>';
    echo '<div class="menu"><a href="file_' . $id . '.html">К файлу</a></div>';
    }
    break;
    
    
    
    
    //////////////// Удаление комментария ////////////////
    case 'del':
    if ($rights >= 1){
    mysql_query("DELETE FROM `downkomm` WHERE `id` = '".$komid."' LIMIT 1");
    echo '<div class="gmenu">Удалено!</div>';
    echo '<div class="menu"><a href="file_' . $id . '.html">К файлу</a></div>';
    }else{
        echo '<div class="rmenu">Нет доступа!</div>';
    }
    break;
    
    
    
    
    //////////// Вывод комментариев ////////////
    default:
    $set_download = unserialize($datauser['set_forum']);
    
    echo'<div class="phdr">Комментарии </div>';    
    $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `downkomm` WHERE `fileid` = '" . $id . "';"), 0);
    if ($total > 0) {
    $query = mysql_query("select * from `downkomm` where fileid='" . $id . "' ORDER BY `time` ASC LIMIT " . $start . "," . $kmess);
    while($arr = mysql_fetch_array($query)){
    echo ($i % 2) ? '<div class="list1">' : '<div class="list2">';
    $i++;
    $res = mysql_fetch_array(mysql_query("SELECT * from `users` where id = '".$arr['userid']."';"));      
    
    $text = functions::checkout($arr['text'], 1, 1);
    $text = functions::smileys($text);
    $vrp = $arr['time'] + $sdvig * 3600;
    $idd = explode('|',$arr['golos']);
    $vr = date("d.m.y / H:i", $vrp);
    $upr = '';
    if($rights >= 1)
    $upr .= '<a href="?act=del&amp;komid='.$arr['id'].'&amp;id='.$id.'">[Удалить]</a> ';
    
    if(!in_array($user_id, $idd) && ($user_id != $arr['userid']))
    $upr .= 'Одобрение: <a href="?act=yes&amp;komid='.$arr['id'].'&amp;id='.$id.'">За ['.$arr['plus'].']</a> | <a href="?act=no&amp;komid='.$arr['id'].'&amp;id='.$id.'">Против ['.$arr['minus'].']</a>'; 
    else
    $upr .= 'Одобрение: За ['.$arr['plus'].'] | Против ['.$arr['minus'].']';
    $upr .= ' / <a href="?act=who&amp;komid='.$arr['id'].'&amp;id='.$id.'">Кто голосовал?</a>';
    $arg = array ('stshide' => 1, 'header' => '('.$vr.')',
     'body' => $text,
   'sub' => $upr);
    echo functions::display_user($res, $arg) . '</div>';
    } 
    }else
    echo'<div class="rmenu">Комментариев ещё нет! Ваш будет первым!</div>';
    
    if($user_id)
    echo '<div class="menu"><form action="komm.php?act=add&amp;id='.$id.'" method="post"><input type="submit" name="add" value="Написать"/></form></div>';
    else
    echo '<div class="rmenu">Комментировать могут только зарегистрированные пользователи!</div>';
    
    echo'<div class="phdr">Всего комментариев: '.$total.'</div>';
    
    if ($total > $kmess){
   	echo '<div class="menu">' . functions::display_pagination('komm.php?id='.$id.'&amp;', $start, $total, $kmess) . '';
   	echo '<form action="komm.php" method="get"><input type="hidden" name="id" value="'.$id.'"/><input type="text" name="page" size="2"/><input type="submit" value="К странице &gt;&gt;"/></form></div>';
    }
    echo'<div class="gmenu"><a href="file_' . $id . '.html">К файлу</a></div>';
    break;
}

require_once '../incfiles/end.php';   
    
?>