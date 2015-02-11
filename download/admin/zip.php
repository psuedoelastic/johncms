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
$headmod = 'load';

if ($rights == 4 || $rights >= 6){

require_once 'classes/pclzip.lib.php';
$zip_act = isset($_GET['zip_act']) ? $_GET['zip_act'] : '';
$file = functions::check($_GET['file']);
$file_id = intval($_GET['file_id']);
$file1 = $_SERVER['DOCUMENT_ROOT'].'/'.$downpat.'/'.$file;
$zip = new PclZip($file1);

switch ($zip_act){


/////////// Добавление в архив \\\\\\\\\\\\
case 'add':

echo'<div class="phdr">Выгрузка файла в архив '.basename($file).'</div>';

if (isset($_POST['submit']))
{

    if ((move_uploaded_file($_FILES["fail"]["tmp_name"], 'upl/'.$_FILES["fail"]["name"])) == true)
    {

        @chmod('upl/'.$_FILES["fail"]["name"], 0777);
        echo '<div class="gmenu">Файл загружен во временную папку!</div>';

    }
    else
    {
        echo '<div class="rmenu">Не удалось загрузить файл во временную папку! Операция не может быть продолжена!</div>';
        echo '<div class="phdr"><a href="admin.php?act=zip&amp;file='.$file.'&amp;file_id='.$file_id.'">Назад в архив</a></div>';
        echo'<div class="gmenu"><a href="admin.php?act=file&amp;view='.$file_id.'">К описанию</a><br/>';
        echo'<a href="admin.php">В админку</a></div>';
        require_once ('../incfiles/end.php');
        exit;
    }

    $add = $zip->add('upl/'.$_FILES["fail"]["name"], PCLZIP_OPT_ADD_PATH, $_POST['dir'], PCLZIP_OPT_REMOVE_ALL_PATH);
    if($add)
    echo'<div class="gmenu">Файл успешно добавлен в архив!</div>';
    else
    echo '<div class="rmenu">Файл не добавлен в архив!</div>';

    if(unlink('upl/'.$_FILES["fail"]["name"]))
    echo'<div class="gmenu">Файл удалён из временной папки!</div>';
    else
    echo '<div class="rmenu">Файл не удалён из временной папки! Удалите его из папки upl вручную!</div>';


}else{
    
    echo '<form name="import" action="admin.php?act=zip&amp;zip_act=add&amp;file='.$file.'&amp;file_id='.$file_id.'" method="post" enctype="multipart/form-data">';
    echo'<div class="menu">Выберите файл:<br/><input type="file" name="fail"/></div>
    <div class="menu">Папка для сохранения:<br/><input type="text" name="dir" value=""/><br/>
    <small>Если не требуется, ничего не пишите! Если папки нет, будет создана!<br/>
    У класса есть свои особенности при работе с папками! Папки добавляются в конец архива!</small>';
    echo'</div><div class="menu"><input type="submit" name="submit" value="Добавить"/></div></form>';
}

echo '<div class="phdr"><a href="admin.php?act=zip&amp;file='.$file.'&amp;file_id='.$file_id.'">Назад в архив</a></div>';
echo'<div class="gmenu"><a href="admin.php?act=file&amp;view='.$file_id.'">К описанию</a><br/>';
echo'<a href="admin.php">В админку</a></div>';
break;



///////// Удаление файла из архива \\\\\\\\\\\    
case 'del':
$f = functions::check($_GET['ob']);
$ext = $zip->delete(PCLZIP_OPT_BY_NAME, $f);
echo'<div class="phdr">Удаление файла: '.$f.'</div>';
if($ext != 0){
    echo'<div class="gmenu">Файл успешно удалён из архива!</div>';
}else{
    echo '<div class="rmenu">Файл не удалён из архива!</div>';
}
echo '<div class="phdr"><a href="admin.php?act=zip&amp;file='.$file.'&amp;file_id='.$file_id.'">Назад в архив</a></div>';
echo'<div class="gmenu"><a href="admin.php?act=file&amp;view='.$file_id.'">К описанию</a><br/>';
echo'<a href="admin.php">В админку</a></div>';
break;
    
//////// Просмотр файла в архиве \\\\\\\\\\\\\    
case 'view':
$f = functions::check($_GET['ob']);
$ext = $zip->extract(PCLZIP_OPT_BY_NAME, $f, PCLZIP_OPT_EXTRACT_AS_STRING);

echo'<div class="phdr">Файл: '.$f.'</div>';
echo '<div class="menu">Размер: '.size_convert($ext[0]['size']).'</div>';

echo'<div class="menu">Сжат: '.size_convert($ext[0]['compressed_size']).'</div>';

$vrp = $ext[0]['mtime'] + $sdvig * 3600;
$vr = date("d-m-Y - H:i", $vrp);

echo'<div class="menu">Изменён: '.$vr.'</div>';
echo '<div class="list1">'.functions::checkout($ext[0]['content'], 1, 1).'</div>';
echo '<div class="phdr"><a href="admin.php?act=zip&amp;file='.$file.'&amp;file_id='.$file_id.'">Назад в архив</a></div>';
echo'<div class="gmenu"><a href="admin.php?act=file&amp;view='.$file_id.'">К описанию</a><br/>';
echo'<a href="admin.php">В админку</a></div>';
break;
    




//////////// Просмотр архива \\\\\\\\\\\\\   
default:

$list = $zip->listContent();
echo'<div class="phdr">Файл: '.basename($file).'</div>';
echo '<div class="menu">';
echo'<table border="1" cellspacing="1" cellpadding="2">';
echo '<tr><td bgcolor="#FFCC99">Имя файла/папки</td><td bgcolor="#FFCC99">Тип</td><td bgcolor="#FFCC99">Размер</td><td bgcolor="#FFCC99">Сжат</td><td bgcolor="#FFCC99">Изменён</td><td bgcolor="#FFCC99">Удаление</td></tr>';

$s = count($list);

// Постраничная навигация //
if(isset($_GET['page'])) 
$page = intval($_GET['page']);
else
$page = 1;
// текущая страница
$number = ceil($s/$kmess); // всего страниц
$start = ($page > 1 && $page <= $number) ? (($page - 1)*$kmess + 1) : 0; // стартовое число
$end = ($page > 0) ? $page*$kmess + 1 : $kmess; // Конечное число
if($end > $s)
$end = $s;
// Конец считалок //

for ($i = $start; $i < $end; ++$i) {
    echo '<tr><td>';
    $name = str_replace('%2F', '/', rawurlencode($list[$i]['filename']));
    $type = pathinfo($list[$i]['filename'], PATHINFO_EXTENSION);
    if ($list[$i]['folder']) {
    echo '<b>'.htmlspecialchars($list[$i]['filename']).'</b>';
    $type = 'Папка';
    }else{
    $vrp = $list[$i]['mtime'] + $sdvig * 3600;
    $vr = date("d-m-Y - H:i", $vrp);
    echo '<a href="admin.php?act=zip&amp;zip_act=view&amp;file='.$file.'&amp;ob='.$list[$i]['filename'].'&amp;file_id='.$file_id.'">'.htmlspecialchars($list[$i]['filename']).'</a>'; 
    }
    echo'</td><td>'.$type.'</td><td>'.size_convert($list[$i]['size']).'</td><td>'.size_convert($list[$i]['compressed_size']).'</td><td>'.$vr.'</td><td><a href="admin.php?act=zip&amp;zip_act=del&amp;file='.$file.'&amp;ob='.$name.'&amp;file_id='.$file_id.'">Удалить</a></td></tr>';
    }

$prop = $zip->properties();
		if($prop['comment']){
			if(iconv('UTF-8', 'UTF-8', $prop['comment']) != $prop['comment']){
				$prop['comment'] = iconv('Windows-1251', 'UTF-8', $prop['comment']); }
echo '</table><table border="1" cellspacing="1" cellpadding="2"><tr><td bgcolor="#FFCC99">Комментарий: </td><td>'.functions::checkout($prop['comment'], 1, 1).'</td></tr>'; }
echo'</table>';
echo '</div>';
echo'<div class="phdr">Файлов/папок в архиве: '.$s.'</div>';

// Вывод страниц навигации //
if($s > $kmess){
    echo '<div class="menu">Страница '.$page.' из '.$number.'<br/>';
    
    // Предыдущая
    if($page > 1){
    $back_page = $page-1;
    echo '<a href="admin.php?act=zip&amp;file='.$file.'&amp;file_id='.$file_id.'&amp;page='.$back_page.'">&lt;&lt;</a>&nbsp;';
    }
    
    // 3 страницы меньше текущей.
    $a = ($page-3) > 0 ? ($page-3) : 1;
    for($back = $a; $back < $page; $back++)
    echo '<a href="admin.php?act=zip&amp;file='.$file.'&amp;file_id='.$file_id.'&amp;page='.$back.'">'.$back.'</a>&nbsp;';
    
    // текущая страница
    echo '<b>'.$page.'</b>&nbsp;';
    
    // 3 страницы больше текущей
    $a = ($page+3) < $number ? ($page+3) : $number;
    for($next = $page+1; $next <= $a; $next++)
    echo '<a href="admin.php?act=zip&amp;file='.$file.'&amp;file_id='.$file_id.'&amp;page='.$next.'">'.$next.'</a>&nbsp;';
    
    // Следующая
    if($page < $number){
    $next_page = $page+1;
    echo '<a href="admin.php?act=zip&amp;file='.$file.'&amp;file_id='.$file_id.'&amp;page='.++$page.'">&gt;&gt;</a>';
    }
    echo '</div>';
}
// Конец вывода страниц навигации //



echo '<div class="gmenu">';
echo '<a href="admin.php?act=zip&amp;zip_act=add&amp;file='.$file.'&amp;file_id='.$file_id.'">Добавить файл</a><br/>';
echo '<a href="admin.php?act=file&amp;view='.$file_id.'">К описанию</a><br/>';
echo '<a href="admin.php">В админку</a></div>';



break;


}
}
