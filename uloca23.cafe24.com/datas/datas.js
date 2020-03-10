
function savepubsetup() {
	var form = document.myForm;
	var table = document.getElementById('bidData'); 
    rows = table.getElementsByTagName('tr');
	//alert(rows.length);
	if (rows.length>=6) // 제목 줄 까지....
	{
		alert('설정은 한 아이디에 5개까지 됩니다.');
		return;
	}
	
	if (form.kwd.value == '')
		{
			//alert('검색 키워드를 입력 하세요.');
			//return;
			
		} 
		kt = 0;
		if (form.bidthing.checked == false && form.bidcnstwk.checked == false && form.bidservc.checked == false && form.chkHrc.checked == false)
		{
			alert('검색 종류가 선택되지 않았습니다.');
			return;
		}
		// searchType 1: 물품 2: 사전규격 4: 공사 8:용역
		if (form.chkHrc.checked ) kt += 2;
		if (form.bidthing.checked  ) kt += 1;
		if (form.bidcnstwk.checked  ) kt += 4;
		if (form.bidservc.checked  ) kt += 8;
		form.searchType.value = kt;
		//alert(form.searchType.value+'/'+kt);
		/* if (form.sendmail.checked == false && form.sendkatalk.checked == false && form.sendsms.checked == false)
		{
			alert('알림 방법이 선택되지 않았습니다.');
			return;
		} */
		/* var st = 0;
		if (form.sendmail.checked  ) st += 1;
		if (form.sendkatalk.checked ) st += 2;
		if (form.sendsms.checked  ) st += 4;
		form.sendType.value = st; 
		if (form.sendkatalk.checked && form.katalk.value == "") {
			alert('알림이 카카오톡으로 선택되었으나 카톡아이디가 없습니다.');
			return;
		}
		if (form.sendsms.checked && form.cellphone.value == "") {
			alert('알림이 문자로 선택되었으나 휴대폰 번호가 없습니다.');
			return;
		} */
		if (checkDup() == false) return;
		//if (form.kwd.value == '') form.kwd.value = ' ';
		//alert('kwd='+form.kwd.value+' startDate='+form.startDate.value+' endDate='+form.endDate.value);
		//form.submit();
//$arr2 = $idx.',\''. $userid.'\',\''. $email.'\',\''. $kwd.'\',\''. $dminsttnm.'\',\''. $searchType.'\',\''. $sendType.'\',\''. $katalk.'\',\''. $cellphone.'\'';
		parm = '?userid='+form.userid.value;
		parm += '&email='+form.email.value;
		parm += '&kwd='+form.kwd.value;
		parm += '&dminsttNm='+form.dminsttNm.value;
		parm += '&searchType='+form.searchType.value;
		//parm += '&sendType='+form.sendType.value;
		//parm += '&katalk='+form.katalk.value;
		//parm += '&cellphone='+form.cellphone.value;

		$.ajax({
        type: "get",/*method type*/
        contentType: "application/json; charset=utf-8",
        url: "/datas/insAutoSet.php"+parm,
        //data: parm , /*parameter pass data is parameter name param is value */
        //dataType: "json",
        success: function(data) {
               //alert("Success data="+data);
			   document.getElementById('wasrec').innerHTML = data;
			   alert('저장 되었습니다.');
			   clearSearch();
			   //move_stop();
            }
        ,
        error: function(result) {
			//move_stop();
            alert("Error "+result);
        }
    });
}
function clearSearch() {
	var form = document.myForm;
	form.bidthing.checked =true;
	form.bidcnstwk.checked =true;
	form.bidservc.checked =true;
	form.chkHrc.checked = true;
	//alert(form.bidservc.checked);
}

var xhr;
function ajaxUloca(server,client) {
// XMLHttpRequest 객체의 생성
      xhr = new XMLHttpRequest();
      // 비동기 방식으로 Request를 오픈한다
      xhr.open('GET', server);
      // Request를 전송한다
      xhr.send();
		xhr.onreadystatechange=client;
      // Event Handler
     /* xhr.onreadystatechange = function () {
        // 서버 응답 완료 && 정상 응답
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            console.log(xhr.responseText);
			
            //document.getElementById('content').innerHTML = xhr.responseText;

            // document.getElementById('content').insertAdjacentHTML('beforeend', xhr.responseText);
          } else {
            console.log('[' + xhr.status + ']: ' + xhr.statusText);
          }
        }
      }; */
}

