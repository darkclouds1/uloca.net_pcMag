<!DOCTYPE html>
<meta charset="utf-8" />
<?php

$now_date = $_POST["now_date"];
$text01 = $_POST["text01"];
$textarea1 = $_POST["textarea1"];

echo ('hello');
echo ($action);
echo ($now_date);
echo ($text01);
echo ($textarea1);
echo ($textarea2);

?>

<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8" />
</head>
<body>

<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
  <input type="date" name="now_date" id="now_date" value="<?php echo date('Y-m-d');?>" />	<br><br>
  <input type="text" name="text01" id="text01" />		<br><br>  
  <textarea name="textarea1"></textarea><br>
  <textarea name="textarea2"></textarea><br>

  <input type="submit" value="제출하기" /><br>
</form>
<div id='div1'>
  	<button onclick="toDaySet()">javascript test</button>
</div>
</body>
</html>