<!DOCTYPE html>
<html>
<head>
<title>ULOCA Data 수집</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
<link rel="stylesheet" href="/jquery/jquery-ui.css">
<script src="/jquery/jquery.min.js"></script>
<script src="/jquery/jquery-ui.min.js"></script>
<script src="/js/common.js?version=20190203"></script>
<script src="/g2b/g2b.js"></script>

<script>
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

var today_01 = yyyymmdd;
document.write(today);

document.getElementById('strDate').value = '20130101';
document.getElementById('endDate').value = '20130110';

function updateCompInofStart() {
	var strDate = document.getElementById('strDate').value;
	var endDate = document.getElementById('endDate').value;

	
	//server="/test/updateCompInfo_01.php?startDate="+strDate+"&endDate="+endDate;
	server="/test/s01_openCompany_1.php?startDate="+strDate+"&endDate="+endDate;
	//clog(server+'?'+parm);
	//alert (server);
	//return;
	//move();
	
	//------------------------------
	getAjaxPost(server,getCompInfoUpdate);
	//------------------------------
}

// 기업정보 업데이트 리턴값 표시 
function getCompInfoUpdate(data) {
	alert ("getData");
	
	document.getElementById('status').innerHTML = data;

	//jQuery
	//$("#mydiv").scrollTop($("#mydiv")[0].scrollHeight);
	//move_stop();

	alert (data); return;
	
	tmpTable_Move("openCompany", "openCompany_tmp");
}


// temp table 이동 (oepnCompany_tmp)

function tmpTable_Move(table01='', tmptable='') {
	var tmpTableName;
	var tableName;
	// openCompany_tmp 임시테이블 처리 

	if( tmptable == '') tmpTableName = "openCompany_tmp";
	if( table01 == '') tableName = "openCompany";
	
	
	url = '/g2b/datas/tmpTableHandle.php?tableName='+tableName+'&tmpTableName='+tmpTableName;

	move();
	//----------------------------
	getAjax(url,moved_exit);
	//----------------------------
}

// tmpTable move 결과 확인 -openCompany_tmp
function moved_exit(data) {
	alert (data);
	move_stop();
	clog('moved_tmp '+data);
	document.getElementById('status').innerHTML = data;
}

</script>

</head>
<form>
</form>
<body>
	<div class="btn_area" id='btn'>
		<button type="button" width="800px " onclick="updateCompInofStart()">기업정보 UPDATE</button>
		<button type="button" width="800px " onclick="tmpTable_Move()">임시테이블 정리 </button>
	</div>
	<div class='inputdate' id='inputdate'>
		<input type="text" name="strDate" id='strDate' value='20130101' style='text-align: center;' size=10/>
		<input type="text" name="endDate" id='endDate' value='20130110' style='text-align: center;' size=10/>
	</div>
	<div id='status'>
	</div>
	<div id='mydiv'>
	 - 기업정보 업데이트 ;;;;	
	</div>
</body>
</html>
