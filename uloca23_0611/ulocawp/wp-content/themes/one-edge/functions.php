<?php

add_action( 'wp_enqueue_scripts', 'one_edge_enqueue_styles' );
function one_edge_enqueue_styles() {
	wp_enqueue_style( 'one_edge-font', '//fonts.googleapis.com/css?family=Cabin:400,600|Open+Sans:400,300,600|Ubuntu:400,300,500,700');
	wp_enqueue_style( 'one_edge-style',  get_template_directory_uri() . '/style.css', array('llorix-one-lite-bootstrap-style'),'1.0.0');
	wp_enqueue_script( 'one_edge-custom-js', get_stylesheet_directory_uri() . '/js/custom-js.js', array('jquery'), '1.0.0', true );
}

function one_edge_setup() {

	/* Set the image size by cropping the image */
	add_image_size( 'one-edge-post-thumbnail-big', 340, 340, true );
	add_image_size( 'one_edge-post-thumbnail-mobile', 233, 233, true );

}
add_action( 'after_setup_theme', 'one_edge_setup', 11 );

// 로그아웃 시 사용자를 원하는 페이지로 보내기 -by jsj 20181214
add_action('wp_logout','wpbox_redirect_after_logout');
function wpbox_redirect_after_logout(){
	//아래의 url을 원하는 페이지로 변경합니다
	wp_redirect( 'https://uloca.net/ulocawp/?page_id=1134' );	//통합검색 
	exit();
}

// 우커머스 확인없이 로그아웃 하기
function wpbox_bypass_logout_confirmation() {
	global $wp;
	if ( isset( $wp->query_vars['customer-logout'] ) ) {
		wp_redirect( str_replace( '&amp;', '&', wp_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) );
		exit();
	}
}
add_action( 'template_redirect', 'wpbox_bypass_logout_confirmation' );


// MY유로카 메뉴 클릭시 특정페이지로 이동 -by jsj 20190108
add_action( 'template_redirect', 'redirect_to_specific_page' );
function redirect_to_specific_page() {
	// MY유로카 (902) 페이지 ID => 구매결제(1352)로 이동
	if ( is_page('902') && is_user_logged_in() ) {
		wp_redirect( 'https://uloca.net/ulocawp/?page_id=1352' );
		exit(); 
	}
	// 입찰정보&기업검색(337) 페이지 ID => 통합검색(1134)로 이동 
	if ( is_page('337')) {
		wp_redirect( 'https://uloca.net/ulocawp/?page_id=1134' );
		exit();
	}
}

// 문의하기(ID=1), 결제문의 (ID=4) - 자신의 쓴글만, 관리자는 전체
add_filter('kboard_list_where', 'my_kboard_list_where', 10, 2);
function my_kboard_list_where($where, $board_id){
	if(!is_admin() AND ($board_id=='1' || $board_id=='4')){ // 원하시는 게시판 ID 값으로 바꿔주세요.
		$user_ID = get_current_user_id();
		return $where . " AND `member_uid`='$user_ID'";
	}
	return $where; 
}
