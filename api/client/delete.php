
<?php

// en cas d'exception on retourne le code http 500 et un message a l'utilisateur
function myException($exception) {
    http_response_code(500);
    die("Exception : " . $exception->getMessage());
}
set_exception_handler('myException');
$num = $_POST['num'];

// include database connection file
include_once("../../config.php");

// delete query
$sql =  "delete from client 
        where num = '$num'";
$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);
    die(mysqli_error($conn));
}

echo json_encode($result);
$conn->close();

?>
