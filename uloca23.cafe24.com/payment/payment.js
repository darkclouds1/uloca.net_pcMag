
var IMP = window.IMP; // 생략해도 괜찮습니다.
IMP.init("imp81175537");
var paytype = 0;
var paytypes = '';
var itemname = '';
var amt = 22000;
//var userid = '<?=$userid?>';
//var usermail = '<?=$usermail?>';
var rspg;
var uloca_cid;
var customer_uid='';
var merchant_uid='';
var parsedUnixTime='';
var access_token='';
function number_format(amount) {

	if(amount==0) return 0;
    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (amount + '');
    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
    return n;
}
function chamt(amt) {
	document.getElementById("amt").innerHTML = '결제금액 : '+amt+'원'
}
function dopay(type) {
	var duration = 0;
	amt = 22000;
	var msg = '';
	if (type == 2)
	{
		amt = 19800; // 10% 할인
		paytype = '02';	//결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)	
		paytypes = '10'; //할인구분코드: 03, 06, 09, 12개월 - 3% ~ 12% 할인
		itemname = '정기결제 등록';
		uloca_cid = 'kakaopay.TCSUBSCRIP'; //'CA00011FF2';
		msg = '정기결제를 선택하셨습니다. 19,800원이 결제됩니다. 계속진행 하시겠습니까?';
	} else if (type == 22)
	{
		paytype = '22';
		paytypes = '';
		itemname = '정기결제 해지';
		uloca_cid = 'kakaopay.TCSUBSCRIP'; //'CA00011FF2';
		msg = '정기결제 해지를 선택하셨습니다. 계속진행 하시겠습니까?';
		//alert('작업중입니다.....');
		//return;
	} else if (type == 1)
	{

		amt = 22000;
		paytype = '01';
		paytypes = '0';
		itemname = '1달사용결제';
		
		uloca_cid = 'kakaopay.CA00011668'; //TC0ONETIME'; //'CA00011668';
		msg = '1달 결제를 선택하셨습니다. 22,000원이 결제됩니다. 계속진행 하시겠습니까?';
	}
	else if (type == 3)
	{
		duration = $('input[name="duration"]:checked').val();
		if (duration == 3) amt = 64020; // 22000 * 3 * 0.97
		if (duration == 6) amt = 124080; // 22000 * 6 * 0.94
		if (duration == 9) amt = 180180; // 22000 * 9 * 0.91
		if (duration == 12) amt = 232320; // 22000 * 12 * 0.88
		paytype = '03'; //duration;
		paytypes = duration; // + '개월';
		itemname = duration+'개월 기간선택';
		uloca_cid = 'kakaopay.CA00011668'; //TC0ONETIME'; //'CA00011668';
		msg = duration+'개월 결제를 선택하셨습니다. '+number_format(amt)+'원이 결제됩니다. 계속진행 하시겠습니까?';
	}
	console.log('dopay userid='+userid+' usermail='+usermail+'/type='+type+'/duration='+duration+'/amt='+amt+' uid='+'uloca_' + new Date().getTime());
	console.log('type='+type+' payTypeCD='+payTypeCD+' uloca_cid='+uloca_cid);
	if (type<20 && payTypeCD == '02')
	{
		alert('정기결제 고객이십니다.');
		return;
	}
	var con_test = confirm(msg);
	console.log('con_test='+con_test);
	if(con_test == true){
		if (type== 2) requestPay_sbcr();
		else requestPay();
	}

	
}

