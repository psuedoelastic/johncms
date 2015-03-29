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
    echo'<div class="phdr">'.$lng['settings'].'</div>';
    if (isset($_POST['submit']))
   {
        $down_setting = array();
        $down_setting = file_get_contents('set.dat');
        $down_setting = unserialize($down_setting);

        $down_setting['guest'] = intval($_POST['gues']);
        $down_setting['komm'] = intval($_POST['komclose']);
        $down_setting['kmess'] = intval($_POST['kol']);
        $down_setting['priv'] = intval($_POST['privinf']);
        $down_setting['priv_user'] = intval($_POST['priv_user']);
        $down_setting['infvideo'] = intval($_POST['infvideo']);
        $down_setting['zipview'] = intval($_POST['zipview']);
        $down_setting['mp3info'] = intval($_POST['mp3info']);
        $down_setting['tmini'] = intval($_POST['tmini']);
        $down_setting['vmini'] = intval($_POST['vmini']);
        $down_setting['bb'] = intval($_POST['bb']);
        $down_setting['filesize'] = intval($_POST['filesize']);
        $down_setting['cachetime'] = intval($_POST['cachetime']);
        $down_setting['newtime'] = intval($_POST['newtime']);
        if(is_file(functions::check(trim($_POST['zipfile']))))
        {
            $down_setting['zipfile'] = functions::check(trim($_POST['zipfile']));
        }
        else
        {
            echo'<div class="rmenu">'.$lng_dl['file_for_archive_not_found'].'</div>';
        }

        if($arr = fopen('set.dat', "w"))
        {
        fwrite($arr, serialize($down_setting));
        fclose($arr);
            echo'<div class="gmenu">'.$lng_dl['saved'].'</div>';
        }
        else
        {
            echo'<div class="rmenu">'.$lng_dl['setting_not_saved'].'</div>';
        }

        echo'<div class="menu"><a href="admin.php?act=setting">'.$lng['settings'].'</a></div><div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';

    }
    else
    {
        $down_setting = file_get_contents('set.dat');
        $down_setting = unserialize($down_setting);
        echo '<form action="admin.php?act=setting" method="post">

        <div class="menu">'.$lng_dl['guest_access'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_setting['guest'], 'gues');
        echo ''.$lng_dl['set_off'].'.</div><div class="gmenu">

        '.$lng_dl['comments'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_setting['komm'], 'komclose');
        echo''.$lng_dl['set_off'].'.</div><div class="menu">

        '.$lng_dl['comments_notification'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_setting['priv'], 'privinf');
        echo''.$lng_dl['set_off'].'.<br/>

        '.$lng_dl['admin_id'].':<br/>';
        echo'<input type="number" name="priv_user" value="'.$down_setting['priv_user'].'"/>
        </div><div class="gmenu">';

        if (!extension_loaded('ffmpeg'))
        echo'<b><span class="red">'.$lng_dl['must_be_off'].'</span></b><br/>';
        echo''.$lng_dl['video_info'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_setting['infvideo'], 'infvideo');
        echo ''.$lng_dl['set_off'].'.</div><div class="menu">';

        echo''.$lng_dl['zip_view_setting'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_setting['zipview'], 'zipview');
        echo $lng_dl['set_off'].".</div><div class='gmenu'>";

        echo''.$lng_dl['mp3_info'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_setting['mp3info'], 'mp3info');
        echo ''.$lng_dl['set_off'].'.</div><div class="menu">';

        echo''.$lng_dl['small_themes_list'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_setting['tmini'], 'tmini');
        echo ''.$lng_dl['set_off'].'.<br/><small>'.$lng_dl['small_list_msg'].'</small></div><div class="gmenu">';

        if (!extension_loaded('ffmpeg'))
        echo'<b><span class="red">'.$lng_dl['must_be_off'].'</span></b><br/>';
        echo''.$lng_dl['small_videos_list'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_setting['vmini'], 'vmini');
        echo ''.$lng_dl['set_off'].'.<br/><small>'.$lng_dl['small_list_msg'].'</small></div><div class="menu">';

        echo''.$lng_dl['bb_codes'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_setting['bb'], 'bb');
        echo ''.$lng_dl['set_off'].'.</div>';

        echo '<div class="gmenu">
        '.$lng_dl['max_file_size_for_user'].':<br/>
        <input type="number" name="filesize" value="'.$down_setting['filesize'].'"/> kb.</div>
        <div class="menu">
        '.$lng_dl['count_cache_time'].':<br/>
        <input type="number" name="cachetime" value="'.$down_setting['cachetime'].'"/></div>
        <div class="gmenu">
        '.$lng_dl['file_new_time'].':<br/>
        <input type="number" name="newtime" value="'.$down_setting['newtime'].'"/></div>
        <div class="menu">
        '.$lng_dl['file_for_archive'].':<br/>
        <input type="text" name="zipfile" value="'.$down_setting['zipfile'].'"/>
        <div class="sub">'.$lng_dl['file_for_archive_msg'].'</div></div>
        <div class="gmenu">
        <input type="submit" name="submit" value="'.$lng['save'].'"/></div>
        </form>';
        echo'<div class="gmenu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
    }
}
else
{
    echo $lng_dl['access_denied'];
}