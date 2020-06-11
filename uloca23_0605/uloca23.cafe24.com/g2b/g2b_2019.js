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
var curStart = 0;
var cntonce = 100;
var cookieUse = true;

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

//-------------------------------------
//통합검색  -by jsj 0312
//-------------------------------------
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

	if (searchType==1 && form.kwd.value.trim().length<2){
		alert('검색 키워드를 2자리 이상 입력 하세요.');
		form.kwd.focus();
		return;
	} 

	if (searchType==1 && form.kwd.value.trim().indexOf('%%') >=0) {
		alert('검색 키워드를 바르게 입력 하세요.');
		form.kwd.focus();
		return;
	}

	if (searchType==2 && form.compname.value.trim().length<2) {			
		alert('기업정보 검색에 입찰 기업명을 2자리 이상 입력 하세요!');
		form.compname.focus();
		clog('searchajax1 duridx='+duridx+' searchType='+searchType);
		return;
	}
	clog('searchajax1 duridx='+duridx+' searchType='+searchType+' form.dminsttNm.value.trim()='+form.dminsttNm.value.trim());
	
	parm = 'kwd='+encodeURIComponent(form.kwd.value.trim()); //+'&startDate='+encodeURIComponent(sDate1)+'&endDate='+eDate1;
	parm +='&dminsttNm='+encodeURIComponent(form.dminsttNm.value.trim());
	parm +='&compname='+encodeURIComponent(form.compname.value);
	//parm += '&fromDT='+t2+'&toDT='+t1;
	parm += '&curStart='+curStart+'&cntonce='+cntonce;
	curStart += cntonce;
	//parm +='&bidthing=1';
	//parm +='&bidcnstwk=1';
	//parm +='&bidservc=1'; 
	if (searchType==2){
		parm +='&compinfo=1';
	} else { 
		parm +='&bidinfo=1';
	}
	parm +='&id='+form.id.value;

	// 쿠키 저장을 위한 kwd, compName 변수 -by jsj 20200521
	if (cookieUse == true) {	// 관심 목록 조회 시 전역변수 cookieUse = false로 설정
		cookieDisp(searchType);
	} 

	move();
	isRun = true;	// 중복 ajax금지
	server="/g2b/datas/publicData_2019.php";
	clog(server+'?'+parm);
	if (form.dminsttNm.value != '') document.getElementById('tables').innerHTML = '';
	getAjaxPost(server, recv, parm);  // g2b/g2b.js -by jsj 20200423
}

function searchComp(data){
	//searchUrl = searchUrl_path + '?page_id=1134&searchType=2&' + parm;			
	parm = '&searchType=2&' + parm; //copyURL() 사용 -by jsj 190320

	if (mobile == "Computer") {
		makeTableHead(colc, colc2, colc3, colcw);
		makeTabletrCompany(data);
		document.getElementById('useExplain').innerHTML = "<font size='2em'>✔︎︎[기업검색과 대표자명 동시검색] 기업명? 대표자명 → [?]물음표 뒤에 대표자명을 입력하세요.</font> "; //-by jsj 링크설명 
		document.getElementById('linkExplain').innerHTML = "<font size='2em'>✔︎︎[사업자번호]클릭→기업의응찰기록  <font color=red>✔︎︎[업체명]</font>클릭→업체정보팝업 </font>"; //-by jsj 링크설명 
		document.getElementById('totalrec').innerHTML = "<font size='2em'>[" + String(SearchCounts) + "]total record=" + idx;
		setSort();
	} else {
		idx = 0;
		makeTabletrCompany2(data);
		document.getElementById('totalrec').innerHTML = "<font size='2em'>[" + String(SearchCounts) + "]total record=" + idx;
	}
	// chart clear
	// viewmore(); // <-- 상세검색에만 있음
	nochart();
	clog('makeTable5');
	return;	
}

