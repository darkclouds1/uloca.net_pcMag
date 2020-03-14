<div id="kboard-cross-link-latest">
	<div class="kboard-cross-link-list">
		<!-- 리스트 시작 -->
		<div class="kboard-list">
			<table>
				<thead>
					<tr>
						<td class="kboard-list-uid"><div class="right-line"><?php echo __('Number', 'kboard')?></div></td>
						<?php if($board->use_category && $board->initCategory1()):?>
								<td class="kboard-list-category1"><div class="right-line"><?php echo __('Category', 'kboard')?></div></td>
						<?php endif?>
						<td class="kboard-list-title"><div class="right-line"><?php echo __('Title', 'kboard')?></div></td>
						<?php if($board->use_category && $board->initCategory2()):?>
							<td class="kboard-list-category2"><div class="right-line"><?php echo __('Source', 'kboard-cross-link')?></div></td>
						<?php endif?>
						<td class="kboard-list-date"><div class="right-line"><?php echo __('Article Date', 'kboard-cross-link')?></div></td>
						<td class="kboard-list-shortcuts"><?php echo __('Shortcuts', 'kboard-cross-link')?></td>
					</tr>
				</thead>
				<tbody>
					<?php while($content = $list->hasNext()):?>
					<tr class="<?php if($content->uid == kboard_uid()):?>kboard-list-selected<?php endif?>">
						<td class="kboard-list-uid"><?php echo $list->index()?></td>
						<?php if($board->use_category):?>
							<td class="kboard-list-category1">
							<?php if($board->initCategory1() && $content->category1):?>
								<a href="<?php echo $url->set('category1', $content->category1)->set('pageid', '1')->set('target', '')->set('keyword', '')->set('mod', 'list')->toStringWithPath($board_url)?>" title="<?php echo $content->category1?>"><div class="category1-wrap"><?php echo $content->category1?></div></a>
							<?php endif?>
							</td>
						<?php endif?>
						<td class="kboard-list-title">
							<a href="<?php echo kboard_cross_link_print($content->option->link)?>" onclick="return kboard_cross_link_shortcut(this, '<?php echo $content->uid?>', '<?php echo $content->option->link_target?>')" title="<?php echo $content->title?>">
								<div class="kboard-cross-link-cut-strings">
									<?php if($content->isNew()):?><span class="kboard-cross-link-new-notify">New</span><?php endif?>
									<?php echo $content->title?>
								</div>
							</a>
							<?php if($content->isEditor() || $board->permission_write=='all'):?>
							<div class="separator">
								<span class="kboard-edit">
									<a href="<?php echo $url->set('uid', $content->uid)->set('mod', 'editor')->toStringWithPath($board_url)?>" title="<?php echo __('Edit Link', 'kboard-cross-link')?>"><?php echo __('Edit Link', 'kboard-cross-link')?></a>
								</span>
								<span class="kboard-remove">‧</span>
								<span class="kboard-remove">
									<a href="<?php echo $url->getContentRemove($content->uid)?>" class="" onclick="return confirm('<?php echo __('Are you sure you want to delete?', 'kboard')?>');" title="<?php echo __('Delete Link', 'kboard-cross-link')?>"><?php echo __('Delete Link', 'kboard-cross-link')?></a>
								</span>
							</div>
							<?php endif?>
							<div class="kboard-mobile-contents">
								<?php if($content->category2):?>
								<span class="contents-item kboard-category2"><a href="<?php echo $url->set('category2', $content->category2)->set('pageid', '1')->set('target', '')->set('keyword', '')->set('mod', 'list')->toStringWithPath($board_url)?>"><?php echo $content->category2?></a></span>
								<span class="contents-separator kboard-date">‧</span>
								<?php endif?>
								<span class="contents-item kboard-date"><?php echo $content->getDate()?></span>
								<?php if($content->isEditor() || $board->permission_write=='all'):?>
								<span class="contents-separator kboard-edit">‧</span>
								<span class="contents-item kboard-edit"><a href="<?php echo $url->set('uid', $content->uid)->set('mod', 'editor')->toStringWithPath($board_url)?>" title="<?php echo __('Edit Link', 'kboard-cross-link')?>"><?php echo __('Edit Link', 'kboard-cross-link')?></a></span>
								<span class="contents-separator kboard-remove">‧</span>
								<span class="contents-item kboard-remove"><a href="<?php echo $url->getContentRemove($content->uid)?>" onclick="return confirm('<?php echo __('Are you sure you want to delete?', 'kboard')?>');" <?php echo __('Delete Link', 'kboard-cross-link')?>><?php echo __('Delete Link', 'kboard-cross-link')?></a></span>
								<?php endif?>
							</div>
						</td>
						<?php if($board->use_category):?>
						<td class="kboard-list-category2">
							<?php if($board->initCategory2() && $content->category2):?>
								<a href="<?php echo $url->set('category2', $content->category2)->set('pageid', '1')->set('target', '')->set('keyword', '')->set('mod', 'list')->toStringWithPath($board_url)?>" title="<?php echo $content->category2?>"><?php echo $content->category2?></a>
							<?php endif?>
						</td>
						<?php endif?>
						<td class="kboard-list-date"><?php echo $content->getDate()?></td>
						<td class="kboard-list-shortcuts"><a href="<?php echo kboard_cross_link_print($content->option->link)?>" onclick="return kboard_cross_link_shortcut(this, '<?php echo $content->uid?>')" title="<?php echo __('Shortcuts', 'kboard-cross-link')?>"><img src="<?php echo $skin_path?>/images/icon-link.png" onmouseover="this.src='<?php echo $skin_path?>/images/icon-link-hover.png?>'" onmouseout="this.src='<?php echo $skin_path?>/images/icon-link.png'" alt="<?php echo __('Shortcuts', 'kboard-cross-link')?>"></a></td>
					</tr>
					<?php endwhile?>
				</tbody>
			</table>
		</div>
		<!-- 리스트 끝 -->
	</div>
</div>
<?php wp_enqueue_script('kboard-cross-link-list', "{$skin_path}/list.js", array(), KBOARD_CROSS_LINK_VERSION, true)?>