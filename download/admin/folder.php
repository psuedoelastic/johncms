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

$sort = isset($_GET['sort']) ? $_GET['sort']: '';
$cat = intval($_GET['cat']);
if ($_GET['fil'])
$url = implode('&amp;fil[]=', $_GET[fil]);
elseif ($_POST['fil'])
$url = implode('&amp;fil[]=', $_POST[fil]);

if ($cat)
echo '<div class="phdr"><a href="admin.php?act=folder&amp;fil[]=' . $url . '">'.$lng_dl['base_dir'].'</a></div>';
else
echo '<div class="phdr"><a href="admin.php?act=new">'.$lng_dl['last_100_files'].'</a></div>';

$totalcat = mysql_result(mysql_query("SELECT COUNT(*) FROM `downpath` WHERE `refid` = '" . $cat . "'"), 0);
if ($totalcat > 0) {

    // Отображение папок //
    $zap = mysql_query("SELECT * FROM `downpath` WHERE `refid` = '" . $cat . "' ORDER BY `position` ASC LIMIT " . $start . "," . $kmess);
    while ($zap2 = mysql_fetch_array($zap))
    {
        echo ($i % 2) ? '<div class="list1">' : '<div class="list2">';
        ++$i;

        echo '<img src="img/dir.png" alt="." /> <a href="admin.php?act=folder&amp;cat=' . $zap2['id'] . '&amp;fil[]=' . $url . '">' . $zap2['name'] . '</a><br/>';

        // Счётчики папок и файлов //
        $countf = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `type` != 1 && `way` LIKE '" . $zap2['way'] . "%' "), 0);
        $old = time() - ($down_setting['newtime'] * 24 * 3600);
        $countnf = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `time` > '" . $old . "' && `way` LIKE '" . $zap2['way'] . "%' "), 0);
        $countp = mysql_result(mysql_query("SELECT COUNT(*) FROM `downpath` WHERE `way` LIKE '" . $zap2['way'] . "%';"), 0);
        $countp--;

        echo '<div class="sub">';
        if ($zap2['desc'])
            echo '' . $zap2['desc'] . '<br/>';
        echo $lng_dl['file_count'].': ' . $countf . '';
        if ($countnf > 0)
            echo '+' . $countnf;
        echo ', '.$lng_dl['section_count'].': ' . $countp . '<br/>

        <a href="admin.php?act=sdvig&amp;dir=' . $zap2['id'] .'">'.$lng_dl['up'].'</a> |
        <a href="admin.php?act=delete&amp;op=folder&amp;id=' . $zap2['id'] . '">'.$lng['delete'].'</a> |
        <a href="admin.php?act=editcat&amp;cat=' . $zap2['id'] . '">'.$lng['edit'].'</a></div></div>';
    }
    echo '<div class="phdr">'.$lng_dl['all_dirs'].': ' . $totalcat . '</div>';
    if ($totalcat > $kmess)
    {
        echo '<div class="menu">' . functions::display_pagination('admin.php?act=folder&amp;fil=' . $url . '&amp;cat=' . $cat . '&amp;', $start, $totalcat, $kmess) . '';
        echo '<form action="admin.php" method="get"><input type="hidden" name="act" value="folder"/><input type="hidden" name="fil" value="' .
        $fil . '"/><input type="hidden" name="cat" value="' . $cat .
        '"/><input type="text" name="page" size="2"/><input type="submit" value="'.$lng_dl['to_page'].' &gt;&gt;"/></form></div>';
    }

    echo '<div class="func"><a href="admin.php?act=createdir&amp;cat=' . $cat . '">'.$lng_dl['create_section'].'</a><br/>';
    echo '<a href="admin.php?act=import&amp;cat=' . $cat . '">'.$lng_dl['import_file'].'</a><br/>';
    echo '<a href="admin.php?act=upload&amp;cat=' . $cat . '">'.$lng_dl['upload_file'].'</a><br/>';
    echo '<a href="admin.php?act=updateall&amp;cat=' . $cat . '">'.$lng_dl['refresh_base'].'</a><br/>';
    echo '<a href="admin.php?act=update&amp;cat=' . $cat . '">'.$lng_dl['clean_base'].'</a></div>';

}
else
{

    // Отображение файлов //

    $totalfile = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `pathid` = '" . $cat . "'"), 0);

    if ($totalfile > 0)
    {
        if ($sort)
        {
            $_SESSION['downsort'] = $sort;
        }
        else
        {
            $sort = isset($_SESSION['downsort']) ? $_SESSION['downsort'] : "";
        }


        // Проверка установки сортировки
        if($sort)
        $_SESSION['downsort'] = $sort;
        else
        $sort = isset($_SESSION['downsort']) ? $_SESSION['downsort'] : '';
        // Проверка упорядочивания
        if(isset($_GET['orderby']))
        {
            $order = $_GET['orderby'] == 'desc' ? 'desc' : 'asc';
            $_SESSION['orderby'] = $order;
        }else
        $order = $_SESSION['orderby'] == 'desc' ? 'desc' : 'asc';
        // Ссылки
        echo'<div class="menu">
        <a href="admin.php?act=folder&amp;cat='.$cat.'&amp;orderby='.($order == 'desc' ? 'asc' : 'desc').'" title = "'.($order == 'desc' ? $lng_dl['desc'] : $lng_dl['asc']).'"><img src="img/'.($order == 'desc' ? 'asc' : 'desc').'.png" alt="'.($order == 'desc' ? $lng_dl['desc'] : $lng_dl['asc']).'" /></a>
        '.$lng_dl['sorting'].': '.(($sort == 'time' or $sort == 'count' or $sort == 'rating') ? '<a href="admin.php?act=folder&amp;cat='.$cat.'&amp;sort=name">' . $lng_dl['name'] . '</a>' : '<b>' . $lng_dl['name'] . '</b>').' |
        '.($sort != 'time' ? '<a href="admin.php?act=folder&amp;cat='.$cat.'&amp;sort=time">' . $lng_dl['date'] . '</a>' : '<b>' . $lng_dl['date'] . '</b>').' |
        '.($sort != 'count' ? '<a href="admin.php?act=folder&amp;cat='.$cat.'&amp;sort=count">' . $lng_dl['loads_count'] . '</a>' : '<b>' . $lng_dl['loads_count'] . '</b>').' |
        '.($sort != 'rating' ? '<a href="admin.php?act=folder&amp;cat='.$cat.'&amp;sort=rating">' . $lng_dl['rating'] . '</a>' : '<b>' . $lng_dl['rating'] . '</b>').'</div>';
        if($sort != 'time' && $sort != 'count' && $sort != 'rating')
        $sort = 'name';
        $zap = mysql_query("SELECT * FROM `downfiles` WHERE `pathid` = '" . $cat ."' AND `status` = 1 ORDER BY `".$sort."` ".$order." LIMIT " . $start . "," . $kmess);

        echo '<form action="admin.php?act=folder&amp;" method="post">';

        while ($zap2 = mysql_fetch_array($zap))
        {
            echo ($i % 2) ? '<div class="list1">' : '<div class="list2">';
            ++$i;
            $tf = pathinfo($zap2['way'], PATHINFO_EXTENSION); // Тип файла

            $set_view = array('size' => 1, 'desc' => 1, 'count' => 1, 'div' => 1,
            'comments' => 1, 'add_date' => 1, 'rating' => 1);

            $set_view['admin'] = '<div class="sub"><input type="checkbox" name="fil[]" value="' . $zap2['id'] . '"/>&nbsp;
            [<a href="admin.php?act=edit&amp;file=' . $zap2['id'] . '">' . $lng['edit'] . '</a>]
            [<a href="admin.php?act=delete&amp;op=file&amp;id=' . $zap2['id'] . '">' . $lng['delete'] . '</a>]</div>';

            echo f_preview($zap2, $set_view, $tf);
            echo '</div>';
        }
        echo '<div class="gmenu"><input type="submit" value="'.$lng_dl['relocation'].'"/>';
        echo '</div></form>';
    }
    else
    {
        echo '<div class="menu"><p>'.$lng['list_empty'].'</p></div>';
        echo '<div class="func"><a href="admin.php?act=createdir&amp;cat=' . $cat . '">'.$lng_dl['create_section'].'</a></div>';
    }


    echo '<div class="phdr">'.$lng_dl['all_files'].': ' . $totalfile . '</div>';

    if ($totalfile > $kmess)
    {
    echo '<div class="menu">' . functions::display_pagination('admin.php?act=folder&amp;cat=' . $cat . '&amp;fil=' . $url . '&amp;', $start, $totalfile, $kmess) . '';
    echo '<form action="admin.php" method="get"><input type="hidden" name="fil" value="' . $url . '"/><input type="hidden" name="act" value="folder"/><input type="hidden" name="cat" value="' .
    $cat . '"/><input type="text" name="page" size="2"/><input type="submit" value="'.$lng_dl['to_page'].' &gt;&gt;"/></form></div>';
    }

    echo '<div class="func">';
    if ($url)
    {
        echo '<a href="admin.php?act=relocate&amp;fil[]=' . $url . '&amp;cat=' . $cat . '"><b>'.$lng_dl['past'].'</b></a><br/>';
    }

    echo '<a href="admin.php?act=updateall&amp;cat=' . $cat . '">'.$lng_dl['refresh_section'].'</a></div>';

}

echo'<div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
