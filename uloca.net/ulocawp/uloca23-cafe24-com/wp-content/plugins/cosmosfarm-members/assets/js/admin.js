/**
 * @author https://www.cosmosfarm.com/
 */

function cosmosfarm_members_order_cancel(order_id){
	if(confirm('환불후에는 취소할 수 없습니다. 환불할까요?')){
		jQuery.post(cosmosfarm_members_admin_settings.post_url, {action:'cosmosfarm_members_order_cancel', order_id:order_id, security:cosmosfarm_members_admin_settings.ajax_nonce}, function(res){
			if(res.result == 'success'){
				alert(res.message);
				window.location.reload();
			}
			else{
				alert(res.message);
			}
		});
	}
	return false;
}

function cosmosfarm_members_mailchimp_subscribe(user_id, status){
	if(user_id){
		jQuery.post(cosmosfarm_members_admin_settings.post_url, {action:'cosmosfarm_members_'+status+'_mailchimp', user_id:user_id, mailchimp_field:status, security:cosmosfarm_members_admin_settings.ajax_nonce}, function(res){
			window.location.reload();
		});
	}
}