<%!
Connection dbConn = null;
Statement stmt = null;
PreparedStatement pstmt = null;
boolean dbConnSw = false;

/*
                      Class Name dbFunc 
 -------------------------------------------------------------------------------------------------------------
 *  Source Path		: /include/dbFunc.jsp 
 *  �ý��۸�		: 
 * �������߼Һз�	:
 * ���α׷�����		: DB ��� ���̺귯��
 * ���ϸ�			: dbFunc.jsp
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
	< %@ include file ="/include/dbFunc.jsp" % >
	$db    = new dbConn;
	$db->setQuery("SELECT * FROM p_projectinfo where tb_nm='���̺��' ");

--------function list-----------------------------------------------------------
function dbConn($dbName = "oceans", $dbUser = "oceans", $dbPasswd = "snaeco") {
function setQuery($query) {
function setCnt($query){
function getCnt($table,$field,$where){
function setSelect($table,$field,$where) {
function getSelectAssoc( $table, $field, $where, $orders ) {
function setInsert($table,$arr,$arr2) {
function setUpdate($table,$arr,$where="") {
function setDelete($table,$where) {
function setMax($table,$field,$where)
function TableList()						//���̺� ���
function TableExists($tablename)			//���̺� ���� ����
 -------------------------------------------------------------------------------------------------------------

*/

void getConnectionMySql() throws ClassNotFoundException, SQLException,Exception
{
  //String strUrl = "jdbc:mysql://127.0.0.1/dit" ; //?user=dit?password=dit";
  String strUrl = "jdbc:mysql://mysql2.javasarang.net:3306/oceans?useUnicode=true&characterEncoding=euckr";
  
  //jdbc:mysql://[hostname][,failoverhost...][:port]/[dbname][?param1=value1][&param2=value2].....
  String uid="oceans";
  String pswd="snaeco";
  //Class.forName("org.gjt.mm.mysql.Driver").newInstance(); 
  //Class.forName("com.mysql.jdbc.Driver").newInstance();
  Class.forName("org.gjt.mm.mysql.Driver").newInstance();
  dbConn = DriverManager.getConnection( strUrl,uid,pswd );
  stmt = dbConn.createStatement(); // Ŀ�ؼ����κ��� Statement ����
  dbConnSw=true;
  return ;
}
ResultSet getRecordSet(String sql) throws ClassNotFoundException, SQLException,Exception
{
	return setQuery(sql);
}
ResultSet setQuery(String sql) throws ClassNotFoundException, SQLException,Exception
{
	try {
		//if(dbConnSw==false) getConnection();
		if(dbConnSw==false) getConnectionMySql();
		ResultSet rs0 = stmt.executeQuery( sql );
		return rs0;
	}
	catch (SQLException e){
		//out.println(e);
		return null;
	}
}


void ExecuteSql(String sql) throws ClassNotFoundException, SQLException,Exception
{
	try {
		if(dbConnSw==false) getConnectionMySql();
		int i=stmt.executeUpdate( sql );
		return ;
	}
	catch (SQLException e){
		//out.print(e);
		return;
	}
}

void CloseAll() {
	try {
		stmt.close();
		dbConn.close();
		dbConnSw=false;
		return;
	}
	catch (SQLException e){
		//out.println(e);
		return;
	}
}

void VisitLog(String ip,String url,String rem) throws ClassNotFoundException, SQLException,Exception
	{
		if (rem==null) rem="";
	// �湮�� �α� =======================================================
	String sqlLog="insert into visit (ip,dt,url,rem) values('"+ip+"',now(),'"+url+"','"+rem+"')";
	try {
		ExecuteSql(sqlLog);
	} catch (SQLException e){
		//out.println(e);
		return;
	}
	// �湮�� �α� =======================================================
}
%>