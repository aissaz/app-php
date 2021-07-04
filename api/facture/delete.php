
<?php

// en cas d'exception on retourne le code http 500 et un message a l'utilisateur
function myException($exception) {
    http_response_code(500);
    die("Exception : " . $exception->getMessage());
}
set_exception_handler('myException');
$num_facture = $_POST['num_facture'];

// include database connection file
include_once("../../config.php");

// delete query
$sql =  "delete from facture 
        where num_facture = '$num_facture'";
$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);

    die(mysqli_error($conn));
}

echo json_encode($result);
$conn->close();

?>
