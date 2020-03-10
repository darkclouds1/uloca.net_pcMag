<div id="kboard-pic-gallery-latest">
	<?php while($content = $list->hasNext()):?>
		<div class="kboard-pic-gallery-latest-item">
			<a href="<?php echo $url->set('uid', $content->uid)->set('mod', 'document')->toStringWithPath($board_url)?>#kboard-pic-gallery-document" class="kboard-pic-gallery-latest-thumbnail">
				<?php if($content->getThumbnail(180, 180)):?>
					<img src="<?php echo $content->getThumbnail(180, 180)?>" alt="">
				<?php else:?>
					<i class="icon-picture"></i>
				<?php endif?>
			</a>
			<div class="kboard-pic-gallery-latest-title">
				<p class="cut_strings"><a href="<?php echo $url->set('uid', $content->uid)->set('mod', 'document')->toStringWithPath($board_url)?>#kboard-pic-gallery-document"><?php echo $content->title?></a></p>
			</div>
		</div>
	<?php endwhile?>
</div>