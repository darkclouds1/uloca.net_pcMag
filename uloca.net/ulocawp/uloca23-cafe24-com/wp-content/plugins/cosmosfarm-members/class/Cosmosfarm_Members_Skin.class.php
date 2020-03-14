<?php
/**
 * Cosmosfarm_Members_Skin
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2018 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Skin {
	
	public function __construct(){
		$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
		switch($action){
			case 'cosmosfarm_members_skin_notifications_list':
				check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
				echo $this->notifications_list();
				exit;
			case 'cosmosfarm_members_skin_messages_list':
				check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
				echo $this->messages_list();
				exit;
			case 'cosmosfarm_members_skin_orders_list':
				check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
				echo $this->orders_list();
				exit;
			case 'cosmosfarm_members_skin_users_list':
				check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
				echo $this->users_list();
				exit;
		}
	}
	
	public function header($current_page=''){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
		$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/header.php";
		$layout = '';
		
		$file_path = apply_filters('cosmosfarm_members_template_header', $file_path);
		
		if(file_exists($file_path)){
			
			if(!$current_page){
				if($option->subscription_orders_page_id == $post->ID){
					$current_page = 'orders';
				}
				else if($option->notifications_page_id == $post->ID){
					$current_page = 'notifications';
				}
				else if($option->messages_page_id == $post->ID){
					$current_page = 'messages';
				}
				else if($option->users_page_id == $post->ID){
					$current_page = 'users';
				}
			}
			
			$menu_items = array();
			
			$menu_items['profile'] = array(
				'id' => 'profile',
				'url' => get_cosmosfarm_members_profile_url(),
				'title' => __('Account', 'cosmosfarm-members'),
			);
			
			if($option->subscription_orders_page_id){
				$menu_items['orders'] = array(
					'id' => 'orders',
					'url' => get_cosmosfarm_members_orders_url(),
					'title' => __('Orders', 'cosmosfarm-members'),
				);
			}
			
			if($option->notifications_page_id){
				$menu_items['notifications'] = array(
					'id' => 'notifications',
					'url' => get_cosmosfarm_members_notifications_url(),
					'title' => sprintf('%s %s', __('Notifications', 'cosmosfarm-members'), cosmosfarm_members_unread_notifications_count()),
				);
			}
			
			if($option->messages_page_id){
				$menu_items['messages'] = array(
					'id' => 'messages',
					'url' => get_cosmosfarm_members_messages_url(),
					'title' => sprintf('%s %s', __('Messages', 'cosmosfarm-members'), cosmosfarm_members_unread_messages_count()),
				);
			}
			
			if($option->users_page_id){
				$menu_items['users'] = array(
					'id' => 'users',
					'url' => get_cosmosfarm_members_users_url(),
					'title' => __('Members', 'cosmosfarm-members'),
				);
			}
			
			$menu_items = apply_filters('cosmosfarm_members_header_menu_items', $menu_items);
			
			$current_page = apply_filters('cosmosfarm_members_header_menu_current_page', $current_page, $menu_items);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function login_form($layout, $action){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '';
		
		if(!$redirect_to && $post->ID == $option->login_page_id){
			$redirect_to = home_url();
		}
		else if(!$redirect_to){
			$redirect_to = get_permalink();
		}
		
		$redirect_to = apply_filters('cosmosfarm_members_login_redirect_to', $redirect_to);
		$login_action_url = remove_query_arg(array('verify_email_confirm', 'register_success', 'login_timeout'));
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/login-form.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/login-form.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/login-form.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_login_form', $file_path, $action);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function change_password_form($layout, $action){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$form_action_url = get_permalink();
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/change-password-form.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/change-password-form.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/change-password-form.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_change_password_form', $file_path, $action);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function account_links($args=array()){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/account-links.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/account-links.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/account-links.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_account_links', $file_path, $args);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function login_timeout_popup($login_timeout_url, $login_timeout){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/login-timeout-popup.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/login-timeout-popup.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/login-timeout-popup.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_login_timeout_popup', $file_path, $login_timeout_url, $login_timeout);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function notifications(){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/notifications.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/notifications.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/notifications.php";
		}
		
		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
		$notifications_view = isset($_GET['notifications_view']) ? sanitize_text_field($_GET['notifications_view']) : 'inbox';
		
		if(!in_array($notifications_view, array('inbox', 'unread'))){
			$notifications_view = 'inbox';
		}
		
		$notification = new Cosmosfarm_Members_Notification();
		
		$file_path = apply_filters('cosmosfarm_members_template_notifications', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function notifications_list(){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/notifications-list.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/notifications-list.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/notifications-list.php";
		}
		
		$paged = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']): 1;
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		$notifications_view = isset($_REQUEST['notifications_view']) ? sanitize_text_field($_REQUEST['notifications_view']) : 'inbox';
		
		if(!in_array($notifications_view, array('inbox', 'unread'))){
			$notifications_view = 'inbox';
		}
		
		$meta_query = array();
		if($notifications_view == 'unread'){
			$meta_query[] = array(
				'key'     => 'item_status',
				'value'   => $notifications_view,
				'compare' => '=',
			);
		}
		
		$notification = new Cosmosfarm_Members_Notification();
		
		$file_path = apply_filters('cosmosfarm_members_template_notifications_list', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function notifications_list_item($post_id, $item_type_default='default'){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		$item = new Cosmosfarm_Members_Notification($post_id);
		$from_user_id = get_post_meta($item->ID, 'from_user_id', true);
		$from_user = get_userdata($from_user_id);
		$item_type = get_post_meta($item->ID, 'item_type', true);
		$item_type = $item_type ? $item_type : $item_type_default;
		
		if(file_exists(get_stylesheet_directory() . sprintf('/cosmosfarm-members/notifications-list-item-%s.php', $item_type))){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . sprintf('/cosmosfarm-members/notifications-list-item-%s.php', $item_type);
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . sprintf('/skin/%s/notifications-list-item-%s.php', $option->skin, $item_type);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_notifications_list_item', $file_path, $item_type, $item);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function messages(){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		$redirect_to = isset($_GET['redirect_to']) ? esc_url_raw($_GET['redirect_to']) : get_cosmosfarm_members_messages_url();
		$to_user_id = isset($_GET['to_user_id']) ? intval($_GET['to_user_id']) : '';
		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
		$messages_view = isset($_GET['messages_view']) ? sanitize_text_field($_GET['messages_view']) : 'inbox';
		
		if(!in_array($messages_view, array('inbox', 'sent'))){
			$messages_view = 'inbox';
		}
		
		if($to_user_id){
			$to_user = get_userdata($to_user_id);
			if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/messages-form.php')){
				$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
				$file_path = get_stylesheet_directory() . '/cosmosfarm-members/messages-form.php';
			}
			else{
				$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
				$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/messages-form.php";
			}
		}
		else{
			if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/messages.php')){
				$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
				$file_path = get_stylesheet_directory() . '/cosmosfarm-members/messages.php';
			}
			else{
				$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
				$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/messages.php";
			}
		}
		
		$message = new Cosmosfarm_Members_Message();
		
		$file_path = apply_filters('cosmosfarm_members_template_messages', $file_path, $to_user_id);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function messages_list(){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/messages-list.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/messages-list.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/messages-list.php";
		}
		
		$paged = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']): 1;
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		$messages_view = isset($_REQUEST['messages_view']) ? sanitize_text_field($_REQUEST['messages_view']) : 'inbox';
		
		if(!in_array($messages_view, array('inbox', 'sent'))){
			$messages_view = 'inbox';
		}
		
		$meta_query = array();
		if($messages_view == 'inbox'){
			$meta_query[] = array(
				'key'     => 'to_user_id',
				'value'   => get_current_user_id(),
				'compare' => '=',
			);
		}
		else if($messages_view == 'sent'){
			$meta_query[] = array(
				'key'     => 'from_user_id',
				'value'   => get_current_user_id(),
				'compare' => '=',
			);
		}
		
		$message = new Cosmosfarm_Members_Message();
		
		$file_path = apply_filters('cosmosfarm_members_template_messages_list', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function messages_list_item($post_id, $item_type_default='default'){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		$item = new Cosmosfarm_Members_Message($post_id);
		$from_user_id = get_post_meta($item->ID, 'from_user_id', true);
		$from_user = get_userdata($from_user_id);
		$item_type = get_post_meta($item->ID, 'item_type', true);
		$item_type = $item_type ? $item_type : $item_type_default;
		
		if(file_exists(get_stylesheet_directory() . sprintf('/cosmosfarm-members/messages-list-item-%s.php', $item_type))){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . sprintf('/cosmosfarm-members/messages-list-item-%s.php', $item_type);
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . sprintf('/skin/%s/messages-list-item-%s.php', $option->skin, $item_type);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_messages_list_item', $file_path, $item_type, $item);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function orders(){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/orders.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/orders.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/orders.php";
		}
		
		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
		$orders_view = isset($_GET['orders_view']) ? sanitize_text_field($_GET['orders_view']) : 'paid';
		
		if(!in_array($orders_view, array('paid', 'expired'))){
			$orders_view = 'paid';
		}
		
		$order = new Cosmosfarm_Members_Subscription_Order();
		
		$file_path = apply_filters('cosmosfarm_members_template_orders', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function orders_list(){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/orders-list.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/orders-list.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/orders-list.php";
		}
		
		$paged = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']): 1;
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		$orders_view = isset($_REQUEST['orders_view']) ? sanitize_text_field($_REQUEST['orders_view']) : 'paid';
		
		if(!in_array($orders_view, array('paid', 'expired'))){
			$orders_view = 'paid';
		}
		
		$meta_query = array();
		if($orders_view == 'paid'){
			$meta_query[] = array(
				'key'     => 'subscription_next',
				'value'   => array('success', 'wait'),
				'compare' => 'IN',
			);
		}
		else if($orders_view == 'expired'){
			$meta_query[] = array(
				'key'     => 'subscription_next',
				'value'   => array('expiry', 'cancel'),
				'compare' => 'IN',
			);
		}
		
		$order = new Cosmosfarm_Members_Subscription_Order();
		
		$file_path = apply_filters('cosmosfarm_members_template_orders_list', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function orders_list_item($post_id, $item_type_default='default'){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		$item = new Cosmosfarm_Members_Subscription_Order($post_id);
		$item_type = get_post_meta($item->ID, 'item_type', true);
		$item_type = $item_type ? $item_type : $item_type_default;
		
		if(file_exists(get_stylesheet_directory() . sprintf('/cosmosfarm-members/orders-list-item-%s.php', $item_type))){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . sprintf('/cosmosfarm-members/orders-list-item-%s.php', $item_type);
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . sprintf('/skin/%s/orders-list-item-%s.php', $option->skin, $item_type);
		}
		
		$product = new Cosmosfarm_Members_Subscription_Product($item->product_id());
		$fields = $product->order_view_fields();
		
		$file_path = apply_filters('cosmosfarm_members_template_orders_list_item', $file_path, $item_type, $item);
		
		if(file_exists($file_path)){
			wp_enqueue_script('cosmosfarm-members-subscription');
			
			$settings = array(
				'iamport_id' => $option->iamport_id,
				'subscription_pg' => $option->subscription_pg,
			);
			wp_localize_script("cosmosfarm-members-{$option->skin}", 'cosmosfarm_members_subscription_checkout_settings', $settings);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function users(){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/users.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/users.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/users.php";
		}
		
		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
		
		$file_path = apply_filters('cosmosfarm_members_template_users', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function users_list(){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/users-list.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/users-list.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/users-list.php";
		}
		
		$paged = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']): 1;
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		
		$file_path = apply_filters('cosmosfarm_members_template_users_list', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function users_list_item($user, $item_type_default='default'){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		$item_type = get_user_meta($user->ID, 'item_type', true);
		$item_type = $item_type ? $item_type : $item_type_default;
		
		if(file_exists(get_stylesheet_directory() . sprintf('/cosmosfarm-members/users-list-item-%s.php', $item_type))){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . sprintf('/cosmosfarm-members/users-list-item-%s.php', $item_type);
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . sprintf('/skin/%s/users-list-item-%s.php', $option->skin, $item_type);
		}
		
		$file_path = sprintf($file_path, $item_type);
		$file_path = apply_filters('cosmosfarm_members_template_users_list_item', $file_path, $item_type, $user);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function subscription_product($product){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		$user = wp_get_current_user();
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/subscription-product.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/subscription-product.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-product.php";
		}
		
		if(is_int($product)){
			$product = new Cosmosfarm_Members_Subscription_Product($product);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_product', $file_path, $product);
		
		if(file_exists($file_path)){
			wp_enqueue_script('cosmosfarm-members-subscription');
			
			$settings = array(
				'iamport_id' => $option->iamport_id,
				'subscription_pg' => $option->subscription_pg,
			);
			wp_localize_script("cosmosfarm-members-{$option->skin}", 'cosmosfarm_members_subscription_checkout_settings', $settings);
			
			do_action('cosmosfarm_members_skin_subscription_product', $this, $product);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function subscription_checkout($product){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		$user = wp_get_current_user();
		
		include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Iamport.class.php';
		$iamport = new Cosmosfarm_Members_API_Iamport();
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/subscription-checkout.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/subscription-checkout.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-checkout.php";
		}
		
		if(is_int($product)){
			$product = new Cosmosfarm_Members_Subscription_Product($product);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_checkout', $file_path, $product);
		
		if(file_exists($file_path)){
			wp_enqueue_script('daum-postcode');
			wp_enqueue_script('cosmosfarm-members-subscription');
			
			$pay_success_url = isset($_GET['cosmosfarm_redirect_to']) ? esc_url_raw($_GET['cosmosfarm_redirect_to']) : wp_get_referer();
			$pay_success_url = esc_url_raw(apply_filters('cosmosfarm_members_subscription_pay_success_url', $pay_success_url, $product));
			
			$subscription_pg_type = get_cosmosfarm_members_subscription_pg_type();
			if($subscription_pg_type == 'general'){
				$m_redirect_url = add_query_arg(array(
					'action' => 'cosmosfarm_members_subscription_request_pay_complete',
					'display' => 'mobile',
					'product_id' => $product->ID(),
					'checkout_nonce' => wp_create_nonce('cosmosfarm-members-subscription-checkout-' . $product->ID()),
				), get_permalink());
			}
			else{
				$m_redirect_url = add_query_arg(array(
					'action' => 'cosmosfarm_members_subscription_request_pay_mobile',
					'product_id' => $product->ID(),
					'checkout_nonce' => wp_create_nonce('cosmosfarm-members-subscription-checkout-' . $product->ID()),
				), get_permalink());
			}
			$m_redirect_url = esc_url_raw(apply_filters('cosmosfarm_members_subscription_m_redirect_url', $m_redirect_url, $product));
			
			if($product->subscription_type() == 'monthly'){
				$product_period_interval = 'month';
			}
			else if($product->subscription_type() == '12monthly'){
				$product_period_interval = 'year';
			}
			else{
				$product_period_interval = '';
			}
			$product_period_from = date('Ymd', current_time('timestamp'));
			
			$settings = array(
				'checkout_nonce'          => wp_create_nonce('cosmosfarm-members-subscription-checkout-' . $product->ID()),
				'iamport_id'              => $option->iamport_id,
				'subscription_pg_type'    => $subscription_pg_type,
				'subscription_pg'         => $option->subscription_pg,
				'subscription_general_pg' => $option->subscription_general_pg,
				'merchant_uid'            => $iamport->getMerchantUID(),
				'customer_uid'            => $iamport->getCustomerUID(),
				'product_id'              => $product->ID(),
				'product_title'           => $product->title(),
				'product_price'           => $product->first_price(),
				'product_period_interval' => $product_period_interval,
				'product_period_from'     => $product_period_from,
				'product_period_to'       => $product->next_subscription_datetime($product_period_from, 'Ymd'),
				'pay_success_url'         => $pay_success_url,
				'm_redirect_url'          => $m_redirect_url,
				'app_scheme'              => apply_filters('cosmosfarm_members_subscription_pay_app_scheme', '', $product),
			);
			wp_localize_script("cosmosfarm-members-{$option->skin}", 'cosmosfarm_members_subscription_checkout_settings', $settings);
			
			do_action('cosmosfarm_members_skin_subscription_checkout', $this, $product);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function subscription_checkout_field_template($field=array(), $field2=array(), $field3=array()){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		$user = wp_get_current_user();
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/subscription-checkout-fields.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/subscription-checkout-fields.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-checkout-fields.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_checkout_fields', $file_path, $field);
		
		if(file_exists($file_path) && isset($field['type']) && $field['type']){
			$field_type = $field['type'];
			
			ob_start();
			include $file_path;
			do_action('cosmosfarm_members_skin_subscription_checkout_field_template', $field_type, $field, $field2, $field3);
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
}
?>