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

$file = intval($_GET['file']);

echo '<div class="phdr">'.$lng_dl['edit_file'].'</div>';

$edit = mysql_query("select * from `downfiles` where id = '" . $file . "';");
if(!mysql_num_rows($edit)){
    echo '<div class="rmenu">'.$lng_dl['file_not_found'].'</div>';
    echo '<div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
    require_once ('../incfiles/end.php');
    exit;
    }
    
$arr = mysql_fetch_array($edit);

if (isset($_POST['submit'])) {
    $error = array();
    $cat = intval($_GET['cat']);
    $opis = mysql_real_escape_string(trim($_POST['desc']));
    $name = functions::check($_POST['name']);
    $linkname = functions::check($_POST['linkname']);
    $count = intval($_POST['count']);
    $name = $name . '||||' . $linkname;
    $ftpname = functions::check($_POST['ftpname']);
    
    if (!$name)
        $error[] = $lng_dl['empty_filename_to_view'];
        
    if (preg_match("/[^a-z0-9.()_-]/i", $ftpname))
    {
        $error[] = str_replace('#FILE_NAME#', $ftpname, $lng_dl['incorrect_name']);
    }

    if (pathinfo($ftpname, PATHINFO_EXTENSION) == 'php')
        $error[] = str_replace('#FILE_TYPE#', 'php', $lng_dl['file_type_not_allowed']);
    
    
    $newway = mb_substr($arr['way'], 0, mb_strlen($arr['way'])-mb_strlen(basename($arr['way']))).$ftpname;
    
    if(is_file($loadroot.'/'.$newway) && $arr['way'] != $newway)
        $error[] = 'Файл с таким именем в фтп уже существует!';
    else{
    if(!rename($loadroot.'/'.$arr['way'], $loadroot.'/'.$newway))
        $error[] = $lng_dl['file_not_renamed'];
    }
    if ($error) {
        echo '<div class="rmenu">'.$lng['error'].'</div>';
        foreach ($error as $val) {
            echo '<div class="rmenu">' . $val . '</div>';
        }
        echo '<div class="menu"><a href="admin.php?act=folder">'.$lng_dl['structure_manage'].'</a><br/>';
        echo '<a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
        require_once ('../incfiles/end.php');
        exit;
    }    

    mysql_query("update `downfiles` set `way` = '".$newway."', `desc` = '" . $opis . "', `name` = '" . $name .
        "', `count` = '" . $count . "' where `id` = '" . $file . "';");
    
    echo '<div class="gmenu">'.$lng_dl['saved'].'</div><div class="menu"><a href="admin.php?act=folder&amp;cat=' . $cat .
        '">'.$lng_dl['to_section'].'</a></div>';

} else {
    
    $namee = explode('||||', $arr['name']);
    $set_download = unserialize($datauser['set_forum']);
    echo '<form action="admin.php?act=edit&amp;file=' . $file . '&amp;cat=' .
        $arr['pathid'] . '" name="edit" method="post">
<div class="menu">'.$lng_dl['name'].':<br/>
<input type="text" name="name" value="' . $namee[0] . '"/></div>
<div class="menu">
'.$lng_dl['link_name'].'<br/>
<input type="text" name="linkname" value="' . $namee[1] . '"/></div>
<div class="menu">'.$lng_dl['name_in_file_system'].':<br/>
<input type="text" name="ftpname" value="' . basename($arr['way']) . '"/></div>
<div class="menu">
'.$lng_dl['description'].':<br/>';
if(!$is_mobile)
    echo bbcode::auto_bb('edit', 'desc');
    echo '<textarea cols="' . $set_user['field_w'] . '" rows="' . $set_user['field_h'] . '" name="desc">' . $arr[desc] . '</textarea></div>
<div class="menu">';


    echo $lng_dl['loads_count'].':<br/>
<input type="number" name="count" value="' . $arr['count'] . '"/></div>
<div class="menu">
<input type="submit" name="submit" value="'.$lng['save'].'"/></div>
</form>';
}


echo '<div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';

?>