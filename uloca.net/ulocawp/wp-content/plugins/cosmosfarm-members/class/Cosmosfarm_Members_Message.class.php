<?php
/**
 * Cosmosfarm_Members_Message
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2018 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Message {
	
	var $post;
	var $post_id = 0;
	var $user_id = 0;
	var $post_type = 'cosmosfarm_message';
	
	public function __construct($post_id=''){
		$this->post_id = 0;
		$this->user_id = 0;
		
		if($post_id){
			$this->init_post_id($post_id);
		}
	}
	
	public function __get($name){
		if($this->post_id && isset($this->post->{$name})){
			return $this->post->{$name};
		}
		return '';
	}
	
	public function init_post_id($post_id){
		$this->post = get_post($post_id);
		if($this->post){
			$this->post_id = $this->post->ID;
			$this->user_id = $this->post->post_author;
		}
		else{
			$this->post_id = 0;
			$this->user_id = 0;
		}
	}
	
	public function create($args){
		$this->post_id = 0;
		
		$option = get_cosmosfarm_members_option();
		if(!$option->messages_page_id){
			return $this->post_id;
		}
		
		if(isset($args['from_user_id']) && $args['from_user_id'] && isset($args['to_user_id']) && $args['to_user_id']){
			$from_user_id = intval($args['from_user_id']);
			$from_user = get_userdata($from_user_id);
			
			$to_user_id = intval($args['to_user_id']);
			$to_user = get_userdata($to_user_id);
			
			if($from_user && $from_user->ID && $to_user && $to_user->ID){
				$this->user_id = $to_user->ID;
				
				if(isset($args['content']) && $args['content']){
					$title = isset($args['title']) ? $args['title'] : '';
					$meta_input = isset($args['meta_input']) ? $args['meta_input'] : array();
					
					$this->post_id = wp_insert_post(array(
							'post_title'     => wp_strip_all_tags($title),
							'post_content'   => $args['content'],
							'post_status'    => 'publish',
							'comment_status' => 'closed',
							'ping_status'    => 'closed',
							'post_author'    => $this->user_id,
							'post_type'      => $this->post_type,
							'meta_input'     => $meta_input
					));
				}
				
				add_post_meta($this->post_id, 'from_user_id', $from_user->ID);
				add_post_meta($this->post_id, 'to_user_id', $to_user->ID);
				
				if($this->post_id && isset($args['item_type']) && $args['item_type']){
					add_post_meta($this->post_id, 'item_type', $args['item_type']);
				}
				
				if($this->post_id){
					$this->unread();
					
					if($this->is_user_subnotify_email($this->user_id)){
						$mail = new Cosmosfarm_Members_Mail();
						$mail->send(apply_filters('cosmosfarm_members_messages_subnotify_email_args', array(
								'to' => $to_user->user_email,
								'subject' => $option->messages_subnotify_email_title,
								'message' => $option->messages_subnotify_email_content,
						), $this));
					}
					
					if($this->is_user_subnotify_sms($this->user_id)){
						$phone = get_user_meta($this->user_id, $option->subnotify_sms_field, true);
						if($phone){
							$sms_args = array();
							$sms_args['to'] = $phone;
							$sms_args['message'] = $option->messages_subnotify_sms_message;
							$sms_args = apply_filters('cosmosfarm_members_messages_subnotify_sms_args', $sms_args, $this);
							cosmosfarm_members_sms_send($sms_args['to'], $sms_args['message']);
						}
					}
					
					do_action('cosmosfarm_members_send_message', $this);
				}
			}
		}
		
		return $this->post_id;
	}
	
	public function delete(){
		if($this->post_id){
			if($this->get_status() == 'unread'){
				$this->user_unread_count_down();
			}
			wp_delete_post($this->post_id);
		}
	}
	
	public function read(){
		if($this->post_id){
			update_post_meta($this->post_id, 'item_status', 'read');
			$this->user_unread_count_down();
		}
	}
	
	public function unread(){
		if($this->post_id){
			update_post_meta($this->post_id, 'item_status', 'unread');
			$this->user_unread_count_up();
		}
	}
	
	public function get_status(){
		if($this->post_id){
			return get_post_meta($this->post_id, 'item_status', true);
		}
		return '';
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
	
	private function user_unread_count_up(){
		if($this->post_id && $this->user_id){
			$unread_count = intval(get_user_meta($this->user_id,  'cosmosfarm_members_unread_messages_count', true));
			$unread_count++;
			update_user_meta($this->user_id, 'cosmosfarm_members_unread_messages_count', $unread_count);
		}
	}
	
	private function user_unread_count_down(){
		if($this->post_id && $this->user_id){
			$unread_count = intval(get_user_meta($this->user_id,  'cosmosfarm_members_unread_messages_count', true));
			$unread_count--;
			if($unread_count < 0) $unread_count = 0;
			update_user_meta($this->user_id, 'cosmosfarm_members_unread_messages_count', $unread_count);
		}
	}
	
	public function is_subnotify_email(){
		$option = get_cosmosfarm_members_option();
		if($option->messages_subnotify_email){
			return true;
		}
		return false;
	}
	
	public function is_user_subnotify_email($user_id=''){
		if($this->is_subnotify_email()){
			if(!$user_id){
				$user_id = get_current_user_id();
			}
			if($user_id){
				$subnotify_email = get_user_meta($user_id, 'cosmosfarm_members_messages_subnotify_email', true);
				if($subnotify_email){
					return true;
				}
			}
		}
		return false;
	}
	
	public function is_subnotify_sms(){
		$option = get_cosmosfarm_members_option();
		if($option->messages_subnotify_sms && $option->subnotify_sms_field && get_cosmosfarm_members_sms()->is_active()){
			return true;
		}
		return false;
	}
	
	public function is_user_subnotify_sms($user_id=''){
		if($this->is_subnotify_sms()){
			if(!$user_id){
				$user_id = get_current_user_id();
			}
			if($user_id){
				$subnotify_sms = get_user_meta($user_id, 'cosmosfarm_members_messages_subnotify_sms', true);
				if($subnotify_sms){
					return true;
				}
			}
		}
		return false;
	}
}
?>