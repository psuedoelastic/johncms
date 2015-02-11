<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

/////////////////////////////////////////////
////////// Изменение описания файла /////////
/////////////////////////////////////////////

defined('_IN_JOHNCMS') or die('Error: restricted access');

$file = intval($_GET['file']);

echo '<div class="phdr">Изменение описания файла</div>';

$edit = mysql_query("select * from `downfiles` where id = '" . $file . "';");
if(!mysql_num_rows($edit)){
    echo '<div class="rmenu">Файл не найден!</div>';
    echo '<div class="menu"><a href="admin.php">Админка</a></div>';
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
        $error[] = 'Не заполнено название для отображения!';
        
    if (preg_match("/[^a-z0-9.()_-]/i", $ftpname))
        $error[] = 'В названии папки для фтп <b>' . $ftpname .
            '</b> присутствуют недопустимые символы<br/>Разрешены только латинские символы, цифры и некоторые знаки ( .()_- )';
    
    if (pathinfo($ftpname, PATHINFO_EXTENSION) == 'php')
        $error[] = 'Файл имеет недопустимое расширение!';
    
    
    $newway = mb_substr($arr['way'], 0, mb_strlen($arr['way'])-mb_strlen(basename($arr['way']))).$ftpname;
    
    if(is_file($loadroot.'/'.$newway) && $arr['way'] != $newway)
        $error[] = 'Файл с таким именем в фтп уже существует!';
    else{
    if(!rename($loadroot.'/'.$arr['way'], $loadroot.'/'.$newway))
        $error[] = 'Не удалось переименовать файл в фтп! Проверьте права доступа или наличие файла!';
    }
    if ($error) {
        echo '<div class="rmenu">Обнаружены ошибки!</div>';
        foreach ($error as $val) {
            echo '<div class="rmenu">' . $val . '</div>';
        }
        echo '<div class="menu"><a href="admin.php?act=folder">Управление файлами и папками</a><br/>';
        echo '<a href="admin.php">Админка</a></div>';
        require_once ('../incfiles/end.php');
        exit;
    }    

    mysql_query("update `downfiles` set `way` = '".$newway."', `desc` = '" . $opis . "', `name` = '" . $name .
        "', `count` = '" . $count . "' where `id` = '" . $file . "';");
    
    echo '<div class="gmenu">Выполнено!</div><div class="menu"><a href="admin.php?act=folder&amp;cat=' . $cat .
        '">В папку</a></div>';

} else {
    
    $namee = explode('||||', $arr['name']);
    $set_download = unserialize($datauser['set_forum']);
    echo '<form action="admin.php?act=edit&amp;file=' . $file . '&amp;cat=' .
        $arr['pathid'] . '" name="edit" method="post">
<div class="menu">Имя для отображения:<br/>
<input type="text" name="name" value="' . $namee[0] . '"/></div>
<div class="menu">
Имя для ссылки в описании:<br/>
<input type="text" name="linkname" value="' . $namee[1] . '"/></div>
<div class="menu">Имя в фтп:<br/>
<input type="text" name="ftpname" value="' . basename($arr['way']) . '"/></div>
<div class="menu">
Измените описание:<br/>';
if(!$is_mobile)
    echo bbcode::auto_bb('edit', 'desc');
    echo '<textarea cols="' . $set_user['field_w'] . '" rows="' . $set_user['field_h'] . '" name="desc">' . $arr[desc] . '</textarea></div>
<div class="menu">';


    echo 'Измените кол-во скачиваний:<br/>
<input type="number" name="count" value="' . $arr['count'] . '"/></div>
<div class="menu">
<input type="submit" name="submit" value="Продолжить"/></div>
</form>';
}


echo '<div class="menu"><a href="admin.php">Админка</a></div>';

?>