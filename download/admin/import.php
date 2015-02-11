<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

///////////////////////////////////////
///////////// Импорт //////////////////
///////////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');
echo '<div class="phdr">Импорт файлов</div>';
$cat = intval($_GET['cat']);
$more_file = intval($_GET['file']);
$addget = $more_file ? '&amp;file=' . $more_file : '';
// Выбор типа импортируемого файла //
if (!isset($_POST['type_import'])) {
    echo '<form action="admin.php?act=import&amp;cat=' . $cat . $addget .
        '" name="add" method="post">';
    if (!$more_file) {
        echo '<div class="menu">Что будем импортировать?<br/>';
        echo '<select name="type_import" class="textbox">';
        echo '<option value="applications">Игры/приложения</option>';
        echo '<option value="images">Картинки</option>';
        echo '<option value="videos">Видео</option>';
        echo '<option value="music">Музыку</option>';
        echo '<option value="scripts">Скрипты и т.п.</option>';
        echo '<option value="others">Прочее</option>';
        echo '</select></div>';
    } else {
        echo '<input type="hidden" name="type_import" value="more_file"/>'; // что импортируем
    }
    echo '<div class="menu">Какое количество файлов импортировать?<br/><input type="number" name="col_files" value="1"/></div>
    <div class="menu"><input type="submit" name="type_selected" value="Далее"/></div></form>';
    echo '<div class="rmenu">Учтите, что при импорте большого количества файлов, импотр может длиться довольно долго.</div>';
    echo '<div class="phdr"><a href="admin.php">Админка</a></div>';
    require_once ('../incfiles/end.php');
    exit;
}

if (!$more_file) {
    // Массив настроек полей ввода //
    $down_add = file_get_contents('set_add.dat');
    $down_add = unserialize($down_add);
    // Массив с именами полей //
    $arr_input = explode(',', $down_add[$_POST['type_import']]);
} else {
    $arr_input = array('desc');
}


if (!$_POST['col_files'])
    $_POST['col_files'] = 1;

