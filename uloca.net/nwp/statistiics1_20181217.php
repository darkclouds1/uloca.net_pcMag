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
<script src="/jquery/jquery.min.js"></script>
<script src="/jquery/jquery-ui.min.js"></script>
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
//$frrange, $torange
if ($frrange == '') $frrange =75;
if ($torange == '') $torange =99;
$range = 1;
if ($torange - $frrange <= 4) $range = 2;
if ($torange - $frrange <= 0.6) $range = 3;
if ($torange - $frrange <= 0.06) $range = 4;
if ($bidthing == '' && $bidcnstwk == '' && $bidservc == '') $bidall=1;
if ($bidthing == 1) $pss = '물품';
if ($bidcnstwk == 1) $pss = '공사';
if ($bidservc == 1) $pss = '용역';
$today = Date('Y-m-d');

if ($p1w == '' && $p1m == '' && $p6m == '' && $p1y == '') $pall=1;
if ($p1w == '1') $timestamp = strtotime("-7 day");
else if ($p1m == '1') $timestamp = strtotime("-1 month");
else if ($p6m == '1') $timestamp = strtotime("-6 month");
else if ($p1y == '1') $timestamp = strtotime("-1 year");
else if ($pall == '1') $timestamp = strtotime("-10 year");

$startDate = date("Y-m-d", $timestamp);
/*
$sql = "select ROUND(b.tuchalrate1) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1)>=? and ROUND(b.tuchalrate1)<=? group by ROUND(b.tuchalrate1);";
//$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) $tr = $row['tr'];
$sql = "select bidNtceNo from openBidInfo where opengDt <= '".$today."' and substr(bidNtceNo,1,2) = '20' order by opengDt desc limit 0,1";
$result = $conn->query($sql);
$bidNtceNo2 = '';
if ($row = $result->fetch_assoc()) $bidNtceNo2 = $row['bidNtceNo'];
echo $today.'/'.$startDate.'/'.$bidNtceNo1.'/'.$bidNtceNo2;
//exit;
*/

if ($bidall == 1) { // 통합
	if ($range == 1) {
		$sql = 'select ROUND(tuchalrate1) tr, count(*) cnt from forecastData where ROUND(tuchalrate1)>=? and ROUND(tuchalrate1)<=? group by ROUND(tuchalrate1)';
	} else if ($range == 2) {
		$sql = 'select ROUND(tuchalrate1,1) tr, count(*) cnt from forecastData where ROUND(tuchalrate1,1)>=? and ROUND(tuchalrate1,1)<=? group by ROUND(tuchalrate1,1)';
	} else if ($range == 3) {
		$sql = 'select ROUND(tuchalrate1,2) tr, count(*) cnt from forecastData where ROUND(tuchalrate1,2)>=? and ROUND(tuchalrate1,2)<=? group by ROUND(tuchalrate1,2)';
	} else if ($range == 4) {
		$sql = 'select ROUND(tuchalrate1,3) tr, count(*) cnt from forecastData where ROUND(tuchalrate1,3)>=? and ROUND(tuchalrate1,3)<=? group by ROUND(tuchalrate1,3)';
	}
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("dd", $frrange, $torange);
}

else { // 물품,공사,용역
	if ($range == 1) {
		$sql = 'select ROUND(tuchalrate1) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1)>=? and ROUND(tuchalrate1)<=? group by ROUND(tuchalrate1)';
	} else if ($range == 2) {
		$sql = 'select ROUND(tuchalrate1,1) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1,1)>=? and ROUND(tuchalrate1,1)<=? group by ROUND(tuchalrate1,1)';
	} else if ($range == 3) {
		$sql = 'select ROUND(tuchalrate1,2) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1,2)>=? and ROUND(tuchalrate1,2)<=? group by ROUND(tuchalrate1,2)';
	} else if ($range == 4) {
		$sql = 'select ROUND(tuchalrate1,3) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1,3)>=? and ROUND(tuchalrate1,3)<=? group by ROUND(tuchalrate1,3)';
	}
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("sdd", $pss,$frrange, $torange);
}


	if (!$stmt->execute()) return $stmt->errno;
	$rowCount = $stmt->num_rows;
	$fields = $g2bClass->bindAll($stmt);
	
	$json_string = $g2bClass->rs2Json11($stmt, $fields);
	

?>
<script>

