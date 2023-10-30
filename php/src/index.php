<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        $host = 'db';
        $user = 'MYSQL_USER';
        $pass = 'MYSQL_PASSWORD';
        
        $conn = new mysqli($host,$user,$pass);
        
        if($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }else{
            echo "Connected to MYSQL server successfully";
        }
        
    ?>
</body>
</html>