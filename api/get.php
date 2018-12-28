<?php

        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET");
        header("Content-Type: application/json");
        
        $fitbit = $_GET['fitbit'];
        
        $conn = pg_connect(getenv("DATABASE_URL"));
        $myquery = "SELECT * FROM users WHERE fitbit='{$fitbit}';";
        $result = pg_query($conn, $myquery);      
        
        if(pg_num_rows($result) > 0) {
            $row = pg_fetch_row($result);
            
            
            
            echo '{ "success" : true }';
        } else {
            echo '{ "success" : false, "msg" : "Could not find user in database." }';            
        }               
        
        pg_close();
       
?>
