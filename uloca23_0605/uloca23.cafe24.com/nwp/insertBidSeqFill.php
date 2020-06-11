<?

// 낙찰SEQ 업뎃::공고번호, 공고차수로 낙찰 API call -by jsj 20200328
// 입찰정보에 1순위 및 낙찰_seq 두테이블에 모두 업뎃
// $openBidSeq = 테이블네임_년도 포함

@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); 
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');

$g2bClass = new g2bClass;
$dbConn   = new dbConn;
$conn     = $dbConn->conn();

// url = '/insertBidSeqFill.php?bidNtceNo=' + bidNtceNo + '&bidNtceNo=' + bidNtceNo +'&openBidInfo=' + openBidInfo + '&openBidSeq=' + openBidSeq_tmp + '&bididx=' + bididx + '&pss=' + pss;
$bidNtceNo   = $_GET['bidNtceNo'];
$bidNtceOrd  = $_GET['bidNtceOrd'];      
$bidIdx      = $_GET['bidIdx'];

// -----------------------------------------------
//예정가격, 기초금액 업데이트
// -----------------------------------------------
$idx++; //openbidSeq max(id)
updateInfo($conn, $g2bClass, $bidNtceNo, $pss);

//------------------------------------------------
// 개찰목록 업데이트
//------------------------------------------------
$openBidSeqCnt = openBidSeq_Update($g2bClass, $conn, $bidNtceNo, $bidNtceOrd);
echo $opeBidSeqCnt;

function updateInfo($conn, $g2bClass, $bidNtceNo, $pss)
{
	if      ($pss == '입찰물품') $bidrdo = 'opnbidThng';   //$bsnsDivCd = '1'; // 물품
	else if ($pss == '입찰공사') $bidrdo = 'opnbidCnstwk'; //$bsnsDivCd = '1'; // 공사
	else if ($pss == '입찰용역') $bidrdo = 'opnbidservc';  //$bsnsDivCd = '1'; // 용역

	$itemr = $g2bClass->getSvrDataOpn($bidrdo, $bidNtceNo); //tot_getSvrDataOpnStd($startDate,$endDate,$numOfRows,$pageNo,$bsnsDivCd);
	$json1 = json_decode($itemr, true);
	//$iRowCnt = $json1['response']['body']['totalCount'];
	$items = $json1['response']['body']['items'];
	if (count($items) > 0) {
		foreach ($items as $arr) {
			// 예정가격, 기초금액 업데이트
            $bidNtceNo1 = updateopenBidInfo($conn, "", "", $arr, $bidNtceNo);
            $msg .= "ln47:: 기초금액업데이트=" .$bidNtceNo1;
		}
	}
}

//------------------------------------
//예정가격, 기초금액 업데이트 -jsj 190507
//------------------------------------
function updateopenBidInfo($conn, $openBidInfo, $idx, $arr, $bidNtceNo)
{
	//echo '<br>'.$bidNtceNo .'/'. $arr['bidNtceNo'];
	//if ($bidNtceNo == $arr['bidNtceNo']) return $bidNtceNo;
	//$bidNtceNo = $arr['bidNtceNo'];
	$sql  = " UPDATE openBidInfo SET rsrvtnPrce = '" . $arr['plnprc'] . "', ";
	$sql .= "                        bssAmt     = '" . $arr['bssamt'] . "', ";
	$sql .= "                        ModifyDT   = now() ";
    $sql .= "  WHERE bidNtceNo  ='" .$arr['bidNtceNo'].  "' ";
    $sql .= "    AND bidNtceOrd ='" .$arr['bidNtceOrd']. "' ";
    
	//echo $sql.'<br>';
	$conn->query($sql);
	return $arr['$bidNtceNo']; // update
}


