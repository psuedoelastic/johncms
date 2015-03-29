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

if ($rights >= 9)
{
    echo'<div class="phdr">'.$lng_dl['screens_setting'].'</div>';
    if (isset($_POST['submit']))
    {
        $down_scr = array();
        $down_scr = file_get_contents('set.dat');
        $down_scr = unserialize($down_scr);
        $down_scr['scr_load_img'] = intval($_POST['scr_load_img']);
        $down_scr['scr_copy'] = intval($_POST['scr_copy']);
        $down_scr['copy_position'] = intval($_POST['copy_position']);
        $down_scr['screencache'] = intval($_POST['screencache']);
        $down_scr['screenshot'] = intval($_POST['screenshot']);
        $down_scr['screenlist'] = intval($_POST['screenlist']);
        $down_scr['screenview'] = intval($_POST['screenview']);
        $down_scr['screenvideo'] = intval($_POST['screenvideo']);
        $down_scr['scrlistvideo'] = intval($_POST['scrlistvideo']);
        $down_scr['scr_size'] = intval($_POST['scr_size']);
        $down_scr['scr_size_list'] = intval($_POST['scr_size_list']);
        $down_scr['scr_copy_size'] = intval($_POST['scr_copy_size']);
        $down_scr['scr_copy_listsize'] = intval($_POST['scr_copy_listsize']);
        $down_scr['scr_copy_text'] = functions::check($_POST['scr_copy_text']);

        if($arr = fopen('set.dat', "w"))
        {
            fwrite($arr, serialize($down_scr));
            fclose($arr);
                echo'<div class="gmenu">'.$lng_dl['saved'].'</div>';
        }
        else
        {
            echo'<div class="rmenu">'.$lng_dl['setting_not_saved'].'</div>';
        }

        echo'<div class="menu"><a href="admin.php?act=set_screens">'.$lng_dl['screens_setting'].'</a></div><div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';

    }
    else
    {
        $down_scr = file_get_contents('set.dat');
        $down_scr = unserialize($down_scr);
        echo '<form action="admin.php?act=set_screens" method="post">';

        echo '<div class="menu">'.$lng_dl['image_copyright'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_scr['scr_load_img'], 'scr_load_img');
        echo $lng_dl['set_off'].'.</div>';

        echo '<div class="menu">'.$lng_dl['screen_copyright'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_scr['scr_copy'], 'scr_copy');
        echo $lng_dl['set_off'].'.</div><div class="menu">';

        echo ''.$lng_dl['position_copyright'].':<br/>'.$lng_dl['left_top'].'.';
        radio_check($down_scr['copy_position'], 'copy_position');
        echo ''.$lng_dl['right_bottom'].'.</div><div class="menu">';

        echo ''.$lng_dl['screen_caching'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_scr['screencache'], 'screencache');
        echo $lng_dl['set_off'].'.</div><div class="menu">';

        echo ''.$lng_dl['screen_in_description'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_scr['screenshot'], 'screenshot');
        echo $lng_dl['set_off'].'.</div><div class="menu">';

        echo ''.$lng_dl['screen_in_list'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_scr['screenlist'], 'screenlist');
        echo $lng_dl['set_off'].'.</div><div class="menu">';

        echo ''.$lng_dl['screen_for_theme_detail'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_scr['screenview'], 'screenview');
        echo $lng_dl['set_off'].'.</div><div class="menu">';
        if (!extension_loaded('ffmpeg'))
        echo'<b><span class="red">'.$lng_dl['must_be_off'].'</span></b><br/>';
        echo ''.$lng_dl['screen_for_video_detail'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_scr['screenvideo'], 'screenvideo');
        echo $lng_dl['set_off'].'.</div><div class="menu">';
        if (!extension_loaded('ffmpeg'))
        echo'<b><span class="red">'.$lng_dl['must_be_off'].'</span></b><br/>';
        echo ''.$lng_dl['screen_in_list_video'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_scr['scrlistvideo'], 'scrlistvideo');
        echo $lng_dl['set_off'].'.</div><div class="menu">';

        echo ''.$lng_dl['screen_size'].':<br/>
        <input type="number" name="scr_size" value="'.$down_scr['scr_size'].'"/> px.<br/>
        <small>'.$lng_dl['screen_size_msg'].'</small></div>
        <div class="menu">';

        echo ''.$lng_dl['screen_size_in_list'].':<br/>
        <input type="number" name="scr_size_list" value="'.$down_scr['scr_size_list'].'"/> px.<br/>
        <small>'.$lng_dl['screen_size_msg'].'</small></div>
        <div class="menu">';

        echo ''.$lng_dl['copyright_size_detail'].':<br/>
        <input type="number" name="scr_copy_size" value="'.$down_scr['scr_copy_size'].'"/></div>
        <div class="menu">';

        echo ''.$lng_dl['copyright_size_list'].':<br/>
        <input type="number" name="scr_copy_listsize" value="'.$down_scr['scr_copy_listsize'].'"/></div>
        <div class="menu">';

        echo ''.$lng_dl['copyright_text'].':<br/>
        <input type="text" name="scr_copy_text" value="'.$down_scr['scr_copy_text'].'"/></div>
        <div class="menu">';

        echo '<input type="submit" name="submit" value="'.$lng['save'].'"/></div>
        </form>';
        echo'<div class="gmenu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
    }
}else
{
    echo $lng_dl['access_denied'];
}