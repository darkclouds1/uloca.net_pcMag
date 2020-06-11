<?php
	/*
		db conn for uloca & uloca23
		2018/06/20 by HMJ
	*/
class dbconn {
	function conn_live() {
		$conn = new mysqli('localhost', 'uloca22', 'w3m69p21!@', 'uloca22');
		return $conn;
	}
	function conn_test() {
		$conn = new mysqli('localhost', 'uloca23', 'uloca23090(', 'uloca23');
		return $conn;
	}
	function conn() {
		$conn = $this->conn_test();
		// $conn = $this->conn_live();	

		if ($conn->connect_error) {
			die("DB Connection failed: " . $conn->connect_error);
		} 
		mysqli_set_charset($conn, 'utf8');
		return $conn;
	}

	function countLog($conn,$ip) { //$_SERVER['REMOTE_ADDR']
		$today = date('Y-m-d');
		$sql = "select count(*) cnt from logdb where ip='".$ip."' and substr(dt,1,10) = '".$today."' ";
		$result = $conn->query($sql);
		//echo $sql;
		$cnt = 0;
		if ($row = $result->fetch_assoc()) $cnt = $row['cnt'];
		//echo ' cnt='.$cnt;
		return $cnt;
	}
	
	function countLogdt($conn,$id,$dt1,$dt2) { //from-to
		//$today = date('Y-m-d');
		$sql = "select count(*) cnt from logdb where id='".$id."' and substr(dt,1,10) >= '".$dt1."' and substr(dt,1,10) <= '".$dt2."' ";
		$result = $conn->query($sql);
		//echo $sql;
		$cnt = 0;
		if ($row = $result->fetch_assoc()) $cnt = $row['cnt'];
		//echo ' cnt='.$cnt;
		return $cnt;
	}
	// -----------------------------------------------
	// 상세검색 
	// 입찰공고, 사전규격 -상세검색 -by jsj 190317
	// -----------------------------------------------
	function getSvrDataDB2($conn, $bidrdo, $kwd, $dminsttNm, $pss,$sYear, $LikeOrEqual, $startNo, $noOfRow) {

		$kwd1 = explode(' ',$kwd);
		$kwds = '';
		$kwd2 = ''; 

		// 공고명 및 공고번호로 조회  -by jsj 20200324
		for ($i=0;$i<sizeof($kwd1);$i++) {
			// 공고명 [-]마이너스는 미포함
			if (strpos($kwd1[$i],'-') !== false) {
				$kwdTmp2 = str_replace('-', '', $kwd1[$i]);
				$kwds .= " bidNtceNm NOT LIKE '%" .$kwdTmp2.  "%' AND "; // [-]미포함
			} else {
				$kwds .= " bidNtceNm LIKE '%" .$kwd1[$i].  "%'  AND ";    // 공고명   (AND=포함된 키워드가 모두 있어야 함)
			}
			$kwd2 .= " bidNtceNo like '%".$kwd1[$i]."%' AND "; // 공고번호, 
		}
		$dminsttNm1 = explode(' ',$dminsttNm);
		$dminsttNms = ''; // dminsttNm
		for ($i=0;$i<sizeof($dminsttNm1);$i++) {
			$dminsttNms .= " dminsttNm like '%".$dminsttNm1[$i]."%' OR ";

		}
		$kwds = substr($kwds,0,strlen($kwds)-4);
		$kwd2 = substr($kwd2,0,strlen($kwd2)-4);
		$dminsttNms = substr($dminsttNms,0,strlen($dminsttNms)-4);

		$sql = "SELECT bidNtceNo, bidNtceOrd, bidNtceNm,     presmptPrce, bidNtceDt, ";
		$sql .="       dminsttNm, bidClseDt,  bidNtceDtlUrl, bidwinnrNm,  bidwinnrBizno, ";
		$sql .="       progrsDivCdNm, cntrctCnclsMthdNm, nobidRsn, ";				// 진행구분, 계약방법, 유찰사유
		$sql .= "		CASE WHEN bidtype = '물품' THEN '입찰물품' ";
		$sql .= "			 WHEN bidtype = '용역' THEN '입찰용역' ";
		$sql .= "			 WHEN bidtype = '공사' THEN '입찰공사' ";
		$sql .= " 			 WHEN bidtype = '사물' THEN '사전물품' ";
		$sql .= "			 WHEN bidtype = '사용' THEN '사전용역' ";
		$sql .= "			 WHEN bidtype = '사공' THEN '사전공사' ELSE bidtype END pss, ";
		$sql .= "		CASE WHEN opengDt IS NULL THEN '' ";
		$sql .= "			 WHEN opengDt = 'NULL' THEN '' ELSE opengDt END opengDt, locate";
		$sql .= " FROM openBidInfo ";
		$sql .= "WHERE 1=1 "; //and substr(bidNtceNo,1,2) = '20' ";
		if ($pss != '') $sql .= "AND bidtype = '".$pss."' ";

		//공고명 키워드
		if ($kwds != '') $sql .= " AND ((" . $kwds . " )) ";
		
		// 수요기관
		if ($dminsttNm != '' && $LikeOrEqual == 'equal') {
			$sql .= " AND dminsttNm = '".$dminsttNm."' "; 
		} else if ($dminsttNms != '') {
			$sql .= " AND ((" . $dminsttNms . " )) ";
		}

		//if ($dminsttNm != '' ) $sql .= " and dminsttNm like '%".$dminsttNm."%' ";
		$sql .= " ORDER BY opengDt desc limit ".$startNo.",".$noOfRow." ";
		//echo($sql);
		
		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($sql);
		//return $sql;
		//$stmt->bind_param("1", $pss);
		//$stmt->bind_param("2", $kwds);
		//if (!$stmt->execute()) return $stmt->errno;
		//$colArray = array ( 'bidNtceNo', 'bidNtceOrd', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt','bidNtceDtlUrl');
		$stmt->execute();
		return $stmt;
	}

