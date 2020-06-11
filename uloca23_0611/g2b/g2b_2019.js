//----------------------------------------
//  통합검색 -by jsj 190320 
//----------------------------------------
// 쿠키 전역변수, -by jsj 20200601
// 관심목록(Reload시)은 자동 검색되므로 false로 설정->  최초조회시 검색 쿠키 저장 막음
searchType      = 1; 				// 1:입찰정보 2:기업정보
searchCountMax  = 5;  				// 무료검색횟수 	-by jsj 0314 
searchLoginPlus = 5; 				// 로그인후 무료검색횟수 -by jsj 0314
duridx = -1;
searchDuration = [ -1, -3, -6, -12, -24, -999 ];
parm = '';

// 통합검색 시작
function searchajax() {
	duridx = -1;
	curStart = 0;
	clog('searchajax searchType='+searchType);
	document.myForm.dminsttNm.value = '';

	// table 지움
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
	if ( userId == '' && (SearchCounts > searchCountMax))	{
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
		alert('검색 키워드을 2자리 이상 입력 하세요.');
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
	parm +='&id='+userId;
	baljusw = true;
	server="/g2b/g2bOrder.php";
	clog(server+'?'+parm);

	// if (form.dminsttNm.value != '') document.getElementById('tables').innerHTML = '';

	move();
	getAjaxPost(server,recv_balju,parm);
}

//시작 = 통합검색 - 기업검색   -by jsj 0312
function searchajax1(t1,t2) {
	var form = document.myForm;
	if (userId == '' && (SearchCounts > searchCountMax))	{
		alert (String(SearchCounts)+'::무료 검색 횟수가 초과 했습니다. 로그인 하십시요. '); //-by jsj 0312
		location.href='/ulocawp/?page_id=325';	//로그인메뉴로 이동 
		exit;
	}
	else if (loginSW == 0 && (SearchCounts > (searchCountMax + searchLoginPlus))) {
		alert('[' + String(SearchCounts) + '구매결제]회비 납부 기간이 지났습니다. 서비스운영을 위한 구독료 or Donation이 필요합니다.^^');
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
	if (searchType==2) {
		parm +='&compinfo=1';
	} else {
		parm +='&bidinfo=1';
		parm +='&id='+userId;
	} 

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
					cookieKwd.push(kwd);
					setCookieArray ('kwd', cookieKwd, 30);										// 쿠키저장(30일)
				};
				document.getElementById('bidKwd').innerHTML = makeLink_cookieKwd('kwd', cookieKwd);	// 링크 표시
				break;

			case 2:	// 기업검색
				cookieCompName = unescape(getCookieArray('compName', cookieCnt));
				cookieCompName = cookieCompName.split(',');
				if (cookieCompName.indexOf(trim(compName)) == -1) {
					cookieCompName.push(compName);
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
	server="/g2b/datas/publicData_2019.php?searchType=1";	// searchtype=1: 통합검색, 2:상세검색
	clog(server+'?'+parm);

	// if (form.dminsttNm.value != '') document.getElementById('tables').innerHTML = '';
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

function makeTabletr(datas) {
	// ADD JSON DATA TO THE TABLE AS ROWS.
	var data = JSON.parse(datas);
	items = data.response.body.items;

	// 찜한 공고번호 cookie에서 가져오기
	// cookieBidNoList = unescape(getCookieArray('bidNoList'));
	// cookieBidNoList = cookieBidNoList.split(',');

	var tbody = table1.createTBody();
	for (var i = 0; i < items.length; i++) {
		try {
			//clog('col.length='+col.length);
			idx = idx + 1;
			tr = tbody.insertRow(-1);
			var tabCells = tr.insertCell(-1);
			tabCells.innerHTML = idx;
			tabCells.setAttribute('style', 'text-align:center;');
			for (var j = 1; j < col.length; j++) {
				var tabCell = tr.insertCell(-1);
				if (col2[j] == 'opengDt' && items[i]['opengDt'] == '') {
					tabCell.innerHTML = '';
					continue;
				}

				tabCell.innerHTML = items[i][col2[j]];
				attr = '';
				if (col3[j] == 'c') attr += 'text-align:center;';
				else if (col3[j] == 'l') attr += 'text-align:left;';
				else if (col3[j] == 'r') {
					attr += 'text-align:right;';
					if (tabCell != null) tabCell.innerHTML = tabCell.innerHTML.format();
				} else if (col3[j] == 'd') {
					attr += 'text-align:center;';
					if (tabCell != null && tabCell.innerHTML.length > 10) tabCell.innerHTML = tabCell.innerHTML.substr(0, 10);
				}

				tabCell.setAttribute('style', attr);
				if (col2[j] == 'bidNtceNo') tabCell.innerHTML += '-' + items[i]['bidNtceOrd'];
				// 공고명 뒤에 계약방법 추가 -by jsj 20200511
				// if (col2[j] == 'bidNtceNm') tabCell.innerHTML += ' (' + items[i]['cntrctCnclsMthdNm'] + ')';
				tabCell.innerHTML = setLink(items, i, j, tabCell);

				// 찜하기 chkBox
				if (col2[j] == 'check') {
					// alert (trim(items[i]['bidNtceNo']));
					// alert ("indexOf=" +cookieKwd.indexOf(trim(items[i]['bidNtceNo'])) + ", cookie=" + cookieKwd);

					if (cookieBidNoList.indexOf(trim(items[i]['bidNtceNo'])) == -1 ) {
						//tabCell.innerHTML = "<input type=\'checkbox\' name=\'saveChkBox" +i+ "\' unchecked onclick=\'saveCookieBidNo(this);>";
						tabCell.innerHTML = "<input type=checkbox name=saveChkBox" + idx + " unchecked onclick=saveCookieBidNo(this\,\'"+trim(items[i]['bidNtceNo'])+ "\'\)\;\>";
					} else {
						// 찜한 공고번호
						tabCell.innerHTML = "<input type=checkbox name=saveChkBox" + idx + " checked onclick=saveCookieBidNo(this\,\'"+trim(items[i]['bidNtceNo'])+ "\'\)\;\>";
					}
				}
			} // column 

		} catch (ex) {}

	}

	// FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
	var divContainer = document.getElementById("tables");
	//divContainer.innerHTML = "";
	divContainer.appendChild(table1);
}

//----------------------------
//bitly Copy URL -by jsj 190325
//----------------------------
function shortURL(link) {
	format = 'txt';
	login = 'enable21';
	apiKey = 'R_cb94c2cd92bba988984791acf7704b6e';
	
	bitlyUrl = 'https://api-ssl.bitly.com/v3/shorten?login='+login+'&apiKey='+apiKey+'&longUrl='+encodeURIComponent(link)+'&format='+format;
	//-------------------------------------
	getAjax_bitly(bitlyUrl, callBack_Url);
	//-------------------------------------
}

function getAjax_bitly(bitlyUrl, callBack) {
 	var xhttp = new XMLHttpRequest();
 	xhttp.onreadystatechange = function() {
    	if (this.readyState == 4 && this.status == 200) {
    		callBack(this.responseText);
    	} else if (this.status == 504) {
			alert('Bitly Time-out Error...');
		}
	};
	xhttp.open("GET", bitlyUrl, true);
	xhttp.send();
}

function callBack_Url(data) {
	//prompt('Ctrl+C를 눌러 아래의 URL을 복사하세요:', data);
	prompt('Ctrl+C를 눌러 URL을 전달하세요!', data);
}


