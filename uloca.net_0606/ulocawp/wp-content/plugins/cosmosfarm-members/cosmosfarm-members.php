<?php
/*
Plugin Name: 코스모스팜 회원관리
Plugin URI: https://www.cosmosfarm.com/wpstore/product/cosmosfarm-members
Description: 한국형 회원가입 레이아웃과 기능을 제공합니다.
Version: 2.8.2
Author: 코스모스팜 - Cosmosfarm
Author URI: https://www.cosmosfarm.com/
*/

if(!defined('ABSPATH')) exit;
if(!session_id()) session_start();

define('COSMOSFARM_MEMBERS_VERSION', '2.8.2');
define('COSMOSFARM_MEMBERS_DIR_PATH', dirname(__FILE__));
define('COSMOSFARM_MEMBERS_URL', plugins_url('', __FILE__));

if(!defined('COSMOSFARM_MEMBERS_CERTIFIED_PHONE')){
	define('COSMOSFARM_MEMBERS_CERTIFIED_PHONE', false);
}

include_once 'class/Cosmosfarm_Members_Controller.class.php';
include_once 'class/Cosmosfarm_Members_KBoard.class.php';
include_once 'class/Cosmosfarm_Members_Mail.class.php';
include_once 'class/Cosmosfarm_Members_MailChimp.class.php';
include_once 'class/Cosmosfarm_Members_Message.class.php';
include_once 'class/Cosmosfarm_Members_Mycred.class.php';
include_once 'class/Cosmosfarm_Members_Notification.class.php';
include_once 'class/Cosmosfarm_Members_Option.class.php';
include_once 'class/Cosmosfarm_Members_Page_Builder.class.php';
include_once 'class/Cosmosfarm_Members_Security.class.php';
include_once 'class/Cosmosfarm_Members_Skin.class.php';
include_once 'class/Cosmosfarm_Members_Sms.class.php';
include_once 'class/Cosmosfarm_Members_Subscription_Order.class.php';
include_once 'class/Cosmosfarm_Members_Subscription_Product.class.php';
include_once 'class/Cosmosfarm_Members.class.php';

add_action('plugins_loaded', 'cosmosfarm_members_plugins_loaded');
function cosmosfarm_members_plugins_loaded(){
	global $cosmosfarm_members_option, $sosmosfarm_members_security;
	$cosmosfarm_members_option = get_cosmosfarm_members_option();
	$sosmosfarm_members_security = new Cosmosfarm_Members_Security();
}

add_action('init', 'cosmosfarm_members_init');
function cosmosfarm_members_init(){
	global $cosmosfarm_members, $cosmosfarm_members_skin, $cosmosfarm_members_sms, $cosmosfarm_members_page_builder, $cosmosfarm_members_controller, $cosmosfarm_members_kboard;
	
	load_plugin_textdomain('cosmosfarm-members', false, dirname(plugin_basename(__FILE__)) . '/languages');
	
	if(defined('WPMEM_VERSION')){
		$cosmosfarm_members = new Cosmosfarm_Members();
		$cosmosfarm_members_skin = new Cosmosfarm_Members_Skin();
		$cosmosfarm_members_sms = get_cosmosfarm_members_sms();
		$sosmosfarm_members_mailchimp = new Cosmosfarm_Members_MailChimp();
		$cosmosfarm_members_page_builder = new Cosmosfarm_Members_Page_Builder();
		$cosmosfarm_members_controller = new Cosmosfarm_Members_Controller();
		$cosmosfarm_members_kboard = new Cosmosfarm_Members_KBoard();
		
		add_action('admin_menu', array($cosmosfarm_members, 'add_admin_menu'));
	}
}

