<?php

/**
 * @Project NUKEVIET 4.x
 * @Author T&#1073;&#1108;­p &#1044;&#1106;o&#1043; n H&#1073;»&#1036; Nguy&#1073;»…n <adminwmt@gmail.com>
 * @Copyright (C) 2023 T&#1073;&#1108;­p &#1044;&#1106;o&#1043; n H&#1073;»&#1036; Nguy&#1073;»…n. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 24 Jun 2023 13:16:47 GMT
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

$module_version = array(
    'name' => 'Frame-facebook',
    'modfuncs' => 'main,detail,search,upload,download,myframe,file',
    'change_alias' => 'main,detail,search,upload,download,myframe',
    'submenu' => 'main,detail,search,upload,download,myframe',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.3.03',
    'date' => 'Sat, 24 Jun 2023 13:16:47 GMT',
    'author' => 'T&#1073;&#1108;­p &#1044;&#1106;o&#1043; n H&#1073;»&#1036; Nguy&#1073;»…n (adminwmt@gmail.com)',
    'uploads_dir' => array($module_name,$module_name.'/frame',$module_name.'/users'),
    'files_dir' => array($module_name,$module_name.'/frame',$module_name.'/users'),
    'note' => ''
);
