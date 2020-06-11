<?php
/*
Plugin Name: pay1_ShortCode : 구매 결재
Plugin URI: /www/ulocawp/wp-content/plugins/payment/payment.php
Description: 워드프레스 구매 결재 입니다.
Version: 1.0
Author: Monolith
Author URI: /www/ulocawp/wp-content/plugins/payment/payment.php
*/

function pay1_ShortCode() {
	//session_start(); //-by 정순장 193006
		$current_user = wp_get_current_user();
		$userid = $current_user->user_login;
		$usermail = $current_user->user_email;
	$svr = 'uloca23.cafe24.com';
	//$svr = 'uloca.net';
		require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');
		$dbConn = new dbConn;
		$conn = $dbConn->conn();
		$sql = "select payTypeCD,customer_uid from PayUser02H where PayUser01M_user_login='".$userid."'";
		$sql .= " order by idx desc limit 0,1 ";
		//echo $sql;
		$result=$conn->query($sql);
		$payTypeCD = '01'; // 1달사용
		$customer_uid = '';
		if ($row = $result->fetch_assoc() )  {
			$payTypeCD = $row['payTypeCD'];
			$customer_uid = $row['customer_uid'];
		}
		$payBtn = '정기결제 구매하기';
		if ($payTypeCD == '02') $payBtn = '정기결제 해지하기'; 
?>
<!DOCTYPE html>
<html>
<head>
<title>구매 결재</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="google-site-verification" content="l7HqV1KZeDYUI-2Ukg3U5c7FaCMRNe3scGPRZYjB_jM" />
<meta name="format-detection" content="telephone=no">  <!--//-by jsj 전화걸기로 링크되는 것 막음 -->

<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
<link rel="stylesheet" href="/jquery/jquery-ui.css">

<script src="/jquery/jquery.min.js"></script>
<script src="/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.1.6-20181005.js?ver=20181009"></script>
<script src="/js/common.js?version=20190203"></script>
<script src="/payment/payment.js"></script>
<script>
var userid = '<?=$userid?>';
var usermail = '<?=$usermail?>';
var payTypeCD ='<?=$payTypeCD?>';
var customer_uid = '<?=$customer_uid?>';
</script>
</head>

<body>


<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>구매결제 시 주의사항</p><p></p><cite>1개월 사용 상품은 환불이 되지 않습니다. </cite></blockquote>
<!-- /wp:quote -->

<!-- wp:media-text {"mediaId":919,"mediaType":"image","mediaWidth":37} -->
<div class="wp-block-media-text alignwide" style="grid-template-columns:37% auto"><!--figure class="wp-block-media-text__media">
	<img src="http://uloca.net/ulocawp/wp-content/uploads/2019/01/image.png" width='278' height='136' alt="" /></figure -->
<span style="color:#E9602C; font-size: 120px; font-family: 'Times New Roman';">uloca.net</span><br>
<!-- p style='color:#E9602C; font-size:large; text-align:left'>유로카 닷넷</font -->
<div class="wp-block-media-text__content"><!-- wp:paragraph -->
<p>구매결제 ₩ 22,000/월(1User) (월 최대 500회 검색) </p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:media-text -->

<!-- wp:paragraph -->
<p>1User 당 월 최대 500검색이 넘어서면 추가 결제가 필요합니다. 
<br /> 사용자수가 많은 법인 기업이면 신규 유저(User)를 등록/결제하여 사용하시기 바랍니다. 
<br /> 관련 도움이 필요하신 경우 구매전용 핫라인을 이용해 주세요. T.070-8876-7880 </p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":37} -->
<div style="height:37px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:quote {"align":"center","className":"is-style-large"} -->
<blockquote style="text-align:center" class="wp-block-quote is-style-large"><p><strong>상품종류</strong></p></blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph -->
<!-- p>▷<strong>정기결제 (무제한검색 요금제)</strong>  </p>
<!-- /wp:paragraph -- >




<!-- 정기 결제 ----------------------------------------------------->

<!-- wp:paragraph -- >
<p>정기결제 시 10% 할인 적용 및 검색 조회수 관계없이 무제한 서비스 적용되며, 오늘부터 월마다 ₩19,800 청구됩니다.<br/>환불규정: 정기결제는 언제든지 취소하실 수 있습니다. 단, 사용중인 달은 취소하실 수 없습니다.</p>
<!-- /wp:paragraph -- >

<!-- wp:spacer {"height":53} -- >
<div style="height:53px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:html -->
<!-- ?
/*
if ($payTypeCD == '02') {
	?>
<div class="wp-block-button aligncenter"><font color=red>결제금액 : 19,800원/월</font>&nbsp;<a class="wp-block-button__link has-background has-luminous-vivid-orange-background-color" href="#" onclick="dopay(22); return false;">정기결제 <font color=blue>해지</font>하기</a><a class="wp-block-button__link has-background has-luminous-vivid-orange-background-color" href="#" onclick="dopay(21); return false;">이번달 결제 하기</a></div>
<? // } else { ?>
<div class="wp-block-button aligncenter"><font color=red>결제금액 : 19,800원/월</font>&nbsp;<a class="wp-block-button__link has-background has-luminous-vivid-orange-background-color" href="#" onclick="dopay(2); return false;">정기결제 구매하기</a>
</div>
< ?  }  ? */  -->






<!-- /wp:html -->

<!-- wp:separator -->
<hr class="wp-block-separator"/>
<!-- /wp:separator -->

<!-- wp:spacer {"height":40} -->
<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph -->
<p>▷<strong>1개월 사용&nbsp;</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>오늘부터 1달간 사용합니다. &nbsp;₩22,000/월 (1User)  <br /> 환불규정: 1개월사용 상품은 구매 후 환불이 되지 않습니다.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":65} -->
<div style="height:65px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:button {"backgroundColor":"luminous-vivid-orange","align":"center"} -->
<div class="wp-block-button aligncenter"><font color=red>결제금액 : 22,000원</font>&nbsp;
<a class="wp-block-button__link has-background has-luminous-vivid-orange-background-color" href="#" onclick="dopay(1); return false;">1달사용 구매하기</a></div>
<!-- /wp:button -->

<!-- wp:separator -->
<hr class="wp-block-separator"/>
<!-- /wp:separator -->

<!-- wp:spacer {"height":40} -->
<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph -->
<p>▷<strong>기간선택 사용&nbsp;(</strong>3, 6, 9, 12개월 선택) (무제한검색 요금제) </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>오늘부터 선택한 개월 동안 사용합니다. 선택 옵션에 따라 3, 6, 9, 12% 할인 및 검색 조회수 관계없이 무제한 서비스 적용됩니다. <br />환불규정: 사용중인 해당월은 구매 후 환불이 되지 않습니다. 나머지 기간에 대한 환불은 언제든지 요청하실 수 있습니다.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<br><div style="text-align: center;">
<input type="radio" name="duration" id="duration" onclick="chamt('64,020');" value="3">3개월&nbsp;
<input type="radio" name="duration" id="duration" onclick="chamt('124,080');" value="6">6개월&nbsp;
<input type="radio" name="duration" id="duration" onclick="chamt('180,180');" value="9">9개월&nbsp;
<input type="radio" name="duration" id="duration" onclick="chamt('232,320');" value="12" checked="checked">12개월&nbsp;
</div>

<!-- /wp:html -->

<!-- wp:spacer {"height":60} -->
<div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:button {"backgroundColor":"luminous-vivid-orange","align":"center"} -->
<div class="wp-block-button aligncenter"><span id=amt style='color:red'>결제금액 : 232,320원</span>&nbsp;<a class="wp-block-button__link has-background has-luminous-vivid-orange-background-color" href="#" onclick="dopay(3); return false;">기간선택 구매하기</a></div>
<!-- /wp:button -->

<!-- wp:separator -->
<hr class="wp-block-separator"/>
<!-- /wp:separator -->

<!-- wp:spacer {"height":20} -->
<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p style="text-align:center" class="has-large-font-size"><em>간편결제</em> </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":33} -->
<div style="height:33px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:spacer {"height":20} -->
<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:separator -->
<hr class="wp-block-separator"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"align":"center"} -->
<p style="text-align:center">필요 시 결제와 관련된 문의 메시지를 남겨주세요.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":20} -->
<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:button {"backgroundColor":"very-light-gray","textColor":"luminous-vivid-orange","align":"center"} -->
<div class="wp-block-button aligncenter"><a class="wp-block-button__link has-text-color has-luminous-vivid-orange-color has-background has-very-light-gray-background-color" href="https://<?=$svr?>/ulocawp/?page_id=1077">결제메시지 남기기</a></div>
<!-- /wp:button -->

<!-- wp:spacer {"height":20} -->
<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:separator -->
<hr class="wp-block-separator"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"align":"center"} -->
<p style="text-align:center">※ 세금계산서 발급은 사용자ID 및 성명을 기입하여 메일로 사업자등록증 사본을 보내주시거나, 세금계산서 발급요청 글을 남겨주세요. uloca@ulcoa.net, T. 070-8876-788</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":20} -->
<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:button {"backgroundColor":"very-light-gray","textColor":"luminous-vivid-orange","align":"center"} -->
<div class="wp-block-button aligncenter"><a class="wp-block-button__link has-text-color has-luminous-vivid-orange-color has-background has-very-light-gray-background-color" href="https://<?=$svr?>/ulocawp/?page_id=1077">세금계산 발급요청</a></div>
<!-- /wp:button -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->




<script>


</script>
</body>
</html>

<?
}
add_shortcode('payment','pay1_ShortCode');

?>