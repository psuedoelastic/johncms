<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
Сайт: http://symbos.su
R866920725287
Z117468354234
*/
/////////////////////////////////
/////// Обновление базы /////////
/////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');

$cat = intval($_GET['cat']);
echo'<div class="phdr">Полное обновление базы</div>';
function updateall($cat){
    global $screenroot, $login;
    $countp = 0;
    $zap = mysql_query("SELECT * FROM `downpath` WHERE `id` = '" . $cat ."';");
    $delf1 = mysql_fetch_array($zap);
    $dir = 'files/'.$delf1['way'];
    $result = scandir($dir);
    $ii = count($result);
    
    for($i = 2; $i<$ii; $i++){
    if (is_dir($dir.$result[$i])  && $result[$i] != ".." && $result[$i] != "."){ //// Определяем папки.
    $zapp = mysql_query("SELECT * FROM `downpath` WHERE `way` = '" . $delf1['way'].$result[$i] ."/';");
    $count = mysql_num_rows($zapp);
    $arr = mysql_fetch_array($zapp);
    $countp++;
    if($count == 0){
    $countob++;
    //echo '<div class="menu">'.$delf1['way'].$result[$i].' - Такой папки нет в базе! - Теперь есть!</div>';
    mysql_query("INSERT INTO `downpath` SET
    `refid` = '" . $cat . "',
    `way` = '".$delf1['way'].$result[$i]."/',
    `name` = '".$result[$i]."',
    `position` = '0';");
    $rid = mysql_insert_id();
    }
    if(!isset($rid))
    $rid = $arr['id'];
    updateall($rid);
    }
    }
    if($countp == 0){
    for($i = 2; $i<$ii; $i++){
        if ($result[$i] != ".htaccess" && $result[$i] != ".." && $result[$i] != "." && $result[$i] != "index.php" && !preg_match ("/.jad$/",$result[$i]) && !preg_match ("/.txt$/", $result[$i]) && !preg_match ("/.JPG$/", $result[$i]) && !preg_match ("/.GIF$/", $result[$i]) && !preg_match ("/.PNG$/", $result[$i])){
    if (is_file($dir.$result[$i])){ //// Ищем файлы!

    $zapp = mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `way` = '" . $delf1['way'].$result[$i] ."';");
    $count = mysql_result($zapp, 0);
    $countf++;
    if($count == 0){
    $countobf++;
    //echo '<div class="menu">'.$delf1['way'].$result[$i].' - Такого файла нет в базе! - Теперь есть!</div>';
    if(in_array($result[$i].'.txt' , $result)){
        $txt = file_get_contents('files/'.$delf1['way'].$result[$i].'.txt');
        unlink('files/'.$delf1['way'].$result[$i].'.txt');
                    if (mb_check_encoding($txt, 'UTF-8')) {
                    }
                    elseif (mb_check_encoding($txt, 'windows-1251')) {
                        $txt = iconv("windows-1251", "UTF-8", $txt);
                    }
                    elseif (mb_check_encoding($txt, 'KOI8-R')) {
                        $txt = iconv("KOI8-R", "UTF-8", $txt);
                    }
                    else {
                        //echo "Файл в неизвестной кодировке!<br />";
                    }
        //echo'Есть описание!<br/>';
    }
    if(in_array($result[$i].'.JPG' , $result)){
        //echo'Есть скриншот JPG!';
        $scr = $result[$i].'.JPG';
        rename('files/'.$delf1['way'].$scr, $screenroot.'/'.$scr);
    }elseif (in_array($result[$i].'.GIF' , $result)){
       $scr = $result[$i].'.GIF';
       //echo'Есть скриншот GIF!';
    rename('files/'.$delf1['way'].$scr, $screenroot.'/'.$scr);
    }else{
        $scr = "";
    }

    mysql_query("INSERT INTO `downfiles` SET
    `pathid` = '".$cat."',
    `way` = '".$delf1['way'].$result[$i]."',
    `name` = '".$result[$i]."',
    `desc` = '".functions::check($txt)."',
    `time` = '".time()."',
    `gol` = '',
    `login` = '".$login."';");
    $rid = mysql_insert_id();
    if($scr !== "")
    {
        mysql_query("INSERT INTO `downscreen` SET
        `fileid` = '".$rid."',
        `way` = '".$scr."';");
    }

       } } } }
    //echo'<div class="menu">Найдено файлов: '.$countf.'</div>
    //<div class="menu">Из них не найдено в базе: '.$countobf.'</div>';
    }

    }
    updateall($cat);
    auto_clean_cache(); // Чистим кэш счётчиков
    echo'<div class="gmenu">Успешно обновлено!</div>';

    echo'<a href="admin.php?act=folder&amp;cat='.$cat.'">Назад в папку</a><br/>';
    echo'<a href="admin.php">Админка</a><br/>';
?>