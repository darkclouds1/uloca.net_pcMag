<?
@extract($_GET);
if ($bidno == '') $bidno = '20170108892';
$url = 'http://www.g2b.go.kr:8081/ep/invitation/publish/bidInfoDtl.do?bidno='.$bidno.'&bidseq=00&releaseYn=Y&taskClCd=5';

header("Location: ".$url." ", true, 301);
exit();
?>