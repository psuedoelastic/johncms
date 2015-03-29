<?php define('_IN_JOHNCMS', 1);
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

$headmod = 'load';
require_once '../incfiles/core.php';
require_once 'functions.php';
$textl = $lng_dl['downloads'].' / '.$lng_dl['create_sitemap'];
require_once '../incfiles/head.php';
echo'<div class="phdr">'.$lng_dl['create_sitemap'].'</div>';
if ($rights == 4 || $rights >= 6) {
    $i = 0;
    $sitemap = 0;
    $sitem = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    $zap = mysql_query("SELECT * FROM `downfiles` WHERE `type` = '0' AND `status` = '1'");
    while ($zap2 = mysql_fetch_array($zap)) {
        $i++;
        $filtime = date("Y-m-d", $zap2['time']);
        $name = explode('||||', $zap2['name']);
        $sitem .= ' 
    <url>
      <loc>' . $home . '/download/' . name_replace($name[0]) . '_' . $zap2['id'] .
            '.html</loc>
      <lastmod>' . $filtime . '</lastmod>
      <changefreq>never</changefreq>
      <priority>0.9</priority>
    </url>';
        // Делаем 1 sitemap не более чем на 50к ссылок.
        $count_sitemap = round($i / 50000, 0);
        if ($sitemap != $count_sitemap) {
            $sitemap++;
            $sitem .= '</urlset>';
            $file = fopen('sitemap/download-' . $count_sitemap . '.xml', "w");
            fwrite($file, $sitem);
            fclose($file);
            $sitem = '<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        }
    }
    $sitem .= '</urlset>';

    $file = fopen('sitemap/download-' . $count_sitemap . '.xml', "w");
    fwrite($file, $sitem);
    fclose($file);

    echo '<div class="gmenu">'.$lng_dl['saved'].'</div>';
    echo '<div class="gmenu">'.$lng_dl['sitemap_added'].': ' . $i . '</div>';
    if ($count_sitemap > 0) {
        $count_sitemap++;
        echo '<div class="gmenu">'.str_replace('#FILE_COUNT#', $count_sitemap, $lng_dl['sitemap_cutted']).'</div>';
    }
    echo '<div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
} else {
    echo '<div class="rmenu">'.$lng_dl['access_denied'].'</div>';
}
require_once ('../incfiles/end.php');