	//-------------------------------------
	// 통합검색 -by jsj 20200326
	//-------------------------------------
	function getSvrDataDB4($conn,$kwd,$dminsttNm,$curStart,$cntonce) {
		//"?" 뒤는 수요기관으로 검색 -by jsj 20200326
		$kwd1 = ''; // 1번째 문자열 (공고명, 공고번호)
		$kwd2 = ''; // 2번째 문자열 (수요기관) ? 수요기관
		$kwd3 = ''; // 2번째 문자열 (계약방법) ?? 계약방법

		// ?? 계약방법으로 검색
		if (strpos($kwd,'??')) {
			$kwd = explode('??', $kwd);
			for ($i=0;$i<sizeof($kwd);$i++) {
				if ($i == 0 ) {
					$kwd1 .= " ".$kwd[$i]. " "; // 공고명 문자열은 ? 포함된 1번째열
				} else {
					$kwd3 .= " ".$kwd[$i]. " "; // 계약방법 문자열
				}
			}
		} else {
			// 문자열에 '?? 계약방법' 없음
			$kwd = explode('?', $kwd);
			for ($i=0;$i<sizeof($kwd);$i++) {
				if ($i == 0 ) {
					$kwd1 .= " ".$kwd[$i]. " "; // 공고명 문자열은 ? 포함된 1번째열
				} else {
					$kwd2 .= " ".$kwd[$i]. " "; // 수요기관 문자열
				}
			}
		}

		$kwds = ''; // 공고명 SQL
		$kwdN = ''; // 공고번호 SQL
		$kwdd = ''; // 수요기관 SQL
		$kwdM = ''; // 계약방법 SQL 

		// 공고명, 공고번호 SQL 작성
		$kwd1 = preg_replace("/[#\&\+\%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>\[\]\{\}]/i", "", $kwd1); //특수문자 없앰
		$kwdTmp = explode(' ', trim($kwd1)); 
		for ($i=0;$i<sizeof($kwdTmp);$i++) {
			if ($kwdTmp[$i] == '') continue;

			// 공고명 [-]마이너스는 미포함
			if (strpos($kwdTmp[$i],'-') !== false) {
				$kwdTmp2 = str_replace('-', '', $kwdTmp[$i]);
				$kwds .= " bidNtceNm NOT LIKE '%" .$kwdTmp2.  "%' AND "; // [-]미포함
			} else {
				$kwds .= " bidNtceNm LIKE '%" .$kwdTmp[$i].  "%'  AND ";    // 공고명   (AND=포함된 키워드가 모두 있어야 함)
			}

			// 공고번호는 영문자, 숫자만
			if (ctype_alnum($kwdTmp[$i])) { 							
				$kwdN .= " bidNtceNo = '" .$kwdTmp[$i]. "' OR  "; 	// 공고번호 (OR = 번호는 중복이 거의 없음)
			}
		}

		// 수요기관 SQL 작성 
		$kwd2 = preg_replace("/[#\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>\[\]\{\}]/i", "", $kwd2); //특수문자 없앰
		$kwdTmp = explode(' ', trim($kwd2));
		for ($i=0;$i<sizeof($kwdTmp);$i++) {		
			if ($kwdTmp[$i] == '') continue;

			$kwdd .= " dminsttNm like '%" .$kwdTmp[$i].  "%' OR  "; // 수요기관
		}

		// 계약방법 SQL 작성 
		$kwd3 = preg_replace("/[#\&\+%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>\[\]\{\}]/i", "", $kwd3); //특수문자 없앰 - 계약방법에는 '-'없애지 않음
		$kwdTmp = explode(' ', trim($kwd3));
		for ($i=0;$i<sizeof($kwdTmp);$i++) {		
			if ($kwdTmp[$i] == '') continue;

			// '-' 제외 단어 ex. -협상 = 협상단어를 포함하지 않는거 Not Like
			if (strpos($kwdTmp[$i],'-') !== false) {
				$kwdTmp2 = str_replace('-', '', $kwdTmp[$i]);
				$kwdM .= " cntrctCnclsMthdNm NOT LIKE '%" .$kwdTmp2.  "%' AND "; // 계약방법 미포함
			} else {
				$kwdM .= " cntrctCnclsMthdNm LIKE '%" .$kwdTmp[$i].  "%' OR  ";    // 계약방법
			}
		}
		
		// SQL보완 마지막 4문자 "and " 삭제해서 SQL 보완
		$kwds = substr($kwds,0,strlen($kwds)-4);  // 공고명
		$kwdN = substr($kwdN,0,strlen($kwdN)-4);  // 공고번호
		$kwdd = substr($kwdd,0,strlen($kwdd)-4);  // 수요기관
		$kwdM = substr($kwdM,0,strlen($kwdM)-4);  // 계약방법

		$sql = "SELECT bidNtceNo, bidNtceOrd, bidNtceNm,     presmptPrce, bidNtceDt, ";
		$sql .="       dminsttNm, bidClseDt,  bidNtceDtlUrl, bidwinnrNm,  bidwinnrBizno, ";
		$sql .="       progrsDivCdNm, cntrctCnclsMthdNm, nobidRsn, ";				// 진행구분, 계약방법, 유찰사유
		$sql .= "		CASE WHEN bidtype = '물품' THEN '입찰물품' ";
		$sql .= "			 WHEN bidtype = '용역' THEN '입찰용역' ";
		$sql .= "			 WHEN bidtype = '공사' THEN '입찰공사' ";
		$sql .= " 			 WHEN bidtype = '사물' THEN '사전물품' ";
		$sql .= "			 WHEN bidtype = '사용' THEN '사전용역' ";
		$sql .= "			 WHEN bidtype = '사공' THEN '사전공사' ELSE bidtype END pss, ";
		$sql .= "		CASE WHEN opengDt IS NULL THEN '' ";
		$sql .= "			 WHEN opengDt = 'NULL' THEN '' ELSE opengDt END opengDt, locate";
		$sql .= " FROM openBidInfo ";
		$sql .= "WHERE 1 ";
		if ($dminsttNm == "" ) { 
			if (trim($kwdd) <> '') $sql .= " AND (" .$kwdd. " ) "; // 수요기관
			if (trim($kwdM) <> '') $sql .= " AND (" .$kwdM. " ) "; // 계약방법
			if (trim($kwds) <> '') $sql .= " AND (" .$kwds. " ) "; // 공고명
			if (trim($kwdN) <> '') $sql .= " OR (" .$kwdN. " ) ";  // 공고번호
		} else {  // 파라미터 수요기관으로 재검색
			if (trim($kwdd) <> '') $sql .= " AND  (" .$kwdd. " ) "; // 수요기관
			if (trim($kwds) <> '') $sql .= " AND  (" .$kwds. " ) "; // 공고명
		}
		$sql .= "ORDER BY bidNtceDt desc limit ".$curStart.",".$cntonce." ";
		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		return $stmt;
	}	

