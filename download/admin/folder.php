<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/
/////////////////////////////////
/////// Чистильщик кэша /////////
/////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');

$sort = isset($_GET['sort']) ? $_GET['sort']: '';
$cat = intval($_GET['cat']);
if ($_GET['fil'])
$url = implode('&amp;fil[]=', $_GET[fil]);
elseif ($_POST['fil'])
$url = implode('&amp;fil[]=', $_POST[fil]);

if ($cat)
echo '<div class="phdr"><a href="admin.php?act=folder&amp;fil[]=' . $url . '">В корень</a></div>';
else
echo '<div class="phdr"><a href="admin.php?act=new">Новые файлы</a></div>';

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
        echo 'Файлов: ' . $countf . '';
        if ($countnf > 0)
            echo '+' . $countnf;
        echo ', Папок: ' . $countp . '<br/>

        <a href="admin.php?act=sdvig&amp;dir=' . $zap2['id'] .'">Вверх</a> |
        <a href="admin.php?act=delete&amp;op=folder&amp;id=' . $zap2['id'] . '">Удалить</a> |
        <a href="admin.php?act=editcat&amp;cat=' . $zap2['id'] . '">Изм.</a></div></div>';
    }
    echo '<div class="phdr">Всего папок: ' . $totalcat . '</div>';
    if ($totalcat > $kmess)
    {
        echo '<div class="menu">' . functions::display_pagination('admin.php?act=folder&amp;fil=' . $url . '&amp;cat=' . $cat . '&amp;', $start, $totalcat, $kmess) . '';
        echo '<form action="admin.php" method="get"><input type="hidden" name="act" value="folder"/><input type="hidden" name="fil" value="' .
        $fil . '"/><input type="hidden" name="cat" value="' . $cat .
        '"/><input type="text" name="page" size="2"/><input type="submit" value="К странице &gt;&gt;"/></form></div>';
    }

    echo '<div class="func"><a href="admin.php?act=createdir&amp;cat=' . $cat . '">Создать папку</a><br/>';
    echo '<a href="admin.php?act=import&amp;cat=' . $cat . '">Импортировать файл</a><br/>';
    echo '<a href="admin.php?act=upload&amp;cat=' . $cat . '">Выгрузить файл</a><br/>';
    echo '<a href="admin.php?act=updateall&amp;cat=' . $cat . '">Обновить базу</a><br/>';
    echo '<a href="admin.php?act=update&amp;cat=' . $cat . '">Очистка базы от ненайденных файлов/папок</a></div>';

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
        <a href="admin.php?act=folder&amp;cat='.$cat.'&amp;orderby='.($order == 'desc' ? 'asc' : 'desc').'" title = "'.($order == 'desc' ? 'По убыванию' : 'По возрастанию').'"><img src="img/'.($order == 'desc' ? 'asc' : 'desc').'.png" alt="'.($order == 'desc' ? 'По убыванию' : 'По возрастанию').'" /></a>
        Сортировка: '.(($sort == 'time' or $sort == 'count' or $sort == 'rating') ? '<a href="admin.php?act=folder&amp;cat='.$cat.'&amp;sort=name">Имя</a>' : '<b>Имя</b>').' |
        '.($sort != 'time' ? '<a href="admin.php?act=folder&amp;cat='.$cat.'&amp;sort=time">Дата</a>' : '<b>Дата</b>').' |
        '.($sort != 'count' ? '<a href="admin.php?act=folder&amp;cat='.$cat.'&amp;sort=count">Скачивания</a>' : '<b>Скачивания</b>').' |
        '.($sort != 'rating' ? '<a href="admin.php?act=folder&amp;cat='.$cat.'&amp;sort=rating">Рейтинг</a>' : '<b>Рейтинг</b>').'</div>';
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
            [<a href="admin.php?act=edit&amp;file=' . $zap2['id'] . '">Изменить</a>]
            [<a href="admin.php?act=delete&amp;op=file&amp;id=' . $zap2['id'] . '">Удалить</a>]</div>';

            echo f_preview($zap2, $set_view, $tf);
            echo '</div>';
        }
        echo '<div class="gmenu"><input type="submit" value="Переместить отмеченные"/>';
        echo '</div></form>';
    }
    else
    {
        echo '<div class="rmenu">Папок в данной папке нет!<br/> Файлов тоже нет!</div>';
        echo '<div class="func"><a href="admin.php?act=createdir&amp;cat=' . $cat . '">Создать папку</a></div>';
    }


    echo '<div class="phdr">Всего файлов: ' . $totalfile . '</div>';

    if ($totalfile > $kmess)
    {
    echo '<div class="menu">' . functions::display_pagination('admin.php?act=folder&amp;cat=' . $cat . '&amp;fil=' . $url . '&amp;', $start, $totalfile, $kmess) . '';
    echo '<form action="admin.php" method="get"><input type="hidden" name="fil" value="' . $url . '"/><input type="hidden" name="act" value="folder"/><input type="hidden" name="cat" value="' .
    $cat . '"/><input type="text" name="page" size="2"/><input type="submit" value="К странице &gt;&gt;"/></form></div>';
    }

    echo '<div class="func">';
    if ($url)
    echo '<a href="admin.php?act=relocate&amp;fil[]=' . $url . '&amp;cat=' . $cat . '"><b>Вставить</b></a><br/>';

    echo '<a href="admin.php?act=updateall&amp;cat=' . $cat . '">Обновить папку</a></div>';

}

echo'<div class="menu"><a href="admin.php">Админка</a></div>';
