//----------------------------------------
//  통합검색 -by jsj 190320 
//----------------------------------------
// 쿠키 전역변수, -by jsj 20200601
// 관심목록(Reload시)은 자동 검색되므로 false로 설정->  최초조회시 검색 쿠키 저장 막음
var searchType      = 1; 				// 1:입찰정보 2:기업정보
var searchCountMax  = 5;  				// 무료검색횟수 	-by jsj 0314 
var searchLoginPlus = 5; 				// 로그인후 무료검색횟수 -by jsj 0314
var loginSW         = '<?=$loginSW?>'; 	// plugins/g2b.php 83line -by jsj 0312

var duridx = -1;
var searchDuration = [ -1, -3, -6, -12, -24, -999 ];

function searchajax() {
	duridx = -1;
	curStart = 0;
	clog('searchajax searchType='+searchType);
	document.myForm.dminsttNm.value = '';
	document.getElementById('tables').innerHTML = '';
	document.getElementById('totalrec').innerHTML = '';
	idx=0;

	if (searchType==1 ) {
		searchajax0(); 			//searchajax_balju(); --> 발주계획 
	} else {
		searchajax0();			//입찰기업 검색 
	}
	duridx = -1;
}
	
// 용도확인 필요 -by jsj 20200423
function searchajax0_1() {   
	duridx = 5;
	var d = new Date();
	sDate1 = d.format("yyyyMMdd");
	d= d.format("yyyy-MM-dd");
	var t1;
	var t2;
	
		t1 = d;
		t2 = '2010-01-01';
	
	clog('g2b_2019 searchajax0_1 today='+d+' t1='+t1+' t2='+t2+' searchDuration.length='+searchDuration.length);
	curStart = 0;
	searchajax1(t1,t2);
}

function searchajax0() {
	duridx ++;
	var d = new Date();
	d= d.format("yyyy-MM-dd");
	var t1;
	var t2;
	if (duridx <1)
	{
		t1 = '0';
		t2 = dateAddDel(d, searchDuration[duridx], 'm');
	} else if (duridx < searchDuration.length )
	{
		t1 = dateAddDel(d, searchDuration[duridx-1], 'm');
		t2 = dateAddDel(d, searchDuration[duridx], 'm');
		
	} else {
		alert('자료가 더이상 없습니다.');
		return;
	}
	clog('g2b_2019 today='+d+' t1='+t1+' t2='+t2+' searchDuration.length='+searchDuration.length);
	searchajax1(t1,t2);
}

var curStart = 0;
var cntonce = 100;
var baljusw=false;
//통합검색-입찰공고 검색 
function searchajax_balju() {	
	var form = document.myForm;
	if (form.id.value == '' && (SearchCounts > searchCountMax))	{
		alert (String(SearchCounts)+'::무료 검색 횟수가 초과 했습니다. 로그인 하십시요. '); //-by jsj 0312
		location.href='/ulocawp/?page_id=325';	//로그인메뉴로 이동 
		exit;
	}
	else if (loginSW == 0 && (SearchCounts > (searchCountMax + searchLoginPlus))) {
		//alert(String(loginSW));
		alert(String(SearchCounts) + '[구매결제]회비 납부 기간이 지났습니다. 서비스운영을 위한 구독료 or Donation이 필요합니다.^^');

		// 회비 납부 기간이 지나면, 구매결제로 이동하지 않고 alert창만 띄우는 걸로.. -by jsj 20200427
		// location.href='/ulocawp/?page_id=1352'; //구매결제로 이동 -by jsj 0312 
		// exit;
	}

	if (searchType==1 && form.kwd.value.trim().length<2)
	{
		alert('검색 키워드를 2자리 이상 입력 하세요.');
		form.kwd.focus();
		return;
		//form.kwd.value = ' ';
	}
	if (searchType==1 && form.kwd.value.trim() == '%%')
	{
		alert('검색 키워드를 바르게 입력 하세요.');
		form.kwd.focus();
		return;
	}
	if (searchType==2 && form.compname.value.trim().length<2)
	{
		alert('기업정보 검색에 입찰기업명을 2자리 이상 입력 하세요.');
		form.compname.focus();
		return;
	}

	//clog('ajax 1 sDate1='+sDate1+' eDate1='+eDate1+' endSw ='+ endSw+' durationIndex='+durationIndex+' ddur='+ddur+' ajaxCnt='+ajaxCnt);
	parm = 'kwd='+encodeURIComponent(form.kwd.value.trim()); //+'&startDate='+encodeURIComponent(sDate1)+'&endDate='+eDate1;
	parm +='&dminsttNm='+encodeURIComponent(form.dminsttNm.value.trim());
	parm +='&id='+form.id.value;
	baljusw = true;
	server="/g2b/g2bOrder.php";
	clog(server+'?'+parm);

	if (form.dminsttNm.value != '') document.getElementById('tables').innerHTML = '';

	move();
	getAjaxPost(server,recv_balju,parm);
}

