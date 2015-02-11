<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/
defined('_IN_JOHNCMS') or die('Error:restricted access');
// Версия скрипта. Не менять!!!
$script_version = '6.2';

$lng_dl = core::load_lng('downloads');

////// Основные папки /////
$downpat = 'download/files';
$filesroot = '../download'; /////////// Главная папка со скриптом
$screenroot = $filesroot . '/screens'; ////////// Папка со скриншотами
$loadroot = $filesroot . '/files';  /////// Папка с файлами
require_once 'classes/classImageEdit.php';
require_once 'classes/classJarInfo.php';
//////////// Получаем основные настройки загруза ////////////
$file = $_SERVER['DOCUMENT_ROOT'] . '/download/set.dat';
$down_setting = file_get_contents($file);
$down_setting = unserialize($down_setting);

//$kmess = $down_setting['kmess'];

/**
 * Функция вывода рейтинга файла в виде звёздочек
 */
function rat_star($rat)
{
    // $rat - Числовой рейтинг
    for ($i = 1; $i <= 5; $i++)
    {
        if ($rat >= $i)
            echo '<img src="img/star.png" alt="' . $i . '"/>';
        else
            echo '<img src="img/star_empty.png" alt="' . $i . '"/>';
    }
}


function auto_clean_cache()
{
    // Функция очистки кэша счётчиков //    
    $dir = scandir('cache/');
    $ii = count($dir);
    for ($i = 3; $i < $ii; $i++)
    {
        if (is_file('cache/' . $dir[$i]))
            unlink('cache/' . $dir[$i]);
    }
}


function simba_delcat($directory)
{
    /////////////////////////////////////
    // Функция удаления папок и файлов //
    /////////////////////////////////////
    $dir = scandir($directory);
    $dir = array_slice($dir, 2);
    foreach ($dir as $file)
    {
        $file = $directory . '/' . $file;
        if (is_dir($file))
        {
            simba_delcat($file);
            if (is_dir($file))
                rmdir($file);
        } else
        {
            unlink($file);
        }
    }
    rmdir($directory);
}


/////////////////////////////////
/////// Включить/выключить //////
/////////////////////////////////
function radio_check($val, $name)
{
    // Чтобы сто раз не писать этот код, напишем функцию //
    // $val - текущее состояние
    // $name - имя формы.
    if ($val)
    {
        echo '<input name="' . $name . '" type="radio" value="1" checked="checked" />';
    } else
    {
        echo '<input name="' . $name . '" type="radio" value="1" />';
    }
    echo ' &nbsp; &nbsp; ';
    if (!$val)
    {
        echo '<input name="' . $name . '" type="radio" value="0" checked="checked" />';
    } else
    {
        echo '<input name="' . $name . '" type="radio" value="0" />';
    }
}

