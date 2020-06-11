<?php
/*
Plugin Name: KBoard : Powered By KBoard 삭제
Plugin URI: http://www.cosmosfarm.com/wpstore/product/kboard-remove-powered-by
Description: Powered By KBoard 텍스트를 삭제합니다.
Version: 1.0
Author: 코스모스팜 - Cosmosfarm
Author URI: http://www.cosmosfarm.com/
*/

if(!defined('ABSPATH')) exit;
if(!session_id()) session_start();

define('KBOARD_REMOVE_POWERED_BY', '1.0');

add_filter('kboard_contribution', 'kboard_remove_powered_by', 10, 2);
function kboard_remove_powered_by($contribution, $board){
	$contribution = false;
	return $contribution;
}