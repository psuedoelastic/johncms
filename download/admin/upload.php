<?php defined('_IN_JOHNCMS') or die('Error: restricted access');
/**
 * @package     JohnCMS
 * @link        http://johncms.com
 * @copyright   Copyright (C) 2008-2011 JohnCMS Community
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      http://johncms.com/about
 *
 * @var $lng
 * @var $lng_dl
 */


echo'<div class="phdr">'.$lng_dl['upload_files'].'</div>';
$cat = intval($_GET['cat']);
$more_file = intval($_GET['file']);
$addget = $more_file ? '&amp;file='.$more_file : '';
// Выбор типа импортируемого файла //
if (!isset($_POST['type_upload'])){
    echo'<form action="admin.php?act=upload&amp;cat='.$cat.$addget.'" name="add" method="post">';
    if(!$more_file){
    echo'<div class="menu">'.$lng_dl['what_download'].'<br/>';
    echo'<select name="type_upload" class="textbox">';
    echo'<option value="applications">'.$lng_dl['games_soft'].'</option>';
    echo'<option value="images">'.$lng_dl['images'].'</option>';
    echo '<option value="videos">'.$lng_dl['videos'].'</option>';
    echo '<option value="music">'.$lng_dl['music'].'</option>';
    echo '<option value="scripts">'.$lng_dl['scripts'].'</option>';
    echo '<option value="others">'.$lng_dl['other'].'</option>';
    echo'</select></div>';
    }else{
        echo'<input type="hidden" name="type_upload" value="more_file"/>'; // что импортируем
    }
    echo'<div class="menu">'.$lng_dl['count_to_import'].'<br/><input type="number" name="col_files" value="1"/></div>
    <div class="menu"><input type="submit" name="type_selected" value="'.$lng_dl['next'].'"/></div></form>';
    echo'<div class="phdr"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
    require_once ('../incfiles/end.php');
    exit;
    }

if(!$more_file){
// Массив настроек полей ввода //
$down_add = file_get_contents('set_add.dat');
$down_add = unserialize($down_add);
// Массив с именами полей //
$arr_input = explode(',', $down_add[$_POST['type_upload']]);
}else{
    $arr_input = array('desc');
}


if(!$_POST['col_files'])
$_POST['col_files'] = 1;

