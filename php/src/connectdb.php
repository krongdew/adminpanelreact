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
    
// } elseif ($_SERVER["REQUEST_METHOD"] === "DELETE") {
//     // ตรวจสอบการลบข้อมูลจากตารางที่คุณต้องการ
//     if (isset($_GET["table"]) && isset($_GET["CustomerID"])) {
//         $table = $_GET["table"];
//         $id = $_GET["CustomerID"];
        
//         $sql = "DELETE FROM " . $table . " WHERE CustomerID = " . $id;
//         if ($conn->query($sql) === TRUE) {
//             echo "รายการถูกลบเรียบร้อย";
//         } else {
//             echo "เกิดข้อผิดพลาดในการลบรายการ: " . $conn->error;
//         }
//     } else {
//         echo "ระบุตารางและรหัสที่ต้องการลบด้วยพารามิเตอร์ 'table' และ 'CustomerID'";
//     }
} elseif ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    if (isset($_GET["table"]) && isset($_GET["CustomerID"])) {
        $table = $_GET["table"];
        $customerIDs = $_GET["CustomerID"];

        $sql = "DELETE FROM " . $table . " WHERE CustomerID IN (" . $customerIDs . ")";
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
//สำหรับการเพิ่มข้อมูล
// Check if the request method is POST and if it contains data
// ...
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST)) {
   
        if (isset($_GET["table"])) {
            $table = $_GET["table"];
            // ดำเนินการดึงข้อมูลจากตารางที่คุณต้องการ
    // สร้างตัวแปรเพื่อเก็บข้อมูลที่ถูกส่งมาจากฟอร์มผ่าน HTTP POST
    $customerName = $_POST['CustomerName']; // ชื่อลูกค้า
    $customerEmail = $_POST['CustomerEmail']; // อีเมลลูกค้า
    $projectName = $_POST['ProjectName']; // ชื่อโครงการ
    $status = $_POST['Status']; // สถานะ
    $weeks = $_POST['Weeks']; // สัปดาห์
    $budget = $_POST['Budget']; // งบประมาณ
    $location = $_POST['Location']; // สถานที่

    if (isset($_FILES['CustomerImage'])) {
        $image_name = 'http://localhost:8080/img/' . $_FILES['CustomerImage']['name'];
        $image_temp = $_FILES['CustomerImage']['tmp_name'];
        
        $target = '/var/www/html/img/' . $_FILES['CustomerImage']['name'];
        move_uploaded_file($image_temp, $target);
    }
    else {
        $image_name = 'http://localhost:8080/img/default.jpg'; // URL ของรูปภาพเริ่มต้นถ้าไม่มีรูปภาพถูกอัปโหลด
    }

    // SQL Query สำหรับการเพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO $table (CustomerName, CustomerEmail, ProjectName, Status, Weeks, Budget, Location, CustomerImage) VALUES ('$customerName', '$customerEmail', '$projectName', '$status', '$weeks', '$budget', '$location', '$image_name')";

    if ($conn->query($sql) === TRUE) {
        echo "การเพิ่มข้อมูลลูกค้าเสร็จสมบูรณ์";
    } else {
        echo "เกิดข้อผิดพลาดในการเพิ่มข้อมูลลูกค้า: " . $conn->error;
    }
} else {
    echo "กรุณาระบุตารางและข้อมูลที่ต้องการเพิ่มด้วยพารามิเตอร์ 'table' และ HTTP POST data";
}
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