add_action('wp_login', 'cosmosfarm_members_subscription_again');
add_action('cosmosfarm_members_subscription_again', 'cosmosfarm_members_subscription_again');
function cosmosfarm_members_subscription_again($user_login='', $user=''){
	global $cosmosfarm_members_controller;
	
	$option = get_cosmosfarm_members_option();
	if($option->subscription_checkout_page_id){
		$subscription_order = new Cosmosfarm_Members_Subscription_Order();
		$args = array(
				'post_type'      => $subscription_order->post_type,
				'order'          => 'DESC',
				'orderby'        => 'ID',
				'posts_per_page' => '-1',
				'meta_query'     => array(
						array(
								'key'     => 'end_datetime',
								'value'   => date('YmdHis', current_time('timestamp')),
								'compare' => '<=',
						),
						array(
								'key'     => 'subscription_next',
								'value'   => 'wait',
								'compare' => '=',
						),
				),
		);
		$query = new WP_Query($args);
		foreach($query->posts as $post){
			$cosmosfarm_members_controller->subscription_again($post->ID);
		}
	}
}

add_action('admin_init', 'cosmosfarm_members_admin_init');
function cosmosfarm_members_admin_init(){
	include_once 'class/Cosmosfarm_Members_Meta_Box.class.php';
	$cosmosfarm_members_meta_box = new Cosmosfarm_Members_Meta_Box();
}

add_action('wp_enqueue_scripts', 'cosmosfarm_members_scripts', 999);
function cosmosfarm_members_scripts(){
	$cosmosfarm_members_option = get_cosmosfarm_members_option();
	
	wp_enqueue_script('cosmosfarm-members-script', COSMOSFARM_MEMBERS_URL . '/assets/js/script.js', array('jquery'), COSMOSFARM_MEMBERS_VERSION, true);
	wp_enqueue_script("cosmosfarm-members-{$cosmosfarm_members_option->skin}", COSMOSFARM_MEMBERS_URL . "/skin/{$cosmosfarm_members_option->skin}/script.js", array('jquery'), COSMOSFARM_MEMBERS_VERSION, true);
	wp_enqueue_style("cosmosfarm-members-{$cosmosfarm_members_option->skin}", COSMOSFARM_MEMBERS_URL . "/skin/{$cosmosfarm_members_option->skin}/style.css", array(), COSMOSFARM_MEMBERS_VERSION);
	
	// 스크립트 등록
	wp_register_script('daum-postcode', 'https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js', array('jquery'), '2.0.0', true);
	wp_register_script('iamport-payment', 'https://service.iamport.kr/js/iamport.payment-1.1.7.js', array('jquery'), '1.1.7');
	wp_register_script('cosmosfarm-members-subscription', COSMOSFARM_MEMBERS_URL . '/assets/js/subscription.js', array('iamport-payment'), COSMOSFARM_MEMBERS_VERSION, true);
	
	// 설정 등록
	$localize = array(
		'ajax_nonce' => wp_create_nonce('cosmosfarm-members-check-ajax-referer'),
		'home_url' => home_url('/', 'relative'),
		'site_url' => site_url('/', 'relative'),
		'post_url' => admin_url('/admin-post.php', 'relative'),
		'ajax_url' => admin_url('/admin-ajax.php', 'relative'),
		'locale' => get_cosmosfarm_members_locale(),
		'postcode_service_disabled' => $cosmosfarm_members_option->postcode_service_disabled,
		'use_postcode_service_iframe' => $cosmosfarm_members_option->use_postcode_service_iframe,
		'use_strong_password' => $cosmosfarm_members_option->use_strong_password,
		'use_certification' => $cosmosfarm_members_option->use_certification,
		'certified_phone' => COSMOSFARM_MEMBERS_CERTIFIED_PHONE,
		'certification_min_age' => $cosmosfarm_members_option->certification_min_age,
		'certification_name_field' => $cosmosfarm_members_option->certification_name_field,
		'certification_gender_field' => $cosmosfarm_members_option->certification_gender_field,
		'certification_birth_field' => $cosmosfarm_members_option->certification_birth_field,
		'certification_carrier_field' => $cosmosfarm_members_option->certification_carrier_field,
		'certification_phone_field' => $cosmosfarm_members_option->certification_phone_field,
		'iamport_id' => $cosmosfarm_members_option->iamport_id,
	);
	wp_localize_script("cosmosfarm-members-{$cosmosfarm_members_option->skin}", 'cosmosfarm_members_settings', $localize);
	
	// 번역 등록
	$localize = array(
		'please_enter_the_postcode' => __('Please enter the postcode.', 'cosmosfarm-members'),
		'please_wait' => __('Please wait.', 'cosmosfarm-members'),
		'yes' => __('Yes', 'cosmosfarm-members'),
		'no' => __('No', 'cosmosfarm-members'),
		'password_must_consist_of_8_digits' => __('Password must consist of 8 digits, including English, numbers and special characters.', 'cosmosfarm-members'),
		'your_password_is_different' => __('Your password is different.', 'cosmosfarm-members'),
		'please_enter_your_password_without_spaces' => __('Please enter your password without spaces.', 'cosmosfarm-members'),
		'it_is_a_safe_password' => __('It is a safe password.', 'cosmosfarm-members'),
		'male' => __('Male', 'cosmosfarm-members'),
		'female' => __('Female', 'cosmosfarm-members'),
		'certificate_completed' => __('Certificate Completed', 'cosmosfarm-members'),
		'please_fill_out_this_field' => __('Please fill out this field.', 'cosmosfarm-members'),
		'available' => __('Available.', 'cosmosfarm-members'),
		'not_available' => __('Not available.', 'cosmosfarm-members'),
		'already_in_use' => __('Already in use.', 'cosmosfarm-members'),
		'are_you_sure_you_want_to_delete' => __('Are you sure you want to delete?', 'cosmosfarm-members'),
		'no_notifications_found' => __('No notifications found.', 'cosmosfarm-members'),
		'no_messages_found' => __('No messages found.', 'cosmosfarm-members'),
		'no_orders_found' => __('No orders found.', 'cosmosfarm-members'),
		'no_users_found' => __('No users found.', 'cosmosfarm-members'),
		'please_agree' => __('Please agree.', 'cosmosfarm-members'),
		'place_order' => __('Place order', 'cosmosfarm-members'),
	);
	wp_localize_script("cosmosfarm-members-{$cosmosfarm_members_option->skin}", 'cosmosfarm_members_localize_strings', $localize);
}

