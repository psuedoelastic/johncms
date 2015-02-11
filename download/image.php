<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/
require_once 'classes/classImageEdit.php';

$file = $_SERVER['DOCUMENT_ROOT'].'/download/set.dat';
$down_setting = file_get_contents($file);
$down_setting = unserialize($down_setting);

$file = @$_GET["file"];
$size = @$_GET["size"];
$q = 100;

if(isset($file)){
    $fname = basename($file);
    $img = new ImageEdit($file, $maxsize, $size);
    $img->setQuality($q);
    if($down_setting['scr_load_img'])
    $img->setCopy($down_setting['scr_copy_size'], $down_setting['scr_copy_text']);
    header('Content-Disposition: inline; filename='.$fname.'');
    $img->getImage();
}else{
    echo 'Image not found!';
}


?>