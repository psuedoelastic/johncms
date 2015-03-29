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

$headmod = 'downsearch';
require_once '../incfiles/core.php';
require_once 'functions.php';

$textl = $lng_dl['downloads'].' / '.$lng_dl['search_files'];
require_once '../incfiles/head.php';

echo '<div class="phdr"><b>'.$lng_dl['search_files'].'</b></div>';
if (isset($_POST['submit']) || isset($_GET['submit']))
{
    $search = isset ($_POST['search']) ? trim($_POST['query']) : '';
    $search = $search ? $search : rawurldecode(trim($_GET['query']));
    $search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
    $type = isset ($_POST['search']) ? intval($_POST['search']) : intval($_GET['search']);
    if ($type == 0)
    {
        $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE MATCH (`desc`) AGAINST ('" . mysql_real_escape_string($search) . "')"), 0);
        $arr = mysql_query("SELECT * FROM `downfiles` WHERE MATCH (`desc`) AGAINST ('" . mysql_real_escape_string($search) . "')  LIMIT " . $start . ", " . $kmess);
    } else
    {
        $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `name` LIKE '%" . mysql_real_escape_string($search) . "%'"), 0);
        $arr = mysql_query("SELECT * FROM `downfiles` WHERE `name` LIKE '%" . mysql_real_escape_string($search) . "%' LIMIT " . $start . ", " . $kmess);
    }
    if ($total > 0)
    {
        while ($mass = mysql_fetch_array($arr))
        {
            echo ($i % 2) ? '<div class="list1">' : '<div class="list2">';
            ++$i;
            $tf = pathinfo($mass['way'], PATHINFO_EXTENSION); // Тип файла
            if ($tf == 'mp3')
            {
                $set_view = array('variant' => 1, 'way_to_path' => 1);
            } elseif ((($tf == 'thm' or $tf == 'nth') && $down_setting['tmini']) || (($tf == '3gp' or $tf == 'mp4' or $tf == 'avi') && $down_setting['vmini']) || ($tf == 'jpg' or $tf == 'png' or $tf == 'jpeg' or $tf == 'gif'))
            {
                $set_view = array('link_download' => 1, 'div' => 1, 'way_to_path' => 1);
            } else
            {
                $set_view = array('variant'  => 1,
                                  'size'     => 1, 'desc' => 1, 'count' => 1, 'div' => 1,
                                  'comments' => 1, 'add_date' => 1, 'rating' => 1, 'way_to_path' => 1);
            }
            echo f_preview($mass, $set_view, $tf);
            echo '</div>';
        }
        echo '<div class="phdr">'.$lng_dl['found'].': ' . $total . '</div>';
        if ($total > $kmess)
        {
            echo '<div class="menu">' . functions::display_pagination('search.php?search=' . $type . '&amp;query=' . $search . '&amp;submit=1&amp;', $start, $total, $kmess) . '</div>';
            echo '<div class="menu"><form action="search.php" method="get"><input type="hidden" name="search" value="' . $type . '"/><input type="hidden" name="query" value="' . $search . '"/><input type="hidden" name="submit" value="1"/><input type="text" name="page" size="2"/><input type="submit" value="'.$lng_dl['to_page'].' &gt;&gt;"/></form></div>';
        }
        echo '<div class="menu"><a href="search.php">'.$lng_dl['new_search'].'</a><br/>';
        echo '<a href="/download/">'.$lng_dl['back_to_downloads'].'</a></div>';

    } else
    {
        echo '<div class="rmenu">'.$lng_dl['search_not_results'].'<br/>
    <a href="search.php">'.$lng_dl['repeat'].'</a></div>';
        echo '<div class="menu"><a href="/download/">'.$lng_dl['back_to_downloads'].'</a></div>';
    }

} else
{
    echo '<form action="search.php?" method="post">
<div class="gmenu">'.$lng_dl['search_text'].':<br/>
<input type="text" name="query"/><br/><small>'.$lng_dl['search_text_msg'].'</small></div>';

    echo '<div class="menu">'.$lng_dl['found_for'].':<br/>';
    echo '<input name="search" type="radio" value="0" checked="checked" />';
    echo ''.$lng_dl['search_desc'].'<br/>';
    echo '<input name="search" type="radio" value="1" />';
    echo ''.$lng_dl['search_name'].'</div>
<div class="menu"><input type="submit" name="submit" value="'.$lng_dl['search'].'"/></div>
</form>';
    echo '<div class="menu"><a href="/download/">'.$lng_dl['back_to_downloads'].'</a></div>';
}


require_once '../incfiles/end.php';

