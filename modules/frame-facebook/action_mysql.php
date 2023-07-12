<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Tập Đoàn Họ Nguyễn <adminwmt@gmail.com>
 * @Copyright (C) 2023 Tập Đoàn Họ Nguyễn. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 24 Jun 2023 13:16:47 GMT
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_frame";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_frame_users";

$sql_create_module = $sql_drop_module;
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_frame (
  fid int(11) unsigned NOT NULL AUTO_INCREMENT,
  userid int(11) unsigned NOT NULL DEFAULT '0',
  title varchar(250) NOT NULL,
  frame_image varchar(250) DEFAULT '',
  add_time int(11) unsigned NOT NULL DEFAULT '0',
  edit_time int(11) unsigned NOT NULL DEFAULT '0',
  groups_view varchar(255) DEFAULT '',
  status smallint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (fid),
  KEY status (status)
) ENGINE=MyISAM;";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_frame_users (
  fid int(11) unsigned NOT NULL AUTO_INCREMENT,
  userid int(11) unsigned NOT NULL DEFAULT '0',
  title varchar(250) NOT NULL,
  frame_image varchar(250) DEFAULT '',
  add_time int(11) unsigned NOT NULL DEFAULT '0',
  edit_time int(11) unsigned NOT NULL DEFAULT '0',
  groups_view varchar(255) DEFAULT '',
  status smallint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (fid),
  KEY status (status)
) ENGINE=MyISAM;";


// Gửi báo cáo lỗi
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'frame_0', 'frame_0.png')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'frame_1', 'frame_1.png')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'frame_2', 'frame_2.png')";