function ajaxClient() {
	if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            console.log(xhr.responseText);
			
            //document.getElementById('content').innerHTML = xhr.responseText;

            // document.getElementById('content').insertAdjacentHTML('beforeend', xhr.responseText);
          } else {
            console.log('[' + xhr.status + ']: ' + xhr.statusText);
          }
        }
}
/* -------------------------------------------------------------------------
	new Version of get ajax : 20180728 HMJ
-------------------------------------------------------------------------- */
function getAjax(server,client) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     ///document.getElementById("demo").innerHTML = this.responseText;
	 client(this.responseText);
    } else {
		//alert ("Error" + xhttp.status);
	}
  };
  xhttp.open("GET", server, true);
  xhttp.send();
}

function checkDup() {
	var table = document.getElementById('bidData'); 
    rows = table.getElementsByTagName('tr');
	var form = document.myForm;
	//var tablei = document.getElementById('tablei');
	//rowsi = tablei.getElementsById('tr');
	//cellsi1 = rowsi[2].getElementsByTagName('kwd');
	kwd = form.kwd.value.trim(); //cellsi1[0].innerHTML;
	//cellsi2 = rowsi[3].getElementsByTagName('td');
	dminsttnm = form.dminsttNm.value.trim(); //cellsi2[0].innerHTML;
	//alert('kwd='+kwd+' dminsttnm='+dminsttnm);
	if (kwd == '' && dminsttnm == '')
		{
			alert('키워드나 수요기관 하나는 있어야 됩니다.');
			return false;
		}
	for (i = 0; i< rows.length; i++) {
		cells = rows[i].getElementsByTagName('td');
		if (!cells.length) continue;
		//rows[i].style.backgroundColor = 'red';
		
		if (kwd != '' && kwd == cells[3].innerHTML && dminsttnm != '' && dminsttnm == cells[4].innerHTML)
		{
			alert('같은 키워드, 수요기관이 이미 등록 되어 있습니다.');
			return false;
		}
		else if (kwd != '' && kwd == cells[3].innerHTML && dminsttnm == '' )
		{
			alert('같은 키워드가 이미 등록 되어 있습니다.');
			return false;
		} else if (dminsttnm != '' && dminsttnm == cells[4].innerHTML && kwd == '')
		{
			alert('같은 수요기관이 이미 등록 되어 있습니다.');
			return false;
		}
		
	}
	return true;
}

function gobackins() {
	document.getElementById('doupdate').style.display = 'none';
	document.getElementById('doins').style.display = 'inline';
	var form = document.myForm;
	form.kwd.value='';
	form.dminsttNm.value = '';
	form.searchType.value=1;
	//form.sendType.value=1;
	//form.katalk.value='';
	//form.cellphone.value='';
	clearSearch();
	//form.bidservc.checked = false;
	//form.sendmail.checked=true;
	//form.chkHrc.checked = false;
	//form.sendkatalk.checked = false;
	//form.sendsms.checked = false;
	if (trObj0 != '') trObj0.style.color = 'black';	
}

// 수정
function lineupdate() {
	var form = document.myForm
		kt = 0;
		if (form.bidthing.checked) kt += 1;
		if (form.bidcnstwk.checked  ) kt += 4; 
		if (form.bidservc.checked  ) kt += 8;
		if (form.chkHrc.checked ) kt += 2;
		form.searchType.value = kt;
		//alert(form.bidservc.checked+'/'+form.searchType.value +'/'+ kt);
		/*if (form.sendmail.checked == false && form.sendkatalk.checked == false && form.sendsms.checked == false)
		{
			alert('알림 방법이 선택되지 않았습니다.');
			return;
		} */
		var st = 0;
		/*if (form.sendmail.checked  ) st += 1;
		if (form.sendkatalk.checked ) st += 2;
		if (form.sendsms.checked  ) st += 4;
		form.sendType.value = st;
		if (form.sendkatalk.checked && form.katalk.value == "") {
			alert('알림이 카카오톡으로 선택되었으나 카톡아이디가 없습니다.');
			return;
		}
		if (form.sendsms.checked && form.cellphone.value == "") {
			alert('알림이 문자로 선택되었으나 휴대폰 번호가 없습니다.');
			return;
		} */
		st = 0;
	parm = '?userid='+form.userid.value;
		parm += '&email='+form.email.value;
		parm += '&kwd='+form.kwd.value;
		parm += '&dminsttNm='+form.dminsttNm.value;
		parm += '&searchType='+form.searchType.value;
		//parm += '&sendType='+form.sendType.value;
		//parm += '&katalk='+form.katalk.value;
		//parm += '&cellphone='+form.cellphone.value;
		parm += '&idx='+form.idx.value;
		//alert("/datas/updAutoSet.php"+parm);
		$.ajax({
        type: "get",/*method type*/
        contentType: "application/json; charset=utf-8",
        url: "/datas/updAutoSet.php"+parm,
        //data: parm , /*parameter pass data is parameter name param is value */
        //dataType: "json",
        success: function(data) {
               //alert("Success data="+data);
			   document.getElementById('wasrec').innerHTML = data;
			   alert('수정 되었습니다.');
			   gobackins();
			   //move_stop();
            }
        ,
        error: function(result) {
			//move_stop();
            alert("Error "+result);
        }
    });
}

