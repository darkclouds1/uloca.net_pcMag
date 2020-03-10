<?php
define('KBOARD_CROSS_LINK_VERSION', '1.5');

load_plugin_textdomain('kboard-cross-link', false, dirname(plugin_basename(__FILE__)) . '/languages');

add_action('wp_enqueue_scripts', 'kboard_cross_link_scripts', 999);
add_action('kboard_iframe_head', 'kboard_cross_link_scripts');
function kboard_cross_link_scripts(){
	$localize = array(
		'missing_link_address' => __('Missing link address.', 'kboard-cross-link'),
		'no_more_data' => __('There is no more data to display.', 'kboard-cross-link'),
	);
	wp_localize_script('kboard-script', 'kboard_cross_link_localize_strings', $localize);
}

add_action('init', 'kboard_cross_link_more_view');
function kboard_cross_link_more_view(){
	$action = isset($_GET['action'])?$_GET['action']:'';
	$board_id = isset($_GET['board_id'])?intval($_GET['board_id']):'';
	
	if(!$board_id){
		$board_id = isset($_GET['kboard_id'])?intval($_GET['kboard_id']):'';
	}

	if($action == 'kboard_cross_link_more_view' && $board_id){
		echo kboard_builder(array('id'=>$board_id));
		exit;
	}
}

add_action('wp_ajax_kboard_cross_link_shortcusts', 'kboard_cross_link_shortcusts');
add_action('wp_ajax_nopriv_kboard_cross_link_shortcusts', 'kboard_cross_link_shortcusts');
function kboard_cross_link_shortcusts(){
	$content_uid = isset($_POST['content_uid'])?intval($_POST['content_uid']):'';
	if($content_uid){
		$content = new KBContent();
		$content->initWithUID($content_uid);
		$content->increaseView();
	}
	exit;
}

function kboard_cross_link_print($link){
	if(strpos($link, 'http://') !== false || strpos($link, 'https://') !== false){
		return $link;
	}
	return "http://{$link}";
}