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


$file = intval($_GET['file']);
echo '<div class="phdr">'.$lng_dl['upload_screen'].'</div>';
if (isset($_POST['submit'])) {
    $fname = $_FILES['fail']['name'];
    $impcat = mysql_query("select * from `downfiles` where id = '" . $file . "';");
    $arr = mysql_fetch_array($impcat);
    //// ^получаем всё что нужно^ //////
    $scrf = pathinfo($fname, PATHINFO_EXTENSION);

    if ($scrf !== "jpg" && $scrf !== "png" && $scrf !== "gif") {
        echo '<div class=rmenu">'.str_replace('#SCREEN_TYPE#', $scrf, $lng_dl['screen_type_not_allowed']).'<br/>
<a href="admin.php?act=upscreen&amp;file='.$file.'">'.$lng_dl['repeat'].'</a><br/>';
        require_once ('../incfiles/end.php');
        exit;
    }

    if (is_file($screenroot . '/' . basename($arr['way']) . '.' . $scrf))
        $save = $screenroot . '/' . time() . basename($arr['way']) . '.' . $scrf;
    else
        $save = $screenroot . '/' . basename($arr['way']) . '.' . $scrf;

    if ((move_uploaded_file($_FILES["fail"]["tmp_name"], $save)) == true) {
        @chmod($save, 0777);
        echo '<div class="gmenu">'.$lng_dl['file_loaded'].'</div>';
        mysql_query("INSERT INTO `downscreen` SET `fileid` = '" . $file . "', `way` = '" .
            basename($save) . "';");
    } else {
        echo '<div class="rmenu">'.$lng_dl['load_error'].'</div>';
    }

    echo '<div class="menu"><a href="admin.php?act=file&amp;view=' . $file .
        '">'.$lng_dl['back_to_file'].'</a></div>';

} else {

    echo '<form action="admin.php?act=upscreen&amp;file=' . $file .
        '" method="post" enctype="multipart/form-data">';
    echo '<div class="menu">'.$lng_dl['select_file'].':<br/><input type="file" name="fail"/></div>
   <div class="menu">
   <input type="submit" name="submit" value="'.$lng['save'].'"/></div></form>';
    echo '<div class="menu"><a href="admin.php?act=file&amp;view=' . $file .
        '">'.$lng_dl['back_to_file'].'</a></div>';
}

echo '<div class="menu"><a href="admin.php">'.$lng_dl['admin_panel'].'</a></div>';