	// 입찰공고, 사전규격 2019
	function getSvrDataDB3($conn,$kwd,$dminsttNm,$fromDT,$toDT) {
		//$qry = '%'.$compname.'%';
		/* SELECT bidNtceNo, bidNtceOrd, bidNtceNm, presmptPrce, bidNtceDt, dminsttNm, bidClseDt,bidNtceDtlUrl FROM openBidInfo 
		WHERE bidtype = '물품' 
		and bidNtceNm like '%서버%' 
		and dminsttNm like '%철도%'  
		사전규격 = [ 'bfSpecRgstNo', 'prdctClsfcNoNm', 'asignBdgtAmt', 'rgstDt', 'rlDminsttNm', 'opninRgstClseDt', 'bidNtceNoList' ];
					등록번호			품명					배정예산금액	등록일시		실수요기관명		의견등록마감일시		입찰공고번호목록
		*/
		$kwd1 = explode(' ',$kwd);
		$kwds = '';
		$kwd2 = ''; // dminsttNm
		for ($i=0;$i<sizeof($kwd1);$i++) {
			$kwds .= " bidNtceNm like '%".$kwd1[$i]."%' AND "; // or->and 로 바꿈 20190223
			$kwd2 .= " dminsttNm like '%".$kwd1[$i]."%' OR  ";

		}
		$kwds = substr($kwds,0,strlen($kwds)-4);
		$kwd2 = substr($kwd2,0,strlen($kwd2)-4);
		$sql  = "SELECT bidNtceNo, bidNtceOrd, bidNtceNm, presmptPrce, bidNtceDt, ";
		$sql .= "       dminsttNm, bidClseDt, bidNtceDtlUrl, ";
		$sql .= "  CASE WHEN bidtype = '물품' THEN '입찰물품' "; 
		$sql .= "       WHEN bidtype = '용역' THEN '입찰용역' ";
		$sql .= "       WHEN bidtype = '공사' THEN '입찰공사' ";
		$sql .= "       WHEN bidtype = '사물' THEN '사전물품' ";
		$sql .= "       WHEN bidtype = '사용' THEN '사전용역' ";
		$sql .= "       WHEN bidtype = '사공' THEN '사전공사' ";
		$sql .= "  ELSE bidtype END pss, "; 
		$sql .= "  CASE WHEN opengDt IS NULL THEN '' ";
		$sql .= "       WHEN opengDt = 'NULL' THEN '' ";
		$sql .= "  ELSE opengDt END opengDt,locate ";
		$sql .= "  FROM openBidInfo ";
		$sql .= " WHERE 1=1 ";  //and substr(bidNtceNo,1,2) = '20' ";	
		
		//$sql .= "and ( bidNtceNm like '%".$kwd."%' ";
		$sql .= "  AND ((" . $kwds . " ";
		if ($dminsttNm == "") $sql .= ") OR (". $kwd2 .")) "; //"or dminsttNm like '%".$kwd."%') ";
		else $sql .= " OR dminsttNm = '".$dminsttNm."')) ";
		if ($toDT != 0) $sql .= "and substr(bidNtceDt,1,10) <= '".$toDT."' ";
		$sql .= " AND substr(bidNtceDt,1,10) > '".$fromDT."' ";
		$sql .= " ORDER BY bidNtceDt desc "; // limit ".$startNo.",".$noOfRow." ";
		
		//echo($sql);
		
		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($sql);
		//return $sql;
		//$stmt->bind_param("s", $pss);
		//if (!$stmt->execute()) return $stmt->errno;
		//$colArray = array ( 'bidNtceNo', 'bidNtceOrd', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt','bidNtceDtlUrl');
		$stmt->execute();
		return $stmt;
	}
	
	
	function getSvrDataDB2_mysqli($conn,$bidrdo,$kwd,$dminsttNm,$pss,$sYear,$LikeOrEqual,$startNo, $noOfRow) {
		//$qry = '%'.$compname.'%';
		/* SELECT bidNtceNo, bidNtceOrd, bidNtceNm, presmptPrce, bidNtceDt, dminsttNm, bidClseDt,bidNtceDtlUrl FROM openBidInfo 
		WHERE bidtype = '용역' 
		and bidNtceNm like '%부산%' 
		and dminsttNm like '%철도%'   */
		$sql =  "SELECT bidNtceNo, bidNtceOrd, bidNtceNm, presmptPrce, bidNtceDt, dminsttNm, bidClseDt,bidNtceDtlUrl,bidwinnrNm, bidtype as pss";
		$sql .= "  FROM openBidInfo ";
		$sql .= " WHERE 1=1 ";
		if ($pss != '') $sql .= "and bidtype = '".$pss."' ";
		if ($kwd != '') $sql .= "and bidNtceNm like '%".$kwd."%' ";
		if ($dminsttNm != '' && $LikeOrEqual == 'like') $sql .= "and dminsttNm like '%".$dminsttNm."%' ";
		else if ($dminsttNm != '' && $LikeOrEqual == 'equal') $sql .= "and dminsttNm = '".$dminsttNm."' ";
		$sql .= "order by bidClseDt desc limit ".$startNo.",".$noOfRow." ";
		$result = $conn->query($sql);
		return $result;
	}
	
	function getSvrDataDB($conn,$bidrdo,$kwd,$dminsttNm,$pss,$sYear) {
		return $this->getSvrDataDB2($conn,$bidrdo,$kwd,$dminsttNm,$pss,$sYear,'like','0', '999') ;
	}
	
	function getSvrDataDBLast($conn,$bidrdo,$kwd,$dminsttNm,$pss,$sYear) {
		//$qry = '%'.$compname.'%';
		/* SELECT bidNtceNo, bidNtceOrd, bidNtceNm, presmptPrce, bidNtceDt, dminsttNm, bidClseDt,bidNtceDtlUrl FROM openBidInfo 
		WHERE bidtype = '용역' 
		and bidNtceNm like '%부산%' 
		and dminsttNm like '%철도%'   */
		$sql = "SELECT bidNtceNo, bidNtceOrd, bidNtceNm, presmptPrce, bidNtceDt, dminsttNm, bidClseDt,bidNtceDtlUrl, bidtype as pss";
		$sql .= " FROM openBidInfo ";
		$sql .= "WHERE bidtype = '".$pss."' ";
		if ($kwd != '') $sql .= "and bidNtceNm like '%".$kwd."%' ";
		if ($dminsttNm != '') $sql .= "and dminsttNm like '%".$dminsttNm."%' ";
		$sql .= " order by bidClseDt desc limit 1000,20000";
		//var_dump($sql);
		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($sql);
		//return $sql;
		//$stmt->bind_param("s", $pss);
		//if (!$stmt->execute()) return $stmt->errno;
		//$colArray = array ( 'bidNtceNo', 'bidNtceOrd', 'bidNtceNm', 'presmptPrce', 'bidNtceDt', 'dminsttNm', 'bidClseDt','bidNtceDtlUrl');
		$stmt->execute();
		return $stmt;
	}

