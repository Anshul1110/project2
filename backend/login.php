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

    //This is the line to get JS object sent by...yaha tk humne $http (AngularJS - Client side) se server (PHP) pe request bheji h aur json_decode se usko PHP ke use ke liye convert kiya hai, (json object -> PHP Array)ohkk 
    $user = json_decode(file_get_contents('php://input'), true);
    login($user, $conn);

    function login($user, $conn){
    $message["user"] = $user;
    $u = $user['u'];
    $p = md5($user['p']);
    $arr = array();
    $message = array();
    $message["result"] = false;
    //har code kisi scenario ke liye likha hua hai
    $query  =  "SELECT *
                FROM login
                WHERE l_user = '$u'
                AND l_pass = '$p'";
    $result = $conn->query($query);
         if ($result->num_rows > 0) {  
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
        }
    }
    if(count($arr) > 0){
        $u_id = $arr[0]['u_id'];
        unset($arr); 
        $arr = array();
        $query  =  "SELECT *
                    FROM user
                    WHERE u_id = '$u_id'";
        $result = $conn->query($query);
        //return result in this line
        if ($result->num_rows > 0) {   
            while($row = $result->fetch_assoc()) {
                $arr[] = $row;
            }
            $message["message"] = "Login Successful.";
            $message["result"] = true;
            $message["user"] = $arr[0];
             
            $table = 'user';
            $db_id = $arr[0]['u_id'];
            $id_field = 'u_id';
            $userArr = array();
            $query  =  "SELECT r_child_id
                        FROM referral
                        WHERE r_parent_id = '$db_id'";
            $result = $conn->query($query);  
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $arr = array();
                    $id = $row["r_child_id"];
                    $query  =  "SELECT *
                        FROM $table
                        WHERE $id_field = '$id'";
                    $newresult = $conn->query($query);
                    while($newrow = $newresult->fetch_assoc()) {
                        $arr[] = $newrow;
                    }
                    $userArr = $arr;
                }
            }   
            $message['user']['referrals'] = $userArr;
            echo json_encode($message); 
        }else{
            echo $u_id;
        }
    } else{
        $message["message"] = "Wrong User ID/Password.";
        echo json_encode($message);
    }
}
$conn->close();
?>