add_action('admin_enqueue_scripts', 'cosmosfarm_members_admin_scripts', 999);
function cosmosfarm_members_admin_scripts(){
	$cosmosfarm_members_option = get_cosmosfarm_members_option();
	
	wp_enqueue_script('cosmosfarm-members-admin-script', COSMOSFARM_MEMBERS_URL . '/assets/js/admin.js', array('jquery'), COSMOSFARM_MEMBERS_VERSION, true);
	wp_enqueue_style('cosmosfarm-members-admin', COSMOSFARM_MEMBERS_URL . '/admin/admin.css', array(), COSMOSFARM_MEMBERS_VERSION);
	
	$localize = array(
			'ajax_nonce' => wp_create_nonce('cosmosfarm-members-check-ajax-referer'),
			'home_url' => home_url('/', 'relative'),
			'site_url' => site_url('/', 'relative'),
			'post_url' => admin_url('admin-post.php'),
			'ajax_url' => admin_url('admin-ajax.php'),
			'locale' => get_cosmosfarm_members_locale()
	);
	wp_localize_script('cosmosfarm-members-admin-script', 'cosmosfarm_members_admin_settings', $localize);
}

add_action('admin_notices', 'cosmosfarm_members_admin_notices');
function cosmosfarm_members_admin_notices(){
	if(!defined('WPMEM_VERSION')){
		$class = 'notice notice-error';
		$message = '코스모스팜 회원관리 사용을 위해서는 먼저 <a href="https://wordpress.org/plugins/wp-members/" onclick="window.open(this.href);return false;">WP-Members</a> 플러그인을 설치해주세요.';
		printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
	}
}

