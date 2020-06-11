<?php if(!defined('ABSPATH')) exit;?>
<!DOCTYPE html>
<html <?php language_attributes()?>>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title>SMS 보내기</title>
	<?php $wp_scripts->print_scripts('jquery')?>
	<style>
	html, body { margin: 0; padding: 0; width: 1px; min-width: 100%; *width: 100%; background-color: white; overflow: hidden; }
	form { margin: 0; padding: 0; }
	label { display: block; width: 100%; color: #7c7c7c; }
	input { margin: 0; padding: 6px; width: 100%; border: 0; background-color: #f7f7f7; box-sizing: border-box; }
	textarea { margin: 0; padding: 6px; width: 100%; border: 0; background-color: #f7f7f7; box-sizing: border-box; }
	button { padding: 6px 12px;; border: #2e71f2; background-color: #2e71f2; color: white; cursor: pointer; }
	#cosmosfarm-members-sms-form { margin: 0 auto; padding: 5px 5px 0 5px; }
	.row { margin: 0 0 5px 0; }
	.center { text-align: center; }
	.message { font-size: 13px; }
	</style>
</head>
<body>
<div id="cosmosfarm-members-sms-form">
	<form method="post" action="<?php echo admin_url('admin-post.php')?>">
		<?php wp_nonce_field('cosmosfarm-members-sms-send', 'cosmosfarm-members-sms-send-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_sms_send">
		
		<?php if($user && $user->ID):?>
		<input type="hidden" name="user_id" value="<?php echo $user->ID?>">
		<div class="row">
			<label>받는사람</label>
			<?php echo $user->display_name?>(<?php echo $user->user_login?>)
			<br>
			<select name="meta" onchange="kboard_phone_update(this.value)">
				<option value="">등록된 번호 선택</option>
				<?php
				$all_meta_for_user = get_user_meta($user->ID);
				foreach($all_meta_for_user as $key=>$value):
				if(strpos($key, 'tel') === false && strpos($key, 'phone') === false && strpos($key, 'fax') === false) continue;
					if(is_array($value)) $value = reset($value);
					if(!$value) continue;
					?>
					<option value="<?php echo $value?>"><?php echo $key?>(<?php echo $value?>)</option>
				<?php endforeach?>
			</select>
		</div>
		<?php endif?>
		
		<div class="row">
			<label>휴대폰번호*</label>
			<input name="phone" placeholder="휴대폰번호를 입력해주세요." autofocus required>
		</div>
		
		<div class="row">
			<label>내용*</label>
			<textarea name="content" rows="10" placeholder="내용을 입력해주세요." required></textarea>
		</div>
		
		<div class="row">
			<label class="message">현재 길이는 <span class="total-length">0</span>byte 입니다.<br>
			90byte가 넘으면 내용이 짤리거나 LMS로 전송될 수 있으니 90byte 이내로 작성해주세요.<br>
			단문(SMS) : 최대 90byte까지 전송할 수 있으며, 잔여건수 1건이 차감됩니다.<br>
			장문(LMS) : 한번에 최대 2,000byte까지 전송할 수 있으며 1회 발송당 잔여건수 3건이 차감됩니다.</label>
		</div>
		
		<div class="row center controlbar">
			<button type="submit" class="button">전송</button>
		</div>
	</form>
</div>
<script>
String.prototype.bytes = function(){
	var msg = this;
	var cnt = 0;
	for(var i=0; i<msg.length; i++){
		cnt += (msg.charCodeAt(i) > 128) ? 2 : 1;
	}
	return cnt;
}
jQuery(document).ready(function(res){
	jQuery('[name=content]').keyup(function(e){
		jQuery('.total-length').text(jQuery(this).val().bytes());
	});
	jQuery('.total-length').text(jQuery('[name=content]').val().bytes());
});
function kboard_phone_update(phone){
	jQuery('input[name=phone]').val(phone);
}
</script>
</body>
</html>