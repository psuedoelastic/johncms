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
$textl = $lng_dl['downloads'] . ' / ' . $lng_dl['view_archive'];
require_once '../incfiles/head.php';
if ($down_setting['zipview'])
{

    require_once ROOTPATH . 'incfiles/lib/pclzip.lib.php';
    $act = isset($_GET['act']) ? $_GET['act'] : '';
    $file_id = intval($_GET['file_id']);

    $file_array = DownFile::getById($file_id);
    if(!is_array($file_array)){
        echo functions::display_error($lng_dl['file_not_found'], '<a href="/download/">'.$lng['back'].'</a>');
        include_once '../incfiles/end.php';
        exit;
    }

    $file1 = ROOTPATH . $downpat . '/' . $file_array['way'];

    $zip = new PclZip($file1);
    switch ($act)
    {
        //////// Просмотр файла в архиве \\\\\\\\\\\\\
        case 'view':
            $f = functions::check($_GET['ob']);
            $type = array('txt', 'dat', 'html', 'htm', 'wml', 'php', 'htaccess'); //// типы файлов для просмотра
            if (in_array(pathinfo($f, PATHINFO_EXTENSION), $type))
            {
                $ext = $zip->extract(PCLZIP_OPT_BY_NAME, $f, PCLZIP_OPT_EXTRACT_AS_STRING);
                $siz = round($ext[0]['size'] / 1024, 2);
                if ($siz > 1024)
                {
                    $siz = round($siz / 1024, 2) . ' mb';
                } else
                {
                    $siz = $siz . ' kb';
                }
                $sizc = round($ext[0]['compressed_size'] / 1024, 2);
                if ($sizc > 1024)
                {
                    $sizc = round($sizc / 1024, 2) . ' mb';
                } else
                {
                    $sizc = $sizc . ' mb';
                }
                echo '<div class="phdr">'.$lng_dl['file'].': ' . $f . '</div>';
                echo '<div class="menu">'.$lng_dl['size'].': ' . $siz . '</div>';
                echo '<div class="menu">'.$lng_dl['zipped'].': ' . $sizc . '</div>';
                $vrp = $ext[0]['mtime'] + $sdvig * 3600;
                $vr = date("d-m-Y - H:i", $vrp);
                echo '<div class="menu">'.$lng_dl['last_modified'].': ' . $vr . '</div>';
                echo '<div class="list1">' . functions::checkout(perekodname($ext[0]['content']), 1, 1) . '</div>';
            } else
            {
                echo '<div class="rmenu">'.$lng_dl['view_file_error'].'</div>';
            }
            echo '<div class="phdr"><a href="zipview.php?file_id=' . $file_id . '">'.$lng_dl['back_to_zip'].'</a></div>';
            echo '<div class="gmenu"><a href="'.$file_array['FILE_PAGE_URL'].'">'.$lng_dl['back_to_file'].'</a></div>';
            break;

        //////////// Просмотр архива \\\\\\\\\\\\\
        default:
            $list = $zip->listContent();
            echo '<div class="phdr">'.$lng_dl['file'].': ' . basename($file_array['way']) . '</div>';
            echo '<div class="menu">';
            echo '<table border="1" cellspacing="1" cellpadding="2">';
            echo '<tr><td bgcolor="#FFCC99">'.$lng_dl['name'].'</td>
            <td bgcolor="#FFCC99">'.$lng_dl['file_type'].'</td><td bgcolor="#FFCC99">'.$lng_dl['size'].'</td>
            <td bgcolor="#FFCC99">'.$lng_dl['zipped'].'</td><td bgcolor="#FFCC99">'.$lng_dl['last_modified'].'</td></tr>';

            $s = count($list);

            // Постраничная навигация //
            if (isset($_GET['page']))
                $page = intval($_GET['page']);
            else
                $page = 1;
            // текущая страница
            $number = ceil($s / $kmess); // всего страниц
            $start = ($page > 1 && $page <= $number) ? (($page - 1) * $kmess + 1) : 0; // стартовое число
            $end = ($page > 0) ? $page * $kmess + 1 : $kmess; // Конечное число
            if ($end > $s)
                $end = $s;
            // Конец считалок //

            for ($i = $start; $i < $end; ++$i)
            {
                echo '<tr><td>';
                $name = str_replace('%2F', '/', rawurlencode($list[$i]['filename']));
                $type = pathinfo($list[$i]['filename'], PATHINFO_EXTENSION);
                $typef = array('txt', 'dat', 'html', 'htm', 'wml', 'php', 'htaccess'); //// типы файлов для просмотра
                if ($list[$i]['folder'])
                {
                    echo '<b>' . htmlspecialchars($list[$i]['filename']) . '</b>';
                    $type = 'Папка';
                } else
                {
                    $vrp = $list[$i]['mtime'] + $sdvig * 3600;
                    $vr = date("d-m-Y - H:i", $vrp);
                    if (in_array($type, $typef))
                    {
                        echo '<a href="zipview.php?act=view&amp;ob=' . $list[$i]['filename'] . '&amp;file_id=' . $file_id . '">' . htmlspecialchars($list[$i]['filename']) . '</a>';
                    } else
                    {
                        echo htmlspecialchars($list[$i]['filename']);
                    }
                }
                echo '</td><td>' . $type . '</td><td>' . size_convert($list[$i]['size']) . '</td><td>' . size_convert($list[$i]['compressed_size']) . '</td><td>' . $vr . '</td></tr>';
            }
            $prop = $zip->properties();
            if ($prop['comment'])
            {
                if (iconv('UTF-8', 'UTF-8', $prop['comment']) != $prop['comment'])
                {
                    $prop['comment'] = iconv('Windows-1251', 'UTF-8', $prop['comment']);
                }
                echo '</table><table border="1" cellspacing="1" cellpadding="2"><tr><td bgcolor="#FFCC99">'.$lng_dl['comment'].': </td><td>' . checkout($prop['comment'], 1, 1) . '</td></tr>';
            }
            echo '</table>';
            echo '</div>';
            echo '<div class="phdr">'.$lng_dl['all'].': ' . $s . '</div>';

            // Вывод страниц навигации //
            if ($s > $kmess)
            {
                echo '<div class="menu">'.$lng_dl['page'].' ' . $page . ' '.$lng_dl['pages_of'].' ' . $number . '<br/>';

                // Предыдущая
                if ($page > 1)
                {
                    $back_page = $page - 1;
                    echo '<a href="zipview.php?file_id=' . $file_id . '&amp;page=' . $back_page . '">&lt;&lt;</a>&nbsp;';
                }

                // 3 страницы меньше текущей.
                $a = ($page - 3) > 0 ? ($page - 3) : 1;
                for ($back = $a; $back < $page; $back++)
                    echo '<a href="zipview.php?file_id=' . $file_id . '&amp;page=' . $back . '">' . $back . '</a>&nbsp;';

                // текущая страница
                echo '<b>' . $page . '</b>&nbsp;';

                // 3 страницы больше текущей
                $a = ($page + 3) < $number ? ($page + 3) : $number;
                for ($next = $page + 1; $next <= $a; $next++)
                    echo '<a href="zipview.php?file_id=' . $file_id . '&amp;page=' . $next . '">' . $next . '</a>&nbsp;';

                // Следующая
                if ($page < $number)
                {
                    $next_page = $page + 1;
                    echo '<a href="zipview.php?file_id=' . $file_id . '&amp;page=' . ++$page . '">&gt;&gt;</a>';
                }
                echo '</div>';
            }
            // Конец вывода страниц навигации //
            echo '<div class="gmenu"><a href="'.$file_array['FILE_PAGE_URL'].'">'.$lng_dl['back_to_file'].'</a></div>';
            break;
    }
} else
{
    echo '<div class="rmenu">'.$lng_dl['zip_view_disabled'].'</div>';
}
require_once '../incfiles/end.php';