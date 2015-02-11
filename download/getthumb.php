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
$file = @$_GET["file"];
$maxsize = @$_GET["size"];
$q = isset($_GET["q"]) ? intval($_GET["q"]) : '50';
$text = $_GET['copy'];
if(!isset($maxsize))
	$maxsize=100;

if(isset($file)){
  $img = new ImageEdit($file, $maxsize);  
  $img->setQuality($q);
  if(isset($text))
  $img->setCopy(8, $text);
  $img->getImage();
}

?>