function requestPay() {
	// IMP.request_pay(param, callback) 호출
	clog(uloca_cid);
	//return;
	customer_uid = 'uloca_' + userid + '_' + new Date().getTime();
	merchant_uid = customer_uid; //'uloca_' + userid + '_' + new Date().getTime();
	IMP.request_pay({ // param
    pg: uloca_cid, //"[아임포트] X", //"아임포트(카카오페이)", //"[간편결재]카카오페이", //카카오페이 결제 html5_inicis",
    pay_method: "card",
    merchant_uid: merchant_uid, //'uloca_' +userid + '_' + new Date().getTime(),
	customer_uid : customer_uid, //uloca_cid, 
	//uloca_cid : uloca_cid,
    name: itemname,
    amount: amt,
    buyer_email: usermail, //"gildong@gmail.com",
    buyer_name: userid, //"홍길동",
    buyer_tel: "",
    buyer_addr: "",
    buyer_postcode: ""
	}, function (rsp) { // callback
    if (rsp.success) {
        // 결제 성공 시 로직,
        // jQuery로 HTTP 요청
		//console.log('callback');
		rspg=rsp;
		//viewObject(rsp);
		parm = 'imp_uid='+rsp.imp_uid;				// 아임포트 거래 고유 번호
		parm +='&merchant_uid='+rsp.merchant_uid;	//가맹점에서 생성/관리하는 고유 주문번호
		parm +='&amount='+rsp.paid_amount;			// 결제금액
		parm +='&name='+rsp.name;					// 주문명 할인구분코드: 03, 06, 09, 12개월 - 3% ~ 12% 할인
		parm +='&apply_num='+rsp.apply_num;			// 카드 승인번호
		parm +='&userid='+rsp.buyer_name;			// 주문자 이름
		parm +='&paytype='+paytype;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
		parm +='&paytypes='+paytypes;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
		parm +='&customer_uid='+customer_uid;					// 카드(빌링키)와 1:1로 대응하는 값
		//var sel = document.getElementById("kind1");
		//var val = sel.options[sel.selectedIndex].value;
	
		server="/payment/pay_return.php";
		console.log(server+'?'+parm);
		getAjaxPost(server,recv,parm);

		} else {
			clog(rsp.error_code+'/'+rsp.error_msg);
			alert(rsp.error_code+'/'+rsp.error_msg);

		}
	})
}
/* ---------------------------------------------------------------------------
정기 결제 등록 - 빌링키 발급
---------------------------------------------------------------------------- */
function requestPay_sbcr() {
	// IMP.request_pay(param, callback) 호출
	clog(uloca_cid);
	customer_uid = 'uloca_' + new Date().getTime(); //'uloca_userid_1551083692709'; //
	merchant_uid = 'uloca_' + userid + '_' + new Date().getTime();
	tf = requestBill(merchant_uid,customer_uid);
	if (tf == false)
	{
		alert('빌링키 발급이 실패 하였습니다.');
		return;
	} //else alert('빌링키 발급이 성공 하였습니다.');

	

	return;
}
/* --------------------------------------------------------------------------------
정기결제 등록	requestBill.success 에서 call
-------------------------------------------------------------------------------- */
function requestPay_sbcr2(rsp) {
	merchant_uid = 'uloca_' + userid + '_' + new Date().getTime();
	clog('requestPay_sbcr2 uloca_cid='+uloca_cid+' merchant_uid='+merchant_uid+' customer_uid='+customer_uid+' itemname='+itemname+' amt='+amt);
	/*IMP.request_pay({ // param
		pg: uloca_cid, //"[아임포트] X", //"아임포트(카카오페이)", //"[간편결재]카카오페이", //카카오페이 결제 html5_inicis",
		pay_method: "card",
		merchant_uid: merchant_uid, //'uloca_' +userid + '_' + new Date().getTime(),
		customer_uid : customer_uid, //uloca_cid, 
		//uloca_cid : uloca_cid,
		name: itemname,
		amount: amt,
		buyer_email: usermail, //"gildong@gmail.com",
		buyer_name: userid, //"홍길동",
		buyer_tel: "",
		buyer_addr: "",
		buyer_postcode: ""
	}, function (rsp) { // callback
		if (rsp.success) { */
			// 결제 성공 시 로직,
			// jQuery로 HTTP 요청
			clog('결제 성공 시 로직');
			//viewObject(rsp);
			//parm = 'imp_uid='+rsp.imp_uid;				// 아임포트 거래 고유 번호
			parm ='merchant_uid='+merchant_uid;	//가맹점에서 생성/관리하는 고유 주문번호
			parm +='&amount='+amt;			// 결제금액
			parm +='&name='+itemname;					// 주문명 할인구분코드: 03, 06, 09, 12개월 - 3% ~ 12% 할인
			parm +='&apply_num='+rsp.apply_num;			// 카드 승인번호
			parm +='&userid='+userid;			// 주문자 이름
			parm +='&usermail='+usermail;					// 카드(빌링키)와 1:1로 대응하는 값
			parm +='&paytype='+paytype;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
			parm +='&paytypes='+paytypes;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
			parm +='&customer_uid='+customer_uid;					// 카드(빌링키)와 1:1로 대응하는 값
			
			server="/payment/sbcr_customers_GET.php"; //sbcr_schedule.php"; //sbcr_customers_GET.php";
			console.log(server+'?'+parm);
			getAjaxPost(server,recv_get,parm);

			/*} else {
				clog(rsp.error_code+'/'+rsp.error_msg);
				alert(rsp.error_code+'/'+rsp.error_msg);

			} */
	//})
}
/* --------------------------------------------------------------------------------
빌링키 신청
-------------------------------------------------------------------------------- */
function requestBill(merchant_uid,customer_uid) {
	// IMP.request_pay(param, callback) 호출
	clog('requestBill uloca_cid='+uloca_cid+' merchant_uid='+merchant_uid+' customer_uid='+customer_uid);
	var tf = false;
	IMP.request_pay({ // param
		pg: uloca_cid,
		pay_method: "card", // "card"만 지원됩니다
		merchant_uid: "issue_billingkey_monthly_"+new Date().getTime(), // 빌링키 발급용 주문번호 merchant_uid, // 빌링키 발급용 주문번호
		customer_uid: customer_uid, // 카드(빌링키)와 1:1로 대응하는 값
		name: "최초인증결제",
		amount: 0, // 0 으로 설정하여 빌링키 발급만 진행합니다.
		buyer_email: usermail,
		buyer_name: "",
		buyer_tel: "",
		buyer_addr: "",
		buyer_postcode: ""
	}, function (rsp) { // callback
		//clog('callback');
		if (rsp.success) {
			clog('빌링키 발급 성공');
			tf= true;
			clog('tf='+tf);
			rspg=rsp;
			//getAccessToken();
			//requestPay_sbcr2(rsp);
			recv_get2(rsp);
			return tf;
		} else {
			clog('빌링키 발급 실패');
			tf= false;
			clog('tf='+tf);
			return tf;
		}
	});
	
}
function recv(data) {
	//viewObject(data);
	try
	{
		var msg = '결제가 완료되었습니다. ';
    			//msg += '/n고유ID : ' + rspg.imp_uid;
    			msg += '\n상점 거래ID : ' + rspg.merchant_uid;
    			msg += '\n결제 기간 : ' + rspg.name;
    			msg += '\n결제 금액 : ' + number_format(amt);
    			msg += '\n카드 승인번호 : ' + rspg.apply_num;
    			//msg += '\ndata.status= '+data.status;
    			console.log(msg);
				alert(msg);
	}
	catch (e)
	{
		alert('데이타에 에러가 있는것 같습니다. 관리자에게 문의하세요.'+e.message);
		clog('recv '+data);
	}
	clog('paytype='+paytype);
	if (paytype != '02') return;
	
	return;
	// 결제 예약 ---------------------------------------------------------------------------------
	sDate = getToday('-');

nDate =  dateAddDel(sDate, 1, 'm');
parsedUnixTime = new Date(nDate).getUnixTime();

clog('sDate='+sDate+' nDate='+nDate+' parsedUnixTime='+parsedUnixTime);
	
	getAccessToken();
}

