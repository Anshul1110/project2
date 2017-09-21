<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    include('db_connect.php');   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };
    $v_det = $_GET;
    $arr = array();
    $message = array();
    $message["result"] = false;
    $u_md5 = $v_det['xcj'];
    $token_md5 = $v_det['tc'];
    $query  =  "SELECT l_token, l_status, l_role, l_id 
                FROM login
                WHERE MD5(l_id) = '$u_md5'
                AND l_token = '$token_md5'";
                
    $result = $conn->query($query);  
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
        }
    }
    if(count($arr) > 0){    
        if($arr[0]["l_status"] == "Pending"){
            $query  =  "UPDATE login
                        SET l_status = 'Active'
                        WHERE MD5(l_id) = '$u_md5'";
            $result = $conn->query($query);  
            if($result){
                $status_msg = "You have been verified successfully.<br/> Please login using your Username and Password.";
            }else{
                $status_msg = "There was a problem verifying, please try again later.";
            }
        }else{
            $status_msg = "You have already registered. You can login using the details sent in the confirmation E-Mail.";
        }
    }else{
        $status_msg = "Please register first. If you have already registered, please contact admin.";
    }
    if($_SERVER['SERVER_NAME'] != "localhost"){
        //Currently Hosted URL
        $hostname = "http://" .$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/projects/Member-Registration"; 
    }else{
        //Local URL
        $hostname = "http://" .$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/Member-Registration"; 
    }
    /*var_dump($arr);
    var_dump($query);
    var_dump($v_det);*/
    include('../templates/header.html');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Verification Page</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body style="padding-top:6rem;">        
    <div class="container text-center">
        <div class="row">
            <div class="col-md-12">
                <h1><?php echo $status_msg;?></h1>
                <a href="<?php echo $hostname; ?>">
                    <button class="btn btn-lg btn-success">Click here to go to Login Page</button>
                </a>
            </div>
        </div>
    </div>
  </body>
</html>