/**
 * @author https://www.cosmosfarm.com/
 */

jQuery(document).ready(function(){
	IMP.init(cosmosfarm_members_subscription_checkout_settings.iamport_id);
});

function cosmosfarm_members_subscription_pay(form){
	/*
	 * 잠시만 기다려주세요.
	 */
	if(jQuery(form).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	
	var product_id = jQuery('[name="product_id"]', form).val();
	
	if(product_id){
		for(var i=0; i<jQuery('input[name=agree]', form).length; i++){
			if(!jQuery('input[name=agree]', form).eq(i).prop('checked')){
				alert(cosmosfarm_members_localize_strings.please_agree);
				jQuery('input[name=agree]', form).eq(i).focus();
				return false;
			}
		}
		
		if(cosmosfarm_members_subscription_checkout_settings.subscription_pg_type == 'general'){
			cosmosfarm_members_subscription_pay_general(form, function(){
				
			});
		}
		else{
			cosmosfarm_members_subscription_pay_billing(form, function(){
				
			});
		}
		
		jQuery(form).data('submitted', 'submitted');
		jQuery('[type=submit]', form).text(cosmosfarm_members_localize_strings.please_wait);
		jQuery('[type=submit]', form).val(cosmosfarm_members_localize_strings.please_wait);
	}
	return false;
}

function cosmosfarm_members_subscription_pay_general(form, callback){
	var product_id = jQuery('[name="product_id"]', form).val();
	
	cosmosfarm_members_pre_subscription_request_pay(form, product_id, function(res){
		if(res.result == 'success'){
			var custom_data = {};
			
			custom_data['pay_success_url'] = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
			
			jQuery.each(jQuery(form).serializeArray(), function(){
				if(this.name != 'security' && this.name != 'imp_uid'){
					custom_data[this.name] = this.value;
				}
			});
			
			IMP.request_pay({
				pg             : cosmosfarm_members_subscription_checkout_settings.subscription_general_pg,
				pay_method     : 'card',
				merchant_uid   : cosmosfarm_members_subscription_checkout_settings.merchant_uid,
				name           : cosmosfarm_members_subscription_checkout_settings.product_title,
				amount         : cosmosfarm_members_subscription_checkout_settings.product_price,
				buyer_email    : jQuery('[name="buyer_email"]', form).val(),
				buyer_name     : jQuery('[name="buyer_name"]', form).val(),
				buyer_tel      : jQuery('[name="buyer_tel"]', form).val(),
				buyer_addr     : jQuery.trim(jQuery('[name="addr1"]', form).val() + ' ' + jQuery('[name="addr2"]', form).val()),
				buyer_postcode : jQuery('[name="zip"]', form).val(),
				custom_data    : custom_data,
				m_redirect_url : cosmosfarm_members_subscription_checkout_settings.m_redirect_url,
				app_scheme     : cosmosfarm_members_subscription_checkout_settings.app_scheme
			}, function(res){
				if(res.success && res.imp_uid){
					cosmosfarm_members_subscription_request_pay_complete(form, product_id, res.imp_uid, function(res){
						if(res.result == 'success'){
							alert(res.message);
							window.location.href = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
						}
						else{
							alert(res.message);
							
							jQuery(form).data('submitted', '');
							jQuery('[type=submit]', form).text(cosmosfarm_members_localize_strings.place_order);
							jQuery('[type=submit]', form).val(cosmosfarm_members_localize_strings.place_order);
						}
					});
				}
				else{
					if(res.error_msg){
						alert(res.error_msg);
					}
					else{
						alert('결제 실패');
					}
					
					jQuery(form).data('submitted', '');
					jQuery('[type=submit]', form).text(cosmosfarm_members_localize_strings.place_order);
					jQuery('[type=submit]', form).val(cosmosfarm_members_localize_strings.place_order);
				}
			});
		}
		else{
			alert(res.message);
			
			jQuery(form).data('submitted', '');
			jQuery('[type=submit]', form).text(cosmosfarm_members_localize_strings.place_order);
			jQuery('[type=submit]', form).val(cosmosfarm_members_localize_strings.place_order);
		}
	});
}

function cosmosfarm_members_subscription_pay_billing(form, callback){
	var product_id = jQuery('[name="product_id"]', form).val();
	
	if(cosmosfarm_members_subscription_checkout_settings.subscription_pg == 'nice'){
		
		var card_number = jQuery('[name="cosmosfarm_members_subscription_checkout_card_number"]', form).val();
		
		var expiry = '';
		expiry += jQuery('[name="cosmosfarm_members_subscription_checkout_expiry_year"]', form).val();
		expiry += jQuery('[name="cosmosfarm_members_subscription_checkout_expiry_month"]', form).val();
		
		var birth = jQuery('[name="cosmosfarm_members_subscription_checkout_birth"]', form).val();
		
		var pwd_2digit = jQuery('[name="cosmosfarm_members_subscription_checkout_pwd_2digit"]', form).last().val();
		
		jQuery.post('?action=cosmosfarm_members_subscription_register_card', {cosmosfarm_members_subscription_checkout_card_number:card_number, cosmosfarm_members_subscription_checkout_expiry:expiry, cosmosfarm_members_subscription_checkout_birth:birth, cosmosfarm_members_subscription_checkout_pwd_2digit:pwd_2digit, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
			if(res.result == 'success'){
				if(res.error_message){
					alert(res.error_message);
					
					jQuery(form).data('submitted', '');
					jQuery('[type=submit]', form).text(cosmosfarm_members_localize_strings.place_order);
					jQuery('[type=submit]', form).val(cosmosfarm_members_localize_strings.place_order);
				}
				else{
					cosmosfarm_members_subscription_request_pay({form:form, product_id:product_id}, function(res){
						if(res.result == 'success'){
							alert(res.message);
							window.location.href = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
						}
						else{
							alert(res.message);
							
							jQuery(form).data('submitted', '');
							jQuery('[type=submit]', form).text(cosmosfarm_members_localize_strings.place_order);
							jQuery('[type=submit]', form).val(cosmosfarm_members_localize_strings.place_order);
						}
					});
				}
			}
			else{
				alert(res.message);
				
				jQuery(form).data('submitted', '');
				jQuery('[type=submit]', form).text(cosmosfarm_members_localize_strings.place_order);
				jQuery('[type=submit]', form).val(cosmosfarm_members_localize_strings.place_order);
			}
		});
	}
	else{
		cosmosfarm_members_pre_subscription_request_pay(form, product_id, function(res){
			if(res.result == 'success'){
				var custom_data = {};
				
				custom_data['pay_success_url'] = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
				
				jQuery.each(jQuery(form).serializeArray(), function(){
					if(this.name != 'security' && this.name != 'imp_uid'){
						custom_data[this.name] = this.value;
					}
				});
				
				IMP.request_pay({
					pg             : cosmosfarm_members_subscription_checkout_settings.subscription_pg,
					pay_method     : 'card',
					merchant_uid   : cosmosfarm_members_subscription_checkout_settings.merchant_uid,
					name           : cosmosfarm_members_subscription_checkout_settings.product_title,
					amount         : cosmosfarm_members_subscription_checkout_settings.product_price,
					period : {
						interval : cosmosfarm_members_subscription_checkout_settings.product_period_interval,
						from     : cosmosfarm_members_subscription_checkout_settings.product_period_from,
						to       : cosmosfarm_members_subscription_checkout_settings.product_period_to
					},
					customer_uid   : cosmosfarm_members_subscription_checkout_settings.customer_uid,
					buyer_email    : jQuery('[name="buyer_email"]', form).val(),
					buyer_name     : jQuery('[name="buyer_name"]', form).val(),
					buyer_tel      : jQuery('[name="buyer_tel"]', form).val(),
					buyer_addr     : jQuery.trim(jQuery('[name="addr1"]', form).val() + ' ' + jQuery('[name="addr2"]', form).val()),
					buyer_postcode : jQuery('[name="zip"]', form).val(),
					custom_data    : custom_data,
					m_redirect_url : cosmosfarm_members_subscription_checkout_settings.m_redirect_url,
					app_scheme     : cosmosfarm_members_subscription_checkout_settings.app_scheme
				}, function(res){
					if(res.success){
						cosmosfarm_members_subscription_request_pay({form:form, product_id:product_id, imp_uid:res.imp_uid}, function(res){
							if(res.result == 'success'){
								alert(res.message);
								window.location.href = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
							}
							else{
								alert(res.message);
								
								jQuery(form).data('submitted', '');
								jQuery('[type=submit]', form).text(cosmosfarm_members_localize_strings.place_order);
								jQuery('[type=submit]', form).val(cosmosfarm_members_localize_strings.place_order);
							}
						});
					}
					else{
						if(res.error_msg){
							alert(res.error_msg);
						}
						else{
							alert('빌링키 발급 실패');
						}
						
						jQuery(form).data('submitted', '');
						jQuery('[type=submit]', form).text(cosmosfarm_members_localize_strings.place_order);
						jQuery('[type=submit]', form).val(cosmosfarm_members_localize_strings.place_order);
					}
				});
			}
			else{
				alert(res.message);
				
				jQuery(form).data('submitted', '');
				jQuery('[type=submit]', form).text(cosmosfarm_members_localize_strings.place_order);
				jQuery('[type=submit]', form).val(cosmosfarm_members_localize_strings.place_order);
			}
		});
	}
}

function cosmosfarm_members_pre_subscription_request_pay(form, product_id, callback){
	jQuery('[name="security"]', form).val(cosmosfarm_members_settings.ajax_nonce);
	jQuery('[name="product_id"]', form).val(product_id);
	jQuery.post('?action=cosmosfarm_members_pre_subscription_request_pay', jQuery(form).serialize() + '&checkout_nonce=' + cosmosfarm_members_subscription_checkout_settings.checkout_nonce, function(res){
		callback(res);
	});
}

function cosmosfarm_members_subscription_request_pay(args, callback){
	var form = args.form;
	var product_id = args.product_id;
	var imp_uid = args.imp_uid;
	jQuery('[name="security"]', form).val(cosmosfarm_members_settings.ajax_nonce);
	jQuery('[name="product_id"]', form).val(product_id);
	jQuery.post('?action=cosmosfarm_members_subscription_request_pay', jQuery(form).serialize() + '&imp_uid=' + imp_uid + '&checkout_nonce=' + cosmosfarm_members_subscription_checkout_settings.checkout_nonce, function(res){
		callback(res);
	});
}

function cosmosfarm_members_subscription_request_pay_complete(form, product_id, imp_uid, callback){
	jQuery('[name="security"]', form).val(cosmosfarm_members_settings.ajax_nonce);
	jQuery('[name="product_id"]', form).val(product_id);
	jQuery.post('?action=cosmosfarm_members_subscription_request_pay_complete&display=pc', jQuery(form).serialize() + '&imp_uid=' + imp_uid + '&checkout_nonce=' + cosmosfarm_members_subscription_checkout_settings.checkout_nonce, function(res){
		callback(res);
	});
}

function cosmosfarm_members_subscription_update(obj, order_id, subscription_active){
	/*
	 * 잠시만 기다려주세요.
	 */
	if(jQuery(obj).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	
	jQuery.post('?action=cosmosfarm_members_subscription_update', {order_id:order_id, subscription_active:subscription_active, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
		if(res.result == 'success'){
			window.location.reload();
		}
		else{
			alert(res.message);
			jQuery(obj).data('submitted', '');
		}
	});
	
	jQuery(obj).data('submitted', 'submitted');
	return false;
}