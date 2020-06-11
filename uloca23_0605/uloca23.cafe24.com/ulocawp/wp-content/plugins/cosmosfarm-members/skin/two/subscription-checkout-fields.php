<?php if(!defined('ABSPATH')) exit;
if($field_type == 'buyer_name'):?>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : '')?>"<?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'buyer_email'):?>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="email" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : '')?>"<?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'buyer_tel'):?>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : '')?>"<?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'text'):?>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : '')?>"<?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'number'):?>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="number" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : '')?>"<?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'select'):?>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<select id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>"<?php if($field['required']):?> required<?php endif?>>
			<?php
			if(isset($field['data'])):
				$user_meta_value = $field['user_meta_key'] ? $user->{$field['user_meta_key']} : '';
				$list = explode(',', $field['data']);
				$list = array_map('trim', $list);
				foreach($list as $value):
			?>
			<option value="<?php echo esc_attr($value)?>"<?php if($value == $user_meta_value):?> selected<?php endif?>><?php echo esc_html($value)?></option>
			<?php endforeach; endif;?>
		</select>
	</div>
</div>
<?php elseif($field_type == 'radio'):?>
<div class="checkout-attr-row">
	<label class="attr-name" for=""><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<?php
		if(isset($field['data'])):
			$user_meta_value = $field['user_meta_key'] ? $user->{$field['user_meta_key']} : '';
			$list = explode(',', $field['data']);
			$list = array_map('trim', $list);
			foreach($list as $value):
		?>
		<label><input type="radio" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($value)?>"<?php if($value == $user_meta_value):?> checked<?php endif?>> <?php echo esc_html($value)?></label>
		<?php endforeach; endif;?>
	</div>
</div>
<?php elseif($field_type == 'checkbox'):?>
<div class="checkout-attr-row">
	<label class="attr-name" for=""><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<?php
		if(isset($field['data'])):
			$user_meta_value = $field['user_meta_key'] ? $user->{$field['user_meta_key']} : '';
			$list = explode(',', $field['data']);
			$list = array_map('trim', $list);
			foreach($list as $value):
		?>
		<label><input type="checkbox" name="<?php echo esc_attr($field['meta_key'])?>[]" value="<?php echo esc_attr($value)?>"<?php if($value == $user_meta_value):?> checked<?php endif?>> <?php echo esc_html($value)?></label>
		<?php endforeach; endif;?>
	</div>
</div>
<?php elseif($field_type == 'zip'):?>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : '')?>"<?php if(get_cosmosfarm_members_locale() == 'ko_KR' && !$option->postcode_service_disabled):?> onclick="cosmosfarm_members_open_postcode('subscription_checkout')" readonly<?php endif?><?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field2['meta_key'])?>"><?php echo esc_html($field2['label'])?><?php if($field2['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field2['meta_key'])?>" name="<?php echo esc_attr($field2['meta_key'])?>" value="<?php echo esc_attr($field2['user_meta_key'] ? $user->{$field2['user_meta_key']} : '')?>"<?php if(get_cosmosfarm_members_locale() == 'ko_KR' && !$option->postcode_service_disabled):?> onclick="cosmosfarm_members_open_postcode('subscription_checkout')" readonly<?php endif?><?php if($field2['required']):?> required<?php endif?>>
	</div>
</div>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field3['meta_key'])?>"><?php echo esc_html($field3['label'])?><?php if($field3['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field3['meta_key'])?>" name="<?php echo esc_attr($field3['meta_key'])?>" value="<?php echo esc_attr($field3['user_meta_key'] ? $user->{$field3['user_meta_key']} : '')?>"<?php if($field3['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'textarea'):?>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<textarea id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" rows="5" name="<?php echo esc_attr($field['meta_key'])?>"<?php if($field['required']):?> required<?php endif?>><?php echo esc_html($field['user_meta_key'] ? $user->{$field['user_meta_key']} : '')?></textarea>
	</div>
</div>
<?php elseif($field_type == 'agree'):?>
<div class="checkout-attr-row">
	<div class="attr-value">
		<h5 class="agree-title"><?php echo esc_html($field['label'])?></h5>
		<div class="agree-content"><?php echo wpautop($field['data'])?></div>
		<label><input type="checkbox" name="agree" value="1" required> <?php echo sprintf(__('I agree to %s.', 'cosmosfarm-members'), esc_html($field['label']))?> <span class="required">*</span></label>
	</div>
</div>
<?php elseif($field_type == 'hr'):?>
<hr>
<?php elseif($field_type == 'hidden'):?>
<input type="hidden" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : '')?>">
<?php elseif($field_type == 'nice_billing'):?>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_card_number">신용카드번호 <span class="required">*</span></label>
	<div class="attr-value">
		<input type="number" id="cosmosfarm_members_subscription_checkout_card_number" name="cosmosfarm_members_subscription_checkout_card_number" value="" maxlength="16" size="16" required>
		<div class="description">신용카드번호 숫자만 입력해주세요.</div>
	</div>
</div>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_expiry">만료일 <span class="required">*</span></label>
	<div class="attr-value">
		<select id="cosmosfarm_members_subscription_checkout_expiry" name="cosmosfarm_members_subscription_checkout_expiry_month" class="width-auto" required>
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
		</select>
		월
		<select name="cosmosfarm_members_subscription_checkout_expiry_year" class="width-auto" required>
			<?php for($year=date('Y', current_time('timestamp')); $year<=(date('Y', current_time('timestamp'))+20); $year++):?>
			<option value="<?php echo $year?>"><?php echo $year?></option>
			<?php endfor?>
		</select>
		년
	</div>
</div>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_pwd_2digit">비밀번호 앞 2자리 <span class="required">*</span></label>
	<div class="attr-value">
		<input type="password" id="cosmosfarm_members_subscription_checkout_pwd_2digit" name="cosmosfarm_members_subscription_checkout_pwd_2digit" value="" maxlength="2" size="2" autocomplete="new-password" required>
		<div class="description">신용카드 비밀번호의 앞 2자리를 입력해주세요.</div>
	</div>
</div>
<div class="checkout-attr-row">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_birth_or_business_license">생년월일6자리 또는 사업자등록번호 <span class="required">*</span></label>
	<div class="attr-value">
		<input type="number" id="cosmosfarm_members_subscription_checkout_birth_or_business_license" name="cosmosfarm_members_subscription_checkout_birth" value="" maxlength="10" size="10" required>
		<div class="description">개인카드는 생년월일 6자리, 법인카드는 사업자등록번호 10자리를 입력해주세요.</div>
	</div>
</div>
<?php elseif($field_type == 'billing_agree'):?>
<div class="checkout-attr-row">
	<div class="attr-value">
		<label><input type="checkbox" name="agree" value="1" required> <?php echo __('I have confirmed my payment terms and I agree to proceed with the purchase.', 'cosmosfarm-members')?> <span class="required">*</span></label>
	</div>
</div>
<?php endif?>