// Поля ввода //
if (!isset($_POST['submit'])) {

    echo '<form action="admin.php?act=import&amp;cat=' . $cat . $addget .
        '" name="add" method="post">
<div class="rmenu">Обязательные поля отмечены звёздочкой (*)</div>';
    echo '<input type="hidden" name="type_import" value="' . $_POST['type_import'] .
        '"/>'; // что импортируем
    echo '<input type="hidden" name="col_files" value="' . intval($_POST['col_files']) .
        '"/>'; // сколько импортируем
    // Цикл с повторяющимеся полями //
    for ($i = 1; $i <= $_POST['col_files']; $i++) {
        echo '<div class="func">Файл: ' . $i . '</div>
<div class="menu"><b>*</b> Введите URL файла:<br/><input type="text" name="url_file[' .
            $i . ']" value="http://"/></div>
<div class="menu"><b>*</b> Имя для отображения:<br/><input type="text" name="name[' .
            $i . ']" value=""/></div>';

        // Скриншот //
        if (in_array('urlscreen', $arr_input))
            echo '<div class="menu">URL скриншота:<br/><input type="text" name="urlscreen[' .
                $i . ']" value=""/></div>';

        // Имя для фтп //
        if (in_array('ftpname', $arr_input))
            echo '<div class="menu">Имя для фтп с расширением:<br/><input type="text" name="ftp[' .
                $i . ']" value=""/></div>';

        // Имя ссылки скачать //
        if (in_array('namelink', $arr_input))
            echo '<div class="menu">Имя для основной ссылки (в просмотре файла):<br/>
<input type="text" name="namelink[' . $i . ']" value=""/></div>';

        // Автор //
        if (in_array('autor', $arr_input))
            echo '<div class="menu">Автор:<br/>
<input type="text" name="autor[' . $i . ']" value=""/></div>';

        // Производитель //
        if (in_array('vendor', $arr_input))
            echo '<div class="menu">Производитель:<br/>
<input type="text" name="vendor[' . $i . ']" value=""/></div>';

        // Совместимость //
        if (in_array('lang', $arr_input))
            echo '<div class="menu">Язык интерфейса:<br/>
<input type="text" name="lang[' . $i . ']" value=""/></div>';

        // Совместимость //
        if (in_array('compatibility', $arr_input))
            echo '<div class="menu">Совместимость:<br/>
<input type="text" name="compatibility[' . $i . ']" value=""/></div>';

        // Распространяется //
        if (in_array('distributed', $arr_input))
            echo '<div class="menu">Распространяется:<br/>
<input type="text" name="distributed[' . $i . ']" value=""/></div>';

        // url //
        if (in_array('url', $arr_input))
            echo '<div class="menu">Адрес сайта:<br/>
<input type="text" name="url[' . $i . ']" value=""/></div>';

        // Версия //
        if (in_array('ver', $arr_input))
            echo '<div class="menu">Версия:<br/>
<input type="text" name="ver[' . $i . ']" value=""/></div>';

        // Год выхода //
        if (in_array('year', $arr_input))
            echo '<div class="menu">Год выхода:<br/>
<input type="text" name="year[' . $i . ']" value=""/></div>';

        // Описание //
        if (in_array('desc', $arr_input)) {
echo '<div class="menu">Описание: <br/>';
if(!$is_mobile)
    echo bbcode::auto_bb('add', 'desc[' . $i . ']');
echo '<textarea cols="' . $set_user['field_w'] . '" rows="' . $set_user['field_h'] . '" name="desc[' . $i . ']"></textarea></div>';
        }
        if (!$more_file) {
            echo '<div class="menu"><b>*</b> Выберите каталог:<br/>
<select name="namekat[' . $i . ']" class="textbox">';

            $impcat = mysql_query("select * from `downpath` where refid = '" . $cat . "';");
            while ($arr = mysql_fetch_array($impcat)) {
                $countp = mysql_result(mysql_query("SELECT COUNT(*) FROM `downpath` WHERE `way` LIKE '" .
                    $arr['way'] . "%';"), 0) - 1;
                if ($countp <= 0)
                    echo '<option value="' . $arr['id'] . '">' . $arr['name'] . '</option>';
            }
            echo '</select><br/><small>Отображаются только папки без вложенных папок!</small></div>';
        }
    }

    echo '<div class="menu"><input type="submit" name="submit" value="Загрузить"/></div></form>';
} else {

    // Проверка и импорт //
    for ($i = 1; $i <= $_POST['col_files']; $i++) {
        $error = array();
        $url = isset($_POST['url_file'][$i]) ? str_replace('./', '_', trim($_POST['url_file'][$i])) : false;
        $name = isset($_POST['name'][$i]) ? functions::check(trim($_POST['name'][$i])) : false;
        $linkname = functions::check(trim($_POST['namelink'][$i]));
        $urlscreen = isset($_POST['urlscreen'][$i]) ? str_replace('./', '_', trim($_POST['urlscreen'][$i])) : false;
        $ftp = isset($_POST['ftp'][$i]) ? functions::check(trim($_POST['ftp'][$i])) : false;

        if ($url) {
            if (mb_substr($url, 0, 7) !== 'http://')
                $error[] = 'Неправильно введена ссылка: ' . $url;
            else
                $url = str_replace('http://', '', $url);
        }

        if (empty($url)) {
            $error[] = 'Не введена ссылка!';
        }

        if (!$name) {
            $error[] = 'Не заполнено имя для отображения!';
        }

        if ($ftp) {
            if (preg_match("/[^a-z0-9.()_-]/i", $ftp)) {
                $error[] = 'В названии файла <b>' . $ftp .
                    '</b> присутствуют недопустимые символы<br/>Разрешены только латинские символы, цифры и некоторые знаки ( .()_- )';
            }
        }

        if ($urlscreen) {
            if (mb_substr($urlscreen, 0, 7) !== 'http://')
                $urlscreen = false;
            else
                $urlscreen = str_replace('http://', '', $urlscreen);
        }

        // Получаем путь до папки //
        $catid = intval($_POST['namekat'][$i]) ? intval($_POST['namekat'][$i]) : $cat;
        $impcat = mysql_query("select * from `downpath` where id = '" . $catid . "';");
        $arr = mysql_fetch_array($impcat);
        $loaddir = $loadroot . '/' . $arr['way'];
        if (!$ftp) {
            ////// Если не задано имя для фтп подгоняем под допустимое ///////
            $ftp = name_replace($name);
            /////// Определяем тип файла ///////
            $typ = pathinfo($url, PATHINFO_EXTENSION);
            //////// Конечное имя файла для сохранения с расширением /////////
            $ftp = $ftp . '.' . $typ;
            if (is_file($loaddir . $ftp)) {
                $ftp = time() . $i . $ftp;
            }
        }

        if ((preg_match("/php/i", pathinfo($ftp, PATHINFO_EXTENSION))) or ($ftp == ".htaccess")) {
            $error[] = 'Файл запрещёного типа!';
        }


        if (!$error) {

            // Вставляем в описание дополнительные поля //
            $desc = '';

            // Если заполнено поле с описанием
            if ($_POST['desc'][$i])
                $desc = $desc . '[b]Описание:[/b] ' . $_POST['desc'][$i] . "\r\n";

            // Если заполнено поле с автором
            if ($_POST['autor'][$i])
                $desc = $desc . '[b]Автор:[/b] ' . $_POST['autor'][$i] . "\r\n";

            // Если заполнено поле с производителем
            if ($_POST['vendor'][$i])
                $desc = $desc . '[b]Разработчик:[/b] ' . $_POST['vendor'][$i] . "\r\n";

            // Если заполнено поле с языком интерфейса
            if ($_POST['lang'][$i])
                $desc = $desc . '[b]Язык интерфейса:[/b] ' . $_POST['lang'][$i] . "\r\n";

            // Если заполнено поле с версией
            if ($_POST['ver'][$i])
                $desc = $desc . '[b]Версия:[/b] ' . $_POST['ver'][$i] . "\r\n";

            // Если заполнено поле с условием распространения
            if ($_POST['distributed'][$i])
                $desc = $desc . '[b]Распространяется:[/b] ' . $_POST['distributed'][$i] . "\r\n";

            // Если заполнено поле с совместимостью
            if ($_POST['compatibility'][$i])
                $desc = $desc . '[b]Совместимость:[/b] ' . $_POST['compatibility'][$i] . "\r\n";

            // Если заполнено поле с годом выхода
            if ($_POST['year'][$i])
                $desc = $desc . '[b]Год выхода:[/b] ' . $_POST['year'][$i] . "\r\n";

            // Если заполнено поле с адресом сайта
            if ($_POST['url'][$i])
                $desc = $desc . '[b]Адрес сайта:[/b] ' . $_POST['url'][$i] . "\r\n";




            // Качаем файл себе на сервер //
            if (copy('http://' . $url, $loaddir . $ftp)) {
                @chmod($loaddir . $ftp, 0777); // ставим права доступа
                if (!$more_file)
                    $name = $name . '||||' . $linkname;

                mysql_query("INSERT INTO `downfiles` SET
        " . ($more_file > 0 ? " `type` = '1', " : '') . "
        `pathid` = '" . ($more_file > 0 ? $more_file : $catid) . "',
        `way` = '" . $arr['way'] . $ftp . "',
        `name` = '" . $name . "',
        `desc` = '" . mysql_real_escape_string($desc) . "',
        `time` = '" . time() . "',
        `login` = '" . $login . "',
        `gol` = '',
        `user_id` = '" . $user_id . "';");
                $rid = mysql_insert_id();
                if ($urlscreen) {
                    $scr_type = pathinfo($urlscreen, PATHINFO_EXTENSION);
                    if ($scr_type == 'jpg' || $scr_type == 'png' || $scr_type == 'gif' || $scr_type ==
                        'jpeg') {
                        $save_screen = $filesroot . '/screens/' . $rid . '.' . $scr_type;
                        if (copy('http://' . $urlscreen, $save_screen)) {
                            @chmod($save_screen, 0777); // ставим права доступа
                            mysql_query("INSERT INTO `downscreen` SET
                `fileid` = '" . $rid . "',
                `way` = '" . $rid . "." . $scr_type . "';");
                            echo '<div class="gmenu">Скриншот успешно загружен!</div>';
                        } else {
                            echo '<div class="rmenu">Не удалось получить скриншот с удалённого сервера!</div>';
                        }
                    } else {
                        echo '<div class="rmenu">Скриншот недопустимого типа!</div>';
                    }
                }
                echo '<div class="gmenu">Файл успешно загружен!</div>';
                echo '<div class="menu"><a href="admin.php?act=file&amp;view=' . ($more_file > 0 ?
                    $more_file : $rid) . '">К файлу</a> | <a href="admin.php?act=folder&amp;cat=' .
                    $catid . '">В папку</a></div>';
            }
            else
            {
                echo '<div class="rmenu">Не удалось получить файл с удалённого сервера!</div>';
            }

        } else {
            echo '<div class="rmenu">Обнаружены ошибки!</div>';
            foreach ($error as $val) {
                echo '<div class="rmenu">' . $val . '</div>';
            }
        }
    }
    
    auto_clean_cache(); // Чистим кэш счётчиков
    
    echo '<div class="gmenu">Импорт файлов завершён!</div>';

}


echo '<div class="phdr"><a href="admin.php">Админка</a></div>';

?>