<?php
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
if($action == 'kboard_cross_link_more_view'):
	// 리스트 레이아웃을 불러온다.
	if(isset($_GET['current_page']) && $_GET['current_page'] == 'admin'){
		include 'list3.php';
	}
	else{
		include 'list2.php';
	}
else:
?>
<div id="kboard-cross-link-list">
	<input type="hidden" name="kboard_cross_link_page" value="<?php echo $list->page?>">
	<input type="hidden" name="kboard_cross_link_category1" value="<?php echo $list->category1?>">
	<input type="hidden" name="kboard_cross_link_category2" value="<?php echo $list->category2?>">
	<input type="hidden" name="kboard_cross_link_current_page" value="<?php echo is_admin() ? 'admin' : ''?>">
	<input type="hidden" name="kboard_cross_link_latest_board_url" value="<?php echo $_SERVER['REQUEST_URI']?>">
	
	<div class="kboard-cross-link-list">
		<!-- 게시판 정보 시작 -->
		<div class="kboard-list-header">
			
			<!-- 버튼 시작 -->
			<div class="kboard-control">
				<div class="kboard-sort">
					<form id="kboard-sort-form-<?php echo $board->id?>" method="get" action="<?php echo $url->toString()?>">
						<?php echo $url->set('pageid', '1')->set('category1', '')->set('category2', '')->set('target', '')->set('keyword', '')->set('mod', 'list')->set('kboard_list_sort_remember', $board->id)->toInput()?>
						<select name="kboard_list_sort" onchange="jQuery('#kboard-sort-form-<?php echo $board->id?>').submit();">
							<option value="newest"<?php if($list->getSorting() == 'newest'):?> selected<?php endif?>><?php echo __('Newest', 'kboard')?></option>
							<option value="viewed"<?php if($list->getSorting() == 'viewed'):?> selected<?php endif?>><?php echo __('Viewed', 'kboard')?></option>
							<option value="updated"<?php if($list->getSorting() == 'updated'):?> selected<?php endif?>><?php echo __('Updated', 'kboard')?></option>
						</select>
					</form>
				</div>
				<?php if($board->isWriter()):?>
					<a href="<?php echo $url->getContentEditor()?>" class="kboard-cross-link-button-small" title="<?php echo __('Register Link', 'kboard-cross-link')?>"><?php echo __('Register Link', 'kboard-cross-link')?></a>
				<?php endif?>	
				<a href="<?php echo $url->set('mod', 'list')->set('category1', '')->set('category2', '')->set('keyword', '')->set('target', '')->set('pageid', '')->toString()?>" class="kboard-cross-link-button-small" title="<?php echo __('All List', 'kboard-cross-link')?>"><?php echo __('All List', 'kboard-cross-link')?></a>
			</div>
			<!-- 버튼 끝 -->
			
			<!-- 검색폼 시작 -->
			<div class="kboard-search">
				<form id="kboard-search-form-<?php echo $board->id?>" method="get" action="<?php echo $url->toString()?>">
					<?php echo $url->set('pageid', '1')->set('target', '')->set('keyword', '')->set('mod', 'list')->toInput()?>
					<select name="target">
						<option value="title"<?php if(kboard_target() == 'title'):?> selected<?php endif?>><?php echo __('Title', 'kboard')?></option>
						<?php if($board->use_category && $board->initCategory1()):?>
						<option value="category1"<?php if(kboard_target() == 'category1'):?> selected<?php endif?>><?php echo __('category1', 'kboard-cross-link')?></option>
						<?php endif?>
						<?php if($board->use_category && $board->initCategory2()):?>
						<option value="category2"<?php if(kboard_target() == 'category2'):?> selected<?php endif?>><?php echo __('category2', 'kboard-cross-link')?></option>
						<?php endif?>
					</select>
					<input type="text" name="keyword" value="<?php echo kboard_keyword()?>" required>
					<input type="image" class="magnifier" src="<?php echo $skin_path?>/images/icon-search.png" title="<?php echo __('Search', 'kboard-cross-link')?>" alt="<?php echo __('Search', 'kboard-cross-link')?>">
				</form>
			</div>
			<!-- 검색폼 끝 -->
			
		</div>
		<!-- 게시판 정보 끝 -->
		
		<!-- 리스트 시작 -->
		<div class="kboard-list">
			<table>
				<thead>
					<tr>
						<td class="kboard-list-uid"><div class="right-line"><?php echo __('Number', 'kboard')?></div></td>
						<?php if($board->use_category && $board->initCategory1()):?>
						<td class="kboard-list-category1">
							<div class="right-line"><?php echo __('Category', 'kboard')?></div>
						</td>
						<?php endif?>
						<td class="kboard-list-title"><div class="right-line"><?php echo __('Title', 'kboard')?></div></td>
						<?php if($board->use_category && $board->initCategory2()):?>
						<td class="kboard-list-category2">
							<div class="right-line"><?php echo __('Source', 'kboard-cross-link')?></div>
						</td>
						<?php endif?>
						<td class="kboard-list-date"><div class="right-line"><?php echo __('Date', 'kboard-cross-link')?></div></td>
						<td class="kboard-list-shortcuts"><?php echo __('Link', 'kboard-cross-link')?></td>
					</tr>
				</thead>
				<tbody>
					<?php while($content = $list->hasNextNotice()):?>
					<tr class="top-fixing<?php if($content->uid == kboard_uid()):?> kboard-list-selected<?php endif?>">
						<td class="kboard-list-uid">※</td>
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
								<span class="contents-item kboard-category2"><a href="<?php echo $url->set('category2', $content->category2)->set('pageid', '1')->set('target', '')->set('keyword', '')->set('mod', 'list')->toString()?>" title="<?php echo $content->category2?>"><?php echo $content->category2?></a></span>
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
								<a href="<?php echo $url->set('category2', $content->category2)->set('pageid', '1')->set('target', '')->set('keyword', '')->set('mod', 'list')->toString()?>" title="<?php echo $content->category2?>"><?php echo $content->category2?></a>
							<?php endif?>
						</td>
						<?php endif?>
						<td class="kboard-list-date"><?php echo $content->getDate()?></td>
						<td class="kboard-list-shortcuts">
							<?php if($content->option->link):?>
							<a href="<?php echo kboard_cross_link_print($content->option->link)?>" onclick="return kboard_cross_link_shortcut(this, '<?php echo $content->uid?>', '<?php echo $content->option->link_target?>')" title="<?php echo __('Link', 'kboard-cross-link')?>">
								<img src="<?php echo $skin_path?>/images/icon-link.png" onmouseover="this.src='<?php echo $skin_path?>/images/icon-link-hover.png?>'" onmouseout="this.src='<?php echo $skin_path?>/images/icon-link.png'" alt="">
							</a>
							<?php else:?>
							<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>" title="<?php echo __('Link', 'kboard-cross-link')?>">
								<img src="<?php echo $skin_path?>/images/icon-link.png" onmouseover="this.src='<?php echo $skin_path?>/images/icon-link-hover.png?>'" onmouseout="this.src='<?php echo $skin_path?>/images/icon-link.png'" alt="">
							</a>
							<?php endif?>
						</td>
					</tr>
					<?php endwhile?>
					
					<?php
					// 리스트 레이아웃을 불러온다.
					if(is_admin()){
						include 'list3.php';
					}
					else{
						include 'list2.php';
					}
					?>
				</tbody>
			</table>
		</div>
		<!-- 리스트 끝 -->
		
		<!-- 페이징 시작 -->
		<div class="kboard-pagination">
			<button class="kboard-cross-link-button-small" onclick="return kboard_cross_link_more_view(this, '<?php echo $board->id?>')" title="<?php echo __('View More', 'kboard-cross-link')?>"><?php echo __('View More', 'kboard-cross-link')?></button>
		</div>
		<!-- 페이징 끝 -->

		<div class="kboard-cross-link-poweredby">
			<a href="https://www.cosmosfarm.com/products/kboard" onclick="window.open(this.href);return false;" title="<?php echo __('KBoard is the best community software available for WordPress', 'kboard')?>">Powered by KBoard</a>
		</div>
	</div>
</div>
<?php wp_enqueue_script('kboard-cross-link-list', "{$skin_path}/list.js", array(), KBOARD_CROSS_LINK_VERSION, true)?>
<?php endif?>