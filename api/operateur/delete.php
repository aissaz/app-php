
<?php

// en cas d'exception on retourne le code http 500 et un message a l'utilisateur
function myException($exception) {
    http_response_code(500);
    die("Exception : " . $exception->getMessage());
}
set_exception_handler('myException');
$designation_op = $_POST['designation_op'];

// include database connection file
include_once("../../config.php");

// delete query
$sql =  "delete from operateur 
        where designation_op = '$designation_op'";
$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);

    die(mysqli_error($conn));
}

echo json_encode($result);
$conn->close();

?>