// Поля ввода //
if (!isset($_POST['submit'])){

echo'<form action="admin.php?act=upload&amp;cat='.$cat.$addget.'" name="add" method="post" enctype="multipart/form-data">
<div class="rmenu">'.$lng_dl['required_fields'].'</div>';
echo'<input type="hidden" name="type_upload" value="'.$_POST['type_upload'].'"/>'; // что импортируем
echo'<input type="hidden" name="col_files" value="'.intval($_POST['col_files']).'"/>'; // сколько импортируем
// Цикл с повторяющимеся полями //
for($i=1; $i <= $_POST['col_files']; $i++){
echo'<div class="func">'.$lng_dl['file'].': '.$i.'</div>
<div class="menu"><b>*</b> '.$lng_dl['select_file'].':<br/><input type="file" name="file['.$i.']"/></div>
<div class="menu"><b>*</b> '.$lng_dl['name'].':<br/><input type="text" name="name['.$i.']" value=""/></div>';

// Скриншот //
if(in_array('urlscreen', $arr_input))
echo'<div class="menu">'.$lng_dl['select_screen'].':<br/><input type="file" name="screen['.$i.']"/></div>';

// Имя для фтп //
if(in_array('ftpname', $arr_input))
echo'<div class="menu">'.$lng_dl['name_in_file_system'].':<br/><input type="text" name="ftp['.$i.']" value=""/></div>';

// Имя ссылки скачать //
if(in_array('namelink', $arr_input))
echo '<div class="menu">'.$lng_dl['name_to_view'].':<br/>
<input type="text" name="namelink['.$i.']" value=""/></div>';

// Автор //
if(in_array('autor', $arr_input))
echo '<div class="menu">'.$lng_dl['author'].':<br/>
<input type="text" name="autor['.$i.']" value=""/></div>';

// Производитель //
if(in_array('vendor', $arr_input))
echo '<div class="menu">'.$lng_dl['vendor'].':<br/>
<input type="text" name="vendor['.$i.']" value=""/></div>';

// Совместимость //
if(in_array('lang', $arr_input))
echo '<div class="menu">'.$lng_dl['interface_language'].':<br/>
<input type="text" name="lang['.$i.']" value=""/></div>';

// Совместимость //
if(in_array('compatibility', $arr_input))
echo '<div class="menu">'.$lng_dl['compatibility'].':<br/>
<input type="text" name="compatibility['.$i.']" value=""/></div>';

// Распространяется //
if(in_array('distributed', $arr_input))
echo '<div class="menu">'.$lng_dl['propagation_conditions'].':<br/>
<input type="text" name="distributed['.$i.']" value=""/></div>';

// url //
if(in_array('url', $arr_input))
echo '<div class="menu">'.$lng_dl['site_url'].':<br/>
<input type="text" name="url['.$i.']" value=""/></div>';

// Версия //
if(in_array('ver', $arr_input))
echo '<div class="menu">'.$lng_dl['version'].':<br/>
<input type="text" name="ver['.$i.']" value=""/></div>';

// Год выхода //
if(in_array('year', $arr_input))
echo '<div class="menu">'.$lng_dl['released'].':<br/>
<input type="text" name="year['.$i.']" value=""/></div>';

// Описание //
if(in_array('desc', $arr_input)){
$set_download = unserialize($datauser['set_forum']);
echo '<div class="menu">'.$lng_dl['description'].': <br/>';
if(!$is_mobile)
    echo bbcode::auto_bb('add', 'desc[' . $i . ']');
echo '<textarea cols="' . $set_user['field_w'] . '" rows="' . $set_user['field_h'] . '" name="desc[' . $i . ']"></textarea></div>';

}

if(!$more_file){
echo '<div class="menu"><b>*</b> '.$lng_dl['change_section'].':<br/>
<select name="namekat['.$i.']" class="textbox">';

$impcat = mysql_query("select * from `downpath` where refid = '" . $cat . "';");
while($arr = mysql_fetch_array($impcat)){
$countp = mysql_result(mysql_query("SELECT COUNT(*) FROM `downpath` WHERE `way` LIKE '" . $arr['way'] ."%';"), 0)-1;
if($countp <= 0)
echo '<option value="'.$arr['id'].'">'.$arr['name'].'</option>';
}
echo '</select><br/><small>'.$lng_dl['change_section_warn'].'</small></div>';
}
}

echo '<div class="menu"><input type="submit" name="submit" value="'.$lng_dl['save'].'"/></div></form>';
}else{
    
    // Проверка и импорт //
    for($i=1; $i <= $_POST['col_files']; $i++){
        $error = array();
        $file = $_FILES['file']['name'][$i];
        $name = isset($_POST['name'][$i]) ? functions::check(trim($_POST['name'][$i])) : false;
        $linkname = functions::check(trim($_POST['namelink'][$i]));
        $urlscreen = $_FILES['screen']['name'][$i];
        $ftp = isset($_POST['ftp'][$i]) ? functions::check(trim($_POST['ftp'][$i])) : false;
        
        if(!$name){
            $error[] = $lng_dl['empty_filename_to_view'];
        }
        
        if($ftp){
            if (preg_match("/[^a-z0-9.()_-]/i", $ftp)){
            $error[] = str_replace('#FILE_NAME#', $ftp, $lng_dl['incorrect_name']);
            }
        }
        
        // Получаем путь до папки //
        $catid = intval($_POST['namekat'][$i]) ? intval($_POST['namekat'][$i]) : $cat;
        $impcat = mysql_query("select * from `downpath` where id = '" . $catid . "';");
        $arr = mysql_fetch_array($impcat);
        $loaddir = $loadroot.'/'.$arr['way'];
        if(!$ftp){
        ////// Если не задано имя для фтп подгоняем под допустимое ///////
        $ftp = name_replace($name);
        /////// Определяем тип файла ///////
        $typ = pathinfo($file, PATHINFO_EXTENSION);
        //////// Конечное имя файла для сохранения с расширением /////////
        $ftp = $ftp.'.'.$typ;
        if(is_file($loaddir.$ftp)){
        $ftp = time().$i.$ftp;
        }}
        
        if((preg_match("/php/i", pathinfo($ftp, PATHINFO_EXTENSION))) or ($ftp == ".htaccess")){
            $error[] = str_replace('#FILE_TYPE#', 'php', $lng_dl['file_type_not_allowed']);
        }
        
        
        if(!$error){
        
        // Вставляем в описание дополнительные поля //
        $desc = '';

        // Если заполнено поле с описанием
        if($_POST['desc'][$i])
            $desc = $desc.'[b]'.$lng_dl['description'].':[/b] '.$_POST['desc'][$i]."\r\n";


        // Если заполнено поле с автором
        if($_POST['autor'][$i])
        $desc = $desc.'[b]'.$lng_dl['author'].':[/b] '.$_POST['autor'][$i]."\r\n";
        
        // Если заполнено поле с производителем
        if($_POST['vendor'][$i])
        $desc = $desc.'[b]'.$lng_dl['vendor'].':[/b] '.$_POST['vendor'][$i]."\r\n";
        
        // Если заполнено поле с языком интерфейса
        if($_POST['lang'][$i])
        $desc = $desc.'[b]'.$lng_dl['interface_language'].':[/b] '.$_POST['lang'][$i]."\r\n";
        
        // Если заполнено поле с версией
        if($_POST['ver'][$i])
        $desc = $desc.'[b]'.$lng_dl['version'].':[/b] '.$_POST['ver'][$i]."\r\n";
        
        // Если заполнено поле с условием распространения
        if($_POST['distributed'][$i])
        $desc = $desc.'[b]'.$lng_dl['propagation_conditions'].':[/b] '.$_POST['distributed'][$i]."\r\n";
        
        // Если заполнено поле с совместимостью
        if($_POST['compatibility'][$i])
        $desc = $desc.'[b]'.$lng_dl['compatibility'].':[/b] '.$_POST['compatibility'][$i]."\r\n";
        
        // Если заполнено поле с годом выхода
        if($_POST['year'][$i])
        $desc = $desc.'[b]'.$lng_dl['released'].':[/b] '.$_POST['year'][$i]."\r\n";
        
        // Если заполнено поле с адресом сайта
        if($_POST['url'][$i])
        $desc = $desc.'[b]'.$lng_dl['site_url'].':[/b] '.$_POST['url'][$i]."\r\n";
        

        
        
        // Качаем файл себе на сервер //
        if(move_uploaded_file($_FILES["file"]["tmp_name"][$i], $loaddir.$ftp) == true){
        @chmod($loaddir.$ftp, 0777); // ставим права доступа
        if(!$more_file)
        $name = $name.'||||'.$linkname;
        
        mysql_query("INSERT INTO `downfiles` SET
        " .($more_file > 0 ? " `type` = '1', " : ''). "
        `pathid` = '" .($more_file > 0 ? $more_file : $catid). "',
        `way` = '".$arr['way'].$ftp."',
        `name` = '".$name."',
        `desc` = '".mysql_real_escape_string($desc)."',
        `time` = '" . time() . "',
        `gol` = '',
        `login` = '".$login."',
        `user_id` = '".$user_id."';");
        $rid = mysql_insert_id();
        if($urlscreen){
            $scr_type = pathinfo($urlscreen, PATHINFO_EXTENSION);
            if($scr_type == 'jpg' || $scr_type == 'png' || $scr_type == 'gif' || $scr_type == 'jpeg'){
                $save_screen = $filesroot.'/screens/'.$rid.'.'.$scr_type;
                if(move_uploaded_file($_FILES["screen"]["tmp_name"][$i], $save_screen) == true){
                @chmod($save_screen, 0777); // ставим права доступа
                 mysql_query("INSERT INTO `downscreen` SET
                `fileid` = '".$rid."',
                `way` = '".$rid.".".$scr_type."';");
                echo'<div class="gmenu">'.$lng_dl['screen_loaded'].'</div>';
                }else{
                    echo'<div class="rmenu">'.$lng_dl['screen_not_loaded'].'</div>';
                }
            }else{
                echo'<div class="rmenu">'.$lng_dl['screen_type_incorrect'].'</div>';
            }
            }
        echo'<div class="gmenu">'.$lng_dl['file_loaded'].'</div>';
        echo'<div class="menu"><a href="admin.php?act=file&amp;view='.($more_file > 0 ? $more_file : $rid).'">'.$lng_dl['back_to_file'].'</a> | <a href="admin.php?act=folder&amp;cat='.$catid.'">'.$lng_dl['to_section'].'</a></div>';
        }else{
            echo'<div class="rmenu">'.$lng_dl['file_not_loaded'].'</div>';
        }
        
        }else{
            echo '<div class="rmenu">'.$lng['error'].'</div>';
            foreach($error as $val){
                echo '<div class="rmenu">'.$val.'</div>';
            }
        }}
    auto_clean_cache(); // Чистим кэш счётчиков
    echo'<div class="gmenu">'.$lng_dl['upload_success'].'</div>';

}

echo '<div class="gmenu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