var mobile = '<?=$mobile?>'; // "Mobile" : "Computer"
var myLineChart;
var parm="";
var curpos = 0;
var lowidx = [0,0,0,0,0];
var hiidx = [0,0,0,0,0];
function doplus() {
	if (curspos > 4)
	{
		alert("이미 최소 크기 입니다.");
		return;
	}
	curpos ++;
	dif = eval(orgdata[1]['tr'])-eval(orgdata[0]['tr']);
	fridx = maxindex - 2;
	toidx = maxindex + 2;
	document.getElementById('frrange').value = orgdata[fridx]['tr'];
	document.getElementById('torange').value = orgdata[toidx]['tr'];
	lowidx[cuspos] = fridx;
	hiidx[curpos] = toidx;
	refresh();
}
function dominus() {
	if (curspos <1)
	{
		alert("이미 최대 크기 입니다.");
		return;
	}
	curpos --;
	document.getElementById('frrange').value = orgdata[lowidx[cuspos]]['tr'];
	document.getElementById('torange').value = orgdata[hiidx[curpos]]['tr'];
	refresh();
}
function refresh() {
	makeparm();
	url = 'statistics1.php'+parm;
	console.log(url);
	location.href = url;
}
function reinit() {
	frrange = document.getElementById('frrange').value = 75;
	torange = document.getElementById('torange').value = 99;
	curpos = 0;
	makeparm();
	url = 'statistics1.php'+parm;
	location.href = url;
}
function makeparm() {
	parm="?a=1";
	if (document.getElementById('kind20').checked) parm +='&bidall=1';
	if (document.getElementById('kind21').checked) parm +='&bidthing=1';
	if (document.getElementById('kind22').checked) parm +='&bidcnstwk=1';
	if (document.getElementById('kind23').checked) parm +='&bidservc=1';
	if (document.getElementById('kind40').checked) parm +='&p1w=1';
	if (document.getElementById('kind41').checked) parm +='&p1m=1';
	if (document.getElementById('kind42').checked) parm +='&p6m=1';
	if (document.getElementById('kind43').checked) parm +='&p1y=1';
	if (document.getElementById('kind44').checked) parm +='&pall=1';
	frrange = document.getElementById('frrange').value;
	torange = document.getElementById('torange').value;
	
	parm += '&frrange='+frrange+'&torange='+torange+'&cuspos='+curpos;
	if (document.getElementById('kind60').checked) parm +='&loc0=1';
	if (document.getElementById('kind61').checked) parm +='&loc1=1';
	if (document.getElementById('kind62').checked) parm +='&loc2=1';
	if (document.getElementById('kind63').checked) parm +='&loc3=1';
	if (document.getElementById('kind64').checked) parm +='&loc4=1';
	if (document.getElementById('kind65').checked) parm +='&loc5=1';
	if (document.getElementById('kind66').checked) parm +='&loc6=1';
	if (document.getElementById('kind67').checked) parm +='&loc7=1';
	
}
var maxindex;
var maxvalue;
var stepvalue;
 
