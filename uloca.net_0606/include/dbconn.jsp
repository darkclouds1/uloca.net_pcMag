<%!
Connection dbConn = null;
Statement stmt = null;
PreparedStatement pstmt = null;
boolean dbConnSw = false;
boolean MYSQL=true;
boolean ORACLE=false;
boolean MSSQL=false;

void setDBStyle(String DBS) throws Exception
{
	MYSQL=true;
	try {
		//if("".equals(DBS)) return;
		
		if ("MYSQL".equals(DBS)) {
			MYSQL=true;
			ORACLE=false;
			MSSQL=false;
		}
		if ("ORACLE".equals(DBS)) {
			MYSQL=false;
			ORACLE=true;
			MSSQL=false;
		}
		if ("MSSQL".equals(DBS)) {
			MYSQL=false;
			ORACLE=false;
			MSSQL=true;
		}
		
	}
	catch (Exception e){
		//out.println("setDBStyle error..");
		return ;
	}
} 
void getConnection() throws ClassNotFoundException, SQLException,Exception
{
	if(dbConnSw) return;
	if (MYSQL) getConnectionMySql();
	if (ORACLE) getConnectionOracle();
}
void getConnectionMySql() throws ClassNotFoundException, SQLException,Exception
{
  //String strUrl = "jdbc:mysql://127.0.0.1/dit" ; //?user=dit?password=dit";
  String strUrl = "jdbc:mysql://127.0.0.1:3306/jayhmj?useUnicode=true&characterEncoding=euckr";
  //String strUrl = "jdbc:mysql://127.0.0.1:3306/oceans?useUnicode=true&characterEncoding=euckr";
  
  //jdbc:mysql://[hostname][,failoverhost...][:port]/[dbname][?param1=value1][&param2=value2].....
  String uid="jayhmj";
  String pswd="zxcv23";
  //Class.forName("com.mysql.jdbc.Driver").newInstance();
  Class.forName("org.gjt.mm.mysql.Driver").newInstance();
  dbConn = DriverManager.getConnection( strUrl,uid,pswd );
  stmt = dbConn.createStatement(); // 커넥션으로부터 Statement 생성
  dbConnSw=true;
  return ;
}

void getConnectionOracle() throws ClassNotFoundException, SQLException,Exception
{
	
    try {
        // Load the JDBC driver
        String driverName = "oracle.jdbc.driver.OracleDriver";
        Class.forName(driverName);

        // Create a connection to the database
        String serverName = "211.43.25.195";
        String portNumber = "1521";
        String sid = "ORCL";
        String url = "jdbc:oracle:thin:@" + serverName + ":" + portNumber + ":" + sid;
        String username = "upay";
        String password = "dbvpdlajsxm";
        dbConn = DriverManager.getConnection(url, username, password);
		stmt = dbConn.createStatement(); // 커넥션으로부터 Statement 생성
		dbConnSw=true;

    } catch (ClassNotFoundException e) {
        // Could not find the database driver
    } catch (SQLException e) {
        // Could not connect to the database
    }
}
ResultSet getRecordSet(String sql) throws ClassNotFoundException, SQLException,Exception
{
	try {
		getConnection();
		//if(dbConnSw==false) getConnectionMySql();
		ResultSet rs0 = stmt.executeQuery( sql );
		return rs0;
	}
	catch (SQLException e){
		//out.println(e);
		return null;
	}
}
void getRecordSetRs(String sql,ResultSet rs) throws ClassNotFoundException, SQLException,Exception
{
	try {
		getConnection();
		//out.print("getRecordSetRs 1");
		//if(dbConnSw==false) getConnectionMySql();
		rs = stmt.executeQuery( sql );
		return ;
	}
	catch (SQLException e){
		//out.println(e);
		return ;
	}
}
ResultSet ReadRecordSet(String sql) throws ClassNotFoundException, SQLException,Exception
{
	try {
		getConnection();
		//if(dbConnSw==false) getConnectionMySql();
		pstmt = dbConn.prepareStatement(sql);
		pstmt.setInt(1,1);
		ResultSet rs0 = pstmt.executeQuery();
		return rs0;
	}
	catch (SQLException e){
		//out.println(e);
		return null;
	}
}

/**************************************
/* RecordSet parameter test -by 정순장
/**************************************
boolean getRecordSet_m(String sql, ResultSet pRst) throws ClassNotFoundException, SQLException,Exception
{
	try {
		if(dbConnSw==false) getConnection();
		//if(dbConnSw==false) getConnectionMySql();
		pRst = stmt.executeQuery( sql );
		return true;
	}
	catch (SQLException e){
		//out.println(e);
		return false;
	}
}
*****************************************/
void ExecuteSql(String sql) throws ClassNotFoundException, SQLException,Exception
{
	try {
		getConnection();
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
	// 방문자 로그 =======================================================
	String sqlLog="insert into visit (ip,dt,url,rem) values('"+ip+"',now(),'"+url+"','"+rem+"')";
	try {
		ExecuteSql(sqlLog);
	} catch (SQLException e){
		//out.println(e);
		return;
	}
	// 방문자 로그 =======================================================
}
%>