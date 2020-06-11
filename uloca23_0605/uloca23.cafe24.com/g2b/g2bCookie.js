const cookieCnt = 10;		// 쿠키 갯수
var saveDur     = 30; 		// 찜하기 저장 일수
var cookieKwd   = new Array();		// 입찰키워드 쿠키배열
var cookieCompName = new Array();	// 기업명 쿠키배열
var cookieBidNoList = new Array();	// 관심입찰 쿠키배열(공고번호)

//----------------------------------
// 관심 목록 저장, 조회 (DB data)
//----------------------------------
function updateDbBidNoList (data) {
    var form = document.myForm;
    // alert ("관심목록=" + data);
    if (data == false) {
        // 로그오프 or 관심입찰 없음
        alert ("관심입찰이 없습니다. PC에서 입찰정보 조회 후 \'찜하기\'의 체크박스를 선택하세요.");
    } else { 
        var err_str = 'err sql='; // sql error 인 경우
        delCookie('bidNoList'); // 관심입찰 쿠키삭제
        if (data.indexOf('err sql=') == 0) {
            alert (data);
			alert ("쿠키정보 오류로 관심입찰 데이터를 초기화 하였습니다. PC에서 입찰정보 조회 후 \'찜하기\'의 체크박스를 선택 후 [관심입찰] 버튼을 클릭하세요.");
            return;
        }

        // 입찰 조회
        cookieUse = false;      // 관심 목록은 키워드 쿠키에 넣지 않음 (cookieUse=false)
        viewKwd(data);
        form.kwd.value = '';	// kwd value는 조회 후 삭제 -by jsj 20200531

        // DB 관심입찰을 쿠키에 저장 (로그인 시)
        cookieBidNoList.length = 0;
        cookieBidNoList = data.split(' ');
        setCookieArray ('bidNoList', cookieBidNoList, saveDur);	// saveDur = 30일
	} 
	cookieUse = true;		// 관심입찰 조회 후에는 항상 검색키워드 조회 되도록 함.
}

// 찜하기 배열 저장 cookie=bidNo -by jsj 20200531
function saveCookieBidNo(obj, bidNo){
	cookieBidNoList.length = 0; // 배열초기화
	cookieBidNoList = unescape(getCookieArray('bidNoList'));	
	cookieBidNoList = cookieBidNoList.split(',');

	if ($(obj).prop("checked") == true) {
		if (cookieBidNoList.indexOf(trim(bidNo)) == -1) {
			cookieBidNoList.unshift(bidNo); // 공고 추가
		}
	} else { 
		if (cookieBidNoList.indexOf(trim(bidNo)) != -1) {
			delete cookieBidNoList[cookieBidNoList.indexOf(trim(bidNo))];               // 공고 삭제
		}
	}
	if (cookieBidNoList.length == 1 && cookieBidNoList[0] == undefined) {
		delCookie('bidNoList');
		return;
	}
	// 쿠키에 저장
	setCookieArray ('bidNoList', cookieBidNoList, saveDur);
}

// 쿠키 관심 입찰 목록 조회 (동시에 DB저장) -by 20200601
var bidNoList = ''; // DB에서 관심 목록 저장 후 조회 함
function searchBidNoList(){

	// 로그인 사용자만 관심입찰 조회 가능
	if (userId == '') {
		alert ("관심입찰 저장/조회는 로그인 후 사용가능합니다.");
		return;
	}
	
	// init()에서 쿠키에 DB -> 쿠키에 들어가 있어야 함.
	cookieBidNoList.length = 0; // 배열초기화
	cookieBidNoList = unescape(getCookieArray('bidNoList'));
	cookieBidNoList = cookieBidNoList.split(',');

	bidNoList = '';
	for (var key in cookieBidNoList){
		if (cookieBidNoList[key] == "undefined" || cookieBidNoList[key] == undefined) continue;					
		bidNoList += cookieBidNoList[key] + ' ';
	}
	if (trim(bidNoList) == '') {
		document.getElementById('kwd').focus();
	}
	
	//--------------------------------------------------------		
	// DB저장 후 관심 목록 조회 type: C=업데이터, R=읽기
	//---------------------------------------------------------
	isRun = true;
	url = "./wp-content/plugins/g2b/updateBidNoList.php?bidNoList=" + bidNoList + "&userId=" + userId + "&type=" +"C";	// 관심 입찰번호, userId
	getAjax(url, updateDbBidNoList);
	// console.log(url);
}

// ---------------------------
// 최근 검색 쿠키 - 저장 & 표시
// ---------------------------
function cookieDisp(searchType){
	// var compName = form.compname.value.trim();	// 기업정보
	// var kwd = form.kwd.value.trim();	        // 입찰정보
	switch (searchType) {
		case 1:	// 입찰정보
			searchCookieKwd();
			break;
		case 2:	// 기업검색
			searchCookieCompName();
			break;
	}
	cookieUse = true; // 쿠키 설정 후 항상 true 재설정 (관심 목록은 false 셋팅함)
} 

