<?php
/**
 * @package     JohnCMS
 * @link        http://johncms.com
 * @copyright   Copyright (C) 2008-2011 JohnCMS Community
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      http://johncms.com/about
 *
 * @var $lng_dl
 * @var $lng
 */

define('_IN_JOHNCMS', 1);
$headmod = 'load';

require_once '../incfiles/core.php';
require_once 'functions.php';

$textl = $lng_dl['agreement'];
require_once '../incfiles/head.php';
$id = intval($_GET['id']);
$file_array = DownFile::getById($id);

echo'<div class="phdr">'.$lng_dl['agreement'].'</div>';
echo $lng_dl['agreement_text'];

echo'<div class="phdr"><a href="'.$file_array['FILE_PAGE_URL'].'">'.$lng_dl['back_to_file'].'</a></div>';

require_once ('../incfiles/end.php');