function cosmosfarm_members_menu_item($args){
	$item = new stdClass();
	$item->ID = 10000000 + (isset($args['order']) ? $args['order'] : 0);
	$item->db_id = $item->ID;
	$item->title = isset($args['title']) ? $args['title'] : '';
	$item->url = isset($args['url']) ? $args['url'] : '';
	$item->menu_order = $item->ID;
	$item->menu_item_parent = 0;
	$item->post_parent = 0;
	$item->type = 'custom';
	$item->object = 'custom';
	$item->object_id = '';
	$item->classes = isset($args['classes']) ? $args['classes'] : array();
	$item->target = '';
	$item->attr_title = '';
	$item->description = '';
	$item->xfn = '';
	$item->status = '';
	return $item;
}

function get_cosmosfarm_menu_add_login(){
	$menu_add_login = get_option('cosmosfarm_menu_add_login', '');
	return stripslashes($menu_add_login);
}

function get_cosmosfarm_login_menus(){
	$login_menus = get_option('cosmosfarm_login_menus', array());
	return $login_menus;
}

function get_cosmosfarm_policy_service_content(){
	$policy_service = get_option('cosmosfarm_members_policy_service', '');
	return stripslashes($policy_service);
}

function get_cosmosfarm_policy_privacy_content(){
	$policy_privacy = get_option('cosmosfarm_members_policy_privacy', '');
	return stripslashes($policy_privacy);
}

function get_cosmosfarm_members_option(){
	global $cosmosfarm_members_option;
	if($cosmosfarm_members_option === null){
		$cosmosfarm_members_option = new Cosmosfarm_Members_Option();
	}
	return $cosmosfarm_members_option;
}

function get_cosmosfarm_members_sms(){
	global $cosmosfarm_members_sms;
	if($cosmosfarm_members_sms === null){
		$cosmosfarm_members_sms = new Cosmosfarm_Members_Sms();
	}
	return $cosmosfarm_members_sms;
}

function get_cosmosfarm_members_profile_url(){
	$option = get_cosmosfarm_members_option();
	
	$profile_url = '';
	if($option->account_page_id || $option->account_page_url){
		if($option->account_page_id){
			$profile_url = get_permalink($option->account_page_id);
		}
		else if($option->account_page_url){
			$profile_url = $option->account_page_url;
		}
	}
	else if(wpmem_profile_url()){
		$profile_url = wpmem_profile_url();
	}
	return esc_url_raw($profile_url);
}

function get_cosmosfarm_members_notifications_url($args=array()){
	$option = get_cosmosfarm_members_option();
	
	$url = '';
	if($option->notifications_page_id){
		$url = get_permalink($option->notifications_page_id);
		$url = add_query_arg($args, $url);
	}
	return esc_url_raw($url);
}

function get_cosmosfarm_members_messages_url($args=array()){
	$option = get_cosmosfarm_members_option();
	
	$url = '';
	if($option->messages_page_id){
		$url = get_permalink($option->messages_page_id);
		$url = add_query_arg($args, $url);
	}
	return esc_url_raw($url);
}

function get_cosmosfarm_members_orders_url($args=array()){
	$option = get_cosmosfarm_members_option();
	
	$url = '';
	if($option->subscription_orders_page_id){
		$url = get_permalink($option->subscription_orders_page_id);
		$url = add_query_arg($args, $url);
	}
	return esc_url_raw($url);
}

function get_cosmosfarm_members_users_url($args=array()){
	$option = get_cosmosfarm_members_option();
	
	$url = '';
	if($option->users_page_id){
		$url = get_permalink($option->users_page_id);
		$url = add_query_arg($args, $url);
	}
	return esc_url_raw($url);
}

add_shortcode('cosmosfarm_members_login_url', 'get_cosmosfarm_members_login_url');
function get_cosmosfarm_members_login_url($args=array()){
	if(is_array($args) && isset($args['redirect']) && $args['redirect']){
		$redirect = $args['redirect'];
	}
	else if(is_array($args) && isset($args['redirect_query_var']) && $args['redirect_query_var'] && isset($_REQUEST[$args['redirect_query_var']]) && $_REQUEST[$args['redirect_query_var']]){
		$redirect = $_REQUEST[$args['redirect_query_var']];
	}
	else if(is_string($args) && $args){
		$redirect = $redirect;
	}
	else{
		$redirect = $_SERVER['REQUEST_URI'];
	}
	
	return wp_login_url($redirect);
}

