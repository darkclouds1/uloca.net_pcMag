<?php
/*
  JSON-to-HTML Table
  Version 1.0
  October 21, 2017

  Will Bontrager Software LLC
  https://www.willmaster.com/
  Copyright 2017 Will Bontrager Software LLC

  This software is provided "AS IS," without 
  any warranty of any kind, without even any 
  implied warranty such as merchantability 
  or fitness for a particular purpose.
  Will Bontrager Software LLC grants 
  you a royalty free license to use or 
  modify this software provided this 
  notice appears on all copies. 
*/

/* No customization required. */

$DisplayTable = isset($_POST['json']) and strlen(trim($_POST['json'])) ? true : false;
if( $DisplayTable )
{
  $LocationOfJSONfile = trim($_POST['json']);
  if( preg_match('!^/!',$LocationOfJSONfile) )
  {
    $LocationOfJSONfile = str_replace($_SERVER['DOCUMENT_ROOT'],'',$LocationOfJSONfile);
    $LocationOfJSONfile = "{$_SERVER['DOCUMENT_ROOT']}$LocationOfJSONfile";
  }
  $FileSize = file_exists($LocationOfJSONfile) ? filesize($LocationOfJSONfile) : false;
  $FirstLineIsHeader = isset($_POST['line1header']) ? true : false;
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quick JSON Display</title>
<style type="text/css">
html, body {
  font-size:100%; 
  font-family: verdana,arial,sans-serif;
  }

input[type="text"], input[type="submit"] {
  width:100%;
  font-size:1em;
  -moz-box-sizing:border-box; 
  -webkit-box-sizing:border-box; 
  box-sizing:border-box;
  }

input[type="text"] {
  border:1px solid #ccc;
  border-radius:3px; 
  padding:5px;
  }

th { font-size:80% line-height:110%; }
p, li, td { font-size:1em; line-height:120%; }
td { vertical-align:top;}
th { vertical-align:bottom; font-size:.8em; font-weight:bold; text-align:center; }
h1 { font-size:1.8em; line-height:100%; text-align:center; }
h2 { font-size:1.6em; }
h3 { font-size:1.4em; }
h4 { font-size:1.2em; }
h5 { font-size:1em; }
a { text-decoration:none; color:#1c5292; font-weight:bold; }
a, a img { border:none; outline:none; }
.content { position:relative; max-width:450px; margin:.5in auto; }
.tablediv { margin:.5in auto; display:table; }
.nowrap { white-space:nowrap; }
</style>
</head>
<body><div class="content"><div style="position:absolute; left:-5px; top:-10px;">
<a href="https://www.willmaster.com/">
<img src="https://www.willmaster.com/images/wmlogo_icon.gif" style="border:none; outline:none;">
</a>
</div>
<h1 style="margin:0 0 0 50px;">JSON-to-HTML Table</h1>

<div style="height:45px;"></div>

<?php if($DisplayTable): ?>
<?php if( ! $FileSize): ?>
<p><b>Sorry, but I'm unable to find file <?php echo($_POST['json']); ?>.</b></p>
<?php else: ?>

</div>
<div class="tablediv"><table border="1" cellpadding="7" cellspacing="0" style="border-collapse:collapse;">
<?php
$numCols = 0;
$columns = array();
$columntrack = array();
foreach( file($LocationOfJSONfile) as $line )
{
  $line = trim($line);
  $th = json_decode($line,true);
  if( ! is_array($th) ) { continue; }
  foreach( $th as $column => $text )
  {
    if( isset($columntrack[$column]) ) { continue; }
    $columntrack[$column] = count($columns);
    $columns[] = $column;
  }
}
$numCols = count($columns);
echo "\n<tr>";
foreach( $columns as $col ) { echo "<th>$col</th>"; }
echo "\n</tr>";
foreach( file($LocationOfJSONfile) as $line )
{
  $cols = array();
  for( $i=0; $i<$numCols; $i++ ) { $cols[$i] =''; }
  $line = trim($line);
  $th = json_decode($line,true);
  if( ! is_array($th) ) { continue; }
  foreach( $th as $column => $text )
  {
    echo "\n<tr>";
    $cols[$columntrack[$column]] = $text;
    echo "\n</tr>";
  }
  foreach( $cols as $col ) { echo "<td>$col</td>"; }
}
?>
</table></div><div class="content">
<p style="margin-top:45px;">
Display another?
</p>
<?php endif; ?>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" action="<?php echo(htmlspecialchars($_SERVER['PHP_SELF'])); ?>">
<p>
Server location of JSON file to display.<br>
<input type="text" name="json">
</p>
<p>
<input type="submit" value="Convert JSON to HTML Table">
</p>
</form>
<p style="margin-top:45px;">
Copyright 2017 <a href="https://www.willmaster.com/"><span class="nowrap">Will Bontrager</span> <span class="nowrap">Software LLC</span></a>
</p>
</div>
</body>
</html>
