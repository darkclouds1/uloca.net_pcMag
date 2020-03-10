<?
@extract($_GET);
@extract($_POST);


echo "기초금액=".$amt." 예가범위=".$yega." 예가갯수=".$gasu." 추첨예가=".$pred;
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

	<title>유로카닷넷-입찰기초가격 예측(자동추첨)</title>
	
	<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css?ver=1.3" />
	<link rel="stylesheet" href="/jquery/jquery-ui.css">
	<script src="/jquery/jquery.min.js"></script>
	<script src="/jquery/jquery-ui.min.js"></script>
	<script src="/g2b/g2b.js"></script>
	<script src="/js/common.js"></script>
	<script src="/js/cmp.js?ver=2.14"></script>
	
	<script type="text/javascript">
		function calc() {
			frm = document.foreForm;
			if (frm.amt.value == "")
			{
				alert("기초금액을 입력하세요");
				return;
			}
			frm.submit();
	}
	</script>
</head>
<body>
<form name="foreForm">
<div class="detail_search" width="80%">
<br><center><b>입찰기초가격 예측(자동추첨)</b></center><br>
<table align=center class="grid05" style="width:90%; text-align: left; border: 0px solid #dddddd; word-break:break-all;">
		<colgroup>
			<col style="width:20%;" /><col style="width:20%;" />
			<col style="width:20%;" /><col style="width:20%;" /><col style="width:auto;" />
		</colgroup>
		<tbody>
			<tr>
				<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:bold;' >기초금액</th>
				<td style='border:solid 1px #99bbe8; text-align:left;' colspan=4><input class="input_style2" type="number" name="amt" id="amt" size='32' value="<?=$amt?>" style="text-align:right;" onkeypress="if(event.keyCode==13) {searchajax(); return false;}" placeholder="원 단위까지 입력하세요." />원</td>
			</tr>
			<tr>
				<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:bold;' >예가범위</th>
				<td style='border:solid 1px #99bbe8; text-align:left;'>
					<input type="radio" name="yega" id=="yega" value="2" <? if ($yega == 2) echo "checked"; ?>>±2%
				</td>
				<td style='border:solid 1px #99bbe8; text-align:left;' colspan=3>
					<input type="radio" name="yega" id=="yega" value="3"  <? if ($yega == 3) echo "checked"; ?>>±3%
				</td>
				
			</tr>
			<tr>
				<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:bold;' >예가갯수</th>
				<td style='border:solid 1px #99bbe8; text-align:left;'>
					<input type="radio" name="gasu" value="10"  <? if ($gasu == 10) echo "checked"; ?>>10개
				</td>
				<td style='border:solid 1px #99bbe8; text-align:left;'>
					<input type="radio" name="gasu" value="15" <? if ($gasu == 15) echo "checked"; ?>>15개
				</td>
				<td style='border:solid 1px #99bbe8; text-align:left;'>
					<input type="radio" name="gasu" value="30" <? if ($gasu == 30) echo "checked"; ?>>30개
				</td>
				<td style='border:solid 1px #99bbe8; text-align:left;'>
					<input type="radio" name="gasu" value="60" <? if ($gasu == 60) echo "checked"; ?>>60개
				</td>
			</tr>
			<tr>
				<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:bold;' >추첨예가</th>
				<td style='border:solid 1px #99bbe8; text-align:left;'>
					<input type="radio" name="pred" value="3" <? if ($pred == 3) echo "checked"; ?>>3개
				</td>
				<td style='border:solid 1px #99bbe8; text-align:left;'>
					<input type="radio" name="pred" value="4" <? if ($pred == 4) echo "checked"; ?>>4개
				</td>
				<td style='border:solid 1px #99bbe8; text-align:left;' colspan=2>
					<input type="radio" name="pred" value="5" <? if ($pred == 5) echo "checked"; ?>>5개
				</td>
			</tr>
		</tbody>
	</table>
	<br>
	<table align=center class="grid05" style="text-align: left; border: 0px solid #dddddd; width:90%;">
		<colgroup>
			<col style="width:5%;" /><col style="width:15%;" />
			<col style="width:10%;" /><col style="width:5%;" /><col style="width:15%;" />
			<col style="width:10%;" /><col style="width:5%;" /><col style="width:15%;" />
			<col style="width:auto;" />
		</colgroup>
		<tbody>
			<tr>
				<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:bold;' colspan=9>예비가격</th>
								
			</tr>
			<tr>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>1</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.04%</td>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>2</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.13%</td>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>3</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.17%</td>
								
			</tr>
			<tr>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>4</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.04%</td>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>5</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.13%</td>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>6</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.17%</td>
								
			</tr>
			<tr>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>7</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.04%</td>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>8</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.13%</td>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>9</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.17%</td>
								
			</tr>
		</tbody>
	</table>
	<br>
	<table align=center class="grid05" style="text-align: left; border: 0px solid #dddddd; width:90%;">
		<colgroup>
			<col style="width:5%;" /><col style="width:15%;" />
			<col style="width:10%;" /><col style="width:5%;" /><col style="width:15%;" />
			<col style="width:10%;" /><col style="width:5%;" /><col style="width:15%;" />
			<col style="width:auto;" />
		</colgroup>
		<tbody>
			<tr>
				<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:bold;' colspan=9>추첨예가</th>
								
			</tr>
			<tr>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>1</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.04%</td>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>2</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.13%</td>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>3</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.17%</td>
								
			</tr>
			<tr>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>4</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.04%</td>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'>5</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>100,000,000</td>
				<td style='border:solid 1px #99bbe8; text-align:center;'>97.13%</td>
				<td style='border:solid 1px #99bbe8; text-align:center; background:#eeffee;'> </td>
				<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
				<td style='border:solid 1px #99bbe8; text-align:center;'> </td>
								
			</tr>
		</tbody>
	</table>
	<br>
	<table align=center class="grid05" style="width:90%; text-align: left; border: 0px solid #dddddd; font-weight:normal;">
		<colgroup>
			<col style="width:20%;" /><col style="width:auto;" />
		</colgroup>
		<tbody>
			<tr>
				<th style='text-align:center; border: 1px solid #dddddd; color:blue;  font-weight:normal;' >추첨예가 평균값</th>
				<td style='border:solid 1px #99bbe8; left;' >&nbsp;&nbsp;202,355,000/4 = 50,588,750</td>
			</tr>
			<tr>
				<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:normal; ' >평균금액의 87.745%</th>
				<td style='border:solid 1px #99bbe8; left; color:red' >&nbsp;&nbsp;44,007,408</td>
			</tr>
			<tr>
				<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:normal;' >기초금액의 87.745%</th>
				<td style='border:solid 1px #99bbe8; left;' >&nbsp;&nbsp;43,872,500</td>
			</tr>
			<tr>
				<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:normal;' >평균금액의 86.745%</th>
				<td style='border:solid 1px #99bbe8; left; color:red' >&nbsp;&nbsp;43,505,870</td>
			</tr>
			<tr>
				<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:normal;' >기초금액의 86.745%</th>
				<td style='border:solid 1px #99bbe8; left;' >&nbsp;&nbsp;43,372,500</td>
			</tr>
		</tbody>
	</table>
	<br>
	<table align=center class="grid05" style="text-align: left; border: 0px solid #dddddd; width:90%;">

		<tbody>
			<tr>
				<th style='text-align:center; border: 1px solid #dddddd; color:blue; font-weight:bold;' >입찰기초가격 예측 프로그램 사용방법</th>
			</tr>
			<tr>
				<td style='text-align:left; border: 1px solid #dddddd; color:black; font-weight:normal;' >- 기초금액을 입력하시고 예가범위, 예가갯수 및 추첨예가 갯수를 선택하신 후 계산 버튼 클릭</td>
			</tr>
			<tr>
				<td style='text-align:left; border: 1px solid #dddddd; color:black; font-weight:normal; ' >- 계산버튼을 클릭할 때마다 난수발생으로 금액이 변경되오니 적당히 계산버튼을 클릭하여 선택하셔서
  입찰기초금액에 활용하시면 됩니다.</td>
			</tr>
			<tr>
				<td style='text-align:left; border: 1px solid #dddddd; color:black; font-weight:normal; ' >- 기초금액 등에 대한 %는 낙찰예정가격을 기준으로하여 86.745% 또는 87.745%를 선택하여 이용하셔야 합니다.</td>
			</tr>
</div>
<br>
<div class="btn_area" id='continueSearch' >
		<a onclick="calc();" class="search"><input type=button value="계산"></a>
		<a onclick="prnt();" class="search"><input type=button value="인쇄"></a>
</div>
</form>
</body>
</html>