/**
 * @author https://www.cosmosfarm.com/
 */

jQuery(document).ready(function(){
	kboard_cross_link_layout();
});

jQuery(window).resize(function(){
	kboard_cross_link_layout();
});

function kboard_cross_link_layout(){
	jQuery('.kboard-cross-link-list').each(function(){
		var list = jQuery(this).width();
		if(list < 600){
			// mobile
			jQuery(this).addClass('mobile');
		}
		else{
			// pc
			jQuery(this).removeClass('mobile');
		}
	});
}

function kboard_cross_link_shortcut(obj, content_uid, link_target){
	var url = jQuery(obj).attr('href');
	if(url && url.length > 7){
		jQuery.post(kboard_settings.alax_url, {action:'kboard_cross_link_shortcusts', content_uid:content_uid});
		if(link_target == 'new'){
			window.open(url);
		}
		else if(link_target == 'self'){
			window.location.href = url;
		}
	}
	else{
		alert(kboard_cross_link_localize_strings.missing_link_address);
	}
	return false;
}

function kboard_cross_link_more_view(obj, board_id){
	var page_url = jQuery('input[name=kboard_cross_link_latest_board_url]').val();
	var page = jQuery('input[name=kboard_cross_link_page]').val();
	var keyword = jQuery('input[name=keyword]').val();
	var category1 = jQuery('input[name=kboard_cross_link_category1]').val();
	var category2 = jQuery('input[name=kboard_cross_link_category2]').val();
	var current_page = jQuery('input[name=kboard_cross_link_current_page]').val();
	
	if(page == '-1'){
		alert(kboard_cross_link_localize_strings.no_more_data);
	}
	else{
		page++;
		
		jQuery.get(page_url, {action:'kboard_cross_link_more_view', board_id:board_id, pageid:page, keyword:keyword, category1:category1, category2:category2, current_page:current_page}, function(data){
			data = data.replace(/(^\s*)|(\s*$)/gi, "");
			if(data){
				jQuery('#kboard-cross-link-list .kboard-list tbody').append(data);
				jQuery('input[name=kboard_cross_link_page]').val(page);
			}
			else{
				alert(kboard_cross_link_localize_strings.no_more_data);
				jQuery('input[name=kboard_cross_link_page]').val('-1');
			}
	    }, 'text');
	}
	return false;
}