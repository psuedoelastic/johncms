<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

/////////////////////////////////////
////// Настройки полей ввода ////////
/////////////////////////////////////
defined('_IN_JOHNCMS') or die('Error: restricted access');
if ($rights >= 9)
{
    echo'<div class="phdr">Настройки полей ввода</div>';
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
                echo'<div class="gmenu">Настройки успешно сохранены!</div>';
        }
        else
        {
            echo'<div class="rmenu">Не удалось открыть файл настроек! Проверьте права доступа!</div>';
        }

        echo'<div class="menu"><a href="admin.php?act=set_add">Настройки полей ввода</a></div><div class="menu"><a href="admin.php">Админка</a></div>';

    }
    else
    {
        $down_add = file_get_contents('set_add.dat');
        $down_add = unserialize($down_add);
        echo '<form action="admin.php?act=set_add" method="post">
        <div class="rmenu">Имена полей вводятся через запятую! Обязательные поля будут в любом случае.</div>
        <div class="rmenu">Список имён полей ввода:<br/>
        <b>urlscreen</b> - URL скриншота для импорта.<br/>
        <b>ftpname</b> - Имя для сохранения на FTP.<br/>
        <b>namelink</b> - Имя для ссылки "скачать" в описании файла.<br/>
        <b>desc</b> - Описание файла.<br/>
        <b>lang</b> - Язык интерфейса.<br/>
        <b>autor</b> - Автор.<br/>
        <b>vendor</b> - Производитель.<br/>
        <b>compatibility</b> - Совместимость.<br/>
        <b>distributed</b> - Распространяется (бесплатно, crack и т.п.).<br/>
        <b>url</b> - Адрес сайта.<br/>
        <b>ver</b> - Версия.<br/>
        <b>year</b> - Год выхода.<br/>
        </div>
        <div class="menu">
        Поля для картинок:<br/>
        <input type="text" name="images" value="'.$down_add['images'].'"/>
        </div>

        <div class="menu">
        Поля для видео:<br/>
        <input type="text" name="videos" value="'.$down_add['videos'].'"/>
        </div>

        <div class="menu">
        Поля для музыки:<br/>
        <input type="text" name="music" value="'.$down_add['music'].'"/>
        </div>

        <div class="menu">
        Поля для приложений/игр:<br/>
        <input type="text" name="applications" value="'.$down_add['applications'].'"/>
        </div>

        <div class="menu">
        Поля для скриптов:<br/>
        <input type="text" name="scripts" value="'.$down_add['scripts'].'"/>
        </div>

        <div class="menu">
        Поля для прочего:<br/>
        <input type="text" name="others" value="'.$down_add['others'].'"/>
        </div>

        <div class="menu">
        <input type="submit" name="submit" value="Сохранить"/></div>
        </form>';
        echo'<div class="gmenu"><a href="admin.php">Админка</a></div>';
    }
}else
    echo'Вы не имеете необходимых прав для доступа к данному разделу!';
?>