///////////////////////////////////////////
// Функция вывода файла в списке файлов ///
///////////////////////////////////////////
// $arr - Массив с инфой о файле из базы
// $tf - тип файла.
// $set_view - массив с настройками вывода.
// Возможные элементы:
// [variant] - Вариант вывода. 0- темы,видео,картинки. 1 - Обычный файл
// [size] - вывод размера. 1-ссылкой, 0-текстом.
// [desc] - вывод описания 1 - вкл., 2 - выкл.
// [count] - вывод количества скачиваний. 1-вкл., 0-выкл.
// [comments] - вывод комментариев. 1-вкл., 0-выкл.
// [add_date] - Вывод времени добавления файла. 1-вкл., 0-выкл.
// [rating] - Вывод рейтинга файла.
// [way_to_path] - Путь к файлу.
// [link_download] - Ссылка "Скачать"
// [div] - Доп инфа в диве sub
// [admin] - Админские ссылки
function f_preview($arr = array(), $set_view = array(), $tf = '')
{

    global $user_id, $loadroot, $down_setting, $home, $dostguest, $lng_dl;
    // Определяем выводимую иконку
    $arc = array('rar', 'zip', 'tar');
    $music = array('mp3', 'wma', 'ogg', 'flac', 'wav', 'aac', 'amr');
    $video = array('3gp', 'avi', 'mp4');
    $images = array('gif', 'jpg', 'png', 'jpeg');
    $icon = 'img/file.gif';
    if (in_array($tf, $arc))
        $icon = 'img/rar.png';
    if (in_array($tf, $music))
        $icon = 'img/mp3.png';

    // Превьюшки к темам.
    if ($tf == 'thm' && $down_setting['screenlist'])
    {
        if ($down_setting['screencache'])
        {
            $icon = $file_check = 'graftemp/thm_' . $arr['id'] . '.GIF'; //Путь к сохраняемому файлу
            $siz_h = 60;
            $siz_w = 75; //Размеры превьюшки
        } else
        {
            $siz_h = 128;
            $siz_w = 160;
            $file_check = $loadroot . '/' . $arr['way'] . '.GIF';
            $icon = 'prew.php?id=' . $arr['way'] . '.GIF&amp;way=1';
        }
        if (!is_file($file_check))
            autoscreen_thm($loadroot . '/' . $arr['way'], $siz_h, $siz_w, $file_check);

    } elseif ($tf == 'nth' && $down_setting['screenlist'])
    {
        if ($down_setting['screencache'])
        {
            $icon = $file_check = 'graftemp/nth_' . $arr['id'] . '.GIF'; //Путь к сохраняемому файлу
            $siz_h = 60;
            $siz_w = 75; //Размеры превьюшки
        } else
        {
            $siz_h = 128;
            $siz_w = 160;
            $file_check = $loadroot . '/' . $arr['way'] . '.GIF';
            $icon = 'prew.php?id=' . $arr['way'] . '.GIF&amp;way=1';
        }
        if (!is_file($file_check))
            autoscreen_nth($loadroot . '/' . $arr['way'], $siz_h, $siz_w, $file_check);

    }

    // Превьюшки к видео
    if (in_array($tf, $video) && $down_setting['scrlistvideo'])
    {
        if ($down_setting['screencache'])
        {
            $icon = $file_check = 'graftemp/video_' . $arr['id'] . '.GIF'; //Путь к сохраняемому файлу
            $siz_h = 75;
            $siz_w = 60; //Размеры превьюшки
        } else
        {
            $siz_h = 132;
            $siz_w = 96;
            $file_check = $loadroot . '/' . $arr['way'] . '.GIF';
            $icon = 'prew.php?id=' . $arr['way'] . '.GIF&amp;way=1';
        }
        if (!is_file($file_check))
            autoscreen_video($loadroot . '/' . $arr['way'], $file_check, $siz_h, $siz_w);
    }

    //Превьюшки к картинкам
    if (in_array($tf, $images))
    {
        if ($down_setting['screencache'])
        {
            if (!is_file('graftemp/' . $arr['id'] . '.mini.' . $tf))
            {
                $img = new ImageEdit($loadroot . '/' . $arr['way'], $down_setting['scr_size_list']);
                if ($down_setting['scr_copy'])
                    $img->setCopy($down_setting['scr_copy_listsize'], $down_setting['scr_copy_text']);
                $img->setQuality(80);
                $img->saveImage('graftemp/' . $arr['id'] . '.mini.' . $tf);
            }
            $icon = 'graftemp/' . $arr['id'] . '.mini.' . $tf;
        } else
            $icon = 'getthumb.php?file=' . $loadroot . '/' . $arr['way'] . '&amp;size=80';
    }

    // Иконки к jar
    if ($tf == 'jar')
    {
        $archive2 = new JarInfo($loadroot . '/' . $arr['way']);
        $icon = 'graftemp/' . $arr['id'] . '.icon.png';
        if (!is_file('graftemp/' . $arr['id'] . '.icon.png'))
        {
            if (!$archive2->getIcon('graftemp/' . $arr['id'] . '.icon.png'))
                $icon = 'img/jar.png';
        }
    }


    if (!$arr['size'])
    {
        $arr['size'] = filesize($loadroot . '/' . $arr['way']);
        mysql_query("UPDATE `downfiles` set `size` = '" . $arr['size'] . "' WHERE `id` = '" . $arr['id'] . "'");
    }

    $name = explode('||||', $arr['name']);
    if ($set_view['variant'])
        echo '<img src="' . $icon . '" alt="."/> <a href="' . name_replace($name[0]) . '_' . $arr['id'] . '.html">' . $name[0] . '</a>';
    elseif ($set_view['admin'])
        echo '<img src="' . $icon . '" alt="."/> <a href="admin.php?act=file&amp;view=' . $arr['id'] . '">' . $name[0] . '</a>';
    else
        echo '<a href="' . name_replace($name[0]) . '_' . $arr['id'] . '.html"><img src="' . $icon . '" alt="' . $name[0] . '" /></a><br />';

    if ($set_view['desc'])
    { // Описание

        if ($arr['desc'])
            $desc = mb_strlen($arr['desc']) > 100 ? functions::checkout(mb_substr($arr['desc'], 0, 100), 2, 1) . '...' : functions::checkout($arr['desc'], 2, 1);
        else
            $desc = $lng_dl['description_is_empty'];

        echo '<div class="sub">' . $desc . '</div>';

    }
    if ($set_view['div'])
        echo '<div class="sub">';

    // Ссылка скачать
    if ($set_view['link_download'])
        echo '<a href="loadfile.php?down=' . $arr['way'] . '">' . $lng_dl['download'] . '</a>&nbsp;';

    // размер (ссылка/текст)
    if ($dostguest == 'open' && $set_view['size'])
        echo '[<a href="loadfile.php?down=' . $arr['way'] . '">' . size_convert($arr['size']) . '</a>]';
    else
        echo '&nbsp;[' . size_convert($arr['size']) . ']';

    // Скачивания
    if ($set_view['count'])
        echo '[' . $arr['count'] . ']';

    if ($down_setting['komm'] && $set_view['comments'])
    {
        $totalk = mysql_result(mysql_query("SELECT COUNT(*) FROM `downkomm` WHERE `fileid` = '" . $arr['id'] . "';"), 0);
        echo '[<a href="komm.php?id=' . $arr['id'] . '">' . $lng_dl['comment_small'] . ' ' . $totalk . '</a>]&nbsp;';
    }

    if ($set_view['rating'])
        echo $lng_dl['rating'] . ': ' . $arr['rating'];

    // Время
    if ($set_view['add_date'])
        echo '&nbsp;[' . date("d.m.Y", $arr['time']) . ']';


    if ($set_view['way_to_path'])
    {
        $nadir = $arr['pathid'];
        $pat = '';
        while ($nadir != "")
        {
            $way_to = mysql_fetch_array(mysql_query("select * from `downpath` where id = '" . $nadir . "';"));
            $pat = '<a href="dir_' . $way_to['id'] . '.html">' . $way_to['name'] . '</a> &gt;  ' . $pat . '';
            $nadir = $way_to['refid'];
        }
        $pat1 = mb_substr($pat, 0, mb_strlen($pat) - 6);
        echo '<div class="sub">' . $pat1 . '</div>';
    }

    if ($set_view['div'])
        echo '</div>';

    if ($set_view['admin'])
        echo $set_view['admin'];


}


