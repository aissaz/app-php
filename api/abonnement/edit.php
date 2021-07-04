<?php

// en cas d'exception on retourne le code http 500 et un message a l'utilisateur
function myException($exception) {
    http_response_code(500);
    die("Exception : " . $exception->getMessage());
}
set_exception_handler('myException');
$num_abonnement = $_POST['num_abonnement'];
$date = $_POST['date'];
$designation_offre = $_POST['designation_offre'];
$num_client = $_POST['num_client'];
// include database connection file
include_once("../../config.php");

// Insert user data into table
$sql = "update abonnement set 
        date = '$date',
        designation_offre = '$designation_offre',
        num_client = '$num_client'
        where num_abonnement = '$num_abonnement' ";


$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);

    die(mysqli_error($conn));
}

echo json_encode($result);
$conn->close();
?>
