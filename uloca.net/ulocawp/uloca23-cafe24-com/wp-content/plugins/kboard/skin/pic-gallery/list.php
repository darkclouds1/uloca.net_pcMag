<div id="kboard-pic-gallery-list">
	<div class="kboard-header">
		<!-- 카테고리 시작 -->
		<?php
		if($board->use_category == 'yes'){
			if($board->isTreeCategoryActive()){
				$category_type = 'tree-select';
			}
			else{
				$category_type = 'default';
			}
			$category_type = apply_filters('kboard_skin_category_type', $category_type, $board, $boardBuilder);
			echo $skin->load($board->skin, "list-category-{$category_type}.php", $vars);
		}
		?>
		<!-- 카테고리 끝 -->
	</div>
	
	<!-- 리스트 시작 -->
	<ul class="kboard-list">
	<?php while($content = $list->hasNext()):?>
	<li class="kboard-list-item">
		<div class="kboard-item-wrap">
			<p class="kboard-item-title cut_strings"><a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>#kboard-pic-gallery-document"><?php echo $content->title?></a></p>
			<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>#kboard-pic-gallery-document" class="kboard-item-thumbnail">
				<?php if($content->getThumbnail(270, 177)):?>
					<img src="<?php echo $content->getThumbnail(270, 177)?>" alt="">
				<?php else:?>
					<i class="icon-picture"></i>
				<?php endif?>
			</a>
			<div class="kboard-item-description">
				<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>#kboard-pic-gallery-document" class="kboard-item-avatar">
					<?php if($content->member_uid):?>
						<span title="<?php echo apply_filters('kboard_user_display', $content->member_display, $content->member_uid, $content->member_display, 'kboard', $boardBuilder)?>"><?php echo get_avatar($content->member_uid, 45, '', $content->member_display)?></span>
					<?php else:?>
						<span title="<?php echo apply_filters('kboard_user_display', $content->member_display, $content->member_uid, $content->member_display, 'kboard', $boardBuilder)?>"><img src="<?php echo $skin_path?>/images/default-avatar.png" alt="<?php echo apply_filters('kboard_user_display', $content->member_display, $content->member_uid, $content->member_display, 'kboard', $boardBuilder)?>"></span>
					<?php endif?>
					<img src="<?php echo $skin_path?>/images/avatar-mask.png" alt="" class="kboard-item-avatar-mask">
				</a>
				<p class="kboard-item-user">by <span><?php echo apply_filters('kboard_user_display', $content->member_display, $content->member_uid, $content->member_display, 'kboard', $boardBuilder)?></span></p>
			</div>
			<div class="kboard-item-info">
				<span class="kboard-item-info-views"><?php echo $content->view?></span>
				<span class="kboard-item-info-comments"><?php echo $content->getCommentsCount()?></span>
				<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>#kboard-pic-gallery-document" class="kboard-item-info-chain"></a>
			</div>
		</div>
	</li>
	<?php endwhile?>
	</ul>
	<!-- 리스트 끝 -->
	
	<!-- 페이징 시작 -->
	<div class="kboard-pagination">
		<ul class="kboard-pagination-pages">
			<?php echo kboard_pagination($list->page, $list->total, $list->rpp)?>
		</ul>
	</div>
	<!-- 페이징 끝 -->
	
	<form id="kboard-search-form" method="get" action="<?php echo $url->toString()?>">
		<?php echo $url->set('pageid', '1')->set('target', '')->set('keyword', '')->set('mod', 'list')->toInput()?>

		<div class="kboard-search">
			<select name="target">
				<option value=""><?php echo __('All', 'kboard')?></option>
				<option value="title"<?php if(kboard_target() == 'title'):?> selected<?php endif?>><?php echo __('Title', 'kboard')?></option>
				<option value="content"<?php if(kboard_target() == 'content'):?> selected<?php endif?>><?php echo __('Content', 'kboard')?></option>
				<option value="member_display"<?php if(kboard_target() == 'member_display'):?> selected<?php endif?>><?php echo __('Author', 'kboard')?></option>
			</select>
			<input type="text" name="keyword" value="<?php echo kboard_keyword()?>" placeholder="<?php echo __('Search', 'kboard')?>...">
			<button type="submit" class="kboard-pic-gallery-button-small"><?php echo __('Search', 'kboard')?></button>
		</div>
	</form>
	
	<?php if($board->isWriter()):?>
	<!-- 버튼 시작 -->
	<div class="kboard-control">
		<a href="<?php echo $url->set('mod', 'editor')->toString()?>" class="kboard-pic-gallery-button-small"><?php echo __('New', 'kboard')?></a>
	</div>
	<!-- 버튼 끝 -->
	<?php endif?>
	
	<?php if($board->contribution()):?>
	<div class="kboard-pic-gallery-poweredby">
		<a href="https://www.cosmosfarm.com/products/kboard" onclick="window.open(this.href);return false;" title="<?php echo __('KBoard is the best community software available for WordPress', 'kboard')?>">Powered by KBoard</a>
	</div>
	<?php endif?>
</div>