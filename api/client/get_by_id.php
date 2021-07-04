
<?php

// en cas d'exception on retourne le code http 500 et un message a l'utilisateur
function myException($exception) {
    http_response_code(500);
    die("Exception : " . $exception->getMessage());
}
set_exception_handler('myException');
$num = $_GET['num'];

// include database connection file
include_once("../../config.php");

// delete query
$sql =  "select * from client 
        where num = '$num'";
$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);
    die(mysqli_error($conn));
}
$dbdata = array();
if ($result->num_rows > 0) {
        $dbdata=$result->fetch_assoc();
}

http_response_code(200);
// output client as json
echo json_encode($dbdata);

?>
