<?
@extract($_GET);
@extract($_POST);
session_start();
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');
$g2bClass = new g2bClass;
$dbConn = new dbConn;

$conn = $dbConn->conn(); //

// --------------------------------- log
$rmrk = '입찰기록창';
$dbConn->logWrite2($id, $_SERVER['REQUEST_URI'], $rmrk, '', '09');
// --------------------------------- log

if (!isset($compno)) {
	echo '사업자등록번호가 없습니다.';
	exit;
}

$sql = "select compno, compname, repname from openCompany where compno='" . $compno . "'";

//echo $sql;
$result = $conn->query($sql);
$compname = '없는 사업자등록번호'; // 업체명
if ($row = $result->fetch_assoc()) {
	$compno = $row['compno'];	// 사업자등록번호
	$compname = $row['compname']; // 업체명
	$repname = $row['repname'];	// 대표자
}
$today = Date('Y');

if ($duration == '') $duration = $g2bClass->countDuration($today);
$openBidSeq = 'openBidSeq_' . $duration;
$openBidInfo = 'openBidInfo'; //_'.$duration;

// rbidNo (입찰번호) 추가:: 낙찰결과에 공고번호,공고ord,사업자번호,입찰번호 ==>4개를 유니크Key로 
// -by jsj 20190804
$sql = " SELECT x.*, y.bidNtceNm,y.ntceInsttNm,y.dminsttNm,y.bidtype pss,y.opengDt,y.sucsfbidRate FROM ( ";
$sql .= " SELECT a.tuchalamt,a.tuchalrate,a.tuchaldatetime,a.remark, a.bidNtceNo, MAX(a.bidNtceOrd) AS bidNtceOrd, a.rbidNo ";
$sql .= " FROM openBidSeq_2020 a where a.compno='" . $compno . "' ";
$sql .= " GROUP BY a.bidNtceNo ";
$sql .= " UNION ";
$sql .= " SELECT b.tuchalamt,b.tuchalrate,b.tuchaldatetime,b.remark, b.bidNtceNo, MAX(b.bidNtceOrd) AS bidNtceOrd, b.rbidNo ";
$sql .= " FROM openBidSeq_2019 b where b.compno='" . $compno . "' ";
$sql .= " GROUP BY b.bidNtceNo ";
$sql .= " UNION ";
$sql .= " SELECT c.tuchalamt,c.tuchalrate,c.tuchaldatetime,c.remark, c.bidNtceNo, MAX(c.bidNtceOrd) AS bidNtceOrd, c.rbidNo ";
$sql .= " FROM openBidSeq_2018 c where c.compno='" . $compno . "' ";
$sql .= " GROUP BY c.bidNtceNo ";
$sql .= " UNION ";
$sql .= " SELECT d.tuchalamt,d.tuchalrate,d.tuchaldatetime,d.remark, d.bidNtceNo, MAX(d.bidNtceOrd) AS bidNtceOrd, d.rbidNo ";
$sql .= " FROM openBidSeq_2017 d where d.compno='" . $compno . "' ";
$sql .= " GROUP BY d.bidNtceNo ";
$sql .= " UNION ";
$sql .= " SELECT e.tuchalamt,e.tuchalrate,e.tuchaldatetime,e.remark, e.bidNtceNo, MAX(e.bidNtceOrd) AS bidNtceOrd, e.rbidNo ";
$sql .= " FROM openBidSeq_2016 e where e.compno='" . $compno . "' ";
$sql .= " GROUP BY e.bidNtceNo ";
$sql .= " ) x, openBidInfo y ";
$sql .= " WHERE x.bidNtceNo = y.bidNtceNo ";
$sql .= " AND x.bidNtceOrd = y.bidNtceOrd ";
$sql .= " ORDER BY x.bidNtceNo DESC "; 

$result = $conn->query($sql);
//var_dump($result);
$colArray = array('tuchalrate', 'sucsfbidRate', 'seq');
$json_rs = $g2bClass->rs2Json2($result); //,$colArray) ;
//echo $json_rs;

$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"
?>
<!DOCTYPE html>
<html>

