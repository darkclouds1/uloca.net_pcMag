
var	cookieUse = false;  // 최근 검색 쿠키 저장여부
const cookieCnt = 10;	// 쿠키 갯수
var cookieKwd = new Array();

// 관심 목록 저장, 조회 (DB data)
function updateDbBidNoList (data) {
    var form = document.myForm;
    // alert ("관심목록=" + data);
    if (data == false) {
        // 로그오프 or 관심입찰 없음
        alert ("로그아웃 or 관심입찰이 없습니다. PC에서 입찰정보 조회 후 \'찜하기\'의 체크박스를 선택하세요.");
    } else { 
        var err_str = 'err sql='; // sql error 인 경우
        delCookie('bidNoList'); // 관심입찰 쿠키삭제
        if (data.indexOf('err sql=') == 0) {
            alert (data);
            alert ("[운영자] 쿠키정보 오류로 관심입찰 데이터를 초기화 하였습니다.<br>PC에서 입찰정보 조회 후 \'찜하기\'의 체크박스를 선택 후 [관심입찰] 버튼을 클릭하세요.");
            return;
        }

        // 입찰 조회
        cookieUse = false;      // 관심 목록은 키워드 쿠키에 넣지 않음 (cookieUse=false)
        viewKwd(data);
        form.kwd.value = '';	// kwd value는 조회 후 삭제 -by jsj 20200531

        // DB 관심입찰을 쿠키에 저장 (로그인 시)
        cookieKwd.length = 0;
        cookieKwd = data.split(' ');
        setCookieArray ('bidNoList', cookieKwd, saveDur);	// saveDur = 30일
    } 
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

//-----------------------------------
// 키워드 삭제 후 통합검색 Reload 
//-----------------------------------
function delKwd(cname) {
	delCookie(cname);
	location.reload();
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