<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    include('db_connect.php');   
    include('random.php'); 
      $user = json_decode(file_get_contents('php://input'), true);  
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };
    
    checkIfUserExists($user, $conn);
    function checkIfUserExists($user, $conn){
            $message = array();
            $message["result"] = false;
            $uname = $user['u'];
            $query  =  "SELECT l_id
                    FROM login
                    WHERE l_user = '$uname'";
            $result = $conn->query($query); 
              if ($result->num_rows > 0) {
            $message['message'] = "Please use a different username.";
            echo json_encode($message);
        }else{
            register($user, $conn);
        }
    }

    function register($user, $conn){

        $message = array();
        $message["result"] = false;
        $message["user"] = $user;
        $u_id = 'U'.generateRand();
        $user['id'] = $u_id;
        $u_fname=$user['fname'];
        $u_lname=$user['lname'];
        $u_user = $user['u'];
        $u_pass = md5($user['p']);
        $u_cpass = $user['c'];
        $u_age = $user['a'];
        $u_gender = $user['g'];
        $u_email = $user['e'];
        $u_level = 1;
        $u_credits = 25000;
        $u_links = 5;
        $u_ref = generateRefCode();
        $blank = "";
        $blank = "";
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
                            $blank,
                            $blank
                        );
        if($stmt->execute()){
            $l_id = generateRand();
            $u_id = $user['id'] ;
            $l_user = $user['u'];
            $l_pass = md5($user['p']);
            $stmt = $conn->prepare("INSERT INTO login ( 
                                    l_id,
                                    u_id, 
                                    l_user, 
                                    l_pass
                                )
                                VALUES (?, ?, ?, ?)"); 
            $stmt->bind_param("ssss", 
                            $l_id, 
                            $u_id, 
                            $l_user,
                            $l_pass
                            );
            if($stmt->execute()){
                addRefData($user, $conn, $message);
            }else{
                
            }
        }
    }

    function addRefData($user, $conn, $message){
        $refCode = $user['ref'];
        if($refCode!= ""){
            $arr = array();
            $table = 'user';
            $id_field = 'u_id';
            $ref_field = 'u_ref';
            $query = "SELECT * 
                      FROM $table
                      WHERE $ref_field = '$refCode'";
               
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
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
                if($stmt->execute()){                                    
                    $message["result"] = true;
                    echo json_encode($message);
                }
            }
        }            
    }
    $conn->close();
?> 
  
    
  
    
   