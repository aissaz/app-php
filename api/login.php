<?php
// en cas d'exception on retourne le code http 500 et un message a l'utilisateur
function myException($exception) {
    http_response_code(500);
    die("Exception : " . $exception->getMessage());
}
set_exception_handler('myException');
include_once("../config.php");

// demarrage de la session
session_start();

// si le user est déja connecté  on fait rien
if (isset($_SESSION["user_name"])) {
    http_response_code(200);
} // si non et qu'il y a des information dans le formulaire
else if (count($_POST) > 0) {

    // on verifie en base de donnée le user
    $result = mysqli_query($conn, "SELECT * FROM client 
                            WHERE  num='" . $_POST["user_name"] . "' 
                                and  mot_passe = '" . $_POST["password"] . "'");
    $row = mysqli_fetch_array($result);
    // si il y a eu un resultat en db pour ce user alors on ajouter ces information dans la session
    if (is_array($row)) {
        $_SESSION["user_name"] = $row['num'];
        $_SESSION["name"] = $row['nom'];
        http_response_code(200);

    } // si non on retourne non autoriser a l'utilisateur
    else {
        http_response_code(403);
        die("Invalid Username or Password!");
    }
}
// dans le cas ou le formulaire est vide
//on retourne non autoriser a l'utilisateur
else {
    http_response_code(403);
    die("Invalid Username or Password!");
}