add_shortcode('cosmosfarm_members_logout_url', 'get_cosmosfarm_members_logout_url');
function get_cosmosfarm_members_logout_url($args=array()){
	if(is_array($args) && isset($args['redirect']) && $args['redirect']){
		$redirect = $args['redirect'];
	}
	else if(is_array($args) && isset($args['redirect_query_var']) && $args['redirect_query_var'] && isset($_REQUEST[$args['redirect_query_var']]) && $_REQUEST[$args['redirect_query_var']]){
		$redirect = $_REQUEST[$args['redirect_query_var']];
	}
	else if(is_string($args) && $args){
		$redirect = $redirect;
	}
	else{
		$redirect = $_SERVER['REQUEST_URI'];
	}
	
	return wp_logout_url($redirect);
}

function get_cosmosfarm_members_file_handler(){
	if(!class_exists('Cosmosfarm_Members_File_Handler')){
		include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/Cosmosfarm_Members_File_Handler.class.php';
	}
	return new Cosmosfarm_Members_File_Handler();
}

function get_cosmosfarm_members_locale(){
	return apply_filters('cosmosfarm_members_locale', get_locale());
}

function get_cosmosfarm_members_news_list(){
	
	$news_list = get_transient('cosmosfarm_members_news_list');
	
	if($news_list){
		return $news_list;
	}
	
	$response = wp_remote_get('http://updates.wp-kboard.com/v1/AUTH_3529e134-c9d7-4172-8338-f64309faa5e5/kboard/news.json');
	
	if(!is_wp_error($response) && isset($response['body']) && $response['body']){
		$news_list = json_decode($response['body']);
	}
	else{
		$news_list = array();
	}
	
	set_transient('cosmosfarm_members_news_list', $news_list, 60*60);
	
	return $news_list;
}

function get_cosmosfarm_members_subscription_pg_type(){
	$subscription_pg_type = 'billing';
	$option = get_cosmosfarm_members_option();
	if($option->subscription_pg_type){
		$subscription_pg_type = $option->subscription_pg_type;
	}
	return apply_filters('cosmosfarm_members_subscription_pg_type', $subscription_pg_type);
}

function cosmosfarm_members_currency_format($value, $format='%s원'){
	return sprintf(apply_filters('cosmosfarm_members_currency_format', $format), number_format($value));
}

function cosmosfarm_members_send_verify_email($user, $verify_code=''){
	if($user->ID && $user->user_email){
		
		if(!$verify_code) $verify_code = md5(uniqid());
		$option = get_cosmosfarm_members_option();
		
		if($option->verify_email_title && $option->verify_email_content){
			
			$blogname = get_option('blogname');
			$home_url = home_url();
			$verify_email_url = home_url('?action=cosmosfarm_members_verify_email_confirm&verify_code='.$verify_code);
			
			$subject = str_replace('[blogname]', $blogname, $option->verify_email_title);
			$subject = str_replace('[home_url]', sprintf('<a href="%s" target="_blank">%s</a>', $home_url, $home_url), $subject);
			$subject = str_replace('[verify_email_url]', sprintf('<a href="%s" target="_blank">%s</a>', $verify_email_url, $verify_email_url), $subject);
			
			$message = str_replace('[blogname]', $blogname, $option->verify_email_content);
			$message = str_replace('[home_url]', sprintf('<a href="%s" target="_blank">%s</a>', $home_url, $home_url), $message);
			$message = str_replace('[verify_email_url]', sprintf('<a href="%s" target="_blank">%s</a>', $verify_email_url, $verify_email_url), $message);
			
			if($option->allow_email_login){
				$subject = str_replace('[id_or_email]', $user->user_email, $subject);
				$message = str_replace('[id_or_email]', $user->user_email, $message);
			}
			else{
				$subject = str_replace('[id_or_email]', $user->display_name, $subject);
				$message = str_replace('[id_or_email]', $user->display_name, $message);
			}
			
			$mail = new Cosmosfarm_Members_Mail();
			$mail->send(array(
					'to' => $user->user_email,
					'subject' => $subject,
					'message' => $message,
			));
		}
	}
	return $verify_code;
}

