<%!
/*
                      Class Name util 
 -------------------------------------------------------------------------------------------------------------
 *  Source Path		: /include/util.jsp 
 *  �ý��۸�		: 
 * �������߼Һз�	:
 * ���α׷�����		: utility ���̺귯��
 * ���ϸ�			: util.jsp
 * Called By		: All
 * Calling			:
 *  �ۼ���			: Ȳ����
 *  �ۼ���¥		: 2008. 11. 08
 * ----------------------------------------------------------------------
 * �������
 * ��������	    ������		    ����
 * -------		-----			------------------------------
 * 2008.11.08	Ȳ����			���� ����

--------���� ����-----------------------------------------------------------
	< %@ include file ="/include/util.jsp" % >
	out.print(sendRedirect("msg","index.jsp"));

--------function list-----------------------------------------------------------

*/

	// Ư�� URL�� �̵�
	String sendRedirect(String msg,String url)
	{
		String str="";
		if (msg.equals("") ) {
			str = "<script language='javascript'>location.href='"+url+"'</script>";
		} else {
			str = "<script language='javascript'>alert(\""+msg+"\")</script>";
			str += "<script language='javascript'>location.href='"+url+"'</script>";
		}
		return str;
	}	// end sendRedirect
	String sendTopRedirect(String msg,String url)
	{
		String str="";
		if (msg.equals("") ) {
			str = "<script language='javascript'>top.location.href='"+url+"'</script>";
		} else {
			str = "<script language='javascript'>alert(\""+msg+"\")</script>";
			str += "<script language='javascript'>top.location.href='"+url+"'</script>";
		}
		return str;
	}	// end sendTopRedirect

	// ALERT�� �̿��� �޽��� ���
	String msg(String msg)
	{
		String str="";
		str = "<script language='javascript'>alert(\""+msg+"\")</script>";
		return str;
	}


	// ���� �޽��� ���
	String errormsg(String msg)
	{
		String str="";
		str = "<script language=\"javascript\">";
		str += "alert(\""+msg+"\");";
		str += "	history.go(-1);";
		str += "</script>";
		return str;
	}

	/* =============================================================================================================
	���� ���ڸ� ���ϴ� �������� ���� 
	=============================================================================================================== */
String today(String fmt) {
	if (fmt==null || fmt=="") fmt="yyyy-MM-dd";
	java.util.Calendar now = java.util.Calendar.getInstance();
	java.text.SimpleDateFormat sdt = new java.text.SimpleDateFormat(fmt); //"yyyy-MM-dd");
	return sdt.format(now.getTime());
}

/* =============================================================================================================
	Page List�� ���� (����������,���̺�,SQL where ��,�������� line��,url)
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

	if (total_Page>end_Page) link_str +="&nbsp;<a href='"+url+i+"'><img src='/btn/btn_loc3.gif' border=0></a>&nbsp;<a href='"+url+total_Page+"'><img src='/btn/btn_loc4.gif' border=0></a>";
	link_str +="&nbsp;<font size=2>�� ���ڵ�="+total_Rec+" / �� ������="+total_Page+"</font>";
	return link_str;

	} catch (SQLException e){
		//out.println(e);
		return "";
	}
}

///////////////////////////////////////////////////////////////////////////////
// �Լ��� :format_phone()
// ��  �� : ��ȭ��ȣ ������ �ڵ������Ѵ�.
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
// �Լ��� :getFormattedPhone()
// ��  �� : ��ȭ��ȣ ������ �ڵ������Ѵ�.
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

	// �ֹι�ȣ�� ���̰��
	int calcAge(String sno) { 
		int sno1 = Integer.parseInt(sno.substring(6,7));
		int sno2 = Integer.parseInt(sno.substring(0,2));
		int yr=0;
		/* java.util.Calendar now = java.util.Calendar.getInstance(); //Calendar.get(Calendar.YEAR) - 1900. 
		java.text.SimpleDateFormat sdt = new java.text.SimpleDateFormat("yyyy"); //"yyyy-MM-dd");
		return sdt.format(now.getTime());
		yr = now.YEAR+1900; */
		java.util.Date dt = new java.util.Date();
		yr=dt.getYear()+1900;
		int sn = sno2; //java.lang.Integer.parseInt(sno2);
		if ((sno1>2 && sno1<5) || (sno1>6 && sno1<9)) {
			
			yr=yr-2000-sn; // 3,4, 7,8
		} else {
			yr=yr-1900-sn; // 1,2, 5,6
		} 
		// 5,6,7,8 �� �ܱ��� ��ȭ�� ���
		return yr;
	}


%>