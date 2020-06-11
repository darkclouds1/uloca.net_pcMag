<?
@extract($_GET);
@extract($_POST);

// http://uloca23.cafe24.com/forecast/calcForecast.php?amt=10000&yega=3&gasu=16&pred=5
//echo "기초금액=".$amt." 예가범위=".$yega." 예가갯수=".$gasu." 추첨예가=".$pred;
if ($amt != "") {
	// 계산 로직
	$yegar = $yega * 2;
	$yegas = (100 - $yega) * 100;	// 난수 범위 시작
	$yega100 = 10000;		
	$yegae = (100 + $yega) * 100;	// 난수 범위 끝
	$gasu1 = ceil($gasu / 2 );
	$gasu2 = $gasu - $gasu1;
	$gasui=0;
	for ($i=0;$i<$gasu1;$i++) {
		$k = rand($yegas,$yega100);
		for ($ii = 0; $ii<$i;$ii++) {
			while ($yebi[$ii] == $k) {
				$k = rand($yegas,$yega100);
				//echo "<br>--ii=".$ii." p[i]=".$k;
			}
		}

		$yebi[$gasui] = $k;
		$gasui++;
	}
		
	for ($i=0;$i<$gasu2;$i++) {
		$k = rand($yega100,$yegae);
		for ($ii = 0; $ii<$i;$ii++) {
			while ($yebi[$ii] == $k) {
				$k = rand($yega100,$yegae);
				//echo "<br>--ii=".$ii." p[i]=".$k;
			}
		}

		$yebi[$gasui] = $k;
		$gasui++;
	}
		
	sort($yebi);

	//$json = '{"yebi" : [ ';
	$arr = '';
	//echo"<br>--예가--------------------------------------------";
	for ($i=0;$i<$gasu;$i++) {
		$arr .= $yebi[$i].",";
		//$yebi[$i] = $yebi[$i]/100;
		//$yebiga[$i] = round($amt * $yebi[$i] / 100);
		//echo "<br>i= ".$i." yebi=".$yebi[$i]." yebiga=".$yebiga[$i];
		//$json .= ' {"yebiyul": "' . $yebi[$i]. '","yebiga": "'.$yebiga[$i]. '"},' ;
	}
	$arr = substr($arr,0,strlen($arr)-1);
	$arr .= '/';
	//$json .= '"sel" : [ ';
	//echo"<br>--추첨예가--------------------------------------------";
	for ($i=0;$i<$pred;$i++) {
		$k = rand(0,$gasu-1);
		for ($ii = 0; $ii<$i;$ii++) {
			while ($p[$ii] == $k) {
				$k = rand(0,$gasu-1);
				//echo "<br>--ii=".$ii." p[i]=".$k;
			}
		}
		$p[$i] = $k;
		
		//echo "<br>k= ".$k." yebiga=".$yebiga[$k];
		$arr .= $k . ","; //' {"idx": "' . $k. '","yebiga": "'.$yebiga[$k]. '"},' ;
	}
	$arr = substr($arr,0,strlen($arr)-1);
	//$json .= '] }';

	//$data = json_encode($json);

	echo($arr);
}
/*
if ($yega == "") $yega = 3;
if ($gasu == "") $gasu = 15;
if ($pred == "") $pred = 4;
*/

?>