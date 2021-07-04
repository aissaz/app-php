<?php

// en cas d'exception on retourne le code http 500 et un message a l'utilisateur
function myException($exception) {
    http_response_code(500);
    die("Exception : " . $exception->getMessage());
}
set_exception_handler('myException');
$num = $_POST['num'];
$mail = $_POST['mail'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$date_naiss = $_POST['date_naiss'];
$pays = $_POST['pays'];
$mot_passe = $_POST['mot_passe'];
$designation_op = $_POST['designation_op'];

// include database connection file
include_once("../../config.php");

// Insert user data into table
$sql = "update client set 
        mail = '$mail',
        nom = '$nom',
        prenom = '$prenom',
        date_naiss = '$date_naiss',
        pays = '$pays',
        mot_passe = '$mot_passe',
        designation_op = '$designation_op'
        where num = '$num' ";
$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);
    die(mysqli_error($conn));
}

echo json_encode($result);
$conn->close();
?>
