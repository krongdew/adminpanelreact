<?php
// ตั้งค่าการเชื่อมต่อกับฐานข้อมูล MySQL
$host = "db";
$username = "MYSQL_USER";
$password = "MYSQL_PASSWORD";
$database = "MYSQL_DATABASE";

// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $username, $password, $database);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    if (isset($_GET["table"]) && isset($_GET["CustomerID"])) {
        $table = $_GET["table"];
        $id = $_GET["CustomerID"];
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        if ($data) {
            $result = updateData($table, $id, $data);
            echo $result;
        } else {
            echo "ไม่มีข้อมูลที่จะอัพเดท";
        }
    } else {
        echo "ระบุตารางและรหัสที่ต้องการแก้ไขด้วยพารามิเตอร์ 'table' และ 'CustomerID'";
    }
}

function updateData($table, $id, $data) {
   
    $sql = "UPDATE $table SET ";

    $columns = array();
    foreach ($data as $key => $value) {
        if ($key !== "CustomerID") {
            $columns[] = "$key = '$value'";
        }
    }

    $sql .= implode(", ", $columns);
    $sql .= " WHERE CustomerID = $id";

    if ($conn->query($sql) === TRUE) {
        return "การแก้ไขข้อมูลเสร็จสมบูรณ์";
    } else {
        return "เกิดข้อผิดพลาดในการแก้ไขข้อมูล: " . $conn->error;
    }
}
?>