<head>
	<title>입찰기록><?= $compname ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="format-detection" content="telephone=no">
	<!--//-by jsj 전화걸기로 링크되는 것 막음 -->
	<style>
		input {
			-webkit-appearance: none;
			-webkit-border-radius: 0;
		}
	</style>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/ulocawp/wp-content/themes/one-edge/style.css" />
	<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
	<link rel="stylesheet" href="/jquery/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/dhtml/codebase/fonts/font_roboto/roboto.css" />
	<link rel="stylesheet" type="text/css" href="/dhtml/codebase/dhtmlx.css" />
	<script src="/jquery/jquery.min.js"></script>
	<script src="/jquery/jquery-ui.min.js"></script>
	<script src="/g2b/g2b.js"></script>
	<script src="/dhtml/codebase/dhtmlx.js"></script>
	<script>
		//copy URL ============================================================
		function copyURL() {
			var url = document.location.href;
			try {
				shortURL(url);
			} catch (e) {
				alert('Error:'.e);
			}
			/*
			setTimeout(function() {
				prompt('Ctrl+C를 눌러 아래의 URL을 복사하세요:', <?= gBitlyData ?>);
			}, 1000);
			*/
		}

		var mobile = '<?= $mobile ?>'; // "Mobile" : "Computer"
		var myLineChart;

		function mydrawChart() {
			//clog('drawChart');
			chartbox = document.getElementById("chartbox");
			if (data.length < 80) chartbox.style.width = '100%';
			else chartbox.style.width = (data.length * 12) + 'px'; //2400px
			clog('chartbox.style.width='+chartbox.style.width);
			myLineChart = new dhtmlXChart({
				view: "line",
				container: "chartbox",
				value: "#tuchalrate#",
				item: {
					borderColor: "#1293f8",
					color: "#ffffff"
				},
				line: {
					color: "#1293f8",
					width: 1
				},
				xAxis: {
					template: "#seq#"
				},
				offset: 0,
				yAxis: {
					start: 82,
					end: 92,
					step: 0.1,
					title: "<span style='font: normal bold small 궁서 ; color: red;'>투찰율</span>",
					template: function(obj) {
						return (obj % 1 ? "" : obj)
					}
				},
				legend: {
					values: [{
							text: "당사",
							color: "#1293f8"
						},
						{
							text: "1순위",
							color: "#E9602C"
						}
					],
					valign: "bottom",
					align: 'center',
					layout: "x"
				}
			});
			myLineChart.addSeries({
				view: "line",
				item: {
					borderColor: "#E9602C", //1293f8",
					color: "#E9602C"
				},
				line: {
					color: "#ee36ab",
					width: 1
				},
				value: "#sucsfbidRate#"
			});
			myLineChart.parse(data, "json");
		}
		var data = <?= $json_rs ?>;
	</script>

