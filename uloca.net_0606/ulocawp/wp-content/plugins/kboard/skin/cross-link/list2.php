<?php while($content = $list->hasNext()):?>
<tr class="<?php if($content->uid == kboard_uid()):?>kboard-list-selected<?php endif?>">
	<td class="kboard-list-uid"><?php echo $list->index()?></td>
	<?php if($board->use_category && $board->initCategory1()):?>
		<td class="kboard-list-category1">
		<?php if($board->initCategory1() && $content->category1):?>
			<a href="<?php echo $url->set('category1', $content->category1)->set('pageid', '1')->set('target', '')->set('keyword', '')->set('mod', 'list')->toString()?>" title="<?php echo $content->category1?>"><div class="category1-wrap"><?php echo $content->category1?></div></a>
		<?php endif?>
		</td>
	<?php endif?>
	<td class="kboard-list-title">
		<?php if($content->option->link):?>
		<a href="<?php echo kboard_cross_link_print($content->option->link)?>" onclick="return kboard_cross_link_shortcut(this, '<?php echo $content->uid?>', '<?php echo $content->option->link_target?>')" title="<?php echo $content->title?>">
			<div class="kboard-cross-link-cut-strings">
				<?php if($content->isNew()):?><span class="kboard-cross-link-new-notify">New</span><?php endif?>
				<?php echo $content->title?>
			</div>
		</a>
		<?php else:?>
		<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>" title="<?php echo $content->title?>">
			<div class="kboard-cross-link-cut-strings">
				<?php if($content->isNew()):?><span class="kboard-cross-link-new-notify">New</span><?php endif?>
				<?php echo $content->title?>
			</div>
		</a>
		<?php endif?>
		<?php if($content->isEditor() || $board->permission_write=='all'):?>
		<div class="separator">
			<span class="kboard-edit">
				<a href="<?php echo $url->getContentEditor($content->uid)?>" title="<?php echo __('Edit Link', 'kboard-cross-link')?>"><?php echo __('Edit Link', 'kboard-cross-link')?></a>
			</span>
			<span class="kboard-remove">‧</span>
			<span class="kboard-remove">
				<a href="<?php echo $url->getContentRemove($content->uid)?>" class="" onclick="return confirm('<?php echo __('Are you sure you want to delete?', 'kboard')?>');" title="<?php echo __('Delete Link', 'kboard-cross-link')?>"><?php echo __('Delete Link', 'kboard-cross-link')?></a>
			</span>
		</div>
		<?php endif?>
		<div class="kboard-mobile-contents">
			<?php if($content->category2):?>
			<span class="contents-item kboard-category2"><a href="<?php echo $url->set('category2', $content->category2)->set('pageid', '1')->set('mod', 'list')->toString()?>" title="<?php echo $content->category2?>"><?php echo $content->category2?></a></span>
			<span class="contents-separator kboard-date">‧</span>
			<?php endif?>
			<span class="contents-item kboard-date"><?php echo $content->getDate()?></span>
			<?php if($content->isEditor() || $board->permission_write=='all'):?>
			<span class="contents-separator kboard-edit">‧</span>
			<span class="contents-item kboard-edit"><a href="<?php echo $url->getContentEditor($content->uid)?>" title="<?php echo __('Edit Link', 'kboard-cross-link')?>"><?php echo __('Edit Link', 'kboard-cross-link')?></a></span>
			<span class="contents-separator kboard-remove">‧</span>
			<span class="contents-item kboard-remove"><a href="<?php echo $url->getContentRemove($content->uid)?>" onclick="return confirm('<?php echo __('Are you sure you want to delete?', 'kboard')?>');" title="<?php echo __('Delete Link', 'kboard-cross-link')?>"><?php echo __('Delete Link', 'kboard-cross-link')?></a></span>
			<?php endif?>
		</div>
	</td>
	<?php if($board->use_category && $board->initCategory2()):?>
	<td class="kboard-list-category2">
		<?php if($board->initCategory2() && $content->category2):?>
			<a href="<?php echo $url->set('category2', $content->category2)->set('pageid', '1')->set('mod', 'list')->toString()?>" title="<?php echo $content->category2?>"><?php echo $content->category2?></a>
		<?php endif?>
	</td>
	<?php endif?>
	<td class="kboard-list-date"><?php echo $content->getDate()?></td>
	<td class="kboard-list-shortcuts">
		<?php if($content->option->link):?>
		<a href="<?php echo kboard_cross_link_print($content->option->link)?>" onclick="return kboard_cross_link_shortcut(this, '<?php echo $content->uid?>', '<?php echo $content->option->link_target?>')" title="<?php echo __('Shortcuts', 'kboard-cross-link')?>">
			<img src="<?php echo $skin_path?>/images/icon-link.png" onmouseover="this.src='<?php echo $skin_path?>/images/icon-link-hover.png?>'" onmouseout="this.src='<?php echo $skin_path?>/images/icon-link.png'" alt="<?php echo __('Shortcuts', 'kboard-cross-link')?>">
		</a>
		<?php else:?>
		<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>" title="<?php echo __('Shortcuts', 'kboard-cross-link')?>">
			<img src="<?php echo $skin_path?>/images/icon-link.png" onmouseover="this.src='<?php echo $skin_path?>/images/icon-link-hover.png?>'" onmouseout="this.src='<?php echo $skin_path?>/images/icon-link.png'" alt="<?php echo __('Shortcuts', 'kboard-cross-link')?>">
		</a>
		<?php endif?>
	</td>
</tr>
<?php endwhile?>