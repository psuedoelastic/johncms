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
$textl = $lng_dl['downloads'] . ' / ' . $lng_dl['admin_panel'];
require_once '../incfiles/head.php';
$act = isset($_GET['act']) ? $_GET['act'] : '';

if ($rights == 4 || $rights >= 9) {
    $incf = array('mod', 'edit', 'clear_cache', 'createdir', 'setting', 'updateall',
        'update', 'stat', 'delete', 'sizeupdate', 'import', 'set_add', 'upload',
        'upscreen', 'set_jar', 'set_screens', 'folder', 'file', 'zip', 'zipdel', 'zipman');
    if (in_array($act, $incf)) {
        include_once 'admin/' . $act . '.php';
        include_once '../incfiles/end.php';
        exit;
    }


    switch ($act) {


        //////////////////////////////////////////////////////
        /////////// Создание темы обсуждения на форуме\\\\\\\\
        //////////////////////////////////////////////////////
        case 'createtheme':
            $fid = intval($_GET['fid']);
            echo '<div class="phdr">'.$lng_dl['create_forum_theme'].'</div>';
            if (isset($_POST['submit'])) {
                if (empty($_POST['name'])) {
                    echo '<div class="rmenu">'.$lng_dl['empty_title'].'<br/><a href="admin.php?act=createtheme&amp;fid=' .
                        $fid . '">'.$lng_dl['repeat'].'</a></div>';
                    include_once '../incfiles/end.php';
                    exit;
                }
                if (empty($_POST['text'])) {
                    echo '<div class="rmenu">'.$lng_dl['empty_text'].'<br/><a href="admin.php?act=createtheme&amp;fid=' .
                        $fid . '">'.$lng_dl['repeat'].'</a></div>';
                    include_once '../incfiles/end.php';
                    exit;
                }
                $name = functions::check($_POST['name']);
                $text = trim($_POST['text']);
                if (!empty($_POST['pf']) && ($_POST['pf'] != '0')) {
                    $pf = intval($_POST['pf']);
                    $rz = $_POST['rz'];
                    $pr = mysql_query("SELECT * FROM `forum` WHERE `refid` = '$pf' AND `type` = 'r'");
                    while ($pr1 = mysql_fetch_array($pr)) {
                        $arr[] = $pr1['id'];
                    }
                    foreach ($rz as $v) {
                        if (in_array($v, $arr)) {
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
                echo $lng_dl['theme_created']."<p><a href='admin.php?act=file&amp;view=" .
                    $fid . "'>".$lng_dl['back_to_file']."</a></p>";
            } else {
                $file = mysql_query("SELECT * FROM `downfiles` WHERE `id` = '" . $fid . "'");
                $file = mysql_fetch_array($file);
                echo '<form action="admin.php?act=createtheme&amp;fid=' . $fid .
                    '" method="post">';
                echo '<div class="menu"><u>'.$lng_dl['title'].'</u><br/><input type="text" name="name" value="' .
                    $file['name'] . '"/></div>';
                $textv = str_replace("<br />", "\r\n", $file['desc']);
                $textv = str_replace("<br/>", "\r\n", $textv);

                $name = explode('||||', $file['name']);

                echo '<div class="menu"><u>'.$lng_dl['text'].'</u><br/><textarea rows="4" name="text">' . $textv .
                    '<br/>[url=' . $home . str_replace('../', '/', $filesroot) . '/' . name_replace($name[0]) . '_' . $file['id'] .
                    '.html]' . $file['name'] . '[/url]</textarea></div>';
                echo '<div class="menu"><u>'.$lng_dl['forum_section'].'</u><br/>';
                $fr = mysql_query("SELECT * FROM `forum` WHERE `type` = 'f'");
                echo '<input type="radio" name="pf" value="0" checked="checked" />'.$lng_dl['not_comment'].'<br />';
                while ($fr1 = mysql_fetch_array($fr)) {
                    echo "<input type='radio' name='pf' value='" . $fr1['id'] . "'/>$fr1[text]<select name='rz[]'>";
                    $pr = mysql_query("SELECT * FROM `forum` WHERE type='r' AND refid= '" . $fr1['id'] .
                        "'");
                    while ($pr1 = mysql_fetch_array($pr)) {
                        echo '<option value="' . $pr1['id'] . '">' . $pr1['text'] . '</option>';
                    }
                    echo '</select><br/>';
                }
                echo '</div><div class="bmenu"><input type="submit" name="submit" value="'.$lng['save'].'"/></div></form><p><a href="admin.php?act=file&amp;view=' .
                    $fid . '">'.$lng_dl['back_to_file'].'</a></p>';
            }
            echo '<a href="admin.php">'.$lng_dl['admin_panel'].'</a><br/>';
            break;


        /////////////////////////////////////////////////
        ///////////// Новые файлы ///////////////////////
        /////////////////////////////////////////////////
        case 'new':
            // TODO Переделать это...
            echo '<div class="phdr"><img src="img/new.png" alt="."/> '.$lng_dl['top_files'].'</div>';

            $totalfile = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles`  WHERE `type` != 1"),
                0);
            if ($totalfile > 100) {
                $totalfile = 100;
            }
            $zap = mysql_query("SELECT * FROM `downfiles` WHERE `type` != 1 ORDER BY `time` DESC LIMIT " .
                $start . "," . $kmess);
            $i = 0;
            while ($zap2 = mysql_fetch_array($zap)) {
                echo ($i % 2) ? '<div class="list1">' : '<div class="list2">';
                ++$i;
                $nadir = $zap2[pathid];
                $pat = "";
                while ($nadir != "") {
                    $dnew = mysql_query("SELECT * FROM `downpath` WHERE id = '" . $nadir . "';");
                    $dnew1 = mysql_fetch_array($dnew);
                    $pat = '<a href="admin.php?act=file&amp;view=' . $dnew1['id'] . '">' . $dnew1['name'] .
                        '</a> &gt;  ' . $pat . '';
                    $nadir = $dnew1['refid'];
                }
                $l = mb_strlen($pat);
                $pat1 = mb_substr($pat, 0, $l - 6);
                if ($zap2['desc']) {
                    $tx = $zap2['desc'];
                    if (mb_strlen($tx) > 100) {
                        $tx = mb_substr($tx, 0, 100);
                        $tx = functions::checkout($tx, 1, 1) . '...';
                    } else {
                        $tx = functions::checkout($tx, 1, 1);
                    }
                } else {
                    $tx = "<br/>".$lng_dl['description_is_empty'];
                }
                if (!$zap2['size']) {
                    $siz = filesize("$loadroot/$zap2[way]");
                    mysql_query("UPDATE `downfiles` SET `size` = '" . $siz . "' WHERE `id` = '" . $viewf .
                        "'");
                } else {
                    $siz = $zap2['size'];
                }
                $namee = explode('||||', $zap2['name']);
                $filtime = date("d.m.Y", $zap2['time']);
                echo '<img src="img/file.gif" alt="."/> <a href="admin.php?act=file&amp;view=' .
                    $zap2['id'] . '">' . $namee[0] . '</a>' . $tx . '<br/>';
                echo '[<a href="admin.php?act=edit&amp;file=' . $zap2['id'] .
                    '">'.$lng['edit'].'</a>][<a href="admin.php?act=delfile&amp;file=' . $zap2['id'] .
                    '">'.$lng['delete'].'</a>][<a href="loadfile.php?down=' . $zap2['way'] . '">' . size_convert($siz) .
                    '</a>]';
                echo ' [' . $zap2['count'] . '] '.$lng_dl['rating'].': ' . $zap2['rating'] . ' [' . $filtime .
                    ']<br/><b>' . $pat1 . '</b></div>';
            }
            if ($totalfile > $kmess) {
                echo '<div class = "phdr">' . functions::display_pagination('admin.php?act=new&amp;', $start, $totalfile,
                        $kmess) . '';
                echo '</div><form action="admin.php" method="get"><input type="hidden" name="act" value="new"/><input type="text" name="page" size="2"/><input type="submit" value="'.$lng_dl['to_page'].' &gt;&gt;"/></form>';
            }
            echo '<div class="menu"><a href="admin.php?act=folder">'.$lng_dl['structure_manage'].'</a></div>';
            echo '<div class="menu"><a href="admin.php">' . $lng_dl['admin_panel'] . '</a></div>';
            break;



        //////////////////////
        ///// Сдвиг //////////
        //////////////////////
        case 'sdvig':
            $dir = intval($_GET['dir']);
            $zap = mysql_query("SELECT * FROM `downpath` WHERE `id` = '" . $dir . "' ORDER BY `position` ASC");
            $zap2 = mysql_fetch_array($zap);
            $zap = mysql_query("SELECT * FROM `downpath` WHERE `refid` = '" . $zap2['refid'] . "' ORDER BY `position` ASC");
            while ($arr = mysql_fetch_array($zap)) {
                $arr1[] = $arr[id];
            }
            $i = 0;
            while ($arr1[$i] != $dir) {
                $i++;
            }
            echo '' . $i . '';

            $ii = $i - 1;
            mysql_query("UPDATE `downpath` SET  position='" . $i . "' WHERE id='" . $arr1[$ii] .
                "';");
            mysql_query("UPDATE `downpath` SET  position='" . $ii . "' WHERE id='" . $arr1[$i] .
                "';");
            header("Location: admin.php?act=folder&cat=$zap2[refid]");
            break;


        //////////////////////////////////////////
        ////////// Изменение имени папки /////////
        //////////////////////////////////////////
        case 'editcat':
            // TODO Переделать...
            $cat = intval($_GET['cat']);
            echo '<div class="phdr">'.$lng_dl['edit_section'].'</div>';
            if (isset($_POST['submit'])) {

                $name = functions::check(trim($_POST['name']));
                $desc = functions::check(trim($_POST['desc']));
                $types = functions::check(trim($_POST['types']));
                $nameftp = functions::check(trim($_POST['nameftp']));
                $dost = intval($_POST['dost']);

                if (preg_match("/[^a-z0-9.()+_-]/i", $nameftp)) {
                    echo '<div class="rmenu">'.str_replace('#FILE_NAME#', $nameftp, $lng_dl['incorrect_name']).'<br>
                    <a href="admin.php?act=editcat&amp;cat='.$cat.'">'.$lng_dl['repeat'].'</a><br/>';
                    include_once '../incfiles/end.php';
                    exit;
                }
                if ($dost) {
                    if (!$types) {
                        echo '<div class="rmenu">'.$lng_dl['error_file_types_is_empty'].'<br /><a href="admin.php?act=editcat&amp;cat=' .
                            $cat . '">'.$lng_dl['repeat'].'</a></div>';
                        include_once '../incfiles/end.php';
                        exit;
                    }
                }

                $rcat = intval($_GET['rcat']);
                mysql_query("UPDATE `downpath` SET  `name` = '" . $name . "', `desc` = '" . $desc .
                    "', `dost` = '" . $dost . "', `types` = '" . $types . "' WHERE `id` = '" . $cat .
                    "';"); // Пишем имя для отображения
                //// Смена имён для фтп и замена путей к файлам и папкам.
                $edit = mysql_fetch_array(mysql_query("SELECT * FROM `downpath` WHERE id = '" .
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
                while ($i < $countexp - 1) {
                    $newway = $newway . $exp[$i] . '/';
                    $i++;
                }
                // echo '<b>'.$loadroot.'/'.$newway.'</b><br/>'; // Для отладки
                rename($loadroot . '/' . $edit['way'], $loadroot . '/' . $newway); // Переименовываем в фтп

                while ($path1 = mysql_fetch_array($path)) { // Меняем пути в базе папок
                    $exp = explode('/', $path1['way']);
                    $exp[$el] = $nameftp;
                    $i = 0;
                    $katt = '';
                    $countexp = count($exp);
                    while ($i < $countexp - 1) {
                        $katt = $katt . $exp[$i] . '/';
                        $i++;
                    }
                    $dir = 'files/' . $katt;
                    //echo $dir.'<br/>'; // Для отладки
                    $result = scandir($dir);
                    $ii = count($result);
                    for ($i = 2; $i < $ii; $i++) {
                        if (preg_match("/.jad$/i", $result[$i])) { ///// Удаляем Jad файлы, т.к. пути изменены и они работать не будут.
                            //echo $result[$i].' - Удалён!<br/>'; // Для отладки
                            unlink('files/' . $katt . $result[$i]);
                        }
                    }
                    mysql_query("UPDATE `downpath` SET  `way` = '" . $katt . "' WHERE `id` = '" . $path1['id'] .
                        "';"); // Пишем новые пути
                }


                while ($file1 = mysql_fetch_array($file)) { // Меняем пути в базе файлов
                    $exp = explode('/', $file1['way']);
                    $exp[$el] = $nameftp;
                    $i = 0;
                    $katt = '';
                    $countexp = count($exp);
                    while ($i < $countexp) {
                        $ap = '';
                        if ($i < $countexp - 1) {
                            $ap = '/';
                        }
                        $katt = $katt . $exp[$i] . $ap;
                        $i++;
                    }
                    //echo $katt.'<br/>'; // Для отладки
                    mysql_query("UPDATE `downfiles` SET  `way` = '" . $katt . "' WHERE `id` = '" . $file1['id'] .
                        "';");
                }


                echo '<div class="gmenu">'.$lng_dl['saved'].'</div>
<div class="menu"><a href="admin.php?act=folder&amp;cat='.$rcat.'">'.$lng_dl['to_section'].'</a></div>';
                echo '<div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';

            } else {
                /////////// Поля ввода имён папок ////////
                $edit = mysql_query("SELECT * FROM `downpath` WHERE id = '" . $cat . "';");
                $arr = mysql_fetch_array($edit);
                $exp = explode('/', $arr['way']);
                $thisdir = $exp[count($exp) - 2];
                echo "<form action='admin.php?act=editcat&amp;cat=" . $_GET['cat'] .
                    "&amp;rcat=" . $arr['refid'] . "' method='post'><div class='menu'>
".$lng_dl['name_in_file_system'].":<br/>
<input type='text' name='nameftp' value='" . $thisdir .
                    "'/><br/><small>".$lng_dl['only_eng_symbols']."</small></div><div class='menu'>
".$lng_dl['name'].":<br/>
<input type='text' name='name' value='$arr[name]'/></div><div class='menu'>
".$lng_dl['description'].":<br/>
<input type='text' name='desc' value='$arr[desc]'/></div><div class='menu'>
".$lng_dl['file_types'].":<br/>
<input type='text' name='types' value='$arr[types]'/><br/>
<small>".$lng_dl['file_types_notice']."</small></div><div class='menu'>
<input type='checkbox' name='dost' value='1' " . ($arr['dost'] == 1 ?
                        'checked="checked"' : '') . "/> ".$lng_dl['allow_user_add_files']."
</div><div class='menu'>
<input type='submit' name='submit' value='".$lng['save']."'/></div>
</form>";

                echo '<a href="admin.php?act=folder">'.$lng_dl['structure_manage'].'</a><br/>';
                echo '<a href="admin.php">'.$lng_dl['admin_panel'].'</a><br/>';
            }
            break;


        /////////////////////////////////////////////////////
        ///////////////// Перемещение файлов ////////////////
        /////////////////////////////////////////////////////
        case 'relocate':
            $cat = intval($_GET['cat']);
            echo '<div class="phdr">'.$lng_dl['relocation_files'].'</div>';
            $path = mysql_fetch_array(mysql_query("SELECT * FROM `downpath` WHERE `id` LIKE '" .
                $cat . "';"));
            foreach ($_GET['fil'] as $fill) {
                $file = mysql_fetch_array(mysql_query("SELECT * FROM `downfiles` WHERE `id` LIKE '" .
                    intval($fill) . "' "));
                $out = $loadroot . '/' . $file['way'];
                $in = $loadroot . '/' . $path['way'] . basename($file['way']);
                if (rename($out, $in)) {
                    $zap = $path['way'] . basename($file['way']);
                    mysql_query("UPDATE `downfiles` SET `pathid` = '" . $path['id'] . "', `way` = '" .
                        $zap . "' WHERE `id` = '" . $file['id'] . "';");
                    echo $lng_dl['file'].' <b>' . $file['name'] . '</b> '.$lng_dl['relocated_to'].' <b>' . $path['name'] .
                        '</b><br/>';
                } else {
                    echo ''.$lng_dl['relocation_error'].'<br/>';
                }
            }
            echo '<div class="gmenu">'.$lng_dl['relocation_completed'].'</div>';
            echo '<div class="menu"><a href="admin.php?act=folder">'.$lng_dl['structure_manage'].'</a></div>';
            echo '<div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
            break;



        //////////////////////////////////////////////
        ////////////// Главная админки ///////////////
        /////////////////////////////////////////////
        default:
            $cherr = '';
            // Проверка прав доступа к папкам
            $arr = array(
                $filesroot . '/files/',
                $filesroot . '/graftemp/',
                $filesroot . '/screens/',
                $filesroot . '/upl/',
                $filesroot . '/cache/',
                $filesroot . '/sitemap/'
            );
            foreach ($arr as $v) {
                if (!is_writable($v)) {
                    $cherr .= '<div class="red">'.$lng_dl['dir_not_writable'].': <b>' . $v . '</b>
                    <br /><span class="gray">'.$lng_dl['dir_not_writable_msg'].'</span></div>';
                }
            }

            $countf = mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `type` != 1 AND `status` = 0");
            $countf = mysql_result($countf, 0);

            if (!extension_loaded('ffmpeg')) {
                $cherr .= $lng_dl['ffmpeg_error'].'<br/>';
            }

            if (!function_exists("imagegif")) {
                $cherr .= $lng_dl['imagegif_error'].'<br/>';
            }
            if (!function_exists("imagejpeg")) {
                $cherr .= $lng_dl['imagejpeg_error'].'<br/>';
            }

            if (!function_exists("imagepng")) {
                $cherr .= $lng_dl['imagepng_error'].'<br/>';
            }

            ?>
            <div class="phdr"><?= $lng_dl['admin_panel'] ?></div>
            <div class="user blockpad">
                <h3><img src="../images/modules.png" width="16" height="16"/>&#160;<?= $lng_dl['files_manage'] ?></h3>
                <ul>
                    <li><a href="admin.php?act=mod"><?= $lng_dl['moderation_files'] ?></a> (<?= $countf ?>)</li>
                    <li><a href="admin.php?act=import"><?= $lng_dl['import_file'] ?></a></li>
                    <li><a href="admin.php?act=upload"><?= $lng_dl['upload_file'] ?></a></li>
                    <li><a href="admin.php?act=folder&amp;fil[]="><?= $lng_dl['structure_manage'] ?></a></li>
                    <li><a href="admin.php?act=update"><?= $lng_dl['clean_base'] ?></a></li>
                    <li><a href="admin.php?act=updateall"><?= $lng_dl['refresh_base'] ?></a></li>
                    <li><a href="admin.php?act=zipman"><?= $lng_dl['add_file_to_archive'] ?></a></li>
                    <li><a href="admin.php?act=zipdel"><?= $lng_dl['mass_file_del'] ?></a></li>
                </ul>
            </div>

            <div class="user blockpad">
                <h3><img src="../images/green.gif" width="16" height="16"/>&#160;<?= $lng_dl['caching_refresh'] ?></h3>
                <ul>
                    <li><a href="admin.php?act=sizeupdate"><?= $lng_dl['file_size_check'] ?></a></li>
                    <li><a href="admin.php?act=update"><?= $lng_dl['clean_base'] ?></a></li>
                    <li><a href="admin.php?act=updateall"><?= $lng_dl['refresh_base'] ?></a></li>
                    <li><a href="admin.php?act=clear_cache&amp;op=screen"><?= $lng_dl['clean_cache_screens'] ?></a></li>
                    <li><a href="admin.php?act=clear_cache&amp;op=count"><?= $lng_dl['clean_cache_counters'] ?></a></li>
                    <li><a href="sitemap.php"><?= $lng_dl['create_sitemap'] ?></a></li>
                </ul>
            </div>

            <div class="rmenu blockpad">
                <h3><img src="../images/settings.png" width="16" height="16" class="left"/>&#160;<?= $lng['settings'] ?></h3>
                <ul>
                    <li><a href="admin.php?act=setting"><?= $lng['settings'] ?></a></li>
                    <li><a href="admin.php?act=set_add"><?= $lng_dl['fields_setting'] ?></a></li>
                    <li><a href="admin.php?act=set_screens"><?= $lng_dl['screens_setting'] ?></a></li>
                    <li><a href="admin.php?act=set_jar"><?= $lng_dl['setting_for_java'] ?></a></li>
                </ul>
            </div>

            <div class="menu blockpad">
                <h3><img src="../images/rate.gif" width="16" height="16"/>&#160;<?= $lng_dl['other'] ?></h3>
                <ul>
                    <li><a href="admin.php?act=stat"><?= $lng_dl['statistic'] ?></a></li>
                    <li><a href="index.php"><?= $lng_dl['back_to_downloads'] ?></a></li>
                </ul>
            </div>

            <div class="bmenu">
                <h3><img src="img/apply.png" alt="."/>&#160;<?= $lng_dl['config_check'] ?></h3>
                <?php if(!empty($cherr)): ?>
                    <div class="red">
                        <p>
                            <?= $cherr ?>
                        </p>
                    </div>
                <?php else: ?>
                    <ul>
                        <li>
                            <p class="green">
                                <?= $lng_dl['config_correct'] ?>
                            </p>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
            <?php
            break;
    }
} else {
    header("Location: ../index.php?err");
}
require_once '../incfiles/end.php';
