<?php

// Version of the plugin
define('DUPLICATE_POST_CURRENT_VERSION', '2.4.1' );

require_once (dirname(__FILE__).'/duplicate-post-common.php');

if (is_admin()){
	require_once (dirname(__FILE__).'/duplicate-post-admin.php');
}

?>