///////////////////////////////////////////////////////////////////////////////////
/////////// Функция пересчёта размера файла из байтов в нужную единицу ////////////
///////////////////////////////////////////////////////////////////////////////////

function size_convert($size)
{
    $size = round($size / 1024, 2);
    if ($size > 1024000)
    {
        $size = round($size / 1024000, 2) . ' Gb';
    } elseif ($size > 1024)
    {
        $size = round($size / 1024, 2) . ' mb';
    } else
    {
        $size = $size . ' kb';
    }

    return $size;
}


///////////////////////////////////////////////////////////////////////////////////
/////////// Функция вывода правильного окончания в счётчике скачиваний ////////////
///////////////////////////////////////////////////////////////////////////////////
function ending($num)
{
    $num100 = $num % 100;
    $num10 = $num % 10;
    if (($num100 >= 5 && $num100 <= 20) || ($num10 == 0) || ($num10 == 1) || ($num10 >= 5 && $num10 <= 9))
    {
        return $num . ' раз';
    } else if ($num10 >= 2 && $num10 <= 4)
    {
        return $num . ' раза';
    } else
    {
        return $num . ' раз';
    }

    return $size;
}

//TODO:Переделать автоскрины ниже...

///////////// Автоскрины к темам thm /////////////
function autoscreen_thm($theme, $g_preview_image_w, $g_preview_image_h, $name)
{
    global $home;
    include_once 'classes/tar.php';
    $thm = new Archive_Tar($theme);
    if (!$file = $thm->extractInString('Theme.xml') or !$file = $thm->extractInString(pathinfo($theme, PATHINFO_FILENAME) . '.xml'))
    {
        $list = $thm->listContent();
        $all = sizeof($list);
        for ($i = 0; $i < $all; ++$i)
        {
            if (pathinfo($list[$i]['filename'], PATHINFO_EXTENSION) == 'xml')
            {
                $file = $thm->extractInString($list[$i]['filename']);
                break;
            }
        }
    }
    if (!$file)
    {
        preg_match('/<\?\s*xml\s*version\s*=\s*"1\.0"\s*\?>(.*)<\/.+>/isU', file_get_contents($theme), $arr);
        $file = trim($arr[0]);
    }

    $load = simplexml_load_string($file)->Standby_image['Source'] or $load = simplexml_load_string($file)->Desktop_image['Source'] or $load = simplexml_load_string($file)->Desktop_image['Source'];
    $image = $thm->extractInString(trim($load));
    $im = array_reverse(explode('.', $load));
    $im = 'imageCreateFrom' . str_ireplace('jpg', 'jpeg', trim($im[0]));
    file_put_contents($name, $image);
    $f = $im($name);
    $h = imagesy($f);
    $w = imagesx($f);
    $ratio = $w / $h;
    if ($g_preview_image_w / $g_preview_image_h > $ratio)
    {
        $g_preview_image_w = $g_preview_image_h * $ratio;
    } else
    {
        $g_preview_image_h = $g_preview_image_w / $ratio;
    }
    $new = imagecreatetruecolor($g_preview_image_w, $g_preview_image_h);
    imagecopyresized($new, $f, 0, 0, 0, 0, $g_preview_image_w, $g_preview_image_h, $w, $h);
    $icx_str = strtoupper(str_replace('http://', '', $home)); //Водяной знак в нижнем правом углу
    $icx_size = 1; // размер шрифта watermark строки
    // определяем координаты вывода текста
    $icx_x_text = $g_preview_image_w - imagefontwidth($icx_size) * strlen($icx_str) - 3;
    $icx_y_text = $g_preview_image_h - imagefontheight($icx_size) - 3;
    // определяем каким цветом на каком фоне выводить текст
    $icx_white = imagecolorallocate($new, 255, 255, 255);
    $icx_black = imagecolorallocate($new, 0, 0, 0);
    $icx_gray = imagecolorallocate($new, 127, 127, 127);
    if (imagecolorat($new, $icx_x_text, $icx_y_text) > $icx_gray)
        $icx_color = $icx_black;
    if (imagecolorat($new, $icx_x_text, $icx_y_text) < $icx_gray)
        $icx_color = $icx_white;
    // выводим текст
    imagestring($new, $icx_size, $icx_x_text - 1, $icx_y_text - 1, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text + 1, $icx_y_text + 1, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text + 1, $icx_y_text - 1, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text - 1, $icx_y_text + 1, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text - 1, $icx_y_text, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text + 1, $icx_y_text, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text, $icx_y_text - 1, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text, $icx_y_text + 1, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text, $icx_y_text, $icx_str, $icx_color);
    imageGif($new, $name);
    imagedestroy($new);
}


