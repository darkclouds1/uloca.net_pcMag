<?php
if($board->initCategory2()){
	$status_list = $board->category;
}
else{
	$status_list = kboard_ask_status();
}
?>
<div id="kboard-ask-one-latest">
	<table>
		<thead>
			<tr>
				<th class="kboard-latest-title"><?php echo __('Title', 'kboard')?></th>
				<th class="kboard-latest-status"><?php echo __('Status', 'kboard')?></th>
				<th class="kboard-latest-date"><?php echo __('Date', 'kboard')?></th>
			</tr>
		</thead>
		<tbody>
			<?php while($content = $list->hasNext()):?>
			<tr>
				<td class="kboard-latest-title">
					<a href="<?php echo $url->set('uid', $content->uid)->set('mod', 'document')->toStringWithPath($board_url)?>">
						<div class="kboard-ask-one-cut-strings">
							<?php if($content->isNew()):?><span class="kboard-ask-one-new-notify">N</span><?php endif?>
							<?php if($content->secret):?><img src="<?php echo $skin_path?>/images/icon-lock.png" class="kboard-icon-lock" alt="<?php echo __('Secret', 'kboard')?>"><?php endif?>
							<?php echo $content->title?>
							<span class="kboard-comments-count"><?php echo $content->getCommentsCount()?></span>
						</div>
					</a>
				</td>
				<td class="kboard-latest-status">
					<?php if($content->category2):?>
						<span class="kboard-ask-one-status status-<?php echo array_search($content->category2, $status_list)?>"><?php echo $content->category2?></span>
					<?php endif?>
				</td>
				<td class="kboard-latest-date"><?php echo $content->getDate()?></td>
			</tr>
			<?php endwhile?>
		</tbody>
	</table>
</div>