//통합검색 - 기업검색   -by jsj 0312
function searchajax1(t1,t2) {
	var form = document.myForm;
	if (form.id.value == '' && (SearchCounts > searchCountMax))	{
		alert (String(SearchCounts)+'::무료 검색 횟수가 초과 했습니다. 로그인 하십시요. '); //-by jsj 0312
		location.href='/ulocawp/?page_id=325';	//로그인메뉴로 이동 
		exit;
	}
	else if (loginSW == 0 && (SearchCounts > (searchCountMax + searchLoginPlus))) {
		alert(String(SearchCounts) + '[구매결제]회비 납부 기간이 지났습니다. 서비스운영을 위한 구독료 or Donation이 필요합니다.^^');
		// location.href='/ulocawp/?page_id=1352'; //구매결제로 이동 -by jsj 0312 
		// exit;
	}

	if (searchType==1 && form.kwd.value.trim().length<2)
	{
		alert('검색 키워드를 2자리 이상 입력 하세요.');
		form.kwd.focus();
		return;
		//form.kwd.value = ' ';
	} 
	if (searchType==1 && form.kwd.value.trim().indexOf('%%') >=0) // form.kwd.value == '%%')
	{
		alert('검색 키워드를 바르게 입력 하세요.');
		form.kwd.focus();
		return;
	}
	if (searchType==2 && form.compname.value.trim().length<2)
	{			
		alert('기업정보 검색에 입찰 기업명을 2자리 이상 입력 하세요!');
		form.compname.focus();
		return;
	}

	clog('searchajax1 duridx='+duridx+' searchType='+searchType+' form.dminsttNm.value.trim()='+form.dminsttNm.value.trim());
	if ( searchType==2) //duridx == 0 )
	{
		clog('searchajax1 duridx='+duridx+' searchType='+searchType);
	}	

	parm = 'kwd='+encodeURIComponent(form.kwd.value.trim()); //+'&startDate='+encodeURIComponent(sDate1)+'&endDate='+eDate1;
	parm +='&dminsttNm='+encodeURIComponent(form.dminsttNm.value.trim());
	parm +='&compname='+encodeURIComponent(form.compname.value);
	//parm += '&fromDT='+t2+'&toDT='+t1;
	parm += '&curStart='+curStart+'&cntonce='+cntonce;
	curStart += cntonce;
	//parm +='&bidthing=1';
	//parm +='&bidcnstwk=1';
	//parm +='&bidservc=1'; 
	if (searchType==2) parm +='&compinfo=1';
	else parm +='&bidinfo=1';
	parm +='&id='+form.id.value;

	//--------------------------------------------------------
	// 쿠키 저장을 위한 kwd, compName 변수 -by jsj 20200521
	//--------------------------------------------------------
	if (cookieUse == true) {	// 관심 목록 조회 시 전역변수 cookieUse = false로 설정
		// alert (cookieUse);
		var compName = form.compname.value.trim();	// 기업정보
		var kwd = form.kwd.value.trim();	        // 입찰정보
		var cookieKwd = new Array();		        // 쿠키 

		switch (searchType) {
			case 1:	// 입찰정보
				// 쿠기 array 가져옴
				cookieKwd = unescape(getCookieArray('kwd', cookieCnt));
				cookieKwd = cookieKwd.split(',');

				// 동일 키워드 없으면 저장
				if (cookieKwd.indexOf(trim(kwd)) == -1) {
					cookieKwd.unshift(kwd);
					setCookieArray ('kwd', cookieKwd, 30);	// 쿠키저장(30일)

					// 쿠키 입찰정보 표시
					cookieKwd = unescape(getCookieArray('kwd', cookieCnt));
					cookieKwd = cookieKwd.split(',');

					var cookieLink = "";
					for (var i in cookieKwd){
						cntNum = i;	cntNum++;	// 번호 추가
						if (cntNum == 1) cntNum = '[입찰정보] ' + cntNum;
						cookieLink += cntNum + ')<a onclick=\'viewKwd(\"' + cookieKwd[i] + '")\'>' + cookieKwd[i] + '&nbsp</a> ';
						// 마지막에 쿠키 '삭제' 링크 추가
						if (i == cookieKwd.length -1 ) {
							cookieLink += '&nbsp&nbsp → <a onclick=\'delKwd(\"kwd")\'>삭제</a>';
						}
					}
					document.getElementById('bidKwd').innerHTML = cookieLink;
				};
				break;

			case 2:	// 기업검색
				cookieKwd = unescape(getCookieArray('compName', cookieCnt));
				cookieKwd = cookieKwd.split(',');

				if (cookieKwd.indexOf(trim(compName)) == -1) {
					cookieKwd.unshift(compName);
					setCookieArray ('compName', cookieKwd, 7);

					// 쿠키 기업검색 표시
					cookieKwd = unescape(getCookieArray('compName', cookieCnt));
					cookieKwd = cookieKwd.split(',');

					var cookieLink = "";
					for (var i in cookieKwd){
						cntNum = i;	cntNum++;
						if (cntNum == 1) cntNum = '[기업검색] ' + cntNum;
						cookieLink += cntNum + ')<a onclick=\'viewCompKwd(\"' + cookieKwd[i] + '")\'>' + cookieKwd[i] + '&nbsp</a> ';
						// 마지막에 쿠키'삭제' 링크 추가
						if (i == cookieKwd.length -1 ) {
							cookieLink += '&nbsp&nbsp → <a onclick=\'delKwd(\"compName")\'>삭제</a>';
						}
					}
					document.getElementById('compKwd').innerHTML = cookieLink;

					//$("#compKwd").text(decodeURIComponent(unescape(getCookieArray('compName', 5))));
				};
				break;
		}
	} // if cookieUse== true
	cookieUse = true; // 조회 후 쿠키 사용으로 바꿈 (관심 목록은 false 셋팅 후 콜)

	//--------------------------------
	// 통합검색
	//--------------------------------
	move();
	isRun = true;	// 중복 ajax금지
	server="/g2b/datas/publicData_2019.php";
	clog(server+'?'+parm);
	if (form.dminsttNm.value != '') document.getElementById('tables').innerHTML = '';
	getAjaxPost(server, recv, parm);  // g2b/g2b.js -by jsj 20200423
}