function requestPayAgain() {

		//parm = 'imp_uid='+rspg.imp_uid;				// 아임포트 거래 고유 번호
		parm ='merchant_uid='+merchant_uid;	//가맹점에서 생성/관리하는 고유 주문번호
		parm +='&amount='+amt;			// 결제금액
		parm +='&name='+itemname;					// 주문명 할인구분코드: 03, 06, 09, 12개월 - 3% ~ 12% 할인
		parm +='&userid='+userid; 			// 주문자 이름
		parm +='&usermail='+usermail;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
							// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
		parm +='&customer_uid='+customer_uid;					// 카드(빌링키)와 1:1로 대응하는 값
			
		server="/payment/sbcr_again.php";
		console.log(server+'?'+parm);
		getAjaxPost(server,recv_get2,parm);
}

function recv_get(data) {
	clog('recv_get '+data);
	//rspg=data;
	if (data.substr(0,5) == 'Error')
	{
		alert(data);
		return;
	}
	requestPayAgain();
	//recv_get2(data);
}
function recv_get2(data) {
	//clog(data);
	/*if (data.substr(0,5) == 'Error')
	{
		alert(data);
		return;
	} 
*/
	clog('recv_get2 '+data);
	rspg=data;
	parm = 'imp_uid='+rspg.imp_uid;				// 아임포트 거래 고유 번호
		parm +='&merchant_uid='+rspg.merchant_uid;	//가맹점에서 생성/관리하는 고유 주문번호
		parm +='&amount='+amt;			// 결제금액
		parm +='&name='+rspg.name;					// 주문명 할인구분코드: 03, 06, 09, 12개월 - 3% ~ 12% 할인
		parm +='&apply_num='+rspg.apply_num;			// 카드 승인번호
		parm +='&userid='+userid; //rspg.buyer_name;			// 주문자 이름
		parm +='&paytype='+paytype;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
		parm +='&paytypes='+paytypes;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
		parm +='&customer_uid='+customer_uid;					// 카드(빌링키)와 1:1로 대응하는 값

		server="/payment/pay_return.php";
		console.log(server+'?'+parm);
		getAjaxPost(server,recv,parm);
	
	return;
	// 결제 예약 ---------------------------------------------------------------------------------
	/* sDate = getToday('-');

nDate =  dateAddDel(sDate, 1, 'm');
parsedUnixTime = new Date(nDate).getUnixTime();

clog('sDate='+sDate+' nDate='+nDate+' parsedUnixTime='+parsedUnixTime);
	
	getAccessToken(); */
}
/* ---------------------------------------------------------------------------
정기 결제 등록 해지
---------------------------------------------------------------------------- */
function requestPay_sbcr_delete() {
	// IMP.request_pay(param, callback) 호출
	clog(uloca_cid);
	//return;
	customer_uid = 'uloca_' + userid + '_' + new Date().getTime(); //'uloca_userid_1551083692709'; //
	merchant_uid = customer_uid; //'uloca_' + userid + '_' + new Date().getTime();
	IMP.request_pay({ // param
		pg: uloca_cid, //"[아임포트] X", //"아임포트(카카오페이)", //"[간편결재]카카오페이", //카카오페이 결제 html5_inicis",
		pay_method: "card",
		merchant_uid: merchant_uid, //'uloca_' +userid + '_' + new Date().getTime(),
		customer_uid : customer_uid, //uloca_cid, 
		//uloca_cid : uloca_cid,
		name: itemname,
		amount: amt,
		buyer_email: usermail, //"gildong@gmail.com",
		buyer_name: userid, //"홍길동",
		buyer_tel: "",
		buyer_addr: "",
		buyer_postcode: ""
	}, function (rsp) { // callback
    if (rsp.success) {
        // 결제 성공 시 로직,
        // jQuery로 HTTP 요청
		//console.log('callback');
		rspg=rsp;
		//viewObject(rsp);
		parm = 'imp_uid='+rsp.imp_uid;				// 아임포트 거래 고유 번호
		parm +='&merchant_uid='+rsp.merchant_uid;	//가맹점에서 생성/관리하는 고유 주문번호
		parm +='&amount='+rsp.paid_amount;			// 결제금액
		parm +='&name='+rsp.name;					// 주문명 할인구분코드: 03, 06, 09, 12개월 - 3% ~ 12% 할인
		parm +='&apply_num='+rsp.apply_num;			// 카드 승인번호
		parm +='&userid='+rsp.buyer_name;			// 주문자 이름
		parm +='&paytype='+paytype;					// 결제구분코드: 01:1달사용, 02:정기결제, 03:기간선택(할인적용)
		parm +='&customer_uid='+customer_uid;					// 카드(빌링키)와 1:1로 대응하는 값
		//var sel = document.getElementById("kind1");
		//var val = sel.options[sel.selectedIndex].value;
	
		server="/payment/pay_return.php";
		console.log(server+'?'+parm);
		getAjaxPost(server,recv,parm);

		} else {
			clog(rsp.error_code+'/'+rsp.error_msg);
			alert(rsp.error_code+'/'+rsp.error_msg);

		}
	})
}	

function getAccessToken() {

server = 'https://uloca23.cafe24.com/payment/getaccesstoken.php';
parm='';
getAjaxPost(server,retAccesstoken,parm);
}
function retAccesstoken(data) {
	access_token = data;

	clog('access_token='+access_token);

	// 결제(재결제) 요청
	jQuery.ajax({
		url: 'https://api.iamport.kr/subscribe/payments/again', //schedule',
		method: "post",
		headers: { "Authorization": access_token }, // 인증 토큰 Authorization header에 추가
		contentType: "text/plain", //
		accessControlAllowCredentials: true,
		data: {
                customer_uid:customer_uid,
                merchant_uid: "order_monthly_"+new Date().getTime(), // 새로 생성한 결제(재결제)용 주문 번호
                amount: amt,
                name: "월간 이용권 정기결제"
            },
	success:function(data){
		var str = '';
                for(var name in data){
                    str += '<li>name='+name+'/'+data[name]+'</li>';
                }
                clog(str);
            }

	});

	

}
Date.prototype.getUnixTime = function() { return this.getTime()/1000|0 };

// Parse a date and get it as Unix time
