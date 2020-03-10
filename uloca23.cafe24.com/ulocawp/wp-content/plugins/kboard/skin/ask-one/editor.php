<div id="kboard-ask-one-editor">
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
		
		<?php foreach($board->fields()->getSkinFields() as $key=>$field):?>
			<?php echo $board->fields()->getTemplate($field, $content, $boardBuilder)?>
		<?php endforeach?>
		
		<div class="kboard-control">
			<div class="left">
				<?php if($content->uid):?>
				<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>" class="kboard-ask-one-button-small"><?php echo __('Back', 'kboard')?></a>
				<a href="<?php echo $url->set('mod', 'list')->toString()?>" class="kboard-ask-one-button-small"><?php echo __('List', 'kboard')?></a>
				<?php else:?>
				<a href="<?php echo $url->set('mod', 'list')->toString()?>" class="kboard-ask-one-button-small"><?php echo __('Back', 'kboard')?></a>
				<?php endif?>
			</div>
			<div class="right">
				<?php if($board->isWriter()):?>
				<button type="submit" class="kboard-ask-one-button-small"><?php echo __('Save', 'kboard')?></button>
				<?php endif?>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript" src="<?php echo $skin_path?>/script.js?<?php echo KBOARD_VERSION?>"></script>
<script>
jQuery(document).ready(function(){
	var auto_secret_check = false;
	var document_uid = <?php echo intval($content->uid)?>;
	if(auto_secret_check && !document_uid){
		jQuery('input[name=secret]').prop('checked', true);
		kboard_toggle_password_field(jQuery('input[name=secret]'));
	}
});
</script>