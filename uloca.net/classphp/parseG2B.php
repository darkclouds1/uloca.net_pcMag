<?php

class parseG2B { 
	/*
		나라장터 http://www.g2b.go.kr/index.jsp crawling
		2018/06/20 by HMJ

	*/
	// 통합검색결과 목록 만들기
	var $rec = 0;
	var $suc = 0;
	var $maxNo = 10;
	var $maxPage = 10;
	var $curPage = 1;
	function getList($text_utf) {
		
		// 목록
		$texts = strpos($text_utf, '<div class="tit_bar">');
		$texte = strpos($text_utf, 'Page Navigation',$texts+10);
		$text = substr($text_utf,$texts+21,$texte-$texts-5);
		//echo($text);
		// record 수
		$texts = strpos($text, '<h3 class="tit">입찰공고');
		$texte = strpos($text, '</h3>',$texts+1);
		$records = substr($text,$texts,$texte-$texts+5);
		echo($records.' 건<br><br>');

		$texts = strpos($text,'<ul class="search_list">');
		$text = substr($text,$texts);
		echo($text);
		// 페이지 링크
		$pages = strpos($text_utf, 'Page Navigation');
		$pagee = strpos($text_utf, 'page_last',$pages);
		$pagess = substr($text_utf,$pages+46,$pagee-$pages-35).'</a>';
		echo('<br><br><br><br>'.'page='.$pagess);
	}
	var $nexts = 0;
	function getOneItem($text,$start) {
		$one = "";
		$texts = strpos($text, '<span class="cate1">',$start);
		$texte = strpos($text, '<span class="cate1">',$texts+20);
		if ($texte <1) exit;
		$one = substr($text,$texts-10,$texte-$texts);
		$start = $texts-10;

		$nexts = $start;
	} 
	//$items = {};
	function ItemToJson($one) {

	}
	
