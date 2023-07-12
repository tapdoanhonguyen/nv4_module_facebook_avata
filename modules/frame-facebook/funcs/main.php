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

    $query = 'SELECT status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_frame_users WHERE fid=' . $fid;
    $row = $db->query($query)->fetch();
    if (isset($row['status']))     {
        $status = ($row['status']) ? 0 : 1;
        $query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_frame_users SET status=' . intval($status) . ' WHERE fid=' . $fid;
        $db->query($query);
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
    if ($fid > 0 and $delete_checkss == md5($fid . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_frame_users  WHERE fid = ' . $db->quote($fid));
        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete Main', 'ID: ' . $fid, $admin_info['userid']);
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
}

$row = array();
$error = array();

	if(defined('NV_IS_USER')){
        $row['userid'] = $user_info['userid'];
    }elseif(!empty($array_op[0])){
		$user_q=$db->query('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE username = ' . $db->quote($array_op[0]));
		$user_f=$user_q->fetch();
		if(!empty($user_f)){
			$row['userid'] = $user_f['userid'];
			
		}else{
			$row['userid'] = 1;
		}
		
	}else{
        $row['userid'] = 1;
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
		if(!empty($array_op[0])){
			$db->where('(userid LIKE :q_userid OR title LIKE :q_title OR frame_image LIKE :q_frame_image) AND status = 1');
		}else{
			$db->where('(userid LIKE :q_userid OR title LIKE :q_title OR frame_image LIKE :q_frame_image) AND status = 1');
		}
    }elseif(!empty($array_op[0])){
		$db->where('userid LIKE :q_userid AND status = 1');
	}else{
		$db->where('userid LIKE :q_userid AND status = 1');
	}
    $sth = $db->prepare($db->sql());

    if (!empty($q)) {
        $sth->bindValue(':q_userid', '%' . $q . '%');
        $sth->bindValue(':q_title', '%' . $q . '%');
        $sth->bindValue(':q_frame_image', '%' . $q . '%');
    }else{
		$sth->bindValue(':q_userid', '%' . $row['userid'] . '%');
	}
    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select('*')
        ->order('fid DESC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());

    if (!empty($q)) {
        $sth->bindValue(':q_userid', '%' . $q . '%');
        $sth->bindValue(':q_title', '%' . $q . '%');
        $sth->bindValue(':q_frame_image', '%' . $q . '%');
    }else{
		$sth->bindValue(':q_userid', '%' . $row['userid'] . '%');
	}
	
    $sth->execute();
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
$xtpl->assign('TEMPLATE', $module_info['template']);
$xtpl->assign('USERID', $user_info['userid']);
$xtpl->assign('USER_LINK', $user_info['username']);
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
    $number = 0;
	if(!empty($sth)){
		while ($view = $sth->fetch()) {
			$view['frame'] = $view['frame_image'];
			if($view['userid'] > 0){
				if (!empty($view['frame_image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/frame/' . $view['userid'] . '/' . $view['frame_image'])) {
					
					$view['frame_image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/frame/' . $view['userid'] . '/' . $view['frame_image'];
				}
			}else{
				if (!empty($view['frame_image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $view['frame_image'])) {
					$view['frame_image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $view['frame_image'];
				}
			}
			if($number == 0) {
				$view['active'] = 'factive';
			}
			$xtpl->assign('IMAGEPERSON', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/person.png');
			$view['number'] = $number++;
			$xtpl->assign('CHECK', $view['status'] == 1 ? 'checked' : '');
			$view['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;fid=' . $view['fid'];
			$view['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_fid=' . $view['fid'] . '&amp;delete_checkss=' . md5($view['fid'] . NV_CACHE_PREFIX . $client_info['session_id']);
			$xtpl->assign('VIEW', $view);
			
			$xtpl->parse('main.view.loop');
			
		}
	}else{
		
		$xtpl->parse('main.view.guest');
	}
	if(defined('NV_IS_USER')){
		$xtpl->parse('main.share');
	}
	
    $xtpl->parse('main.view');
}


if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['main'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