function cosmosfarm_members_send_confirmed_email($user){
	if($user->ID && $user->user_email){
		$option = get_cosmosfarm_members_option();
		
		if($option->confirmed_email_title && $option->confirmed_email_content){
			
			$blogname = get_option('blogname');
			$home_url = home_url();
			
			$subject = str_replace('[blogname]', $blogname, $option->confirmed_email_title);
			$subject = str_replace('[home_url]', sprintf('<a href="%s" target="_blank">%s</a>', $home_url, $home_url), $subject);
			
			$message = str_replace('[blogname]', $blogname, $option->confirmed_email_content);
			$message = str_replace('[home_url]', sprintf('<a href="%s" target="_blank">%s</a>', $home_url, $home_url), $message);
			
			if($option->allow_email_login){
				$subject = str_replace('[id_or_email]', $user->user_email, $subject);
				$message = str_replace('[id_or_email]', $user->user_email, $message);
			}
			else{
				$subject = str_replace('[id_or_email]', $user->display_name, $subject);
				$message = str_replace('[id_or_email]', $user->display_name, $message);
			}
			
			$mail = new Cosmosfarm_Members_Mail();
			$mail->send(array(
					'to' => $user->user_email,
					'subject' => $subject,
					'message' => $message,
			));
		}
	}
}

function cosmosfarm_members_send_message($args=array()){
	$message = new Cosmosfarm_Members_Message();
	return $message->create($args);
}

function cosmosfarm_members_send_notification($args=array()){
	$notification = new Cosmosfarm_Members_Notification();
	return $notification->create($args);
}

function cosmosfarm_members_skins(){
	$dir = COSMOSFARM_MEMBERS_DIR_PATH . '/skin';
	if($dh = opendir($dir)){
		while(($name = readdir($dh)) !== false){
			if($name == "." || $name == ".." || $name == "readme.txt") continue;
			$skin = new stdClass();
			$skin->name = $name;
			$skin->dir = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$name}";
			$skin->url = COSMOSFARM_MEMBERS_URL . "/skin/{$name}";
			$ist[$name] = $skin;
		}
	}
	closedir($dh);
	return apply_filters('cosmosfarm_members_skin_list', $ist);
}

function cosmosfarm_members_user_value_exists($meta_key, $meta_value, $skip_user_id=''){
	global $wpdb;
	
	if($meta_value){
		if(in_array($meta_key, array('username', 'user_login', 'user_nicename', 'user_email', 'user_url', 'display_name'))){
			if($meta_key == 'username') $meta_key = 'user_login';
			$meta_value = esc_sql($meta_value);
			
			$where = "`$meta_key`='$meta_value'";
			
			if($skip_user_id && is_array($skip_user_id)){
				$where .= " AND `ID` NOT IN (".implode(',', $skip_user_id).")";
			}
			else if($skip_user_id){
				$where .= " AND `ID`!='{$skip_user_id}'";
			}
			
			$count = $wpdb->get_var("SELECT COUNT(*) FROM `$wpdb->users` WHERE {$where}");
			if($count) return true;
		}
		else{
			$meta_key = esc_sql($meta_key);
			$meta_value = esc_sql($meta_value);
			
			$where = "`meta_key`='$meta_key' AND `meta_value`='$meta_value'";
			
			if($skip_user_id && is_array($skip_user_id)){
				$where .= " AND `user_id` NOT IN (".implode(',', $skip_user_id).")";
			}
			else if($skip_user_id){
				$where .= " AND `user_id`!='{$skip_user_id}'";
			}
			
			$count = $wpdb->get_var("SELECT COUNT(*) FROM `$wpdb->usermeta` WHERE {$where}");
			if($count) return true;
		}
	}
	return false;
}

