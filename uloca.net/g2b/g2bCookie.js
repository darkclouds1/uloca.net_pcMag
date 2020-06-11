var	cookieUse = true;  // 최근 검색 쿠키 저장여부
const cookieCnt = 10;	// 쿠키 갯수
var cookieKwd = new Array();		// 입찰정보 키워드
var cookieCompName = new Array();	// 기업검색 키워드
var cookieBidNoList = new Array();	// 관심공고 번호

//----------------------------------------------------------------
// 쿠키 관심 입찰 목록 조회 (쿠키 -> DB저장) -by 20200601
//-----------------------------------------------------------------
function searchBidNoList(){

	// 로그인 사용자만 관심입찰 조회 가능
	if (resudi == '') {
		alert ("관심입찰 저장/조회는 로그인 후 사용가능합니다.");
		return;
	}
	
	// init()에서 쿠키에 DB -> 쿠키에 들어가 있어야 함.
	// cookieBidNoList.length = 0; // 배열초기화
	cookieBidNoList = unescape(getCookieArray('bidNoList'));
	cookieBidNoList = cookieBidNoList.split(',');

	var bidNoList = '';
	for (var key in cookieBidNoList){
		// if (cookieBidNoList[key] == "undefined" || cookieBidNoList[key] == undefined) continue;					
		if (cookieBidNoList[key] == "undefined") continue;					

		bidNoList += cookieBidNoList[key] + ' ';
	}
	// alert 후 계속 저장 진행
	if (trim(bidNoList) == '') {
		alert ("관심입찰이 없습니다. PC에서 입찰정보 조회 후 \'찜하기\'의 체크박스를 선택하세요.");
		document.getElementById('kwd').focus();
	}

	//--------------------------------------------------------		
	// DB저장 C=업데이터
	//---------------------------------------------------------
	isRun = true;
	url = "./wp-content/plugins/g2b/updateBidNoList.php?bidNoList=" + bidNoList + "&id=" + resudi + "&type=U";	// 관심 입찰번호, id
	getAjax(url, updateDbBidNoList);
}

// 관심 목록 저장, 조회 (DB data)
function updateDbBidNoList (data) {
	var bidNoList = "";
	var form = document.myForm;
    if (!data || data == 0) {
        // 로그오프 or 관심입찰 없음
		delCookie('bidNoList'); // 관심입찰 쿠키삭제
    } else { // 입찰 조회
        cookieUse = false;      // 관심 목록은 키워드 쿠키에 넣지 않음 (cookieUse=false)
        viewKwd(data);
        form.kwd.value = '';	// kwd value는 조회 후 삭제 -by jsj 20200531

		// DB 관심입찰을 쿠키에 저장 (로그인 시)
		cookieBidNoList = data.split(' ');
		if (data != '') {
			setCookieArray ('bidNoList', cookieBidNoList, saveDur);	// saveDur = 30일
		} else {
			delCookie ('bidNoList');
		}
    } 
}

// 찜하기 배열 저장 cookie=bidNo -by jsj 20200531
var saveDur = 30; // 찜하기 저장 일수
function saveCookieBidNo(obj, bidNo){
	// cookieBidNoList = unescape(getCookieArray('bidNoList'));
	// cookieBidNoList = cookieBidNoList.split(',');

	if ($(obj).prop("checked") == true) {
		if (cookieBidNoList.indexOf(trim(bidNo)) == -1) {
			cookieBidNoList.unshift(bidNo); // 공고 추가
		}
	} else { 
		if (cookieBidNoList.indexOf(trim(bidNo)) != -1) {
			cookieBidNoList.splice(cookieBidNoList.indexOf(trim(bidNo)),1);             // 공고 삭제
		}
	}
	// 쿠키에 저장 - undefined 이면 저장이 안되므로 체크 후 삭제 필요
	setCookieArray ('bidNoList', cookieBidNoList, saveDur);
}

/* ------------------------------------------------------------------------------------------
// 입찰정보 검색 - 쿠기 문자열로 검색 -by jsj 20200527
------------------------------------------------------------------------------------------- */
function viewKwd(kwd) {
	frm = document.getElementById('myForm');
	frm.kwd.value = kwd;
	searchType = 1;
	searchajax();
}

/* ------------------------------------------------------------------------------------------
// 기업 검색 - 쿠기 문자열로 검색 -by jsj 20200527
------------------------------------------------------------------------------------------- */
function viewCompKwd(kwd) {
	frm = document.getElementById('myForm');
	frm.compname.value = kwd;
	searchType = 2;
	searchajax();
}

