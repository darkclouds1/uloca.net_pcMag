<div id="kboard-ocean-gallery-latest">
	<?php while($content = $list->hasNext()): $resize_img_src = $content->getThumbnail(109, 64);?>
		<a href="<?php echo $url->set('uid', $content->uid)->set('mod', 'document')->toStringWithPath($board_url)?>">
			<div class="kboard-ocean-gallery-latest-item">
				<div class="kboard-ocean-gallery-latest-thumbnail"><?php if($resize_img_src):?><img src="<?php echo $resize_img_src?>" style="width:100%;height:100%" alt=""><?php else:?><div class="kboard-no-image"><i class="icon-picture"></i></div><?php endif?></div>
				<div class="kboard-ocean-gallery-latest-title cut_strings"><?php echo $content->title?></div>
			</div>
		</a>
	<?php endwhile?>
</div>