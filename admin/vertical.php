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
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link href="admin.css" rel="stylesheet">
  <link rel="shortcut icon" type="image/jpeg" href="../utils/img/favicon.jpg">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xavier Legall | Upload Vertical</title>
</head>
<body>
 <?php
  include("header.html")
?>


<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Configuration

// Chemin d'accès relatif vers le répertoire d'upload
$conf_repertoire_horizontal = "../utils/upload/vertical/";


// Extensions autorisées
$conf_extensions = array('pdf','jpg','png');

// Mime autoriées
$conf_mime = array('image/jpeg','image/png');

// Taille maximum des fichiers en octets
$conf_taille_max = 50000000;

// Nom du fichier sur le serveur
// 0 = nom d'origine
// 1 = nom généré de manière aléatoire
// 2 = nom fixe
$conf_nom_type = 1;

// Si choix 2, quel est le nom prédéfini
$conf_nom_fixe = "fichier";

// Quelques fonctions utiles

// Fonction pour générer un nom de fichier non prédictible, au hasard
function hasard(int $longueur = 64 ,string $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string 
{
  if ($longueur < 1) 
  {
    throw new \RangeException("La longueur doit être positive");
  }
  $pieces = [];
  $max = mb_strlen($caracteres, '8bit') - 1;
  for ($i = 0; $i < $longueur; ++$i) 
  {
    $pieces [] = $caracteres[random_int(0, $max)];
  }
  return implode('', $pieces);
}

// Fonction de sécurisation du nom du fichier
function securisation_fichier($fichier) 
{
  $fichier = trim($fichier);
  $fichier = stripslashes($fichier);
  $fichier = htmlspecialchars($fichier);
  return $fichier;
}

// On définit les variables nécessaires
$erreur = "";
$succes = "";

// Traitement et upload du fichier
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if ((isset($_FILES["fichier"])) && ($_FILES['fichier']['error'] == UPLOAD_ERR_OK))
  {
    $fichier_nom_origine = $_FILES['fichier']['name'];  
    $fichier_nom_temporaire = $_FILES['fichier']['tmp_name'];
    $fichier_extension= strtolower(pathinfo($fichier_nom_origine, PATHINFO_EXTENSION));
    $fichier_mime = mime_content_type($_FILES['fichier']['tmp_name']);
    
    if (is_uploaded_file($fichier_nom_temporaire)) 
    {
      if ($conf_nom_type == 0)
      {
        $fichier_nom_definitif = securisation_fichier($fichier_nom_origine);
      }
      else if ($conf_nom_type == 1)
      {
        $fichier_nom_definitif = securisation_fichier(hasard());
      }
      else if ($conf_nom_type == 2)
      {
        $fichier_nom_definitif = securisation_fichier($conf_nom_fixe);
      }
      else $erreur .= "Nom du fichier non défini<br>";
      
      if (!in_array($fichier_extension, $conf_extensions))
      { 
        $erreur .= "Extension du fichier non valide<br>";
      }
    
      if (!in_array($fichier_mime, $conf_mime)) 
      {
        $erreur .= "Mime du fichier non valide<br>";
      }
      
      if ((($_FILES['fichier']['size']) > $conf_taille_max) || (($_FILES['fichier']['size']) < 0))
      { 
        $erreur .= "Taille du fichier non valide<br>";
      }
        
      if (!is_dir(dirname($conf_repertoire_horizontal)))
      {
        $erreur .= "Le répertoire de destination n'existe pas<br>";
      }

      if (!is_writable(dirname($conf_repertoire_horizontal)))
      {
        $erreur .= "Le répertoire de destination ne dispose pas des droits en écriture<br>";
      }
      
      if (file_exists($conf_repertoire_horizontal.$fichier_nom_definitif.".".$fichier_extension)) 
      {
        $erreur .= "Un fichier avec le même nom existe déja<br>";
      }
    }
    
    if ($erreur == "")
    {
      if ($conf_nom_type == 0)
      {

          if (move_uploaded_file($fichier_nom_temporaire, $conf_repertoire_horizontal.$fichier_nom_definitif))
          {
            $succes .= "✅ Fichier envoyé avec succès";
          }
          }
          else 
          {
          if (move_uploaded_file($fichier_nom_temporaire, $conf_repertoire_horizontal.$fichier_nom_definitif.".".$fichier_extension))
          {
            $succes .= "✅ Fichier envoyé avec succès";
          }

        
      }
    }
  }
}
?>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <input type="file" name="fichier" required>
    <input type="submit" value="Upload"required >
    
    
  </form>
  <p><?php echo $erreur.$succes;?></p>


<style>
  input{
    text-align: center;
    font-size: 30px;
    font-family: 'Josefin Sans', sans-serif;
    font-weight: 1000;
  }
  form{
    text-align: center;
    margin-top: 180px;
    margin-bottom: 450px;
  }
  p{
    text-align: center;
    font-size: 30px;
    font-family: 'Josefin Sans', sans-serif;
    font-weight: 1000;
    text-align: center;
  }
</style>  



<?php
  include("../utils/structure/footer.php")
?>


</body>
</html>