var table1 = new Object();
function makeTableHead(column, column2, columnattr, columnw) {
	// var head = document.getElementById('tables').innerHTML;
	//clog('searchType='+searchType+' head='+head);
	if (searchType == 1 && head != '') return; // 제목란이 있으면..
	table1 = document.createElement("table");
	table1.setAttribute('class', 'type10');
	table1.setAttribute('id', 'specData');
	table1.setAttribute('style', 'width:100%'); //'width', '700px');
	//clog('makeTableHead '+document.getElementById('specData').innerHTML);
	var header = table1.createTHead();
	var tr = header.insertRow(-1); // TABLE ROW.

	for (var i = 0; i < column.length; i++) {
		var th = document.createElement("th"); // TABLE HEADER.
		if (column[i] == 'check') th.innerHTML = '<input type="checkbox" onclick="javascript:CheckAll(\'' + chkid + '\')">';
		else if (i != 0) th.innerHTML = column[i] + '<a onclick="sortTD (' + i + ')">▲</a><a onclick="reverseTD (' + i + ')">▼</a>';
		else th.innerHTML = column[i];
		th.setAttribute('style', 'width:' + columnw[i] + ';');
		tr.appendChild(th);
		//clog(th.innerHTML);
	}
	if (document.getElementById('tables').innerHTML = '') document.getElementById('tables').innerHTML = table1.outerHTML;
	idx = 0;
}

// 검색데이터 전역변수로
var items;	
function makeTabletr(datas) {
	// ADD JSON DATA TO THE TABLE AS ROWS.
	var data = JSON.parse(datas);
	items = data.response.body.items;
	//if (items.length>1) items.sort(custonSort);

	// 찜한 공고번호 cookie에서 가져오기
	cookieKwd = unescape(getCookieArray('bidNoList'));
	cookieKwd = cookieKwd.split(',');

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

					if (cookieKwd.indexOf(trim(items[i]['bidNtceNo'])) == -1 ) {
						//tabCell.innerHTML = "<input type=\'checkbox\' name=\'saveChkBox" +i+ "\' unchecked onclick=\'saveCookieBidNo(this);>";
						tabCell.innerHTML = "<input type=checkbox name=saveChkBox" + idx + " unchecked onclick=saveCookieBidNo(this\,\'"+trim(items[i]['bidNtceNo'])+ "\'\)\;\>";
					} else {
						// 찜한 공고번호
						tabCell.innerHTML = "<input type=checkbox name=saveChkBox" + idx + " checked onclick=saveCookieBidNo(this\,\'"+trim(items[i]['bidNtceNo'])+ "\'\)\;\>";
					}
				}
			} // column 

		} catch (ex) {}
		//total record 뒤에 검색 URL 표시 -by jsj 190320
		var useExplain =  "<font size='2em'>✔︎︎[공고명검색] 입찰정보를 검색하려면 공고명에 포함된 단어(=키워드)를 입력하세요. 제외하려면 키워드 앞에[-]마이너스를 사용하세요.(ex.정보 감리 -공사)</br>";
		useExplain    += " ✔︎︎[수요기관검색] 공고명 ?수요기관  → [?]물음표 뒤에 수요기관 키워드를 입력하세요.(ex.정보 감리 ?서울)</br>"
		useExplain    += " ✔︎︎[계약방법검색] 공고명 ??계약방법 → [??]물음표 2개 뒤에 계약방법 키워드를 입력하세요.(ex. 정보 감리 ??총액), 제외하려면 (ex.정보 감리 ??-협상) [-]마이너스를 사용하세요.</font>"; //-by jsj 링크설명 
		if (loginSW) useExplain    = ''; // 1:관리자 or 유료사용자는 설명없이 진행
		document.getElementById('useExplain').innerHTML = useExplain;
		document.getElementById('totalrec').innerHTML = "<font size='2em'>[" + String(SearchCounts) + "]total record=" + idx;

		clog('val=' + val + ' table1.rows.length=' + table1.rows.length);
		if (val == 'bid' && table1.rows.length > 2) {
			setSort();
		}
	}

	// FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
	var divContainer = document.getElementById("tables");
	//divContainer.innerHTML = "";
	divContainer.appendChild(table1);
}