// 삭제
function linedelete() {

	var r =confirm("정말 삭제 하시겠습니까?");
	if (r != true) return;
	
	var form = document.myForm
	parm = '?userid='+form.userid.value;
	parm += '&idx='+form.idx.value;
		//alert("/datas/delAutoSet.php"+parm);
		$.ajax({
        type: "get",/*method type*/
        contentType: "application/www; charset=utf-8",
        url: "/datas/delAutoSet.php"+parm,
        //data: parm , /*parameter pass data is parameter name param is value */
        //dataType: "json",
        success: function(data) {
               //alert("Success data="+data);
			   document.getElementById('wasrec').innerHTML = data;
			   alert('삭제 되었습니다.');
			   gobackins();
			   //move_stop();
            }
        ,
        error: function(result) {
			//move_stop();
            alert("Error "+result.responseText);
        }
    });
}
var trBackColor;	// 이정 로우 배경셋
var trObj0 = '';			// 이전 로우
function clickTrEvent(trObj) {
	//alert('clickTrEvent');
	if (trObj0 != '') trObj0.style.color = 'black';	
	
	cells = trObj.getElementsByTagName('td');
		if (!cells.length) return;
		trBackColor = trObj.style.backgroundColor;
		trObj.style.color = 'blue';	

		var form = document.myForm;
		//parm = '?userid='+cells[1].innerHTML;
		//parm += '&email='+cells[2].innerHTML;
		form.kwd.value=cells[3].innerHTML;
		form.dminsttNm.value=cells[4].innerHTML;

		if (cells[5].innerHTML.indexOf('물품')>=0) form.bidthing.checked = true;
		else form.bidthing.checked = false;
		if (cells[5].innerHTML.indexOf('공사')>=0) form.bidcnstwk.checked = true;
		else form.bidcnstwk.checked = false;
		if (cells[5].innerHTML.indexOf('용역')>=0) form.bidservc.checked = true;
		else form.bidservc.checked = false;
		if (cells[5].innerHTML.indexOf('사전규격')>=0) form.chkHrc.checked = true;
		else form.chkHrc.checked = false;
		/*if (cells[6].innerHTML.indexOf('이메일')>=0) form.sendmail.checked = true;
		else form.sendmail.checked = false;
		if (cells[6].innerHTML.indexOf('카톡')>=0) form.sendkatalk.checked = true;
		else form.sendkatalk.checked = false;
		if (cells[6].innerHTML.indexOf('문자')>=0) form.sendsms.checked = true;
		else form.sendsms.checked = false;
		form.katalk.value = cells[8].innerHTML;
		form.cellphone.value = cells[9].innerHTML; */
		form.idx.value = cells[7].innerHTML;
		console.log('idx='+form.idx.value);
		//if (cells[5].innerHTML == 2 || cells[5].innerHTML == 3) form.chkHrc.checked = true;
		//if (cells[6].innerHTML == 1 || cells[6].innerHTML == 3 || cells[6].innerHTML == 7) form.sendmail.checked = true;
		//if (cells[6].innerHTML == 2 || cells[6].innerHTML == 3 || cells[6].innerHTML == 7) form.sendkatalk.checked = true;
		//if (cells[6].innerHTML == 4 || cells[6].innerHTML == 6 || cells[6].innerHTML == 7) form.sendsms.checked = true;
	trObj0 = trObj;
	document.getElementById('doupdate').style.display = 'inline';
	document.getElementById('doins').style.display = 'none';
	
}

var oldColor = '#000000';
var newColor = '#FF0000';
function changeTrColor(trObj) {
	trObj.style.color = newColor;
	//trObj.style.cursor = pointer;
	trObj.onmouseout = function(){
		trObj.style.color = oldColor;
		//trObj.style.cursor = default;
	}
}