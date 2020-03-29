
//----------------------------------------
//  통합검색 
//  -by jsj 190320 
//----------------------------------------
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
	} else searchajax0();		//입찰기업 검색 
	duridx = -1;
}
	
function searchajax0_1() {   //주석 달아주세요 -by jsj 0312
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
function searchajax_balju() {	//통합검색-입찰공고 검색  ( -by jsj 0415 발주계획 
	// var form = document.historyForm;
	// SearchCounts = 0;
	var form = document.myForm;
	if (form.id.value == '' && (SearchCounts > searchCountMax))	{
		alert (String(SearchCounts)+'::무료 검색 횟수가 초과 했습니다. 로그인 하십시요. '); //-by jsj 0312
		location.href='/ulocawp/?page_id=325';	//로그인메뉴로 이동 
		exit;
	}
	else if (loginSW == 0 && (SearchCounts > (searchCountMax + searchLoginPlus))) {
		//alert(String(loginSW));
		alert(String(SearchCounts) + '::회비 납부 기간이 지났습니다.[구매결제]' ); //+SearchCounts);
		location.href='/ulocawp/?page_id=1352'; //구매결제로 이동 -by jsj 0312 
		exit;
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
		//form.kwd.value = ' ';
	}
	//alert(form.compname.value.length);
	//return;
	if (searchType==2 && form.compname.value.trim().length<2)
	{
		alert('기업정보 검색에 입찰기업명을 2자리 이상 입력 하세요.');
		form.compname.focus();
		return;
	}
	if (duridx == 0 || searchType==2)
	{
		//document.getElementById('tables').innerHTML = '';
		//document.getElementById('totalrec').innerHTML = '';
		//durationIndex = 0;
	}	
	//clog('ajax 1 sDate1='+sDate1+' eDate1='+eDate1+' endSw ='+ endSw+' durationIndex='+durationIndex+' ddur='+ddur+' ajaxCnt='+ajaxCnt);
	parm = 'kwd='+encodeURIComponent(form.kwd.value.trim()); //+'&startDate='+encodeURIComponent(sDate1)+'&endDate='+eDate1;

	// 통합검색 '서버' 키워드 검색 안됨, '서버'인 경우만 키워드+'%'임시로 추가 -by jsj 19317    
	/*if (form.kwd.value.trim() == '서버'){
			//parm += '%'; //정상 작동되어 원복 -by jsj 190320
	}
	*/
	
	parm +='&dminsttNm='+encodeURIComponent(form.dminsttNm.value.trim());
	//parm +='&compname='+encodeURIComponent(form.compname.value);
	//parm += '&fromDT='+t2+'&toDT='+t1;
	//parm += '&curStart='+curStart+'&cntonce='+cntonce;
	//if (searchType==2) parm +='&compinfo=1';
	//else parm +='&bidinfo=1';

	parm +='&id='+form.id.value;
	baljusw = true;
	server="/g2b/g2bOrder.php";
	
	clog(server+'?'+parm);
	
	if (form.dminsttNm.value != '') document.getElementById('tables').innerHTML = '';
	
	move();
	//------------------------------------
	getAjaxPost(server,recv_balju,parm);
	//------------------------------------
	// http://uloca23.cafe24.com/g2b/datas/publicData_2019.php?kwd=%EB%B6%80%EC%82%B0&compname=&fromDT=0&toDT=2019-01-10&curStart=0&cntonce=1000&bidinfo=1&id=blueoceans
}

//통합검색 - 기업검색   -by jsj 0312
function searchajax1(t1,t2) {
	//var form = document.historyForm;
	var form = document.myForm;
	//$Searchcounts = 0; //검색횟수 삭제 -by jsj 0313 
	if (form.id.value == '' && (SearchCounts > searchCountMax))	{
		alert (String(SearchCounts)+'::무료 검색 횟수가 초과 했습니다. 로그인 하십시요. '); //-by jsj 0312
		location.href='/ulocawp/?page_id=325';	//로그인메뉴로 이동 
		exit;
	}
	else if (loginSW == 0 && (SearchCounts > (searchCountMax + searchLoginPlus))) {
		//alert(String(loginSW));
		alert(String(SearchCounts) + '::회비 납부 기간이 지났습니다.[구매결제]' ); //+SearchCounts);
		location.href='/ulocawp/?page_id=1352'; //구매결제로 이동 -by jsj 0312 
		exit;
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
		//form.kwd.value = ' ';
	}
	//alert(form.compname.value.length);
	//return;
	if (searchType==2 && form.compname.value.trim().length<2)
	{			
		alert('기업정보 검색에 입찰기업명을 2자리 이상 입력 하세요!');
		form.compname.focus();
		return;
	}
	//기업검색 로그인 후 무료 검색횟수 체크 
	else if (loginSW ==0 && (SearchCounts > (searchCountMax + searchLoginPlus))) { //+10 로그인후 10회 무료 
		alert('회비 납부 기간이 지났습니다.[구매결제]'); //+SearchCounts);
		location.href='/ulocawp/?page_id=1352'; //-by jsj 0312 
		exit;
	}

	clog('searchajax1 duridx='+duridx+' searchType='+searchType+' form.dminsttNm.value.trim()='+form.dminsttNm.value.trim());
	if ( searchType==2) //duridx == 0 )
	{
		clog('searchajax1 duridx='+duridx+' searchType='+searchType);
		//document.getElementById('tables').innerHTML = '';
		//document.getElementById('totalrec').innerHTML = '';
		//durationIndex = 0;
	}	
	//clog('ajax 1 sDate1='+sDate1+' eDate1='+eDate1+' endSw ='+ endSw+' durationIndex='+durationIndex+' ddur='+ddur+' ajaxCnt='+ajaxCnt);

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

	server="/g2b/datas/publicData_2019.php";
	//server="/datas/publicData.php";
	clog(server+'?'+parm);
	if (form.dminsttNm.value != '') document.getElementById('tables').innerHTML = '';
	
	move();
	//--------------------------------
	// 통합검색
	//--------------------------------
	getAjaxPost(server, recv, parm);  // g2b/g2b.js -by jsj
	//--------------------------------
	// http://uloca23.cafe24.com/g2b/datas/publicData_2019.php?kwd=%EB%B6%80%EC%82%B0&compname=&fromDT=0&toDT=2019-01-10&curStart=0&cntonce=1000&bidinfo=1&id=blueoceans
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
/*	<input id="bidNtceNm" name="bidNtceNm" type="hidden" value="사업명"/>
	<input id="pss" name="pss" type="hidden" value="업무"/>
	<input id="bidNtceNo" name="bidNtceNo" type="hidden" value="2019-02"/>
	<input id="cntrctMthdNm" name="cntrctMthdNm" type="hidden" value="계약방법"/>
	<input id="orderInsttNm" name="orderInsttNm" type="hidden" value="발주기관"/>
	<input id="info" name="info" type="hidden" value="나라장터"/>
	<input id="nticeDt" name="nticeDt" type="hidden" value="게시일시"/>
	<input id="specCntnts" name="specCntnts" type="hidden" value="용도"/>
	<input id="dtilPrdctClsfcNoNm" name="dtilPrdctClsfcNoNm" type="hidden" value="품명"/>
	<input id="qtyCntnts" name="qtyCntnts" type="hidden" value="수량"/>
	<input id="sumOrderAmt" name="sumOrderAmt" type="hidden" value="구매예정금액"/>
	<input id="unit" name="unit" type="hidden" value="수량단위"/>
	<input id="telNo" name="telNo" type="hidden" value="전화번호"/>
	<input id="ofclNm" name="ofclNm" type="hidden" value="담당자"/>
	<input id="deptNm" name="deptNm" type="hidden" value="부서명"/> */
	
	//clog('viewBalju i='+i+itembalju[i]['bidNtceNm']);
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
// 사업명 bidNtceNm, 업무 pss, 발주시기 bidNtceNo-bidNtceOrd
// 계약방법 cntrctMthdNm,발주기관 orderInsttNm,게시일시 nticeDt,용도 specCntnts,품명 dtilPrdctClsfcNoNm,수량 qtyCntnts
// 구매예정금액 sumOrderAmt, 수량단위 unit, 전화번호 telNo,담당자 ofclNm,부서명 deptNm
}