//////////// автоскрины к темам nth /////////////////
function autoscreen_nth($theme, $g_preview_image_w, $g_preview_image_h, $name)
{
    global $home;
    require_once 'classes/pclzip.lib.php';
    $nth = new PclZip($theme);
    $content = $nth->extract(PCLZIP_OPT_BY_NAME, 'theme_descriptor.xml', PCLZIP_OPT_EXTRACT_AS_STRING);
    if (!$content)
    {
        $content = $nth->extract(PCLZIP_OPT_BY_PREG, '\.xml$', PCLZIP_OPT_EXTRACT_AS_STRING);
    }
    $teg = simplexml_load_string($content[0]['content'])->wallpaper['src'] or $teg = simplexml_load_string($content[0]['content'])->wallpaper['main_display_graphics'];
    $image = $nth->extract(PCLZIP_OPT_BY_NAME, trim($teg), PCLZIP_OPT_EXTRACT_AS_STRING);
    $im = array_reverse(explode('.', $teg));
    $im = 'imageCreateFrom' . str_ireplace('jpg', 'jpeg', trim($im[0]));

    file_put_contents($name, $image[0]['content']);
    $f = $im($name);

    $h = imagesy($f);
    $w = imagesx($f);

    $ratio = $w / $h;
    if ($g_preview_image_w / $g_preview_image_h > $ratio)
    {
        $g_preview_image_w = $g_preview_image_h * $ratio;
    } else
    {
        $g_preview_image_h = $g_preview_image_w / $ratio;
    }

    $new = imagecreatetruecolor($g_preview_image_w, $g_preview_image_h);
    imagecopyresized($new, $f, 0, 0, 0, 0, $g_preview_image_w, $g_preview_image_h, $w, $h);
    $icx_str = strtoupper(str_replace('http://', '', $home)); //Водяной знак в нижнем правом углу
    $icx_size = 1; // размер шрифта watermark строки
    // определяем координаты вывода текста
    $icx_x_text = $g_preview_image_w - imagefontwidth($icx_size) * strlen($icx_str) - 3;
    $icx_y_text = $g_preview_image_h - imagefontheight($icx_size) - 3;
    // определяем каким цветом на каком фоне выводить текст
    $icx_white = imagecolorallocate($new, 255, 255, 255);
    $icx_black = imagecolorallocate($new, 0, 0, 0);
    $icx_gray = imagecolorallocate($new, 127, 127, 127);
    if (imagecolorat($new, $icx_x_text, $icx_y_text) > $icx_gray)
        $icx_color = $icx_black;
    if (imagecolorat($new, $icx_x_text, $icx_y_text) < $icx_gray)
        $icx_color = $icx_white;
    // выводим текст
    imagestring($new, $icx_size, $icx_x_text - 1, $icx_y_text - 1, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text + 1, $icx_y_text + 1, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text + 1, $icx_y_text - 1, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text - 1, $icx_y_text + 1, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text - 1, $icx_y_text, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text + 1, $icx_y_text, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text, $icx_y_text - 1, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text, $icx_y_text + 1, $icx_str, $icx_white - $icx_color);
    imagestring($new, $icx_size, $icx_x_text, $icx_y_text, $icx_str, $icx_color);
    imageGif($new, $name);
    imagedestroy($new);
}

