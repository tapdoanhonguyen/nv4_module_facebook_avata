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

// Change status
if ($nv_Request->isset_request('change_status', 'post, get')) {
    $fid = $nv_Request->get_int('fid', 'post, get', 0);
    $content = 'NO_' . $fid;

    $query = 'SELECT status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_frame WHERE fid=' . $fid;
    $row = $db->query($query)->fetch();
    if (isset($row['status']))     {
        $status = ($row['status']) ? 0 : 1;
        $query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_frame SET status=' . intval($status) . ' WHERE fid=' . $fid;
        $db->query($query);
        $content = 'OK_' . $fid;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
}

if ($nv_Request->isset_request('ajax_action', 'post')) {
    $fid = $nv_Request->get_int('fid', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);
    $content = 'NO_' . $fid;
    if ($new_vid > 0)     {
        $sql = 'SELECT fid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_frame WHERE fid!=' . $fid . ' ORDER BY add_time ASC';
        $result = $db->query($sql);
        $add_time = 0;
        while ($row = $result->fetch())
        {
            ++$add_time;
            if ($add_time == $new_vid) ++$add_time;             $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_frame SET add_time=' . $add_time . ' WHERE fid=' . $row['fid'];
            $db->query($sql);
        }
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_frame SET add_time=' . $new_vid . ' WHERE fid=' . $fid;
        $db->query($sql);
        $content = 'OK_' . $fid;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
}

if ($nv_Request->isset_request('delete_fid', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $fid = $nv_Request->get_int('delete_fid', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if(defined('NV_IS_USER')){
        $userid = $user_info['userid'];
    }else{
        $userid = 0;
    }
    if ($userid > 0 and $fid > 0 and $delete_checkss == md5($fid . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $add_time=0;
        $sql = 'SELECT add_time FROM ' . NV_PREFIXLANG . '_' . $module_data . '_frame WHERE fid =' . $db->quote($fid);
        $result = $db->query($sql);
        list($add_time) = $result->fetch(3);
        
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_frame  WHERE fid = ' . $db->quote($fid));
        if ($add_time > 0)         {
            $sql = 'SELECT fid, add_time FROM ' . NV_PREFIXLANG . '_' . $module_data . '_frame WHERE add_time >' . $add_time;
            $result = $db->query($sql);
            while (list($fid, $add_time) = $result->fetch(3))
            {
                $add_time--;
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_frame SET add_time=' . $add_time . ' WHERE fid=' . intval($fid));
            }
        }
        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete Myframe', 'ID: ' . $fid, $userid);
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }else{
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
}
if(defined('NV_IS_USER')){
	$row = array();
	$error = array();
	$row['fid'] = $nv_Request->get_int('fid', 'post,get', 0);
	if(defined('NV_IS_USER')){
			$row['userid'] = $user_info['userid'];
		}else{
			$row['userid'] = 0;
		}
	if ($nv_Request->isset_request('submit', 'post')) {
		if(defined('NV_IS_USER')){
			$row['userid'] = $user_info['userid'];
		}else{
			$row['userid'] = 0;
		}
		$row['title'] = $nv_Request->get_title('title', 'post', '');
		$row['frame_image'] = $nv_Request->get_title('frame_image', 'post', '');
		$sourceImg = @imagecreatefromstring(@file_get_contents($_FILES["frame_image"]["tmp_name"]));
		$directoryPath = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/frame/' . $row['userid'];

		if (!is_dir($directoryPath)) {
			if (mkdir($directoryPath, 0777, true)) {
				echo "Thư mục đã được tạo thành công.";
			} else {
				echo "Không thể tạo thư mục.";
			}
		}
		$loc = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/frame/' . $row['userid'] . '/' . $row['frame_image'];
		//file_put_contents($loc, $sourceImg);
		$data = $nv_Request->get_title('imgdata', 'post', '');
		// Loại bỏ các ký tự lạ và mã hóa dữ liệu ảnh
		$filteredData = str_replace('data:image/png;base64,', '', $data);
		$filteredData = str_replace(' ', '+', $filteredData);
		$decodedData = base64_decode($filteredData);
		file_put_contents($loc, $decodedData);
		
		
		
		


		
		$_groups_post = $nv_Request->get_array('groups_view', 'post', array());
		//$row['groups_view'] = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';
		$row['status'] = $nv_Request->get_int('status', 'post', 0);

		if (empty($row['title'])) {
			$error[] = $lang_module['error_required_title'];
		} 

		if (empty($error)) {
			try {
				if (empty($row['fid'])) {
					//$row['userid'] = 0;
					$row['edit_time'] = 0;
					$row['add_time'] = NV_CURRENTTIME;
					$stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_frame (userid, title, frame_image, add_time, edit_time, groups_view, status) VALUES (:userid, :title, :frame_image, :add_time, :edit_time, :groups_view, :status)');

					$stmt->bindParam(':userid', $row['userid'], PDO::PARAM_INT);
					
					$stmt->bindParam(':add_time', $row['add_time'], PDO::PARAM_INT);

					$stmt->bindParam(':edit_time', $row['edit_time'], PDO::PARAM_INT);

				} else {
					$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_frame SET title = :title, frame_image = :frame_image, groups_view = :groups_view, status = :status WHERE fid=' . $row['fid']);
				}
				$stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
				$stmt->bindParam(':frame_image', $row['frame_image'], PDO::PARAM_STR);
				$stmt->bindParam(':groups_view', $row['groups_view'], PDO::PARAM_STR);
				$stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);

				$exc = $stmt->execute();
				if ($exc) {
					$nv_Cache->delMod($module_name);
					if (empty($row['fid'])) {
						nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Myframe', ' ', $row['userid']);
					} else {
						nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Myframe', 'ID: ' . $row['fid'], $row['userid']);
					}
					nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
				}
			} catch(PDOException $e) {
				trigger_error($e->getMessage());
				die($e->getMessage()); //Remove this line after checks finished
			}
		}
	} elseif ($row['fid'] > 0) {
		$row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_frame WHERE fid=' . $row['fid'] . ' and userid=' . $row['userid'])->fetch();
		if (empty($row)) {
			nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
		}
	} else {
		$row['fid'] = 0;
		
		$row['title'] = '';
		$row['frame_image'] = '';
		$row['groups_view'] = '6';
		$row['status'] = 1;
	}
	if($row['userid'] > 0){
		if (!empty($row['frame_image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/frame/' . $row['userid'] . '/' . $row['frame_image'])) {
			$row['frame_image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/frame/' . $row['userid'] . '/' . $row['frame_image'];
		}
	}else{
		if (!empty($row['frame_image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['frame_image'])) {
			$row['frame_image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['frame_image'];
		}
	}
	$q = $nv_Request->get_title('q', 'post,get');

	// Fetch Limit
	$show_view = false;
	if (!$nv_Request->isset_request('id', 'post,get')) {
		$show_view = true;
		$per_page = 20;
		$page = $nv_Request->get_int('page', 'post,get', 1);
		$db->sqlreset()
			->select('COUNT(*)')
			->from('' . NV_PREFIXLANG . '_' . $module_data . '_frame');

		if (!empty($q)) {
			$db->where('userid LIKE :q_userid OR title LIKE :q_title OR frame_image LIKE :q_frame_image');
		}else{
	$db->where('userid LIKE :q_userid');
	}
		$sth = $db->prepare($db->sql());

		if (!empty($q)) {
			$sth->bindValue(':q_userid', '%' . $row['userid'] . '%');
			$sth->bindValue(':q_title', '%' . $q . '%');
			$sth->bindValue(':q_frame_image', '%' . $q . '%');
		}else{
	$sth->bindValue(':q_userid', '%' . $row['userid'] . '%');
	}
		$sth->execute();
		$num_items = $sth->fetchColumn();

		$db->select('*')
			->order('add_time ASC')
			->limit($per_page)
			->offset(($page - 1) * $per_page);
		$sth = $db->prepare($db->sql());

		if (!empty($q)) {
			$sth->bindValue(':q_userid', '%' . $row['userid'] . '%');
			$sth->bindValue(':q_title', '%' . $q . '%');
			$sth->bindValue(':q_frame_image', '%' . $q . '%');
		}else{
	$sth->bindValue(':q_userid', '%' . $row['userid'] . '%');
	}
		$sth->execute();
	}

	$xtpl = new XTemplate('myframe.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
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
	$xtpl->assign('ROW', $row);

	$xtpl->assign('Q', $q);

	if ($show_view) {
		$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
		if (!empty($q)) {
			$base_url .= '&q=' . $q;
		}
		$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
		if (!empty($generate_page)) {
			$xtpl->assign('NV_GENERATE_PAGE', $generate_page);
			$xtpl->parse('main.view.generate_page');
		}
		$number = $page > 1 ? ($per_page * ($page - 1)) + 1 : 1;
		while ($view = $sth->fetch()) {
	if($view['userid'] > 0){
		if (!empty($view['frame_image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/frame/' . $view['userid'] . '/' . $view['frame_image'])) {
			$view['frame_image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/frame/' . $view['userid'] . '/' . $view['frame_image'];
		}
	}else{
		if (!empty($view['frame_image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $view['frame_image'])) {
			$view['frame_image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $view['frame_image'];
		}
	}


			for($i = 1; $i <= $num_items; ++$i) {
				$xtpl->assign('WEIGHT', array(
					'key' => $i,
					'title' => $i,
					'selected' => ($i == $view['add_time']) ? ' selected="selected"' : ''));
				$xtpl->parse('main.view.loop.add_time_loop');
			}
			$xtpl->assign('CHECK', $view['status'] == 1 ? 'checked' : '');
			if($row['userid'] > 0){
				$ulink['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;fid=' . $view['fid'];
				$ulink['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_fid=' . $view['fid'] . '&amp;delete_checkss=' . md5($view['fid'] . NV_CACHE_PREFIX . $client_info['session_id']);
				$xtpl->assign('ULINK', $ulink);
				$xtpl->parse('main.view.loop.action');
			}

			$xtpl->assign('VIEW', $view);
			$xtpl->parse('main.view.loop');
		}
		$xtpl->parse('main.view');
	}


	if (!empty($error)) {
		$xtpl->assign('ERROR', implode('<br />', $error));
		$xtpl->parse('main.error');
	}

	$xtpl->parse('main');
	$contents = $xtpl->text('main');

	$page_title = $lang_module['myframe'];

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme($contents);
	include NV_ROOTDIR . '/includes/footer.php';
}else{
	nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']) . '&checkss=' . md5('0' . NV_CHECK_SESSION));
}