<?php
/**
 * @package     JohnCMS
 * @link        http://johncms.com
 * @copyright   Copyright (C) 2008-2011 JohnCMS Community
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      http://johncms.com/about
 */
require_once 'classes/classImageEdit.php';

$file = 'set.dat';
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


