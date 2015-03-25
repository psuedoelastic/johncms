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
 * Class DownSection
 *
 * @package JohnCMS
 * @author  Maxim (Simba) Masalov <max@symbos.su>
 */
class DownSection
{
    public $last_error;

    public function __construct()
    {
        $this->last_error = array();
    }


    /**
     * This method creates section
     *
     * @param $arFields
     * @return bool|int
     */
    public function add($arFields)
    {
        global $lng_dl;
        $files_dir = ROOTPATH.'download/files/';

        if(empty($arFields['NAME']))
        {
            $this->last_error[] = $lng_dl['error_dir_name_is_empty'];
        }

        if(empty($arFields['FS_NAME']))
        {
            $this->last_error[] = $lng_dl['error_fs_dir_name_is_empty'];
        }

        if(!empty($arFields['USER_UPLOAD']))
        {
            if(empty($arFields['FILES_TYPES']))
            {
                $this->last_error[] = $lng_dl['error_file_types_is_empty'];
            }
        }

        // Correction of the directory names in the file system
        $arFields['FS_NAME'] = DownUtil::translit($arFields['FS_NAME']).'/';

        $parent_section = !empty($arFields['PARENT_SECTION_ID']) ? intval($arFields['PARENT_SECTION_ID']) : 0;
        if($parent_section > 0)
        {
            // Get parent section way
            $res = mysql_query("SELECT * FROM `downpath` WHERE id = '".$parent_section."';");
            if($arDir = mysql_fetch_array($res))
            {
                $arFields['FS_NAME'] = $arDir['way'].$arFields['FS_NAME'].'/';
            }
        }

        if(is_dir($files_dir.$arFields['FS_NAME']))
        {
            $this->last_error[] = $lng_dl['error_file_types_is_empty'];
        }

        if(empty($this->last_error))
        {
            // Create directory and insert to data base
            if (mkdir($files_dir.$arFields['FS_NAME'], 0777))
            {
                @chmod($files_dir.$arFields['FS_NAME'], 0777);
                mysql_query("INSERT INTO `downpath` SET
                `refid` = '".$parent_section."',
                `way` = '".mysql_real_escape_string($arFields['FS_NAME'])."',
                `name` = '".mysql_real_escape_string($arFields['NAME'])."',
                `desc` = '".mysql_real_escape_string($arFields['DESCRIPTION'])."',
                `position` = '0',
                `dost` = '".(empty($arFields['USER_UPLOAD']) ? 0 : 1)."',
                `types` = '".mysql_real_escape_string($arFields['FILES_TYPES'])."';");

                return mysql_insert_id();
            }
        }

        return false;
    }



}