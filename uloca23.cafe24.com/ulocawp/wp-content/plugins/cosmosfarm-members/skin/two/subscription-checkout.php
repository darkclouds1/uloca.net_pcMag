<div class="cosmosfarm-members-subscription cosmosfarm-members-subscription-checkout subscription-product-<?php echo $product->ID()?>">
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
	
	<hr>
	
	<?php if($product->is_in_use() && !$product->is_subscription_multiple_pay()):?>
		<?php echo wpmem_inc_regmessage('already_existing_order', '<p>1회만 구매할 수 있는 상품으로 기간 종료 후 다시 구매해주세요.</p>')?>
	<?php else:?>
		<form autocomplete="off" method="post" onsubmit="return cosmosfarm_members_subscription_pay(this)">
			<input type="hidden" name="security" value="">
			<input type="hidden" name="product_id" value="<?php echo intval($product->ID())?>">
			
			<div class="checkout-attr-group">
				<?php
				$fields = $product->fields();
				if($fields):
					$fields_count = count($fields);
					for($index=0; $index<$fields_count; $index++){
						if($fields[$index]['type'] == 'zip'){
							echo $skin->subscription_checkout_field_template($fields[$index++], $fields[$index++], $fields[$index]);
						}
						else{
							echo $skin->subscription_checkout_field_template($fields[$index]);
						}
					}
					?>
					<hr>
				<?php endif?>
				
				<?php if(!$option->subscription_pg_type && $option->subscription_pg == 'nice'):?>
					<?php echo $skin->subscription_checkout_field_template(array('type'=>'nice_billing'))?> 
					<hr>
				<?php endif?>
				
				<?php echo $skin->subscription_checkout_field_template(array('type'=>'billing_agree'))?>
			</div>
			
			<button type="submit"><?php echo cosmosfarm_members_currency_format($product->price())?> <?php echo __('Place order', 'cosmosfarm-members')?></button>
		</form>
	<?php endif?>
</div>