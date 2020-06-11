<%!

/* =============================================================================================================
	방문자 DB의 IP List 
=============================================================================================================== */

String [][] ipArray ; 
boolean ipArraySw = false;
int ipArrayCnt = 0;

void makeIPArray() throws ClassNotFoundException, SQLException,Exception {
	try {
		String sql = "select count(*) cnt from visitip ";
		ResultSet rs = getRecordSet( sql );
		rs.next();
		ipArrayCnt = rs.getInt("cnt");
		ipArray = new String[ipArrayCnt][3];
		sql = "select ip,loc from visitip order by loc ";
		rs = getRecordSet( sql );
		int i=0;
		while(rs.next()) {
			//ipArray[i]= new String[2];
			ipArray[i][0] = rs.getString("ip");
			ipArray[i][1] = rs.getString("loc");
			//out.print("i="+i+" "+ipArray[i][0]+" "+ipArray[i][1]);
			i++;
		}
		 
		
		for (i=0;i<ipArrayCnt;i++) {
			sql = "select dt from visit where ip like '"+ipArray[i][0]+"%' order by idx desc limit 0,1";
			ResultSet rs2 = getRecordSet( sql );
			if (rs2.next()) {
				ipArray[i][2] = rs2.getString("dt").substring(2,10);
			} else {
				ipArray[i][2] = "&nbsp;";
			}
		}
		
		ipArraySw = true;
	} catch (Exception e) {  }
}

String[] ip2Loc(String ip) throws  SQLException,Exception
{
  if (!ipArraySw) 
	  makeIPArray();
	String ips = ip.substring(0,8);
	for (int i=0;i<ipArrayCnt;i++) {
		if (ipArray[i][0].indexOf(ips)==0 ) {
			return ipArray[i];
		}
	}
	ResultSet rs = null;
	String[] ipr = new String[3]; // { "","<font color=red>없는주소</font>","0"};
	ipr[0] = ip;
	ipr[1] = "<font color=red>없는주소</font>";
	ipr[2] = "&nbsp;";
	 //String sql = "select dt from visit where ip = '"+ip+"' order by dt desc limit 0,1";
	 //ipr[2]=sql;
			//rs = getRecordSet( sql );
			//if (rs.next()) {
			//	ipr[2] = rs.getString("dt").substring(2,10);
			//} 
	
  return ipr;
}

/* =============================================================================================================
	로그인 체크
=============================================================================================================== */
boolean loginChk(String url) {
	/*
	String login = (String)session.getAttribute("login");
	if (login==null) {
		response.sendRedirect ("/login.jsp?url="+url);
	} else if (!login.equals("dbfhr") && !login.equals("6808")) {
		out.print(login+"은 권한이 없습니다.");
		//session.removeAttribute("id"); // id라는 세션 변수가 가지고 있는 값 삭제
		return false;
	} else return true;
	*/
	return true;
}

/* =============================================================================================================
	오늘 날자를 원하는 포맷으로 리턴 
=============================================================================================================== */
String today(String fmt) {
	if (fmt==null || fmt=="") fmt="yyyy-MM-dd";
	java.util.Calendar now = java.util.Calendar.getInstance();
	java.text.SimpleDateFormat sdt = new java.text.SimpleDateFormat(fmt); //"yyyy-MM-dd");
	return sdt.format(now.getTime());
}

