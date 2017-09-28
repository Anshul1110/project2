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

    //This is the line to get JS object sent by...yaha tk humne $http (AngularJS - Client side) se server (PHP) pe request bheji h aur json_decode se usko PHP ke use ke liye convert kiya hai, (json object -> PHP Array)ohkk ab yaha se mj value db daaldunga right ?? query likh kr haan insert ? which else? fir uske baad login me bh same insert krke value ,,,, naopnde login ki details register ko kese ?feck baar reg ho gaya to teeno details stored hai uske baad nahi store karna hai, login par username aur password match karna hai select statement use karke... isi type ka function? haan...boss gussa mat hona dheere dheere samajh ati hmj ..:Paur kuch pucxhna h?nh nh me ye krta hu ....pehle....ancha 1 baat ....1 baar git bh check krlo mera sahi kiya kya? haan dekha hai sahi hai ...or meri branch se git upstream kese set krte h chalo wo baad me krta hu ...ok
/*
ab please is code ko samajh le dhang se main thodi der me aata hun..ohk boss..... suno cpass bh banana h na?


"Boss khana khaale ta hu mummy bula rrh h ...15mins only
u dr??
"

*/
    $user = json_decode(file_get_contents('php://input'), true);

    register($user, $conn);

    function register($user, $conn){
        $message = array();
        $message["result"] = false;
        $l_id = 'A'.generateRand();
        $user['id'] = $l_id;
        $l_user = $user['u'];
        $l_pass = md5($user['p']);
        $l_email = $user['e'];
        $l_ref = generateRefCode();
        $user['ref'] = $l_ref;
        $stmt = $conn->prepare("INSERT INTO login ( 
                                    l_id, 
                                    l_user,
                                    l_pass,
                                    l_email,
                                    l_ref
                                )
                                VALUES (?, ?, ?, ?, ?)"); 
 
        $stmt->bind_param("sssss", 
                            $l_id, 
                            $l_user, 
                            $l_pass, 
                            $l_email,
                            $l_ref
                        );
        if($stmt->execute()){
            $message["result"] = true;
            $message["user"] = $user;
            echo json_encode($message);
        }  
    }
       
    $conn->close();
?>