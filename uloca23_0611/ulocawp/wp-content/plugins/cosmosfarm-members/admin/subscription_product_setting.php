<?php if(!defined('ABSPATH')) exit;?>
<div class="wrap">
	<div style="float:left;margin:7px 8px 0 0;width:36px;height:34px;background:url(<?php echo COSMOSFARM_MEMBERS_URL . '/images/icon-big.png'?>) left top no-repeat;"></div>
	<h1>
		코스모스팜 회원관리
		<a href="https://www.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">홈페이지</a>
		<a href="https://www.cosmosfarm.com/threads" class="page-title-action" onclick="window.open(this.href);return false;">커뮤니티</a>
		<a href="https://www.cosmosfarm.com/support" class="page-title-action" onclick="window.open(this.href);return false;">고객지원</a>
		<a href="https://blog.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">블로그</a>
	</h1>
	<p>코스모스팜 회원관리는 한국형 회원가입 레이아웃과 기능을 제공합니다.</p>
	
	<hr>
	
	<form method="post" action="<?php echo admin_url('admin-post.php')?>">
		<?php wp_nonce_field('cosmosfarm-members-product-save', 'cosmosfarm-members-product-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_product_save">
		<input type="hidden" name="product_id" value="<?php echo $product->ID()?>">
		<table class="form-table">
			<tbody>
				<?php if($product->ID()):?>
				<tr valign="top">
					<th scope="row"><label for="product_title">숏코드</label></th>
					<td>
						<code>[cosmosfarm_members_subscription_product id="<?php echo $product->ID()?>"]</code>
						<p class="description">숏코드를 페이지 혹은 글에 삽입해주세요.</p>
						<p class="description">레이아웃은 <code>/wp-content/plugins/cosmosfarm-members/skin/사용중인스킨/subscription-product.php</code> 파일을 편집해주세요.</p>
						<p class="description">또는 테마에 <code>/wp-content/themes/사용중인테마/cosmosfarm-members/subscription-product.php</code> 파일을 추가해서 편집해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="product_title">결제 페이지 주소</label></th>
					<td>
						<code><?php echo $product->get_order_url_without_redirect()?></code>
						<p class="description">상품의 결제 페이지로 연결되는 주소입니다.</p>
						<p class="description">특별한 경우가 아니라면 숏코드를 사용해주세요.</p>
					</td>
				</tr>
				<?php endif?>
				<tr valign="top">
					<th scope="row"><label for="product_title">상품 이름</label></th>
					<td>
						<input type="text" id="product_title" name="product_title" class="regular-text" value="<?php echo $product->title()?>" required>
						<p class="description">상품의 이름을 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="product_content">설명</label></th>
					<td>
						<?php echo wp_editor($product->content(), 'product_content', array('editor_height'=>200))?>
						<p class="description">상품의 설명을 간략하게 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="product_price">가격</label></th>
					<td>
						<input type="number" id="product_price" name="product_price" value="<?php echo $product->price()?>" required>원
						<p class="description">0원 이상의 가격을 입력해주세요. 특수문자 제외하고 숫자만 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="product_first_price">첫 결제 가격</label></th>
					<td>
						<input type="number" id="product_first_price" name="product_first_price" value="<?php echo intval($product->first_price)?>">원
						<p class="description">정기결제 상품에만 적용됩니다.</p>
						<p class="description">0원일 경우 기본 가격으로 결제됩니다.</p>
						<p class="description">첫 결제시 비용을 받지 않으시려면 <label for="product_subscription_first_free" style="font-weight:bold">첫 결제 무료 이용기간</label>설정을 사용해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="product_subscription_type">이용기간</label></th>
					<td>
						<select id="product_subscription_type" name="product_subscription_type">
							<option value="onetime"<?php if($product->subscription_type() == 'onetime'):?> selected<?php endif?>>제한없음</option>
							<option value="daily"<?php if($product->subscription_type() == 'daily'):?> selected<?php endif?>>1일</option>
							<option value="weekly"<?php if($product->subscription_type() == 'weekly'):?> selected<?php endif?>>1주일</option>
							<option value="monthly"<?php if($product->subscription_type() == 'monthly'):?> selected<?php endif?>>1개월</option>
							<option value="2monthly"<?php if($product->subscription_type() == '2monthly'):?> selected<?php endif?>>2개월</option>
							<option value="3monthly"<?php if($product->subscription_type() == '3monthly'):?> selected<?php endif?>>3개월</option>
							<option value="4monthly"<?php if($product->subscription_type() == '4monthly'):?> selected<?php endif?>>4개월</option>
							<option value="5monthly"<?php if($product->subscription_type() == '5monthly'):?> selected<?php endif?>>5개월</option>
							<option value="6monthly"<?php if($product->subscription_type() == '6monthly'):?> selected<?php endif?>>6개월</option>
							<option value="7monthly"<?php if($product->subscription_type() == '7monthly'):?> selected<?php endif?>>7개월</option>
							<option value="8monthly"<?php if($product->subscription_type() == '8monthly'):?> selected<?php endif?>>8개월</option>
							<option value="9monthly"<?php if($product->subscription_type() == '9monthly'):?> selected<?php endif?>>9개월</option>
							<option value="10monthly"<?php if($product->subscription_type() == '10monthly'):?> selected<?php endif?>>10개월</option>
							<option value="11monthly"<?php if($product->subscription_type() == '11monthly'):?> selected<?php endif?>>11개월</option>
							<option value="12monthly"<?php if($product->subscription_type() == '12monthly'):?> selected<?php endif?>>1년</option>
						</select>
						<p class="description">멜론, 유튜브 프리미엄 등과 같은 정기구독 서비스, 정기배송 등 결제 후 다음 결제가 필요한 때까지의 기간입니다.</p>
						<p class="description">이용기간이 제한없음일 경우 자동 결제가 실행되지 않고 계속 상태가 유지됩니다.</p>
						<p class="description">최대 이용기간은 PG사 계약에 따라서 달라집니다. PG사와 협의 후 설정해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="product_subscription_active">정기결제</label></th>
					<td>
						<select id="product_subscription_active" name="product_subscription_active">
							<option value="">자동 결제 없음</option>
							<option value="1"<?php if($product->subscription_active()):?> selected<?php endif?>>이용기간 만료 후 자동 결제</option>
						</select>
						<p class="description"><label for="product_subscription_type" style="font-weight:bold">이용기간</label>이 제한없음일 경우 자동 결제가 실행되지 않습니다.</p>
						<p class="description">※ 반드시 빌링결제를 사용할 수 있는 경우에만 사용해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="product_pay_count_limit">정기결제 만료 횟수</label></th>
					<td>
						<select id="product_pay_count_limit" name="product_pay_count_limit">
							<option value="">자동 만료 없음</option>
							<option value="2"<?php if($product->pay_count_limit() == 2):?> selected<?php endif?>>2회</option>
							<option value="3"<?php if($product->pay_count_limit() == 3):?> selected<?php endif?>>3회</option>
							<option value="4"<?php if($product->pay_count_limit() == 4):?> selected<?php endif?>>4회</option>
							<option value="5"<?php if($product->pay_count_limit() == 5):?> selected<?php endif?>>5회</option>
							<option value="6"<?php if($product->pay_count_limit() == 6):?> selected<?php endif?>>6회</option>
							<option value="7"<?php if($product->pay_count_limit() == 7):?> selected<?php endif?>>7회</option>
							<option value="8"<?php if($product->pay_count_limit() == 8):?> selected<?php endif?>>8회</option>
							<option value="9"<?php if($product->pay_count_limit() == 9):?> selected<?php endif?>>9회</option>
							<option value="10"<?php if($product->pay_count_limit() == 10):?> selected<?php endif?>>10회</option>
							<option value="11"<?php if($product->pay_count_limit() == 11):?> selected<?php endif?>>11회</option>
							<option value="12"<?php if($product->pay_count_limit() == 12):?> selected<?php endif?>>12회</option>
							<option value="13"<?php if($product->pay_count_limit() == 13):?> selected<?php endif?>>13회</option>
							<option value="14"<?php if($product->pay_count_limit() == 14):?> selected<?php endif?>>14회</option>
							<option value="15"<?php if($product->pay_count_limit() == 15):?> selected<?php endif?>>15회</option>
							<option value="16"<?php if($product->pay_count_limit() == 16):?> selected<?php endif?>>16회</option>
							<option value="17"<?php if($product->pay_count_limit() == 17):?> selected<?php endif?>>17회</option>
							<option value="18"<?php if($product->pay_count_limit() == 18):?> selected<?php endif?>>18회</option>
							<option value="19"<?php if($product->pay_count_limit() == 19):?> selected<?php endif?>>19회</option>
							<option value="20"<?php if($product->pay_count_limit() == 20):?> selected<?php endif?>>20회</option>
							<option value="21"<?php if($product->pay_count_limit() == 21):?> selected<?php endif?>>21회</option>
							<option value="22"<?php if($product->pay_count_limit() == 22):?> selected<?php endif?>>22회</option>
							<option value="23"<?php if($product->pay_count_limit() == 23):?> selected<?php endif?>>23회</option>
							<option value="24"<?php if($product->pay_count_limit() == 24):?> selected<?php endif?>>24회</option>
							<option value="25"<?php if($product->pay_count_limit() == 25):?> selected<?php endif?>>25회</option>
							<option value="26"<?php if($product->pay_count_limit() == 26):?> selected<?php endif?>>26회</option>
							<option value="27"<?php if($product->pay_count_limit() == 27):?> selected<?php endif?>>27회</option>
							<option value="28"<?php if($product->pay_count_limit() == 28):?> selected<?php endif?>>28회</option>
							<option value="29"<?php if($product->pay_count_limit() == 29):?> selected<?php endif?>>29회</option>
							<option value="30"<?php if($product->pay_count_limit() == 30):?> selected<?php endif?>>30회</option>
							<option value="31"<?php if($product->pay_count_limit() == 31):?> selected<?php endif?>>31회</option>
							<option value="32"<?php if($product->pay_count_limit() == 32):?> selected<?php endif?>>32회</option>
							<option value="33"<?php if($product->pay_count_limit() == 33):?> selected<?php endif?>>33회</option>
							<option value="34"<?php if($product->pay_count_limit() == 34):?> selected<?php endif?>>34회</option>
							<option value="35"<?php if($product->pay_count_limit() == 35):?> selected<?php endif?>>35회</option>
							<option value="36"<?php if($product->pay_count_limit() == 36):?> selected<?php endif?>>36회</option>
						</select>
						
						<p class="description"><label for="product_subscription_active" style="font-weight:bold">정기결제</label>가 이용기간 만료 후 자동 결제인 경우에 적용됩니다.</p>
						<p class="description">첫 결제를 포함해서 모든 결제 횟수가 설정된 횟수가 될때 까지만 정기결제가 실행됩니다.</p>
						<p class="description">만료 횟수가 2회일 경우 "첫 결제 + 정기결제 1회" 까지만 결제되고 중단됩니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="product_subscription_first_free">첫 결제 무료 이용기간</label></th>
					<td>
						<select id="product_subscription_first_free" name="product_subscription_first_free">
							<option value="">무료 이용기간 없음</option>
							<option value="1day"<?php if($product->subscription_first_free() == '1day'):?> selected<?php endif?>>1일</option>
							<option value="2day"<?php if($product->subscription_first_free() == '2day'):?> selected<?php endif?>>2일</option>
							<option value="3day"<?php if($product->subscription_first_free() == '3day'):?> selected<?php endif?>>3일</option>
							<option value="4day"<?php if($product->subscription_first_free() == '4day'):?> selected<?php endif?>>4일</option>
							<option value="5day"<?php if($product->subscription_first_free() == '5day'):?> selected<?php endif?>>5일</option>
							<option value="6day"<?php if($product->subscription_first_free() == '6day'):?> selected<?php endif?>>6일</option>
							<option value="7day"<?php if($product->subscription_first_free() == '7day'):?> selected<?php endif?>>7일</option>
							<option value="8day"<?php if($product->subscription_first_free() == '8day'):?> selected<?php endif?>>8일</option>
							<option value="9day"<?php if($product->subscription_first_free() == '9day'):?> selected<?php endif?>>9일</option>
							<option value="10day"<?php if($product->subscription_first_free() == '10day'):?> selected<?php endif?>>10일</option>
							<option value="15day"<?php if($product->subscription_first_free() == '15day'):?> selected<?php endif?>>15일</option>
							<option value="20day"<?php if($product->subscription_first_free() == '20day'):?> selected<?php endif?>>20일</option>
							<option value="1month"<?php if($product->subscription_first_free() == '1month'):?> selected<?php endif?>>1개월</option>
							<option value="2month"<?php if($product->subscription_first_free() == '2month'):?> selected<?php endif?>>2개월</option>
							<option value="3month"<?php if($product->subscription_first_free() == '3month'):?> selected<?php endif?>>3개월</option>
						</select>
						<p class="description">실제 사용 가능한 카드인지 확인하기 위해서 실제로 결제 후 성공시 다시 결제를 취소하게 됩니다.</p>
						<p class="description"><label for="product_subscription_type" style="font-weight:bold">이용기간</label>이 제한없음 또는 <label for="product_subscription_active" style="font-weight:bold">정기결제</label>가 자동 결제 없음일 경우에는 적용되지 않습니다.</p>
						<p class="description">사용자의 동일 상품의 과거 결제 내역이 존재하면 적용되지 않습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="product_subscription_again_price_type">정기결제 기준 가격</label></th>
					<td>
						<select id="product_subscription_again_price_type" name="product_subscription_again_price_type">
							<option value="">상품에 설정된 가격으로 결제</option>
							<option value="old_order"<?php if($product->subscription_again_price_type() == 'old_order'):?> selected<?php endif?>>이전 결제된 가격으로 결제</option>
						</select>
						<p class="description">정기결제 상품의 가격이 바뀔 때 기존 사용자의 경우 정기결제 가격을 유지할 때 사용됩니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="product_subscription_role">사용자 역할(Role)</label></th>
					<td>
						<select id="product_subscription_role" name="product_subscription_role">
							<option value="">역할 변경 없음</option>
							<?php foreach(get_editable_roles() as $key=>$value): if($key == 'administrator') continue;?>
							<option value="<?php echo $key?>"<?php if($product->subscription_role() == $key):?> selected<?php endif?>><?php echo _x($value['name'], 'User role')?></option>
							<?php endforeach?>
						</select>
						<p class="description">이용기간 동안 사용자의 역할(Role)을 변경합니다. 역할(Role) 관리는 <a href="https://wordpress.org/plugins/user-role-editor/" onclick="window.open(this.href);return false;">User Role Editor</a> 플러그인으로 가능합니다.</p>
						<p class="description">역할(Role) 변경은 다른 상품 혹은 다른 기능과 충돌에 유의해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="product_subscription_multiple_pay">여러번 결제 가능</label></th>
					<td>
						<select id="product_subscription_multiple_pay" name="product_subscription_multiple_pay">
							<option value="">비활성화</option>
							<option value="1"<?php if($product->subscription_multiple_pay() == '1'):?> selected<?php endif?>>활성화</option>
						</select>
						<p class="description">사용자 역할(Role) 변경이 없을 경우에만 활성화됩니다.</p>
						<p class="description">상품의 성격에 따라서 선택해주세요.</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="변경 사항 저장">
		</p>
		
		<hr>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_checkout_page_id">결제 필드</label></th>
					<td>
						<div class="cosmosfarm-members-fields-wrap">
							<?php
							$fields = $product->fields();
							$fields_count = count($fields);
							for($index=0; $index<$fields_count; $index++){
								if($fields[$index]['type'] == 'zip'){
									echo $product->get_admin_field_template($fields[$index++], $fields[$index++], $fields[$index]);
								}
								else{
									echo $product->get_admin_field_template($fields[$index]);
								}
							}
							?>
						</div>
						<p class="description">
							<select name="new_field_type">
								<?php foreach($product->get_admin_field_template_list() as $key=>$value):?>
								<option value="<?php echo $key?>"><?php echo $value?></option>
								<?php endforeach?>
							</select>
							<button type="button" class="button" onclick="cosmosfarm_members_product_field_new()">새로운 필드 추가</button>
						</p>
						<p class="description">라벨은 필드의 이름입니다.</p>
						<p class="description">Meta Key는 DB저장시 사용되는 필드의 고유한 키값으로 공백(스페이스)와 특수문자는 입력할 수 없으며 영문 소문자, 언더바(_)만 사용이 가능합니다.</p>
						<p class="description">데이터는 셀렉트, 라디오, 체크박스에서 사용됩니다. 콤마(,)로 값을 구분합니다.</p>
						<p class="description">User Meta Key는 사용자 정보를 자동으로 입력하기 위해서 사용됩니다.</p>
						<hr>
						<p class="description">결제 필드의 레이아웃은 <code>/wp-content/plugins/cosmosfarm-members/skin/사용중인스킨/subscription-checkout-fields.php</code> 파일을 편집해주세요.</p>
						<p class="description">또는 테마에 <code>/wp-content/themes/사용중인테마/cosmosfarm-members/subscription-checkout-fields.php</code> 파일을 추가해서 편집해주세요.</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="변경 사항 저장">
		</p>
	</form>
	
	<ul class="cosmosfarm-members-news-list">
		<?php
		foreach(get_cosmosfarm_members_news_list() as $news_item):?>
		<li>
			<a href="<?php echo esc_url($news_item->url)?>" target="<?php echo esc_attr($news_item->target)?>" style="text-decoration:none"><?php echo esc_html($news_item->title)?></a>
		</li>
		<?php endforeach?>
	</ul>
</div>
<div class="clear"></div>

<?php foreach($product->get_admin_field_template_list() as $key=>$value):?>
<div class="cosmosfarm-members-field-<?php echo $key?>" style="display:none">
	<?php echo $product->get_admin_field_template(array('type'=>$key))?>
</div>
<?php endforeach?>

<script>
var cosmosfarm_members_product_field;
jQuery(function(){
	cosmosfarm_members_product_field = jQuery('.cosmosfarm-members-fields-wrap').find('.ui-state-default').last().clone();
	jQuery('.cosmosfarm-members-fields-wrap').sortable();
	jQuery('.cosmosfarm-members-fields-wrap').disableSelection();
});
function cosmosfarm_members_product_field_new(){
	var new_field_type = jQuery('select[name=new_field_type]').val();
	jQuery('.cosmosfarm-members-fields-wrap').append(jQuery('.cosmosfarm-members-field-'+new_field_type).html());
}
function cosmosfarm_members_product_field_delete(obj){
	jQuery(obj).parent().parent().remove();
}
</script>