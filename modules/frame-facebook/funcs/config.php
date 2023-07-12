<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_FRAME_FACEBOOK'))
    die('Stop!!!');

//$groups_list = nv_groups_list();

if ($nv_Request->isset_request('delete_fid', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $fid = $nv_Request->get_int('delete_fid', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($fid > 0 and $delete_checkss == md5($fid . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_frame  WHERE fid = ' . $db->quote($fid));
        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete Config', 'ID: ' . $fid, $admin_info['userid']);
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
}

$row = array();
$error = array();
$savesetting = $nv_Request->get_int('savesetting', 'post', 0);
if (!empty($savesetting)) {
	$array_config = [];
    
	$row['frame_image0'] = $nv_Request->get_title('frame_image0', 'post', '');
	//$sourceImg = @imagecreatefromstring(@file_get_contents($_FILES["frame_image0"]["tmp_name"]));
	//$directoryPath = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/frame/' . $row['userid'];

	$loc0 = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/frame/' . $row['frame_image0'];
	//file_put_contents($loc, $sourceImg);
	$data0 = $nv_Request->get_title('imgdata0', 'post', '');
	// Loại bỏ các ký tự lạ và mã hóa dữ liệu ảnh
	$filteredData = str_replace('data:image/png;base64,', '', $data0);
	$filteredData = str_replace(' ', '+', $filteredData);
	$decodedData = base64_decode($filteredData);
	file_put_contents($loc0, $decodedData);
	$row['frame_image1'] = $nv_Request->get_title('frame_image1', 'post', '');
	//$sourceImg = @imagecreatefromstring(@file_get_contents($_FILES["frame_image1"]["tmp_name"]));
	//$directoryPath = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/frame/';

	$loc1 = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/frame/' . $row['frame_image1'];
	//file_put_contents($loc, $sourceImg);
	$data1 = $nv_Request->get_title('imgdata1', 'post', '');
	// Loại bỏ các ký tự lạ và mã hóa dữ liệu ảnh
	$filteredData = str_replace('data:image/png;base64,', '', $data1);
	$filteredData = str_replace(' ', '+', $filteredData);
	$decodedData = base64_decode($filteredData);
	file_put_contents($loc1, $decodedData);
	$row['frame_image2'] = $nv_Request->get_title('frame_image2', 'post', '');
	//$sourceImg = @imagecreatefromstring(@file_get_contents($_FILES["frame_image2"]["tmp_name"]));
	//$directoryPath = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/frame/';

	$loc2 = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/frame/' . $row['frame_image2'];
	//file_put_contents($loc, $sourceImg);
	$data2 = $nv_Request->get_title('imgdata2', 'post', '');
	// Loại bỏ các ký tự lạ và mã hóa dữ liệu ảnh
	$filteredData = str_replace('data:image/png;base64,', '', $data2);
	$filteredData = str_replace(' ', '+', $filteredData);
	$decodedData = base64_decode($filteredData);
	file_put_contents($loc2, $decodedData);
	
	$array_config['frame_0'] = $row['frame_image0'];
    $array_config['frame_1'] = $row['frame_image1'];
    $array_config['frame_2'] = $row['frame_image2'];
	
	
	
	if (empty($error)) {
        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
        $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
        foreach ($array_config as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        $nv_Cache->delMod('settings');
        $nv_Cache->delMod($module_name);
    }
}


$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $module_config);
$xtpl->assign('IMAGE_0', $module_config['frame_0']);
$xtpl->assign('IMAGE_1', $module_config['frame_1']);
$xtpl->assign('IMAGE_2', $module_config['frame_2']);


if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['config'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
