<?

@extract($_GET);
@extract($_POST);
ob_start();
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();

echo " startDate=" . $_POST['startDate'] . ", enddate=" . $_POST['endDate'] . ", contn=" . $_POST['contn'] . ", stop=" . $_POST['stop'] . "<br>";

$dur = '2020';
if ($_POST['startDate'] != '') {
    $dur = substr($startDate, 0, 4);
}

$openBidInfo = 'openBidInfo'; //.$dur;
$openBidSeq = 'openBidSeq' . '_' . $dur;

if (isset($_POST['contn'])) {
    $contn = 1;    //on
} else {
    $contn = 0;
}
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
if ($startDate == '') {
    $sql = "select workdt, lastdt from workdate where workname = 'dailyDataFill'";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()) {
        $workdt = $row['workdt'];
        $startDate = $row['lastdt'];
        $endDate = $startDate;
    }
}

echo " startDate= " .$startDate. ", ";
echo " endDate= " .$endDate;

$workname = 'dailyDataFill';
$workdt = date('Y-m-d', strtotime(date("Ymd")));
$endDate = date('Y-m-d', strtotime($endDate));
echo " 저장할 endDate=" .$endDate;
workdate($conn, $workname, $workdt, $endDate);

?>

<!DOCTYPE html>
<html>

<head>
    <title>ULOCA</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/ulocawp/wp-content/themes/one-edge/style.css" />
    <link rel="stylesheet" type="text/css" href="/dhtml/codebase/fonts/font_roboto/roboto.css" />
    <link rel="stylesheet" type="text/css" href="/dhtml/codebase/dhtmlx.css" />
    <link rel="stylesheet" type="text/css" href="http://uloca23.cafe24.com/g2b/css/g2b.css" />
    <link rel="stylesheet" href="http://uloca23.cafe24.com/jquery/jquery-ui.css">
    <script src="http://uloca23.cafe24.com/jquery/jquery.min.js"></script>
    <script src="http://uloca23.cafe24.com/jquery/jquery-ui.min.js"></script>
    <script src="http://uloca23.cafe24.com/g2b/g2b.js"></script>
    <script src="/dhtml/codebase/dhtmlx.js"></script>

    <script>
		var stopOn = false;

        function doit() {
            move();
            frm = document.myForm;
            alert ("Start submit");
            frm.submit();
        }

        function stopit() {
            var contn = document.getElementById("contn");
            contn.checked = false;
            stopOn = true;
            alert("stop! = ");
        }

        function donextday() {
            frm = document.myForm;
            if (frm.startDate.value > '<?= $endDate ?>') {
                return;
            }
            //오늘날짜 이후는 무의미
            if (frm.startDate.value >= '<?= $workdt ?>') {
                return
            }

            // stopOn 
            if (stopOn) {
                frm.contn.checked = false;
                return;
            }
            if (frm.contn.checked && !stopOn) {
                dts = dateAddDel(frm.startDate.value, 1, 'd');
                frm.startDate.value = dts;
                frm.endDate.value = dts;
                setTimeout(function() {
                    if (!stopOn) {
                        move();
                        frm.submit();
                    }
                }, 3000);


            }
            return;
        }
    </script>

<body onload="javascript:donextday();">
    <form action="tst_frmSubmit.php" name="myForm" id="myform" method="post">
    <input type="hidden" name="contnVal" id="contnVal" value="<?php $contn?>">
        <div id="contents">
            <div class="detail_search">
                <table align=center cellpadding="0" cellspacing="0" width="700px">
                    <colgroup>
                        <col style="width:20%;" />
                        <col style="width:auto;" />
                    </colgroup>
                    <tbody>
                        <tr>
                            <th>기간</th>
                            <td>
                                <div class="calendar">
                                    <input autocomplete="off" type="text" maxlength="10" name="startDate" id="startDate" value="<?= $endDate ?>" style="width:76px;" readonly='readonly' onchange='document.getElementById("endDate").value = this.value' />
                                    ~
                                    <input autocomplete="off" type="text" maxlength="10" name="endDate" id="endDate" value="<?= $endDate ?>" style="width:76px;" readonly='readonly' />

                                    1주일 이내-하루
                                    <input type="checkbox" name="contn" id="contn" <? if ($contn) { ?> checked=checked <? } ?>>계속

                                    <div id="datepicker"></div>
                                </div>
                            </td>
                        </tr>
                </table>
                <div class="btn_area">
                    <!-- input type="submit" class="search" value="검색" onclick="searchx()" /-->&nbsp;&nbsp;
                    <a onclick="doit();" class="search">실행</a>
                    <a onclick="stopit();" class="search">STOP</a>
                </div>
            </div>
        </div>
    </form>

    <div id='loading' style='display: none; position: fixed; width: 100px; height: 100px; top: 35%;left: 60%;margin-top: -10px; margin-left: -50px; '>
        <img src='http://uloca.net/g2b/loading3.gif' width='100px' height='100px'>
    </div>

    <center>
        <div style='font-size:14px; color:blue;font-weight:bold'>- 입찰정보 / 낙찰업데이트 1등정보 (입찰/낙찰 연관없음) <br>
            입찰정보(등록일시기준): DB에 없는것만 INSERT, 낙찰정보(개찰일시기준): DB에 공고번호로 업데이트 </div>
    </center>

    <table class="type10" id="specData" style="text-align: left; border: 0px solid #dddddd; word-break:break-all;">
        <thead>
            <tr>
                <th scope="cols" width="5%;">순위</th>
                <th scope="cols" width="12%;">공고번호</th>
                <th scope="cols" width="20%;">공고명</th>
                <th scope="cols" width="15%;">공고기관</th>
                <th scope="cols" width="12%;">수요기관</th>
                <th scope="cols" width="10%;">개찰일시</th>
                <th scope="cols" width="6%;">구분</th>
                <th scope="cols" width="6%;">공고Index</th>
                <th scope="cols" width="6%;">응찰건수</th>
                <th scope="cols" width="10%;">낙찰업체</th>

            </tr>
        </thead>
        <tbody>

            <?php

            function debug_flush($msg)
            {
                //ob_end_clean();
                echo $msg.'<br/>';
                echo str_pad('',256);
                ob_flush();
                flush();
            }

            function workdate($conn, $workname, $workdt, $endDate)
            {
                $sql = " UPDATE workdate SET workdt='" . $workdt . "', lastdt='" . $endDate . "'";
                $sql .= " Where workname='" . $workname . "' ";
                if ($conn->query($sql) <> true) {
                    echo "SQL Error: " . $sql;
                }
            }

            for($i=1; $i<4; ++$i) {
                debug_flush("실행완료".$i);
                sleep(1);
            }


            ?>