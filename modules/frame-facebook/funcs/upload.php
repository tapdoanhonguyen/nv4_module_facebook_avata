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
if(isset($_FILES["image"])){
	
	$sourceImg = @imagecreatefromstring(@file_get_contents($_FILES["image"]["tmp_name"]));
  if ($sourceImg === false){
    echo "images/default-profile-pic.png";
    exit;
  }

  $image = makeDP($_FILES["image"]["tmp_name"], (
    isset($_POST["design"]) ? $_POST["design"] : ''
  ), $screenWidth);
	$directoryPath = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/users/' . $user_info['userid'];

	if (!is_dir($directoryPath)) {
		if (mkdir($directoryPath, 0777, true)) {
			echo "Thư mục đã được tạo thành công.";
		} else {
			echo "Không thể tạo thư mục.";
		}
	}
  $loc_name = rand_string(10) . '.png';
  $loc_path = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/users/' . $user_info['userid'] . '/' . $loc_name;
  $loc_url = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/users/' . $user_info['userid'] . '/' . $loc_name;

  file_put_contents($loc_path, $image);
  echo $loc_url;

  imagedestroy($dest);
  imagedestroy($src);
  die;
}



$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];

$array_data = array();

//------------------
// Viết code vào đây
//------------------

$contents = nv_theme_frame_facebook_upload($array_data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
