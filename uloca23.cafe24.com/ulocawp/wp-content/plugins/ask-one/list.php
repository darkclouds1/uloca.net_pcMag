<div id="kboard-ask-one-list">
	
	<!-- 게시판 정보 시작 -->
	<div class="kboard-list-header">
		<div class="kboard-left">
			<?php if($board->isWriter()):?>
				<a href="<?php echo $url->getContentEditor()?>" class="kboard-ask-one-button-small"><?php echo __('New', 'kboard')?></a>
			<?php endif?>
		</div>
		
		<div class="kboard-right">
			<?php if($board->use_category == 'yes'):?>
			<div class="kboard-category category-pc">
				<form id="kboard-category-form-<?php echo $board->id?>-pc" method="get" action="<?php echo $url->toString()?>">
					<?php echo $url->set('pageid', '1')->set('category1', '')->set('category2', '')->set('target', '')->set('keyword', '')->set('mod', 'list')->toInput()?>
					
					<?php if($board->initCategory1()):?>
						<select name="category1" onchange="jQuery('#kboard-category-form-<?php echo $board->id?>-pc').submit();">
							<option value=""><?php echo __('All', 'kboard')?></option>
							<?php while($board->hasNextCategory()):?>
							<option value="<?php echo $board->currentCategory()?>"<?php if(kboard_category1() == $board->currentCategory()):?> selected<?php endif?>><?php echo $board->currentCategory()?></option>
							<?php endwhile?>
						</select>
					<?php endif?>
					
					<?php if($board->initCategory2()):?>
						<select name="category2" onchange="jQuery('#kboard-category-form-<?php echo $board->id?>-pc').submit();">
							<option value=""><?php echo __('All', 'kboard')?></option>
							<?php while($board->hasNextCategory()):?>
							<option value="<?php echo $board->currentCategory()?>"<?php if(kboard_category2() == $board->currentCategory()):?> selected<?php endif?>><?php echo $board->currentCategory()?></option>
							<?php endwhile?>
						</select>
					<?php endif?>
				</form>
			</div>
			<?php endif?>
			<!--
			<form id="kboard-sort-form-<?php echo $board->id?>" method="get" action="<?php echo $url->toString()?>">
				<?php echo $url->set('pageid', '1')->set('category1', '')->set('category2', '')->set('target', '')->set('keyword', '')->set('mod', 'list')->set('kboard_list_sort_remember', $board->id)->toInput()?>
				
				<select name="kboard_list_sort" onchange="jQuery('#kboard-sort-form-<?php echo $board->id?>').submit();">
					<option value="newest"<?php if($list->getSorting() == 'newest'):?> selected<?php endif?>><?php echo __('Newest', 'kboard')?></option>
					<option value="best"<?php if($list->getSorting() == 'best'):?> selected<?php endif?>><?php echo __('Best', 'kboard')?></option>
					<option value="viewed"<?php if($list->getSorting() == 'viewed'):?> selected<?php endif?>><?php echo __('Viewed', 'kboard')?></option>
					<option value="updated"<?php if($list->getSorting() == 'updated'):?> selected<?php endif?>><?php echo __('Updated', 'kboard')?></option>
				</select>
			</form>
			-->
		</div>
	</div>
	<!-- 게시판 정보 끝 -->
	
	<?php if($board->use_category == 'yes'):?>
	<div class="kboard-category category-mobile">
		<form id="kboard-category-form-<?php echo $board->id?>-mobile" method="get" action="<?php echo $url->toString()?>">
			<?php echo $url->set('pageid', '1')->set('category1', '')->set('category2', '')->set('target', '')->set('keyword', '')->set('mod', 'list')->toInput()?>
			
			<?php if($board->initCategory1()):?>
				<select name="category1" onchange="jQuery('#kboard-category-form-<?php echo $board->id?>-mobile').submit();">
					<option value=""><?php echo __('All', 'kboard')?></option>
					<?php while($board->hasNextCategory()):?>
					<option value="<?php echo $board->currentCategory()?>"<?php if(kboard_category1() == $board->currentCategory()):?> selected<?php endif?>><?php echo $board->currentCategory()?></option>
					<?php endwhile?>
				</select>
			<?php endif?>
			
			<?php if($board->initCategory2()):?>
				<select name="category2" onchange="jQuery('#kboard-category-form-<?php echo $board->id?>-mobile').submit();">
					<option value=""><?php echo __('All', 'kboard')?></option>
					<?php while($board->hasNextCategory()):?>
					<option value="<?php echo $board->currentCategory()?>"<?php if(kboard_category2() == $board->currentCategory()):?> selected<?php endif?>><?php echo $board->currentCategory()?></option>
					<?php endwhile?>
				</select>
			<?php endif?>
		</form>
	</div>
	<?php endif?>
	
	<!-- 리스트 시작 -->
	<?php
	if($board->initCategory2()){
		$status_list = $board->category;
	}
	else{
		$status_list = kboard_ask_status();
	}
	?>
	<div class="kboard-list">
		<table>
			<thead>
				<tr>
					<td class="kboard-list-uid"><?php echo __('Number', 'kboard')?></td>
					
					<?php if($board->use_category == 'yes' && $board->initCategory1()):?>
						<td class="kboard-list-category"><?php echo __('Category', 'kboard')?></td>
					<?php endif?>
					
					<td class="kboard-list-title"><?php echo __('Title', 'kboard')?></td>
					<td class="kboard-list-status"><?php echo __('Status', 'kboard')?></td>
					<td class="kboard-list-user"><?php echo __('Author', 'kboard')?></td>
					<td class="kboard-list-date"><?php echo __('Date', 'kboard')?></td>
					<td class="kboard-list-vote"><?php echo __('Votes', 'kboard')?></td>
					<td class="kboard-list-view"><?php echo __('Views', 'kboard')?></td>
				</tr>
			</thead>
			<tbody>
				<?php while($content = $list->hasNextNotice()):?>
				<tr class="kboard-list-notice<?php if($content->uid == kboard_uid()):?> kboard-list-selected<?php endif?>">
					<td class="kboard-list-uid"><?php echo __('Notice', 'kboard')?></td>
					
					<?php if($board->use_category == 'yes' && $board->initCategory1()):?>
						<td class="kboard-list-category"><?php echo $content->category1?></td>
					<?php endif?>
					
					<td class="kboard-list-title">
						<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>">
							<?php if($content->category2):?>
								<div class="kboard-mobile-status">
									<span class="kboard-ask-one-status status-<?php echo array_search($content->category2, $status_list)?>"><?php echo $content->category2?></span>
								</div>
							<?php endif?>
							<div class="kboard-ask-one-cut-strings">
								<?php if($content->isNew()):?><span class="kboard-ask-one-new-notify">New</span><?php endif?>
								<?php if($content->secret):?><img src="<?php echo $skin_path?>/images/icon-lock.png" class="kboard-icon-lock" alt="<?php echo __('Secret', 'kboard')?>"><?php endif?>
								
								<?php if($board->use_category == 'yes' && $board->initCategory1()):?>
									<span class="kboard-mobile-category"><?php if($content->category1):?>[<?php echo $content->category1?>]<?php endif?></span>
								<?php endif?>
								
								<?php echo $content->title?>
								<span class="kboard-comments-count"><?php echo $content->getCommentsCount()?></span>
							</div>
							<div class="kboard-mobile-contents">
								<span class="contents-item"><?php echo $content->member_display?></span>
								<span class="contents-separator">|</span>
								<span class="contents-item"><?php echo $content->getDate()?></span>
								<!--
								<span class="contents-separator">|</span>
								<span class="contents-item"><?php echo __('Votes', 'kboard')?> <?php echo $content->vote?></span>
								<span class="contents-separator">|</span>
								<span class="contents-item"><?php echo __('Views', 'kboard')?> <?php echo $content->view?></span>
								-->
							</div>
						</a>
					</td>
					<td class="kboard-list-status">
						<?php if($content->category2):?>
							<span class="kboard-ask-one-status status-<?php echo array_search($content->category2, $status_list)?>"><?php echo $content->category2?></span>
						<?php endif?>
					</td>
					<td class="kboard-list-user"><?php echo $content->member_display?></td>
					<td class="kboard-list-date"><?php echo $content->getDate()?></td>
					<td class="kboard-list-vote"><?php echo $content->vote?></td>
					<td class="kboard-list-view"><?php echo $content->view?></td>
				</tr>
				<?php endwhile?>
				<?php while($content = $list->hasNext()):?>
				<tr class="<?php if($content->uid == kboard_uid()):?>kboard-list-selected<?php endif?>">
					<td class="kboard-list-uid"><?php echo $list->index()?></td>
					<?php if($board->use_category == 'yes' && $board->initCategory1()):?>
						<td class="kboard-list-category"><?php echo $content->category1?></td>
					<?php endif?>
					<td class="kboard-list-title">
						<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>">
							<?php if($content->category2):?>
								<div class="kboard-mobile-status">
									<span class="kboard-ask-one-status status-<?php echo array_search($content->category2, $status_list)?>"><?php echo $content->category2?></span>
								</div>
							<?php endif?>
							<div class="kboard-ask-one-cut-strings">
								<?php if($content->isNew()):?><span class="kboard-ask-one-new-notify">New</span><?php endif?>
								<?php if($content->secret):?><img src="<?php echo $skin_path?>/images/icon-lock.png" class="kboard-icon-lock" alt="<?php echo __('Secret', 'kboard')?>"><?php endif?>
								
								<?php if($board->use_category == 'yes' && $board->initCategory1()):?>
									<span class="kboard-mobile-category"><?php if($content->category1):?>[<?php echo $content->category1?>]<?php endif?></span>
								<?php endif?>
								
								<?php echo $content->title?>
								<span class="kboard-comments-count"><?php echo $content->getCommentsCount()?></span>
							</div>
							<div class="kboard-mobile-contents">
								<span class="contents-item"><?php echo $content->member_display?></span>
								<span class="contents-separator">|</span>
								<span class="contents-item"><?php echo $content->getDate()?></span>
								<!--
								<span class="contents-separator">|</span>
								<span class="contents-item"><?php echo __('Votes', 'kboard')?> <?php echo $content->vote?></span>
								<span class="contents-separator">|</span>
								<span class="contents-item"><?php echo __('Views', 'kboard')?> <?php echo $content->view?></span>
								-->
							</div>
						</a>
					</td>
					<td class="kboard-list-status">
						<?php if($content->category2):?>
							<span class="kboard-ask-one-status status-<?php echo array_search($content->category2, $status_list)?>"><?php echo $content->category2?></span>
						<?php endif?>
					</td>
					<td class="kboard-list-user"><?php echo $content->member_display?></td>
					<td class="kboard-list-date"><?php echo $content->getDate()?></td>
					<td class="kboard-list-vote"><?php echo $content->vote?></td>
					<td class="kboard-list-view"><?php echo $content->view?></td>
				</tr>
				<?php $boardBuilder->builderReply($content->uid)?>
				<?php endwhile?>
			</tbody>
		</table>
	</div>
	<!-- 리스트 끝 -->
	
	<!-- 페이징 시작 -->
	<div class="kboard-pagination">
		<ul class="kboard-pagination-pages">
			<?php echo kboard_pagination($list->page, $list->total, $list->rpp)?>
		</ul>
	</div>
	<!-- 페이징 끝 -->
	
	<!-- 검색폼 시작 -->
	<div class="kboard-search">
		<form id="kboard-search-form-<?php echo $board->id?>" method="get" action="<?php echo $url->toString()?>">
			<?php echo $url->set('pageid', '1')->set('target', '')->set('keyword', '')->set('mod', 'list')->toInput()?>
			
			<select name="target">
				<option value=""><?php echo __('All', 'kboard')?></option>
				<option value="title"<?php if(kboard_target() == 'title'):?> selected="selected"<?php endif?>><?php echo __('Title', 'kboard')?></option>
				<option value="content"<?php if(kboard_target() == 'content'):?> selected="selected"<?php endif?>><?php echo __('Content', 'kboard')?></option>
				<option value="member_display"<?php if(kboard_target() == 'member_display'):?> selected="selected"<?php endif?>><?php echo __('Author', 'kboard')?></option>
			</select>
			<input type="text" name="keyword" value="<?php echo kboard_keyword()?>">
			<button type="submit" class="kboard-ask-one-button-search" title="<?php echo __('Search', 'kboard')?>"><img src="<?php echo $skin_path?>/images/icon-search.png" alt="<?php echo __('Search', 'kboard')?>"></button>
		</form>
	</div>
	<!-- 검색폼 끝 -->
	
	<?php if($board->isWriter()):?>
	<div class="kboard-control">
		<a href="<?php echo $url->getContentEditor()?>" class="kboard-ask-one-button-small"><?php echo __('New', 'kboard')?></a>
	</div>
	<?php endif?>
	
	<?php if($board->contribution()):?>
	<div class="kboard-ask-one-poweredby">
		<a href="http://www.cosmosfarm.com/products/kboard" onclick="window.open(this.href);return false;" title="<?php echo __('KBoard is the best community software available for WordPress', 'kboard')?>">Powered by KBoard</a>
	</div>
	<?php endif?>
</div>