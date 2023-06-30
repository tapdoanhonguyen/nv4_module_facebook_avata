<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Tập Đoàn Họ Nguyễn <adminwmt@gmail.com>
 * @Copyright (C) 2023 Tập Đoàn Họ Nguyễn. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 24 Jun 2023 13:16:47 GMT
 */

if (!defined('NV_SYSTEM'))
    die('Stop!!!');

define('NV_IS_MOD_FRAME_FACEBOOK', true);

function makeDP($sourcePath, $design , $screenWidth = 900){
  global $user_info, $module_upload;

  $src = imagecreatefromstring(file_get_contents($sourcePath));
  $fg = imagecreatefrompng(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/frame/' . $user_info['userid'] . '/' . $design);

  list($width, $height, $sourceType) = getimagesize($sourcePath);
  

$targetImage = imagecreatetruecolor(900, 900);
 switch ($sourceType) {
    case IMAGETYPE_JPEG:
      $sourceImage = imagecreatefromjpeg($sourcePath);
      break;
    case IMAGETYPE_PNG:
      $sourceImage = imagecreatefrompng($sourcePath);
      break;
    case IMAGETYPE_GIF:
      $sourceImage = imagecreatefromgif($sourcePath);
      break;
    default:
      return false;
  }
  imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, 900, 900, $width, $height);

  $croppedFG = imagecreatetruecolor(900, 900);

  $background = imagecolorallocate($croppedFG, 0, 0, 0);
  // removing the black from the placeholder
  imagecolortransparent($croppedFG, $background);

  imagealphablending($croppedFG, false);
  imagesavealpha($croppedFG, true);

  imagecopyresized($croppedFG, $fg, 0, 0, 0, 0, 900, 900, 900, 900);
	//$croppedFG = imagecreatetruecolor($targetWidth, $targetHeight);

  // Start merging
  $out = imagecreatetruecolor(900, 900);
  //$src = imagecreatefrompng($src);
  imagecopyresampled($out, $targetImage, 0, 0, 0, 0, 900, 900, 900, 900);
  imagecopyresampled($out, $croppedFG, 0, 0, 0, 0, 900, 900, 900, 900);

  ob_start();
  imagepng($out);
  $image = ob_get_clean();
  return $image;
}
function rand_string($length) {
     $str="";
     $chars = "subinsblogabcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
     $size = strlen($chars);
     for($i = 0;$i < $length;$i++) {
      $str .= $chars[rand(0,$size-1)];
    }
    return $str;
  }