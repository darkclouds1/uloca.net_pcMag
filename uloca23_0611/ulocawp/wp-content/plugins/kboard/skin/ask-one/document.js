/**
 * @author https://www.cosmosfarm.com/
 */

function kboard_ask_one_category_update(content_uid, new_category){
	kboard_content_update(content_uid, {category2:new_category}, function(res){
		if(res.result == 'success'){
			alert('완료되었습니다.');
		}
		else{
			alert(res.message);
		}
	});
}