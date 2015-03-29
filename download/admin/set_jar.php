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
    echo'<div class="phdr">'.$lng_dl['setting_for_java'].'</div>';
    if (isset($_POST['submit']))
    {
        $down_jar = array();
        $down_jar = file_get_contents('set.dat');
        $down_jar = unserialize($down_jar);
        $down_jar['jadgen'] = intval($_POST['jad']);
        $down_jar['jar_version'] = intval($_POST['jar_version']);
        $down_jar['jar_name'] = intval($_POST['jar_name']);
        $down_jar['jar_vendor'] = intval($_POST['jar_vendor']);
        $down_jar['jar_profile'] = intval($_POST['jar_profile']);
        $down_jar['jar_url'] = intval($_POST['jar_url']);

        if($arr = fopen('set.dat', "w"))
        {
            fwrite($arr, serialize($down_jar));
            fclose($arr);
                echo'<div class="gmenu">'.$lng_dl['saved'].'</div>';
        }
        else
        {
            echo'<div class="rmenu">'.$lng_dl['setting_not_saved'].'</div>';
        }

        echo'<div class="menu"><a href="admin.php?act=set_jar">'.$lng_dl['setting_for_java'].'</a></div><div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';

    }
    else
    {
        $down_jar = file_get_contents('set.dat');
        $down_jar = unserialize($down_jar);
        echo '<form action="admin.php?act=set_jar" method="post">';
        echo'<div class="menu">'.$lng_dl['auto_generate_jad'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_jar['jadgen'], 'jad');
        echo $lng_dl['set_off'].'.</div><div class="menu">';

        echo $lng_dl['version'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_jar['jar_version'], 'jar_version');
        echo $lng_dl['set_off'].'.</div><div class="menu">';

        echo $lng_dl['name'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_jar['jar_name'], 'jar_name');
        echo $lng_dl['set_off'].'.</div><div class="menu">';

        echo $lng_dl['vendor'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_jar['jar_vendor'], 'jar_vendor');
        echo $lng_dl['set_off'].'.</div><div class="menu">';

        echo $lng_dl['profile'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_jar['jar_profile'], 'jar_profile');
        echo $lng_dl['set_off'].'.</div><div class="menu">';

        echo $lng_dl['site_url'].':<br/>'.$lng_dl['set_on'].'.';
        radio_check($down_jar['jar_url'], 'jar_url');
        echo $lng_dl['set_off'].'.</div><div class="menu">';

        echo '<input type="submit" name="submit" value="'.$lng['save'].'"/></div>
        </form>';
        echo'<div class="gmenu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
    }
}else
{
    echo $lng_dl['access_denied'];
}