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
    
} elseif ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    // ตรวจสอบการลบข้อมูลจากตารางที่คุณต้องการ
    if (isset($_GET["table"]) && isset($_GET["CustomerID"])) {
        $table = $_GET["table"];
        $id = $_GET["CustomerID"];
        
        $sql = "DELETE FROM " . $table . " WHERE CustomerID = " . $id;
        if ($conn->query($sql) === TRUE) {
            echo "รายการถูกลบเรียบร้อย";
        } else {
            echo "เกิดข้อผิดพลาดในการลบรายการ: " . $conn->error;
        }
    } else {
        echo "ระบุตารางและรหัสที่ต้องการลบด้วยพารามิเตอร์ 'table' และ 'CustomerID'";
    }
    
} elseif ($_SERVER["REQUEST_METHOD"] === "PUT") {
    // ตรวจสอบการแก้ไขข้อมูลจากตารางที่คุณต้องการ
    $put_vars = json_decode(file_get_contents("php://input"), true);

    if (isset($_GET["table"]) && isset($_GET["CustomerID"])) {
        $table = $_GET["table"];
        $id = $_GET["CustomerID"];

        if ($put_vars) {
            $sql = "UPDATE $table SET ";

            $columns = array();
            foreach ($put_vars as $key => $value) {
                if ($key !== "CustomerID") {
                    $columns[] = "$key = '$value'";
                }
            }

            $sql .= implode(", ", $columns);
            $sql .= " WHERE CustomerID = $id";

            if ($conn->query($sql) === TRUE) {
                echo "การแก้ไขข้อมูลเสร็จสมบูรณ์";
            } else {
                echo "เกิดข้อผิดพลาดในการแก้ไขข้อมูล: " . $conn->error;
            }
        } else {
            echo "ไม่มีข้อมูลที่จะอัพเดท";
        }
    } else {
        echo "ระบุตารางและรหัสที่ต้องการแก้ไขด้วยพารามิเตอร์ 'table' และ 'CustomerID'";
    }
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
