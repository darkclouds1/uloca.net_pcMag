<div id="kboard-cross-link-editor">
	<form class="kboard-form" method="post" action="<?php echo $url->getContentEditorExecute()?>" enctype="multipart/form-data" onsubmit="return kboard_editor_execute(this);">
		<?php wp_nonce_field('kboard-editor-execute', 'kboard-editor-execute-nonce')?>
		<input type="hidden" name="action" value="kboard_editor_execute">
		<input type="hidden" name="mod" value="editor">
		<input type="hidden" name="uid" value="<?php echo $content->uid?>">
		<input type="hidden" name="board_id" value="<?php echo $content->board_id?>">
		<input type="hidden" name="parent_uid" value="<?php echo $content->parent_uid?>">
		<input type="hidden" name="member_uid" value="<?php echo $content->member_uid?>">
		<input type="hidden" name="member_display" value="<?php echo $content->member_display?>">
		<input type="hidden" name="date" value="<?php echo $content->date?>">
		<input type="hidden" name="user_id" value="<?php echo get_current_user_id()?>">
		
		<div class="kboard-attr-row kboard-attr-title">
			<label class="attr-name" for="kboard-input-title"><?php echo __('Title', 'kboard')?></label>
			<div class="attr-value"><input type="text" id="kboard-input-title" name="title" value="<?php echo $content->title?>" placeholder="<?php echo __('Title', 'kboard')?>..."></div>
		</div>
		
		<div class="kboard-attr-row">
			<label class="attr-name" for="kboard_option_link"><?php echo __('Link', 'kboard-cross-link')?></label>
			<div class="attr-value">
				<input type="text" id="kboard_option_link" name="kboard_option_link" value="<?php echo $content->option->link?>" placeholder="<?php echo __('Link', 'kboard-cross-link')?>...">
				<div class="description">※ 링크를 빈 칸으로 두면 일반 게시글처럼 표시합니다.</div>
			</div>
		</div>
		
		<div class="kboard-attr-row">
			<label class="attr-name" for="kboard_option_link_target"><?php echo __('Target', 'kboard-cross-link')?></label>
			<div class="attr-value">
				<select id="kboard_option_link_target" name="kboard_option_link_target">
						<option value="new"<?php if($content->option->link_target == 'new'):?> selected<?php endif?>><?php echo __('blank', 'kboard-cross-link')?></option>
						<option value="self"<?php if($content->option->link_target == 'self'):?> selected<?php endif?>><?php echo __('self', 'kboard-cross-link')?></option>
					</select>
			</div>
		</div>
		
		<?php if($content->isEditor() || $board->permission_write=='all'):?>
		<div class="kboard-attr-row option-row">
			<div class="attr-name"><?php echo __('Options', 'kboard')?></div>
			<div class="attr-value">
				<label class="attr-value-option"><input type="checkbox" name="notice" value="true"<?php if($content->notice):?> checked<?php endif?>> <?php echo __('Top fixing', 'kboard-cross-link')?></label>
				<div class="description"></div>
				<?php do_action('kboard_skin_editor_option', $content, $board, $boardBuilder)?>
			</div>
		</div>
		<?php endif?>
		
		<?php if($board->use_category):?>
			<?php if($board->initCategory1()):?>
			<div class="kboard-attr-row">
				<label class="attr-name" for="kboard-select-category1"><?php echo __('Category', 'kboard')?></label>
				<div class="attr-value">
					<select id="kboard-select-category1" name="category1">
						<option value=""><?php echo __('Category', 'kboard')?> <?php echo __('Select', 'kboard')?></option>
						<?php while($board->hasNextCategory()):?>
						<option value="<?php echo $board->currentCategory()?>"<?php if($content->category1 == $board->currentCategory()):?> selected<?php endif?>><?php echo $board->currentCategory()?></option>
						<?php endwhile?>
					</select>
				</div>
			</div>
			<?php endif?>
			
			<?php if($board->initCategory2()):?>
			<div class="kboard-attr-row">
				<label class="attr-name" for="kboard-select-category2"><?php echo __('Source', 'kboard-cross-link')?></label>
				<div class="attr-value">
					<select id="kboard-select-category2" name="category2">
						<option value=""><?php echo __('Source', 'kboard-cross-link')?> <?php echo __('Select', 'kboard')?></option>
						<?php while($board->hasNextCategory()):?>
						<option value="<?php echo $board->currentCategory()?>"<?php if($content->category2 == $board->currentCategory()):?> selected<?php endif?>><?php echo $board->currentCategory()?></option>
						<?php endwhile?>
					</select>
				</div>
			</div>
			<?php endif?>
		<?php endif?>
		
		<?php if($board->viewUsernameField()):?>
		<div class="kboard-attr-row">
			<label class="attr-name" for="kboard-input-member-display"><?php echo __('Author', 'kboard')?></label>
			<div class="attr-value"><input type="text" id="kboard-input-member-display" name="member_display" value="<?php echo $content->member_display?>" placeholder="<?php echo __('Author', 'kboard')?>..."></div>
		</div>
		<div class="kboard-attr-row">
			<label class="attr-name" for="kboard-input-password"><?php echo __('Password', 'kboard')?></label>
			<div class="attr-value"><input type="password" id="kboard-input-password" name="password" value="<?php echo $content->password?>" placeholder="<?php echo __('Password', 'kboard')?>..."></div>
		</div>
		<?php else:?>
		<input style="display:none" type="text" name="fake-autofill-fields">
		<input style="display:none" type="password" name="fake-autofill-fields">
		<!-- 비밀글 비밀번호 필드 시작 -->
		<div class="kboard-attr-row secret-password-row"<?php if(!$content->secret):?> style="display:none"<?php endif?>>
			<label class="attr-name" for="kboard-input-password"><?php echo __('Password', 'kboard')?></label>
			<div class="attr-value"><input type="password" id="kboard-input-password" name="password" value="<?php echo $content->password?>" placeholder="<?php echo __('Password', 'kboard')?>..."></div>
		</div>
		<!-- 비밀글 비밀번호 필드 끝 -->
		<?php endif?>
		
		<?php if($board->useCAPTCHA() && !$content->uid):?>
			<?php if(kboard_use_recaptcha()):?>
				<div class="kboard-attr-row">
					<label class="attr-name"></label>
					<div class="attr-value"><div class="g-recaptcha" data-sitekey="<?php echo kboard_recaptcha_site_key()?>"></div></div>
				</div>
			<?php else:?>
				<div class="kboard-attr-row">
					<label class="attr-name" for="kboard-input-captcha"><img src="<?php echo kboard_captcha()?>" alt=""></label>
					<div class="attr-value"><input type="text" id="kboard-input-captcha" name="captcha" value="" placeholder="<?php echo __('CAPTCHA', 'kboard')?>..."></div>
				</div>
			<?php endif?>
		<?php endif?>
		
		<div class="kboard-content">
			<?php if($board->use_editor):?>
				<?php wp_editor($content->content, 'kboard_content', array('media_buttons'=>$board->isAdmin(), 'editor_height'=>400))?>
			<?php else:?>
				<textarea name="kboard_content" id="kboard_content"><?php echo $content->content?></textarea>
			<?php endif?>
		</div>
		
		<?php if($board->meta->max_attached_count > 0):?>
			<!-- 첨부파일 시작 -->
			<?php for($attached_index=1; $attached_index<=$board->meta->max_attached_count; $attached_index++):?>
			<div class="kboard-attr-row">
				<label class="attr-name" for="kboard-input-file<?php echo $attached_index?>"><?php echo __('Attachment', 'kboard')?><?php echo $attached_index?></label>
				<div class="attr-value">
					<?php if(isset($content->attach->{"file{$attached_index}"})):?><?php echo $content->attach->{"file{$attached_index}"}[1]?> - <a href="<?php echo $url->getDeleteURLWithAttach($content->uid, "file{$attached_index}")?>" onclick="return confirm('<?php echo __('Are you sure you want to delete?', 'kboard')?>');"><?php echo __('Delete file', 'kboard')?></a><?php endif?>
					<input type="file" id="kboard-input-file<?php echo $attached_index?>" name="kboard_attach_file<?php echo $attached_index?>">
				</div>
			</div>
			<?php endfor?>
			<!-- 첨부파일 끝 -->
		<?php endif?>
		
		<div class="kboard-control">
			<div class="left">
				<?php if($content->uid):?>
				<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>" class="kboard-cross-link-button-small"><?php echo __('Back', 'kboard')?></a>
				<a href="<?php echo $url->set('mod', 'list')->toString()?>" class="kboard-cross-link-button-small"><?php echo __('List', 'kboard')?></a>
				<?php else:?>
				<a href="<?php echo $url->set('mod', 'list')->toString()?>" class="kboard-cross-link-button-small"><?php echo __('Back', 'kboard')?></a>
				<?php endif?>
			</div>
			<div class="right">
				<?php if($board->isWriter()):?>
					<button type="submit" class="kboard-cross-link-button-small" title="<?php echo __('Register Link', 'kboard-cross-link')?>"><?php echo __('Register Link', 'kboard-cross-link')?></button>
				<?php endif?>
			</div>
		</div>
	</form>
</div>

<?php wp_enqueue_script('kboard-cross-link-script', "{$skin_path}/script.js", array(), KBOARD_VERSION, true)?>