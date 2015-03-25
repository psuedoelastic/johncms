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

$cat = intval($_GET['cat']);
?>
    <div class="phdr">
        <?= $lng_dl['create_section'] ?>
    </div>

    <?php if(isset($_POST['submit'])): ?>
        <?php
        $section = new DownSection();
        $save_dir = $section->add(
            array(
                'NAME' => $_POST['name'],
                'FS_NAME' => $_POST['fs_name'],
                'DESCRIPTION' => $_POST['description'],
                'FILES_TYPES' => $_POST['file_types'],
                'USER_UPLOAD' => $_POST['user_add'],
                'PARENT_SECTION_ID' => $cat
            )
        );
        ?>

        <?php if(!$save_dir): ?>
            <div class="rmenu">
                <p>
                    <b><?= $lng['error'] ?></b><br>
                    <?php foreach($section->last_error as $error): ?>
                        <?= $error ?><br>
                    <?php endforeach; ?>
                    <a href="/download/admin.php?act=createdir&amp;cat=<?= $cat ?>"><?= $lng_dl['repeat'] ?></a>
                </p>
            </div>
        <?php else: ?>
            <div class="gmenu">
                <?= $lng_dl['section_created_success'] ?>
            </div>
        <?php endif; ?>

    <?php else: ?>

        <form action="/download/admin.php?act=createdir&amp;cat=<?= $cat ?>" method="post">
            <div class="gmenu">
                <div class="form-group">
                    <label for="name" class="label"><?= $lng_dl['name'] ?></label><br>
                    <input type="text" name="name" id="name" value=""/>
                </div>
                <div class="form-group">
                    <label for="fs_name" class="label"><?= $lng_dl['name_in_file_system'] ?></label><br>
                    <input type="text" name="fs_name" id="fs_name" value=""/>
                </div>
                <div class="form-group">
                    <label for="description" class="label"><?= $lng_dl['description'] ?></label><br>
                    <input type="text" name="description" id="description" value=""/>
                </div>
                <div class="form-group">
                    <label for="file_types" class="label"><?= $lng_dl['file_types'] ?></label><br>
                    <input type="text" name="file_types" id="file_types" value=""/><br>
                    <small><?= $lng_dl['file_types_notice'] ?></small>
                </div>
                <div class="form-group">
                    <input type="checkbox" id="user_add" name="user_add" value="1"/>
                    <label for="user_add" class="label"><?= $lng_dl['allow_user_add_files'] ?></label>
                </div>
                <div class="form-group">
                    <input type='submit' name='submit' value='<?= $lng['save'] ?>'/>
                </div>
            </div>
        </form>
    <?php endif; ?>
    <p>
        <a href="/download/admin.php?act=folder"><?= $lng_dl['structure_manage'] ?></a><br>
        <a href="/download/admin.php"><?= $lng_dl['admin_panel'] ?></a>
    </p>