/////////// Скрины к видео ////////////
function autoscreen_video($file, $name, $width, $height)
{
    //TODO:Попробовать класс для работы с видео....
    $frame = 24;
    $mov = new ffmpeg_movie($file);
    $w = $mov->GetFrameWidth();
    $h = $mov->GetFrameHeight();
    $ff_frame = $mov->getFrame($frame);
    if ($ff_frame)
    {
        $gd_image = $ff_frame->toGDImage();
        if ($gd_image)
        {
            $des_img = imagecreatetruecolor($width, $height);
            $ratio = $w / $h;

            if ($width / $height > $ratio)
            {
                $width = $height * $ratio;
            } else
            {
                $height = $width / $ratio;
            }

            $s_img = $gd_image;
            imagecopyresampled($des_img, $s_img, 0, 0, 0, 0, $width, $height, $w, $h);
            imageGif($des_img, $name);
            imagedestroy($des_img);
            imagedestroy($s_img);
        }
    }
}

///////////// Установка доступа гостям /////////////\

$dostguest = ($user_id || $down_setting['guest'] == 1) ? 'open' : 'close';


//////////////////////////////
////// Перекодировщик ////////
//////////////////////////////
function perekodname($zapros)
{
    if (mb_check_encoding($zapros, 'UTF-8'))
    {
    } elseif (mb_check_encoding($zapros, 'windows-1251'))
    {
        $zapros = iconv("windows-1251", "UTF-8", $zapros);
    } elseif (mb_check_encoding($zapros, 'KOI8-R'))
    {
        $zapros = iconv("KOI8-R", "UTF-8", $zapros);
    }

    return $zapros;
}