function getMaxno() {
	max = 0;
	for (i=0;i<data.length ; i++)
	{
		if (eval(data[i]['cnt']) > max) {
			max = data[i]['cnt'];
			maxindex = i;
		}
		//console.log ("data[i]['cnt']="+data[i]['cnt']+" max="+max);
		if (i % 2 == 0 ) data[i]['tr'] = '';
		
	}
	if (max<1000) {
		maxvalue = Math.ceil(max/100)*100;
		stepvalue = max/5;
	}
	else if (max<10000) {
		maxvalue = Math.ceil(max/1000)*1000;
		stepvalue = max/5;
	}
	else if (max<100000) {
		maxvalue = Math.ceil(max/10000)*10000;
		stepvalue = max/5;
	}
	else if (max<1000000) {
		maxvalue = Math.ceil(max/100000)*100000;
		stepvalue = max/5;
	}
	return max; 
}
function viewObject(obj) { 
	try 
	{ 
	var txtValue; 
	for(var x in obj) { txtValue += [x, obj[x]]+"\n"; } 
	console.log("viewObject " +txtValue); 
	} catch (e) {} 

	try 
	{ 
	txtValue = ""; 
	for(var x in obj.target) { txtValue += [x, obj[x]]+"\n"; } 
	console.log("viewObject target " +txtValue); 
	} catch (e) {} 

	try 
	{ 
	txtValue = ""; 
	for(var x in obj.currentTarget) { txtValue += [x, obj[x]]+"\n"; } 
	console.log("viewObject currentTarget " +txtValue); 
	} catch (e) {} 

	try 
	{ 
	txtValue = ""; 
	for(var x in obj.delegateTarget) { txtValue += [x, obj[x]]+"\n"; } 
	console.log("viewObject delegateTarget " +txtValue); 
	} catch (e) {} 

}
function mydrawChart() {
	//console.log('drawChart');
	chartbox = document.getElementById("chartbox"); 
	if (data.length<100) chartbox.style.width= '100%';
	else chartbox.style.width= (data.length * 12) + 'px'; //2400px
	max = getMaxno();
	half = maxvalue/2;
	console.log('maxvalue='+maxvalue+' stepvalue='+stepvalue);
	myLineChart =  new dhtmlXChart({
		view:"spline",
		container:"chartbox",
		value:"#cnt#",
		label:"#cnt#",
		
		item:{
			borderColor: "#1293f8",
			color: function(obj){
			   if (obj.cnt > half) return "#E9602C";
			   return "#66cc00";
			}, //"#ffffff"
		},
		line:{
			color:"#1293f8",
			width:3
		},
		xAxis:{
			template:"#tr#"
		},
		offset:0,
		yAxis:{
			start:0,
			end:maxvalue,
			step:stepvalue,
			template:function(obj){
				return (obj%20?"":obj)
			}
		},
		padding:{
				left:60,
				bottom: 30
			}
	});
	myLineChart.parse(data,"json");
	myLineChart.attachEvent("onItemClick", function(id){
		var index = myLineChart.indexById(id); // 0 부터 시작
		//var id = myChart.idByIndex(index);
		var idx = myLineChart.get(id);
		//viewObject(idx);
		//id.color = '#66ccff';
		console.log('id='+id+'/index='+index+'/tr='+orgdata[index].tr+'/cnt='+myLineChart.get(id).cnt);
		var tr0 = eval(orgdata[index].tr);
		if (tr1<0) 
		{
			tr1 = tr0;
			document.getElementById("frrange").value=tr1;
		} else if (tr2<0) 
		{
			tr2 = orgdata[index].tr;
			document.getElementById("torange").value=tr2;
		} else if (Math.abs(tr1-tr0) > Math.abs(tr2-tr0))
		{
			tr2 = tr0;
			document.getElementById("torange").value=tr2;
		} else {
			tr1 = tr0;
			document.getElementById("frrange").value=tr1;
		}
		if (tr1>tr2)
		{
			tr0=tr2;
			tr2=tr1;
			tr1=tr0;
			document.getElementById("frrange").value=tr1;
			document.getElementById("torange").value=tr2;
		}
    return true;
})
}
var tr1=<?=$frrange?>;
var tr2=<?=$torange?>;

var data = <?=$json_string?>;
var orgdata = <?=$json_string?>;
/*
,
			origin:0,
			legend:{
				layout:"x",
				width: 75,
				align:"center",
				valign:"bottom",
				values:[
					{text:"통합",color:"#3399ff"},
					{text:"용역",color:"#66cc00"}
				],
				margin: 10
			}

var data = [
{ "tr": "75", "cnt": "1220"},{ "tr": "76", "cnt": "1057"},
{ "tr": "77", "cnt": "1107"},{ "tr": "78", "cnt": "1237"},
{ "tr": "79", "cnt": "2525"},{ "tr": "80", "cnt": "2542"},
{ "tr": "81", "cnt": "10468"},{ "tr": "82", "cnt": "4540"},
{ "tr": "83", "cnt": "3574"},{ "tr": "84", "cnt": "3761"},
{ "tr": "85", "cnt": "14028"},{ "tr": "86", "cnt": "7375"},
{ "tr": "87", "cnt": "35523"},{ "tr": "88", "cnt": "369259"},
{ "tr": "89", "cnt": "155745"},{ "tr": "90", "cnt": "21109"},
{ "tr": "91", "cnt": "77128"},{ "tr": "92", "cnt": "12193"},
{ "tr": "93", "cnt": "8530"},{ "tr": "94", "cnt": "8664"},
{ "tr": "95", "cnt": "10058"},{ "tr": "96", "cnt": "10978"},
{ "tr": "97", "cnt": "13086"},{ "tr": "98", "cnt": "16684"},
{ "tr": "99", "cnt": "18941"}
] ; 
*/
function locall() {
	chk = document.getElementById("kind60").checked;
	document.getElementById("kind61").checked = chk;
	document.getElementById("kind62").checked = chk;
	document.getElementById("kind63").checked = chk;
	document.getElementById("kind64").checked = chk;
	document.getElementById("kind65").checked = chk;
	document.getElementById("kind66").checked = chk;
	document.getElementById("kind67").checked = chk;
}
	</script>

</head>

