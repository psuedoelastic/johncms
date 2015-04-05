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

$headmod = 'load';

if ($rights == 4 || $rights >= 6)
{

    require_once ROOTPATH . 'incfiles/lib/pclzip.lib.php';
    $zip_act = isset($_GET['zip_act']) ? $_GET['zip_act'] : '';
    $file = functions::check($_GET['file']);
    $file_id = intval($_GET['file_id']);
    $file1 = ROOTPATH . $downpat . '/' . $file;
    $zip = new PclZip($file1);

    switch ($zip_act)
    {


        /////////// Добавление в архив \\\\\\\\\\\\
        case 'add':

            echo '<div class="phdr">'.$lng_dl['add_to_archive'].'</div>';

            if (isset($_POST['submit']))
            {

                if ((move_uploaded_file($_FILES["fail"]["tmp_name"], 'upl/' . $_FILES["fail"]["name"])) == true)
                {

                    @chmod('upl/' . $_FILES["fail"]["name"], 0777);
                    echo '<div class="gmenu">'.$lng_dl['file_loaded_to_tmp_dir'].'</div>';

                } else
                {
                    echo '<div class="rmenu">'.$lng_dl['file_not_loaded_to_tmp_dir'].'</div>';
                    echo '<div class="phdr"><a href="admin.php?act=zip&amp;file=' . $file . '&amp;file_id=' . $file_id . '">'.$lng_dl['back_to_zip'].'</a></div>';
                    echo '<div class="gmenu"><a href="admin.php?act=file&amp;view=' . $file_id . '">'.$lng_dl['back_to_file'].'</a><br/>';
                    echo '<a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
                    require_once('../incfiles/end.php');
                    exit;
                }

                $add = $zip->add('upl/' . $_FILES["fail"]["name"], PCLZIP_OPT_ADD_PATH, $_POST['dir'], PCLZIP_OPT_REMOVE_ALL_PATH);
                if ($add)
                    echo '<div class="gmenu">'.$lng_dl['file_added_to_archive'].'</div>';
                else
                    echo '<div class="rmenu">'.$lng_dl['file_not_added_to_archive'].'</div>';

                if (unlink('upl/' . $_FILES["fail"]["name"]))
                    echo '<div class="gmenu">'.$lng_dl['file_deleted_from_tmp_dir'].'</div>';
                else
                    echo '<div class="rmenu">'.$lng_dl['file_not_deleted_from_tmp_dir'].'</div>';


            } else
            {

                echo '<form name="import" action="admin.php?act=zip&amp;zip_act=add&amp;file=' . $file . '&amp;file_id=' . $file_id . '" method="post" enctype="multipart/form-data">';
                echo '<div class="menu">'.$lng_dl['select_file'].':<br/><input type="file" name="fail"/></div>
    <div class="menu">'.$lng_dl['path_to_save'].':<br/><input type="text" name="dir" value=""/><br/>
    <small>'.$lng_dl['path_to_save_message'].'</small>';
                echo '</div><div class="menu"><input type="submit" name="submit" value="'.$lng['save'].'"/></div></form>';
            }

            echo '<div class="phdr"><a href="admin.php?act=zip&amp;file=' . $file . '&amp;file_id=' . $file_id . '">'.$lng_dl['back_to_zip'].'</a></div>';
            echo '<div class="gmenu"><a href="admin.php?act=file&amp;view=' . $file_id . '">'.$lng_dl['back_to_file'].'</a><br/>';
            echo '<a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
            break;


        ///////// Удаление файла из архива \\\\\\\\\\\
        case 'del':
            $f = functions::check($_GET['ob']);
            $ext = $zip->delete(PCLZIP_OPT_BY_NAME, $f);
            echo '<div class="phdr">'.$lng_dl['delete_file'].': ' . $f . '</div>';
            if ($ext != 0)
            {
                echo '<div class="gmenu">'.$lng_dl['file_deleted'].'</div>';
            } else
            {
                echo '<div class="rmenu">'.$lng_dl['file_delete_error'].'</div>';
            }
            echo '<div class="phdr"><a href="admin.php?act=zip&amp;file=' . $file . '&amp;file_id=' . $file_id . '">'.$lng_dl['back_to_zip'].'</a></div>';
            echo '<div class="gmenu"><a href="admin.php?act=file&amp;view=' . $file_id . '">'.$lng_dl['back_to_file'].'</a><br/>';
            echo '<a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
            break;

        //////// Просмотр файла в архиве \\\\\\\\\\\\\
        case 'view':
            $f = functions::check($_GET['ob']);
            $ext = $zip->extract(PCLZIP_OPT_BY_NAME, $f, PCLZIP_OPT_EXTRACT_AS_STRING);

            echo '<div class="phdr">'.$lng_dl['file'].': ' . $f . '</div>';
            echo '<div class="menu">'.$lng_dl['size'].': ' . size_convert($ext[0]['size']) . '</div>';

            echo '<div class="menu">'.$lng_dl['zipped'].': ' . size_convert($ext[0]['compressed_size']) . '</div>';

            $vrp = $ext[0]['mtime'] + $sdvig * 3600;
            $vr = date("d-m-Y - H:i", $vrp);

            echo '<div class="menu">'.$lng_dl['last_modified'].': ' . $vr . '</div>';
            echo '<div class="list1">' . functions::checkout($ext[0]['content'], 1, 1) . '</div>';
            echo '<div class="phdr"><a href="admin.php?act=zip&amp;file=' . $file . '&amp;file_id=' . $file_id . '">'.$lng_dl['back_to_zip'].'</a></div>';
            echo '<div class="gmenu"><a href="admin.php?act=file&amp;view=' . $file_id . '">'.$lng_dl['back_to_file'].'</a><br/>';
            echo '<a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
            break;


        //////////// Просмотр архива \\\\\\\\\\\\\
        default:

            $list = $zip->listContent();
            echo '<div class="phdr">'.$lng_dl['file'].': ' . basename($file) . '</div>';
            echo '<div class="menu">';
            echo '<table border="1" cellspacing="1" cellpadding="2">';
            echo '<tr><td bgcolor="#FFCC99">'.$lng_dl['name'].'</td><td bgcolor="#FFCC99">'.$lng_dl['file_type'].'</td><td bgcolor="#FFCC99">'.$lng_dl['size'].'</td><td bgcolor="#FFCC99">'.$lng_dl['zipped'].'</td><td bgcolor="#FFCC99">'.$lng_dl['last_modified'].'</td><td bgcolor="#FFCC99">'.$lng_dl['delete'].'</td></tr>';

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
                if ($list[$i]['folder'])
                {
                    echo '<b>' . htmlspecialchars($list[$i]['filename']) . '</b>';
                    $type = $lng_dl['path'];
                } else
                {
                    $vrp = $list[$i]['mtime'] + $sdvig * 3600;
                    $vr = date("d-m-Y - H:i", $vrp);
                    echo '<a href="admin.php?act=zip&amp;zip_act=view&amp;file=' . $file . '&amp;ob=' . $list[$i]['filename'] . '&amp;file_id=' . $file_id . '">' . htmlspecialchars($list[$i]['filename']) . '</a>';
                }
                echo '</td><td>' . $type . '</td><td>' . size_convert($list[$i]['size']) . '</td><td>' . size_convert($list[$i]['compressed_size']) . '</td><td>' . $vr . '</td><td><a href="admin.php?act=zip&amp;zip_act=del&amp;file=' . $file . '&amp;ob=' . $name . '&amp;file_id=' . $file_id . '">'.$lng_dl['delete'].'</a></td></tr>';
            }

            $prop = $zip->properties();
            if ($prop['comment'])
            {
                if (iconv('UTF-8', 'UTF-8', $prop['comment']) != $prop['comment'])
                {
                    $prop['comment'] = iconv('Windows-1251', 'UTF-8', $prop['comment']);
                }
                echo '</table><table border="1" cellspacing="1" cellpadding="2"><tr><td bgcolor="#FFCC99">'.$lng_dl['comment'].': </td><td>' . functions::checkout($prop['comment'], 1, 1) . '</td></tr>';
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
                    echo '<a href="admin.php?act=zip&amp;file=' . $file . '&amp;file_id=' . $file_id . '&amp;page=' . $back_page . '">&lt;&lt;</a>&nbsp;';
                }

                // 3 страницы меньше текущей.
                $a = ($page - 3) > 0 ? ($page - 3) : 1;
                for ($back = $a; $back < $page; $back++)
                    echo '<a href="admin.php?act=zip&amp;file=' . $file . '&amp;file_id=' . $file_id . '&amp;page=' . $back . '">' . $back . '</a>&nbsp;';

                // текущая страница
                echo '<b>' . $page . '</b>&nbsp;';

                // 3 страницы больше текущей
                $a = ($page + 3) < $number ? ($page + 3) : $number;
                for ($next = $page + 1; $next <= $a; $next++)
                    echo '<a href="admin.php?act=zip&amp;file=' . $file . '&amp;file_id=' . $file_id . '&amp;page=' . $next . '">' . $next . '</a>&nbsp;';

                // Следующая
                if ($page < $number)
                {
                    $next_page = $page + 1;
                    echo '<a href="admin.php?act=zip&amp;file=' . $file . '&amp;file_id=' . $file_id . '&amp;page=' . ++$page . '">&gt;&gt;</a>';
                }
                echo '</div>';
            }
            // Конец вывода страниц навигации //


            echo '<div class="gmenu">';
            echo '<a href="admin.php?act=zip&amp;zip_act=add&amp;file=' . $file . '&amp;file_id=' . $file_id . '">'.$lng_dl['add_file'].'</a><br/>';
            echo '<a href="admin.php?act=file&amp;view=' . $file_id . '">'.$lng_dl['back_to_file'].'</a><br/>';
            echo '<a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';


            break;


    }
}
else
{
    echo $lng_dl['access_denied'];
}
