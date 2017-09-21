<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    include('db_connect.php');   
    include('random.php');   
    include('MAIL/PHPMailerAutoload.php');
    $user = json_decode(file_get_contents('php://input'), true);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };
    $message = array();
    $message["result"] = false;
    
    checkIfUserExists($user, $conn);
    function checkIfUserExists($user, $conn){
        $uname = $user['uname'];
        $query  =  "SELECT l_id
                    FROM login
                    WHERE l_user = '$uname'";
        $result = $conn->query($query);  
        if ($result->num_rows > 0) {
            $message['message'] = "Please use a different username.";
            echo json_encode($message);
        }else{
            switch ($user['r']) {
                case "Online Entrepreneur":
                    register($user, $conn);
                    echo 'anshul';
            }
        }
    }
   
    function register($user, $conn){
        $l_id = 'A'.generateRand();
        $user['id'] = $l_id;
        $l_user = $user['user'];
        $l_pass = $user['pass'];
        $l_email = $user['email'];
        $stmt = $conn->prepare("INSERT INTO login ( 
                                    l_id, 
                                    l_user,
                                    l_pass
                                    l_email,
                                )
                                VALUES (?, ?, ?, ?)"); 
 
        $stmt->bind_param("ssss", 
                            $l_id, 
                            $l_user, 
                            $l_pass, 
                            $l_email,
                            );
        if($stmt->execute()){
            addLoginDetails($user, $conn);
        }
    }
  
    
    function addLoginDetails($user, $conn){
        //Check if referred
        $refCode = $user["c"];
        if($refCode!=""){
            $arr = array();
            switch ($user['r']) {
                case "Online Entrepreneur":
                    $table = 'login';
                    $id_field = 'l_id';
                    break;
            }
            $query  =  "SELECT *
                        FROM $table
                        WHERE $ref_field = '$refCode'";
            $result = $conn->query($query);  
           /* if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $arr[] = $row;
                }
                $r_id = 'R'.generateRand();
                $parent_id = $arr[0][$id_field];
                $child_id = $user['id'];
                $stmt = $conn->prepare("INSERT INTO referral ( 
                                            r_id,
                                            r_code, 
                                            r_parent_id, 
                                            r_child_id
                                        )
                                        VALUES (?, ?, ?, ?)"); 
                $stmt->bind_param("ssss", 
                                    $r_id, 
                                    $refCode,
                                    $parent_id,
                                    $child_id
                                    );
                $stmt->execute();
            }
        }*/
        if( $user['pass'] == $user['cpass']){
            $l_id = $user['id'];
            $l_user = $user['user'];
            $l_status = "Pending";
            $id_md5 = md5($user['id']);
            $l_token = md5($user['r']).$id_md5;
            $l_pass = md5($user['pass']);
            $stmt = $conn->prepare("INSERT INTO login ( 
                                        l_id,
                                        l_user, 
                                        l_pass,
                                        l_email
                                        l_token,
                                        l_status
                                    )
                                    VALUES (?, ?, ?, ?, ?, ?)"); 
            $stmt->bind_param("ssssss", 
                                $l_id, 
                                $l_user,
                                $l_pass,
                                $l_email,
                                $l_token,
                                $l_status
                                );
            if($stmt->execute()){
                if($_SERVER['SERVER_NAME'] != "localhost"){
                    //Currently Hosted URL
                    $verifurl = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/projects/project2"."/backend/verify.php?xcj=".$id_md5."&tc=".$l_token;
                }else{
                    //Local URL
                    $verifurl = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/project2"."/backend/verify.php?xcj=".$id_md5."&tc=".$l_token;
                }
                $message["result"] = true;
                $message["user"] = $user;
                $message["message"] = "Thank you for registering, a verification E-Mail has been sent to your E-Mail ID. You can login after verifying successfully.";
                sendVerificationEmail($message, $user);
            }
        }else{
            $message['result'] = false;
            echo json_encode($message);
        }
    }
    function sendVerificationEmail($message, $user){
        $mail = new PHPMailer;
        $mail->IsSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'sg2plcpnl0102.prod.sin2.secureserver.net';  // Specify main and backup SMTP servers
        $mail->Port = 465;                                    // TCP port to connect to
        $mail->SMTPDebug  = 0;           
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->SMTPSecure = "ssl";
        $mail->Username = 'anurag@envisagecyberart.in';                 // SMTP username
        $mail->Password = 'sbb-4645752';                           // SMTP password
        $mail->IsHTML(true);  
        $mail->setFrom('anurag@envisagecyberart.in', 'Member Registration Admin');        
        $mail->addAddress($user["email"], $user["fname"]);     // Add a recipient
        $mail->Subject = 'Member Verification E-Mail | '.$user['r'].' '.$user['fname'].' '.$user['lname'];
        $body = '<div style="font-size:1.5em;">';
        $body.=     '<h3>Hello, '.$user["fname"]." ".$user["lname"].'!</h3>';
        $body.=     '<p>Thank you for registering with us as a '.$user['r'].'. Click on the below button to complete the verification process.</p><br/>';
        $body.=     '<a style="cursor:pointer;" href="'.$message["url"].'"><button style="font-size:1em; padding:0.5em;">Verify Account</button></>';
        $body.= '</div>';
        $mail->Body = $body;
        if(!$mail->send()) {
            $message['mailsent'] = false;
        } else {
            $message['mailsent'] = true;
        }
        echo json_encode($message);
    }
    $conn->close();
?>