</head>
<?
if ($mobile == "Mobile") {
	$emaildiv_top1 = 120;
	$emaildiv_top2 = 120;
	$emaildiv_right1 = 10;
	$emaildiv_right2 = 96;
	$emaildiv_right3 = 182;
	$emaildiv_right4 = 248;
} else {
	$emaildiv_top1 = 52;
	$emaildiv_top2 = 90;
	$emaildiv_right1 = 20;
	$emaildiv_right2 = 105;
	$emaildiv_right3 = 190;
	$emaildiv_right4 = 275;
}
?>
<!-- 그래프 보이기 -->
<body onload='mydrawChart();'>

	<!-- 이메일 삭제 
	<div id='emaildiv' style='visibility:hidden;position: fixed; top: <?= $emaildiv_top2 ?>px; right: <?= $emaildiv_right4 ?>px; width:150px;'>
		<input type="text" style='border:1px solid #000; width:150px;' name="emailid" id="emailid" autocomplete="on" placeholder="email 주소" onkeypress="if(event.keyCode==13) {mailMe3(); return false;}" value="<?= $current_user->user_email ?>"  />
	</div>
	<div style='position: fixed; top: <?= $emaildiv_top1 ?>px; right: <?= $emaildiv_right3 ?>px;' class="btn_areas"><a onclick="mailMe3();" class="search">이메일</a></div>
	-->

	<div style='position: fixed; top: <?= $emaildiv_top1 ?>px; right: <?= $emaildiv_right3 ?>px;' class="btn_areas"><a onclick="location.href='https://uloca.net'" class="search">통합검색</a></div>
	<div style='position: fixed; top: <?= $emaildiv_top1 ?>px; right: <?= $emaildiv_right2 ?>px;' class="btn_areas"><a onclick="copyURL();" class="search">링크복사</a></div>
	<div style='position: fixed; top: <?= $emaildiv_top1 ?>px; right: <?= $emaildiv_right1 ?>px;' class="btn_areas"><a onclick="self.close();" class="search">닫 기</a></div>

	<!-- 입찰 결과 
	<div id='monolithdiv' style="overflow:auto; width:98%; height:250px; padding:10px; background-color:#eeeeee;">
 	<canvas id="monolithCanvas" width="900" height="200"></canvas>
	</div>
	-->

	<form name="popForm">
		<input type="hidden" name="bidNtceNo" value="" />
		<input type="hidden" name="bidNtceOrd" />
		<input type="hidden" name="pss" />
		<input type="hidden" name="resudi" value="<?= $resudi ?>" />
		<input type="hidden" name="from" />
	</form>

	<div id='bidinfo'>
		<div id='bidinfohead' style='font-size:14px;'>
			<div id=summaryrec></div>
			<center>
			<div id=totalrec >total record=xx</div>
			</center>
		</div> <!-- end of bidinfohead -->
	</div>
	<!-- change  color from #438ad1 to #666666 -->
	<script>
		//document.getElementById('totalrec').innerHTML = '(<?= $tuchalrate0Cnt ?>건 제외)';
		document.getElementById('summaryrec').innerHTML = '<font size=5><strong><?= $compname ?></strong></font>사업자번호:<?= $compno ?>, 대표:<?= $repname ?>';
		document.getElementById('summaryrec').innerHTML += '<a onclick="showhide()" style="cursor:pointer"><font size=3><strong>▷낙찰1순위만 검색(클릭):<?= $nakchal ?>건</strong></font></a>';
		document.getElementById('summaryrec').innerHTML += '▷평균투찰율:<font color=red><?= round($tuchalrateAvg, 1) ?></font>';
		document.getElementById('summaryrec').innerHTML += ', 표준편차:' + '<font color=red>' + mk + '</font>';
	</script>
	<?
	// --------------------------------------------------------------------------------------
	mysqli_data_seek($result, 0); // 처음으로
	$tuchalrateSum = 0;
	$i = 1;
	$nakchal = 0;
	$rowCount = mysqli_num_rows($result);
	$noLimit = $rowCount; //700; 
	$ilevel = 0;
	if ($mobile == "Computer") {
	?>
		<table class="type10" id="specData" style="text-align: left; border: 0px solid #dddddd; word-break:break-all;">
			<thead>
				<tr>
					<th scope="cols" width="4%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">No.</th>
					<th scope="cols" width="8%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">공고번호<br>(상세보기)</th>
					<th scope="cols" width="20%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">공고명<br>(낙찰결과)</th>
					<th scope="cols" width="18%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">수요기관</th>
					<th scope="cols" width="5%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">종류</th>
					<th scope="cols" width="12%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">입찰금액(원)</th>
					<th scope="cols" width="8%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">투찰율(%)</th>
					<th scope="cols" width="12%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">투찰일시</th>
					<th scope="cols" width="3%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">입찰번호</th>
					<th scope="cols" width="10%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">개찰순위</th>
				</tr>
			</thead>
			<tbody>
			<?
			$json_string = $g2bClass->rs2Json2bidRec($result);
			mysqli_data_seek($result, 0); // 처음으로
			while ($row = $result->fetch_assoc()) {
				if ($i > $noLimit) continue;
				if ($row['tuchalrate'] > 70 && $row['tuchalrate'] < 100) {
					$tuchalrateSum += $row['tuchalrate'];
					$ilevel++;
				}
				if ($row['remark'] == '1') { // && $row['tuchalrate'] > 0) {
					$nakchal++;
					$colordate = 'color:red;font-weight: bold;';
				} else {
					$colordate = '';
				}
				$rank = $row['remark'];

				$tr = '<tr>';
				$tr .= '<td style="text-align: center;">' . $i . '</td>';
				//$tr .= '<td ><a href="http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='.$row['bidNtceNo'].'&bidseq='.$row['bidNtceOrd'].'&releaseYn=Y&taskClCd=5">'.$row['bidNtceNo'].'-'.$row['bidNtceOrd'].'</a></td>';
				$tr .= '<td ><a onclick=
			"viewBid(\'' . $row['bidNtceNo'] . '\',\'' . $row['bidNtceOrd'] . '\')" />' . $row['bidNtceNo'] . '-' . $row['bidNtceOrd'] . '</a></td>';
				$tr .= '<td ><a onclick="viewRslt(\'' . $row['bidNtceNo'] . '\',\'' . $row['bidNtceOrd'] . '\',\'' . $row['opengDt'] . '\',\'' . $row['pss'] . '\')">' . $row['bidNtceNm'] . '</a></td>';
				$tr .= '<td>' . $row['dminsttNm'] . '</td>';
				$tr .= '<td style="text-align: center;">' . $row['pss'] . '</td>';
				$tr .= '<td style="text-align: right;' . $colordate . '">' . number_format($row['tuchalamt']) . '</td>';
				$tr .= '<td style="text-align: right;' . $colordate . '">' . $row['tuchalrate'] . '</td>';
				$tr .= '<td style="text-align: center;' . $colordate . '">' . $row['tuchaldatetime'] . '</td>';
				$tr .= '<td style="text-align: center;' . $colordate . '">' . $row['rbidNo'] . '</td>';
				$tr .= '<td style="text-align: center;' . $colordate . '">' . $rank . '</td>';
				//$tr .= '<td ><a onclick="viewRslt(\''.$row['bidNtceNo'].'\',\''.$row['bidNtceOrd'].'\',\''.$row['opengDt'].'\',\''.$row['pss'].'\')">'.$rank.'</a></td>';

				$tr .= '</tr>';
				echo $tr;
				$i += 1;
			}
			echo '</tbody></table></div>';

			$i--;
			if ($ilevel > 0) $tuchalrateAvg = $tuchalrateSum / $ilevel;
			else $tuchalrateAvg = $tuchalrateSum;
			mysqli_data_seek($result, 0); // 처음으로

			//$json_rs = $g2bClass->compressJson($result,$colArray,'') ;	
			//echo $json_rs;

		} // Computer
		else if ($mobile == "Mobile") {
			echo "<hr style='border:solid 0.5px darkorange'>";
			$idx = 1;
			$btncss = 'style="background-color:#E9602C; color:#ffffff; width:110px; height:30px;border:0;"';
			while ($row = $result->fetch_assoc()) {
				//if ($i > $noLimit ) continue;
				$licn = '';
				if ($row['tuchalrate'] > 70 && $row['tuchalrate'] < 100) {
					$tuchalrateSum += $row['tuchalrate'];
					$ilevel++;
				}
				if ($row['remark'] == '1') { // && $row['tuchalrate'] > 0) {
					$nakchal++;
					$colordate = ' style="color:black;font-weight: bold; font-size:10px;" ';
				} else {
					$colordate = ' style="color:black; font-weight:normal; font-size:10px;"';
				}
				//if ($row['tuchalrate'] == 0) $rank = '';
				//else $rank = $row['remark'];
				$rank = $row['remark'];

				$licn .= '<div';
				if ($rank != '1') $licn .= ' name="rank1" ';
				$licn .= ' style="display:\'inline;\'">';
				$licn .= '<li><a ' . $colordate . '  onclick=openButton(' . $idx . ') >'; // '.$colordate.'>';
				$licn .= $row['bidNtceNm'] . '<br /> <p >- ' . $row['dminsttNm'] . '<br /> ' . ' ';
				$licn .= number_format($row['tuchalamt']) . '원 / ' . $row['tuchalrate'] . '% / 순위:' . $rank . '<br />';
				$licn .= $row['tuchaldatetime'] . '&nbsp;&nbsp;' . $row['bidNtceNo'] . '-' . $row['bidNtceOrd'] . ' <font color=red font-weight: bold>' . $row['pss'] . '</font></p></a>';
				$licn .= '<div id="link' . $idx . '" style="display:none;">';

				$licn .= '<center><p>
			<input type=button ' . $btncss . ' value="입찰공고" onclick=
			"viewBid(\'' . $row['bidNtceNo'] . '\',\'' . $row['bidNtceOrd'] . '\')" />
			<input type=button ' . $btncss . ' value="입찰결과" onclick=
			"viewRslt(\'' . $row['bidNtceNo'] . '\',\'' . $row['bidNtceOrd'] . '\',\'' . $row['opengDt'] . '\',\'' . $row['pss'] . '\')" /></p></center>';

				$licn .= '</div>';
				$licn .= '</li></div>'; //<!-- br -->';

				echo $licn;
				$idx += 1;
				$i++;
			}
			echo ("<hr style='border:solid 0.5px '>");
			$i--;
			if ($ilevel > 0) $tuchalrateAvg = $tuchalrateSum / $ilevel;
			else $tuchalrateAvg = $tuchalrateSum;
		} // Mobile
			?>
			<!-- CHART 하단  //-by jsj 190518 -->
			<div style="overflow:auto; width:98%; height:600px; padding:10px; background-color:#eeeeee;">
				<div id="chartbox" style="width:2400px;height:600px;border:1px solid #c0c0c0;"></div>
			</div>
			<!-- CHART -->

			<!-- </div> 
			<!-- end of bidinfo -->

			<div id=mail style='visibility: hidden;'>
				<form action="/g2b/sendmail2.php" name="mailForm" id="mailForm" target='new_blank' method="post">
					<input type="text" name="email2" id="email2" autocomplete="on" value="" />
					<input type="text" name="subject" id="subject" value="입찰 기록" />
					<input type="text" name="message" id="message" value="" />
					<input type="hidden" name="resudi" value="<?= $resudi ?>" />

				</form>
				<form name="compInfoForm" style='visibility: hidden;display:inline;'>
					<input type="hidden" name="resudi" value="<?= $id ?>" />
					<input type="hidden" name="id" value="<?= $id ?>" />
					<input type="hidden" name="compno" />
					<input type="hidden" name="opengDt" />
				</form>
			</div><!-- end mail -->

			<script>
				var data2 = '<?= $json_string ?>';
				//clog(data2);
				/*
				bidNtceNm: "가축질병검사실(BL3) 설비공사(기계)"
				bidNtceNo: "20180732738"
				bidNtceOrd: "00"
				bidtype: "공사"
				dminsttNm: "세종특별자치시"
				opengDt: "2018-08-10 11:00:00"
				rank: "1"
				tuchalamt: "615333900"
				tuchaldatetime: "2018-08-09 21:24:35"
				tuchalrate: "88.886"
				*/
				function openButton(idx) {
					if (document.getElementById("link" + idx).style.display == 'inline') {
						document.getElementById("link" + idx).style.display = 'none';
					} else document.getElementById("link" + idx).style.display = 'inline';
				}

				function viewBid(bidNtceNo, bidNtceOrd) {
					url = 'http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=' + bidNtceNo + '&bidseq=' + bidNtceOrd + '&releaseYn=Y&taskClCd=5';
					//location.href = url;
					popupWindow = window.open(url); //,'_blank','height=920,width=840,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');

				}

				function openFirst() {
					//alert('openFirst');
					var x = document.getElementsByName("rank1");
					var i;
					for (i = 0; i < x.length; i++) {
						if (x[i].style.display != 'none') {
							x[i].style.display = 'none';
						} else x[i].style.display = 'inline';
					}
				}

				//var data = JSON.parse(datas);
				//console.log(data.length);
				function mailMe3() {
					emaildiv = document.getElementById('emaildiv');
					emailid = document.getElementById('emailid');
					if (emaildiv.style.visibility == 'hidden') {
						emaildiv.style.visibility = 'visible';
						emailid.focus();
						return;
					}
					if (emailid.value == "") {
						alert('email 주소를 입력하세요.');
						emaildiv.style.visibility = 'hidden';
						return;
					}
					emaildiv.style.visibility = 'hidden';
					frm2 = document.mailForm;
					frm2.email2.value = emailid.value;
					hdr2 = document.getElementById('bidinfohead').innerHTML;
					i = hdr2.indexOf('<a ');
					if (i > 2) hdr2 = hdr2.substring(0, i);

					msg = document.getElementById('specData').innerHTML; //json2table(data2) ; //document.getElementById('bidinfo').innerHTML;
					hdr = '<p><a href="http://uloca.net"><input  type="button" value="유로카 입찰정보" style="width:300px; background-color:#E9602C; height:28px; color:#ffffff; cursor:pointer; font-size:14px; font-weight: bold; text-align:center; border:solid 1px #99bbe8; border-bottom:solid 1px #99bbe8;"></a></p>';
					//msg = msg.substr(0,s)+msg.substr(e);
					//console.log(s+'/'+e+'/'+msg.substr(0,400));
					frm2.message.value = hdr + hdr2 + msg;
					frm2.submit();
				}

				function json2tablehead(data2) {
					tr = '<table class="type10" id="specData" style="text-align: left; border: 0px solid #dddddd; word-break:break-all;">';
					tr += '<thead>';
					tr += '    <tr>';
					tr += '        <th scope="cols" width="4%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">No.</th>';
					tr += '		<th scope="cols" width="8%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">공고번호<br>(상세보기)</th>';
					tr += '		<th scope="cols" width="20%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">공고명<br>(낙찰결과)</th>';
					tr += '        <th scope="cols" width="18%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">수요기관</th>';
					tr += '        <th scope="cols" width="5%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">종류</th>';
					tr += '       <th scope="cols" width="12%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">입찰금액(원)</th>';
					tr += '       <th width="8%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">투찰율(%)</th>';
					tr += '        <th scope="cols" width="12%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">투찰일시</th>';
					tr += '        <th scope="cols" width="13%;" style="padding: 6px; font-weight: bold; vertical-align: top; text-align: center; color: #fff; background: #666666; margin: 2px 2px;">개찰순위</th>';
					tr += '    </tr>';
					tr += '</thead>';
					return tr;
				}

				function json2table(data2) {
					idx = 1;
					thead = json2tablehead(data2);
					tbody = '<tbody>';
					for (i = 0; i < data2.length; i++) {
						tbody += '<tr><td>' + idx + '</td>';
						tbody += '<td><a href="http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=' + data2[i]['bidNtceNo'] + '&bidseq=' + data2[i]['bidNtceOrd'] + '&releaseYn=Y&taskClCd=5">' + data2[i]['bidNtceNo'] + '-' + data2[i]['bidNtceOrd'] + '</a></td>';
						tbody += '<td><a href="http://uloca.net/g2b/bidResult.php?bidNtceNo=' + data2[i]['bidNtceNo'] + '&bidNtceOrd=' + data2[i]['bidNtceOrd'] + '&pss=' + data2[i]['bidtype'] + '&from=getBid">' + data2[i]['bidNtceNm'] + '</a></td>';
						tbody += '<td style="color:#000;">' + data2[i]['dminsttNm'] + '</td>';
						tbody += '<td style="color:#000;" align="center">' + data2[i]['bidtype'] + '</td>';
						tbody += '<td style="color:#000;" align="right">' + number_format(data2[i]['tuchalamt']) + '</td>';
						tbody += '<td style="color:#000;" align="right">' + data2[i]['tuchalrate'] + '</td>';
						tbody += '<td style="color:#000;" align="center">' + data2[i]['tuchaldatetime'] + '</td>';
						tbody += '<td style="color:#000;" align="center">' + data2[i]['rank'] + '</td></tr>';
						idx++;
					}
					tbody += '</table>'
					tbody = thead + tbody;
					return tbody;
				}
				var showhideSW = false;
				if (mobile == "Mobile") showhideSW = true;

				function showhide() {
					if (showhideSW) {
						showhideSW = false;
						if (mobile == "Mobile") openFirst();
						else showhide2();

					} else {
						showhideSW = true;
						if (mobile == "Mobile") openFirst();
						else showhide2();
					}
					//alert(showhideSW);
				}

				function showhide2() {
					var table = document.getElementById("specData");
					for (var i = 1, row; row = table.rows[i]; i++) {
						//iterate through rows
						//rows would be accessed using the "row" variable assigned in the for loop
						//console.log(row.cells[7].innerHTML);
						if (showhideSW == true && row.cells[9].innerHTML != '1') {
							row.style.display = 'none';
						} else {
							row.style.display = '';
						}

					}
				}
				document.getElementById('totalrec').innerHTML = 'total record=<?= $i ?>';
				//document.getElementById('summaryrec').innerHTML = '<a onclick="showhide()" style="cursor:pointer"><font color=blue>낙찰건수</font></a>: <font color=red>'+<?= $nakchal ?>+' </font> 평균 투찰율: <font color=red><?= round($tuchalrateAvg, 1) ?></font>'; <a onclick="mailMe3();" class="search">이메일</a>

				<?

				// 표준편차 계산 -------------------------------------------------------------------------
				mysqli_data_seek($result, 0); // 처음으로
				$totalCnt = $i;
				$i = 0;
				if ($totalCnt > 0) $w = 900 / $totalCnt;
				$x = 36;
				$tuchalrate1 = 0;
				$xs = $x;
				$ys = 0;
				$tuchalrate0Cnt = 0;
				while ($row = $result->fetch_assoc()) {
					if ($row['tuchalrate'] > 50 && $row['tuchalrate'] <= 100) {
						$tuchalrate0 = $row['tuchalrate'] - $tuchalrateAvg;
						$tuchalrate0 = $tuchalrate0 * $tuchalrate0;
						$tuchalrate1 += $tuchalrate0;
					} else {
						$tuchalrate0Cnt++;
					}
				}
				$ii = $i - 1 - $tuchalrate0Cnt;
				$tuchalrate0 = $tuchalrate1 / $ii;
				//echo $tuchalrate0;
				?>

				var mk = 0;
				mk = Math.abs(<?= $tuchalrate0 ?>);
				mk = Math.floor(Math.sqrt(mk) * 10);
				mk = mk / 10;
				//document.getElementById('totalrec').innerHTML = '(<?= $tuchalrate0Cnt ?>건 제외)';
				document.getElementById('summaryrec').innerHTML = '<font size=5><strong><?= $compname ?></strong></font>&nbsp&nbsp사업자번호:<?= $compno ?>, 대표:<?= $repname ?>';
				document.getElementById('summaryrec').innerHTML += '<br><a onclick="showhide()" style="cursor:pointer"><font size=3><strong>▷낙찰 1순위:&nbsp <?= $nakchal ?>건(클릭)</strong></font></a>';
				document.getElementById('summaryrec').innerHTML += '<br><font color=""> ✔ 입찰이력이 누락된 경우 해당공고의 낙찰결과를 확인해보세요.</font>';
				document.getElementById('summaryrec').innerHTML += '<br>▷평균투찰율:<font color=red><?= round($tuchalrateAvg, 1) ?></font>';
				document.getElementById('summaryrec').innerHTML += ', 표준편차:' + '<font color=red>' + mk + '</font>';
			</script>
			<!-- div class="btn_area" style='width:100% !important;' >
				<input type=button style='height:30px; width:120px; font-size:14px; line-height:30px; color:#fff; text-align:center; padding:2px; background-color:#438ad1;; border:0;vertical-align: middle; cursor:pointer;' value="닫  기" onclick="self.close()" />
			</div -->