	//자동받기 
	function autoRecList($login_userid) {
		$conn = $this->conn();
		//$conn = new mysqli("localhost", "uloca22", "w3m69p21!@", "uloca22");
		//$conn = new mysqli('localhost', 'uloca23', 'uloca23090(', 'uloca23');
			// Check connection
		//echo $current_user;
		//if ($current_user->user_login != '' ) {
		$now = new DateTime();
		$nows = $now->format('Y-m-d H:i:s');
		
		//$sql = 'select a.*,b.till from autoPubDatas a, autoPubAccnt b where a.id=b.id and b.till>= \''.$nows.'\' and a.id = \''. $current_user->user_login . '\' order by a.id';
		$sql = 'SELECT a.*, b.till FROM autoPubDatas AS a LEFT OUTER JOIN autoPubAccnt AS b ON a.id = b.id where a.id = \''. $login_userid . '\'  order by b.till, a.idx';
		//SELECT a.*, b.till FROM autoPubDatas AS a LEFT OUTER JOIN autoPubAccnt AS b ON a.id = b.id where a.id ='jayhmj@naver.com' and b.till >= '20180723' order by a.id
		//$sql = 'select a.*, b.till from autoPubDatas a, autoPubAccnt b where a.id=b.id  order by a.idx';
		//echo 'sql='.$sql;
		$result = $conn->query($sql);
		$cont = '
			<div id=totalrec style="text-align: left;">total records='.mysqli_num_rows($result).'</div>

			<table class="type10" id="bidData">
				<tr>
					<th scope="cols" width="5%;">번호</th>
					<th hidden="hidden">아이디</th>
					<th hidden="hidden">이메일</th>
					<th scope="cols" width="12%;">키워드</th>
					<th scope="cols" width="12%;">수요기관</th>
					<th scope="cols" width="12%;">검색종류</th>
					<th hidden="hidden">종료일</th>

				</tr>
		';
		$i = 0;
		while ($row = $result->fetch_assoc()) {
			$k = $i+1;
			$searchType = $row["searchType"];
			
			if ($searchType == 1) $search = '물품';
			else if ($searchType == 2) $search = '사전규격';
			else if ($searchType == 3) $search = '물품+사전규격';
			else if ($searchType == 4) $search = '공사';
			else if ($searchType == 5) $search = '물품+공사';
			else if ($searchType == 6) $search = '공사+사전규격';
			else if ($searchType == 7) $search = '물품+공사+사전규격';
			else if ($searchType == 8) $search = '용역';
			else if ($searchType == 9) $search = '물품+용역';
			else if ($searchType == 10) $search = '용역+사전규격';
			else if ($searchType == 11) $search = '물품+용역+사전규격';
			else if ($searchType == 12) $search = '공사+용역';
			else if ($searchType == 13) $search = '물품+공사+용역';
			else if ($searchType == 14) $search = '공사+용역+사전규격';
			else if ($searchType == 15) $search = '물품+공사+용역+사전규격';

			/* $sendType = $row["sendType"];
			if ($sendType == 1) $send = '이메일';
			else if ($sendType == 2) $send = '카톡';
			else if ($sendType == 3) $send = '이메일+카톡';
			else if ($sendType == 4) $send = '문자';
			else if ($sendType == 5) $send = '이메일+문자';
			else if ($sendType == 6) $send = '카톡+문자';
			else if ($sendType == 7) $send = '이메일+카톡+문자'; */
			if ($i % 2 == 0) {
				$tr = '<tr onclick="javascript:clickTrEvent(this)">';
				$tr .= '<td style="text-align: center; ">'.$k.'</td>';
				$tr .= '<td hidden="hidden" ">'.$row['id'].'</td>';
				
				$tr .= '<td hidden="hidden">'.$row['email'].'</td>';
				$tr .= '<td>'.$row['kwd'].'</td>';
				$tr .= '<td>'.$row['dminsttnm'].'</td>';
				$tr .= '<td>'.$search.'</td>';
				//$tr .= '<td>'.$send.'</td>';
				$tr .= '<td hidden="hidden" style="text-align:center;">'.$row['till'].'</td>';
				//$tr .='<td hidden="hidden">'.$row['katalk'].'</td>';
				//$tr .='<td hidden="hidden">'.$row['cellphone'].'</td>';
				$tr .='<td hidden="hidden">'.$row['idx'].'</td>';
				$tr .= '</tr>';
			} else {
				$tr = '<tr onclick="javascript:clickTrEvent(this)">';
				$tr .= '<td class="even" style="text-align: center;">'.$k.'</td>';
				$tr .= '<td hidden="hidden" class="even">'.$row['id'].'</td>';
				$tr .= '<td hidden="hidden" class="even">'.$row['email'].'</td>';
				$tr .= '<td class="even" >'.$row['kwd'].'</td>';
				$tr .= '<td class="even" >'.$row['dminsttnm'].'</td>';
				$tr .= '<td class="even" >'.$search.'</td>';
				//$tr .= '<td class="even" >'.$send.'</td>';
				$tr .= '<td hidden="hidden" class="even" style="text-align:center;">'.$row['till'].'</td>';
				//$tr .='<td hidden="hidden">'.$row['katalk'].'</td>';
				//$tr .='<td hidden="hidden">'.$row['cellphone'].'</td>';
				$tr .='<td hidden="hidden">'.$row['idx'].'</td>';
				$tr .= '</tr>';
			}
			$cont .= $tr;
			$i += 1;
		}
		$cont .= '</table>';
		return $cont;
	}
	
	//결제여부 및 프리패스 여부 확인 -by jsj 190317
	// 1: login & 유료회원 & 프리패스 , 0: not login
	function getMemberFee($conn,$userid) {
		if ($userid == '') return 0; // not login
	
		$sql = 'SELECT ( IF ( ';
		$sql .= "(SELECT IF (MAX(m.meta_value) >= now() ,1,0) ";
		$sql .= "FROM wp_users u, wp_posts p, wp_postmeta m ";
		$sql .= "WHERE 1  ";
		$sql .= "AND m.meta_key = 'end_datetime'  "; 
		$sql .= "AND p.id = m.post_id ";
		$sql .= "AND u.ID = p.post_author ";
		$sql .= "AND u.user_login = '".$userid."')  ";
		$sql .= "    OR ";
		$sql .= "(SELECT IF ((u2.PayFreeCD = 99),1,0) ";
		$sql .= "FROM wp_users u, PayUser01M u2  ";
		$sql .= "WHERE 1  ";
		$sql .= "AND u2.termDate >= str_to_date(now(), '%Y-%m-%d') ";
		$sql .= "AND u.user_login = u2.user_login  ";
		$sql .= "AND u.user_login = '".$userid."') = 1, 1,0) ) as log ";
		$sql .= "FROM DUAL ";
	
		$result = $conn->query($sql);
		if ($row = $result->fetch_assoc() )  {
			$loginSW = $row['log'];
		}
		return $loginSW; // 1: login & 유료회원 & 프리패스 , 0: not login
	}

	function getBidNo($conn,$openBidSeq,$last,$noRow) {
		$sql = "select bidNtceNo, count(*) cnt from ".$openBidSeq." where bidNtceNo >'".$last."' and LENGTH(bidNtceNo) = 11 group by bidNtceNo order by bidNtceNo limit 0, ".$noRow."";
		//select bidNtceNo, count(*) cnt from openBidSeq_2018 where bidNtceNo>'0' group by bidNtceNo order by bidNtceNo limit 0, 1000
		echo $sql;
		$result = $conn->query($sql);
		return $result;
	}