//-----------------------------------------
// 키워드 삭제 후 통합검색 Reload 
// delCd: 0= 0 전체삭제, 1=앞에 1개만 삭제
// searchKwd: kwd
//-----------------------------------------
function delKwd(cname, delCd, kwd, cookieStr) {
	switch (delCd){
		case 0:	// 쿠키 전체 삭제
			delCookie(cname);					// 쿠키전체삭제
			if (cname == 'kwd') {				// 입찰정보
				document.getElementById('bidKwd').innerHTML = '';
			} else if (cname == 'compName') {
				document.getElementById('compKwd').innerHTML = '';
			}
			break;

		case 1:		// 쿠키 1개만 삭제
			// 링크 표시
			var cookieArr = cookieStr.split (",");
			if (cookieArr.indexOf(kwd)  == -1) {
				alert ('삭제할 키워드가 없습니다.');
			} else {
				cookieArr.splice(cookieArr.indexOf(kwd),1);	// 1개 삭제
			}
			setCookieArray (cname, cookieArr, saveDur);	 

			if (cname == 'kwd') {				// 입찰정보
				document.getElementById('bidKwd').innerHTML = makeLink_cookieKwd(cname, cookieArr);
			} else if (cname == 'compName') {	// 기업검색
				document.getElementById('compKwd').innerHTML = makeLink_cookieKwd(cname, cookieArr);
			}
	}
}

function makeLink_cookieKwd (cname, cookieArr){
	var cookieLink = '';
	var cnameTitle;
	if (cname == 'kwd') {
		cnameTitle = '[입찰정보] ';
	} else if (cname == 'compName') {
		cnameTitle = '[기업검색] ';
	}

	var idxUndefined;
	idxUndefined = cookieArr.indexOf("undefined");
	if (idxUndefined != -1) {
		cookieArr.splice(idxUndefined,1);
	}

	for (var i in cookieArr){
		var cntNum = i; cntNum++;

		switch (cname) {
			case 'kwd':
				cookieLink += cntNum + ')<a onclick=\'viewKwd(\"' + cookieArr[i] + '")\'>' + cookieArr[i] + '&nbsp</a> ';
				if (i == cookieArr.length -1 ) {				
					cookieLink += "&nbsp&nbsp&nbsp → <a onclick=\"delKwd(\'kwd\', 1 ,\'" + cookieArr[0] + "\',\'" + cookieArr + "\')\;\">삭제</a>";
					if (cookieArr.length != 1 ) { 
						cookieLink += "&nbsp&nbsp&nbsp<a onclick=\"delKwd(\'kwd\',0)\;\">※전체삭제</a>";
					}
				}
				break;

			case 'compName':
				cookieLink += cntNum + ')<a onclick=\'viewCompKwd(\"' + cookieArr[i] + '")\'>' + cookieArr[i] + '&nbsp</a> ';
				if (i == cookieArr.length -1 ) {
					cookieLink += "&nbsp&nbsp&nbsp → <a onclick=\"delKwd(\'compName\', 1 ,\'" + cookieArr[0] + "\',\'" + cookieArr + "\')\;\">삭제</a>";
					if (cookieArr.length != 1 ) { 
						cookieLink += "&nbsp&nbsp&nbsp<a onclick=\"delKwd(\'compName\',0)\;\">※전체삭제</a>";
					}
				}
				break;
		}
	}
	if (cookieLink != '') cookieLink = cnameTitle + cookieLink;
	return cookieLink;
}

// 키워드 쿠키저장 -by jsj 20200521
function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	const SameSite = "; SameSite=Lax ; Secure";
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	var expires = "; expires=" + d.toUTCString();
	document.cookie = cname + "=" + escape(cvalue) + SameSite + expires;
}

// 쿠키 가져오기
function getCookie(cname) {
	// 변수를 선언한다.
	var cookies = document.cookie.split(";");
	for(var i in cookies) {
		if(cookies[i].search(cname) !== -1) {
			return (cookies[i].replace(cname + "=", ""));
		}
	}
}

// 쿠키 삭제
function delCookie(cname) {
	setCookie(cname);
}

// 배열데이타 쿠키 저장
function setCookieArray(cname, carray, exdays) {
	var str = '';
	for (var key in carray) {
		if (carray[key] == "undefined" || carray[key] == undefined || carray[key] == '') continue;
		if (str != "") str += ",";
		str += carray[key];
    }

    if (str == '') {
		delCookie(cname);
		return;
	}
    str = encodeURIComponent(str);
    this.setCookie(cname, str, exdays);
}

// 쿠키에서 배열로 저장된 데이타 가져옴
function getCookieArray(cname, retCnt=0) {
	var str = decodeURIComponent(unescape(this.getCookie(cname)));
	var carray = new Array();
	
	carray.length = 0;	// 배열초기화
	if (retCnt == 0) {	// 전체 다가져옴.
		carray = str.split(',');	//
	} else {
		carray = str.split(',', retCnt);	// @retCnt = 가져올 갯수
	}
	// 배열 문자열 모두 trim해야 중복키워드 비교가 됨
	for (var key in carray) {
		if (carray[key] == "undefined" || carray[key] == undefined) continue;
		carray[key] = trim(carray[key]);
	}
	return carray;
}


//-----------------
// 공백 제거
//-----------------
function rtrim(str)
{
   return str.replace( /\s*$/g, "" );
}

function ltrim(str)
{
   return str.replace( /^\s*/g, "" );
}

// 앞뒤 공백문자열을 제거
function trim(str)
{
  return str.replace(/(^\s*)|(\s*$)/gi, "");
}