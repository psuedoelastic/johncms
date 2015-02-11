<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

define('_IN_JOHNCMS', 1);
$headmod = 'load';
require_once '../incfiles/core.php';
require_once 'functions.php';
$textl = 'Загруз-Центр! Админка';
require_once '../incfiles/head.php';
echo'<div class="phdr">Карта загрузок</div>';
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

    echo '<div class="gmenu">Карта загрузок успешно создана!</div>';
    echo '<div class="gmenu">Добавлено ссылок в карту: ' . $i . '</div>';
    if ($count_sitemap > 0) {
        $count_sitemap++;
        echo '<div class="gmenu">Карта содержит более 50 тысяч ссылок, по этому она была разбита на ' .
            $count_sitemap . ' файла(ов).</div>';
    }
    echo '<div class="menu"><a href="admin.php">Админка</a></div>';
} else {
    echo '<div class="rmenu">В доступе отказано!</div>';
}
require_once ('../incfiles/end.php');
?>