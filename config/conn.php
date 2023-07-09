<?php

$server_name = "localhost";
$server_username = "root";
$server_password = "";
$db_name = "mycontacts";


try{
    $conn = mysqli_connect($server_name, $server_username, $server_password, $db_name);

    if(!$conn){
        die("Connection Failed:". mysqli_connect_error());
        
    }
}
catch(Exception $msg){
    echo "<script>alert($msg)</script>";
}
?>