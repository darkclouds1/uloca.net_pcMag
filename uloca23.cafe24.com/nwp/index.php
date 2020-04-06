<?
error_reporting(E_ALL);
ini_set("display_errors", 1);

@extract($_POST);
if ($pw == '1123') {
?>
<!DOCTYPE html>
<html>
<head>
<title>ULOCA 관리자 유지관리 메뉴</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="icon" href="icons8-menu-64.png" sizes="128x128">
<link rel="apple-touch-icon-precomposed" href="icons8-menu-64.png">

<style>
body {
  background: #2ecc71;
  font-size: 62.5%;
}

.container {
  padding: 2em;
}

/* GENERAL BUTTON STYLING */
button,
button::after {
  -webkit-transition: all 0.3s;
    -moz-transition: all 0.3s;
  -o-transition: all 0.3s;
    transition: all 0.3s;
}

button {
  background: none;
  border: 3px solid #fff;
  border-radius: 5px;
  color: #ffffff;
  display: block;
  font-size: 1.6em;
  font-weight: bold;
  margin: 1em auto;
  padding: 2em 6em;
  position: relative;
  text-transform: uppercase;
  cursor:pointer;
  width:450px;
}

button::before,
button::after {
  background: #fff;
  content: '';
  position: absolute;
  z-index: -1;
}

button:hover {
  color: #2ecc71;
}

/* BUTTON 1 */
.btn-1::after {
  height: 0;
  left: 0;
  top: 0;
  width: 100%;
}

.btn-1:hover:after {
  height: 100%;
}

/* BUTTON 2 */
.btn-2::after {
  height: 100%;
  left: 0;
  top: 0;
  width: 0;
}

.btn-2:hover:after {
  width: 100%;
}

/* BUTTON 3 */
.btn-3::after {
  height: 0;
  left: 50%;
  top: 50%;
  width: 0;
}

.btn-3:hover:after {
  height: 100%;
  left: 0;
  top: 0;
  width: 100%;
}

/* BUTTON 4 */
.btn-4::before {
  height: 100%;
  left: 0;
  top: 0;
  width: 100%;
}

.btn-4::after {
  background: #2ecc71;
  height: 100%;
  left: 0;
  top: 0;
  width: 100%;
}

.btn-4:hover:after {
  height: 0;
  left: 50%;
  top: 50%;
  width: 0;
}

/* BUTTON 5 */
.btn-5 {
  overflow: hidden;
}

.btn-5::after {
  /*background-color: #f00;*/
  height: 100%;
  left: -35%;
  top: 0;
  transform: skew(50deg);
  transition-duration: 0.6s;
  transform-origin: top left;
  width: 0;
}

.btn-5:hover:after {
  height: 100%;
  width: 135%;
}

</style>
<script type="text/javascript">
    function clickme(addr)
    {
        //document.location.href=addr;
		window.open(addr,'_blank');
    }
</script>
</head>
<body>
<p style='text-align:center; font-weight:bold; font-size:24px;'>ULOCA 관리자 유지관리 메뉴</p>
<div class="container" style='width:500px; position: fixed; top: 60px; left: 100px;'>
  <!-- button class="btn-1" onclick='clickme("http://uloca.net/ulocawp/?page_id=440")'>게시판</button -->
  <button class="btn-1" onclick='clickme("getBid12.php")'>응찰기록/면허제한 수집</button><!-- ' -->
  <button class="btn-2" onclick='clickme("http://uloca.net/ulocawp/?page_id=490")'>일일자료수집</button>
  <button class="btn-3" onclick='clickme("http://uloca.net/ulocawp/?page_id=364")'>자동받기 실행</button>
  <!-- button class="btn-4" onclick='clickme("완료 getForecast.php")'>예측Data 수집 완료</button -->
  <button class="btn-4" onclick='clickme("ip-id.php")'>ip-id</button>
  <button class="btn-5" onclick='clickme("statistics1.php")'>통계자료</button>
  
</div>
<div class="container" style='width:500px; position: fixed; top: 60px; right: 100px;'>
  <button class="btn-1" onclick='clickme("dailyDataFill_1.php")'>날자로 입찰정보+낙찰정보 보완</button><!-- onclick='clickme("dailyDataFill.php")' -->
  <!-- dailyDataFill.php 는 해당일자의 공공번호와 낙찰공고번호와의 매치가 안되고 각가 불러옴.  -->
  <button class="btn-2" onclick='clickme("dailyDataFill2.php")'>기간,갯수로 입찰정보+낙찰정보 보완</a></button><!--  onclick='clickme("dailyDataFill2.php")' -->
  <button class="btn-3">개찰정보 보완</button><!--  onclick='clickme("dailySeqFill.php")' -->
  <button class="btn-4" onclick='clickme("logviewer.php")'>로그 보기</button>
  <button class="btn-4" onclick='clickme("execsql.php")'>utility</button>
</div>
</body>
</html>


<?
} else {
?>


<!DOCTYPE html>
<html>
<head>
<title>ULOCA 관리자 유지관리 메뉴</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="icon" href="icons8-menu-64.png" sizes="128x128">
<link rel="apple-touch-icon-precomposed" href="icons8-menu-64.png">
<style>
.search {width:80px; height:30px; display:inline-block; font-size:14px; line-height:30px; color:#fff; text-align:center; background-color:#438ad1; border:0; cursor:pointer;}
</style>
<script>

</script>
</head>
<form action="index.php" name="myForm" id="myForm" method="post" >
ULOCA 비밀번호  <input type=password name=pw id=pw value='' autofocus />
<a onclick="document.getElementById('myForm').submit();" class="search">인증</a>
</form>
</body>
</html>
<?
}
?>
