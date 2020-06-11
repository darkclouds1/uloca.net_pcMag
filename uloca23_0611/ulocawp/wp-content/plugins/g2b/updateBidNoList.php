<?

@extract($_GET);
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');
$dbConn = new dbConn;
$conn = $dbConn->conn();

$bidNoList = trim($_GET['bidNoList']);
$userId    = $_GET['userId'];
$type      = $_GET['type']; // C=저장, R=읽기

// 로그아웃 후 id가  없는 경우
if ($userId == '') {
    // alert ("관심목록은 로그인 후 사용하실 수 있습니다.")
    echo (false);
    return;
}

// 저장, 조회 모두 관심입찰을 return 함. (값이 없으면 false)
switch ($type) {
    case 'U':  // UPDATE
        $sql  = "REPLACE INTO autoPubAccnt (id, bidNoList) VALUES ('" .$userId. "', '" .$bidNoList. "')";
        if ($conn->query($sql) == false) {
            return false; 
        }
        echo ($bidNoList);  // 저장 후 목록 전달
        break;

    case 'R':   // 조회
        $sql  = "SELECT bidNoList FROM autoPubAccnt WHERE id= '" .$userId. "'";
        $result = $conn->query($sql);
        $num_rows = $dbResult->num_rows;
        if ($num_rows == 0) {
            $sql  = "REPLACE INTO autoPubAccnt (id, bidNoList) VALUES ('" .$userId. "', '" .$bidNoList. "')";
            echo false; // 0=false ::값이 없고, 사용자 추가
        }
        if ($row = $result->fetch_assoc()) {
            $bidNoList = $row['bidNoList'];
            echo ($bidNoList); 
        }
        break;
}

?>