// 쿠키 입찰 검색
function searchCookieKwd (kwd) {
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
				// 0:전체 삭제, 1:1개삭제
				delStr = "&nbsp&nbsp → <a onclick=\"delKwd(\'kwd\',1,1,\'" + kwd +"\')\;\">삭제</a>";
				cookieLink += delStr;
				alert (delStr);
			}
		}
		document.getElementById('bidKwd').innerHTML = cookieLink;
	};
}

// 쿠키 기업 검색
function searchCookieCompName (compName) {
	cookieCompName = unescape(getCookieArray('compName', cookieCnt));
	cookieCompName = cookieCompName.split(',');

	if (cookieCompName.indexOf(trim(compName)) == -1) {
		cookieCompName.unshift(compName);
		setCookieArray ('compName', cookieCompName, 7);

		// 쿠키 기업검색 표시
		cookieCompName = unescape(getCookieArray('compName', cookieCnt));
		cookieCompName = cookieCompName.split(',');

		var cookieLink = "";
		for (var i in cookieCompName){
			cntNum = i;	cntNum++;
			if (cntNum == 1) cntNum = '[기업검색] ' + cntNum;
			cookieLink += cntNum + ')<a onclick=\'viewCompKwd(\"' + cookieCompName[i] + '")\'>' + cookieCompName[i] + '&nbsp</a> ';
			// 마지막에 쿠키'삭제' 링크 추가
			if (i == cookieCompName.length -1 ) {
				cookieLink += '&nbsp&nbsp → <a onclick=\'delKwd(\"compName")\'>삭제</a>';
			}
		}
		document.getElementById('compKwd').innerHTML = cookieLink;
		//$("#compKwd").text(decodeURIComponent(unescape(getCookieArray('compName', 5))));
	};
}

//-----------------------------------------
// 쿠키 공통 lib -by jsj 20200521
//-----------------------------------------
// 쿠기 저장
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
function delCookie(cname, type) {
	setCookie(cname);
}

// 배열데이타 쿠키 저장
function setCookieArray(cname, carray, exdays) {
	var str = '';
	for (var key in carray) {
		if (carray[key] == "undefined" || carray[key] == undefined) continue;
		if (str != "") str += ",";
		str += carray[key];
	}

	// 저장할 값이 없으면 쿠기 삭제
	if (key == "undefined" || key == undefined || key == '') {
		delCookie(cname);
		return;
	}

	if (str == '') return;
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

// ------------------------------------------------------
// 입찰정보 검색 - 쿠기 문자열로 검색 -by jsj 20200527
// ------------------------------------------------------
function viewKwd(kwd) {
	frm = document.getElementById('myForm');
	frm.kwd.value = kwd;
	searchType = 1;
	searchajax();
}

// ------------------------------------------------------
// 기업 검색 - 쿠기 문자열로 검색 -by jsj 20200527
// ------------------------------------------------------
function viewCompKwd(kwd) {
	frm = document.getElementById('myForm');
	frm.compname.value = kwd;
	searchType = 2;
	searchajax();
}

//-----------------------------------------
// 키워드 삭제 후 통합검색 Reload 
// delCd: 0= 0 전체삭제, 1=앞에 1개만 삭제
// kwdCd:0= 입찰정보, 1=기업정보
// searchKwd: kwd
//-----------------------------------------
function delKwd(cname, delCd, kwdCd, kwd) {
	switch (delCd){
		case 0:								// 쿠키전체삭제
			delCookie(cname);
			location.reload();
			break;
		case 1:								// 1)번삭제
			if (kwdCd = 0) {				// 입찰정보
				alert (cookieKwd[0]);
				delete cookieKwd[0];		
				setCookieArray (cname, cookieKwd, saveDur);	// saveDur = 30일
				searchCookieKwd(kwd);
			} else {						// 기업정보 kwdCd=1
				alert (cookieCompName[0]);
				delete cookieCompName[0];	
				setCookieArray (cname, cookieCompName, saveDur);	// saveDur = 30일
				searchCookieCompName(kwd);
			}
			break;
	}
}

//-----------------
// 공백 제거
//-----------------
function rtrim(str){
	return str.toString().replace( /\s*$/g, "" );
}

function ltrim(str){
   return str.toString().replace( /^\s*/g, "" );
}

// 앞뒤 공백문자열을 제거
function trim(str) {
	if(str.isNumeric) {
		return str.toString().replace(/(^\s*)|(\s*$)/gi, "");
	} else {
		return str.replace(/(^\s*)|(\s*$)/gi, "");
	}
}


Object.prototype.isNumeric = function () {
	var value = String(this);
	if (value.indexOf(" ") != -1 || value == "")
		return false;
	else if (isNaN(value))
		return false;
	else
		return true;
};
