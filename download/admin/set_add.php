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
    echo'<div class="phdr">'.$lng_dl['fields_setting'].'</div>';
    if (isset($_POST['submit']))
    {
        $down_add = array();
        $down_add['images'] = functions::check($_POST['images']);
        $down_add['videos'] = functions::check($_POST['videos']);
        $down_add['music'] = functions::check($_POST['music']);
        $down_add['applications'] = functions::check($_POST['applications']);
        $down_add['scripts'] = functions::check($_POST['scripts']);
        $down_add['others'] = functions::check($_POST['others']);

        if($arr = fopen('set_add.dat', "w"))
        {
            fwrite($arr, serialize($down_add));
            fclose($arr);
                echo'<div class="gmenu">'.$lng_dl['saved'].'</div>';
        }
        else
        {
            echo'<div class="rmenu">'.$lng_dl['setting_not_saved'].'</div>';
        }

        echo'<div class="menu"><a href="admin.php?act=set_add">'.$lng_dl['fields_setting'].'</a></div><div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';

    }
    else
    {
        $down_add = file_get_contents('set_add.dat');
        $down_add = unserialize($down_add);
        echo '<form action="admin.php?act=set_add" method="post">
        <div class="rmenu">'.$lng_dl['fields_setting_warn'].'</div>
        <div class="rmenu">'.$lng_dl['fields_setting_list'].':<br/>
        <b>urlscreen</b> - '.$lng_dl['screen_url'].'.<br/>
        <b>ftpname</b> - '.$lng_dl['name_in_file_system'].'.<br/>
        <b>namelink</b> - '.$lng_dl['link_name'].'<br/>
        <b>desc</b> - '.$lng_dl['description'].'<br/>
        <b>lang</b> - '.$lng_dl['interface_language'].'<br/>
        <b>autor</b> - '.$lng_dl['author'].'<br/>
        <b>vendor</b> - '.$lng_dl['vendor'].'<br/>
        <b>compatibility</b> - '.$lng_dl['compatibility'].'<br/>
        <b>distributed</b> - '.$lng_dl['propagation_conditions'].'<br/>
        <b>url</b> - '.$lng_dl['site_url'].'<br/>
        <b>ver</b> - '.$lng_dl['version'].'<br/>
        <b>year</b> - '.$lng_dl['released'].'<br/>
        </div>
        <div class="menu">
        '.$lng_dl['fields_for_image'].':<br/>
        <input type="text" name="images" value="'.$down_add['images'].'"/>
        </div>

        <div class="menu">
        '.$lng_dl['fields_for_videos'].':<br/>
        <input type="text" name="videos" value="'.$down_add['videos'].'"/>
        </div>

        <div class="menu">
        '.$lng_dl['fields_for_music'].':<br/>
        <input type="text" name="music" value="'.$down_add['music'].'"/>
        </div>

        <div class="menu">
        '.$lng_dl['fields_for_soft'].':<br/>
        <input type="text" name="applications" value="'.$down_add['applications'].'"/>
        </div>

        <div class="menu">
        '.$lng_dl['fields_for_scripts'].':<br/>
        <input type="text" name="scripts" value="'.$down_add['scripts'].'"/>
        </div>

        <div class="menu">
        '.$lng_dl['fields_for_other'].':<br/>
        <input type="text" name="others" value="'.$down_add['others'].'"/>
        </div>

        <div class="menu">
        <input type="submit" name="submit" value="'.$lng['save'].'"/></div>
        </form>';
        echo'<div class="gmenu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
    }
}else
{
    echo $lng_dl['access_denied'];
}
