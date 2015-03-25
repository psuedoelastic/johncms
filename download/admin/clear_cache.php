<?php defined('_IN_JOHNCMS') or die('Error: restricted access');
/**
 * @package     JohnCMS
 * @link        http://johncms.com
 * @copyright   Copyright (C) 2008-2011 JohnCMS Community
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      http://johncms.com/about
 *
 * @var $lng
 * @var $lng_dl
 */

$operation = !empty($_GET['op']) ? $_GET['op'] : '';
$cache_name = ($operation == 'count') ? $lng_dl['clean_cache_counters'] : $lng_dl['clean_cache_screens'];
$count_files = DownUtil::clearCache($operation);
?>

<div class="phdr">
    <?= $cache_name ?>
</div>
<div class="gmenu">
    <?= $lng_dl['deleted'] ?>: <?= $count_files ?>
</div>
<div class="menu">
    <a href="/download/admin.php"><?= $lng_dl['admin_panel'] ?></a>
</div>
