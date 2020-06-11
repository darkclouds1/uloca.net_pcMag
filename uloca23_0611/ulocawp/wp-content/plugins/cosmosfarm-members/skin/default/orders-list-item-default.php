<li class="orders-list-item item-type-<?php echo $item->get_type()?> item-status-<?php echo $item->get_status()?> item-post-id-<?php echo $item->ID?> item-more-area-hide">
	<div class="item-right-wrap">
		<div class="cosmosfarm-members-item-wrap">
			<div class="add-item-middot item-date"><?php echo $item->post_date?></div>
		</div>
		<div class="cosmosfarm-members-item-wrap">
			<?php if($item->post_title):?>
			<div class="item-title"><?php echo $item->post_title?></div>
			<?php endif?>
			
			<div class="item-content"><?php echo wpautop($item->post_content)?></div>
			
			<?php if(in_array($item->subscription_next(), array('success', 'wait'))):?>
				<?php if($product->subscription_type() != 'onetime' && $product->subscription_active()):?>
					<div class="item-content">
						<?php if($item->subscription_active()):?>
							<?php echo date('Y-m-d H:i', strtotime($item->end_datetime()))?> 이후 자동결제됩니다.
						<?php else:?>
							<?php echo date('Y-m-d H:i', strtotime($item->end_datetime()))?> 이후 종료됩니다.
						<?php endif?>
					</div>
				<?php endif?>
			<?php endif?>
		</div>
		<div class="cosmosfarm-members-item-wrap">
			<?php if(in_array($item->subscription_next(), array('success', 'wait'))):?>
				<?php if($product->subscription_type() != 'onetime' && $product->subscription_active()):?>
					<?php if($item->subscription_active()):?>
						<div class="add-item-middot"><a href="#" onclick="return cosmosfarm_members_subscription_update(this, '<?php echo $item->ID()?>', '')">자동결제 중지</a></div>
					<?php else:?>
						<div class="add-item-middot"><a href="#" onclick="return cosmosfarm_members_subscription_update(this, '<?php echo $item->ID()?>', '1')">자동결제 활성화</a></div>
					<?php endif?>
				<?php endif?>
			<?php endif?>
			
			<?php if($fields):?>
			<div class="add-item-middot"><a href="#" onclick="return cosmosfarm_members_orders_toggle(this, '<?php echo $item->ID?>');">더보기</a></div>
			<?php endif?>
		</div>
		<?php if($fields):?>
			<div class="cosmosfarm-members-item-more-area">
				<table>
				<?php
				foreach($fields as $key=>$field){
					$meta_value = get_post_meta($item->ID(), $field['meta_key']);
					
					echo '<tr>';
					
					echo '<td class="field-label">';
					echo $field['label'];
					echo '</td>';
					
					echo '<td class="field-value">';
					if(is_array($meta_value)){
						echo implode(', ', $meta_value);
					}
					else{
						echo $meta_value;
					}
					echo '</td>';
					
					echo '</tr>';
				}
				?>
				</table>
			</div>
		<?php endif?>
	</div>
</li>