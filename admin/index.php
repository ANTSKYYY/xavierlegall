<?php
	// Initialiser la session
	session_start();
	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
	if(!isset($_SESSION["username"])){
		header("Location: login.php");
		exit(); 
	}
?>
<!DOCTYPE html>
<html>
	<head>
	<link rel="stylesheet" href="style.css" />
  <link href="admin.css" rel="stylesheet">
  <link rel="shortcut icon" type="image/jpeg" href="../utils/img/favicon.jpg">
  <title>Xavier Legall | Admin</title>
	</head>
	<body>
		<div class="sucess">
		<h1>Bienvenue <?php echo $_SESSION['username']; ?>!</h1>
		<p>Choisissez entre upload une photo à l'horizontal ou a la verticale</p>
		<a href="logout.php">Déconnexion</a>
		</div>
    <a href="horizontal.php"><img src="../utils/img/horizontal.JPG" alt="image1" style="display:inline-block; margin-left: 5%;"/> <!-- Image à gauche --></a>
    <a href="vertical.php"><img href="vertical.php" src="../utils/img/vertical.JPG" alt="image2" style="display:inline-block; margin-left: 20%;"/> <!-- Image à droite --></a>
	</body>
</html>