<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

define('_IN_JOHNCMS', 1);
$headmod = 'load';
require_once '../incfiles/core.php';
require_once 'functions.php';
$textl = 'Загруз-Центр / Просмотр архива';
require_once '../incfiles/head.php';
if ($down_setting['zipview'])
{

    include_once 'classes/pclzip.lib.php';
    $act = isset($_GET['act']) ? $_GET['act'] : '';
    $file = functions::check($_GET['file']);
    $file_id = intval($_GET['file_id']);

    $file1 = ROOTPATH . $downpat . '/' .$file;

    $zip = new PclZip($file1);
    switch ($act)
    {
    //////// Просмотр файла в архиве \\\\\\\\\\\\\
    case 'view':
        $f = check($_GET['ob']);
        $type = array('txt', 'dat', 'html', 'htm', 'wml', 'php', 'htaccess'); //// типы файлов для просмотра
        if(in_array(pathinfo($f, PATHINFO_EXTENSION), $type)){
        $ext = $zip->extract(PCLZIP_OPT_BY_NAME, $f, PCLZIP_OPT_EXTRACT_AS_STRING);
        $siz = round($ext[0]['size'] / 1024, 2);
        if ($siz > 1024) { $siz = round($siz / 1024, 2) . ' мб'; } else { $siz = $siz . ' кб'; }
        $sizc = round($ext[0]['compressed_size'] / 1024, 2);
        if ($sizc > 1024) { $sizc = round($sizc / 1024, 2) . ' мб'; } else { $sizc = $sizc . ' кб'; }
        echo'<div class="phdr">Файл: '.$f.'</div>';
        echo '<div class="menu">Размер: '.$siz.'</div>';
        echo'<div class="menu">Сжат: '.$sizc.'</div>';
        $vrp = $ext[0]['mtime'] + $sdvig * 3600;
            $vr = date("d-m-Y - H:i", $vrp);
        echo'<div class="menu">Изменён: '.$vr.'</div>';
        echo '<div class="list1">'.functions::checkout(perekodname($ext[0]['content']), 1, 1).'</div>';
        }else{
            echo'<div class="rmenu">Данный тип файла нельзя просмотреть в виде текста!</div>';
        }
        echo '<div class="phdr"><a href="zipview.php?file='.$file.'&amp;file_id='.$file_id.'">Назад в архив</a></div>';
        echo'<div class="gmenu"><a href="file_'.$file_id.'.html">К описанию</a></div>';
    break;

    //////////// Просмотр архива \\\\\\\\\\\\\
    default:
        $list = $zip->listContent();
        echo'<div class="phdr">Файл: '.basename($file).'</div>';
        echo '<div class="menu">';
        echo'<table border="1" cellspacing="1" cellpadding="2">';
        echo '<tr><td bgcolor="#FFCC99">Имя файла/папки</td><td bgcolor="#FFCC99">Тип</td><td bgcolor="#FFCC99">Размер</td><td bgcolor="#FFCC99">Сжат</td><td bgcolor="#FFCC99">Изменён</td></tr>';

        $s = count($list);

        // Постраничная навигация //
        if(isset($_GET['page']))
        $page = intval($_GET['page']);
        else
        $page = 1;
        // текущая страница
        $number = ceil($s/$kmess); // всего страниц
        $start = ($page > 1 && $page <= $number) ? (($page - 1)*$kmess + 1) : 0; // стартовое число
        $end = ($page > 0) ? $page*$kmess + 1 : $kmess; // Конечное число
        if($end > $s)
        $end = $s;
        // Конец считалок //

        for ($i = $start; $i < $end; ++$i) {
            echo '<tr><td>';
            $name = str_replace('%2F', '/', rawurlencode($list[$i]['filename']));
            $type = pathinfo($list[$i]['filename'], PATHINFO_EXTENSION);
            $typef = array('txt', 'dat', 'html', 'htm', 'wml', 'php', 'htaccess'); //// типы файлов для просмотра
            if ($list[$i]['folder']) {
            echo '<b>'.htmlspecialchars($list[$i]['filename']).'</b>';
            $type = 'Папка';
            }else{
            $vrp = $list[$i]['mtime'] + $sdvig * 3600;
            $vr = date("d-m-Y - H:i", $vrp);
            if(in_array($type, $typef)){
            echo '<a href="zipview.php?act=view&amp;file='.$file.'&amp;ob='.$list[$i]['filename'].'&amp;file_id='.$file_id.'">'.htmlspecialchars($list[$i]['filename']).'</a>';
            }else{
             echo htmlspecialchars($list[$i]['filename']);
            }
            }
            echo'</td><td>'.$type.'</td><td>'.size_convert($list[$i]['size']).'</td><td>'.size_convert($list[$i]['compressed_size']).'</td><td>'.$vr.'</td></tr>';
            }
        $prop = $zip->properties();
                if($prop['comment']){
                    if(iconv('UTF-8', 'UTF-8', $prop['comment']) != $prop['comment']){
                        $prop['comment'] = iconv('Windows-1251', 'UTF-8', $prop['comment']); }
        echo '</table><table border="1" cellspacing="1" cellpadding="2"><tr><td bgcolor="#FFCC99">Комментарий: </td><td>'.checkout($prop['comment'], 1, 1).'</td></tr>'; }
        echo'</table>';
        echo '</div>';
        echo'<div class="phdr">Файлов/папок в архиве: '.$s.'</div>';

        // Вывод страниц навигации //
        if($s > $kmess)
        {
            echo '<div class="menu">Страница '.$page.' из '.$number.'<br/>';

            // Предыдущая
            if($page > 1){
            $back_page = $page-1;
            echo '<a href="zipview.php?file='.$file.'&amp;file_id='.$file_id.'&amp;page='.$back_page.'">&lt;&lt;</a>&nbsp;';
            }

            // 3 страницы меньше текущей.
            $a = ($page-3) > 0 ? ($page-3) : 1;
            for($back = $a; $back < $page; $back++)
            echo '<a href="zipview.php?file='.$file.'&amp;file_id='.$file_id.'&amp;page='.$back.'">'.$back.'</a>&nbsp;';

            // текущая страница
            echo '<b>'.$page.'</b>&nbsp;';

            // 3 страницы больше текущей
            $a = ($page+3) < $number ? ($page+3) : $number;
            for($next = $page+1; $next <= $a; $next++)
            echo '<a href="zipview.php?file='.$file.'&amp;file_id='.$file_id.'&amp;page='.$next.'">'.$next.'</a>&nbsp;';

            // Следующая
            if($page < $number)
            {
            $next_page = $page+1;
            echo '<a href="zipview.php?file='.$file.'&amp;file_id='.$file_id.'&amp;page='.++$page.'">&gt;&gt;</a>';
            }
            echo '</div>';
        }
        // Конец вывода страниц навигации //
        echo'<div class="gmenu"><a href="file_'.$file_id.'.html">К описанию</a></div>';
    break;
    }
}
else
{
    echo'<div class="rmenu">Просмотр архивов не доступен!</div>';
}
require_once '../incfiles/end.php';