	function logWrite($id,$pg,$rmrk) {
		$conn = $this->conn();
		$pgd = urldecode($pg); //decodeURIComponent($pg);
		$sql  = "INSERT INTO logdb (pg,id,ip,rmrk) VALUES ('".$pgd."','".$id."','".$_SERVER['REMOTE_ADDR']."','".$rmrk."')";
		$conn->query($sql);
		$this->addlog($conn,$id);
	}
	function logWrite2($id,$pg,$rmrk,$pss,$key) {
		$conn = $this->conn();
		$pgd = urldecode($pg); //decodeURIComponent($pg);
		$sql  = "INSERT INTO logdb (pg,id,ip,rmrk,pgDtlCD,keyDtlCD) VALUES ('".$pgd."','".$id."','".$_SERVER['REMOTE_ADDR']."','".$rmrk."','".$pss."','".$key."')";
		$conn->query($sql);
		// $this->addlog($conn,$id);
	}
	function addlog($conn,$id) {
		if (trim($id) == '') return;
		$sql = "select * from PayUser02H where PayUser01M_user_login='".$id."'";
		//echo 'sql='.$sql;
		$result3=$conn->query($sql);
		if ($row = $result3->fetch_assoc() )  {
		} else {
			$sql = 'insert into PayUser02H (PayUser01M_user_login,payDate,payTypeCD,svrstrDT,svrendDT,svrserCnt,modifyDT) ';
			$sql .= " VALUES('".$id."',now(),'00',now(),now(),0,now() )";
			//echo 'sql='.$sql;
			$conn->query($sql);
		}

		$today = date('Y-m-d');
		$sql  = "update PayUser02H set svrserCnt = svrserCnt + 1 where PayUser01M_user_login = '".$id."' and substr(svrstrDT,1,10) <='".$today."' and substr(svrendDT,1,10)>='".$today."'" ;
		//echo $sql;
		$conn->query($sql);
	}
	
	// 한국기업데이터 기업정보 E017 ------------------------------- 
	// 최초 DB에서 조회 백그라운드에서 Replace 필요 함
	// REPLACE 있으면 업데이트 없으면 인서트 -by jsj 20190909
	//-----------------------------------------------------------
	// kedcd-관리고객번호,enp_nm-기업명,enp_nm_trd-기업형태 포함명,bzno-사업자등록번호,cono_pid-법인주민등록번호,eng_enp_nm-영문기업명
	// reper_nm-대표자명,enp_fcd-기업형태,ipo_cd-기업공개형태,estb_dt-설립일,acct_mm-결산월,group_nm-그룹,em_cnt-종업원수
	// bzc_cd-업종코드(표준산업분류코드),bzc_nm-업종명,zip-우편번호,addr1-주소,addr2-상세주소,tel_no-전화번호,fax_no-팩스번호
	// hpage_url-홈페이지,email-대표이메일,major_pd-주요상품,mtx_bnk_nm-주거래은행,enp_scd-기업상태,enp_scd_chg_dt-기업상태변경일
	// enp_sze-기업규모,std_dt-기업개요정보기준일,opnn_enp-종합의견_기업체개요,opnn_reper-종합의견_경영진,opnn_sales-종합의견_영업현황
	// delyn-삭제여부,modifydt-변경일
	function cmpE01701M_1($conn,$xml) {
		$kedcd = $xml->CONTENTS->E017->kedcd;
		//$xml2 = $xml->CONTENTS->E017;
		//echo "xml2->enp_nm_trd = ".$xml2->enp_nm_trd;
		//opnn_enp-종합의견_기업체개요,opnn_reper-종합의견_경영진,opnn_sales-종합의견_영업현황  ==> 특수문자 "'" 앞에 '\'추가
		$opnn_enp = str_replace("'","\'", $xml->CONTENTS->E017->opnn->opnn_enp);
		$opnn_reper = str_replace("'","\'", $xml->CONTENTS->E017->opnn->opnn_reper);
		$opnn_sales = str_replace("'","\'", $xml->CONTENTS->E017->opnn->opnn_sales);

		$sql = "REPLACE INTO cmpE01701M (kedcd,enp_nm,enp_nm_trd,bzno,cono_pid,eng_enp_nm,reper_nm,enp_fcd,ipo_cd, ";
		$sql .= "estb_dt, acct_mm, group_nm, em_cnt, bzc_cd, bzc_nm, zip, addr1, addr2, ";
		$sql .= "tel_no, fax_no, hpage_url, email, major_pd, mtx_bnk_nm, enp_scd, ";
		$sql .= "enp_scd_chg_dt, enp_sze, std_dt, opnn_enp, opnn_reper, opnn_sales, ";
		$sql .= "delyn, modifydt ) ";
		$sql .= " VALUES ('".$kedcd."', '".$xml->CONTENTS->E017->enp_nm."', '".$xml->CONTENTS->E017->enp_nm_trd."', '".$xml->CONTENTS->E017->bzno."', '".$xml->CONTENTS->E017->cono_pid."', '".$xml->CONTENTS->E017->eng_enp_nm."', '".$xml->CONTENTS->E017->reper_nm."', '".$xml->CONTENTS->E017->enp_fcd."', '".$xml->CONTENTS->E017->ipo_cd."', ";
		$sql .= " '".$xml->CONTENTS->E017->estb_dt."', '".$xml->CONTENTS->E017->acct_mm."', '".$xml->CONTENTS->E017->group_nm."', '".$xml->CONTENTS->E017->em_cnt."', '".$xml->CONTENTS->E017->bzc_cd."', '".$xml->CONTENTS->E017->bzc_nm."', '".$xml->CONTENTS->E017->zip."', '".$xml->CONTENTS->E017->addr1."', '".$xml->CONTENTS->E017->addr2."', ";
		$sql .= " '".$xml->CONTENTS->E017->tel_no."', '".$xml->CONTENTS->E017->fax_no."', '".$xml->CONTENTS->E017->hpage_url."', '".$xml->CONTENTS->E017->email."', '".$xml->CONTENTS->E017->major_pd."', '".$xml->CONTENTS->E017->mtx_bnk_nm."', '".$xml->CONTENTS->E017->enp_scd."', ";
		$sql .= " '".$xml->CONTENTS->E017->enp_scd_chg_dt."', '".$xml->CONTENTS->E017->enp_sze."', '".$xml->CONTENTS->E017->std_dt."', '".$opnn_enp."', '".$opnn_reper."', '".$opnn_sales."', ";
		$sql .= " 'N',now() )";
		
		//clog ("기업정보 sql" .$sql. '<br>');
		$conn->query($sql); 
	} 

