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
