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

defined('_IN_JOHNCMS') or die('Error:restricted access');
require_once '../incfiles/head.php';
if (!$_GET['id'])
{
    echo $lng_dl['file_not_found'].'<br/><a href="index.php?">'.$lng['back'].'</a><br/>';
    include_once '../incfiles/end.php';
    exit;
}

$id = intval(trim($_GET['id']));
$typ = mysql_query("select * from `downfiles` where id='" . $id . "';");
$ms = mysql_fetch_array($typ);

if ($ms[type]){
    echo 'Ошибка<br/><a href="index.php?">'.$lng['back'].'</a><br/>';
    include_once ('../incfiles/end.php');
    exit; }
    
if(!$user_id){
    echo $lng_dl['register_only']."<br/><a href='index.php?'>".$lng['back']."</a><br/>";
    include_once ('../incfiles/end.php');
    exit;
    }

if (intval($_GET['rat']) > 5 || intval($_GET['rat']) <= 0){ 
    echo $lng_dl['error_rating_point'];
include_once ('../incfiles/end.php');
    exit;
    }
$idd = explode('|',$ms['gol']);

if(in_array($user_id, $idd)){
    echo $lng_dl['you_have_already_rated'].'<br/>';
    echo'<a href="file_'.$id.'.html">'.$lng['back'].'</a><br/>';
    include_once '../incfiles/end.php';
    exit;
        }


$rat = intval($_GET['rat']);
$gol = $ms['gol'] ? count($idd) : 0;
$gol++;

if ($ms[rating]){
    
    $rt1 = $ms['rating'];
    $rt2 = $rt1-$rat;
    $rt2 = $rt2/$gol;
    $rat1 = $rt1-$rt2;

}else{ $rat1 = $rat; }

$goll = !$ms['gol'] ? $user_id : $ms['gol'].'|'.$user_id;

mysql_query("update `downfiles` set `rating` = '" . $rat1 . "', `gol` = '".$goll."' where id = '" . $id . "';");
echo $lng_dl['rating_set']."<br/><a href='file_" . $id . ".html'>".$lng['back']."</a><br/>";

?>