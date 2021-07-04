<?php

// en cas d'exception on retourne le code http 500 et un message a l'utilisateur
function myException($exception) {
    http_response_code(500);
    die("Exception : " . $exception->getMessage());
}
set_exception_handler('myException');
$num_facture = $_POST['num_facture'];
$montant = $_POST['montant'];
$date = $_POST['date'];
$num_client = $_POST['num_client'];
$num_abonnement = $_POST['num_abonnement'];

// include database connection file
include_once("../../config.php");

// Insert user data into table
$sql = "INSERT INTO facture(
        montant,
        date,
        num_client,
        num_abonnement
        ) VALUES(
        '$montant',
        '$date',
        '$num_client',
        '$num_abonnement'
    )";
$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);

    die(mysqli_error($conn));
}

echo json_encode($result);
$conn->close();

?>
