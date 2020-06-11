<div id="kboard-document">
	<div id="kboard-ask-one-document">
		<div class="kboard-document-wrap" itemscope itemtype="http://schema.org/Article">
			<meta itemprop="name" content="<?php echo kboard_htmlclear(strip_tags($content->title))?>">
			
			<div class="kboard-detail">
				<div class="detail-attr detail-title">
					<div class="detail-name"><?php echo __('Title', 'kboard')?></div>
					<div class="detail-value"><?php echo $content->title?></div>
				</div>
				<?php if($content->category1):?>
				<div class="detail-attr detail-category1">
					<div class="detail-name"><?php echo $content->category1?></div>
				</div>
				<?php endif?>
				<div class="detail-attr detail-writer">
					<div class="detail-name"><?php echo __('Author', 'kboard')?></div>
					<div class="detail-value"><?php echo apply_filters('kboard_user_display', $content->member_display, $content->member_uid, $content->member_display, 'kboard', $boardBuilder)?></div>
				</div>
				<div class="detail-attr detail-date">
					<div class="detail-name"><?php echo __('Date', 'kboard')?></div>
					<div class="detail-value"><?php echo date('Y-m-d H:i', strtotime($content->date))?></div>
				</div>
				<div class="detail-attr detail-view">
					<div class="detail-name"><?php echo __('Views', 'kboard')?></div>
					<div class="detail-value"><?php echo $content->view?></div>
				</div>
				<?php
				if(!$board->initCategory2()){
					$board->category = kboard_ask_status();
				}
				?>
				<?php if($board->isAdmin()):?>
				<div class="detail-attr detail-category2">
					<div class="detail-name">
						<select id="kboard-select-category2" name="category2" onchange="kboard_ask_one_category_update('<?php echo $content->uid?>', this.value)">
							<option value="">상태없음</option>
							<?php while($board->hasNextCategory()):?>
							<option value="<?php echo $board->currentCategory()?>"<?php if($content->category2 == $board->currentCategory()):?> selected<?php endif?>><?php echo $board->currentCategory()?></option>
							<?php endwhile?>
						</select>
					</div>
				</div>
				<?php else:?>
				<div class="detail-attr detail-category2">
					<div class="detail-name">
						<span class="kboard-ask-one-status status-<?php echo array_search($content->category2, $board->category)?>"><?php echo $content->category2?></span>
					</div>
				</div>
				<?php endif?>
			</div>
			
			<div class="kboard-content" itemprop="description">
				<div class="content-view">
					<?php echo $content->content?>
				</div>
			</div>
			
			<div class="kboard-document-action">
				<div class="left">
					<button type="button" class="kboard-button-action kboard-button-like" onclick="kboard_document_like(this)" data-uid="<?php echo $content->uid?>" title="<?php echo __('Like', 'kboard')?>"><?php echo __('Like', 'kboard')?> <span class="kboard-document-like-count"><?php echo intval($content->like)?></span></button>
					<button type="button" class="kboard-button-action kboard-button-unlike" onclick="kboard_document_unlike(this)" data-uid="<?php echo $content->uid?>" title="<?php echo __('Unlike', 'kboard')?>"><?php echo __('Unlike', 'kboard')?> <span class="kboard-document-unlike-count"><?php echo intval($content->unlike)?></span></button>
				</div>
				<div class="right">
					<button type="button" class="kboard-button-action kboard-button-print" onclick="kboard_document_print('<?php echo $url->getDocumentPrint($content->uid)?>')" title="<?php echo __('Print', 'kboard')?>"><?php echo __('Print', 'kboard')?></button>
				</div>
			</div>
			
			<?php if($content->isAttached()):?>
			<div class="kboard-attach">
				<div class="kboard-attach-title"><?php echo __('Attachment', 'kboard')?> <?php echo intval(count((array)$content->attach))?>개</div>
				<?php foreach($content->attach as $key=>$attach):?>
				<button type="button" class="kboard-button-action kboard-button-download" onclick="window.location.href='<?php echo $url->getDownloadURLWithAttach($content->uid, $key)?>'" title="<?php echo sprintf(__('Download %s', 'kboard'), $attach[1])?>"><?php echo $attach[1]?></button>
				<?php endforeach?>
			</div>
			<?php endif?>
		</div>
		
		<?php if($content->visibleComments()):?>
		<div class="kboard-comments-area"><?php echo $board->buildComment($content->uid)?></div>
		<?php endif?>
		
		<div class="kboard-document-navi">
			<div class="kboard-prev-document">
				<?php
				$bottom_content_uid = $content->getPrevUID();
				if($bottom_content_uid):
				$bottom_content = new KBContent();
				$bottom_content->initWithUID($bottom_content_uid);
				?>
				<a href="<?php echo $url->getDocumentURLWithUID($bottom_content_uid)?>">
					<span class="navi-arrow">«</span>
					<span class="navi-document-title kboard-ask-one-cut-strings"><?php echo $bottom_content->title?></span>
				</a>
				<?php endif?>
			</div>
			
			<div class="kboard-next-document">
				<?php
				$top_content_uid = $content->getNextUID();
				if($top_content_uid):
				$top_content = new KBContent();
				$top_content->initWithUID($top_content_uid);
				?>
				<a href="<?php echo $url->getDocumentURLWithUID($top_content_uid)?>">
					<span class="navi-document-title kboard-ask-one-cut-strings"><?php echo $top_content->title?></span>
					<span class="navi-arrow">»</span>
				</a>
				<?php endif?>
			</div>
		</div>
		
		<div class="kboard-control">
			<div class="left">
				<a href="<?php echo $url->set('mod', 'list')->toString()?>" class="kboard-ask-one-button-gray"><?php echo __('List', 'kboard')?></a>
				<?php if($board->isReply() && !$content->notice):?><a href="<?php echo $url->set('parent_uid', $content->uid)->set('mod', 'editor')->toString()?>" class="kboard-ask-one-button-gray"><?php echo __('Reply', 'kboard')?></a><?php endif?>
			</div>
			<?php if($content->isEditor() || $board->permission_write=='all'):?>
			<div class="right">
				<a href="<?php echo $url->getContentEditor($content->uid)?>" class="kboard-ask-one-button-gray"><?php echo __('Edit', 'kboard')?></a>
				<a href="<?php echo $url->getContentRemove($content->uid)?>" class="kboard-ask-one-button-gray" onclick="return confirm('<?php echo __('Are you sure you want to delete?', 'kboard')?>');"><?php echo __('Delete', 'kboard')?></a>
			</div>
			<?php endif?>
		</div>
		
		<?php if($board->contribution() && !$board->meta->always_view_list):?>
		<div class="kboard-ask-one-poweredby">
			<a href="http://www.cosmosfarm.com/products/kboard" onclick="window.open(this.href);return false;" title="<?php echo __('KBoard is the best community software available for WordPress', 'kboard')?>">Powered by KBoard</a>
		</div>
		<?php endif?>
	</div>
</div>

<?php wp_enqueue_script('ask-one-document', "{$skin_path}/document.js", array(), KBOARD_VERSION, true)?>