<!DOCTYPE html>
<html>
<head>
<title>ULOCA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
<link rel="stylesheet" href="/jquery/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/dhtml/codebase/fonts/font_roboto/roboto.css"/>
<link rel="stylesheet" type="text/css" href="/dhtml/codebase/dhtmlx.css"/>
<script src="http://uloca23.cafe24.com/jquery/jquery.min.js"></script>
<script src="http://uloca23.cafe24.com/jquery/jquery-ui.min.js"></script>
<script src="/g2b/g2b.js"></script>
<script src="/dhtml/codebase/dhtmlx.js"></script>
<?
@extract($_GET);
@extract($_POST);
session_start();
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;

$conn = $dbConn->conn(); //

$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"
$sYear = date("Y");
$i=0;
while ($sYear >= 2016) {
	$ssYear[$i] = $sYear;
	//echo $sYear.'/'.$ssYear[$i].'-';
	$i ++;
	//$timestamp = strtotime("-1 year");
	$sYear --; //= date("Y", $timestamp);
}
?>
</head>

<body>
<form action="execsql2.php" name="myForm" id="myform" method="post" >
<div id="contents">
<div class="detail_search" >
<table align=center cellpadding="0" cellspacing="0" width="80%" id='choice'>
		<colgroup>
			<col style="width:20%;" />
			<col style="width:auto;" />
		</colgroup>
		<tbody>
			
			<tr>
				<th>공고번호(bidNtceNo)</th>
				<td>&nbsp;&nbsp;
					<input class="input_style2" autocomplete="on" type="text" name="bidNtceNo" id="bidNtceNo" value="20180509427" onkeypress="if(event.keyCode==13) {searchajax(); return false;}"  onclick="chBack(0)" maxlength="20" style="width:20%;" />
					
				</td>
			</tr>
			<tr>
				<th>사업자(compno)</th>
				<td>&nbsp;&nbsp;
					<input class="input_style2" autocomplete="on" type="text" name="compno" id="compno" value="" onkeypress="if(event.keyCode==13) {searchajax(); return false;}" onclick="chBack(1)" maxlength="20" style="width:20%;" />
					
				</td>
			</tr>
			
			<!-- tr>
				<th>검색종류</th>
				<td>&nbsp;&nbsp;
				
					<input type="radio" name="kind2" id="kind21" value="물품" onclick="chBack(2)">물품
					<input type="radio" name="kind2" id="kind22" value="공사" onclick="chBack(2)">공사
					<input type="radio" name="kind2" id="kind23" value="용역" onclick="chBack(2)" checked="checked">용역
					
				</td>
			</tr -->
			
			<tr>
				<th>검색년도</th>
				<td>&nbsp;&nbsp;
				<!-- 입찰/사전정보 물품/공사/용역 -->
					<select name="syear" id="syear" onclick="chBack(2)">
						<?
						for ($i=0;$i<count($ssYear);$i++) {
							if ($sYear == $ssYear[$i]) echo "<option value='$ssYear[$i]'selected='selected' >$ssYear[$i]</option>"; 
							else echo "<option value='$ssYear[$i]'>$ssYear[$i]</option>";
						}
						?>
					</select>
					작업중...
					
				</td>
				
			</tr>
			<!-- tr>
				<th>기업명</th>
				<td >&nbsp;&nbsp;
					<input class="input_style2" autocomplete="on" type="text" name="compname" id="compname" style="ime-mode:active;" value="" onkeypress="if(event.keyCode==13) {searchajax(); return false;}" onclick="chBack(4)" maxlength="50" style="width:80%;" />
					
				</td>
			</tr -->
			<tr>
				<th>SQL</th>
				<td >&nbsp;&nbsp;
					<input class="input_style2" autocomplete="on" type="text" name="sqls" id="sqls" style="ime-mode:active;" value="" onkeypress="if(event.keyCode==13) {searchajax(); return false;}" onclick="chBack(3)" size="120" style="width:80%;" />
					1 레코드 이내만
				</td>
			</tr>
		</table>
		<div class="btn_area">
			<a onclick="searchexec();" class="search">검색</a>
		</div>	
	</div>
</div>
</form>
<div id='totalrec'></div>
<div id='tables' style='width: 100%;'></div>