////////////////
//// ББ-коды ///
////////////////
function bb_past($form_name, $field_name)
{
    $out = '<div class="menu"><script language="JavaScript" type="text/javascript">
    function tag(text1, text2) {
    if ((document.selection)) {
    document.form.msg.focus();
    document.form.document.selection.createRange().text = text1+document.form.document.selection.createRange().text+text2;
    } else if(document.forms[\'' . $form_name . '\'].elements[\'' . $field_name . '\'].selectionStart!=undefined) {
    var element = document.forms[\'' . $form_name . '\'].elements[\'' . $field_name . '\'];
    var str = element.value;
    var start = element.selectionStart;
    var length = element.selectionEnd - element.selectionStart;
    element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);
    } else document.form.msg.value += text1+text2;
    }
    </script>
    <a href="javascript:tag(\'[url=]\', \'[/url]\');"><img src="img/bb/link.gif" alt="url" title="Ссылка" /></a><a href="javascript:tag(\'[b]\', \'[/b]\');"><img src="img/bb/b.gif" alt="b" title="Жирный"/></a><a href="javascript:tag(\'[i]\', \'[/i]\');"><img src="img/bb/i.gif" alt="i" title="Наклонный"/></a><a href="javascript:tag(\'[u]\', \'[/u]\');"><img src="img/bb/u.gif" alt="u" title="Подчёркнутый"/></a><a href="javascript:tag(\'[s]\', \'[/s]\');"><img src="img/bb/s.gif" alt="s" title="Перечёркнутый"/></a><a href="javascript:tag(\'[c]\', \'[/c]\');"><img src="img/bb/quote.gif" alt="quote" title="Цитата"/></a><a href="javascript:tag(\'[php]\', \'[/php]\');"><img src="img/bb/code.gif" alt="code" title="Код"/></a>
    <br/><a href="javascript:tag(\'[red]\', \'[/red]\');"><img src="img/bb/red.gif" alt="code" title="Красный"/></a><a href="javascript:tag(\'[green]\', \'[/green]\');"><img src="img/bb/green.gif" alt="code" title="Зелёный"/></a><a href="javascript:tag(\'[blue]\', \'[/blue]\');"><img src="img/bb/blue.gif" alt="code" title="Синий"/></a></div>';

    return $out;
}

///////////////////////////////
/// Функция обработки имени ///
///////////////////////////////
function name_replace($name)
{
    //////////// Транслитируем имя файла ///////
    $trans1 = array("Ё", "Ж", "Ч", "Ш", "Щ", "Э", "Ю", "Я", "ё", "ж", "ч", "ш", "щ", "э", "ю", "я", "А", "Б", "В", "Г", "Д", "Е", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ы", "а", "б", "в", "г", "д", "е", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ь", "Ь", "Ъ", "ъ", "ы");
    $trans2 = array("JO", "ZH", "CH", "SH", "SCH", "JE", "JY", "JA", "jo", "zh", "ch", "sh", "sch", "je", "jy", "ja", "A", "B", "V", "G", "D", "E", "Z", "I", "J", "K", "L", "M", "N", "O", "P", "R", "S", "T", "U", "F", "H", "C", "Y", "a", "b", "v", "g", "d", "e", "z", "i", "j", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "c", "q", "Q", "_", "_", "y");
    $ftp = str_replace($trans1, $trans2, $name);
    ////////// Вырезаем/заменяем различные неподходящие символы ////////
    $ftp = str_replace(' ', '_', $ftp);
    $ftp = str_replace('\'', '_', $ftp);
    $simb = array('?', '/', '|', '~', '+', '=', '%', '^', '&', '@', '!', '`', '*', '$', '#', '№', '"', ':', ';');
    $ftp = str_replace($simb, "", $ftp);
    $ftp = str_replace("'", "", $ftp);

    return $ftp;

}


////////////////////
// Счётчик файлов //
////////////////////

function dcount_simba()
{
    global $rights, $home, $down_setting, $lng_dl;

    $cachetime = time() - $down_setting['cachetime'] * 3600; // Время кэширования
    if ($down_setting['cachetime'] > 0 && is_file('download/cache/all.dat') && filemtime('download/cache/all.dat') > $cachetime)
    {
        // Открываем файл кэша если существует и не устарел
        $out = file_get_contents('download/cache/all.dat');

    } else
    {
        // Считаем если кэша нет или не существует
        $out = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `type` != 1 AND `status` = 1"), 0);
        // Считаем новые файлы
        $old = time() - ($down_setting['newtime'] * 24 * 3600);
        $countnf = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `time` > '" . $old . "' AND `status` = 1 AND `type` != 1"), 0);
        if ($countnf != 0)
            $out = $out . '/ <span class="red"><a href="' . $home . '/download/new.html">+' . $countnf . '</a></span>';
        if ($down_setting['cachetime'] > 0)
        {
            $cache_file = fopen('download/cache/all.dat', "w");
            fwrite($cache_file, $out);
            fclose($cache_file);
        }
    }
    // Подсчёт файлов на модерации вне зависимости от кэша
    if ($rights == 4 || $rights >= 9)
    {
        $countf = mysql_result(mysql_query("SELECT COUNT(*) FROM `downfiles` WHERE `type` != 1 AND `status` = 0"), 0);
        if ($countf > 0)
            $out = $out . '/ <a href="' . $home . '/download/admin.php?act=mod"><font color="#ff0000">'.$lng_dl['moderation'].': ' . $countf . '</font></a>';
    }

    return $out;
}


?>