function openBidSeq_Update($g2bClass, $conn, $bidNtceNo, $bidNtceOrd)
{
    // 낙찰현황 조회 getRsltData (max.999)
    // (ex.getRsltDateAll-> 999개 이상)
    $item1 = $g2bClass->getRsltDataAll($bidNtceNo, $bidNtceOrd);
    //$json1 = json_decode($response1, true);
    //$item1 = $json1['response']['body']['items'];
    $cnt = count($item1);

    $i = 0; // 업체수
    $k = 1; // 순위 저장용
    if ($cnt == 0) return false;
    // echo "ln258 낙찰현황cnt=" .$cnt. " bidNtceNo=" .$bidNtceNo. " bidNtceOrd=" .$bidNtceOrd. "<br>"; exit;

    foreach ($item1 as $arr) { //foreach element in $arr
        //---------------------------
        $rmrk = addslashes($arr['rmrk']); // '낙찰하한선 미달' 등
        $k = (int) $arr["opengRank"];
        switch ($k) {
            case 0:
                $Rank_rmark = $rmrk;       // 순위가 아닌경우
                break;
            default:
                $Rank_rmark = (string) $k; // 순위 대입
                break;
        }

        // 속도문제 고려, 입력 순위 조정 (1순위 필수)
        // if ((int) $arr['opengRank'] == 0) continue;  // 순위 없음					
        // if ($k > 1) break;                           // 1순위만 입력

        // if ((int)$arr['opengRank'] >  999 ) continue;  // 1순위
        // openBidInfo 에 1순위 정보 업데이트 (공고차수 전체) -by jsj 190601
        if ((int) $arr['opengRank'] == 1) {
            $sql = " UPDATE openBidInfo SET ";
            $sql .= "       prtcptCnum =    '" .$cnt.                         "', ";        // 참가업체수
            $sql .= "       bidwinnrNm =    '" .addslashes($arr['prcbdrNm']). "', ";        // 최종낙찰업체명
            $sql .= "       bidwinnrBizno = '" .$arr['prcbdrBizno'].          "', ";        // 사업자번호
            $sql .= "       bidwinnrCeoNm = '" .$arr['bidwinnrCeoNm'].        "', ";        // 대표자명
            $sql .= "       sucsfbidAmt =   '" .$arr['sucsfbidAmt'].          "', ";        // 최종낙찰금액
            $sql .= "       sucsfbidRate =  '" .$arr['bidprcrt'].             "', ";        // 투찰율 = 투찰금액/예정가격
            $sql .= "       bidwinnrTelNo = '" .$arr['bidwinnrTelNo'].        "', ";        // 업체연락처
            $sql .= "       rlOpengDt =     '" .$arr['rlOpengDt'].            "', ";        // 실개찰일시
            $sql .= "       progrsDivCdNm = '" ."개찰완료".                    "', ";        // 개찰완료		
            $sql .= "       modifyDT = now()";
            $sql .= " WHERE bidNtceNo=      '" .$bidNtceNo. "' ";
            $sql .= "   AND bidNtceOrd=     '" .$bidNtceOrd. "' ";
            if (!($conn->query($sql))) echo "Error $sql=" .$sql;
        }

        // 입찰공고의 idx를 낙찰정보에(bidindx) 업데이트
        $sql = " SELECT idx FROM openBidInfo WHERE bidNtceNo = '" . $bidNtceNo . "' AND bidNtceOrd = '" . $bidNtceOrd . "' ";
        if ($result0 = $conn->query($sql)) {
            $row = $result0->fetch_assoc();
            $bididx = $row['idx'];
        } else {
            echo "ln293 Error sql=" . $sql . "<br>";
            continue;
        }

		//---------------------------------------------------
        //$openBidSeq_xxxx 에 입찰이력 입력 -by jsj 190601
        //---------------------------------------------------
        $sql  = " REPLACE INTO openBidSeq_tmp ( bidNtceNo, bidNtceOrd, rbidNo, compno, tuchalamt, tuchalrate, selno, tuchaldatetime, remark, bidIdx )";
        $sql .= "  VALUES ( '" .$arr['bidNtceNo']. "','" .$arr['bidNtceOrd']. "','" .$arr['rbidNo'].   "','" .$arr['prcbdrBizno']. "','" .$arr['bidprcAmt']. "',";
        $sql .=            "'" .$arr['bidprcrt'].  "','" .$arr['drwtNo1']. "','"    .$arr['bidprcDt']. "','" .$Rank_rmark. "',"          .$bididx . ")";
        if (!($conn->query($sql))) echo "Error sql=" . $sql . "<br>";

        $i++;
    } //for eachpro
    return $i; // seq 갯수를 리턴
}


?>