	function connDB() {
		$servername = "localhost";
		$username = "uloca22";
		$password = "w3m69p21!@";
		$dbname = "uloca22";
		$tableG2BLIST = "G2BLIST";
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			die("DB Connection failed: " . $conn->connect_error);
		} 
		return $conn;
	}
	// main 검색 결과를 uloca db로 복사 ----------------------------------------------
	function makeInsert($text_utf,$tableG2BLIST,$conn) {
		$rec = 0;
		$suc = 0;
		$texts = strpos($text_utf, 'tbody');
		$texte = strpos($text_utf, '/tbody',$texts+10);
		if ($texts > 0 && $texte > $texts )
			$text_utf = substr($text_utf,$texts+6,$texte-$texts-7);
		//echo $text_utf;
		$x = strpos($text_utf, '<tr');
		//$n=0;
		$maxNo = 10;

		
		while($x >=0 && $rec<$maxNo) {
			//echo 'x='.$x.' n='.$n;
			$y = strpos($text_utf, '</tr', $x);
			$ln = substr($text_utf,$x,$y-$x);
			//echo $ln;
			$s = strpos($ln,'<div',1);
			$e = strpos($ln,'</div',$s+4);
			$work = substr($ln,$s+5,$e-$s-5);
			//echo ' work='.$work.'//';

			$s = strpos($ln,'<div',$e);
			$e = strpos($ln,'</div',$s+4);
			$bid = substr($ln,$s+5,$e-$s-5);
			if ($bid == "") continue;
			// <a href="http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno=20180625150&amp;bidseq=00&amp;releaseYn=Y&amp;taskClCd=3 ">20180625150-00</a>
			
			$ss = strpos($bid,'href="',0);
			$ee = strpos($bid,'">',$ss+4);
			$href = substr($bid,$ss+6,$ee-$ss-7);
			$e2 = strpos($bid,'</a>',$ss+4);
			$bid = substr($bid,$ee+2,$e2-$ee-2);
			//echo ' e='.$e.' ee='.$ee.' e2='.$e2.' bid='.$bid.'///';
			//echo 'href='.$href.'///';
			

			$s = strpos($ln,'<div',$e);
			$e = strpos($ln,'</div',$s+4);
			$kind = substr($ln,$s+5,$e-$s-5);
			//echo 'kind='.$kind.'///';

			$s = strpos($ln,'<div',$e);
			$e = strpos($ln,'</div',$s+4);
			$workname = substr($ln,$s+5,$e-$s-5);
			$ss = strpos($workname,'<a ',1);
			$ee = strpos($workname,'">',$ss+4);
			//$href = substr($workname,$ss+9,$ee-$ss-9);
			$workname = substr($workname,$ee+2,$e-$ee-6);
			//echo 'workname='.$workname.'///';

			$s = strpos($ln,'<div',$e);
			$e = strpos($ln,'</div',$s+4);
			$workorg = substr($ln,$s+5,$e-$s-5);
			//echo 'workorg='.$workorg.'///';

			$s = strpos($ln,'<div',$e);
			$e = strpos($ln,'</div',$s+4);
			$origorg = substr($ln,$s+5,$e-$s-5);
			//echo 'origorg='.$origorg.'///';

			$s = strpos($ln,'<div',$e);
			$e = strpos($ln,'</div',$s+4);
			$contract = substr($ln,$s+5,$e-$s-5);
			//echo 'contract='.$contract.'///';

			$s = strpos($ln,'<div',$e);
			$e = strpos($ln,'<br',$s+4);
			$dtin = substr($ln,$s+5,$e-$s-5);
			//echo 'dtin='.$dtin.'//';

			$s = strpos($ln,'>(',$e);
			//$e = strpos($ln,'<br',$s+4);
			$dtclose = substr($ln,$s+2,16);
			//echo 'dtclose='.$dtclose.'//';

			$s = strpos($ln,'<div',$s+10);
			$e = strpos($ln,'</div',$s+4);
			$sugeup = substr($ln,$s+5,$e-$s-5);
			$su = strpos($sugeup,'title=',1);
			if ($su > '0') {
				$se = strpos($sugeup,'">',$su);
				$sugeup = substr($sugeup,$su+7,$se-$su-7);
			}
			//echo 'sugeup='.$sugeup.'//';
		/*
		<button type="button" class="btn_agree" onclick="bidLink_jointSppAgtFacil('20180623192');return false;" title="협정"><span class="blind">협정</span></button>
		*/
			$s = strpos($ln,'<div',$e);
			$e = strpos($ln,'</div',$s+4);
			$tuchal = substr($ln,$s+5,$e-$s-5);
			$su = strpos($tuchal,'title=',1);
			if ($su > '0') {
				$se = strpos($tuchal,'">',$su);
				$tuchal = substr($tuchal,$su+7,$se-$su-7);
			}
			//echo 'tuchal='.$tuchal.'//';

			$sql = 'insert into '.$tableG2BLIST. ' (bid, bidno, bidseq, work,kind, workname,workorg,origorg,';
			$sql .= 'contract, dtin, dtclose, sugeup, tuchal, href) ';
			$sql .= "VALUES ('" . $bid . "', '', '', '" .$work. "','" . $kind . "','" . $workname . "',";
			$sql .= "'".$workorg."', '".$origorg."', '".$contract."','".$dtin."','" . $dtclose."',";
			$sql .= "'".$sugeup."','".$tuchal."','".$href."')";

			//echo '<br>'.$sql.'<br>';

		if ($conn->query($sql) === TRUE) {
		?>
			<tr>
			  <td><?=$work?></td>
			  <td><?=$bid?></td>
			  <td><?=$kind?></td>
			  <td><?=$workname?></td>
			  <td><?=$workorg?></td>
			  <!--td><?=$origorg?></td-->
			  <td><?=$contract?></td>
			  <td><?=$dtin?></td>
			  <td>ok</td>
			</tr>

			<?
			$suc ++;
		} else {
		?>
			<tr>
			  <td><?=$work?></td>
			  <td><?=$bid?></td>
			  <td colspan=6><?=$conn->error?></td>
			</tr>
		<?
		}
	$x = strpos($text_utf, '<tr', $y);
	$rec ++;
	//echo ('x='.$x.' '.$rec. '레코드가 시도, '.$suc.' 레코드가 복사 되었습니다.'.$maxNo);
	//echo ($x >=0 . $rec<=$maxNo);
	}
	echo ($rec. '레코드가 시도, '.$suc.' 레코드가 복사 되었습니다.');
}	// makeInsert
}	// parseG2B
?>