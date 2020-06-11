<?

// https://docs.iamport.kr/implementation/payment
/*
pg사 코드값

KG이니시스(웹표준방식), 빌링결제 : html5_inicis
KCP : kcp
KCP(빌링결제) : kcp_billing
LGU+ : uplus
나이스페이먼츠 : nice
JTNet : jtnet
카카오페이 : kakao, kakaopay
다날(휴대폰소액) : danal
다날(신용카드/계좌이체/가상계좌) : danal_tpay
모빌리언스 : mobilians
페이코 : payco
시럽페이 : syrup
페이팔 : paypal
엑심베이 : eximbay
네이버페이(주문형) : naverco
네이버페이(결제형) : naverpay

정기결제	10% 할인
1달사용 구매	22,000원
기간선택	3,6,9,12개월 개월수% 할인
*/
?>
<!-- jQuery -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js" ></script>
<!-- iamport.payment.js -->
<script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.1.5.js"></script>
<!-- script src="/g2b/g2b.js?version=20190203"></script -->

<script>
var IMP = window.IMP; // 생략해도 괜찮습니다.
IMP.init("imp81175537");
//viewObject(IMP);

function requestPay() {
// IMP.request_pay(param, callback) 호출
IMP.request_pay({ // param
    pg: "kakaopay", //"[아임포트] X", //"아임포트(카카오페이)", //"[간편결재]카카오페이", //카카오페이 결제 html5_inicis",
    pay_method: "card",
    merchant_uid: "ORD20180131-0000012",
    name: "노르웨이 회전 의자",
    amount: 10,
    buyer_email: "gildong@gmail.com",
    buyer_name: "홍길동",
    buyer_tel: "010-4242-4242",
    buyer_addr: "서울특별시 강남구 신사동",
    buyer_postcode: "01181"
}, function (rsp) { // callback
    if (rsp.success) {
        // 결제 성공 시 로직,
        // jQuery로 HTTP 요청
        jQuery.ajax({
            url: "https://uloca23.cafe24.com/payments/pay_return.php", // 가맹점 서버
            method: "POST",
            headers: { "Content-Type": "application/json" },
            data: {
                imp_uid: rsp.imp_uid,
                merchant_uid: rsp.merchant_uid
            }
        }).done(function (data) {
			if ( everythings_fine ) {
    			var msg = '결제가 완료되었습니다.';
    			msg += '\n고유ID : ' + rsp.imp_uid;
    			msg += '\n상점 거래ID : ' + rsp.merchant_uid;
    			msg += '\결제 금액 : ' + rsp.paid_amount;
    			msg += '카드 승인번호 : ' + rsp.apply_num;
    			msg += '\ndata.status= '+data.status;
    			alert(msg);
				imp_uid = rsp.imp_uid;
				apply_num = rsp.apply_num;
					payAmt = rsp.paid_amount
    		} 
			switch(data.status) {
                case "vbankIssued":
                    // 가상계좌 발급 시 로직
                    alert('가상계좌 발급 되었습니다.');
					break;
                case "success":
                    // 결제 성공 시 로직
                    alert('결재 되었습니다.');
					break;
            }
        })
    } else {
        alert("결재 실패하였습니다.에러 내용: " +  rsp.error_msg);
    }
});
}

</script>

<button onclick="requestPay()">결제하기</button>
