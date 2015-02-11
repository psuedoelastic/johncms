<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

///////////////////////////////////////////
///////////// Добавление скринов //////////
///////////////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');
$file = intval($_GET['file']);
echo '<div class="phdr">Выгрузка скриншота</div>';
if (isset($_POST['submit'])) {
    $fname = $_FILES['fail']['name'];
    $impcat = mysql_query("select * from `downfiles` where id = '" . $file . "';");
    $arr = mysql_fetch_array($impcat);
    //// ^получаем всё что нужно^ //////
    $scrf = pathinfo($fname, PATHINFO_EXTENSION);

    if ($scrf !== "jpg" && $scrf !== "png" && $scrf !== "gif") {
        echo '<div class=rmenu">К загрузке разрешены только jpg,png и gif скриншоты.<br/><a href="admin.php?act=upscreen&amp;file=' .
            $file . '">Повторить</a><br/>';
        require_once ('../incfiles/end.php');
        exit;
    }

    if (is_file($screenroot . '/' . basename($arr['way']) . '.' . $scrf))
        $save = $screenroot . '/' . time() . basename($arr['way']) . '.' . $scrf;
    else
        $save = $screenroot . '/' . basename($arr['way']) . '.' . $scrf;

    if ((move_uploaded_file($_FILES["fail"]["tmp_name"], $save)) == true) {
        @chmod($save, 0777);
        echo '<div class="gmenu">Файл загружен!</div>';
        mysql_query("INSERT INTO `downscreen` SET `fileid` = '" . $file . "', `way` = '" .
            basename($save) . "';");
    } else {
        echo '<div class="rmenu">Ошибка при загрузке файла</div>';
    }

    echo '<div class="menu"><a href="admin.php?act=file&amp;view=' . $file .
        '">К файлу</a></div>';

} else {

    echo '<form action="admin.php?act=upscreen&amp;file=' . $file .
        '" method="post" enctype="multipart/form-data">';
    echo '<div class="menu">Выберите файл:<br/><input type="file" name="fail"/></div>
   <div class="menu">
   <input type="submit" name="submit" value="Загрузить"/></div></form>';
    echo '<div class="menu"><a href="admin.php?act=file&amp;view=' . $file .
        '">К файлу</a></div>';
}

echo '<div class="menu"><a href="admin.php">Админка</a></div>';

?>