<body onload='mydrawChart();'>
<div id="contents">
<div class="detail_search" >

	<table align=center cellpadding="0" cellspacing="0" width="90%" id='choice'>
		<colgroup>
			<col style="width:20%;" />
			<col style="width:auto;" />
		</colgroup>
		<tbody>
		<tr>
			<th style='border-bottom: 0px;border-right:0px'>낙찰율 분포표 범위(%)&nbsp;&nbsp;</th>
			<td style='border-bottom: 0px;'>&nbsp;&nbsp;
			<input class="input_style2" style="text-align:center;" type="text" name="frrange" id="frrange" value="<?=$frrange?>" size=5> ~ 
			<input class="input_style2" style="text-align:center;" type="text" name="torange" id="torange" value="<?=$torange?>" size=5>
			---from - to 차이가 4%이내면 소숫점 1자리, 0.5% 이내면 소숫점 2자리, 0.05% 이내면 소숫점 3자리로 표현 됩니다.---
			</td>
		</tr>
		<tr>
			<th style='border-bottom: 0px;border-right:0px'>구 분&nbsp;&nbsp;</th>
			<td style='border-bottom: 0px;'>&nbsp;&nbsp;
			<input type="radio" name="kind2" id="kind20" value="통합" <? if ($bidall == 1) echo 'checked="checked"'; ?>>통합
			<input type="radio" name="kind2" id="kind21" value="물품" <? if ($bidthing == 1) echo 'checked="checked"'; ?>>물품
			<input type="radio" name="kind2" id="kind22" value="공사" <? if ($bidcnstwk == 1) echo 'checked="checked"'; ?>>공사
			<input type="radio" name="kind2" id="kind23" value="용역" <? if ($bidservc == 1) echo 'checked="checked"'; ?>>용역
			</td>
		</tr>
		<tr>
			<th style='border-bottom: 0px;border-right:0px'>기 간&nbsp;&nbsp;</th>
			<td style='border-bottom: 0px;'>&nbsp;&nbsp;
			<input type="radio" name="kind4" id="kind40" value="1주일" <? if ($p1w == 1) echo 'checked="checked"'; ?>>1주일
			<input type="radio" name="kind4" id="kind41" value="1개월" <? if ($p1m == 1) echo 'checked="checked"'; ?>>1개월
			<input type="radio" name="kind4" id="kind42" value="6개월" <? if ($p6m == 1) echo 'checked="checked"'; ?>>6개월
			<input type="radio" name="kind4" id="kind43" value="1년" <? if ($p1y == 1) echo 'checked="checked"'; ?>>1년
			<input type="radio" name="kind4" id="kind44" value="전체" <? if ($pall == 1) echo 'checked="checked"'; ?>>전체
			</td>
		</tr>
		<tr>
			<th style='border-right:0px'>지역별&nbsp;&nbsp;</th>
			<td >&nbsp;&nbsp;
			<input type="checkbox" name="kind6" id="kind60" value="전국" <? if ($loc0 == 1) echo 'checked="checked"'; ?> onclick='locall();'>전국
			<input type="checkbox" name="kind6" id="kind61" value="서울" <? if ($loc1 == 1) echo 'checked="checked"'; ?>>서울
			<input type="checkbox" name="kind6" id="kind62" value="경기" <? if ($loc2 == 1) echo 'checked="checked"'; ?>>경기
			<input type="checkbox" name="kind6" id="kind63" value="충청" <? if ($loc3 == 1) echo 'checked="checked"'; ?>>충청
			<input type="checkbox" name="kind6" id="kind64" value="강원" <? if ($loc4 == 1) echo 'checked="checked"'; ?>>강원
			<input type="checkbox" name="kind6" id="kind65" value="전라" <? if ($loc5 == 1) echo 'checked="checked"'; ?>>전라
			<input type="checkbox" name="kind6" id="kind66" value="경상" <? if ($loc6 == 1) echo 'checked="checked"'; ?>>경상
			<input type="checkbox" name="kind6" id="kind67" value="제주" <? if ($loc7 == 1) echo 'checked="checked"'; ?>>제주
			---원본 지역정보가 확실치 않아 추후 지원 예정..
			</td>
		</tr>
		<!-- tr>
			<th style='border-right:0px'>업종별&nbsp;&nbsp;</th>
			<td>&nbsp;&nbsp;
			</td>
		</tr -->
		</tbody>
		</table>
		<div class="btn_area">
			<input class="btnorange" type="button" onclick="refresh()" value="재검색">
			<input class="btnorange" type="button" onclick="reinit()" value="초기화">
			<input class="btnorange" type="button" onclick="doplus()" value="+">
			<input class="btnorange" type="button" onclick="dominus()" value="-">

		</div>
	</div>
</div>
	<div style="overflow:auto; width:98%; height:280px; padding:10px; background-color:#eeeeee;">
		<div id="chartbox" style="width:2400px;height:275px;border:1px solid #c0c0c0;"></div>
	</div>

	  <!-- li>응찰건수와 낙찰 상관관계</li -->


</body>
</html>