	//  신용 정보 cmpCredit01M
	function cmpCredit01M_1($conn,$xml) {
		$xml2 = $xml->CONTENTS->E017;
		$kedcd = $xml->CONTENTS->E017->kedcd;
		
		$sql = "REPLACE INTO cmpCredit01M ";
		$sql .= "(kedcd, cr_grd, cr_grd_dtl, grd_cls, evl_dt, sttl_base_dt, kfb_bad_cnt, kfb_bad_regamt, ";
		$sql .= "kfb_bad_ovamt, kfb_fin_tx_cnt, workout_cnt, dshovd_cnt, bond_grd, bond_evl_dt, bond_grd_cls, ";
		$sql .= "bond_kcd , cp_grd, cp_evl_dt, 	cp_grd_cls, ir_grd, ir_evl_dt, ir_grd_cls, ";
		$sql .= "delyn, modifydt ) ";

		$sql .= " VALUES ('".$kedcd."', '".$xml2->cr_grd."', '".$xml2->cr_grd_dtl."', '".$xml2->grd_cls."', ";
		$sql .= "'".$xml2->evl_dt."', '".$xml2->sttl_base_dt."', '".$xml2->credit_info_cnt->kfb_bad_cnt."', '".$xml2->credit_info_cnt->kfb_bad_regamt."', ";
		$sql .= "'".$xml2->credit_info_cnt->kfb_bad_ovamt."', '".$xml2->credit_info_cnt->kfb_fin_tx_cnt."', '".$xml2->credit_info_cnt->workout_cnt."', '".$xml2->credit_info_cnt->dshovd_cnt."', ";
		$sql .= "'".$xml2->ext_grd->bond_grd."', '".$xml2->ext_grd->bond_evl_dt."', '".$xml2->ext_grd->bond_grd_cls."', ";
		$sql .= "'".$xml2->ext_grd->bond_kcd."', '".$xml2->ext_grd->cp_grd."', '".$xml2->ext_grd->cp_evl_dt."', '".$xml2->ext_grd->cp_grd_cls."', ";
		$sql .= "'".$xml2->ext_grd->ir_grd."', '".$xml2->ext_grd->ir_evl_dt."', '".$xml2->ext_grd->ir_grd_cls."', ";
		$sql .= " 'N',now() )";
		//clog "<br>신용 정보 cmpCredit01M_1 sql2 = ".$sql."<br>";
		$conn->query($sql); 
	}

	//  주주 현황 cmpE01702D
	function cmpE01702D_1($conn,$xml) {
		$kedcd = $xml->CONTENTS->E017->kedcd;
		$xml2 = $xml->CONTENTS->E017;
		$sql = "REPLACE INTO cmpE01702D ";
		$sql .= " (kedcd, sth_nm1, sth_eqrt1, sth_nm2, sth_eqrt2, sth_nm3, sth_eqrt3, renp_nm1, ";
		$sql .= " renp_eqrt1, renp_nme2, renp_eqrt2, renp_nm3, renp_eqrt3, 	customer_nm1, customer_rt1, ";
		$sql .= " customer_nm2 , customer_rt2, customer_nm3, custormer_rt3, supplier_nm1, supplier_rt1, ";
		$sql .= " supplier_nme2, supplier_rt2, supplier_nm3, supplier_rt3, ";
		$sql .= " delyn, modifydt ) ";

		$sql .= " VALUES ('".$kedcd."', '".$xml2->sth->sth_nm1."', '".$xml2->sth->sth_eqrt1."', '".$xml2->sth->sth_nm2."', ";
		$sql .= "'".$xml2->sth->sth_eqrt2."', '".$xml2->sth->sth_nm3."', '".$xml2->sth->sth_eqrt3."', ";
		$sql .= "'".$xml2->renp->renp_nm1."', '".$xml2->renp->renp_eqrt1."', '".$xml2->renp->renp_nme2."', '".$xml2->renp->renp_eqrt2."', ";
		$sql .= "'".$xml2->renp->renp_nm3."', '".$xml2->renp->renp_eqrt3."', '".$xml2->customer->customer_nm1."', '".$xml2->customer->customer_rt1."', ";
		$sql .= "'".$xml2->customer->customer_nm2."', '".$xml2->customer->customer_rt2."', '".$xml2->customer->customer_nm3."', '".$xml2->customer->customer_rt3."', ";
		$sql .= "'".$xml2->supplier->supplier_nm1."', '".$xml2->supplier->supplier_rt1."', '".$xml2->supplier->supplier_nm2."', ";
		$sql .= "'".$xml2->supplier->supplier_rt2."', '".$xml2->supplier->supplier_nm3."', '".$xml2->supplier->supplier_rt3."', ";
		$sql .= " 'N',now() )";
		//echo "<br>sql2 = ".$sql."<br>";
		$conn->query($sql); 
	}

	//  결산 내역 cmpFrsum01M
	function cmpFrsum01M_1($conn,$xml) {
		$this->cmpFrsum01M($conn,$xml);
		$this->cmpFrsum01M1($conn,$xml);
		$this->cmpFrsum01M2($conn,$xml);
	}
	function cmpFrsum01M($conn,$xml) {
		$kedcd = $xml->CONTENTS->E017->kedcd;
		$xml2 = $xml->CONTENTS->E017->fr_summ;
		$sql = "REPLACE INTO cmpFrsum01M ";
		$sql .= "(kedcd, fr_acct_dt, fr_val1, fr_val2, fr_val3, fr_val4, fr_val5, fr_val6, ";
		$sql .= "fr_val7, fr_val8, fr_val9, fr_val10, fr_val11, fr_val12,  ";
		$sql .= "delyn, modifydt ) ";

		$sql .= " VALUES ('".$kedcd."', '".$xml2->fr_acct_dt."', '".$xml2->fr_val1."', '".$xml2->fr_val2."', ";
		$sql .= "'".$xml2->fr_val3."', '".$xml2->fr_val4."', '".$xml2->fr_val5."', '".$xml2->fr_val6."', ";
		$sql .= "'".$xml2->fr_val7."', '".$xml2->fr_val8."', '".$xml2->fr_val9."', '".$xml2->fr_val10."', ";
		$sql .= "'".$xml2->fr_val11."', '".$xml2->fr_val12."', ";
		$sql .= " 'N',now() )";
		//echo "<br>sql2 = ".$sql."<br>";
		$conn->query($sql); 
	}

	function cmpFrsum01M1($conn,$xml) {
		$kedcd = $xml->CONTENTS->E017->kedcd;
		$xml2 = $xml->CONTENTS->E017->fr_summ;
		$sql = "REPLACE INTO cmpFrsum01M ";
		$sql .= "(kedcd, fr_acct_dt, fr_val1, fr_val2, fr_val3, fr_val4, fr_val5, fr_val6, ";
		$sql .= "fr_val7, fr_val8, fr_val9, fr_val10, fr_val11, fr_val12,  ";
		$sql .= " modifydt ) ";

		$sql .= " VALUES ('".$kedcd."', '".$xml2->fr1_acct_dt."', '".$xml2->fr1_val1."', '".$xml2->fr1_val2."', ";
		$sql .= "'".$xml2->fr1_val3."', '".$xml2->fr1_val4."', '".$xml2->fr1_val5."', '".$xml2->fr1_val6."', ";
		$sql .= "'".$xml2->fr1_val7."', '".$xml2->fr1_val8."', '".$xml2->fr1_val9."', '".$xml2->fr1_val10."', ";
		$sql .= "'".$xml2->fr1_val11."', '".$xml2->fr1_val12."', ";
		$sql .= " now() )";
		//echo "<br>sql2 = ".$sql."<br>";
		$conn->query($sql); 
	}

