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
$headmod = 'load';
$textl = 'Соглашение';
require_once '../incfiles/core.php';
require_once '../incfiles/head.php';
$id = $_GET['id'];
echo'<div class="phdr">Соглашение</div>';
echo '<div class="menu">Все файлы расположенные на данном ресурсе были взяты из открытых источников.
Любая информация представленная здесь, может использоваться только в ознакомительных целях, после чего вы обязаны ее удалить.
Ни администрация сайта, ни хостинг-провайдер, ни любые другие лица не могут нести отвественности за использование материалов данного сайта.
Входя на сайт вы автоматически соглашаетесь с данными условиями.</div>
<div class="gmenu">Если вы явдяетесь правообладателем какого либо файла и условия распространения Вашего файла
 Вас не устраивают, пожалуйста свяжитесь с нами и мы вместе решим данный вопрос.</div>';
echo'<div class="phdr"><a href="file_' . $id . '.html">К файлу</a></div>';

require_once ('../incfiles/end.php');
