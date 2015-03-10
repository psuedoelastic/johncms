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

define('_IN_JOHNCMS', 1);
$headmod = 'loadview';
require_once '../incfiles/core.php';
require_once 'functions.php';
$viewf = intval($_GET['view']);
$file = mysql_query("SELECT * FROM `downfiles` WHERE `id` = '" . $viewf . "'");
if (mysql_num_rows($file))
{
    $file = mysql_fetch_array($file);
    $dopway = str_replace(basename($file['way']), '', $file['way']);
    $file22 = mysql_query("SELECT * FROM `downfiles` WHERE `pathid` = '" . $viewf . "' AND `type` = 1");
    $namee = explode('||||', $file['name']);
    $textl = str_replace('#FILE_NAME#', $namee[0], $lng_dl['file_browser_title']);
    require_once '../incfiles/head.php';
    if (!$file['size'])
    {
        $siz = filesize($loadroot . '/' . $file['way']);
        mysql_query("UPDATE `downfiles` set `size` = '" . $siz . "' WHERE `id` = '" . $viewf . "'");
    }
    else
    {
        $siz = $file['size'];
    }
    $filtime = date("d.m.Y", $file['time']);

    $nadir = $file['pathid'];
    $pat = "";
    while ($nadir != "")
    {

        $dnew = mysql_query("select * from `downpath` where id = '" . $nadir . "';");
        $dnew1 = mysql_fetch_array($dnew);
        $pat = '<a href="dir_' . $dnew1['id'] . '.html" title="' . $dnew1['name'] . '">' .
            $dnew1['name'] . '</a> &gt;  ' . $pat;

        $nadir = $dnew1[refid];
    }

    $tf = pathinfo($file['way'], PATHINFO_EXTENSION);
    echo '<div class="phdr"><a href="index.html">'.$lng_dl['downloads'].'</a> ' . $pat .
        '<strong>' . str_replace('_', ' ', $namee[0]) . '</strong> [' . size_convert($siz) .
        ']</div>';
    $gol = explode('|', $file['gol']);
    echo '<div class="menu">'.$lng_dl['rating'].': ' . $rating = $file['rating'] ? $file['rating'] : '0';
    echo '&nbsp;'.$lng_dl['marks'].': ' . $gol1 = $file['rating'] ? count($gol) : '0';
    echo '<br/>';
    echo rat_star($file['rating']) . '</div>';


    $idd = explode('|', $file['gol']);

    if ($user_id && !in_array("$user_id", $idd))
    {
        echo '<div class="menu">'.$lng_dl['set_mark'].': <a href="index.php?act=rat&amp;id=' . $file['id'] .
            '&amp;rat=1">1</a> | <a href="index.php?act=rat&amp;id=' . $file['id'] .
            '&amp;rat=2">2</a> |<a href="index.php?act=rat&amp;id=' . $file['id'] .
            '&amp;rat=3">3</a> | <a href="index.php?act=rat&amp;id=' . $file['id'] .
            '&amp;rat=4">4</a> | <a href="index.php?act=rat&amp;id=' . $file['id'] .
            '&amp;rat=5">5</a></div>';
    }

    echo '<div class="menu"><b>'.$lng_dl['file_type'].':</b> ' . $tf . '</div>';

    //////////////////////////
    ////// Скриншоты /////////
    //////////////////////////
    $scr = mysql_result(mysql_query("SELECT COUNT(*) FROM `downscreen` WHERE `fileid` = '".$viewf."'"), 0);
    if ($scr)
    {
        echo '<div class="menu">';
        $screen = mysql_query("SELECT * FROM `downscreen` WHERE `fileid` = '".$viewf."'");
        $i = 1;
        while ($screen1 = mysql_fetch_array($screen))
        {
            if ($i == 1)
            {
                if ($down_setting['screenshot'])
                    echo '<img src="graftemp/' . $screen1[way] . '" alt="Скриншот..."/><br/>';
                echo $lng_dl['screen'].': ';
            }

            if (!is_file('graftemp/' . $screen1[way]))
            {
                $img = new ImageEdit($screenroot . '/' . $screen1['way'], $down_setting['scr_size']);
                $img->setQuality(90);
                if ($down_setting['scr_copy'])
                    $img->setCopy($down_setting['scr_copy_size'], $down_setting['scr_copy_text']);
                $img->saveImage('graftemp/' . $screen1[way]);
            }
            echo '<a href="getthumb.php?file=screens/' . $screen1[way] .
                '&amp;size=0&amp;q=100&amp;copy=' . $down_setting['scr_copy_text'] . '">' . $i .
                '</a> ';
            $i++;
        }
        echo '<br/>';
        echo '</div>';
    }

    /////////////////////////
    ///// Скрин к темам /////
    /////////////////////////
    echo '<div class="menu">';
    if ($down_setting['screenview'])
    {
        if ($tf == 'thm')
        {
            if (!is_file($loadroot . '/' . $file['way'] . '.GIF'))
                autoscreen_thm($loadroot . '/' . $file['way'], 128, 160, $loadroot . '/' . $file['way'] .
                    '.GIF');
            echo '<img src="' . $loadroot . '/' . $file['way'] .
                '.GIF" alt="'.$lng_dl['screen'].'"/><br/>';
        } elseif ($tf == 'nth')
        {
            if (!is_file($loadroot . '/' . $file['way'] . '.GIF'))
                autoscreen_nth($loadroot . '/' . $file['way'], 128, 160, $loadroot . '/' . $file['way'] .
                    '.GIF');
            echo '<img src="' . $loadroot . '/' . $file['way'] .
                '.GIF" alt="'.$lng_dl['screen'].'"/><br/>';
        }
    }
    /////////////////////////
    ///// Скрин к видео /////
    /////////////////////////
    //TODO:Переделать обработку видео...
    if ($tf == '3gp' or $tf == 'avi' or $tf == 'mp4')
    {
        if ($down_setting['screenvideo'])
        {
            if (!is_file($loadroot . '/' . $file['way'] . '.GIF'))
                autoscreen_video($loadroot . '/' . $file['way'], $loadroot . '/' . $file['way'] .
                    '.GIF', 132, 96);
            echo '<img src="' . $loadroot . '/' . $file['way'] .
                '.GIF" alt="'.$lng_dl['screen'].'"/><br/>';
        }
        if ($down_setting['infvideo'])
        {
            $media = new ffmpeg_movie($loadroot . '/' . $file['way']);
            echo $lng_dl['time_move'].': ' . date('m:s', $media->getDuration()) . '<br/>';
            echo $lng_dl['frame_size'].': ' . $media->getFrameHeight() . 'x' . $media->getFrameWidth() .
                '<br/>';
            echo $lng_dl['bitrate'].': ' . $media->getVideoBitRate() . 'kpbs <br/>';
        }
    }

    ////////////////////////////////////
    //////// Скрин к картинке //////////
    ////////////////////////////////////
    if ($tf == 'gif' or $tf == 'png' or $tf == 'jpg' or $tf == 'jpeg')
    {
        if (!$down_setting['screencache'])
        {
            echo '<img src="getthumb.php?file=' . $loadroot . '/' . $file['way'] .
                '&amp;size=120" alt="'.$lng_dl['screen'].'"/><br/>';
        } else
        {
            if (!is_file('graftemp/' . $file['id'] . '.big.' . $tf))
            {
                $img = new ImageEdit($loadroot . '/' . $file['way'], $down_setting['scr_size']);
                // Подаём оригинал и максимальный размер
                $img->setQuality(90);
                // Качество
                if ($down_setting['scr_copy'])
                    $img->setCopy($down_setting['scr_copy_size'], $down_setting['scr_copy_text']);
                $img->saveImage('graftemp/' . $file['id'] . '.big.' . $tf);
                // Сохраняем во временную папку
            }
            echo '<img src="graftemp/' . $file['id'] . '.big.' . $tf .
                '" alt="'.$lng_dl['screen'].'"/><br/>';
        }
    }


    echo '<b>'.$lng_dl['added'].':</b> ' . $filtime . '</div>';
    if ($file['login'])
    {
        echo '<div class="menu"><b>'.$lng_dl['creator'].':</b> <a href="../users/profile.php?user=' . $file['user_id'] . '">' . $file['login'] . '</a></div>';
    }
    echo '<div class="menu"><b>'.$lng_dl['loaded'].':</b> ' . ending($file['count']) . '</div>';

    ///////////////////////////////
    //////// Если это JAR /////////
    ///////////////////////////////
    if ($tf == 'jar')
    {
        $archive2 = new JarInfo($loadroot . '/' . $file['way']);

        //$archive2->setDeleteConfirm('Скачать программу повторно можно с сайта symbos.su');
        //$archive2->saveManifest();

        if ($down_setting['jar_version'] > 0 && $archive2->getVersion())
            echo '<div class="menu"><b>'.$lng_dl['version'].':</b> ' . $archive2->getVersion() . '</div>';
        if ($down_setting['jar_name'] > 0 && $archive2->getName())
            echo '<div class="menu"><b>'.$lng_dl['name'].':</b> ' . $archive2->getName() . '</div>';
        if ($down_setting['jar_vendor'] > 0 && $archive2->getVendor())
            echo '<div class="menu"><b>'.$lng_dl['vendor'].':</b> ' . $archive2->getVendor() .
                '</div>';
        if ($down_setting['jar_profile'] > 0 && $archive2->getProfile())
            echo '<div class="menu"><b>'.$lng_dl['profile'].':</b> ' . $archive2->getProfile() . '</div>';
        if ($down_setting['jar_url'] > 0 && $archive2->getUrl())
            echo '<div class="menu"><b>Url:</b> ' . $archive2->getUrl() . '</div>';
        // Получаем иконку если её ещё нет.
        $icon = 'graftemp/' . $file['id'] . '.icon.png';
        if (!is_file('graftemp/' . $file['id'] . '.icon.png'))
        {
            if (!$archive2->getIcon('graftemp/' . $file['id'] . '.icon.png'))
                $icon = 'img/jar.png';
        }
    }
    ///////////////////////
    ////// Описание ///////
    ///////////////////////
    echo '<div class="menu">';
    if ($file['desc'])
    {
        echo functions::checkout($file['desc'], 1, 1);
    } else
    {
        echo $lng_dl['description_is_empty'];
    }
    echo '</div>';

    /////////////////////////
    ////// Инфа о mp3 ///////
    /////////////////////////
    if ($down_setting['mp3info'])
    {
        if ($tf == 'mp3')
        {
            echo '<div class="menu">';

            require_once 'classes/classAudioFile.php';
            $f = new AudioFile;
            // добавляем аудиофайл
            $f->loadFile($loadroot . '/' . $file[way]);
            // выводим информацию
            echo functions::checkout(str_replace('&', '&amp;', $f->printSampleInfo()), 1, 1);


            echo '<b>'.$lng_dl['play'].':</b> <br/>';
            echo '<object type="application/x-shockwave-flash" data="mp3player.swf" width="200" height="20" id="mp3player" name="mp3player">';
            echo '<param name="movie" value="mp3player.swf" />';
            echo '<param name="flashvars" value="mp3=' . $loadroot . '/' . $file['way'] . '" />';
            echo '</object>';
            echo '</div>';
        }
    }


    ///////////////////////////////////
    ////// Обсуждение на форуме ///////
    ///////////////////////////////////
    if ($file['themeid'])
    {
        echo '<div class="menu"><img src="img/peopl.png" alt="."/> <a href="../forum/index.php?id=' .
            $file['themeid'] . '">'.$lng_dl['discussion_on_forum'].'</a></div>';
    }

    echo '<div class="menu"><small>'.str_replace('#FILE_ID#', $viewf, $lng_dl['policies']).'</small></div>';

    if ($dostguest == 'open')
    {

        if (!$namee[1])
        {
            $namee[1] = $lng_dl['primary_file'];
        }
        if (!isset($icon))
            $icon = 'img/save.png';

        echo '<div class="menu"><img src="' . $icon .
            '" alt="."/> <a href="loadfile.php?down=' . $file['way'] . '">'.$lng_dl['download'].' ' . $namee[1] .
            '</a>';

        //////////////////////////
        ////// Получаем JAD //////
        //////////////////////////
        if ($tf == 'jar' && $down_setting['jadgen'])
        {
            $jadf = str_ireplace('.jar', '.jad', $file['way']);
            $jarurl = '' . $home . '/' . $downpat . '/' . $file['way']; //// Адрес файла записываемый в JAD
            if (!is_file($loadroot . '/' . $jadf))
                $archive2->getJad($jarurl);
            echo '&nbsp;<a href="loadfile.php?down=' . $file['way'] .
                '&amp;jad=1">[JAD]</a>';
        }
        echo ' [' . $file['count'] . ']</div>';

        ///////////////////////////////////////
        ///// Выбор размеров для картинок /////
        ///////////////////////////////////////
        if ($tf == 'gif' || $tf == 'jpg' || $tf == 'png')
        {
            echo "
<div class='menu'>
<form action='image.php' method='get'>
<img src=\"img/view.png\" alt='.'/> <b>".$lng_dl['download_with_size'].":</b><br/>
<select title='Выберите размер' name='size'>";
            echo '<option value="640x480">640x480</option><option value="240x320">240x320</option><option value="208x208">208x208</option><option value="176x220">176x220</option><option value="176x208">176x208</option><option value="132x176">132x176</option><option value="128x160">128x160</option></select>
<input type="hidden" name="file" value="' . $loadroot . '/' . $file['way'] . '"/>
<input type="submit" value="'.$lng_dl['download'].'"/></form></div>';
        }

        ///////////////////////////////
        ////// Просмотр архивов ///////
        ///////////////////////////////
        if ($down_setting['zipview'])
        {
            if ($tf == "zip")
            {
                echo '<div class="menu"><img src="img/rar.png" alt="."/> <a href="zipview.php?file=' .
                    $file['way'] . '&amp;file_id=' . $viewf . '">'.$lng_dl['view_zip'].'</a></div>';
            }
        }

        echo '<div class="menu">'.$lng_dl['copy_url'].':<br/>
<input type="text" name="url" value="' . $home . str_replace("..", "", $filesroot) .
            '/loadfile.php?down=' . $file['way'] . '"/></div>';

        ////////////////////////////////
        ///// Дополнительные файлы /////
        ////////////////////////////////
        if (mysql_num_rows($file22))
        {
            while ($file2 = mysql_fetch_array($file22))
            {
                $tf = pathinfo($file2['way'], PATHINFO_EXTENSION);
                echo '<div class="menu"><img src="img/save.png" alt="."/> <a href="loadfile.php?down=' .
                    $file2['way'] . '">'.$lng_dl['download'].' ' . $file2['name'] . '</a>';
                if ($tf == "jar" && $down_setting['jadgen'])
                {
                    $archive2 = new JarInfo($loadroot . '/' . $file2['way']);
                    $jadf = str_ireplace('.jar', '.jad', $file2['way']);
                    $jarurl = '' . $home . '/' . $downpat . '/' . $file2['way']; //// Адрес файла записываемый в JAD
                    if (!is_file($loadroot . '/' . $jadf))
                        $archive2->getJad($jarurl);
                    echo '&nbsp;<a href="loadfile.php?down=' . $file2['way'] .
                        '&amp;jad=1">[JAD]</a>';
                }
                echo '&nbsp;[' . $file2['count'] . ']<br/>'.functions::checkout($file2['desc'], 1, 1).'</div>';

                if ($tf == "zip" && $down_setting['zipview'])
                    echo '<img src="img/rar.png" alt="."/> <a href="zipview.php?file=' . $file2[way] .
                        '&amp;file_id=' . $viewf . '">'.$lng_dl['view_zip'].'</a><br/>';
            }
        }
    }
    else
    {
        echo '<b>'.$lng_dl['download_register_only'].'</b><br/>';
    }

    //////////////////////////////////
    /////// Поделиться ссылкой ///////
    //////////////////////////////////
    echo '<div class="menu">'.$lng_dl['share'].':<br/>
    <noindex>
    <a href="http://www.facebook.com/share.php?u=' . $home . '/download/'.name_replace($namee[0]).'_' .
        $viewf . '.html&amp;t='.str_replace('#FILE_NAME#', $namee[0], $lng_dl['file_browser_title']).'"><img src="img/facebook.gif" alt="FaceBook" title="'.$lng_dl['share_on'].'FaceBook"/></a>&nbsp;<a href="http://twitter.com/home/?status=' .
        $home . '/download/'.name_replace($namee[0]).'_' . $viewf . '.html+'.str_replace('#FILE_NAME#', $namee[0], $lng_dl['file_browser_title']).'"><img src="img/twitter.gif" alt="twitter" title="'.$lng_dl['share_on'].'twitter"/></a>&nbsp;<a href="http://vkontakte.ru/share.php?url=' .
        $home . '/download/'.name_replace($namee[0]).'_' . $viewf .
        '.html"><img src="img/vk.gif" alt="Вконтакте" title="'.$lng_dl['share_on'].'Вконтакте"/></a>&nbsp;<a href="http://connect.mail.ru/share?share_url=' .
        $home . '/download/'.name_replace($namee[0]).'_' . $viewf .
        '.html"><img src="img/mailru.gif" alt="Mail.ru" title="'.$lng_dl['share_on'].'mail.ru"/></a>&nbsp;<a href="http://www.livejournal.com/update.bml?event=' .
        $home . '/download/'.name_replace($namee[0]).'_'. $viewf . '.html&amp;subject='.str_replace('#FILE_NAME#', $namee[0], $lng_dl['file_browser_title']).'"><img src="img/lj.gif" alt="Livejournal" title="'.$lng_dl['share_on'].'Livejournal"/></a>
        </noindex>
    </div>';

    if ($down_setting['komm'])
    {
        $totalk = mysql_result(mysql_query("SELECT COUNT(*) FROM `downkomm` WHERE `fileid` = '" . $viewf . "';"), 0);
        echo '<div class="menu"><img src="img/edit.png" alt="."/> <a href="komm.php?id=' .
            $viewf . '">'.$lng_dl['comments'].'</a> (' . $totalk . ')</div>';
    }

    if ($user_id)
        echo '<div class="menu"><img src="img/apply.png" alt="."/> <a href="index.php?act=bookmarks&amp;dejst=add&amp;id=' .
            $viewf . '">'.$lng_dl['add_bookmark'].'</a></div>';

} else
{
    echo '<div class="rmenu">'.$lng_dl['file_not_found'].'</div>';
}
echo '<div class="phdr"><a href="dir_' . $file['pathid'] .
    '.html">'.$lng['back'].'</a></div>';

require_once '../incfiles/end.php';