function cosmosfarm_members_sms_send($phone, $content){
	$cosmosfarm_members_sms = get_cosmosfarm_members_sms();
	return $cosmosfarm_members_sms->send($phone, $content);
}

add_shortcode('cosmosfarm_members_social_buttons', 'cosmosfarm_members_social_buttons');
function cosmosfarm_members_social_buttons($args=array()){
	global $cosmosfarm_members;
	
	$option = get_cosmosfarm_members_option();
	
	if((!is_user_logged_in() || $option->social_buttons_shortcode_display != '1') && $option->social_login_active){
		
		$redirect_to = isset($args['redirect_to']) && $args['redirect_to'] ? $args['redirect_to'] : $_SERVER['REQUEST_URI'];
		$skin = isset($args['skin']) && $args['skin'] ? $args['skin'] : '';
		$file = isset($args['file']) && $args['file'] ? $args['file'] : '';
		
		return $cosmosfarm_members->social_buttons('shortcode', $redirect_to, $skin, $file);
	}
	
	return '';
}

add_shortcode('cosmosfarm_members_account_links', 'cosmosfarm_members_account_links');
function cosmosfarm_members_account_links($args=array()){
	global $cosmosfarm_members_skin;
	return $cosmosfarm_members_skin->account_links($args);
}

add_shortcode('cosmosfarm_members_unread_notifications_count', 'cosmosfarm_members_unread_notifications_count');
function cosmosfarm_members_unread_notifications_count($args=array()){
	$unread_count = 0;
	$user_id = 0;
	
	if(isset($args['user_id']) && $args['user_id']){
		$user_id = intval($args['user_id']);
	}
	
	if(!$user_id){
		$user_id = get_current_user_id();
	}
	
	if($user_id){
		$unread_count = intval(get_user_meta($user_id,  'cosmosfarm_members_unread_notifications_count', true));
	}
	
	return '<span class="cosmosfarm-members-unread-notifications-count'.($unread_count?'':' display-hide').'">' . $unread_count . '</span>';
}

add_shortcode('cosmosfarm_members_unread_messages_count', 'cosmosfarm_members_unread_messages_count');
function cosmosfarm_members_unread_messages_count($args=array()){
	$unread_count = 0;
	$user_id = 0;
	
	if(isset($args['user_id']) && $args['user_id']){
		$user_id = intval($args['user_id']);
	}
	
	if(!$user_id){
		$user_id = get_current_user_id();
	}
	
	if($user_id){
		$unread_count = intval(get_user_meta($user_id,  'cosmosfarm_members_unread_messages_count', true));
	}
	
	return '<span class="cosmosfarm-members-unread-messages-count'.($unread_count?'':' display-hide').'">' . $unread_count . '</span>';
}

add_filter('wpmem_settings', 'cosmosfarm_members_wpmem_settings', 10, 1);
function cosmosfarm_members_wpmem_settings($settings){
	$option = get_cosmosfarm_members_option();
	
	if($option->account_page_id){
		$settings['user_pages']['profile'] = $option->account_page_id;
	}
	
	if($option->register_page_id){
		$settings['user_pages']['register'] = $option->register_page_id;
	}
	
	if($option->login_page_id){
		$settings['user_pages']['login'] = $option->login_page_id;
	}
	
	return $settings;
}

add_action('mycred_init', 'cosmosfarm_members_mycred_init');
function cosmosfarm_members_mycred_init(){
	global $cosmosfarm_members_mycred;
	$cosmosfarm_members_mycred = new Cosmosfarm_Members_Mycred();
}

add_action('switch_blog', 'cosmosfarm_members_switch_blog');
function cosmosfarm_members_switch_blog(){
	global $cosmosfarm_members_option;
	$cosmosfarm_members_option = new Cosmosfarm_Members_Option();
}

