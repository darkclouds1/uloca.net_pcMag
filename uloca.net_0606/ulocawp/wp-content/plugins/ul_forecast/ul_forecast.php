<?php
/*
 Plugin Name: ul_forecast : 예가계산(랜덤) (uloca22)
 Plugin URI: http://uloca.net/ul_forecast.php
 Description: 워드프레스 예가계산(랜덤) 입니다.
 Version: 1.0
 Author: Monolith
 Author URI: http://uloca.net/
 */

function ul_forecastShortCode() {

@extract($_GET);
@extract($_POST);

//echo "기초금액=".$amt." 예가범위=".$yega." 예가갯수=".$gasu." 추첨예가=".$pred;
if ($amt != "") {
// 계산 로직

}

if ($yega == "") $yega = 3;
if ($gasu == "") $gasu = 15;
if ($pred == "") $pred = 4;


?>
<!doctype html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1, maximum-scale=2, user-scalable=no">

	<title>유로카닷넷-가격산정 예비가격 자동추첨:: https://uloca.net</title>
	
	<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css?ver=1.3" />
	<link rel="stylesheet" href="/jquery/jquery-ui.css">
	<script src="/jquery/jquery.min.js"></script>
	<script src="/jquery/jquery-ui.min.js"></script>
	<script src="/g2b/g2b.js"></script>
	<script src="/js/common.js"></script>
	<script src="/js/cmp.js?ver=2.15"></script>
	
	<script type="text/javascript">
	var frm ;
		function calc() {
			frm = document.foreForm;
			if (frm.amt.value == "")
			{
				alert("기초금액을 입력하세요");
				return;
			}
			//frm.submit();
			parm = "amt="+frm.amt.value+"&yega="+frm.yega.value+"&gasu="+frm.gasu.value+"&pred="+frm.pred.value;
			server="/forecast/calcForecast.php"; //+parm;
			//move();
		//------------------------------
		getAjaxPost(server,recv,parm);
		//------------------------------
	}

	function recv(datas) {
		// move_stop(); 
		//SearchCounts ++;  //무료검색횟수 count+ -by jsj 0314
		
		data = datas.split('/'); //eval(datas); //JSON.parse(datas); // stringify parse
		//clog(datas);
		try
		{
			makeTablefctr1(data[0]);	// 예비가격
			makeTablefctr2(data[0],data[1]);	// 추첨예가
		}
		catch (e)
		{
			alert('데이타에 에러가 있는것 같습니다. 다른 Web Browser 를 사용해 보세요.'+e.message);
			clog(datas);
		}
	}
	function makeTablefctr1(data) {
		yebiyul = data.split(',');
		clog("data="+data+ " yebiyul="+yebiyul[0]);
		amt = frm.amt.value;
		gasu = frm.gasu.value;
		ln = Math.ceil(gasu/3);

		if (table1 == '') table1 = document.createElement("table");
		var tbody = table1.createTBody();
		var trow = table1.rows.length;
		for (i=1;i<trow ; i++) table1.deleteRow(-1);
		
		for (i=0;i<gasu ;i++ )
		{
			if (i%3 == 0 )
			{
				tr = tbody.insertRow(-1);
			}
			idx = i+1;
			var tabCells = tr.insertCell(-1);
			tabCells.innerHTML = idx;
			tabCells.setAttribute('style', 'border:solid 1px #99bbe8; text-align:center; background:#eeeeee;');
			tabCells = tr.insertCell(-1);
			
			amts = number_format(Math.round(amt * yebiyul[i]/10000))
			tabCells.innerHTML = amts;
			tabCells.setAttribute('style', 'border:solid 1px #99bbe8; text-align:center;');
			tabCells = tr.insertCell(-1);
			yebiyl  = yebiyul[i]/100+"%";
			tabCells.innerHTML = yebiyl;
			tabCells.setAttribute('style', 'border:solid 1px #99bbe8; text-align:center;');
			clog("idx="+idx+" amt="+amts+" yebiyul="+yebiyl);
			
			//<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>1</td>
			//	<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
			//	<td style='border:solid 1px #99bbe8; text-align:center;'>97.04%</td>

		}
		//clog("yebi="+data.yebi.yebi[0]+" yebiga="+data.yebi.yebiga[0]);
	}
	function makeTablefctr2(data1,data2) {
		data11 = data1.split(',');
		data22 = data2.split(',');
		//clog("data="+data+ " yebiyul="+idx[0]);
		amt = frm.amt.value;
		gasu = frm.gasu.value;
		pred = frm.pred.value;
		ln = Math.ceil(gasu/3);

		if (table2 == '') table2 = document.createElement("table");
		var tbody = table2.createTBody();
		var trow = table2.rows.length;
		for (i=1;i<trow ; i++) table2.deleteRow(-1);
		sum=0;
		for (i=0;i<pred ;i++ )
		{
			if (i%3 == 0 )
			{
				tr = tbody.insertRow(-1);
			}
			idx = data22[i];
			var tabCells = tr.insertCell(-1);
			tabCells.innerHTML = idx;
			tabCells.setAttribute('style', 'border:solid 1px #99bbe8; text-align:center; background:#eeeeee;');
			tabCells = tr.insertCell(-1);
			if (idx>0) idx --;
			amts = number_format(Math.round(amt * data11[idx]/10000));
			sum += Math.round(amt * data11[idx]/10000);
			tabCells.innerHTML = amts;
			tabCells.setAttribute('style', 'border:solid 1px #99bbe8; text-align:center;');
			tabCells = tr.insertCell(-1);
			yebiyl  = data11[idx]/100+"%";
			tabCells.innerHTML = yebiyl;
			tabCells.setAttribute('style', 'border:solid 1px #99bbe8; text-align:center;');
			clog("idx="+idx+" amt="+amts+" yebiyul="+yebiyl);
			
			//<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>1</td>
			//	<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
			//	<td style='border:solid 1px #99bbe8; text-align:center;'>97.04%</td>

		}
		//clog("yebi="+data.yebi.yebi[0]+" yebiga="+data.yebi.yebiga[0]);
		avg = sum / pred;
		saJungYul = (avg / amt) * 100; // 기초금액의 사정율
		
		document.getElementById('avg').innerHTML="&nbsp;&nbsp;"+ number_format(Math.round(avg))+" 원 (=" + number_format(sum)+" / "+pred+"), " + number_format(saJungYul.toFixed(3))+"%)(=기초금액의 사정률)" ;

		avg1 = avg * 87.745 / 100;
		avg2 = amt * 87.745 / 100;
		document.getElementById('avg1').innerHTML = "&nbsp;&nbsp;" + number_format(Math.round(avg1)) + " 원" + " (기초금액의= " + number_format(avg2) + " 원)";
		avg3 = avg * 86.745 / 100;
		avg4 = amt * 86.745 / 100;
		document.getElementById('avg3').innerHTML = "&nbsp;&nbsp;" + number_format(Math.round(avg3)) + " 원" + " (기초금액의= " + number_format(Math.round(avg4)) + " 원)";
		
		avg5 = avg * document.getElementById('txt_avg5').value / 100;
		avg6 = amt * document.getElementById('txt_avg5').value / 100;
		document.getElementById('avg5').innerHTML = "&nbsp;&nbsp;" + number_format(Math.round(avg5))+ " 원" + " (기초금액의= " + number_format(Math.round(avg6)) + " 원)";

	}

</script>
</head>
<body>
<!-- ------------------------------------- processing ----------------------------------------------------- -->
<div id="loading" style="display: none; position: fixed; width: 100px; height: 100px; top: 35%;left: 60%;margin-top: -10px; margin-left: -50px; ">
  <img src="/g2b/loading3.gif" width="100px" height="100px">
</div>

<form name="foreForm"> <!-- action="wp-content/plugins/ul_forecast.php";> ///ulocawp/?page_id=1662" -->
<div class="detail_search" width="80%">
<center><p style='font-size:20; font-weight:bold'>가격산정 ( 추첨예가 입찰금액  )</p></center>

<table align=center class="grid04" style="width:90%; text-align: left; border: 0px solid #dddddd; word-break:break-all;">
	<colgroup>
		<col style="width:20%;" /><col style="width:20%;" />
		<col style="width:20%;" /><col style="width:20%;" /><col style="width:auto;" />
	</colgroup>
	○ 입찰공고에 나입찰공고에 나와있는 기초금액 / 예가범위 / 예가갯수 / 추첨예가 갯수를 입력하세요.
	<tbody>
		<tr>
			<th style='text-align:center; border: 1px solid #dddddd; color:red; font-weight:bold; background:#eeeeee;' >기초금액</th>
			<td style='border:solid 1px #99bbe8; text-align:left;' colspan=4><input class="input_style2" type="number" name="amt" id="amt" size='50' value="<?=$amt?>" style="text-align:right; width:200px; height:30px" onkeypress="if(event.keyCode==13) {calc(); return false;}" placeholder="원 단위까지 입력" />&nbsp 원</td>
		</tr>
		<tr>
			<th style='text-align:center; border: 1px solid #dddddd; color:black; font-weight:bold; background:#eeeeee;' >예가범위</th>
			<td style='border:solid 1px #99bbe8; text-align:left;'>
				<input type="radio" name="yega" id=="yega" value="2" <? if ($yega == 2) echo "checked"; ?>>&nbsp ±2%
			</td>
			<td style='border:solid 1px #99bbe8; text-align:left;'>
				<input type="radio" name="yega" id=="yega" value="3"  <? if ($yega == 3) echo "checked"; ?>>&nbsp ±3%
			</td>
			<td style='border:solid 1px #99bbe8; text-align:left;'>
				<input type="radio" name="yega" id=="yega" value="4"  <? if ($yega == 4) echo "checked"; ?>>&nbsp ±4%
			</td>
			<td style='border:solid 1px #99bbe8; text-align:left;'>
				<input type="radio" name="yega" id=="yega" value="5"  <? if ($yega == 5) echo "checked"; ?>>&nbsp ±5%
			</td>
		</tr>
		<tr>
			<th style='text-align:center; border: 1px solid #dddddd; color:black; font-weight:bold; background:#eeeeee;' >예가갯수</th>
			<td style='border:solid 1px #99bbe8; text-align:left;'>
				<input type="radio" name="gasu" value="10"  <? if ($gasu == 10) echo "checked"; ?>>&nbsp 10개
			</td>
			<td style='border:solid 1px #99bbe8; text-align:left;'>
				<input type="radio" name="gasu" value="15" <? if ($gasu == 15) echo "checked"; ?>>&nbsp 15개
			</td>
			<td style='border:solid 1px #99bbe8; text-align:left;'>
				<input type="radio" name="gasu" value="30" <? if ($gasu == 30) echo "checked"; ?>>&nbsp 30개
			</td>
			<td style='border:solid 1px #99bbe8; text-align:left;'>
				<input type="radio" name="gasu" value="60" <? if ($gasu == 60) echo "checked"; ?>>&nbsp 60개
			</td>
		</tr>
		<tr>
			<th style='text-align:center; border: 1px solid #dddddd; color:black; font-weight:bold; background:#eeeeee;' >추첨예가</th>
			<td style='border:solid 1px #99bbe8; text-align:left;'>
				<input type="radio" name="pred" value="3" <? if ($pred == 3) echo "checked"; ?>>&nbsp 3개
			</td>
			<td style='border:solid 1px #99bbe8; text-align:left;'>
				<input type="radio" name="pred" value="4" <? if ($pred == 4) echo "checked"; ?>>&nbsp 4개
			</td>
			<td style='border:solid 1px #99bbe8; text-align:left;' colspan=2>
				<input type="radio" name="pred" value="5" <? if ($pred == 5) echo "checked"; ?>>&nbsp 5개
			</td>
		</tr>
	</tbody>
</table>
○ 추첨예가를  평균하여 <font color="red">예비가격</font>을 선정합니다. 실제 입찰 예비금액은 사전에 누구도 알수 없으며, 여러번 계산해서 참조용으로만 활용하세요.
<div class="btn_area" id='continueSearch' >
	<a onclick="calc();" class="search"><input type=button value="계산"></a>
	<a onclick="prnt();" class="search"><input type=button value="인쇄"></a>
</div>
<br>

<table align="center" class="grid04" style="width:90%; text-align: left; border: 0px solid #dddddd; font-weight:normal;">
	<colgroup>
		<col style="width:20%;" /><col style="width:auto;" />
	</colgroup>
	<tbody>
	<tr>
		<th style="text-align:center; border: 1px solid #dddddd; color:red;  font-weight:normal; background:#eeeeee;" > 예비가격 (추첨평균)</th>
			<td style="border:solid 1px #99bbe8; left:0%; color:red;" id=avg >&nbsp;&nbsp; </td>
		</tr>
		<tr>
			<th style="text-align:center; border: 1px solid #dddddd; color:black;  font-weight:normal; background:#eeeeee;" > 직접입력
				<input type="text" style="text-align: right; padding-right: 1px; height:30px" size="5" id="txt_avg5" value="99.999" maxlength="6" 
				onkeypress="if(event.keyCode==13) {calc(); return false;}" />%</th>
			<td style="border:solid 1px #99bbe8; left:0%; color:black" id=avg5 >&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<th style="text-align:center; border: 1px solid #dddddd; color:black; font-weight:normal; background:#eeeeee;" >예비가격의 87.745%</th>
			<td style="border:solid 1px #99bbe8; left:0%;" id=avg1 >&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<th style="text-align:center; border: 1px solid #dddddd; color:black; font-weight:normal; background:#eeeeee;" >예비가격의 86.745%</th>
			<td style="border:solid 1px #99bbe8; left:0%; color:black" id=avg3 >&nbsp;&nbsp;</td>
		</tr>
	</tbody>
</table>

<table align=center class="grid04" id="table1" style="text-align: left; border: 0px solid #dddddd; width:90%;">
	<colgroup>
		<col style="width:5%;" /><col style="width:15%;" />
		<col style="width:10%;" /><col style="width:5%;" /><col style="width:15%;" />
		<col style="width:10%;" /><col style="width:5%;" /><col style="width:15%;" />
		<col style="width:auto;" />
	</colgroup>
	<tbody>
		<tr>
			<th style='text-align:center; border: 1px solid #dddddd; color:black; font-weight:bold; background:#eeeeee;' colspan=9>예비가격</th>
		</tr>
		<tr>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>1</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>2</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>3</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
							
		</tr>
		<tr>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>4</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>5</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>6</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
							
		</tr>
		<tr>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>7</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>8</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>9</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
							
		</tr>
	</tbody>
</table>

<table align=center class="grid04" id="table2" style="text-align: left; border: 0px solid #dddddd; width:90%;">
	<colgroup>
		<col style="width:5%;" /><col style="width:15%;" />
		<col style="width:10%;" /><col style="width:5%;" /><col style="width:15%;" />
		<col style="width:10%;" /><col style="width:5%;" /><col style="width:15%;" />
		<col style="width:auto;" />
	</colgroup>
	<tbody>
		<tr>
			<th style='text-align:center; border: 1px solid #dddddd; color:black; font-weight:bold; background:#eeeeee;' colspan=9>추첨예가</th>							
		</tr>
		<tr>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>1</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>2</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>3</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
		</tr>
		<tr>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>4</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'>5</td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center; background:#eeeeee;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
			<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
							
		</tr>
	</tbody>
</table>

</form>
</body>

<?
	
}
add_shortcode('ul_forecast','ul_forecastShortCode');

?>