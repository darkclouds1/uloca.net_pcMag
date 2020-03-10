<?php 
$oldDataCheck = 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<title>PHP AJAX</title>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css" />
<link rel="stylesheet" href="/jquery/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="/js/common.js?version=20190203"></script>
<script src="/g2b/g2b.js?version=20190203"></script>
<script src="/g2b/g2b_2019.js?version=20190203"></script>
<script>
//----------------------------
//bitly -by jsj 190325
//----------------------------	
function shortURL(link) {
	document.getElementById("url").value;
	
	format = 'txt';
	login = 'enable21';
	apiKey = 'R_cb94c2cd92bba988984791acf7704b6e';
	bitlyUrl = 'https://api-ssl.bitly.com/v3/shorten?login='+login+'&apiKey='+apiKey+'&longUrl='+encodeURIComponent(link)+'&format='+format;
	//-------------------------------------
	return getAjax_bitly(bitlyUrl, callBack);
	//-------------------------------------
}

function getAjax_bitly(bitlyUrl,callBack) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
  	if (this.readyState == 4 && this.status == 200) {
	     	callBack(this.responseText);
		 	//return client(this.responseText);
  	} else if (this.status == 504) {
			//move_stop();
			alert('Bitly Time-out Error...');
	};}
	
	xhttp.open("GET", bitlyUrl, true);
	xhttp.send();
}
function callBack(data){
 	document.getElementById("url").value = data;
 	prompt('Ctrl+C를 눌러 아래의 URL을 복사하세요:', data);
}

function checkedbox(){
	var oldDataCheck = document.getElementsByName("oldDataCheck");
	alert (oldDataCheck[0].checked);
}
</script>
</head><!DOCTYPE html>

<body>
	<div id="target"> </div>
	<button type="button" width="300px" onclick="shortURL(document.location.href)">Get Bitly</button>
	<input  type="text" width="800px" id="url" class="inputbox" name="url" /><br />
	<button type="button" width="300px" onclick="checkedbox()">Checked box</button>
	<input type="checkbox" name="oldDataCheck" value="과거데이터수집" id="oldDataCheck" <?php if ($oldDataCheck) { echo 'checked';} ?>/>
</body>