<script>
var selcolor = '#eeeeee';
var unselcolor = '#ffffff';
var choiceTable = '';
var sc = [0,0,0,0,0,0];
function chBack(ln) {
	
		choiceTable = document.getElementById( "choice" ); 
		//alert(choiceTable.rows[ln].style.background);
		if (sc[ln] == 1) {
			choiceTable.rows[ln].style.background = unselcolor;
			sc[ln] = 0;
		}
		else {
			choiceTable.rows[ln].style.background = selcolor;
			sc[ln] = 1;
		}
	
}
function searchexec() {
	var form = document.myForm;
	parm = '?a=1';
	if (sc[0]==1) {
		bidNtceNo = form.bidNtceNo.value;
		parm += '&bidNtceNo='+bidNtceNo;
	}
	if (sc[1]==1) {
		compno = form.compno.value;
		parm += '&compno='+compno;
	}
	if (sc[3]==1) {
		sqls = encodeURIComponent(form.sqls.value);
		parm += '&sqls='+sqls;
	}

	url = 'execsql2.php'+parm;
	console.log(url);
	getAjax(url,searchexec2);
}
var table1;
var col01 = ['idx','bidNtceNo','bidNtceOrd','bidNtceNm','ntceInsttNm','dminsttNm','opengDt','bidtype','reNtceYn','rgstTyNm','ntceKindNm','bidNtceDt','ntceInsttCd','dminsttCd','bidBeginDt','bidClseDt','presmptPrce','bidNtceDtlUrl','bidNtceUrl','sucsfbidLwltRate','bfSpecRgstNo','prtcptCnum','bidwinnrNm','bidwinnrBizno','sucsfbidAmt','sucsfbidRate','rlOpengDt','bidwinnrCeoNm'];
var col02 = ['idx','공고번호','공고차수','입찰공고명','공고기관명','수요기관명','개찰일시','물품,용역,공사','재공고여부(Y/N)','등록유형명','공고종류명','입찰공고일시','공고기관코드','수요기관코드','입찰개시일시','입찰마감일시','추정가격','입찰공고상세URL','입찰공고URL','낙찰하한율','사전규격등록번호','참가업체수','최종낙찰업체명','최종낙찰업체대표자명','최종낙찰금액','최종낙찰률','실개찰일시','최종낙찰업체대표자명'];

var col11 = ['compno','compname','repname'];
var col12 = ['사업자등록번호','회사명','대표자'];
function searchexec2(data) {
	console.log(data);
	document.getElementById('tables').innerHTML = '';
	makeTableHead();
	if (sc[0]==1) makeTabletr(data,col01,col02) ;
	else if (sc[1]==1) makeTabletr(data,col11,col12) ;
	else if (sc[3]==1) makeTabletr2(data) ;
}
function makeTableHead() {
	 var head = document.getElementById('tables').innerHTML;
	if (head != '') return; // 제목란이 있으면..
	//colw = col+'w';
	table1 = document.createElement("table");
	table1.setAttribute('class', 'type10');
	table1.setAttribute('id', 'specData');
	table1.setAttribute('style', 'width:100%'); //'width', '700px');
	var header = table1.createTHead();
        var tr = header.insertRow(-1);                   // TABLE ROW.

        
            var th = document.createElement("th");      // TABLE HEADER.
			th.innerHTML = '제목';
			th.setAttribute('style', 'width:20%;');
            //th.setAttribute('style', 'text-align:right;');
            tr.appendChild(th);
			var td = document.createElement("th");      // TABLE HEADER.
			td.innerHTML = '내용';
			td.setAttribute('style', 'width:80%;');
            tr.appendChild(td);
			
}
function makeTabletr(datas,col1,col2){
	//console.log (datas);
	if (datas == '') return;
	//console.log('datas='+datas);
	var data;
	try
	{
		data = JSON.parse(datas);
	}
	catch (ex) {alert(ex); return;}	
	//console.log('makeTabletrCompany');
	var data = JSON.parse(datas);
	//r = data.response;
	var tbody = table1.createTBody();
	items = data.response.body.items;
	//alert(col1.length);
        for (var i = 0; i < col1.length; i++) {
			try
			{
				//console.log('col.length='+col.length);
				//idx = idx + 1;
				tr = tbody.insertRow(-1);
				var tabCells = tr.insertCell(-1);
				tabCells.innerHTML = col2[i];
				tabCells.setAttribute('style', 'text-align:right;');
				tabCells = tr.insertCell(-1);
				//console.log(col1[i]);
				tabCells.innerHTML = "  "+items[0][col1[i]];
				
				
			}
			catch (ex) {console.log(ex);}
			
        }

        // FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
        var divContainer = document.getElementById("tables");
        //divContainer.innerHTML = "";
        divContainer.appendChild(table1);
}
function makeTabletr2(datas){
	//console.log (datas);
	if (datas == '') return;
	//console.log('datas='+datas);
	var data;
	try
	{
		data = JSON.parse(datas);
	}
	catch (ex) {alert(ex); return;}	
	//console.log('makeTabletrCompany');
	var data = JSON.parse(datas);
	//r = data.response;
	var tbody = table1.createTBody();
	items = data.response.body.items;
	//alert(col1.length);
	cnt = items.length;
	if (cnt>1) cnt = 1;	// 1 record only
        for (var i = 0; i < cnt; i++) {
			try
			{
				var columnsIn = items[i];
				// loop through every key in the object
				for(var key in columnsIn){
					console.log(key + ' : ' + items[i][key]); // here is your column name you are looking for + its value
				

				//idx = idx + 1;
				tr = tbody.insertRow(-1);
				var tabCells = tr.insertCell(-1);
				tabCells.innerHTML = key;
				tabCells.setAttribute('style', 'text-align:right;');
				tabCells = tr.insertCell(-1);
				//console.log(col1[i]);
				tabCells.innerHTML = "  "+items[0][key];
				}
				
			}
			catch (ex) {console.log(ex);}
			
        }

        // FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
        var divContainer = document.getElementById("tables");
        //divContainer.innerHTML = "";
        divContainer.appendChild(table1);
}

</script>

</body>
</html>