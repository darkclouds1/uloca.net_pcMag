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
		<?php wp_nonce_field('cosmosfarm-members-subscription-save', 'cosmosfarm-members-subscription-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_subscription_save">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_policy_privacy">아임포트</label></th>
					<td>
						<p class="description">아임포트는 국내외 주요 PG사와의 연동을 지원합니다.<br>
코스모스팜은 아임포트 서비스와 연동해 쉽고 편리하게 정기결제 기능을 제공합니다.<br>
실제 정기결제 기능을 사용하기 위해서 아임포트와 PG사 가입이 필요합니다.<br>
PG사 가입은 아임포트에 문의해주세요. <a href="http://www.iamport.kr/" onclick="window.open(this.href);return false;">http://www.iamport.kr/</a><br>
아임포트에 로그인 후 <a href="https://admin.iamport.kr/settings" onclick="window.open(this.href);return false;">시스템설정</a>에 있는 정보를 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_iamport_id">가맹점 식별코드</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_iamport_id" name="cosmosfarm_members_iamport_id" class="regular-text" value="<?php echo $option->iamport_id?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_iamport_api_key">REST API 키</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_iamport_api_key" name="cosmosfarm_members_iamport_api_key" class="regular-text" value="<?php echo $option->iamport_api_key?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_iamport_api_secret">REST API secret</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_iamport_api_secret" name="cosmosfarm_members_iamport_api_secret" class="regular-text" value="<?php echo $option->iamport_api_secret?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_pg_type">결제방식(Beta)</label></th>
					<td>
						<select id="cosmosfarm_members_subscription_pg_type" name="cosmosfarm_members_subscription_pg_type">
							<option value="">빌링결제</option>
							<option value="general"<?php if($option->subscription_pg_type == 'general'):?> selected<?php endif?>>일반결제 (실험적인 기능)</option>
						</select>
						<p class="description">정기결제 기능을 사용하기 위해서는 빌링결제를 선택해주세요.</p>
						<p class="description"><a href="https://blog.cosmosfarm.com/?p=170" onclick="window.open(this.href);return false;">PG 가입시 일반결제와 빌링결제 차이 알아보기</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_pg">빌링결제 PG사</label></th>
					<td>
						<select id="cosmosfarm_members_subscription_pg" name="cosmosfarm_members_subscription_pg">
							<option value="">사용안함</option>
							<option value="nice"<?php if($option->subscription_pg == 'nice'):?> selected<?php endif?>>나이스정보통신</option>
							<option value="jtnet"<?php if($option->subscription_pg == 'jtnet'):?> selected<?php endif?>>JTNet(tPay)</option>
							<option value="html5_inicis"<?php if($option->subscription_pg == 'html5_inicis'):?> selected<?php endif?>>KG이니시스(웹표준결제창)</option>
							<option value="kcp_billing"<?php if($option->subscription_pg == 'kcp_billing'):?> selected<?php endif?>>NHN KCP 빌링결제(엔에이치엔한국사이버결제)</option>
							<option value="kakao"<?php if($option->subscription_pg == 'kakao'):?> selected<?php endif?>>[간편결제] 카카오페이</option>
						</select>
						<p class="description">사전에 가입된 PG사를 선택해주세요.</p>
						<p class="description">나이스정보통신을 선택하면 보안 프로그램 설치 없이 PC, 맥, 모바일에서 결제 진행이 가능합니다.</p>
						<p class="description"><a href="https://docs.iamport.kr/admin/test-mode" onclick="window.open(this.href);return false;">정기결제 테스트모드 설정 방법</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_general_pg">일반결제 PG사</label></th>
					<td>
						<select id="cosmosfarm_members_subscription_general_pg" name="cosmosfarm_members_subscription_general_pg">
							<option value="">사용안함</option>
							<option value="html5_inicis"<?php if($option->subscription_general_pg == 'html5_inicis'):?> selected<?php endif?>>KG이니시스(웹표준결제창)</option>
							<option value="kcp"<?php if($option->subscription_general_pg == 'kcp'):?> selected<?php endif?>>NHN KCP(엔에이치엔한국사이버결제)</option>
							<option value="nice"<?php if($option->subscription_general_pg == 'nice'):?> selected<?php endif?>>나이스정보통신</option>
							<option value="jtnet"<?php if($option->subscription_general_pg == 'jtnet'):?> selected<?php endif?>>JTNet(tPay)</option>
							<option value="uplus"<?php if($option->subscription_general_pg == 'uplus'):?> selected<?php endif?>>LGU+</option>
							<option value="paypal"<?php if($option->subscription_general_pg == 'paypal'):?> selected<?php endif?>>페이팔-Express Checkout</option>
						</select>
						<p class="description">사전에 가입된 PG사를 선택해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<p class="description">※ 반드시 서버에 SSL인증서가 설치되어 있고 항상 https로 접속되어야 합니다. 홈페이지를 https로 접속할 수 없다면 정기결제 기능을 사용해선 안됩니다.</p>
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
					<th scope="row"><label for="cosmosfarm_members_subscription_checkout_page_id">정기결제 주문 페이지</label></th>
					<td>
						<select id="cosmosfarm_members_subscription_checkout_page_id" name="cosmosfarm_members_subscription_checkout_page_id">
							<option value="">사용안함</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->subscription_checkout_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<p class="description"><code>[cosmosfarm_members_subscription_checkout]</code> 숏코드가 삽입된 페이지를 선택해주세요.</p>
						<p class="description">다른 페이지와 겹치지 않게 새로운 페이지를 만들어주세요.</p>
						<p class="description">레이아웃은 <code>/wp-content/plugins/cosmosfarm-members/skin/사용중인스킨/subscription-checkout.php</code> 파일을 편집해주세요.</p>
						<p class="description">또는 테마에 <code>/wp-content/themes/사용중인테마/cosmosfarm-members/subscription-checkout.php</code> 파일을 추가해서 편집해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_orders_page_id">주문 목록 페이지</label></th>
					<td>
						<select id="cosmosfarm_members_subscription_orders_page_id" name="cosmosfarm_members_subscription_orders_page_id">
							<option value="">사용안함</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->subscription_orders_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<p class="description"><code>[cosmosfarm_members_orders]</code> 숏코드가 삽입된 페이지를 선택해주세요.</p>
						<p class="description">다른 페이지와 겹치지 않게 새로운 페이지를 만들어주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<p class="description">※ 반드시 서버에 SSL인증서가 설치되어 있고 항상 https로 접속되어야 합니다. 홈페이지를 https로 접속할 수 없다면 정기결제 기능을 사용해선 안됩니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<ul class="cosmosfarm-members-news-list">
							<?php
							foreach(get_cosmosfarm_members_news_list() as $news_item):?>
							<li>
								<a href="<?php echo esc_url($news_item->url)?>" target="<?php echo esc_attr($news_item->target)?>" style="text-decoration:none"><?php echo esc_html($news_item->title)?></a>
							</li>
							<?php endforeach?>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="변경 사항 저장">
		</p>
	</form>
</div>
<div class="clear"></div>