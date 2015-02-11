<?php
/**
 * @var $lng_dl
 */
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

define('_IN_JOHNCMS', 1);
$headmod = 'load';
$textl = $lng_dl['add_file'];
require_once '../incfiles/core.php';
require_once '../incfiles/head.php';
require_once 'functions.php';
$cat = intval($_GET['cat']);
$dost = mysql_query("select * from `downpath` where id = '" . $cat . "';");
$dost = mysql_fetch_array($dost);
echo'<div class="phdr">'.$lng_dl['add_file'].'</div>';
if($dost['dost'] && $user_id > 0){
    
    if(!empty($ban))
    {
        echo'<div class="rmenu">'.$lng_dl['you_banned'].'</div>';
        include_once '../incfiles/end.php';
    exit; 
    }
    
    

    if (isset($_POST['submit'])){
    ///////////////////////////////////////////////
    ////// Обработка отправленного файла //////////
    ///////////////////////////////////////////////
    
    $error = false;
    
    $fname = $_FILES['fail']['name'];
    $fsize = $_FILES['fail']['size'];
    $scrname = $_FILES['screen']['name'];
    $scrsize = $_FILES['screen']['size'];
    $opis = mysql_real_escape_string(trim($_POST['desc']));
    $name = functions::check($_POST['name']);
    $linkname = functions::check($_POST['linkname']);
    $loaddir = $loadroot.'/'.$dost['way'];
    $scrf = pathinfo($scrname, PATHINFO_EXTENSION);
    //// ^получаем всё что нужно^ //////
    
    //////////// Транслитируем имя файла в пригодное для сохранения в фс ///////
    $ftp = name_replace($name);
    /////// Определяем тип файла ///////
    $typ = pathinfo($fname, PATHINFO_EXTENSION);
    //////// Конечное имя файла для сохранения /////////
    $ftp = $ftp.'.'.$typ;
    
    if(is_file($loaddir.$ftp)) // Если файл существует, дописываем время в имя.
       $ftp = time().$ftp;

    $ftypes = explode(',',$dost['types']);
    
    if(isset($opis) && $opis != '')
        $opis = '[b]'.$lng_dl['description'].':[/b] '.$opis;

    if(empty($name))
        $error[] = $lng_dl['empty_filename_to_view'];

    if (!in_array('all', $ftypes) && !in_array($typ, $ftypes))
           $error[] = str_replace('#FILE_TYPE#', $typ, $lng_dl['file_type_not_allowed']);

    $ftypes = array('jpg', 'gif', 'png', 'jpeg'); // Типы скриншотов. Если нужны другие, пропишите через запятую.
    
    if ($scrf && !in_array($scrf, $ftypes))
           $error[] = str_replace('#SCREEN_TYPE#', $scrf, $lng_dl['screen_type_not_allowed']);

    $filesize = 1024* $down_setting['filesize'];
    ////// Проверяем размер файла ///////
    if ($fsize > $filesize)
    {
        $str_size_error = str_replace('#CURRENT_SIZE#', size_convert($fsize), $lng_dl['big_file_size']);
        $error[] = str_replace('#MAX_SIZE#', size_convert($filesize), $str_size_error);
    }

    ///// Проверяем размер скриншота /////
    if ($scrsize > $filesize)
    {
        $str_size_error = str_replace('#CURRENT_SIZE#', size_convert($scrsize), $lng_dl['big_file_size']);
        $error[] = str_replace('#MAX_SIZE#', size_convert($filesize), $str_size_error);
    }

    ////// На всякий случай проверяем название файла если не справился фильтр ///////
    if (preg_match("/[^a-z0-9.()+_-]/i", $ftp))
    {
        $error[] = str_replace('#FILE_NAME#', $ftp, $lng_dl['incorrect_name']);
    }


    if(!$error){
    // Сохраняем всё если нет ошибок.
    if ((move_uploaded_file($_FILES["screen"]["tmp_name"], $filesroot.'/screens/'.$ftp.'.'.$scrf)) == true){
            @chmod("screens/$ftp.$scrf", 0777);
            echo '<div class="gmenu">'.$lng_dl['screen_loaded'].'</div>';
        }
    if ((move_uploaded_file($_FILES["fail"]["tmp_name"], $loaddir.$ftp)) == true){
            @chmod("$save", 0777);
            echo "<div class='gmenu'>".$lng_dl['file_loaded']."</div>";
            echo "<div class='rmenu'>".$lng_dl['file_to_moderation']."</div>";
            $name = $name.'||||'.$linkname;
            mysql_query("INSERT INTO `downfiles` SET
            `pathid` = '" . $cat . "',
            `way` = '".$dost[way].$ftp."',
            `name` = '".$name."',
            `desc` = '".$opis."',
            `time` = '" . time() . "',
            `gol` = '',
            `login` = '".$login."',
            `user_id` = '".$user_id."',
            `status` = 0;");
            $rid = mysql_insert_id();
    if($scrf){
    mysql_query("INSERT INTO `downscreen` SET
    `fileid` = '".$rid."',
    `way` = '".$ftp.".".$scrf."';");
    }
    }else{
    echo "<div class='rmenu'>".$lng_dl['load_error']."</div>";
    }
    
    }else{
    // Найдены ошибки. Уведомляем юзера о них.
    echo functions::display_error($error);
    echo '<div class="menu"><a href="add_file.php?cat='.$cat.'">'.$lng_dl['repeat'].'</a></div>';
    include_once '../incfiles/end.php';
    exit;    
    }    
    }else{
    ///////////////////////////////////////////////
    ////////////// Форма выбора файла /////////////
    ///////////////////////////////////////////////
    $set_download = unserialize($datauser['set_forum']);
    echo '<div class="rmenu">'.$lng_dl['required_fields'].'</div>';
    echo '<form name="add_file" action="add_file.php?cat='.$cat.'" method="post" enctype="multipart/form-data">';
    echo'<div class="menu"><b>*</b> '.$lng_dl['select_file'].':<br/><input type="file" name="fail"/><br/>
    <small>'.$lng_dl['allowed_types'].' <b>'.$dost['types'].'</b></small></div><div class="menu">
    '.$lng_dl['select_screen'].' <br/>
    <input type="file" name="screen"/><br/>
    <small>'.$lng_dl['allowed_types'].' <b>jpg,gif,png</b></small></div><div class="menu">
    <b>*</b> '.$lng_dl['name_to_view'].'<br/>
    <input type="text" name="name" value=""/></div>
    <div class="menu">
    '.$lng_dl['link_name'].'<br/>
    <input type="text" name="linkname" value=""/>
    </div>
    <div class="menu"><b>*</b> '.$lng_dl['description'].':<br />';
    if(!$is_mobile)
    echo bbcode::auto_bb('add_file', 'desc');
    echo '<textarea cols="' . $set_user['field_w'] . '" rows="' . $set_user['field_h'] . '" name="desc"></textarea>
    </div>';
    echo'<div class="menu"><input type="submit" name="submit" value="'.$lng['save'].'"/></div></form>';
    }
    echo'<div class="gmenu"><a href="dir_' . $cat . '.html">'.$lng['back'].'</a></div>';

}else{
    echo'<div class="rmenu">'.$lng_dl['download_not_available'].'</div>';
}

require_once '../incfiles/end.php';
?>