	function cmpFrsum01M2($conn,$xml) {
		$kedcd = $xml->CONTENTS->E017->kedcd;
		$xml2 = $xml->CONTENTS->E017->fr_summ;
		$sql = "REPLACE INTO cmpFrsum01M ";
		$sql .= "(kedcd, fr_acct_dt, fr_val1, fr_val2, fr_val3, fr_val4, fr_val5, fr_val6, ";
		$sql .= "fr_val7, fr_val8, fr_val9, fr_val10, fr_val11, fr_val12,  ";
		$sql .= " modifydt ) ";

		$sql .= " VALUES ('".$kedcd."', '".$xml2->fr2_acct_dt."', '".$xml2->fr2_val1."', '".$xml2->fr2_val2."', ";
		$sql .= "'".$xml2->fr2_val3."', '".$xml2->fr2_val4."', '".$xml2->fr2_val5."', '".$xml2->fr2_val6."', ";
		$sql .= "'".$xml2->fr2_val7."', '".$xml2->fr2_val8."', '".$xml2->fr2_val9."', '".$xml2->fr2_val10."', ";
		$sql .= "'".$xml2->fr2_val11."', '".$xml2->fr2_val12."', ";
		$sql .= " now() )";
		//echo "<br>sql2 = ".$sql."<br>";
		$conn->query($sql); 
	}

	// 결산 내역 cmpFrsum01M
	function cmpFssum01M_1($conn,$xml) {
		$this->cmpFssum01M($conn,$xml);
		$this->cmpFssum01M1($conn,$xml);
		$this->cmpFssum01M2($conn,$xml);
	}
	function cmpFssum01M($conn,$xml) {
		$kedcd = $xml->CONTENTS->E017->kedcd;
		$xml2 = $xml->CONTENTS->E017->fs_summ;
		$sql = "REPLACE INTO cmpFssum01M ";
		$sql .= "(kedcd, fs_acct_dt, fs_val1, fs_val2, fs_val3, fs_val4, fs_val5, fs_val6, ";
		$sql .= "fs_val7, fs_val8, fs_val9, fs_val10, fs_val11, fs_val12, fs_val13,fs_val14,fs_val15,fs_val16, ";
		$sql .= " modifydt ) ";

		$sql .= " VALUES ('".$kedcd."', '".$xml2->fs_acct_dt."', '".$xml2->fs_val1."', '".$xml2->fs_val2."', ";
		$sql .= "'".$xml2->fs_val3."', '".$xml2->fs_val4."', '".$xml2->fs_val5."', '".$xml2->fs_val6."', ";
		$sql .= "'".$xml2->fs_val7."', '".$xml2->fs_val8."', '".$xml2->fs_val9."', '".$xml2->fs_val10."', ";
		$sql .= "'".$xml2->fs_val11."', '".$xml2->fs_val12."', ";
		$sql .= "'".$xml2->fs_val13."', '".$xml2->fs_val14."', ";
		$sql .= "'".$xml2->fs_val15."', '".$xml2->fs_val16."', ";
		$sql .= " now() )";
		//echo "<br>sql2 = ".$sql."<br>";
		$conn->query($sql); 
	}

	function cmpFssum01M1($conn,$xml) {
		$kedcd = $xml->CONTENTS->E017->kedcd;
		$xml2 = $xml->CONTENTS->E017->fs_summ;
		$sql = "REPLACE INTO cmpFssum01M ";
		$sql .= "(kedcd, fs_acct_dt, fs_val1, fs_val2, fs_val3, fs_val4, fs_val5, fs_val6, ";
		$sql .= "fs_val7, fs_val8, fs_val9, fs_val10, fs_val11, fs_val12,  fs_val13,fs_val14,fs_val15,fs_val16, ";
		$sql .= " modifydt ) ";

		$sql .= " VALUES ('".$kedcd."', '".$xml2->fs1_acct_dt."', '".$xml2->fs1_val1."', '".$xml2->fs1_val2."', ";
		$sql .= "'".$xml2->fs1_val3."', '".$xml2->fs1_val4."', '".$xml2->fs1_val5."', '".$xml2->fs1_val6."', ";
		$sql .= "'".$xml2->fs1_val7."', '".$xml2->fs1_val8."', '".$xml2->fs1_val9."', '".$xml2->fs1_val10."', ";
		$sql .= "'".$xml2->fs1_val11."', '".$xml2->fs1_val12."', ";
		$sql .= "'".$xml2->fs1_val13."', '".$xml2->fs1_val14."', ";
		$sql .= "'".$xml2->fs1_val15."', '".$xml2->fs1_val16."', ";
		$sql .= " now() )";
		//echo "<br>sql2 = ".$sql."<br>";
		$conn->query($sql); 
	}
	
	function cmpFssum01M2($conn,$xml) {
		$kedcd = $xml->CONTENTS->E017->kedcd;
		$xml2 = $xml->CONTENTS->E017->fs_summ;
		$sql = "REPLACE INTO cmpFssum01M ";
		$sql .= "(kedcd, fs_acct_dt, fs_val1, fs_val2, fs_val3, fs_val4, fs_val5, fs_val6, ";
		$sql .= "fs_val7, fs_val8, fs_val9, fs_val10, fs_val11, fs_val12, fs_val13,fs_val14,fs_val15,fs_val16, ";
		$sql .= " modifydt ) ";

		$sql .= " VALUES ('".$kedcd."', '".$xml2->fs2_acct_dt."', '".$xml2->fs2_val1."', '".$xml2->fs2_val2."', ";
		$sql .= "'".$xml2->fs2_val3."', '".$xml2->fs2_val4."', '".$xml2->fs2_val5."', '".$xml2->fs2_val6."', ";
		$sql .= "'".$xml2->fs2_val7."', '".$xml2->fs2_val8."', '".$xml2->fs2_val9."', '".$xml2->fs2_val10."', ";
		$sql .= "'".$xml2->fs2_val11."', '".$xml2->fs2_val12."', ";
		$sql .= "'".$xml2->fs2_val13."', '".$xml2->fs2_val14."', ";
		$sql .= "'".$xml2->fs2_val15."', '".$xml2->fs2_val16."', ";
		$sql .= " now() )";
		//echo "<br>sql2 = ".$sql."<br>";
		$conn->query($sql); 
	}

