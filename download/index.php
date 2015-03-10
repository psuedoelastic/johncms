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

require_once '../incfiles/core.php';
require_once 'functions.php';
$cat = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : "";
$tree = array();
$dirid = $cat;

// Access control
$error = '';

if (empty($set['mod_down']) AND $rights < 7)
{
    $error = $lng_dl['downloads_closed'];
}
elseif(!empty($set['mod_down']) AND $set['mod_down'] == 1 AND !$user_id)
{
    $error = $lng['access_guest_forbidden'];
}

if (!empty($error))
{
    require_once('../incfiles/head.php');
    echo '<div class="rmenu"><p>' . $error . '</p></div>';
    require_once("../incfiles/end.php");
    exit;
}

$do = array('new', 'rat', 'bookmarks');
if (in_array($act, $do))
{
    include_once($act . '.php');
}
else
{
    while ($dirid != '0' && $dirid != "")
    {
        $req = mysql_query("SELECT * FROM `downpath` WHERE `id` = '" . $dirid .
            "' LIMIT 1");
        $res = mysql_fetch_array($req);
        $tree[] = '<a href="dir_' . $dirid . '.html" title="' . $res['name'] . '">' . $res['name'] . '</a>';
        $dirid = $res['refid'];
    }
    krsort($tree);
    $cdir = array_pop($tree);
    $valueeq = '';
    foreach ($tree as $valuee)
    {
        $valueeq = '' . $valueeq . $valuee . ' / ';
    }
    $textl = $lng_dl['downloads'] . ' ' . strip_tags($valueeq . $cdir) . '';
    require_once '../incfiles/head.php';


    if (empty($_GET['cat']))
    {
        // Заголовок начальной страницы загрузок
        echo '<div class="phdr">' . $lng_dl['downloads'] . '</div>';
        // Ссылка на новые файлы
        echo '<div class="list2">';
        if ($user_id)
            echo '<img src="img/apply.png" alt="."/> <a href="index.php?act=bookmarks">' . $lng_dl['bookmarks'] . '</a><br/>';
        echo '<img src="img/new.png" alt="."/> <a href="new.html">' . $lng_dl['last_100_files'] . '</a><br/>';
        echo '<img src="img/new_dir.gif" alt="."/> <a href="top.html">' . $lng_dl['top_files'] . '</a><br />';
        echo '<img src="img/peopl.png" alt="."/> <a href="top_users.php">' . $lng_dl['user_rating'] . '</a></div><hr />';

    } else
    {
        echo '<div class="phdr"><a href="index.html">' . $lng_dl['downloads'] . '</a> | ';
        foreach ($tree as $value)
        {
            echo $value;
            if ($value != $cdir)
                echo ' | ';
        }
        echo '<b>' . strip_tags($cdir) . '</b></div>';
    }


    $totalcat = mysql_result(mysql_query("SELECT COUNT(*) FROM `downpath` WHERE `refid` = '" . $cat . "'"), 0);

    if ($totalcat > 0)
    {
        $zap = mysql_query("SELECT * FROM `downpath` WHERE `refid` = '" . $cat . "' ORDER BY `position` ASC LIMIT " . $start . "," . $kmess);
        $cachetime = time() - $down_setting['cachetime'] * 3600; // Время кэширования

        if (is_file('cache/' . $cat . '.dat') && filemtime('cache/' . $cat . '.dat') > $cachetime)
        {
            $count_cache = file_get_contents('cache/' . $cat . '.dat');
            $count_cache = unserialize($count_cache);
            $opencache = 'true';
        } else
        {
            $count_cache = array();
        }
        $i = 0;
        while ($zap2 = mysql_fetch_array($zap))
        {
            echo ($i % 2) ? '<div class="list1">' : '<div class="list2">';
            ++$i;
            if (!$count_cache[$zap2['id']])
            {
                ////////// счётчики //////////
                $countf = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `type` != 1 AND `status` = 1 && `way` LIKE '" . $zap2['way'] . "%' "), 0);
                $old = time() - (3 * 24 * 3600);
                $countnf = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `type` != 1 AND `status` = 1 && `time` > '" . $old . "' && `way` LIKE '" . $zap2['way'] . "%' "), 0);

                //$countp = mysql_result(mysql_query("SELECT COUNT(*) FROM `downpath` WHERE `way` LIKE '" . $zap2['way'] ."%';"), 0);
                //$countp--; // Счётчик папок. Раскомментировать если нужен.

                if ($countnf)
                {
                    $countnf = '/<span class="red">+' . $countnf . '</span>';
                } else
                {
                    $countnf = '';
                }
                $count_cache[$zap2['id']] = $countf . $countnf; // Сюда вставлять $countp если нужен счётчик папок.
            }
            echo '<img src="img/dir.png" alt="."/> <a href="dir_' . $zap2['id'] . '.html">' . $zap2['name'] . '</a> (' . $count_cache[$zap2['id']] . ')<br/>';
            if ($zap2['desc'])
            {
                echo '<div class="sub">' . $zap2['desc'] . '</div>';
            }
            echo '</div>';
        }
        echo '<div class="phdr">' . $lng_dl['all_dirs'] . ': ' . $totalcat . '</div>';
        if ($totalcat > $kmess)
        {
            echo '<div class="topmenu">';
            echo '' . functions::display_pagination('index.php?cat=' . $cat . '&amp;', $start, $totalcat, $kmess) . '</div>';
            echo '<p><form action="index.php" method="get"><input type="hidden" name="cat" value="' . $cat . '"/><input type="text" name="page" size="2"/><input type="submit" value="' . $lng_dl['to_page'] . ' &gt;&gt;"/></form></p>';
        }

        //// Создаём файл с кэшем если он устарел или его нет ////
        if (!$opencache && $arr = fopen('cache/' . $cat . '.dat', "w"))
        {
            fwrite($arr, serialize($count_cache));
            fclose($arr);
            //echo 'Кэш обновлен или создан!';
        }

    }
    else
    {
        $req = mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `type` != '1' AND `pathid` = '" . $cat . "' AND `status` = 1");
        $totalfile = mysql_result($req, 0);

        if ($totalfile > 0)
        {
            // Проверка установки сортировки
            if ($sort)
            {
                $_SESSION['downsort'] = $sort;
            } else
            {
                $sort = isset($_SESSION['downsort']) ? $_SESSION['downsort'] : '';
            }
            // Проверка упорядочивания
            if (isset($_GET['orderby']))
            {
                $order = $_GET['orderby'] == 'desc' ? 'desc' : 'asc';
                $_SESSION['orderby'] = $order;
            } else
            {
                $order = (isset($_SESSION['orderby']) && $_SESSION['orderby'] == 'desc') ? 'desc' : 'asc';
            }
            // Ссылки
            echo '<div class="menu"><small><a href="index.php?act=new&amp;cat=' . $cat . '">' . $lng_dl['last_100_files'] . '</a> | <a href="top.html?&amp;cat=' . $cat . '">' . $lng_dl['dir_top'] . '</a></small></div>';

            echo '<div class="menu">
            <a href="index.php?cat=' . $cat . '&amp;orderby=' . ($order == 'desc' ? 'asc' : 'desc') . '" title = "' . ($order == 'desc' ? $lng_dl['desc'] : $lng_dl['asc']) . '"><img src="img/' . ($order == 'desc' ? 'asc' : 'desc') . '.png" alt="' . ($order == 'desc' ? $lng_dl['desc'] : $lng_dl['asc']) . '" /></a>
            ' . $lng_dl['sorting'] . ': ' . (($sort == 'time' or $sort == 'count' or $sort == 'rating') ? '<a href="index.php?cat=' . $cat . '&amp;sort=name">' . $lng_dl['name'] . '</a>' : '<b>' . $lng_dl['name'] . '</b>') . ' |
            ' . ($sort != 'time' ? '<a href="index.php?cat=' . $cat . '&amp;sort=time">' . $lng_dl['date'] . '</a>' : '<b>' . $lng_dl['date'] . '</b>') . ' |
            ' . ($sort != 'count' ? '<a href="index.php?cat=' . $cat . '&amp;sort=count">' . $lng_dl['loads_count'] . '</a>' : '<b>' . $lng_dl['loads_count'] . '</b>') . ' |
            ' . ($sort != 'rating' ? '<a href="index.php?cat=' . $cat . '&amp;sort=rating">' . $lng_dl['rating'] . '</a>' : '<b>' . $lng_dl['rating'] . '</b>') . '</div>';
            if ($sort != 'time' && $sort != 'count' && $sort != 'rating')
                $sort = 'name';
            $zap = mysql_query("SELECT * FROM `downfiles` WHERE `type` != '1' AND `pathid` = '" . $cat . "' AND `status` = 1 ORDER BY `" . $sort . "` " . $order . " LIMIT " . $start . "," . $kmess);


            // Вывод списка файлов
            $i = 0;
            while ($zap2 = mysql_fetch_array($zap))
            {
                echo ($i % 2) ? '<div class="list1">' : '<div class="list2">';
                ++$i;
                $tf = pathinfo($zap2['way'], PATHINFO_EXTENSION); // Тип файла
                if ($tf == 'mp3')
                {
                    $set_view = array('variant' => 1);
                } elseif ((($tf == 'thm' or $tf == 'nth') && $down_setting['tmini']) || (($tf == '3gp' or $tf == 'mp4' or $tf == 'avi') && $down_setting['vmini']) || ($tf == 'jpg' or $tf == 'png' or $tf == 'jpeg' or $tf == 'gif'))
                {
                    $set_view = array('link_download' => 1, 'div' => 1);
                } else
                {
                    $set_view = array('variant'  => 1,
                                      'size'     => 1, 'desc' => 1, 'count' => 1, 'div' => 1,
                                      'comments' => 1, 'add_date' => 1, 'rating' => 1);
                }
                echo f_preview($zap2, $set_view, $tf);
                echo '</div>';
            }


        } else
        {

            echo '<div class="rmenu">' . $lng_dl['section_is_empty'] . '</div>';
        }

        echo '<div class="phdr">' . $lng_dl['all_files'] . ': ' . $totalfile . '</div>';
        if ($totalfile > $kmess)
        {
            echo '<div class="topmenu">';
            echo '' . functions::display_pagination('index.php?cat=' . $cat . '&amp;', $start, $totalfile, $kmess) . '';
            echo '</div><p><form action="index.php" method="get"><input type="hidden" name="cat" value="' . $cat . '"/><input type="text" name="page" size="2"/><input type="submit" value="' . $lng_dl['to_page'] . ' &gt;&gt;"/></form>';
            echo '</p>';
        }

        $dost = mysql_query("select * from `downpath` where id = '" . $cat . "';");
        $dost = mysql_fetch_array($dost);
        if ($dost['dost'] && $user_id != 0)
        {
            echo '<div class="menu"><img src="img/upload.png" alt="."/><a href="add_file.php?cat=' . $cat . '">' . $lng_dl['load_your_file'] . '</a></div>';
        }
    }

    echo '<div class="menu"><img src="img/search.png" alt="."/><a href="search.php">' . $lng_dl['search_files'] . '</a></div>';
    if (!empty($_GET['cat']))
    {
        echo '<div class="menu"><a href="index.html">' . $lng_dl['back_to_downloads'] . '</a><br/>';
        foreach ($tree as $value)
        {
            echo $value;
            echo '<br/>';
        }
        echo '</div>';
    }
    if ($rights == 4 || $rights >= 6)
    {
        echo '<div class="func">';
        if ($totalcat > 0)
        {
            echo '- <a href="admin.php?act=import&amp;cat=' . $cat . '">' . $lng_dl['import_file'] . '</a><br/>';
            echo '- <a href="admin.php?act=upload&amp;cat=' . $cat . '">' . $lng_dl['upload_file'] . '</a><br/>';
        }
        echo '- <a href="admin.php">' . $lng_dl['admin_panel'] . '</a></div>';
    }

}
require_once '../incfiles/end.php';

