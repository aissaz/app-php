<?php
include_once("../../config.php");
// en cas d'exception on retourne le code http 500 et un message a l'utilisateur
function myException($exception) {
    http_response_code(500);
    die("Exception : " . $exception->getMessage());
}
set_exception_handler('myException');

// select all facture
$sql = "SELECT * FROM facture";
$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);
    die(mysqli_error($conn));
}
$dbdata = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dbdata[] = $row;
    }
}

// output facture as json
echo json_encode($dbdata);

$conn->close();
?>