var itembalju;
function recv_balju(datas) {
	move_stop();
	SearchCounts ++;
	var data = JSON.parse(datas);
	itembalju = data.response.body.items;

	try
	{
		makeTable(datas);
		searchajax0();
	}
	catch (e)
	{
		alert('[유료서비스]다시 시도해주세요! :: recv_balju='+e.message);
		clog('recv_balju '+data);
	}
	baljusw = false;
}

function viewBalju(i) {
	var form = document.getElementById('searchFormCurrent');
	form.bidNtceNm.value = itembalju[i]['bidNtceNm'];
	form.pss.value = itembalju[i]['pss'];
	form.bidNtceNo.value = itembalju[i]['bidNtceNo']+'-'+itembalju[i]['bidNtceOrd'];
	form.cntrctMthdNm.value = itembalju[i]['cntrctMthdNm'];
	form.orderInsttNm.value = itembalju[i]['dminsttNm'];
	form.nticeDt.value = itembalju[i]['bidNtceDt'];
	form.specCntnts.value = itembalju[i]['specCntnts'];
	form.dtilPrdctClsfcNoNm.value = itembalju[i]['dtilPrdctClsfcNoNm'];
	form.qtyCntnts.value = itembalju[i]['qtyCntnts'];
	form.sumOrderAmt.value = itembalju[i]['presmptPrce'];
	form.unit.value = itembalju[i]['unit'];
	form.telNo.value = itembalju[i]['telNo'];
	form.ofclNm.value = itembalju[i]['ofclNm'];
	form.deptNm.value = itembalju[i]['deptNm'];
	form.bidNtceNm.value = itembalju[i]['bidNtceNm'];
	form.submit();
}