var itembalju;
function recv_balju(datas) {
	move_stop();
	SearchCounts ++;
	var data = JSON.parse(datas);
	itembalju = data.response.body.items;

	try	{
		makeTable(datas);
		searchajax0();
	} catch (e)
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
				cell.innerHTML = '<a onclick=\'viewRslt("' + items[i]['bidNtceNo'] + '","' + items[i]['bidNtceOrd'] + '","' + items[i]['opengDt'] + '","' + pss + '","' + userId + '")\'>' + cell.innerHTML + '</a>';
			}
			break;
	} // switch
	return cell.innerHTML;
}

// 기업정보 링크
function setLinkcomp(items, i, j, cell) { // 기업정보 
	//if (i<2) clog('j='+j+'/'+cell.innerHTML);

	if (j == 1) {
		//cell.innerHTML = '<a href="http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='+items[i]['bidNtceNo']+'&bidseq='+items[i]['bidNtceOrd']+'&releaseYn=Y&taskClCd=5" target="_blank">' + cell.innerHTML + '</a>';
		cell.innerHTML = '<a onclick=\'compInfobyComp("' + items[i]['compno'] + '")\'>' + cell.innerHTML + '</a>';
	} else if (j == 2) {
		cell.innerHTML = '<a onclick=\'compInfo("' + items[i]['compno'] + '")\'>' + cell.innerHTML + '</a>';
	}
	return cell.innerHTML;
}

function makeTabletrCompany2(datas) { // mobile 사전규격
	if (datas == '') return;
	var data;
	try {
		data = JSON.parse(datas);
	} catch (ex) {
		return;
	}

	//r = data.response;
	//alert('makeTabletr2bid');
	items = data.response.body.items;
	//items.sort(custonSort);
	var lic = document.getElementById("tables").innerHTML;
	var licn = '';
	for (var i = 0; i < items.length; i++) {
		try {
			// colc2 = [ '','compno', 'compname', 'repname', 'cnt' ];
			idx = idx + 1;
			licn += '<li><a  class="a1"  onclick=openButton(' + idx + ')>';
			//licn += items[i]['compname']+'<br /> <font class="f1">- ' + items[i]['compno']+ '대표 : '+items[i]['repname'] + ' 응찰 : '+ items[i]['cnt'] + '</font> </a>';
			licn += items[i]['compname'] + '<br /> <font class="f1">- ' + items[i]['compno'] + ' 대표 : ' + items[i]['repname'] + '</font> </a>';
			licn += '<div id="link' + idx + '" style="display:none;">';
			licn += '<center><p style="LINE-HEIGHT: 102%"><input type=button value="응찰기록" onclick=\'bidInfo("' + items[i]['compno'] + '")\'>&nbsp;<input type=button value="업체정보" onclick=\'compInfo("' + items[i]['compno'] + '")\'><br>';

			licn += '</div>';
			licn += '</li>';
			//clog('licn = '+licn);			
			//pss = getPSS();

			licn += '</div>';
			licn += '</li>';
		} catch (ex) {}

	}
	lic += licn;
	//alert(lic);
	document.getElementById("tables").innerHTML = lic;
}

function recv(data) {
	move_stop();
	SearchCounts++; //무료검색횟수 count+ -by jsj 0314
	try {
		// 기업검색, 통합검색, 상세검색 makeTable()나눠야 함.
		makeTable(data);

	} catch (e) {
		alert('ln1428::데이타에 에러가 있는것 같습니다. 관리자에게 문의하세요.' + e.message);
		clog(data);
	}
}

//--------------
// sort Table
//--------------
var bidDataTable;
var sortreplace;

function setSort() {
	// bidDataTable = document.getElementById("specData");
	bidDataTable = table1;
	sortreplace = replacement(bidDataTable);
}

function sortTD(index) {
	sortreplace.ascending(index);
}

function reverseTD(index) {
	sortreplace.descending(index);
}