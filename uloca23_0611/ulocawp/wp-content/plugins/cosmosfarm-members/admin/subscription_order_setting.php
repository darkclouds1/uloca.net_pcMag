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
		<?php wp_nonce_field('cosmosfarm-members-order-save', 'cosmosfarm-members-order-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_order_save">
		<input type="hidden" name="order_id" value="<?php echo $order->ID()?>">
		<table class="form-table subscription-order">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="order_post_date">날짜</label></th>
					<td>
						<?php echo $order->post_date?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_title">상품</label></th>
					<td>
						<?php echo $order->title()?>
						<p><a class="button" href="<?php echo admin_url("admin.php?page=cosmosfarm_subscription_product&product_id=".$order->product_id())?>">상품편집</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_title">사용자</label></th>
					<td>
						<?php echo $user->display_name?>(<?php echo $user->user_login?>)
						<p><a class="button" href="mailto:<?php echo $user->user_email?>" target="_blank" title="이메일 보내기"><?php echo $user->user_email?></a> <a class="button" href="<?php echo admin_url("user-edit.php?user_id={$user->ID}")?>">사용자 편집</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_title">주문자명</label></th>
					<td>
						<?php echo $order->buyer_name?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_title">이메일</label></th>
					<td>
						<?php if($order->buyer_email):?><a href="mailto:<?php echo $order->buyer_email?>" target="_blank" title="이메일 보내기"><?php echo $order->buyer_email?></a><?php endif?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_title">전화번호</label></th>
					<td>
						<?php echo $order->buyer_tel?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_price">가격</label></th>
					<td>
						<?php echo cosmosfarm_members_currency_format($order->price())?>
						<p><a class="button" href="<?php echo $order->receipt_url?>" target="_blank" title="영수증">영수증</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_status">결제 상태</label></th>
					<td>
						<select name="order_status">
							<option value="paid"<?php if($order->status() == 'paid'):?> selected<?php endif?>>결제됨</option>
							<option value="cancelled"<?php if($order->status() == 'cancelled'):?> selected<?php endif?>>취소됨</option>
						</select>
						<p class="description">결제 상태를 바꿔도 금액은 환불되지 않기 때문에 특별한 경우가 아니라면 임의로 변경하지 마세요.</p>
						<p class="description">실제 금액을 환불하고 주문을 취소하시려면 '환불하기' 버튼을 눌러주세요.</p>
						<p class="description">아임포트에서 환불된 주문이라서 '환불하기'가 동작하지 않는다면 만료일을 현재 시간으로 변경하세요.</p>
						<p><button type="button" class="button" onclick="cosmosfarm_members_order_cancel('<?php echo $order->ID()?>')">환불하기</button></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_subscription_type">이용기간</label></th>
					<td>
						<?php if($order->subscription_type() == 'onetime'):?>계속사용<?php endif?>
						<?php if($order->subscription_type() == 'daily'):?>1일<?php endif?>
						<?php if($order->subscription_type() == 'weekly'):?>1주일<?php endif?>
						<?php if($order->subscription_type() == 'monthly'):?>1개월<?php endif?>
					</td>
				</tr>
				<?php if($order->subscription_type() != 'onetime'):?>
				<tr valign="top">
					<th scope="row"><label for="order_subscription_active">정기결제</label></th>
					<td>
						<select id="order_subscription_active" name="order_subscription_active">
							<option value="">자동 결제 없음</option>
							<option value="1"<?php if($order->subscription_active()):?> selected<?php endif?>>이용기간 만료 후 자동 결제</option>
						</select>
						<p class="description">결제 상태가 '결제됨' 상태일 경우에 동작합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_subscription_next">정기결제 상태</label></th>
					<td>
						<span class="subscription-next-<?php echo $order->subscription_next()?>"><?php echo $order->subscription_next_format()?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_start_datetime">시작일</label></th>
					<td>
						<?php if($order->end_datetime()):?>
						<?php echo date('Y-m-d H:i', strtotime($order->start_datetime()))?>
						<?php else:?>
						제한없음
						<?php endif?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_end_datetime">만료일</label></th>
					<td>
						<?php if($order->end_datetime()):?>
						<input type="text" name="order_end_year" size="4" maxlength="4" value="<?php echo date('Y', strtotime($order->end_datetime()))?>">년
						<input type="text" name="order_end_month" size="2" maxlength="2" value="<?php echo date('m', strtotime($order->end_datetime()))?>">월
						<input type="text" name="order_end_day" size="2" maxlength="2" value="<?php echo date('d', strtotime($order->end_datetime()))?>">일
						<input type="text" name="order_end_hour" size="2" maxlength="2" value="<?php echo date('H', strtotime($order->end_datetime()))?>">시
						<input type="text" name="order_end_minute" size="2" maxlength="2" value="<?php echo date('i', strtotime($order->end_datetime()))?>">분
						<p class="description">만료일을 현재시간 혹은 이전 시간으로 변경하면 정기결제 상태가 만료됩니다.</p>
						<p class="description">정기결제 상태가 만료될 경우 자동 결제를 사용하고 있다면 자동으로 결제되고 연장됩니다.</p>
						<?php else:?>
						제한없음
						<?php endif?>
					</td>
				</tr>
				<?php endif?>
				<tr valign="top">
					<th scope="row"><label for="order_subscription_role">사용자 역할(Role)</label></th>
					<td>
						<?php if($order->subscription_role()):?>
						<?php echo _x(wp_roles()->roles[$order->subscription_prev_role()]['name'], 'User role')?>
						→
						<?php echo _x(wp_roles()->roles[$order->subscription_role()]['name'], 'User role')?>
						<?php else:?>
						역할 변경 없음
						<?php endif?>
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
				<?php
				$fields = $product->fields();
				$fields_count = count($fields);
				for($index=0; $index<$fields_count; $index++){
					if($fields[$index]['type'] == 'hr') continue;
					if($fields[$index]['type'] == 'zip'){
						echo $order->get_order_field_template($fields[$index++], $fields[$index++], $fields[$index]);
					}
					else{
						echo $order->get_order_field_template($fields[$index]);
					}
				}
				?>
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