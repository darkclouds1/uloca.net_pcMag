<?php
/**
 * Cosmosfarm_Members_Controller
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2018 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members_Controller {

	public function __construct(){
		add_action('admin_post_cosmosfarm_members_setting_save', array($this, 'setting_save'));
		add_action('admin_post_cosmosfarm_members_service_save', array($this, 'policy_service_save'));
		add_action('admin_post_cosmosfarm_members_privacy_save', array($this, 'policy_privacy_save'));
		add_action('admin_post_cosmosfarm_members_certification_save', array($this, 'certification_save'));
		add_action('admin_post_cosmosfarm_members_verify_email_save', array($this, 'verify_email_save'));
		add_action('admin_post_cosmosfarm_members_change_role_save', array($this, 'change_role_save'));
		add_action('admin_post_cosmosfarm_members_security_save', array($this, 'security_save'));
		add_action('admin_post_cosmosfarm_members_exists_check_save', array($this, 'exists_check_save'));
		add_action('admin_post_cosmosfarm_members_sms_setting_save', array($this, 'sms_setting_save'));
		add_action('admin_post_cosmosfarm_members_sms_send', array($this, 'sms_send'));
		add_action('admin_post_cosmosfarm_members_communication_save', array($this, 'communication_save'));
		add_action('admin_post_cosmosfarm_members_subscription_save', array($this, 'subscription_save'));
		add_action('admin_post_cosmosfarm_members_product_save', array($this, 'product_save'));
		add_action('admin_post_cosmosfarm_members_order_save', array($this, 'order_save'));
		add_action('admin_post_cosmosfarm_members_order_cancel', array($this, 'order_cancel'));
		add_action('admin_post_cosmosfarm_members_mailchimp_save', array($this, 'mailchimp_save'));
		
		$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
		switch($action){
			case 'cosmosfarm_members_social_login': $this->social_login(); break;
			case 'cosmosfarm_members_social_login_callback_naver': $this->social_login_callback('naver'); break;
			case 'cosmosfarm_members_social_login_callback_facebook': $this->social_login_callback('facebook'); break;
			case 'cosmosfarm_members_social_login_callback_kakao': $this->social_login_callback('kakao'); break;
			case 'cosmosfarm_members_social_login_callback_google': $this->social_login_callback('google'); break;
			case 'cosmosfarm_members_social_login_callback_twitter': $this->social_login_callback('twitter'); break;
			case 'cosmosfarm_members_social_login_callback_instagram': $this->social_login_callback('instagram'); break;
			case 'cosmosfarm_members_verify_email_confirm': $this->verify_email_confirm(); break;
			case 'cosmosfarm_members_delete_account': $this->delete_account(); break;
			case 'cosmosfarm_members_login_timeout': $this->login_timeout(); break;
			case 'cosmosfarm_members_certification_confirm': $this->certification_confirm(); break;
			case 'cosmosfarm_members_subscription_register_card': $this->subscription_register_card(); break;
			case 'cosmosfarm_members_pre_subscription_request_pay': $this->pre_subscription_request_pay(); break;
			case 'cosmosfarm_members_subscription_request_pay': $this->subscription_request_pay(); break;
			case 'cosmosfarm_members_subscription_request_pay_complete': $this->subscription_request_pay_complete(); break;
			case 'cosmosfarm_members_subscription_request_pay_mobile': $this->subscription_request_pay_mobile(); break;
			case 'cosmosfarm_members_subscription_update': $this->subscription_update(); break;
			case 'cosmosfarm_members_exists_check': $this->exists_check(); break;
			case 'cosmosfarm_members_notifications_read': $this->notifications_read(); break;
			case 'cosmosfarm_members_notifications_unread': $this->notifications_unread(); break;
			case 'cosmosfarm_members_notifications_delete': $this->notifications_delete(); break;
			case 'cosmosfarm_members_notifications_subnotify_update': $this->notifications_subnotify_update(); break;
			case 'cosmosfarm_members_messages_read': $this->messages_read(); break;
			case 'cosmosfarm_members_messages_unread': $this->messages_unread(); break;
			case 'cosmosfarm_members_messages_delete': $this->messages_delete(); break;
			case 'cosmosfarm_members_messages_subnotify_update': $this->messages_subnotify_update(); break;
			case 'cosmosfarm_members_messages_send': $this->messages_send(); break;
		}
		
		$code = isset($_GET['code'])?$_GET['code']:'';
		$state = isset($_GET['state'])?$_GET['state']:'';
		if(!$action && $code && $state){
			$this->social_login_callback('line');
		}
	}
	
	public function setting_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-setting-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-setting-save-nonce'], 'cosmosfarm-members-setting-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$option_name = 'cosmosfarm_menu_add_login';
			$new_value = trim($_POST[$option_name]);
			if(!$new_value){
				delete_option($option_name);
			}
			else{
				if(get_option($option_name) !== false) update_option($option_name, $new_value, 'yes');
				else add_option($option_name, $new_value, '', 'yes');
			}

			$option_name = 'cosmosfarm_login_menus';
			$new_value = isset($_POST[$option_name])?$_POST[$option_name]:'';
			if(!$new_value){
				delete_option($option_name);
			}
			else{
				if(get_option($option_name) !== false) update_option($option_name, $new_value, 'yes');
				else add_option($option_name, $new_value, '', 'yes');
			}

			$option = get_cosmosfarm_members_option();
			$option->update('cosmosfarm_members_social_login_active', array());
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function policy_service_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-service-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-service-save-nonce'], 'cosmosfarm-members-service-save')){
			$option_name = 'cosmosfarm_members_policy_service';
			$new_value = trim($_POST[$option_name]);
			if(!$new_value){
				delete_option($option_name);
			}
			else{
				if(get_option($option_name) !== false) update_option($option_name, $new_value, 'no');
				else add_option($option_name, $new_value, '', 'no');
			}
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function policy_privacy_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-privacy-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-privacy-save-nonce'], 'cosmosfarm-members-privacy-save')){
			$option_name = 'cosmosfarm_members_policy_privacy';
			$new_value = trim($_POST[$option_name]);
			if(!$new_value){
				delete_option($option_name);
			}
			else{
				if(get_option($option_name) !== false) update_option($option_name, $new_value, 'no');
				else add_option($option_name, $new_value, '', 'no');
			}
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function certification_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-certification-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-certification-save-nonce'], 'cosmosfarm-members-certification-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
				
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}

	public function verify_email_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-verify-email-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-verify-email-save-nonce'], 'cosmosfarm-members-verify-email-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function change_role_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-change-role-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-change-role-save-nonce'], 'cosmosfarm-members-change-role-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function security_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-security-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-security-save-nonce'], 'cosmosfarm-members-security-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function exists_check_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-exists-check-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-exists-check-save-nonce'], 'cosmosfarm-members-exists-check-save')){
			$option = get_cosmosfarm_members_option();
			$option->update('cosmosfarm_members_exists_check', array());
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function sms_setting_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-sms-setting-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-sms-setting-save-nonce'], 'cosmosfarm-members-sms-setting-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function sms_send(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-sms-send-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-sms-send-nonce'], 'cosmosfarm-members-sms-send')){
			
			header('Content-Type: text/html; charset=UTF-8');
			
			$_POST = stripslashes_deep($_POST);
			
			$phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
			$content = isset($_POST['content']) ? sanitize_textarea_field($_POST['content']) : '';
			
			$sms = get_cosmosfarm_members_sms();
			$result = $sms->send($phone, $content);
			
			if($result['result'] == 'success'){
				$redirect_url = wp_get_referer();
				
				echo "<script>alert('{$result['message']}')</script>";
				echo "<script>window.location.href='{$redirect_url}';</script>";
			}
			else{
				echo "<script>alert('{$result['message']}')</script>";
				echo "<script>window.history.back();</script>";
			}
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function communication_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-communication-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-communication-save-nonce'], 'cosmosfarm-members-communication-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$option = get_cosmosfarm_members_option();
			$option->update('cosmosfarm_members_notifications_page_id');
			$option->update('cosmosfarm_members_notifications_kboard');
			$option->update('cosmosfarm_members_notifications_kboard_comments');
			$option->update('cosmosfarm_members_notifications_subnotify_email', '');
			$option->update('cosmosfarm_members_notifications_subnotify_sms', '');
			$option->update('cosmosfarm_members_notifications_subnotify_email_title');
			$option->update('cosmosfarm_members_notifications_subnotify_email_content');
			$option->update('cosmosfarm_members_notifications_subnotify_sms_message');
			$option->update('cosmosfarm_members_messages_page_id');
			$option->update('cosmosfarm_members_messages_subnotify_email', '');
			$option->update('cosmosfarm_members_messages_subnotify_sms', '');
			$option->update('cosmosfarm_members_messages_subnotify_email_title');
			$option->update('cosmosfarm_members_messages_subnotify_email_content');
			$option->update('cosmosfarm_members_messages_subnotify_sms_message');
			$option->update('cosmosfarm_members_subnotify_sms_field');
			$option->update('cosmosfarm_members_users_page_id');
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function subscription_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-subscription-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-subscription-save-nonce'], 'cosmosfarm-members-subscription-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function product_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-product-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-product-save-nonce'], 'cosmosfarm-members-product-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : '';
			$product_title = isset($_POST['product_title']) ? sanitize_text_field($_POST['product_title']) : '';
			$product_content = isset($_POST['product_content']) ? $_POST['product_content'] : '';
			$product_price = isset($_POST['product_price']) ? intval($_POST['product_price']) : '';
			$product_first_price = isset($_POST['product_first_price']) ? intval($_POST['product_first_price']) : '';
			$product_pay_count_limit = isset($_POST['product_pay_count_limit']) ? intval($_POST['product_pay_count_limit']) : '';
			$product_subscription_type = isset($_POST['product_subscription_type']) ? sanitize_text_field($_POST['product_subscription_type']) : '';
			$product_subscription_active = isset($_POST['product_subscription_active']) ? sanitize_text_field($_POST['product_subscription_active']) : '';
			$product_subscription_first_free = isset($_POST['product_subscription_first_free']) ? sanitize_text_field($_POST['product_subscription_first_free']) : '';
			$product_subscription_again_price_type = isset($_POST['product_subscription_again_price_type']) ? sanitize_text_field($_POST['product_subscription_again_price_type']) : '';
			$product_subscription_role = isset($_POST['product_subscription_role']) ? sanitize_text_field($_POST['product_subscription_role']) : '';
			$product_subscription_multiple_pay = isset($_POST['product_subscription_multiple_pay']) ? sanitize_text_field($_POST['product_subscription_multiple_pay']) : '';
			
			$fields = array();
			if(isset($_POST['product_field'])){
				foreach($_POST['product_field']['type'] as $key=>$type){
					$field_row = array();
					$field_row['type'] = sanitize_key($_POST['product_field']['type'][$key]);
					$field_row['data'] = sanitize_textarea_field($_POST['product_field']['data'][$key]);
					$field_row['label'] = sanitize_text_field($_POST['product_field']['label'][$key]);
					$field_row['meta_key'] = sanitize_key($_POST['product_field']['meta_key'][$key]);
					$field_row['user_meta_key'] = sanitize_key($_POST['product_field']['user_meta_key'][$key]);
					$field_row['required'] = sanitize_text_field($_POST['product_field']['required'][$key]);
					$field_row['order_view'] = sanitize_text_field($_POST['product_field']['order_view'][$key]);
					$fields[] = $field_row;
				}
			}
			
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			if(!$product->ID()){
				$product->create(get_current_user_id(), array('title'=>$product_title, 'content'=>$product_content));
			}
			else{
				$product->update(array('title'=>$product_title, 'content'=>$product_content));
			}
			
			if($product->ID()){
				$product->set_price($product_price);
				$product->set_first_price($product_first_price);
				$product->set_pay_count_limit($product_pay_count_limit);
				$product->set_subscription_type($product_subscription_type);
				$product->set_subscription_active($product_subscription_active);
				$product->set_subscription_first_free($product_subscription_first_free);
				$product->set_subscription_again_price_type($product_subscription_again_price_type);
				$product->set_subscription_role($product_subscription_role);
				
				if($product_subscription_role) $product_subscription_multiple_pay = '';
				$product->set_subscription_multiple_pay($product_subscription_multiple_pay);
				
				$product->set_fields($fields);
			}
			
			wp_redirect(admin_url('admin.php?page=cosmosfarm_subscription_product&product_id=' . $product->ID()));
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function order_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-order-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-order-save-nonce'], 'cosmosfarm-members-order-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : '';
			$order_content = isset($_POST['order_content']) ? $_POST['order_content'] : '';
			$order_status = isset($_POST['order_status']) ? sanitize_text_field($_POST['order_status']) : '';
			$order_end_year = isset($_POST['order_end_year']) ? sanitize_text_field($_POST['order_end_year']) : '';
			$order_end_month = isset($_POST['order_end_month']) ? sanitize_text_field($_POST['order_end_month']) : '';
			$order_end_day = isset($_POST['order_end_day']) ? sanitize_text_field($_POST['order_end_day']) : '';
			$order_end_hour = isset($_POST['order_end_hour']) ? sanitize_text_field($_POST['order_end_hour']) : '';
			$order_end_minute = isset($_POST['order_end_minute']) ? sanitize_text_field($_POST['order_end_minute']) : '';
			$order_subscription_active = isset($_POST['order_subscription_active']) ? sanitize_text_field($_POST['order_subscription_active']) : '';
			
			$order = new Cosmosfarm_Members_Subscription_Order($order_id);
			
			$order->set_subscription_active($order_subscription_active);
			
			if($order_status == 'paid'){
				$order->set_status_paid();
			}
			else if($order_status == 'cancelled'){
				$order->set_status_cancelled();
			}
			
			if($order->end_datetime() && $order_end_year && $order_end_month && $order_end_day && $order_end_hour && $order_end_minute){
				$order_end_second = date('s', strtotime($order->end_datetime()));
				$next_datetime = date('YmdHis', mktime($order_end_hour, $order_end_minute, $order_end_second, $order_end_month, $order_end_day, $order_end_year));
				$order->set_end_datetime($next_datetime);
				cosmosfarm_members_subscription_again();
			}
			
			$product = new Cosmosfarm_Members_Subscription_Product($order->product_id());
			$order->update_fields($product->fields(), $_POST);
			
			wp_redirect(admin_url('admin.php?page=cosmosfarm_subscription_order&order_id=' . $order->ID()));
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function order_cancel(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(current_user_can('activate_plugins')){
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : '';
			$order = new Cosmosfarm_Members_Subscription_Order($order_id);
			$product = new Cosmosfarm_Members_Subscription_Product($order->product_id());
			
			if($order->imp_uid){
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Iamport.class.php';
				$api = new Cosmosfarm_Members_API_Iamport();
				$cencel_result = $api->cencel($order->imp_uid);
				
				if($cencel_result->status == 'cancelled'){
					$order->set_status_cancelled();
					$order->execute_expiry_action();
					
					cosmosfarm_members_send_notification(array(
							'to_user_id' => $order->user()->ID,
							'content'    => sprintf('<strong>%s</strong> 결제가 취소되었습니다.', $order->title()),
							'meta_input' => array(
									'url'      => $cencel_result->receipt_url,
									'url_name' =>'영수증',
							),
					));
					
					$result = array('result'=>'success', 'message'=>__('This payment has been canceled.', 'cosmosfarm-members'));
				}
				else{
					$result = array('result'=>'error', 'message'=>$cencel_result->error_message);
				}
			}
		}
		
		$result = apply_filters('cosmosfarm_members_order_cancel_result', $result);
		wp_send_json($result);
	}
	
	public function mailchimp_save(){
		if(current_user_can('activate_plugins') && isset($_POST['cosmosfarm-members-mailchimp-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-mailchimp-save-nonce'], 'cosmosfarm-members-mailchimp-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	private function social_login(){
		$channel = isset($_GET['channel'])?$_GET['channel']:'';
		if(is_object($api = $this->get_social_api($channel))){
			
			$redirect_to = isset($_GET['redirect_to']) ? esc_url_raw(trim($_GET['redirect_to'])) : '';
			if($redirect_to){
				$_SESSION['cosmosfarm_members_social_login_redirect_to'] = $redirect_to;
			}
			
			wp_redirect($api->get_request_url());
			exit;
		}
		wp_redirect(home_url());
		exit;
	}
	
	private function social_login_callback($channel){
		if(is_object($api = $this->get_social_api($channel))){
			$api->init_access_token();
			$profile = $api->get_profile();
			
			if($profile->id){
				if($channel == 'naver'){
					if($profile->naver_enc_id){
						$social_id = "{$channel}@{$profile->naver_enc_id}";
						$user = get_users(array('meta_key'=>'cosmosfarm_members_social_id','meta_value'=>$social_id, 'number'=>1, 'count_total'=>false));
						$user = reset($user);
					}
					
					if(!isset($user->ID) || !$user->ID){
						$social_id = "{$channel}@{$profile->id}";
						$user = get_users(array('meta_key'=>'cosmosfarm_members_social_id','meta_value'=>$social_id, 'number'=>1, 'count_total'=>false));
						$user = reset($user);
					}
				}
				else{
					$social_id = "{$channel}@{$profile->id}";
					$user = get_users(array('meta_key'=>'cosmosfarm_members_social_id','meta_value'=>$social_id, 'number'=>1, 'count_total'=>false));
					$user = reset($user);
				}
				
				$random_password = wp_generate_password(128, true, true);
				
				if(!isset($user->ID) || !$user->ID){
					
					$profile->user_login = sanitize_user($profile->user_login);
					$profile->email = sanitize_email($profile->email);
					$profile->nickname = sanitize_text_field($profile->nickname);
					$profile->picture = sanitize_text_field($profile->picture);
					$profile->url = sanitize_text_field($profile->url);
					
					if(!$profile->user_login || username_exists($profile->user_login)){
						$profile->user_login = "{$channel}_" . uniqid();
					}
					
					if(!$profile->email || email_exists($profile->email)){
						// 무작위 이메일 주소로 회원 등록후, 등록된 이메일을 지우기 위해서 $update_email에 빈 값을 등록해준다.
						$profile->email = "{$channel}_" . uniqid() . '@example.com';
						$update_email = '';
					}
					else{
						$update_email = $profile->email;
					}
					
					include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/Cosmosfarm_Members_Social_Login.class.php';
					$social_login = new Cosmosfarm_Members_Social_Login();
					$social_login->social_id = $social_id;
					$social_login->channel = $channel;
					$social_login->picture = $profile->picture;
					$social_login->user_url = $profile->url;
					$social_login->user_email = $update_email;
					$social_login->display_name = $profile->nickname;
					$social_login->nickname = $profile->nickname;
					$social_login->raw_data = $profile->raw_data;
					
					add_action('user_register', array($social_login, 'user_register'), 10, 1);
					
					$user_id = wp_create_user($profile->user_login, $random_password, $profile->email);
					
					$user = new WP_User($user_id);
				}
				else{
					wp_set_password($random_password, $user->ID);
				}
				
				add_user_meta($user->ID, 'cosmosfarm_members_social_picture', $profile->picture);
				
				wp_set_current_user($user->ID, $user->user_login);
				wp_set_auth_cookie($user->ID, false);
				do_action('wp_login', $user->user_login, $user);
				
				$option = get_cosmosfarm_members_option();
				if($option->login_redirect_page == 'main'){
					$redirect_to = home_url();
					wp_redirect($redirect_to);
					exit;
				}
				else if($option->login_redirect_page == 'url' && $option->login_redirect_url){
					$redirect_to = $option->login_redirect_url;
					wp_redirect($redirect_to);
					exit;
				}
				else if(isset($_SESSION['cosmosfarm_members_social_login_redirect_to']) && $_SESSION['cosmosfarm_members_social_login_redirect_to']){
					$redirect_to = $_SESSION['cosmosfarm_members_social_login_redirect_to'];
					$_SESSION['cosmosfarm_members_social_login_redirect_to'] = '';
					unset($_SESSION['cosmosfarm_members_social_login_redirect_to']);
					wp_redirect($redirect_to);
					exit;
				}
			}
		}
		wp_redirect(home_url());
		exit;
	}
	
	private function get_social_api($channel){
		switch($channel){
			
			case 'naver':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Naver.class.php';
				$api = new Cosmosfarm_Members_API_Naver();
				break;
				
			case 'facebook':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Facebook.class.php';
				$api = new Cosmosfarm_Members_API_Facebook();
				break;
				
			case 'kakao':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Kakao.class.php';
				$api = new Cosmosfarm_Members_API_Kakao();
				break;
				
			case 'google':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Google.class.php';
				$api = new Cosmosfarm_Members_API_Google();
				break;
				
			case 'twitter':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Twitter.class.php';
				$api = new Cosmosfarm_Members_API_Twitter();
				break;
				
			case 'instagram':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Instagram.class.php';
				$api = new Cosmosfarm_Members_API_Instagram();
				break;
				
			case 'line':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Line.class.php';
				$api = new Cosmosfarm_Members_API_Line();
				break;
				
			default: $api = false;
		}
		return $api;
	}
	
	private function verify_email_confirm(){
		$verify_code = isset($_GET['verify_code'])?$_GET['verify_code']:'';
		
		if($verify_code){
			$users = get_users(array('meta_key'=>'wait_verify_email', 'meta_value'=>$verify_code));
			
			foreach($users as $user){
				delete_user_meta($user->ID, 'wait_verify_email');
				update_user_meta($user->ID, 'verify_email', '1');
				
				$option = get_cosmosfarm_members_option();
				if($option->confirmed_email){
					cosmosfarm_members_send_confirmed_email($user);
				}
				
				wp_redirect(add_query_arg(array('verify_email_confirm'=>'1'), wp_login_url()));
				exit;
			}
		}
		
		if(is_user_logged_in()){
			wp_redirect(home_url());
		}
		else{
			wp_redirect(wp_login_url());
		}
		exit;
	}
	
	private function delete_account(){
		if(current_user_can('activate_plugins')){
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
		
		if(is_user_logged_in() && isset($_GET['cosmosfarm_members_delete_account_nonce']) || wp_verify_nonce($_GET['cosmosfarm_members_delete_account_nonce'], 'cosmosfarm_members_delete_account')){
			$current_user = wp_get_current_user();
			
			if($current_user->ID){
				header('Content-Type: text/html; charset=UTF-8');
				
				do_action('cosmosfarm_members_delete_account');
				
				if(is_multisite()){
					if(!function_exists('wpmu_delete_user')){
						include_once ABSPATH . '/wp-admin/includes/ms.php';
					}
					
					if(wpmu_delete_user($current_user->ID)){
						wp_clear_auth_cookie();
					}
				}
				else{
					if(!function_exists('wp_delete_user')){
						include_once ABSPATH . '/wp-admin/includes/user.php';
					}
					
					if(wp_delete_user($current_user->ID)){
						wp_clear_auth_cookie();
					}
				}
				
				$message = __('Your account has been deleted. Thank you.', 'cosmosfarm-members');
				$home_url = home_url();
				
				echo "<script>alert('{$message}');</script>";
				echo "<script>window.location.href='{$home_url}';</script>";
				exit;
			}
		}
		
		if(is_user_logged_in()){
			wp_redirect(home_url());
		}
		else{
			wp_redirect(wp_login_url());
		}
		exit;
	}
	
	private function login_timeout(){
		if(is_user_logged_in()){
			$option = get_cosmosfarm_members_option();
			$use_login_timeout = apply_filters('cosmosfarm_members_use_login_timeout', $option->use_login_timeout, $option);
			if($use_login_timeout){
				
				wp_logout();
				
				if($use_login_timeout == '1'){
					wp_redirect(add_query_arg(array('login_timeout'=>'1'), wp_login_url(wp_get_referer())));
					exit;
				}
				else if($use_login_timeout == '2'){
					wp_redirect(wp_get_referer());
					exit;
				}
			}
		}
		
		if(is_user_logged_in()){
			wp_redirect(home_url());
		}
		else{
			wp_redirect(wp_login_url());
		}
		exit;
	}
	
	private function certification_confirm(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$imp_uid = isset($_POST['imp_uid']) ? sanitize_text_field($_POST['imp_uid']) : '';
		if($imp_uid){
			include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Iamport.class.php';
			$api = new Cosmosfarm_Members_API_Iamport();
			$certification = $api->getCertification($imp_uid);
			wp_send_json($certification);
		}
		exit;
	}
	
	private function subscription_register_card(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$card_number = isset($_POST['cosmosfarm_members_subscription_checkout_card_number']) ? sanitize_text_field($_POST['cosmosfarm_members_subscription_checkout_card_number']) : '';
			$expiry = isset($_POST['cosmosfarm_members_subscription_checkout_expiry']) ? sanitize_text_field($_POST['cosmosfarm_members_subscription_checkout_expiry']) : '';
			$birth = isset($_POST['cosmosfarm_members_subscription_checkout_birth']) ? sanitize_text_field($_POST['cosmosfarm_members_subscription_checkout_birth']) : '';
			$pwd_2digit = isset($_POST['cosmosfarm_members_subscription_checkout_pwd_2digit']) ? sanitize_text_field($_POST['cosmosfarm_members_subscription_checkout_pwd_2digit']) : '';
			
			if($card_number && $expiry && $birth && $pwd_2digit){
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Iamport.class.php';
				$api = new Cosmosfarm_Members_API_Iamport();
				$result = $api->registerCard($card_number, $expiry, $birth, $pwd_2digit);
				$result->result = 'success';
			}
		}
		
		wp_send_json($result);
	}
	
	private function pre_subscription_request_pay(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		/*
		 * 보안상 민감한 정보는 삭제한다.
		 */
		unset($_POST['security']);
		unset($_POST['cosmosfarm_members_subscription_checkout_card_number']);
		unset($_POST['cosmosfarm_members_subscription_checkout_expiry_month']);
		unset($_POST['cosmosfarm_members_subscription_checkout_expiry_year']);
		unset($_POST['cosmosfarm_members_subscription_checkout_birth']);
		unset($_POST['cosmosfarm_members_subscription_checkout_pwd_2digit']);
		
		$_POST = stripslashes_deep($_POST);
		
		$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : '';
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			
			if($product->ID()){
				do_action('cosmosfarm_members_pre_subscription_request_pay', $product);
				
				$result = array('result'=>'success', 'message'=>'');
			}
		}
		
		$result = apply_filters('cosmosfarm_members_pre_subscription_request_pay_result', $result);
		wp_send_json($result);
	}

	/**
	 * 일반결제
	 */
	private function subscription_request_pay_complete(){
		header('Content-Type: text/html; charset=UTF-8');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		$pay_success_url = esc_url_raw(home_url());
		
		$product_id = isset($_REQUEST['product_id']) ? intval($_REQUEST['product_id']) : '';
		$imp_uid = isset($_REQUEST['imp_uid']) ? sanitize_text_field($_REQUEST['imp_uid']) : '';
		$display = isset($_REQUEST['display']) ? sanitize_text_field($_REQUEST['display']) : '';
		
		if($product_id && $imp_uid){
			check_ajax_referer("cosmosfarm-members-subscription-checkout-{$product_id}", 'checkout_nonce');
			
			$user = wp_get_current_user();
			$order = new Cosmosfarm_Members_Subscription_Order();
			
			include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Iamport.class.php';
			$api = new Cosmosfarm_Members_API_Iamport();
			$payment = (array) $api->getPayment($imp_uid);
			
			$amount = isset($payment['amount']) ? intval($payment['amount']) : 0;
			$custom_data = isset($payment['custom_data']) ? (array) $payment['custom_data'] : array();
			$pay_success_url = isset($custom_data['pay_success_url']) ? esc_url_raw($custom_data['pay_success_url']) : $pay_success_url;
			$buyer_name = isset($payment['buyer_name']) ? sanitize_text_field($payment['buyer_name']) : '';
			$buyer_email = isset($payment['buyer_email']) ? sanitize_text_field($payment['buyer_email']) : '';
			$buyer_tel = isset($payment['buyer_tel']) ? sanitize_text_field($payment['buyer_tel']) : '';
			$buyer_addr = isset($payment['buyer_addr']) ? sanitize_text_field($payment['buyer_addr']) : '';
			$buyer_postcode = isset($payment['buyer_postcode']) ? sanitize_text_field($payment['buyer_postcode']) : '';
			
			if(!is_user_logged_in()){
				$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
			}
			else if(isset($payment['error_message']) && $payment['error_message']){
				$result = array('result'=>'error', 'message'=>$payment['error_message']);
			}
			else if(isset($payment['status']) && $payment['status'] == 'paid'){
				$product = new Cosmosfarm_Members_Subscription_Product($product_id);
				
				if($product->ID() && $amount === $product->price()){
					$meta_input = array(
						'name'           => $product->title(),
						'buyer_name'     => $buyer_name,
						'buyer_email'    => $buyer_email,
						'buyer_tel'      => $buyer_tel,
						'buyer_addr'     => $buyer_addr,
						'buyer_postcode' => $buyer_postcode,
						'imp_uid'        => $payment['imp_uid'],
						'merchant_uid'   => $payment['merchant_uid'],
						'receipt_url'    => $payment['receipt_url'],
					);
					
					$order->create($user->ID, array('title'=>$product->title(), 'meta_input'=>$meta_input));
					
					if($order->ID()){
						$order->set_sequence_id(uniqid());
						$order->set_status_paid();
						$order->set_product_id($product->ID());
						$order->set_price($product->price());
						$order->set_subscription_type($product->subscription_type());
						$order->set_subscription_active('');
						$order->set_pay_count('1');
						
						if($product->subscription_role()){
							$order->set_subscription_role($product->subscription_role());
							$order->set_subscription_prev_role($user->roles[0]);
							
							if(!is_super_admin($user->ID)){
								$user->remove_role($user->roles[0]);
								$user->add_role($product->subscription_role());
							}
						}
						
						$order->set_subscription_next('success');
						
						$next_datetime = $product->next_subscription_datetime();
						if($next_datetime){
							$order->set_subscription_next('wait');
							$order->set_start_datetime();
							$order->set_end_datetime($next_datetime);
						}
						
						$order->update(array('content'=>sprintf('<strong>%s</strong> 결제되었습니다.', $product->title())));
						$order->update_fields($product->fields(), $custom_data);
						
						do_action('cosmosfarm_members_subscription_request_pay', $order, $product);
						
						cosmosfarm_members_send_notification(array(
							'to_user_id' => $user->ID,
							'content'    => sprintf('<strong>%s</strong> 결제되었습니다.', $product->title()),
							'meta_input' => array(
								'url'      => $payment['receipt_url'],
								'url_name' =>'영수증',
							),
						));
						$result = array('result'=>'success', 'message'=>'결제되었습니다. 고맙습니다.');
					}
				}
			}
		}
		
		if(!$order->ID()){
			$result = array('result'=>'error', 'message'=>'결제에 실패했습니다.');
		}
		
		$result['pay_success_url'] = $pay_success_url;
		$result = apply_filters('cosmosfarm_members_subscription_request_pay_result', $result);
		
		if($display == 'pc'){
			wp_send_json($result);
		}
		else{
			$message = esc_js($result['message']);
			$pay_success_url = esc_url_raw($result['pay_success_url']);
			echo "<script>alert('{$message}');window.location.href='{$pay_success_url}';</script>";
			exit;
		}
	}
	
	/**
	 * 빌링결제
	 */
	private function subscription_request_pay(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		/*
		 * 보안상 민감한 정보는 삭제한다.
		 */
		unset($_POST['security']);
		unset($_POST['cosmosfarm_members_subscription_checkout_card_number']);
		unset($_POST['cosmosfarm_members_subscription_checkout_expiry_month']);
		unset($_POST['cosmosfarm_members_subscription_checkout_expiry_year']);
		unset($_POST['cosmosfarm_members_subscription_checkout_birth']);
		unset($_POST['cosmosfarm_members_subscription_checkout_pwd_2digit']);
		
		$_POST = stripslashes_deep($_POST);
		
		$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : '';
		$buyer_name = isset($_POST['buyer_name']) ? sanitize_text_field($_POST['buyer_name']) : '';
		$buyer_email = isset($_POST['buyer_email']) ? sanitize_text_field($_POST['buyer_email']) : '';
		$buyer_tel = isset($_POST['buyer_tel']) ? sanitize_text_field($_POST['buyer_tel']) : '';
		$addr1 = isset($_POST['addr1']) ? sanitize_text_field($_POST['addr1']) : '';
		$addr2 = isset($_POST['addr2']) ? sanitize_text_field($_POST['addr2']) : '';
		$zip = isset($_POST['zip']) ? sanitize_text_field($_POST['zip']) : '';
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			
			if($product->ID()){
				do_action('cosmosfarm_members_pre_subscription_request_pay', $product);
				
				$meta_input = array(
					'name'           => $product->title(),
					'buyer_name'     => $buyer_name,
					'buyer_email'    => $buyer_email,
					'buyer_tel'      => $buyer_tel,
					'buyer_addr'     => (($addr1 && $addr2) ? trim("{$addr1} {$addr2}") : ''),
					'buyer_postcode' => $zip
				);
				
				$result = $this->subscription_request_pay_order($product, $meta_input, $_POST);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_subscription_request_pay_result', $result);
		wp_send_json($result);
	}
	
	/**
	 * 빌링결제 모바일
	 */
	private function subscription_request_pay_mobile(){
		header('Content-Type: text/html; charset=UTF-8');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		$pay_success_url = esc_url_raw(home_url());
		
		$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : '';
		$imp_uid = isset($_GET['imp_uid']) ? sanitize_text_field($_GET['imp_uid']) : '';
		$imp_success = isset($_GET['imp_success']) ? sanitize_text_field($_GET['imp_success']) : '';
		
		if($product_id && $imp_uid && $imp_success == 'true'){
			check_ajax_referer("cosmosfarm-members-subscription-checkout-{$product_id}", 'checkout_nonce');
			
			include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Iamport.class.php';
			$api = new Cosmosfarm_Members_API_Iamport();
			$payment = (array) $api->getPayment($imp_uid);
			
			$amount = isset($payment['amount']) ? intval($payment['amount']) : 0;
			$custom_data = isset($payment['custom_data']) ? (array) $payment['custom_data'] : array();
			$pay_success_url = isset($custom_data['pay_success_url']) ? esc_url_raw($custom_data['pay_success_url']) : $pay_success_url;
			$buyer_name = isset($payment['buyer_name']) ? sanitize_text_field($payment['buyer_name']) : '';
			$buyer_email = isset($payment['buyer_email']) ? sanitize_text_field($payment['buyer_email']) : '';
			$buyer_tel = isset($payment['buyer_tel']) ? sanitize_text_field($payment['buyer_tel']) : '';
			$buyer_addr = isset($payment['buyer_addr']) ? sanitize_text_field($payment['buyer_addr']) : '';
			$buyer_postcode = isset($payment['buyer_postcode']) ? sanitize_text_field($payment['buyer_postcode']) : '';
			
			if(!is_user_logged_in()){
				$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
			}
			else if(isset($payment['error_message']) && $payment['error_message']){
				$result = array('result'=>'error', 'message'=>$payment['error_message']);
			}
			else if(isset($payment['status']) && $payment['status'] == 'paid'){
				$product = new Cosmosfarm_Members_Subscription_Product($product_id);
				
				if($product->ID() && $amount === $product->first_price()){
					$meta_input = array(
						'name'           => $product->title(),
						'buyer_name'     => $buyer_name,
						'buyer_email'    => $buyer_email,
						'buyer_tel'      => $buyer_tel,
						'buyer_addr'     => $buyer_addr,
						'buyer_postcode' => $buyer_postcode
					);
					
					$result = $this->subscription_request_pay_order($product, $meta_input, $custom_data);
				}
			}
		}
		
		$result['pay_success_url'] = $pay_success_url;
		$result = apply_filters('cosmosfarm_members_subscription_request_pay_result', $result);
		
		$message = esc_js($result['message']);
		$pay_success_url = esc_url_raw($result['pay_success_url']);
		echo "<script>alert('{$message}');window.location.href='{$pay_success_url}';</script>";
		exit;
	}
	
	/**
	 * 빌링결제 주문정보 생성
	 * @param Cosmosfarm_Members_Subscription_Product $product
	 * @param array $meta_input
	 * @param array $args
	 * @return string[]
	 */
	private function subscription_request_pay_order($product, $meta_input, $args){
		if($product->ID()){
			$user = wp_get_current_user();
			$order = new Cosmosfarm_Members_Subscription_Order();
			
			// 주문이 저장되기 전에 첫 결제 무료 이용을 확인한다.
			$is_subscription_first_free = $product->is_subscription_first_free();
			
			$imp_uid = isset($_POST['imp_uid']) ? sanitize_text_field($_POST['imp_uid']) : '';
			
			include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Iamport.class.php';
			$api = new Cosmosfarm_Members_API_Iamport();
			
			$option = get_cosmosfarm_members_option();
			
			if($imp_uid && in_array($option->subscription_pg, array('kakao', 'jtnet'))){
				$subscribe_result = $api->getPayment($imp_uid);
			}
			else{
				$subscribe_result = $api->subscribe($user->ID, $product->first_price(), $meta_input);
			}
			
			if($subscribe_result->status == 'paid'){
				$meta_input['imp_uid'] = $subscribe_result->imp_uid;
				$meta_input['merchant_uid'] = $subscribe_result->merchant_uid;
				$meta_input['receipt_url'] = $subscribe_result->receipt_url;
				
				$order->create($user->ID, array('title'=>$product->title(), 'meta_input'=>$meta_input));
				
				if($order->ID()){
					$order->set_sequence_id(uniqid());
					$order->set_status_paid();
					$order->set_product_id($product->ID());
					$order->set_price($product->price());
					$order->set_first_price($product->first_price());
					$order->set_pay_count_limit($product->pay_count_limit());
					$order->set_subscription_type($product->subscription_type());
					$order->set_subscription_active($product->subscription_active());
					$order->set_subscription_again_price_type($product->subscription_again_price_type());
					
					$pay_count = 1;
					$order->set_pay_count($pay_count);
					
					if($product->subscription_role()){
						$order->set_subscription_role($product->subscription_role());
						$order->set_subscription_prev_role($user->roles[0]);
						
						if(!is_super_admin($user->ID)){
							$user->remove_role($user->roles[0]);
							$user->add_role($product->subscription_role());
						}
					}
					
					$order->set_subscription_next('success');
					
					if($is_subscription_first_free){
						$next_datetime = $product->next_subscription_datetime_first_free();
						if($next_datetime){
							$order->set_subscription_next('wait');
							$order->set_start_datetime();
							$order->set_end_datetime($next_datetime);
							
							// 첫 결제 무료 이용기간이 있을 경우 결제 성공 후 취소한다.
							$cencel_result = $api->cencel($subscribe_result->imp_uid);
							if($cencel_result->status == 'cancelled'){
								$order->set_price(0);
							}
						}
					}
					else{
						$next_datetime = $product->next_subscription_datetime();
						if($next_datetime){
							$order->set_subscription_next('wait');
							$order->set_start_datetime();
							$order->set_end_datetime($next_datetime);
						}
					}
					
					$order->update(array('content'=>sprintf('<strong>%s</strong> 결제되었습니다. [정기결제 1회차]', $product->title())));
					$order->update_fields($product->fields(), $args);
					
					do_action('cosmosfarm_members_subscription_request_pay', $order, $product);
					
					cosmosfarm_members_send_notification(array(
						'to_user_id' => $user->ID,
						'content'    => sprintf('<strong>%s</strong> 결제되었습니다. [정기결제 1회차]', $product->title()),
						'meta_input' => array(
							'url'      => $subscribe_result->receipt_url,
							'url_name' =>'영수증',
						),
					));
					$result = array('result'=>'success', 'message'=>'결제되었습니다. 고맙습니다.');
				}
			}
		}
		
		if(!$order->ID()){
			if(isset($subscribe_result->error_message) && $subscribe_result->error_message){
				$result = array('result'=>'error', 'message'=>$subscribe_result->error_message);
			}
			else{
				$result = array('result'=>'error', 'message'=>'결제에 실패했습니다.');
			}
		}
		
		return $result;
	}
	
	/**
	 * 빌링결제 자동결제 실행
	 * @param int $order_id
	 */
	public function subscription_again($order_id){
		$order_id = intval($order_id);
		$old_order = new Cosmosfarm_Members_Subscription_Order($order_id);
		
		if($old_order->ID() && $old_order->product_id()){
			$old_order->set_subscription_next('expiry');
			
			$user = $old_order->user();
			$product = new Cosmosfarm_Members_Subscription_Product($old_order->product_id());
			$order = new Cosmosfarm_Members_Subscription_Order();
			
			$meta_input = array(
					'name'           => $product->title(),
					'buyer_name'     => $old_order->buyer_name,
					'buyer_email'    => $old_order->buyer_email,
					'buyer_tel'      => $old_order->buyer_tel,
					'buyer_addr'     => (($old_order->addr1 || $old_order->addr2) ? trim("{$old_order->addr1} {$old_order->addr2}") : ''),
					'buyer_postcode' => $old_order->zip
			);
			
			if(!$user->ID){
				do_action('cosmosfarm_members_subscription_again_failure', $old_order, $product);
			}
			else{
				$pay_count_limit = true;
				if($product->pay_count_limit()){
					if($old_order->pay_count() >= $product->pay_count_limit()){
						$pay_count_limit = false;
					}
				}
				
				if($product->ID() && $product->subscription_active() && $old_order->subscription_active() && $old_order->is_paid() && $pay_count_limit){
					
					do_action('cosmosfarm_members_pre_subscription_again', $product);
					
					if($product->subscription_again_price_type() == 'old_order'){
						$again_price = $old_order->price();
					}
					else{
						$again_price = $product->price();
					}
					
					include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Iamport.class.php';
					$api = new Cosmosfarm_Members_API_Iamport();
					$subscribe_result = $api->subscribe($user->ID, $again_price, $meta_input);
					
					if($subscribe_result->status == 'paid'){
						$meta_input['imp_uid'] = $subscribe_result->imp_uid;
						$meta_input['merchant_uid'] = $subscribe_result->merchant_uid;
						$meta_input['receipt_url'] = $subscribe_result->receipt_url;
						
						$order->create($user->ID, array('title'=>$product->title(), 'meta_input'=>$meta_input));
						
						if($order->ID()){
							$order->set_sequence_id($old_order->sequence_id());
							$order->set_status_paid();
							$order->set_product_id($product->ID());
							$order->set_price($again_price);
							$order->set_first_price($product->first_price());
							$order->set_pay_count_limit($product->pay_count_limit());
							$order->set_subscription_type($product->subscription_type());
							$order->set_subscription_active($product->subscription_active());
							$order->set_subscription_again_price_type($product->subscription_again_price_type());
							
							$pay_count = $old_order->pay_count() + 1;
							$order->set_pay_count($pay_count);
							
							if($product->subscription_role()){
								$order->set_subscription_role($product->subscription_role());
								
								$subscription_prev_role = $old_order->subscription_prev_role();
								$order->set_subscription_prev_role($subscription_prev_role ? $subscription_prev_role : $user->roles[0]);
								
								if(!is_super_admin($user->ID)){
									$user->remove_role($user->roles[0]);
									$user->add_role($product->subscription_role());
								}
							}
							else{
								if(!is_super_admin($user->ID)){
									$user->remove_role($user->roles[0]);
									$user->add_role(get_option('default_role'));
								}
							}
							
							$order->set_subscription_next('success');
							$next_datetime = $product->next_subscription_datetime();
							if($next_datetime){
								$order->set_subscription_next('wait');
								$order->set_start_datetime();
								$order->set_end_datetime($next_datetime);
							}
							
							$order->update(array('content'=>sprintf('<strong>%s</strong> 결제되었습니다. [정기결제 %d회차]', $product->title(), $pay_count)));
							$order->update_fields($product->fields(), get_post_meta($old_order->ID()));
							
							do_action('cosmosfarm_members_subscription_again_success', $order, $product);
							
							cosmosfarm_members_send_notification(array(
								'to_user_id' => $user->ID,
								'content'    => sprintf('<strong>%s</strong> 결제되었습니다. [정기결제 %d회차]', $product->title(), $pay_count),
								'meta_input' => array(
									'url'      => $subscribe_result->receipt_url,
									'url_name' => '영수증',
								),
							));
						}
					}
					
					if(!$order->ID()){
						if($old_order->subscription_prev_role()){
							$user->remove_role($user->roles[0]);
							$user->add_role($old_order->subscription_prev_role());
						}
						
						do_action('cosmosfarm_members_subscription_again_failure', $old_order, $product);
						
						cosmosfarm_members_send_notification(array(
							'to_user_id' => $user->ID,
							'content'    => sprintf('<strong>%s</strong> 결제에 실패했습니다. [%s]', $product->title(), $subscribe_result->error_message),
						));
					}
				}
				else{
					if($old_order->subscription_prev_role()){
						$user->remove_role($user->roles[0]);
						$user->add_role($old_order->subscription_prev_role());
					}
					
					do_action('cosmosfarm_members_subscription_expiry', $old_order, $product);
					
					cosmosfarm_members_send_notification(array(
						'to_user_id' => $user->ID,
						'content'    => sprintf('<strong>%s</strong> 만료되었습니다. [만료]', $product->title()),
					));
				}
			}
		}
	}
	
	/**
	 * 빌링결제 정보 업데이트
	 */
	private function subscription_update(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
			$subscription_active = isset($_POST['subscription_active']) ? sanitize_text_field($_POST['subscription_active']) : '';
			$order = new Cosmosfarm_Members_Subscription_Order($order_id);
			
			if($order->ID() && $order->user_id()){
				if($order->user_id() == get_current_user_id()){
					$order->set_subscription_active($subscription_active);
					$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'), 'order_id'=>$order_id, 'subscription_active'=>$subscription_active);
				}
			}
		}
		
		$result = apply_filters('cosmosfarm_members_subscription_update_result', $result);
		wp_send_json($result);
	}
	
	private function exists_check(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$meta_key = isset($_POST['meta_key']) ? sanitize_text_field($_POST['meta_key']) : '';
		$meta_value = isset($_POST['meta_value']) ? sanitize_text_field($_POST['meta_value']) : '';
		
		if(is_user_logged_in()){
			$user_id = get_current_user_id();
		}
		else{
			$user_id = 0;
		}
		
		$exists = cosmosfarm_members_user_value_exists($meta_key, $meta_value, $user_id);
		
		if($exists){
			$message = __('Already in use.', 'cosmosfarm-members');
		}
		else{
			$message = __('Available.', 'cosmosfarm-members');
		}
		
		$result = array('exists'=>$exists, 'meta_key'=>$meta_key, 'meta_value'=>$meta_value, 'message'=>$message);
		$result = apply_filters('cosmosfarm_members_exists_check_result', $result);
		
		wp_send_json($result);
	}
	
	private function notifications_read(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$notification = new Cosmosfarm_Members_Notification($post_id);
			
			if($notification->user_id == get_current_user_id()){
				$notification->read();
				$unread_count = intval(get_user_meta(get_current_user_id(),  'cosmosfarm_members_unread_notifications_count', true));
				$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'), 'unread_count'=>$unread_count);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_notifications_read_result', $result);
		wp_send_json($result);
	}
	
	private function notifications_unread(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$notification = new Cosmosfarm_Members_Notification($post_id);
			
			if($notification->user_id == get_current_user_id()){
				$notification->unread();
				$unread_count = intval(get_user_meta(get_current_user_id(),  'cosmosfarm_members_unread_notifications_count', true));
				$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'), 'unread_count'=>$unread_count);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_notifications_unread_result', $result);
		wp_send_json($result);
	}
	
	private function notifications_delete(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$notification = new Cosmosfarm_Members_Notification($post_id);
			
			if($notification->user_id == get_current_user_id()){
				$notification->delete();
				$unread_count = intval(get_user_meta(get_current_user_id(),  'cosmosfarm_members_unread_notifications_count', true));
				$result = array('result'=>'success', 'message'=>__('Has been deleted.', 'cosmosfarm-members'), 'unread_count'=>$unread_count);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_notifications_delete_result', $result);
		wp_send_json($result);
	}
	
	private function notifications_subnotify_update(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$user_id = get_current_user_id();
			$notifications_subnotify_email = isset($_POST['notifications_subnotify_email']) ? intval($_POST['notifications_subnotify_email']) : 0;
			$notifications_subnotify_sms = isset($_POST['notifications_subnotify_sms']) ? intval($_POST['notifications_subnotify_sms']) : 0;
			
			if($notifications_subnotify_email){
				update_user_meta($user_id, 'cosmosfarm_members_notifications_subnotify_email', $notifications_subnotify_email);
			}
			else{
				delete_user_meta($user_id, 'cosmosfarm_members_notifications_subnotify_email');
			}
			
			if($notifications_subnotify_sms){
				update_user_meta($user_id, 'cosmosfarm_members_notifications_subnotify_sms', $notifications_subnotify_sms);
			}
			else{
				delete_user_meta($user_id, 'cosmosfarm_members_notifications_subnotify_sms');
			}
			
			$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'));
		}
		
		$result = apply_filters('cosmosfarm_members_notifications_subnotify_update_result', $result);
		wp_send_json($result);
	}
	
	private function notifications_send(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$to_user_id = isset($_POST['to_user_id']) ? intval($_POST['to_user_id']) : 0;
			$title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
			$content = isset($_POST['content']) ? sanitize_text_field($_POST['content']) : '';
			
			if(!$to_user_id){
				$result = array('result'=>'error', 'message'=>__('Recipient is required.', 'cosmosfarm-members'));
			}
			else if(!$content){
				$result = array('result'=>'error', 'message'=>__('Content is required.', 'cosmosfarm-members'));
			}
			else{
				$post_id = cosmosfarm_members_send_notification(array(
						'from_user_id' => get_current_user_id(),
						'to_user_id'   => $to_user_id,
						'title'        => $title,
						'content'      => $content
				));
				
				if($post_id){
					$result = array('result'=>'success', 'message'=>__('A notification has been sent.', 'cosmosfarm-members'));
				}
				else{
					$result = array('result'=>'error', 'message'=>__('Notification failed to send.', 'cosmosfarm-members'));
				}
			}
		}
		
		$result = apply_filters('cosmosfarm_members_notifications_send_result', $result);
		wp_send_json($result);
	}
	
	private function messages_read(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$message = new Cosmosfarm_Members_Message($post_id);
			
			if($message->user_id == get_current_user_id()){
				$message->read();
				$unread_count = intval(get_user_meta(get_current_user_id(),  'cosmosfarm_members_unread_messages_count', true));
				$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'), 'unread_count'=>$unread_count);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_messages_read_result', $result);
		wp_send_json($result);
	}
	
	private function messages_unread(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$message = new Cosmosfarm_Members_Message($post_id);
			
			if($message->user_id == get_current_user_id()){
				$message->unread();
				$unread_count = intval(get_user_meta(get_current_user_id(),  'cosmosfarm_members_unread_messages_count', true));
				$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'), 'unread_count'=>$unread_count);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_messages_unread_result', $result);
		wp_send_json($result);
	}
	
	private function messages_delete(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$message = new Cosmosfarm_Members_Message($post_id);
			
			if($message->user_id == get_current_user_id()){
				$message->delete();
				$unread_count = intval(get_user_meta(get_current_user_id(),  'cosmosfarm_members_unread_messages_count', true));
				$result = array('result'=>'success', 'message'=>__('Has been deleted.', 'cosmosfarm-members'), 'unread_count'=>$unread_count);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_messages_delete_result', $result);
		wp_send_json($result);
	}
	
	private function messages_subnotify_update(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$user_id = get_current_user_id();
			$messages_subnotify_email = isset($_POST['messages_subnotify_email']) ? intval($_POST['messages_subnotify_email']) : 0;
			$messages_subnotify_sms = isset($_POST['messages_subnotify_sms']) ? intval($_POST['messages_subnotify_sms']) : 0;
			
			if($messages_subnotify_email){
				update_user_meta($user_id, 'cosmosfarm_members_messages_subnotify_email', $messages_subnotify_email);
			}
			else{
				delete_user_meta($user_id, 'cosmosfarm_members_messages_subnotify_email');
			}
			
			if($messages_subnotify_sms){
				update_user_meta($user_id, 'cosmosfarm_members_messages_subnotify_sms', $messages_subnotify_sms);
			}
			else{
				delete_user_meta($user_id, 'cosmosfarm_members_messages_subnotify_sms');
			}
			
			$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'));
		}
		
		$result = apply_filters('cosmosfarm_members_messages_subnotify_update_result', $result);
		wp_send_json($result);
	}
	
	private function messages_send(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		$_POST = stripslashes_deep($_POST);
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$to_user_id = isset($_POST['to_user_id']) ? intval($_POST['to_user_id']) : 0;
			$title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
			$content = isset($_POST['content']) ? sanitize_textarea_field($_POST['content']) : '';
			
			if(!$to_user_id){
				$result = array('result'=>'error', 'message'=>__('Recipient is required.', 'cosmosfarm-members'));
			}
			else if(!$content){
				$result = array('result'=>'error', 'message'=>__('Content is required.', 'cosmosfarm-members'));
			}
			else{
				$post_id = cosmosfarm_members_send_message(array(
						'from_user_id' => get_current_user_id(),
						'to_user_id'   => $to_user_id,
						'title'        => $title,
						'content'      => $content
				));
				
				if($post_id){
					$result = array('result'=>'success', 'message'=>__('Your message has been sent.', 'cosmosfarm-members'));
				}
				else{
					$result = array('result'=>'error', 'message'=>__('Message failed to send.', 'cosmosfarm-members'));
				}
			}
		}
		
		$result = apply_filters('cosmosfarm_members_messages_send_result', $result);
		wp_send_json($result);
	}
}
?>