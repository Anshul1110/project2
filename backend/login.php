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
      //runs the query and put the resulting data into a variable
    /*$message["query"] = $query;*/
    if ($result->num_rows > 0) {   //checks if there are more than zero rows returned.
        //output data of each row
        while($row = $result->fetch_assoc()) {
            // fetch_assoc() puts all the results into an associative array that we can loop through
            //while() loop loops through through the result set and output the data from the id
            $arr[] = $row;
        }
    }
     if(count($arr) > 0){
        $message["message"] = "Login Successful.";
        $message["result"] = true;
        $message["user"] = $arr[0];
        echo json_encode($message);
    } else{
        $message["message"] = "Wrong User ID/Password.";
        echo json_encode($message);
    }
}

$conn->close();
?>
