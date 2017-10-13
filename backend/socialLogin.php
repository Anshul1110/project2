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
    
        $token = json_decode(file_get_contents('php://input'), true);
    
            checkIfUserExists($token, $conn);
            function checkIfUserExists($token, $conn){
                $message = array();
                $message["result"] = false;
                $u_email = $token['email'];
                $query  =  "SELECT *
                            FROM user
                            WHERE u_email = '$u_email'";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {   
                     while($row = $result->fetch_assoc()) {
                        $arr[] = $row;
                    }
                    $message["message"] = "Already Exist";
                    $message["user"] = $arr;
                }else{
                    $message["result"] = true;
                    echo json_encode($message);
                    socialLogin($token, $conn);
                }

            }
            function socialLogin($token, $conn){
                $message = array();
                $message["result"] = false;
                $message["token"] = $token;
                $u_id = 'U'.generateRand();
                $user['id'] = $u_id;
                $nam = $token['name'];
                list($u_fname, $u_lname) = explode(' ', $nam);
                $u_user = '';
                $u_pass = '';
                $u_cpass = '';
                $u_age = '';
                $u_gender = '';
                $u_email = $token['email'];
                $u_level = 1;
                $u_credits = 25000;
                $u_links = 5;
                $u_ref = generateRefCode();
                $social_type = $token['provider'];
                $social_id = $token['uid'];
                $stmt = $conn->prepare("INSERT INTO user ( 
                                            u_id, 
                                            u_fname, 
                                            u_lname, 
                                            u_user, 
                                            u_age,
                                            u_gender,
                                            u_email,
                                            u_level,
                                            u_credits,
                                            u_links,
                                            u_ref,
                                            u_social_type,
                                            u_social_id
                                        )
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"); 
                $stmt->bind_param("sssssssssssss", 
                                    $u_id, 
                                    $u_fname, 
                                    $u_lname, 
                                    $u_user, 
                                    $u_age, 
                                    $u_gender, 
                                    $u_email,
                                    $u_level,
                                    $u_credits,
                                    $u_links,
                                    $u_ref,
                                    $social_type,
                                    $social_id
                                );
                if($stmt->execute()){
                        $db_id = $u_id;
                        $userArr = array();
                        $query  =  "SELECT r_child_id
                                    FROM referral
                                    WHERE r_parent_id = '$db_id'";
                        $result = $conn->query($query);  
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                unset($arr); 
                                $arr = array();
                                $id = $row["r_child_id"];
                                $query  =  "SELECT *
                                            FROM user
                                            WHERE u_id = '$id'";
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
                 }
                $conn->close();
        ?>
    