<?php

// en cas d'exception on retourne le code http 500 et un message a l'utilisateur
function myException($exception) {
    http_response_code(500);
    die("Exception : " . $exception->getMessage());
}
set_exception_handler('myException');

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

// Insert user data into table
$sql = "INSERT INTO offre(
        designation_offre,
        appels_op,
        sms_op,
        appels_autre_op,
        sms_autre_op,
        internet,
        prix,
        designation_op
        ) VALUES(
        '$designation_offre',
        '$appels_op',
        '$sms_op',
        '$appels_autre_op',
        '$sms_autre_op',
        '$internet',
        '$prix',
        '$designation_op'
    )";
$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);

    die(mysqli_error($conn));
}

echo json_encode($result);
$conn->close();

?>
