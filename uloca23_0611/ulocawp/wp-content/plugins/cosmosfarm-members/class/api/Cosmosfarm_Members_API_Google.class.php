<?php
/**
 * 코스모스팜 회원관리 구글 연동
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2018 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members_API_Google {
	
	private $client_id;
	private $client_secret;
	private $redirect_url;
	private $authorize_url = 'https://accounts.google.com/o/oauth2/auth';
	private $accesstoken_url = 'https://accounts.google.com/o/oauth2/token';
	private $userinfo_url = 'https://www.googleapis.com/plus/v1/people/me';
	private $token;
	
	public function __construct(){
		$option = get_cosmosfarm_members_option();
		$this->client_id = $option->google_client_id;
		$this->client_secret = $option->google_client_secret;
		$this->redirect_url = home_url('?action=cosmosfarm_members_social_login_callback_google');
	}
	
	public function get_request_url(){
		$args['response_type'] = 'code';
		$args['client_id'] = $this->client_id;
		$args['redirect_uri'] = $this->redirect_url;
		$args['scope'] = 'https://www.googleapis.com/auth/userinfo.email';
		return add_query_arg($args, $this->authorize_url);
	}
	
	public function init_access_token(){
		$code = isset($_GET['code'])?$_GET['code']:'';
		if($code){
			$body['grant_type'] = 'authorization_code';
			$body['client_id'] = $this->client_id;
			$body['client_secret'] = $this->client_secret;
			$body['redirect_uri'] = $this->redirect_url;
			$body['code'] = $code;
			$response = wp_safe_remote_post($this->accesstoken_url, array('body'=>$body));
			$data = json_decode($response['body']);
			
			if(isset($data->error) && $data->error){
				wp_die("$data->error");
			}
			
			$this->token = array('Authorization'=>"{$data->token_type} {$data->access_token}");
		}
	}
	
	public function get_profile(){
		$profile = new stdClass();
		if($this->token){
			$response = wp_remote_get($this->userinfo_url, array('headers'=>$this->token));
			$data = json_decode($response['body']);
			
			if(isset($data->error) && $data->error){
				wp_die($data->error->message);
			}
			
			$profile->id = isset($data->id)?$data->id:'';
			$profile->user_login = isset($data->emails)?$data->emails[0]->value:'';
			$profile->email = $profile->user_login;
			$profile->nickname = isset($data->displayName)?$data->displayName:'';
			if(isset($data->image)){
				$url = parse_url($data->image->url);
				$profile->picture = "{$url['scheme']}://{$url['host']}{$url['path']}?sz=500";
			}
			else{
				$profile->picture = '';
			}
			$profile->url = isset($data->url)?$data->url:'';
			$profile->raw_data = $data;
		}
		return $profile;
	}
}
?>