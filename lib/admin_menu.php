<?php
/**
 * CSV page template
 *
 */
if (isset($_GET['export'])) {
	$csvData = array( ocart_export_csv_file($_GET['export']) );
	$fp = fopen('php://memory', 'w+');
	foreach ($csvData as $row) {
	  fputcsv($fp, $row);
	}
	rewind($fp);
	$csvFile = stream_get_contents($fp);
	fclose($fp);
	header('Content-Type: text/csv');
	header('Content-Length: '.strlen($csvFile));
	header('Content-Disposition: attachment; filename="file.csv"');
	exit($csvFile);
}

// check order lookup by ID
if (isset($_GET['oc_orderlookup_id'])) {
	$orderID = $_GET['oc_orderlookup_id'];
	$post = get_post($orderID);
	if ($post) {
		wp_redirect ( admin_url().'edit.php?s='.$orderID.'&post_status=all&post_type=orders&searchby=orderID');
	} else {
		$error[0] = __('We cannot locate an order with that ID.','ocart');
	}
}

// check order lookup by email
if (isset($_GET['oc_orderlookup_email'])) {
	$email = $_GET['oc_orderlookup_email'];
	// try to find a customer with that email
	$user_id = email_exists($email);
	if ($user_id) {
		wp_redirect ( admin_url().'edit.php?s='.$user_id.'&post_status=all&post_type=orders&searchby=customer_email');
	} else {
		$error[1] = __('We cannot locate an order with that e-mail.','ocart');
	}
}

/* load extra plugins */
require_once get_template_directory().'/lib/admin/duplicate-post/duplicate-post.php';

// new class
class ControlPanel {

	var $options;

	// init
	function __construct() {
		add_action('admin_menu', array(&$this, 'add_menu'), 9);
		$this->options = get_option('ocart');
	}

	// add menus
	function add_menu() {
		add_menu_page(__('ocCommerce','ocart'), __('ocCommerce','ocart'), 'add_users', 'occommerce', array(&$this, 'optionsmenu'), get_template_directory_uri().'/lib/img/onecart-16.png', 8);
		add_submenu_page('occommerce', __('Dashboard','ocart'), __('Dashboard','ocart'), 'add_users', 'occommerce', array(&$this, 'optionsmenu'));
		add_submenu_page('occommerce', __('Settings','ocart'), __('Settings','ocart'), 'add_users', 'settings', array(&$this, 'optionsmenu'));
	}

	// load dashboard
	function optionsmenu() {
		include_once get_template_directory().'/lib/'.$_GET['page'].'.php';
	}

}

// load options in $ocart variable
$new_control_panel = new ControlPanel();
$ocart = get_option('ocart');
