<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    include('db_connect.php');   
    include('random.php');   
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };
    $user = json_decode(file_get_contents('php://input'), true);
    socialLogin($user, $conn);

    function socialLogin($user, $conn){
    $message["user"] = $user;
   	$arr = array();
    $message = array();
    $message["result"] = false;
     $e = $user['email'];
      $query  =  "SELECT *
                FROM login
                WHERE l_email = '$e'";
       $result = $conn->query($query);
        if ($result->num_rows > 0) {   
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
        }
    }
    
}
$conn->close();
?>
