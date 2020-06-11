<?

################################################################################
// 문자열 끊기 (이상의 길이일때는 ... 로 표시)
################################################################################
function cut_str($str,$maxlen, $suffix = "..") {
	if($maxlen<=0) return $str;
	if(ereg("\[re\]",$str)) $len=$len+4;
   	if($maxlen >= strlen($str)) return $str;

	$klen = $maxlen - 1;
	while(ord($str[$klen]) & 0x80) $klen--;
	
	return substr($str, 0, $maxlen - (($maxlen + $klen + 1) % 2)).$suffix;
}
################################################################################
// 페이지 URL
################################################################################

function page_list($current_Page="1", $table_name="", $whereStr="", $list_num="12",$url="") {
	$sql = "select count(*) as cnt from $table_name $whereStr";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	$total_Rec = $rows[cnt];
	$total_Page = (int)($total_Rec / $list_num) ;
	if (($total_Rec % $list_num)>0) $total_Page ++;

	$start_Page=(int)(($current_Page-1)/10)*10+1;
	$end_Page=$start_Page+9;
	if ($end_Page>$total_Page) $end_Page=$total_Page;

	$lisk_str="";
	$i=$start_Page-1;
	if ($start_Page>10) $link_str .="<a href='$url"."1'><img src='/btn/btn_loc1.gif' border=0></a> <a href='$url$i'><img src='/btn/btn_loc2.gif' border=0></a>";
	for ($i=$start_Page;$i<=$end_Page;$i++) {
            if ($current_Page != $i) {
                $link_str .= "&nbsp;<a href='$url$i'>[$i]</a>";
            } else {
                $link_str .= "&nbsp;<font color=red><b>$i</b></font>";
            }
        }

	if ($total_Page>$end_Page) $link_str .="&nbsp;<a href='$url$i'><img src='/btn/btn_loc3.gif' border=0></a>&nbsp;<a href='$url$total_Page'><img src='/btn/btn_loc4.gif' border=0></a>";
	$link_str .="&nbsp;총 레코드=$total_Rec / 총 페이지=$total_Page";
	return $link_str;


}


?>