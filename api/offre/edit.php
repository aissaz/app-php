<?php

// Check If form submitted, insert form data into users table.

$designation_offre = $_POST['designation_offre'];
$appels_op = $_POST['appels_op'];
$sms_op = $_POST['sms_op'];
$appels_autre_op = $_POST['appels_autre_op'];
$sms_autre_op = $_POST['sms_autre_op'];
$internet = $_POST['internet'];
$prix = $_POST['prix'];
$designation_op = $_POST['designation_op'];
// include database connection file
include_once("../../config.php");
// en cas d'exception on retourne le code http 500 et un message a l'utilisateur
function myException($exception) {
    http_response_code(500);
    die("Exception : " . $exception->getMessage());
}
set_exception_handler('myException');
// Insert user data into table
$sql = "update offre set 
        appels_op = '$appels_op',
        sms_op = '$sms_op',
        appels_autre_op = '$appels_autre_op',
        sms_autre_op = '$sms_autre_op',
        internet = '$internet',
        prix = '$prix',
        designation_op = '$designation_op'
        where designation_offre = '$designation_offre' ";


$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);

    die(mysqli_error($conn));
}

echo json_encode($result);
$conn->close();

?>