/* =============================================================================================================
	Page List를 리턴 (현재페이지,테이블,SQL where 절,페이지당 line수,url)
=============================================================================================================== */
String page_list(int current_Page, String table_name, String whereStr, int list_num,String url) throws  SQLException,Exception {
// page_list($current_Page="1", $table_name="", $whereStr="", $list_num="12",$url="") {
	//if (current_Page == null) current_Page=1;
	if (whereStr == null) whereStr="";
	if (url == null) url="";
	if (url.indexOf('?')>0) {
		url += "&page=";
	} else {
		url += "?page=";
	}
	//if (!whereStr.equals("") ) whereStr = " where "+whereStr;
	try {
	String sql = "select count(*) as cnt from "+ table_name +" " + whereStr;
	ResultSet rs = getRecordSet( sql );
	rs.next();
	int total_Rec = rs.getInt("cnt");
	int total_Page = total_Rec / list_num;
	total_Page = (int)(total_Rec / list_num) ;
	if ((total_Rec % list_num)>0) total_Page ++;

	int start_Page=(int)((current_Page-1)/10)*10+1;
	int end_Page=start_Page+9;
	if (end_Page>total_Page) end_Page=total_Page;

	String link_str = "";
	int i=start_Page-1;
	if (start_Page>10) link_str +="<a href='"+url+"1'><img src='/btn/btn_loc1.gif' border=0></a> <a href='"+url+i+"'><img src='/btn/btn_loc2.gif' border=0></a>";
	for (i=start_Page;i<=end_Page;i++) {
            if (current_Page != i) {
                link_str += "&nbsp;<a href='"+url+i+"'>["+i+"]</a>";
            } else {
                link_str += "&nbsp;<font color=red size=2><b>"+i+"</b></font>";
            }
        }

	if (total_Page>end_Page) link_str +="&nbsp;<a href='"+url+i+"'><img src='../btn/btn_loc3.gif' border=0></a>&nbsp;<a href='"+url+total_Page+"'><img src='../btn/btn_loc4.gif' border=0></a>";
	link_str +="&nbsp;<font size=2>Total Record="+total_Rec+" / Total Page="+total_Page+"</font>";
	return link_str;

	} catch (SQLException e){
		//out.println(e);
		return "";
	}
}


///////////////////////////////////////////////////////////////////////////////
// 함수명 :format_phone()
// 내  용 : 전화번호 포멧을 자동변경한다.
// Event :
// Object : Input
///////////////////////////////////////////////////////////////////////////////
String format_phone(String str) {
	String[] rgnNo = new String[22];
	rgnNo[0] = "02";
	rgnNo[1] = "031";
	rgnNo[2] = "032";
	rgnNo[3] = "033";
	rgnNo[4] = "041";
	rgnNo[5] = "042";
	rgnNo[6] = "043";
	rgnNo[7] = "051";
	rgnNo[8] = "052";
	rgnNo[9] = "053";
	rgnNo[10] = "054";
	rgnNo[11] = "055";
	rgnNo[12] = "061";
	rgnNo[13] = "062";
	rgnNo[14] = "063";
	rgnNo[15] = "064";
	rgnNo[16] = "010";
	rgnNo[17] = "011";
	rgnNo[18] = "016";
	rgnNo[19] = "017";
	rgnNo[20] = "018";
	rgnNo[21] = "019";

	//String eliminateStr = /(\,|\.|\-|\/|\:|\s)/g;
	//str = str.replace(eliminateStr,"");
	str=str.replaceAll(" ", "");
	str=str.replaceAll("-", "");
	for (int i = 0; i < rgnNo.length; i++) {
		if (str.indexOf(rgnNo[i]) == 0) {
			int len_rgn = rgnNo[i].length();
			String formattedNo = getFormattedPhone(str.substring(len_rgn));
			return rgnNo[i] + "-" + formattedNo;
		}else if(str.length()==11){
			String formattedNo = getFormattedPhone(str.substring(3));
			return str.substring(0,3) + "-" + formattedNo;

		}else if(str.length()==12){
			String formattedNo = getFormattedPhone(str.substring(4));
			return str.substring(0,4) + "-" + formattedNo;
		}
	}

	if (str.length() > 8)
		return str;

	return getFormattedPhone(str);
}
///////////////////////////////////////////////////////////////////////////////
// 함수명 :getFormattedPhone()
// 내  용 : 전화번호 포멧을 자동변경한다.
// Event :
// Object : Input
///////////////////////////////////////////////////////////////////////////////
String getFormattedPhone(String str) {
	if (str.length()<=4) {
		return str;
	}
	else {
		int len_no1 = str.length() - 4;
		String no1 = str.substring(0, len_no1);
		String no2 = str.substring(len_no1);
		return no1 + "-" + no2;
	}
}


%>