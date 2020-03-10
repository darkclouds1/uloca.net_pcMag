<?php
/**
 * Cosmosfarm_Members_Subscription_Order
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2018 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Subscription_Order {
	
	var $post_type = 'cosmosfarm_order';
	var $post;
	var $post_id = 0;
	
	public function __construct($order_id=''){
		if($order_id){
			$this->init_with_id($order_id);
		}
	}
	
	public function __get($name){
		if($this->post_id && isset($this->post->{$name})){
			return $this->post->{$name};
		}
		return '';
	}
	
	public function __set($name, $value){
		if($this->post_id){
			$this->post->{$name} = $value;
		}
	}
	
	public function order_post_type(){
		return 'cosmosfarm_order';
	}
	
	public function init_with_id($post_id){
		$this->post_id = 0;
		$post_id = intval($post_id);
		if($post_id){
			$this->post = get_post($post_id);
			if($this->post && $this->post->ID){
				$this->post_id = $this->post->ID;
			}
		}
	}
	
	public function ID(){
		return intval($this->post_id);
	}
	
	public function title(){
		return $this->post_title;
	}
	
	public function content(){
		return $this->post_content;
	}
	
	public function user_id(){
		return $this->post_author;
	}
	
	public function user(){
		if($this->post_author){
			return new WP_User($this->post_author);
		}
		return new WP_User();
	}
	
	public function create($user_id, $args){
		$user_id = intval($user_id);
		$title = isset($args['title']) ? $args['title'] : '';
		$content = isset($args['content']) ? $args['content'] : '';
		$meta_input = isset($args['meta_input']) ? $args['meta_input'] : array();
		
		$this->post_id = wp_insert_post(array(
				'post_title'     => wp_strip_all_tags($title),
				'post_content'   => $content,
				'post_status'    => 'publish',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_author'    => $user_id,
				'post_type'      => $this->post_type,
				'meta_input'     => $meta_input
		));
		return $this->post_id;
	}
	
	public function update($args){
		if($this->post_id){
			$args['ID'] = $this->post_id;
			
			if(isset($args['title'])){
				$args['post_title'] = $args['title'];
			}
			
			if(isset($args['content'])){
				$args['post_content'] = $args['content'];
			}
			
			wp_update_post($args);
		}
	}
	
	public function delete(){
		if($this->post_id){
			wp_delete_post($this->post_id);
		}
	}
	
	public function set_product_id($product_id){
		if($this->post_id){
			$product_id= intval($product_id);
			update_post_meta($this->post_id, 'product_id', $product_id);
		}
	}
	
	public function product_id(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'product_id', true);
			if($value){
				return intval($value);
			}
		}
		return 0;
	}
	
	public function set_sequence_id($sequence_id){
		if($this->post_id){
			update_post_meta($this->post_id, 'sequence_id', $sequence_id);
		}
	}
	
	public function sequence_id(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'sequence_id', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	public function set_price($price){
		if($this->post_id){
			$price = intval($price);
			update_post_meta($this->post_id, 'price', $price);
		}
	}
	
	public function price(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'price', true);
			if($value){
				return intval($value);
			}
		}
		return 0;
	}
	
	public function set_first_price($first_price){
		if($this->post_id){
			$first_price = intval($first_price);
			update_post_meta($this->post_id, 'first_price', $first_price);
		}
	}
	
	public function first_price(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'first_price', true);
			if($value){
				return intval($value);
			}
			else{
				return $this->price();
			}
		}
		return 0;
	}
	
	public function set_status_paid(){
		if($this->post_id){
			update_post_meta($this->post_id, 'status', 'paid');
		}
	}
	
	public function set_status_cancelled(){
		if($this->post_id){
			update_post_meta($this->post_id, 'status', 'cancelled');
		}
	}
	
	public function status(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'status', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	public function get_status(){
		return $this->status();
	}
	
	public function status_format(){
		$status_format = '';
		if($this->post_id){
			switch($this->status()){
				case 'paid': $status_format = '결제됨'; break;
				case 'cancelled': $status_format = '취소됨'; break;
			}
		}
		return apply_filters('cosmosfarm_members_status_format', $status_format, $this);
	}
	
	public function get_type(){
		if($this->post_id){
			$item_type = get_post_meta($this->post_id, 'item_type', true);
			if($item_type){
				return $item_type;
			}
		}
		return 'default';
	}
	
	public function is_paid(){
		if($this->status() == 'paid'){
			return true;
		}
		return false;
	}
	
	public function is_cancelled(){
		if($this->status() == 'cancelled'){
			return true;
		}
		return false;
	}
	
	public function set_subscription_type($subscription_type){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_type', $subscription_type);
		}
	}
	
	public function subscription_type_format(){
		$subscription_type_format = '';
		if($this->post_id){
			switch($this->subscription_type()){
				case 'onetime': $subscription_type_format = '제한없음'; break;
				case 'daily': $subscription_type_format = '1일'; break;
				case 'weekly': $subscription_type_format = '1주일'; break;
				case 'monthly': $subscription_type_format = '1개월'; break;
				case '2monthly': $subscription_type_format = '2개월'; break;
				case '3monthly': $subscription_type_format = '3개월'; break;
				case '4monthly': $subscription_type_format = '4개월'; break;
				case '5monthly': $subscription_type_format = '5개월'; break;
				case '6monthly': $subscription_type_format = '6개월'; break;
				case '7monthly': $subscription_type_format = '7개월'; break;
				case '8monthly': $subscription_type_format = '8개월'; break;
				case '9monthly': $subscription_type_format = '9개월'; break;
				case '10monthly': $subscription_type_format = '10개월'; break;
				case '11monthly': $subscription_type_format = '11개월'; break;
				case '12monthly': $subscription_type_format = '1년'; break;
			}
		}
		return apply_filters('cosmosfarm_members_subscription_product_type_format', $subscription_type_format, $this);
	}
	
	public function next_subscription_datetime($ymdhis='', $format='YmdHis'){
		$datetime = '';
		if($this->post_id){
			if($ymdhis){
				$timestamp = strtotime($ymdhis);
			}
			else{
				$timestamp = current_time('timestamp');
			}
			switch($this->subscription_type()){
				case 'daily': $datetime = date($format, strtotime('+1 day', $timestamp)); break;
				case 'weekly': $datetime = date($format, strtotime('+1 week', $timestamp)); break;
				case 'monthly': $datetime = date($format, strtotime('+1 month', $timestamp)); break;
				case '2monthly': $datetime = date($format, strtotime('+2 month', $timestamp)); break;
				case '3monthly': $datetime = date($format, strtotime('+3 month', $timestamp)); break;
				case '4monthly': $datetime = date($format, strtotime('+4 month', $timestamp)); break;
				case '5monthly': $datetime = date($format, strtotime('+5 month', $timestamp)); break;
				case '6monthly': $datetime = date($format, strtotime('+6 month', $timestamp)); break;
				case '7monthly': $datetime = date($format, strtotime('+7 month', $timestamp)); break;
				case '8monthly': $datetime = date($format, strtotime('+8 month', $timestamp)); break;
				case '9monthly': $datetime = date($format, strtotime('+9 month', $timestamp)); break;
				case '10monthly': $datetime = date($format, strtotime('+10 month', $timestamp)); break;
				case '11monthly': $datetime = date($format, strtotime('+11 month', $timestamp)); break;
				case '12monthly': $datetime = date($format, strtotime('+1 year', $timestamp)); break;
			}
		}
		return $datetime;
	}
	
	public function subscription_type(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_type', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	public function set_subscription_active($subscription_active){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_active', $subscription_active);
		}
	}
	
	public function subscription_active(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_active', true);
			if($value){
				return true;
			}
		}
		return false;
	}
	
	public function set_subscription_first_free($subscription_first_free){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_first_free', $subscription_first_free);
		}
	}
	
	public function subscription_first_free(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_first_free', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	public function subscription_first_free_format(){
		$subscription_first_free = '';
		if($this->post_id){
			switch($this->subscription_first_free()){
				case '1day': $subscription_first_free = '1일'; break;
				case '2day': $subscription_first_free = '2일'; break;
				case '3day': $subscription_first_free = '3일'; break;
				case '4day': $subscription_first_free = '4일'; break;
				case '5day': $subscription_first_free = '5일'; break;
				case '6day': $subscription_first_free = '6일'; break;
				case '7day': $subscription_first_free = '7일'; break;
				case '8day': $subscription_first_free = '8일'; break;
				case '9day': $subscription_first_free = '9일'; break;
				case '10day': $subscription_first_free = '10일'; break;
				case '15day': $subscription_first_free = '15일'; break;
				case '20day': $subscription_first_free = '20일'; break;
				case '1month': $subscription_first_free = '1개월'; break;
				case '2month': $subscription_first_free = '2개월'; break;
				case '3month': $subscription_first_free = '3개월'; break;
				default: $subscription_first_free = '무료 이용기간 없음';
			}
		}
		return apply_filters('cosmosfarm_members_subscription_first_free_format', $subscription_first_free, $this);
	}
	
	public function next_subscription_datetime_first_free($ymdhis=''){
		$datetime = '';
		if($this->post_id){
			if($ymdhis){
				$timestamp = strtotime($ymdhis);
			}
			else{
				$timestamp = current_time('timestamp');
			}
			switch($this->subscription_first_free()){
				case '1day': $datetime = date('YmdHis', strtotime('+1 day', $timestamp)); break;
				case '2day': $datetime = date('YmdHis', strtotime('+2 day', $timestamp)); break;
				case '3day': $datetime = date('YmdHis', strtotime('+3 day', $timestamp)); break;
				case '4day': $datetime = date('YmdHis', strtotime('+4 day', $timestamp)); break;
				case '5day': $datetime = date('YmdHis', strtotime('+5 day', $timestamp)); break;
				case '6day': $datetime = date('YmdHis', strtotime('+6 day', $timestamp)); break;
				case '7day': $datetime = date('YmdHis', strtotime('+7 day', $timestamp)); break;
				case '8day': $datetime = date('YmdHis', strtotime('+8 day', $timestamp)); break;
				case '9day': $datetime = date('YmdHis', strtotime('+9 day', $timestamp)); break;
				case '10day': $datetime = date('YmdHis', strtotime('+10 day', $timestamp)); break;
				case '15day': $datetime = date('YmdHis', strtotime('+15 day', $timestamp)); break;
				case '20day': $datetime = date('YmdHis', strtotime('+20 day', $timestamp)); break;
				case '1month': $datetime = date('YmdHis', strtotime('+1 month', $timestamp)); break;
				case '2month': $datetime = date('YmdHis', strtotime('+2 month', $timestamp)); break;
				case '3month': $datetime = date('YmdHis', strtotime('+3 month', $timestamp)); break;
			}
		}
		return $datetime;
	}
	
	public function set_subscription_again_price_type($subscription_again_price_type){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_again_price_type', $subscription_again_price_type);
		}
	}
	
	public function subscription_again_price_type(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_again_price_type', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	public function set_subscription_role($subscription_role){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_role', $subscription_role);
		}
	}
	
	public function subscription_role(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_role', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	public function set_subscription_prev_role($subscription_prev_role){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_prev_role', $subscription_prev_role);
		}
	}
	
	public function subscription_prev_role(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_prev_role', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	public function set_subscription_multiple_pay($subscription_multiple_pay){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_multiple_pay', $subscription_multiple_pay);
		}
	}
	
	public function subscription_multiple_pay(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_multiple_pay', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	public function is_subscription_multiple_pay(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_multiple_pay', true);
			if($value && !$this->subscription_role()){ // 사용자 역할(Role) 변경이 없을 경우에만 적용, 역할이 변경될 때 여러번 결제 가능하면 문제 발생
				return true;
			}
		}
		return false;
	}
	
	public function set_start_datetime($start_datetime=''){
		if($this->post_id){
			if(!$start_datetime){
				$start_datetime = date('YmdHis', current_time('timestamp'));
			}
			update_post_meta($this->post_id, 'start_datetime', $start_datetime);
		}
	}
	
	public function start_datetime(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'start_datetime', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	public function set_end_datetime($end_datetime){
		if($this->post_id){
			update_post_meta($this->post_id, 'end_datetime', $end_datetime);
		}
	}
	
	public function end_datetime(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'end_datetime', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	public function set_subscription_next($subscription_next){
		if($this->post_id && in_array($subscription_next, array('success', 'wait', 'cancel', 'expiry'))){
			update_post_meta($this->post_id, 'subscription_next', $subscription_next);
		}
	}
	
	public function subscription_next(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_next', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	public function subscription_next_format(){
		$subscription_next = '';
		if($this->post_id){
			switch($this->subscription_next()){
				case 'wait': $subscription_next = '진행중'; break;
				case 'expiry': $subscription_next = '만료됨'; break;
				case 'success': $subscription_next = ''; break;
			}
		}
		return apply_filters('cosmosfarm_members_subscription_next_format', $subscription_next, $this);
	}
	
	public function set_pay_count($pay_count){
		if($this->post_id){
			$pay_count = intval($pay_count);
			update_post_meta($this->post_id, 'pay_count', $pay_count);
		}
	}
	
	public function pay_count(){
		$value = 0;
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'pay_count', true);
		}
		return intval($value);
	}
	
	public function set_pay_count_limit($pay_count_limit){
		if($this->post_id){
			$pay_count_limit = intval($pay_count_limit);
			update_post_meta($this->post_id, 'pay_count_limit', $pay_count_limit);
		}
	}
	
	public function pay_count_limit(){
		$value = 0;
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'pay_count_limit', true);
		}
		return intval($value);
	}
	
	public function is_in_use($user_id=''){
		if($this->post_id){
			if(!$user_id){
				$user_id = get_current_user_id();
			}
			
			$user = new WP_User($user_id);
			
			if($user->ID && $this->product_id()){
				$args = array(
						'post_type'  => $this->order_post_type(),
						'author' => $user->ID,
						'orderby' => 'ID',
						'posts_per_page' => -1,
						'meta_query' => array(
								array(
										'key'     => 'product_id',
										'value'   => $this->product_id(),
										'compare' => '=',
								),
								array(
										'key'     => 'status',
										'value'   => 'paid',
										'compare' => '=',
								),
								array(
										'key'     => 'subscription_next',
										'value'   => array('success', 'wait'),
										'compare' => 'IN',
								),
						),
				);
				$query = new WP_Query($args);
				if($query->found_posts){
					return new Cosmosfarm_Members_Subscription_Order($query->post->ID);
				}
			}
		}
		return false;
	}
	
	public function get_order_field_template($field=array(), $field2=array(), $field3=array()){
		ob_start();
		if(isset($field['type']) && $field['type']){
			$order = $this;
			$field_type = $field['type'];
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_order_field_template.php';
			do_action('cosmosfarm_members_order_admin_field_template', $order, $field_type, $field, $field2, $field3);
		}
		return ob_get_clean();
	}
	
	public function update_fields($fields, $data){
		if($this->post_id){
			foreach($fields as $field){
				if($field['type'] == 'hr') continue;
				if(!$field['meta_key']) continue;
				
				if($field['type'] == 'checkbox'){
					$meta_value = array();
				}
				else{
					$meta_value = '';
				}
				
				if(isset($data[$field['meta_key']])){
					$meta_value = $data[$field['meta_key']];
				}
				
				if(is_array($meta_value)){
					if($field['type'] == 'textarea'){
						$meta_value = array_map('sanitize_textarea_field', $meta_value);
					}
					else{
						$meta_value = array_map('sanitize_text_field', $meta_value);
					}
					
					delete_post_meta($this->post_id, $field['meta_key']);
					foreach($meta_value as $value){
						add_post_meta($this->post_id, $field['meta_key'], $value);
					}
				}
				else{
					if($field['type'] == 'textarea'){
						$meta_value = sanitize_textarea_field($meta_value);
					}
					else{
						$meta_value = sanitize_text_field($meta_value);
					}
					
					update_post_meta($this->post_id, $field['meta_key'], $meta_value);
				}
				
				$this->init_with_id($this->post_id);
				
				$order = $this;
				do_action('cosmosfarm_members_order_update_field', $order, $field, $meta_value);
			}
		}
	}
	
	public function execute_expiry_action(){
		if($this->post_id){
			if($this->subscription_type() == 'onetime'){
				$this->set_subscription_next('cancel');
				
				if($this->subscription_prev_role()){
					$user = $this->user();
					$user->remove_role($this->subscription_role());
					$user->add_role($this->subscription_prev_role());
				}
				
				$order = $this;
				$product = new Cosmosfarm_Members_Subscription_Product($order->product_id());
				
				do_action('cosmosfarm_members_subscription_expiry', $order, $product);
			}
			else{
				$this->set_end_datetime(date('YmdHis', current_time('timestamp')));
				cosmosfarm_members_subscription_again();
			}
		}
	}
}
?>