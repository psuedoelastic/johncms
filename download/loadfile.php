<?php
/**
 * @package     JohnCMS
 * @link        http://johncms.com
 * @copyright   Copyright (C) 2008-2011 JohnCMS Community
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      http://johncms.com/about
 */

define('_IN_JOHNCMS', 1);

$headmod = 'download';

require_once '../incfiles/core.php';
require_once 'functions.php';
$view = functions::check($_GET['down']);
$jad = intval($_GET['jad']);
$file = mysql_query("SELECT * FROM `downfiles` WHERE `way` = '" . $view . "'");
$file = mysql_fetch_array($file);
$count = $file['count'] + 1;
if($jad == 1){
  $jadf = str_replace('.jar','.jad',$file['way']);
  $load = $jadf;
}else{ $load = $file['way']; }
if ($_SESSION['down'] !== $file['id'])
{
mysql_query("UPDATE `downfiles` set `count` = '" . $count . "' WHERE `id` = '".$file['id']."'");
}
$_SESSION['down'] = $file['id'];

header('location: '.$loadroot.'/'.$load);
