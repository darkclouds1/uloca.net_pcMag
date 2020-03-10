<?php if(!defined('ABSPATH')) exit;
if($field_type == 'buyer_name'):?>
<div class="ui-state-default">
	<h4>주문자명</h4>
	
	<input type="hidden" name="product_field[type][]" value="buyer_name">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="buyer_name">
	<p>
	라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : '주문자명'?>" placeholder="필드 이름" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
	입력
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php elseif($field_type == 'buyer_email'):?>
<div class="ui-state-default">
	<h4>주문자 이메일</h4>
	
	<input type="hidden" name="product_field[type][]" value="buyer_email">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="buyer_email">
	<p>
	라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : '주문자 이메일'?>" placeholder="필드 이름" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : 'user_email'?>" placeholder="사용자 정보 자동 입력">
	입력
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php elseif($field_type == 'buyer_tel'):?>
<div class="ui-state-default">
	<h4>주문자 전화번호</h4>
	
	<input type="hidden" name="product_field[type][]" value="buyer_tel">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="buyer_tel">
	<p>
	라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : '주문자 전화번호'?>" placeholder="필드 이름" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
	입력
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php elseif($field_type == 'text'):?>
<div class="ui-state-default">
	<h4>텍스트</h4>
	
	<input type="hidden" name="product_field[type][]" value="text">
	<input type="hidden" name="product_field[data][]" value="">
	<p>
	라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
	Meta Key
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
	입력
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php elseif($field_type == 'number'):?>
<div class="ui-state-default">
	<h4>숫자</h4>
	
	<input type="hidden" name="product_field[type][]" value="number">
	<input type="hidden" name="product_field[data][]" value="">
	<p>
	라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
	Meta Key
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
	입력
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php elseif($field_type == 'select'):?>
<div class="ui-state-default">
	<h4>셀렉트</h4>
	
	<input type="hidden" name="product_field[type][]" value="select">
	<p>
	라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
	Meta Key
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
	데이터
	<input type="text" name="product_field[data][]" value="<?php echo isset($field['data']) ? $field['data'] : ''?>" placeholder="콤마(,)로 구분" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
	입력
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php elseif($field_type == 'radio'):?>
<div class="ui-state-default">
	<h4>라디오</h4>
	
	<input type="hidden" name="product_field[type][]" value="radio">
	<p>
	라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
	Meta Key
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
	데이터
	<input type="text" name="product_field[data][]" value="<?php echo isset($field['data']) ? $field['data'] : ''?>" placeholder="콤마(,)로 구분" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
	입력
	<select name="product_field[required][]">
		<option value="">생략가능</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php elseif($field_type == 'checkbox'):?>
<div class="ui-state-default">
	<h4>체크박스</h4>
	
	<input type="hidden" name="product_field[type][]" value="checkbox">
	<p>
	라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
	Meta Key
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
	데이터
	<input type="text" name="product_field[data][]" value="<?php echo isset($field['data']) ? $field['data'] : ''?>" placeholder="콤마(,)로 구분" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
	입력
	<select name="product_field[required][]">
		<option value="">생략가능</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php elseif($field_type == 'zip'):?>
<div class="ui-state-default">
	<h4>주소</h4>
	
	<input type="hidden" name="product_field[type][]" value="zip">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="zip">
	<p>
	우편번호 라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : '우편번호'?>" placeholder="필드 이름" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : 'zip'?>" placeholder="사용자 정보 자동 입력">
	입력
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<input type="hidden" name="product_field[type][]" value="addr1">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="addr1">
	<p>
	주소 라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field2['label']) ? $field2['label'] : '주소'?>" placeholder="필드 이름" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field2['user_meta_key']) ? $field2['user_meta_key'] : 'addr1'?>" placeholder="사용자 정보 자동 입력">
	입력
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field2['required']) && $field2['required']):?> selected<?php endif?>>필수</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<input type="hidden" name="product_field[type][]" value="addr2">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="addr2">
	<p>
	상세주소 라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field3['label']) ? $field3['label'] : '상세주소'?>" placeholder="필드 이름" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field3['user_meta_key']) ? $field3['user_meta_key'] : 'addr2'?>" placeholder="사용자 정보 자동 입력">
	입력
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field3['required']) && $field3['required']):?> selected<?php endif?>>필수</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php elseif($field_type == 'textarea'):?>
<div class="ui-state-default">
	<h4>텍스트에어리어</h4>
	
	<input type="hidden" name="product_field[type][]" value="textarea">
	<input type="hidden" name="product_field[data][]" value="">
	<p>
	라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
	Meta Key
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
	입력
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php elseif($field_type == 'agree'):?>
<div class="ui-state-default">
	<h4>동의하기</h4>
	
	<input type="hidden" name="product_field[type][]" value="agree">
	<input type="hidden" name="product_field[meta_key][]" value="">
	<input type="hidden" name="product_field[user_meta_key][]" value="">
	<p>
	라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
	입력
	<select name="product_field[required][]">
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
	</select>
	</p>
	<p>
	<textarea class="regular-text" name="product_field[data][]" rows="5" placeholder="동의하기 내용 입력"><?php echo isset($field['data']) ? $field['data'] : ''?></textarea>
	</p>
	
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php elseif($field_type == 'hr'):?>
<div class="ui-state-default">
	<h4>구분선</h4>
	<input type="hidden" name="product_field[type][]" value="hr">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="">
	<input type="hidden" name="product_field[label][]" value="">
	<input type="hidden" name="product_field[user_meta_key][]" value="">
	<input type="hidden" name="product_field[required][]" value="">
	<input type="hidden" name="product_field[order_view][]" value="">
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php elseif($field_type == 'hidden'):?>
<div class="ui-state-default">
	<h4>숨김필드</h4>
	
	<input type="hidden" name="product_field[type][]" value="hidden">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[required][]" value="">
	<p>
	라벨
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
	Meta Key
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
	User Meta Key
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	</p>
	
	<p><button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button></p>
	<hr>
</div>
<?php endif?>