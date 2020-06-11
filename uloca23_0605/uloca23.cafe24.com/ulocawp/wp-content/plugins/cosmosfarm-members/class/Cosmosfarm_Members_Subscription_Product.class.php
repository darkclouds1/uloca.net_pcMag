<?php
/**
 * Cosmosfarm_Members_Subscription_Product
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2018 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Subscription_Product extends Cosmosfarm_Members_Subscription_Order {
	
	var $post_type = 'cosmosfarm_product';
	
	public function __construct($product_id=''){
		if($product_id){
			$this->init_with_id($product_id);
		}
	}
	
	public function product_post_type(){
		return 'cosmosfarm_product';
	}
	
	public function update($args){
		if($this->post_id){
			$title = isset($args['title']) ? $args['title'] : '';
			$content = isset($args['content']) ? $args['content'] : '';
			
			wp_update_post(array(
					'ID'           => $this->post_id,
					'post_title'   => $title,
					'post_content' => $content
			));
		}
	}
	
	public function product_id(){
		if($this->post_id){
			return intval($this->post_id);
		}
		return 0;
	}
	
	public function set_fields($fields){
		if($this->post_id){
			update_post_meta($this->post_id, 'fields', $fields);
		}
	}
	
	public function fields(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'fields', true);
			if($value){
				return $value;
			}
		}
		return array();
	}
	
	public function order_view_fields(){
		$order_view_fields = array();
		
		if($this->post_id){			
			foreach($this->fields() as $key=>$field){
				if($field['type'] == 'hr') continue;
				//if($field['type'] == 'hidden') continue;
				if(!$field['meta_key']) continue;
				if(!$field['order_view']) continue; // 주문내역에 숨김, 표시 체크
				
				array_push($order_view_fields, $field);
			}
		}
		return $order_view_fields;
	}
	
	public function get_admin_field_template($field=array(), $field2=array(), $field3=array()){
		ob_start();
		if(isset($field['type']) && $field['type']){
			$field_type = $field['type'];
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_product_field_template.php';
			do_action('cosmosfarm_members_product_admin_field_template', $field_type, $field, $field2, $field3);
		}
		return ob_get_clean();
	}
	
	public function get_admin_field_template_list(){
		$list = array(
				'buyer_name'  => '주문자명',
				'buyer_email' => '주문자 이메일',
				'buyer_tel'   => '주문자 전화번호',
				'text'        => '텍스트',
				'number'      => '숫자',
				'select'      => '셀렉트',
				'radio'       => '라디오',
				'checkbox'    => '체크박스',
				'zip'         => '주소',
				'textarea'    => '텍스트에어리어',
				'agree'       => '동의하기',
				'hr'          => '구분선',
				'hidden'      => '숨김필드'
		);
		return apply_filters('cosmosfarm_members_product_admin_field_template_list', $list);
	}
	
	public function get_order_url(){
		if($this->post_id){
			$option = get_cosmosfarm_members_option();
			if($option->subscription_checkout_page_id){
				$order_url = get_permalink($option->subscription_checkout_page_id);
				$order_url = add_query_arg(array('cosmosfarm_product_id'=>$this->post_id, 'cosmosfarm_redirect_to'=>$_SERVER['REQUEST_URI']), $order_url);
				return $order_url;
			}
		}
		return '';
	}
	
	public function get_order_url_without_redirect(){
		if($this->post_id){
			$option = get_cosmosfarm_members_option();
			if($option->subscription_checkout_page_id){
				$order_url = get_permalink($option->subscription_checkout_page_id);
				$order_url = add_query_arg(array('cosmosfarm_product_id'=>$this->post_id), $order_url);
				return $order_url;
			}
		}
		return '';
	}
	
	public function is_in_use($user_id=''){
		return parent::is_in_use($user_id);
	}
	
	public function is_subscription_first_free($user_id=''){
		if($this->post_id){
			if($this->subscription_type() != 'onetime' && $this->subscription_active() && $this->subscription_first_free()){
				if(!$user_id){
					$user_id = get_current_user_id();
				}
				
				$user = new WP_User($user_id);
				
				if($user->ID){
					$args = array(
						'post_type'  => $this->order_post_type(),
						'author' => $user->ID,
						'orderby' => 'ID',
						'posts_per_page' => -1,
						'meta_query' => array(
							array(
								'key'     => 'product_id',
								'value'   => $this->post_id,
								'compare' => '=',
							)
						),
					);
					$query = new WP_Query($args);
					if(!$query->found_posts){
						return true;
					}
				}
			}
		}
		return false;
	}
}
?>