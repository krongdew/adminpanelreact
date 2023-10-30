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

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบคำขอ HTTP method ที่ส่งมา (GET, POST, เป็นต้น) เพื่อดำเนินการตามเงื่อนไขที่คุณต้องการ

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // ตรวจสอบคำขอและพารามิเตอร์ที่ส่งมาจากหน้าของคุณ
    if (isset($_GET["table"])) {
        $table = $_GET["table"];
        // ดำเนินการดึงข้อมูลจากตารางที่คุณต้องการ
        $sql = "SELECT * FROM " . $table;
        $result = $conn->query($sql);

        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        // ตั้งค่าส่วนหัวเพื่อระบุว่าไฟล์นี้รีเทิร์น JSON
        header('Content-Type: application/json');

        // ส่งข้อมูล JSON กลับไปยัง JavaScript
        echo json_encode($data);
    } else {
        echo "ระบุตารางที่ต้องการดึงข้อมูลด้วยพารามิเตอร์ 'table'";
    }
} else {
    echo "ไม่รองรับ HTTP method นี้";
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
