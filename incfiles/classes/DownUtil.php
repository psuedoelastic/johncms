<?php defined('_IN_JOHNCMS') or die('Restricted access');
/**
 * @package     JohnCMS
 * @link        http://johncms.com
 * @copyright   Copyright (C) 2008-2011 JohnCMS Community
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      http://johncms.com/about
 */

/**
 * Class DownUtil
 *
 * @package JohnCMS
 * @author  Maxim (Simba) Masalov <max@symbos.su>
 */
class DownUtil
{

    /**
     * This method translates a string in the correct format
     *
     * @param $string
     * @param int $max_len - Maximum string length
     * @param bool $change_case - Change case. L - to lower, U - to upper, false - not change
     * @param bool $delete_repeat_replace - Remove repeating characters replacement
     * @return mixed|string
     */
    public static function translit($string, $max_len = 100, $change_case = false, $delete_repeat_replace = true)
    {
        $convert = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch', 'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        );
        $string = strtr($string, $convert);

        if($change_case == 'L') {
            $string = strtolower($string);
        } elseif($change_case == 'U') {
            $string = strtoupper($string);
        }

        $string = preg_replace('~[^-a-zA-Z0-9_]+~u', '-', $string);

        $string = trim($string, "-");

        if($delete_repeat_replace == true) {
            $string = preg_replace('/-{2,}/', '-', $string);
        }

        $string = substr($string, 0, $max_len);

        return $string;
    }


    /**
     * This method cleans cache
     *
     * @param $cache_type
     * @return int - Deleted files count
     */
    public static function clearCache($cache_type)
    {
        $cache_dirs = array(
            'count' => 'cache',
            'screen' => 'graftemp'
        );
        $counter = 0;
        if(array_key_exists($cache_type, $cache_dirs))
        {
            $directory = ROOTPATH.'download/'.$cache_dirs[$cache_type].'/';
            $dir_files = scandir($directory);
            foreach($dir_files as $file)
            {
                if(is_file($directory.$file) AND $file != '.htaccess')
                {
                    unlink($directory.$file);
                    $counter++;
                }
            }
        }

        return $counter;
    }


}