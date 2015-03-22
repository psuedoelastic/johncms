<?php

/**
 * @package     JohnCMS
 * @link        http://johncms.com
 * @copyright   Copyright (C) 2008-2011 JohnCMS Community
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      http://johncms.com/about
 */

/**
 * Class DownFile
 *
 * @package JohnCMS
 * @author  Maxim (Simba) Masalov <max@symbos.su>
 */
class DownFile
{

    /**
     * This method returns file data array by ID
     *
     * @param $id - File id in table downfiles
     * @return array|bool - Array or false if not exists
     */
    public static function getById($id)
    {
        if(is_int($id)) {

            $return_array = array();
            $res = mysql_query("SELECT * FROM `downfiles` WHERE `id` = '".$id."'");

            if($file_array = mysql_fetch_array($res)) {

                $name = explode('||||', $file_array['name']);
                $file_name = DownUtil::translit($name['0']);
                $return_array['FILE_PAGE_URL'] = '/download/'.$file_name.'_'.$id.'.html';
                return array_merge($return_array, $file_array);
            }
        }

        return false;
    }

}