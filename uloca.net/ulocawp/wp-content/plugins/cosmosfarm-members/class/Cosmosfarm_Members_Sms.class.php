<?php
/**
 * Cosmosfarm_Members_Sms
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2018 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Sms {
	
	public function __construct(){
		$template = isset($_GET['template'])?$_GET['template']:'';
		switch($template){
			case 'cosmosfarm_members_sms_form': add_action('template_redirect', array($this, 'sms_form')); break;
		}
	}
	
	public function sms_form(){
		global $wpdb, $wp_scripts, $wp_styles;
		if(current_user_can('activate_plugins')){
			$user_id = isset($_GET['user_id'])?intval($_GET['user_id']):'';
			$user = new WP_User($user_id);
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/sms_form.php';
			exit;
		}
	}
	
	public function send($phone, $content){
		$option = get_cosmosfarm_members_option();
		if(!$this->is_active()){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		$phone = trim($phone);
		if(!$phone){
			return array('result'=>'error', 'message'=>'휴대폰번호를 확인해주세요.');
		}
		
		$phone = preg_replace("/[^0-9]/", "", $phone);
		if(preg_match("/^01[0-9]{8,9}$/", $phone)){
			$phone = preg_replace("/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/", "$1-$2-$3", $phone);
		}
		else{
			return array('result'=>'error', 'message'=>'잘못된 번호형식입니다.');
		}
		
		$content = trim($content);
		if(!$content){
			return array('result'=>'error', 'message'=>'내용을 입력해주세요.');
		}
		
		if($option->sms_service == 'cafe24'){
			return $this->send_execute_cafe24($phone, $content);
		}
		else if($option->sms_service == 'toast_cloud'){
			return $this->send_execute_toast_cloud($phone, $content);
		}
	}
	
	public function send_execute_cafe24($phone, $content){
		$option = get_cosmosfarm_members_option();
		if(!$this->is_active()){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		$body = array();
		$body['user_id'] = base64_encode($option->sms_cafe24_id);
		$body['secure'] = base64_encode($option->sms_cafe24_secret);
		$body['msg'] = base64_encode($content);
		$body['rphone'] = base64_encode($phone);
		$body['sphone1'] = base64_encode($option->sms_caller1);
		$body['sphone2'] = base64_encode($option->sms_caller2);
		$body['sphone3'] = base64_encode($option->sms_caller3);
		$body['rdate'] = base64_encode('');
		$body['rtime'] = base64_encode('');
		$body['mode'] = base64_encode('1'); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
		$body['returnurl'] = base64_encode('');
		$body['testflag'] = base64_encode(''); // test is Y
		$body['destination'] = strtr(base64_encode(''), '+/=', '-,');
		$body['repeatFlag'] = base64_encode('');
		$body['repeatNum'] = base64_encode('');
		$body['repeatTime'] = base64_encode('');
		
		if(strlen(iconv('utf8', 'euckr', $content)) > 90){
			$type = 'L';
		}
		else{
			$type = '';
		}
		$body['smsType'] = base64_encode($type); // LMS일경우 L
		
		$response = wp_safe_remote_post('https://sslsms.cafe24.com/sms_sender.php', array('body'=>$body));
		
		if(is_wp_error($response)){
			return array('result'=>'error', 'message'=>$response->get_error_message());
		}
		
		list($result, $count) = explode(',', $response['body']);
		
		if($result == 'success'){
			return array('result'=>'success', 'message'=>"성공적으로 전송되었습니다. 잔여건수는 {$count}건 입니다.");
		}
		else if($result == 'reserved'){
			return array('result'=>'success', 'message'=>"성공적으로 예약되었습니다. 잔여건수는 {$count}건 입니다.");
		}
		else if($result == '3205'){
			return array('result'=>'error', 'message'=>'잘못된 번호형식입니다.');
		}
		else if($result == '0044'){
			return array('result'=>'error', 'message'=>'스팸문자는발송되지 않습니다.');
		}
		else if($result == '-100'){
			return array('result'=>'error', 'message'=>'카페24 서버 에러가 발생했습니다.');
		}
		else if($result == '-102'){
			return array('result'=>'error', 'message'=>'아이디와 인증키를 다시 확인해주세요.');
		}
		else if($result == '-114'){
			return array('result'=>'error', 'message'=>'등록/인증되지 않은 발신번호입니다.');
		}
		else if($result == '-201'){
			return array('result'=>'error', 'message'=>'SMS 발송 건수가 부족합니다.');
		}
		else if($result == '-202'){
			return array('result'=>'error', 'message'=>'문자 \'됬\'은 카페24 SMS 발송시 사용불가능한 문자입니다.');
		}
		else if($result){
			return array('result'=>'error', 'message'=>"error code: {$result}");
		}
		return array('result'=>'error', 'message'=>'알 수 없는 에러가 발생했습니다.');
	}
	
	public function send_execute_toast_cloud($phone, $content){
		$option = get_cosmosfarm_members_option();
		if(!$this->is_active()){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		if(strlen(iconv('utf8', 'euckr', $content)) > 90){
			$sender = 'mms';
			$title = mb_substr($content, 0, 40);
			$args = array(
				'headers' => array('content-type' => 'application/json;charset=UTF-8'),
				'body'    => json_encode(array(
					'title'         => $title,
					'body'          => $content,
					'sendNo'        => "{$option->sms_caller1}{$option->sms_caller2}{$option->sms_caller3}",
					'recipientList' => array(array('recipientNo' => $phone))
				))
			);
		}
		else{
			$sender = 'sms';
			$args = array(
				'headers' => array('content-type' => 'application/json;charset=UTF-8'),
				'body'    => json_encode(array(
					'body'          => $content,
					'sendNo'        => "{$option->sms_caller1}{$option->sms_caller2}{$option->sms_caller3}",
					'recipientList' => array(array('recipientNo' => $phone))
				))
			);
		}
		
		$response = wp_safe_remote_post("https://api-sms.cloud.toast.com/sms/v2.0/appKeys/{$option->sms_toast_cloud_appkey}/sender/{$sender}", $args);
		
		if(is_wp_error($response)){
			return array('result'=>'error', 'message'=>$response->get_error_message());
		}
		
		$result = json_decode($response['body']);
		
		if(isset($result->header)){
			if($result->header->isSuccessful){
				return array('result'=>'success', 'message'=>"성공적으로 전송되었습니다.");
			}
			else{
				return array('result'=>'error', 'message'=>$result->header->resultMessage);
			}
		}
		return array('result'=>'error', 'message'=>'알 수 없는 에러가 발생했습니다.');
	}
	
	public function get_count_cafe24(){
		$option = get_cosmosfarm_members_option();
		if(!$option->sms_service || !$option->sms_cafe24_id || !$option->sms_cafe24_secret){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		$body['user_id'] = base64_encode($option->sms_cafe24_id);
		$body['secure'] = base64_encode($option->sms_cafe24_secret);
		$body['mode'] = base64_encode('1'); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
		
		$response = wp_safe_remote_post('https://sslsms.cafe24.com/sms_remain.php', array('body'=>$body));
		
		if(is_wp_error($response)){
			return array('result'=>'error', 'message'=>$response->get_error_message());
		}
		
		$result = $response['body'];
		
		if($result == '-100'){
			return array('result'=>'error', 'message'=>'카페24 서버 에러가 발생했습니다.');
		}
		else if($result == '-102'){
			return array('result'=>'error', 'message'=>'아이디와 인증키를 다시 확인해주세요.');
		}
		else if($result == '-114'){
			return array('result'=>'error', 'message'=>'등록/인증되지 않은 발신번호입니다.');
		}
		else if($result == '-201'){
			return array('result'=>'error', 'message'=>'SMS 발송 건수가 부족합니다.');
		}
		else if($result == '-202'){
			return array('result'=>'error', 'message'=>'문자 \'됬\'은 카페24 SMS 발송시 사용불가능한 문자입니다.');
		}
		return array('result'=>'success', 'count'=>intval($result));
	}
	
	public function is_active(){
		$option = get_cosmosfarm_members_option();
		if($option->sms_service && $option->sms_caller1 && $option->sms_caller2){
			if($option->sms_service == 'cafe24'){
				if($option->sms_cafe24_id && $option->sms_cafe24_secret){
					return true;
				}
			}
			else if($option->sms_service == 'toast_cloud'){
				if($option->sms_toast_cloud_appkey){
					return true;
				}
			}
		}
		return false;
	}
}
?>