add_action('plugins_loaded', 'cosmosfarm_members_update_check');
function cosmosfarm_members_update_check(){
	global $wpdb;
	
	if(version_compare(COSMOSFARM_MEMBERS_VERSION, get_option('cosmosfarm_members_version'), '<=')) return;
	
	if(get_option('cosmosfarm_members_version') !== false){
		update_option('cosmosfarm_members_version', COSMOSFARM_MEMBERS_VERSION);
	}
	else{
		add_option('cosmosfarm_members_version', COSMOSFARM_MEMBERS_VERSION, null, 'yes');
	}
	
	cosmosfarm_members_activation_execute();
}

register_activation_hook(__FILE__, 'cosmosfarm_members_activation');
function cosmosfarm_members_activation($networkwide){
	global $wpdb;
	if(function_exists('is_multisite') && is_multisite()){
		if($networkwide){
			$old_blog = $wpdb->blogid;
			$blogids = $wpdb->get_col("SELECT `blog_id` FROM {$wpdb->blogs}");
			foreach($blogids as $blog_id){
				switch_to_blog($blog_id);
				cosmosfarm_members_activation_execute();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	cosmosfarm_members_activation_execute();
}

function cosmosfarm_members_activation_execute(){
	global $wpdb;
	
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$charset_collate = $wpdb->get_charset_collate();
	
	dbDelta("CREATE TABLE `{$wpdb->prefix}cosmosfarm_members_login_history` (
	`login_history_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) unsigned NOT NULL,
	`login_datetime` datetime NOT NULL,
	`ip_address` varchar(20) NOT NULL,
	`browser` varchar(127) NOT NULL,
	`operating_system` varchar(127) NOT NULL,
	`country_name` varchar(127) NOT NULL,
	`country_code` varchar(127) NOT NULL,
	`login_result` varchar(20) NOT NULL,
	`user_agent` TEXT NOT NULL,
	PRIMARY KEY (`login_history_id`),
	KEY `user_id` (`user_id`)
	) {$charset_collate};");
	
	dbDelta("CREATE TABLE `{$wpdb->prefix}cosmosfarm_members_activity_history` (
	`activity_history_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) unsigned NOT NULL,
	`related_user_id` bigint(20) unsigned NOT NULL,
	`activity_datetime` datetime NOT NULL,
	`ip_address` varchar(20) NOT NULL,
	`comment` varchar(127) NOT NULL,
	PRIMARY KEY (`activity_history_id`),
	KEY `user_id` (`user_id`),
	KEY `related_user_id` (`related_user_id`)
	) {$charset_collate};");
	
	if(!wp_next_scheduled('cosmosfarm_members_subscription_again')){
		wp_schedule_event(time(), 'hourly', 'cosmosfarm_members_subscription_again');
	}
}

register_deactivation_hook(__FILE__, 'cosmosfarm_members_deactivation');
function cosmosfarm_members_deactivation(){
	wp_clear_scheduled_hook('cosmosfarm_members_subscription_again');
}

register_uninstall_hook(__FILE__, 'cosmosfarm_members_uninstall');
function cosmosfarm_members_uninstall(){
	global $wpdb;
	if(function_exists('is_multisite') && is_multisite()){
		$old_blog = $wpdb->blogid;
		$blogids = $wpdb->get_col("SELECT `blog_id` FROM {$wpdb->blogs}");
		foreach($blogids as $blog_id){
			switch_to_blog($blog_id);
			cosmosfarm_members_uninstall_execute();
		}
		switch_to_blog($old_blog);
		return;
	}
	cosmosfarm_members_uninstall_execute();
}

function cosmosfarm_members_uninstall_execute(){
	$option = get_cosmosfarm_members_option();
	$option->truncate();
}

add_filter('wpmem_localization_dir', 'cosmosfarm_members_localization_dir', 10, 2);
function cosmosfarm_members_localization_dir($dir, $locale){
	$dir = 'wp-members/lang/';
	return $dir;
}