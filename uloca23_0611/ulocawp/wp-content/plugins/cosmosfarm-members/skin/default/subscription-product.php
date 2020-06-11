<div class="cosmosfarm-members-subscription cosmosfarm-members-subscription-product subscription-product-<?php echo $product->ID()?>">
	<h4 class="subscription-product-title"><?php echo $product->title()?></h4>
	<div class="subscription-description subscription-price"><?php echo cosmosfarm_members_currency_format($product->price())?></div>
	<div class="subscription-description subscription-type">
		<?php if($product->subscription_type() == 'onetime'):?>
			기간 무제한
		<?php else:?>
			<?php echo $product->subscription_type_format()?> / <?php echo $product->subscription_active() ? '이용기간 만료 후 자동결제' : '자동결제 없음'?>
		<?php endif?>
	</div>
	<div class="subscription-description subscription-content"><?php echo wpautop($product->content())?></div>
	
	<div class="subscription-button-wrap">
		<?php if($product->is_subscription_multiple_pay()):?>
			<button type="button" class="button-order" onclick="window.location.href='<?php echo $product->get_order_url()?>'">구매하기</button>
		<?php elseif($order = $product->is_in_use()):?>
			<?php if($order->subscription_next() == 'success'):?>
				<div><button type="button" class="button-order">사용중</button></div>
			<?php elseif($product->subscription_active()):?>
				<?php if($order->subscription_active()):?>
					<div><button type="button" class="button-order"><?php echo date('Y-m-d H:i', strtotime($order->end_datetime()))?> 이후 자동결제됩니다.</button></div>
					<div><button type="button" class="button-in-use" onclick="cosmosfarm_members_subscription_update(this, '<?php echo $order->ID()?>', '')">자동결제 중지</button></div>
				<?php else:?>
					<div><button type="button" class="button-order"><?php echo date('Y-m-d H:i', strtotime($order->end_datetime()))?> 이후 종료됩니다.</button></div>
					<div><button type="button" class="button-in-use" onclick="cosmosfarm_members_subscription_update(this, '<?php echo $order->ID()?>', '1')">자동결제 활성화</button></div>
				<?php endif?>
			<?php else:?>
				<div><button type="button" class="button-order">이용기간 <?php echo date('Y-m-d H:i', strtotime($order->end_datetime()))?> 까지</button></div>
			<?php endif?>
		<?php else:?>
			<button type="button" class="button-order" onclick="window.location.href='<?php echo $product->get_order_url()?>'">구매하기</button>
		<?php endif?>
	</div>
</div>