	// 요약현금흐름분석 cf_anal_summ -------------------------------
	function cmpCfsum01M_1($conn,$xml) { 
		$kedcd = $xml->CONTENTS->E017->kedcd;
		//echo "cf_anal_summ->cf_anal1 = ".$xml->CONTENTS->E017->cf_anal_summ->cf1_anal4;
		$xml2 = $xml->CONTENTS->E017->cf_anal_summ;
		$this->cmpCfsum01M($conn,$kedcd,$xml2);
		$this->cmpCfsum01M1($conn,$kedcd,$xml2);
		$this->cmpCfsum01M2($conn,$kedcd,$xml2);
	}

	function cmpCfsum01M($conn,$kedcd,$xml2) {
		$sql = "REPLACE INTO cmpCfsum01M (kedcd,cf_acct_dt,cf_anal1,cf_anal2,cf_anal3,cf_anal4,modifydt) ";
		$sql .= "VALUES ('".$kedcd."', '".$xml2->cf_acct_dt."', ";
		$sql .= " '".$xml2->cf_anal1."', '".$xml2->cf_anal2."',";
		$sql .= " '".$xml2->cf_anal3."', '".$xml2->cf_anal4."', now() )";
		//echo "<br>sql2 = ".$sql."<br>";
		$conn->query($sql);
	}

	function cmpCfsum01M1($conn,$kedcd,$xml2) {
		$sql = "REPLACE INTO cmpCfsum01M (kedcd,cf_acct_dt,cf_anal1,cf_anal2,cf_anal3,cf_anal4,modifydt)";
		$sql .= " VALUES ('".$kedcd."', '".$xml2->cf1_acct_dt."', ";
		$sql .= " '".$xml2->cf1_anal1."', '".$xml2->cf1_anal2."', ";
		$sql .= " '".$xml2->cf1_anal3."', '".$xml2->cf1_anal4."', now() )";
		//echo "<br>sql2 = ".$sql."<br>";
		$conn->query($sql);
	}

	function cmpCfsum01M2($conn,$kedcd,$xml2) {
		$sql = "REPLACE INTO cmpCfsum01M (kedcd,cf_acct_dt,cf_anal1,cf_anal2,cf_anal3,cf_anal4,modifydt) ";
		$sql .= " VALUES ('".$kedcd."', '".$xml2->cf2_acct_dt."', ";
		$sql .= " '".$xml2->cf2_anal1."', '".$xml2->cf2_anal2."', ";
		$sql .= " '".$xml2->cf2_anal3."', '".$xml2->cf2_anal4."', now() )";
		//echo "<br>sql2 = ".$sql."<br>";
		$conn->query($sql);
	}

	//기업데이터 코드명 찾기 -by jsj 20190912
	function KedCdNmSearch($conn, $codeEle = '%', $CodeCd = '%'){
		$SQL = " SELECT codeEle, codeEleNm, codeCd, codeCdNm FROM cmpKedCd";
		//$SQL = " SELECT codeEleNm, codeCdNm FROM cmpKedCd";
		$SQL .= " WHERE codeEle like ? AND CodeCd like ?";
		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($SQL);

		//echo($name);
		$stmt->bind_param('ss', $codeEle, $CodeCd);
		if ($stmt->execute()) {
			$fields = bindAll($stmt);
			$list = array();
			while (	$row = fetchRowAssoc($stmt,$fields)) {
				// User Class
				$code = new KedCd;
				$code->set_codeEle($row['codeEle']);
				$code->set_codeEleNm($row['codeEleNm']);
				$code->set_codeCd($row['codeCd']);
				$code->set_codeCdNm($row['codeCdNm']);
				array_push($list, $code);
			}
		} else {
			$list = 'errorStmt sql= '.$SQL.'<br>';
		}
		$stmt->close();
		$conn->close();
		//echo $list[0]->_codeEleNm . ", " .$list[0]->_codeCdNm. "<br>";
		return $list;
	}

	// 코드집 ->코드명 가져오기 -by jsj 2020530
	// $codeBook: KedCdNmSearch 의 코드집 배열
	// $codeEle: 코드대분류 (ex. enp_scd)
	// $codeCd: 코드
	// return: 코드명
	function codeCdNm ($codeBook, $codeEle, $codeCd ){
		foreach ($codeBook as $i) {
			if ($codeBook[$i]->_codeEle == $codeEle && $codeBook[$i]->_codeCd == $codeCd) {
				break;
			}
			$i++;
		}
		return $codeBook[$i]->_codeCdNm. "<br>";
	} 
	
} //class dbconn

/*
 *  prepare statement 사용을 위해
 *  $fields = bindAll($stmt);
 *  $ row = $ stmt-> get_result (); ==> $row = fetchRowAssoc($stmt, $fields);
 */
function bindAll($stmt) {
	$meta = $stmt->result_metadata();
	$fields = array();
	$fieldRefs = array();
	while ($field = $meta->fetch_field())
	{
		$fields[$field->name] = "";
		$fieldRefs[] = &$fields[$field->name];
	}
	call_user_func_array(array($stmt, 'bind_result'), $fieldRefs);
	$stmt->store_result();
	//var_dump($fields);
	return $fields;
}

function fetchRowAssoc($stmt, &$fields) {
	if ($stmt->fetch()) {
		return $fields;
	}
	return false;
}

function clog($data){
    echo "<script>console.log( 'PHP_Console: " . $data . "' );</script>";
}

//기업데이터 코드명 클래스
class KedCd {
	public $_codeEle;
	public $_codeEleNm;
	public $_codeCd;
	public $_codeCdNm;

	/**
	 * Get the value of _codeEle
	 */ 
	public function get_codeEle()
	{
		return $this->_codeEle;
	}

	/**
	 * Set the value of _codeEle
	 *
	 * @return  self
	 */ 
	public function set_codeEle($_codeEle)
	{
		$this->_codeEle = $_codeEle;

		return $this;
	}

	/**
	 * Get the value of _codeEleNm
	 */ 
	public function get_codeEleNm()
	{
		return $this->_codeEleNm;
	}

	/**
	 * Set the value of _codeEleNm
	 *
	 * @return  self
	 */ 
	public function set_codeEleNm($_codeEleNm)
	{
		$this->_codeEleNm = $_codeEleNm;

		return $this;
	}

	/**
	 * Get the value of _codeCd
	 */ 
	public function get_codeCd()
	{
		return $this->_codeCd;
	}

	/**
	 * Set the value of _codeCd
	 *
	 * @return  self
	 */ 
	public function set_codeCd($_codeCd)
	{
		$this->_codeCd = $_codeCd;

		return $this;
	}

	/**
	 * Get the value of _codeCdNm
	 */ 
	public function get_codeCdNm()
	{
		return $this->_codeCdNm;
	}

	/**
	 * Set the value of _CodeCdNm
	 *
	 * @return  self
	 */ 
	public function set_codeCdNm($_codeCdNm)
	{
		$this->_codeCdNm = $_codeCdNm;

		return $this;
	}

} //class kedCd 코드집