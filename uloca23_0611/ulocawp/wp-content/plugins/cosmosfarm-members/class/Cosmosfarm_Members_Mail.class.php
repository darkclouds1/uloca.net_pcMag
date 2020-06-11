<?php
/**
 * Cosmosfarm_Members_Mail
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2018 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Mail {
	
	public function __construct(){
		
	}
	
	public function send($args){
		add_filter('wp_mail_content_type', array($this, 'content_type'));
		add_filter('wp_mail', array($this, 'message_template'));
		
		$to = $args['to'];
		$subject = $args['subject'];
		$message = $args['message'];
		
		$result = wp_mail($to, $subject, $message);
		
		remove_filter('wp_mail', array($this, 'message_template'));
		remove_filter('wp_mail_content_type', array($this, 'content_type'));
		
		return $result;
	}
	
	public function content_type(){
		return 'text/html';
	}
	
	public function message_template($args){
		$subject = $args['subject'];
		$message = wpautop($args['message']);
		
		ob_start();
		include_once COSMOSFARM_MEMBERS_DIR_PATH . '/email/template.php';
		$args['message'] = ob_get_clean();
		
		return $args;
	}
}
?>