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
 */


// TODO Переделать файл полностью

ini_set('max_execution_time', 5);
define('_IN_JOHNCMS', 1);
$headmod = 'load_admin';
require_once '../incfiles/core.php';
require_once 'functions.php';
$textl = $lng_dl['downloads'].' / '.$lng_dl['admin_panel'];
require_once '../incfiles/head.php';
$act = isset($_GET['act']) ? $_GET['act'] : '';

if ($rights == 4 || $rights >= 9)
{
    $incf = array('mod', 'edit', 'clear_cache', 'createdir', 'setting', 'updateall',
        'update', 'stat', 'delete', 'sizeupdate', 'import', 'set_add', 'upload',
        'upscreen', 'set_jar', 'set_screens', 'folder', 'file', 'zip', 'zipdel', 'zipman');
    if (in_array($act, $incf))
    {
        include_once 'admin/' . $act . '.php';
        include_once '../incfiles/end.php';
        exit;
    }


    //TODO:Найти и убрать eregi
    switch ($act)
    {


        //////////////////////////////////////////////////////
        /////////// Создание темы обсуждения на форуме\\\\\\\\
        //////////////////////////////////////////////////////
        case 'createtheme':
            $fid = intval($_GET['fid']);
            echo '<div class="phdr">Создаём тему на форуме</div>';
            if (isset($_POST['submit']))
            {
                if (empty($_POST['name']))
                {
                    echo '<div class="rmenu">Вы не ввели заголовок<br/><a href="admin.php?act=createtheme&amp;fid=' .
                        $fid . '">Повторить</a></div>';
                    include_once '../incfiles/end.php';
                    exit;
                }
                if (empty($_POST['text']))
                {
                    echo '<div class="rmenu">Вы не ввели текст<br/><a href="admin.php?act=createtheme&amp;fid=' .
                        $fid . '">Повторить</a></div>';
                    include_once '../incfiles/end.php';
                    exit;
                }
                $name = functions::check($_POST['name']);
                $text = trim($_POST['text']);
                if (!empty($_POST['pf']) && ($_POST['pf'] != '0'))
                {
                    $pf = intval($_POST['pf']);
                    $rz = $_POST['rz'];
                    $pr = mysql_query("SELECT * FROM `forum` WHERE `refid` = '$pf' AND `type` = 'r'");
                    while ($pr1 = mysql_fetch_array($pr))
                    {
                        $arr[] = $pr1['id'];
                    }
                    foreach ($rz as $v)
                    {
                        if (in_array($v, $arr))
                        {
                            mysql_query("INSERT INTO `forum` SET
                                    `refid` = '$v',
                                    `type` = 't',
                                    `time` = '" . time() . "',
                                    `user_id` = '$user_id',
                                    `from` = '$login',
                                    `text` = '$name',
                                    `soft` = '',
                                    `edit` = '',
                                    `curators` = ''
                                ");
                            $rid = mysql_insert_id();
                            mysql_query("INSERT INTO `forum` SET
                                    `refid` = '$rid',
                                    `type` = 'm',
                                    `time` = '" . time() . "',
                                    `user_id` = '$user_id',
                                    `from` = '$login',
                                    `ip` = '" . core::$ip . "',
                                    `ip_via_proxy` = '" . core::$ip_via_proxy . "',
                                    `soft` = '" . mysql_real_escape_string($agn) . "',
                                    `text` = '" . mysql_real_escape_string($text) . "',
                                    `edit` = '',
                                    `curators` = ''
                                ");
                        }
                    }
                }
                mysql_query("UPDATE `downfiles` SET `themeid`='" . $rid . "' WHERE `id`='" . $fid .
                    "';");
                mysql_query("UPDATE `users` SET `lastpost` = '" . time() . "' WHERE `id` = '$user_id'");
                echo "Тема для обсуждения добавлена.<p><a href='admin.php?act=file&amp;view=" .
                    $fid . "'>К файлу</a></p>";
            } else
            {
                $file = mysql_query("SELECT * FROM `downfiles` WHERE `id` = '" . $fid . "'");
                $file = mysql_fetch_array($file);
                echo '<form action="admin.php?act=createtheme&amp;fid=' . $fid .
                    '" method="post">';
                echo '<div class="menu"><u>Заголовок</u><br/><input type="text" name="name" value="' .
                    $file['name'] . '"/></div>';
                $textv = str_replace("<br />", "\r\n", $file['desc']);
                $textv = str_replace("<br/>", "\r\n", $textv);

                $name = explode('||||', $file['name']);

                echo '<div class="menu"><u>Текст</u><br/><textarea rows="4" name="text">' . $textv .
                    '<br/>[url=' . $home . str_replace('../', '/', $filesroot) . '/' . name_replace($name[0]) . '_' . $file['id'] .
                    '.html]' . $file['name'] . '[/url]</textarea></div>';
                echo '<div class="menu"><u>Раздел форума для обсуждения файла</u><br/>';
                $fr = mysql_query("SELECT * FROM `forum` WHERE `type` = 'f'");
                echo '<input type="radio" name="pf" value="0" checked="checked" />Не обсуждать<br />';
                while ($fr1 = mysql_fetch_array($fr))
                {
                    echo "<input type='radio' name='pf' value='" . $fr1['id'] . "'/>$fr1[text]<select name='rz[]'>";
                    $pr = mysql_query("select * from `forum` where type='r' and refid= '" . $fr1['id'] .
                        "'");
                    while ($pr1 = mysql_fetch_array($pr))
                    {
                        echo '<option value="' . $pr1['id'] . '">' . $pr1['text'] . '</option>';
                    }
                    echo '</select><br/>';
                }
                echo '</div><div class="bmenu"><input type="submit" name="submit" value="Создать"/></div></form><p><a href="admin.php?act=file&amp;view=' .
                    $fid . '">К файлу</a></p>';
            }
            echo '<a href="admin.php">Админка</a><br/>';
            break;


        /////////////////////////////////////////////////
        ///////////// Новые файлы ///////////////////////
        /////////////////////////////////////////////////
        case 'new':
            // TODO Переделать это...
            echo '<div class="phdr"><img src="img/new.png" alt="."/> Последние 100 добавленных файлов</div>';

            $totalfile = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles`  WHERE `type` != 1"),
                0);
            if ($totalfile > 100)
            {
                $totalfile = 100;
            }
            $zap = mysql_query("SELECT * FROM `downfiles` WHERE `type` != 1 ORDER BY `time` DESC LIMIT " .
                $start . "," . $kmess);
            $i = 0;
            while ($zap2 = mysql_fetch_array($zap))
            {
                echo ($i % 2) ? '<div class="list1">' : '<div class="list2">';
                ++$i;
                $nadir = $zap2[pathid];
                $pat = "";
                while ($nadir != "")
                {
                    $dnew = mysql_query("select * from `downpath` where id = '" . $nadir . "';");
                    $dnew1 = mysql_fetch_array($dnew);
                    $pat = '<a href="admin.php?act=file&amp;view=' . $dnew1['id'] . '">' . $dnew1['name'] .
                        '</a> &gt;  ' . $pat . '';
                    $nadir = $dnew1['refid'];
                }
                $l = mb_strlen($pat);
                $pat1 = mb_substr($pat, 0, $l - 6);
                if ($zap2['desc'])
                {
                    $tx = $zap2['desc'];
                    if (mb_strlen($tx) > 100)
                    {
                        $tx = mb_substr($tx, 0, 100);
                        $tx = functions::checkout($tx, 1, 1) . '...';
                    } else
                    {
                        $tx = functions::checkout($tx, 1, 1);
                    }
                } else
                {
                    $tx = "<br/>Описания данного файла нет!";
                }
                if (!$zap2['size'])
                {
                    $siz = filesize("$loadroot/$zap2[way]");
                    mysql_query("UPDATE `downfiles` set `size` = '" . $siz . "' WHERE `id` = '" . $viewf .
                        "'");
                } else
                {
                    $siz = $zap2['size'];
                }
                $namee = explode('||||', $zap2['name']);
                $filtime = date("d.m.Y", $zap2['time']);
                echo '<img src="img/file.gif" alt="."/> <a href="admin.php?act=file&amp;view=' .
                    $zap2['id'] . '">' . $namee[0] . '</a>' . $tx . '<br/>';
                echo '[<a href="admin.php?act=edit&amp;file=' . $zap2['id'] .
                    '">Изм.</a>][<a href="admin.php?act=delfile&amp;file=' . $zap2['id'] .
                    '">Уд.</a>][<a href="loadfile.php?down=' . $zap2['way'] . '">' . size_convert($siz) .
                    '</a>]';
                echo ' [' . $zap2['count'] . '] Рейт: ' . $zap2['rating'] . ' [' . $filtime .
                    ']<br/><b>' . $pat1 . '</b></div>';
            }
            if ($totalfile > $kmess)
            {
                echo '<div class = "phdr">' . functions::display_pagination('admin.php?act=new&amp;', $start, $totalfile,
                        $kmess) . '';
                echo '</div><form action="admin.php" method="get"><input type="hidden" name="act" value="new"/><input type="text" name="page" size="2"/><input type="submit" value="К странице &gt;&gt;"/></form>';
            }
            echo '<div class="menu"><a href="admin.php?act=folder">К файлам/папкам</a></div>';
            echo '<div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
            break;



        //////////////////////
        ///// Сдвиг //////////
        //////////////////////
        case 'sdvig':
            $dir = intval($_GET['dir']);
            $zap = mysql_query("SELECT * FROM `downpath` WHERE `id` = '" . $dir . "' ORDER BY `position` ASC");
            $zap2 = mysql_fetch_array($zap);
            $zap = mysql_query("SELECT * FROM `downpath` WHERE `refid` = '" . $zap2['refid'] . "' ORDER BY `position` ASC");
            while ($arr = mysql_fetch_array($zap))
            {
                $arr1[] = $arr[id];
            }
            $i = 0;
            while ($arr1[$i] != $dir)
            {
                $i++;
            }
            echo '' . $i . '';

            $ii = $i - 1;
            mysql_query("update `downpath` set  position='" . $i . "' where id='" . $arr1[$ii] .
                "';");
            mysql_query("update `downpath` set  position='" . $ii . "' where id='" . $arr1[$i] .
                "';");
            header("Location: admin.php?act=folder&cat=$zap2[refid]");
            break;


        //////////////////////////////////////////
        ////////// Изменение имени папки /////////
        //////////////////////////////////////////
        case 'editcat':
            // TODO Переделать...
            $cat = intval($_GET['cat']);
            echo '<div class="phdr">Изменение имени папки</div>';
            if (isset($_POST['submit']))
            {

                $name = functions::check(trim($_POST['name']));
                $desc = functions::check(trim($_POST['desc']));
                $types = functions::check(trim($_POST['types']));
                $nameftp = functions::check(trim($_POST['nameftp']));
                $dost = intval($_POST['dost']);

                if (preg_match("/[^a-z0-9.()+_-]/i", $nameftp))
                {
                    echo '<div class="rmenu">В названии папки для фтп <b>' . $nameftp .
                        '</b> присутствуют недопустимые символы<br/>Разрешены только латинские символы, цифры и некоторые знаки ( .()+_- )<br /><a href="admin.php?act=editcat&amp;cat=' .
                        $cat . '">Повторить</a><br/>';
                    include_once '../incfiles/end.php';
                    exit;
                }
                if ($dost)
                {
                    if (!$types)
                    {
                        echo '<div class="rmenu">Должен быть минимум 1 тип файла разрешённый к загрузке!<br /><a href="admin.php?act=editcat&amp;cat=' .
                            $cat . '">Повторить</a></div>';
                        include_once '../incfiles/end.php';
                        exit;
                    }
                }

                $rcat = intval($_GET['rcat']);
                mysql_query("update `downpath` set  `name` = '" . $name . "', `desc` = '" . $desc .
                    "', `dost` = '" . $dost . "', `types` = '" . $types . "' where `id` = '" . $cat .
                    "';"); // Пишем имя для отображения
                //// Смена имён для фтп и замена путей к файлам и папкам.
                $edit = mysql_fetch_array(mysql_query("select * from `downpath` where id = '" .
                    $cat . "';"));
                $file = mysql_query("SELECT * FROM `downfiles` WHERE `way` LIKE '" . $edit['way'] .
                    "%' ");
                $path = mysql_query("SELECT * FROM `downpath` WHERE `way` LIKE '" . $edit['way'] .
                    "%' ");
                ///// Получаем путь новой папки
                $exp = explode('/', $edit['way']);
                $el = count($exp) - 2;
                $exp[$el] = $nameftp;
                $i = 0;
                $newway = '';
                $countexp = count($exp);
                while ($i < $countexp - 1)
                {
                    $newway = $newway . $exp[$i] . '/';
                    $i++;
                }
                // echo '<b>'.$loadroot.'/'.$newway.'</b><br/>'; // Для отладки
                rename($loadroot . '/' . $edit['way'], $loadroot . '/' . $newway); // Переименовываем в фтп

                while ($path1 = mysql_fetch_array($path))
                { // Меняем пути в базе папок
                    $exp = explode('/', $path1['way']);
                    $exp[$el] = $nameftp;
                    $i = 0;
                    $katt = '';
                    $countexp = count($exp);
                    while ($i < $countexp - 1)
                    {
                        $katt = $katt . $exp[$i] . '/';
                        $i++;
                    }
                    $dir = 'files/' . $katt;
                    //echo $dir.'<br/>'; // Для отладки
                    $result = scandir($dir);
                    $ii = count($result);
                    for ($i = 2; $i < $ii; $i++)
                    {
                        if (preg_match("/.jad$/i", $result[$i]))
                        { ///// Удаляем Jad файлы, т.к. пути изменены и они работать не будут.
                            //echo $result[$i].' - Удалён!<br/>'; // Для отладки
                            unlink('files/' . $katt . $result[$i]);
                        }
                    }
                    mysql_query("update `downpath` set  `way` = '" . $katt . "' where `id` = '" . $path1['id'] .
                        "';"); // Пишем новые пути
                }
                echo '<div class="gmenu">Пути в базе папок обновлены...</div>';

                while ($file1 = mysql_fetch_array($file))
                { // Меняем пути в базе файлов
                    $exp = explode('/', $file1['way']);
                    $exp[$el] = $nameftp;
                    $i = 0;
                    $katt = '';
                    $countexp = count($exp);
                    while ($i < $countexp)
                    {
                        $ap = '';
                        if ($i < $countexp - 1)
                        {
                            $ap = '/';
                        }
                        $katt = $katt . $exp[$i] . $ap;
                        $i++;
                    }
                    //echo $katt.'<br/>'; // Для отладки
                    mysql_query("update `downfiles` set  `way` = '" . $katt . "' where `id` = '" . $file1['id'] .
                        "';");
                }
                echo '<div class="gmenu">Пути в базе файлов обновлены...</div>';

                echo '<div class="gmenu">Переименовывание Выполнено!</div><div class="menu"><a href="admin.php?act=folder&amp;cat=' .
                    $rcat . '">В папку</a></div>';
                echo '<div class="menu"><a href="admin.php">Админка</a></div>';

            }
            else
            {
                /////////// Поля ввода имён папок ////////
                $edit = mysql_query("select * from `downpath` where id = '" . $cat . "';");
                $arr = mysql_fetch_array($edit);
                $exp = explode('/', $arr['way']);
                $thisdir = $exp[count($exp) - 2];
                echo "<form action='admin.php?act=editcat&amp;cat=" . $_GET['cat'] .
                    "&amp;rcat=" . $arr['refid'] . "' method='post'><div class='menu'>
Имя для фтп:<br/>
<input type='text' name='nameftp' value='" . $thisdir .
                    "'/><br/><small>Только латинские буквы и цифры</small></div><div class='menu'>
Имя для отображения:<br/>
<input type='text' name='name' value='$arr[name]'/></div><div class='menu'>
Описание папки:<br/>
<input type='text' name='desc' value='$arr[desc]'/></div><div class='menu'>
Допустимые типы файлов:<br/>
<input type='text' name='types' value='$arr[types]'/><br/>
<small>Заполнять через запятую: mp3,gif,zip,sis,exe (all - разрешить любые типы файлов *)</small></div><div class='menu'>
<input type='checkbox' name='dost' value='1' " . ($arr['dost'] == 1 ?
                        'checked="checked"' : '') . "/> Добавление файлов юзерами
</div><div class='menu'>
<input type='submit' name='submit' value='Продолжить'/></div>
</form>";

                echo '<div class="rmenu">* Внимание! Если Ваш хостинг некорректно воспринимает htaccess, есть риск что Вам зальют php скрипт.
Убедитесь, что выгруженные через форму php файлы не исполняются!</div>';

                echo '<a href="admin.php?act=folder">Управление файлами/папками</a><br/>';
                echo '<a href="admin.php">Админка</a><br/>';
            }
            break;


        /////////////////////////////////////////////////////
        ///////////////// Перемещение файлов ////////////////
        /////////////////////////////////////////////////////
        case 'relocate':
            $cat = intval($_GET['cat']);
            echo '<div class="phdr">Перемещение файлов</div>';
            $path = mysql_fetch_array(mysql_query("SELECT * FROM `downpath` WHERE `id` LIKE '" .
                $cat . "';"));
            foreach ($_GET['fil'] as $fill)
            {
                $file = mysql_fetch_array(mysql_query("SELECT * FROM `downfiles` WHERE `id` LIKE '" .
                    intval($fill) . "' "));
                $out = $loadroot . '/' . $file['way'];
                $in = $loadroot . '/' . $path['way'] . basename($file['way']);
                if (rename($out, $in))
                {
                    $zap = $path['way'] . basename($file['way']);
                    mysql_query("update `downfiles` set `pathid` = '" . $path['id'] . "', `way` = '" .
                        $zap . "' where `id` = '" . $file['id'] . "';");
                    echo 'Файл <b>' . $file['name'] . '</b> перемещён в папку <b>' . $path['name'] .
                        '</b><br/>';
                } else
                {
                    echo 'Ошибка при перемещении<br/>';
                }
            }
            echo '<div class="gmenu">Перемещение выполнено!</div>';
            echo '<div class="menu"><a href="admin.php?act=folder">Упр. файлами и папками</a></div>';
            echo '<div class="menu"><a href="admin.php">Админка</a></div>';
            break;



        //////////////////////////////////////////////
        ////////////// Главная админки ///////////////
        /////////////////////////////////////////////
        default:
            // Проверка прав доступа к файлам и папкам
            function permissions($filez)
            {
                $filez = @decoct(@fileperms("$filez")) % 1000;

                return $filez;
            }

            $cherr = '';
            // Проверка прав доступа к папкам
            $arr = array('' . $filesroot . '/files/', '' . $filesroot . '/graftemp/', '' . $filesroot .
                '/screens/', '' . $filesroot . '/upl/', '' . $filesroot . '/cache/');
            foreach ($arr as $v)
            {
                if (is_writable($v) < 777)
                {
                    $cherr .= '<div class="red">Нет доступа к директории: <b>' . $v . '</b>
                    <br /><span class="gray">Необходимо установить права разрешающие запись</span></div>';
                }
            }

            $countf = mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `type` != 1 AND `status` = 0");
            $countf = mysql_result($countf, 0);



            ?>
            <div class="phdr"><?= $lng_dl['admin_panel'] ?></div>
            <div class="user">
                <h3><img src="../images/modules.png" width="16" height="16"/>&#160;Управление файлами</h3>
                <ul>
                    <li><a href="admin.php?act=mod">Файлы на модерации</a> (<?= $countf ?>)</li>
                    <li><a href="admin.php?act=import">Импорт файлов</a></li>
                    <li><a href="admin.php?act=upload">Выгрузка файлов</a></li>
                    <li><a href="admin.php?act=folder&amp;fil[]=">Управление файлами и папками</a></li>
                    <li><a href="admin.php?act=update">Очистка базы от Несуществующих файлов/папок</a></li>
                    <li><a href="admin.php?act=updateall">Обновление базы</a></li>
                    <li><a href="admin.php?act=zipman">Закинуть стандартный файл в архивы</a></li>
                    <li><a href="admin.php?act=zipdel">Массовое удаление файла из архивов</a></li>
                </ul>
            </div>

            <div class="user">
                <h3><img src="../images/admin.png" width="16" height="16"/>&#160;Кэширование/обновление</h3>
                <ul>
                    <li><a href="admin.php?act=sizeupdate">Пересчёт размеров файлов</a></li>
                    <li><a href="admin.php?act=update">Очистка базы от Несуществующих файлов/папок</a></li>
                    <li><a href="admin.php?act=updateall">Обновление базы</a></li>
                    <li><a href="admin.php?act=clear_cache&amp;op=screen">Очистить кэш скринов</a></li>
                    <li><a href="admin.php?act=clear_cache&amp;op=count">Очистить кэш счётчиков</a></li>
                    <li><a href="sitemap.php">Создать карту загрузок</a></li>
                </ul>
            </div>

            <div class="rmenu">
                <h3><img src="../images/settings.png" width="16" height="16" class="left"/>&#160;Настройки</h3>
                <ul>
                    <li><a href="admin.php?act=setting">Настройки</a></li>
                    <li><a href="admin.php?act=set_add">Настройки полей ввода</a></li>
                    <li><a href="admin.php?act=set_screens">Настройки скриншотов</a></li>
                    <li><a href="admin.php?act=set_jar">Настройки для JAVA</a></li>
                </ul>
            </div>

            <div class="menu">
                <h3><img src="../images/rate.gif" width="16" height="16"/>&#160;Прочее</h3>
                <ul>
                    <li><a href="admin.php?act=stat">Статистика загруз-центра</a></li>
                    <li><a href="index.php">В Загруз-Центр</a></li>
                </ul>
            </div>

            <div class="bmenu"><?= $cherr ?>
                <div class="red">
                    <?php
                    if (!extension_loaded('ffmpeg'))
                    {
                        echo 'Модуль php-ffmpeg не установлен на сервере! Отключите авоматическое создание скриншотов к
                    видео в настройках, иначе возможны проблемы!<br/>';

                    }
                    if (!function_exists("imagegif"))
                    {
                        echo 'imagegif Не поддерживается! Автоскрины к темам и gif изображениям созданы не будут!<br/>';
                    }

                    if (!function_exists("imagejpeg"))
                    {
                        echo 'imagejpeg Не поддерживается! Автоскрины к темам и jpeg изображениям созданы не будут!<br/>';
                    }

                    if (!function_exists("imagepng"))
                    {
                        echo 'imagepng Не поддерживается! Автоскрины к темам и png изображениям созданы не будут!<br/>';
                    }
                    ?>
                </div>
            </div>
            <?php
            break;
    }
} else
{
    header("Location: ../index.php?err");
}
require_once '../incfiles/end.php';
