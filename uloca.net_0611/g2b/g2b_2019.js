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
	if (cookieUse == true) {	// 관심 목록 조회 시 전역변수 cookieUse = false로 설정 됨
		// alert (cookieUse);
		var compName = form.compname.value.trim();	// 기업정보
		var kwd = form.kwd.value.trim();	        // 입찰정보

		switch (searchType) {
			case 1:	// 입찰정보
				// 쿠기 array 가져옴
				cookieKwd = unescape(getCookieArray('kwd', cookieCnt));
				cookieKwd = cookieKwd.split(',');
				// 동일 키워드 없으면 저장
				if (cookieKwd.indexOf(trim(kwd)) == -1) {
					cookieKwd.unshift(kwd);
					setCookieArray ('kwd', cookieKwd, 30);										// 쿠키저장(30일)
				};
				document.getElementById('bidKwd').innerHTML = makeLink_cookieKwd('kwd', cookieKwd);	// 링크 표시
				break;

			case 2:	// 기업검색
				cookieCompName = unescape(getCookieArray('compName', cookieCnt));
				cookieCompName = cookieCompName.split(',');
				if (cookieCompName.indexOf(trim(compName)) == -1) {
					cookieCompName.unshift(compName);
					setCookieArray ('compName', cookieCompName, 30);
				};
				document.getElementById('compKwd').innerHTML = makeLink_cookieKwd('compName', cookieCompName);
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