<?php

// en cas d'exception on retourne le code http 500 et un message a l'utilisateur
function myException($exception) {
    http_response_code(500);
    die("Exception : " . $exception->getMessage());
}
set_exception_handler('myException');
$user_name = $_POST['user_name'];
$mot_passe = $_POST['password'];



// include database connection file
include_once("../../config.php");

// Insert user data into table
$sql = "update client set 
         mot_passe = '$mot_passe'
        where num = '$user_name' ";

echo $sql;
$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);
    die(mysqli_error($conn));
}

echo json_encode($result);
$conn->close();
?>