//----------------------------------------------
// 통합/상세 입찰정보 검색 목록 링크 설정 (공통)
//----------------------------------------------
function setLink(items, i, j, cell) {
	//if (i<2) console.log('j='+j+'/'+cell.innerHTML);
	switch (j) {
		case 2: // 공고번호
			//$arr['bidNtceDtlUrl'] 가 없는 경우 viewDtl에 링크 삽입  -by jsj 20181129 
			if (items[i]['bidNtceNo'].length == 6) { // 사전규격
				cell.innerHTML = '<a onclick=\'viewDtls("' + items[i]['bidNtceNo'] + '")\'>' + cell.innerHTML.substr(0, 6) + '</a>';
				return cell.innerHTML;
			}
			if (items[i]['bidNtceDtlUrl'] == '') { // 공고번호
				items[i]['bidNtceDtlUrl'] = 'http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=' + items[i]['bidNtceNo'] + '&bidseq=' + items[i]['bidNtceOrd'] + '&releaseYn=Y&taskClCd=5';
			}
			//clog('setlink '+ (items[i]['pss'].substr(0,2)));
			if (items[i]['pss'].substr(0, 2) != '계획')
				cell.innerHTML = '<a onclick=\'viewDtl("' + items[i]['bidNtceDtlUrl'] + '")\'>' + cell.innerHTML + '</a>';
			else cell.innerHTML = '<a onclick=\'viewBalju(' + i + ')\'>' + cell.innerHTML + '</a>';
			break;
		case 3: // 공고명
			break;
		case 4: // 계약방법
			break;

		case 7: // 수요기관으로 재검색
			cell.innerHTML = '<a onclick=\'viewscs("' + items[i]['dminsttNm'] + '")\'>' + cell.innerHTML + '</a>';
			break;

		case 8: // 낙찰기업 link bidwinnerBizno or '유찰'
			pss = items[i]['pss']; // getPSS(); 사전규격
			if (items[i]['pss'].substr(0, 2) == '사전') {
				return "사전공개일→";
			}
			if (items[i]['progrsDivCdNm'] == '개찰완료') {
				bidwinnrNm = items[i]['bidwinnrNm'];
				bidwinnrNm = bidwinnrNm.replace("주식회사", "");
				bidwinnrNm = bidwinnrNm.replace("(주)", "");
				cell.innerHTML = '<a onclick=\'compInfobyComp("' + items[i]['bidwinnrBizno'] + '")\'>' + bidwinnrNm + '</a>';
				break;
			} else {
				// 유찰인 경우 (유찰사유 nobidRsn ) 표시
				progrsDivCdNm = items[i]['progrsDivCdNm'];
				if (progrsDivCdNm == '유찰') {
					if (items[i]['nobidRsn'] !== '') {
						bidwinnrNm = '유찰<br>(' + items[i]['nobidRsn'] + ')';
					} else {
						bidwinnrNm = '유찰';
					}
				} else if (progrsDivCdNm == '0' || progrsDivCdNm == '') {
					bidwinnrNm = '-';
				} else {	// 재입찰 등 진행현황이 있을 경우 표시
					bidwinnrNm = progrsDivCdNm;
					if (items[i]['nobidRsn'] != '') {
						bidwinnrNm +=  '<br>(' + items[i]['nobidRsn'] + ')'
					}
				}
				return bidwinnrNm;
			}
			// 응찰이력 링크 만듬
			break;

		case 9: // 개찰일시 
			if (today < items[i]['opengDt'].substr(0, 10)) return cell.innerHTML;
			bidwinnrNm = items[i]['progrsDivCdNm']; // 유찰 or 재입찰 외
			pss = items[i]['pss']; // getPSS(); 사전규격
			if (items[i]['pss'].substr(0, 2) == '사전') {
				return cell.innerHTML;
			} else if (bidwinnrNm == '유찰') {
				return cell.innerHTML;
			} else { // 개찰결과 링크
				cell.innerHTML = '<a onclick=\'viewRslt("' + items[i]['bidNtceNo'] + '","' + items[i]['bidNtceOrd'] + '","' + items[i]['opengDt'] + '","' + pss + '","' + resudi + '")\'>' + cell.innerHTML + '</a>';
			}
			break;
	} // switch
	return cell.innerHTML;
}
