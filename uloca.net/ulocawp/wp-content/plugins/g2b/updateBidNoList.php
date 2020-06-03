<?

@extract($_GET);
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');
$dbConn = new dbConn;
$conn = $dbConn->conn();

$bidNoList = trim($_GET['bidNoList']);
$id        = $_GET['id'];
$type      = $_GET['type']; // C=저장, R=읽기

// 로그아웃 후 id가  없는 경우
if ($id == '') {
    echo (false);
    return;
}

// 저장, 조회 모두 관심입찰을 return 함. (값이 없으면 false)
switch ($type) {
    case 'C':  // UPDATE
        $sql  = "REPLACE INTO autoPubAccnt (id, bidNoList) VALUES ('" .$id. "', '" .$bidNoList. "')";
        if ($conn->query($sql) <> true) {
            echo "err sql=" . $sql;
        } else {
                echo $bidNoList;
        }        
        break;

    case 'R':   // 조회
        $sql  = "SELECT bidNoList FROM autoPubAccnt WHERE id= '" .$id. "'";
        $result = $conn->query($sql);
        $num_rows = $dbResult->num_rows;
        if ($num_rows == 0) {
            echo($num_rows); // 0=false
        }
        if ($row = $result->fetch_assoc()) {
            $bidNoList = $row['bidNoList'];
            echo ($bidNoList); 
        } else { // 없으면 Replace
            $sql  = "REPLACE INTO autoPubAccnt (id, bidNoList) VALUES ('" .$id. "', '" .$bidNoList. "')";
            echo ($bidNoList); 
        }
        break;
}

?>