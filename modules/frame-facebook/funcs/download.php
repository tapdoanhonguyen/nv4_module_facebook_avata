<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Tập Đoàn Họ Nguyễn <adminwmt@gmail.com>
 * @Copyright (C) 2023 Tập Đoàn Họ Nguyễn. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 24 Jun 2023 13:16:47 GMT
 */

if (!defined('NV_IS_MOD_FRAME_FACEBOOK'))
    die('Stop!!!');

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];

$array_data = array();
$array_data['image'] = $nv_Request->get_string('i', 'post, get');
//------------------
// Viết code vào đây
//------------------

$contents = nv_theme_frame_facebook_download($array_data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
