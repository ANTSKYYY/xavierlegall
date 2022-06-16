<?php
// Informations d'identification
define('DB_SERVER', '45.90.163.115');
define('DB_USERNAME', 'u3_J30EFHJY0I');
define('DB_PASSWORD', '.ZhCyF+QqpaF6F+yZo4!XgW5');
define('DB_NAME', 's3_xavierlegall');
 
// Connexion � la base de donn�es MySQL 
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// V�rifier la connexion
if($conn === false){
    die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
}
?>
