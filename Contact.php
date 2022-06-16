



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/jpeg" href="utils/img/favicon.jpg">
	<title>Xavier Legall | Contact</title>
</head>
<body>
	<?php
	include("utils/structure/header.html");
	?>

	
</body>
</html>



<?php
/*
	********************************************************************************************
	CONFIGURATION
	********************************************************************************************
*/
// expéditeur du dormulaire. Pour des raisons de sécurité, de plus en plus d'hébergeurs imposent que ce soit une adresse sur votre hébergement/nom de domaine.
// Par exemple si vous mettez ce script sur votre site "test-site.com", mettez votre email @test-site.com comme expéditeur (par exemple contact@test-site.com)
// Si vous ne changez pas cette variable, vous risquez de ne pas recevoir de formulaire.




$email_expediteur = 'rechiram94@gmail.com';
$nom_expediteur = 'Contact Photographie Xavier Legall';
 
ini_set('SMTP','smtp.free.fr');
// destinataire est votre adresse mail. Pour envoyer à plusieurs à la fois, séparez-les par un point-virgule
$destinataire = 'rechiram94@gmail.com';
 
// copie ? (envoie une copie au visiteur)
$copie = 'non';
 
// Action du formulaire (si votre page a des paramètres dans l'URL)
// si cette page est index.php?page=contact alors mettez index.php?page=contact
// sinon, laissez vide
$form_action = '';
 
// Messages de confirmation du mail
$message_envoye = "✅ Votre message à bien été envoyé !";
$message_non_envoye = "❌ L'envoi du mail a échoué, veuillez réessayer SVP.";
 
// Message d'erreur du formulaire
$message_formulaire_invalide = "❌ Vérifiez que tous les champs soient bien remplis et que l'email soit sans erreur.";
 
/*
	********************************************************************************************
	FIN DE LA CONFIGURATION
	********************************************************************************************
*/
 
/*
 * cette fonction sert à nettoyer et enregistrer un texte
 */
function Rec($text)
{
	$text = htmlspecialchars(trim($text), ENT_QUOTES);
	if (1 === get_magic_quotes_gpc())
	{
		$text = stripslashes($text);
	}
 
	$text = nl2br($text);
	return $text;
};
 
/*
 * Cette fonction sert à vérifier la syntaxe d'un email
 */
function IsEmail($email)
{
	$value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
	return (($value === 0) || ($value === false)) ? false : true;
}
 
// formulaire envoyé, on récupère tous les champs.
$nom     = (isset($_POST['nom']))     ? Rec($_POST['nom'])     : '';
$email   = (isset($_POST['email']))   ? Rec($_POST['email'])   : '';
$objet   = (isset($_POST['objet']))   ? Rec($_POST['objet'])   : '';
$message = (isset($_POST['message'])) ? Rec($_POST['message']) : '';

 
// On va vérifier les variables et l'email ...
$email = (IsEmail($email)) ? $email : ''; // soit l'email est vide si erroné, soit il vaut l'email entré



$err_formulaire = false; // sert pour remplir le formulaire en cas d'erreur si besoin
 
if (isset($_POST['envoi']))
{
	if (($nom != '') && ($email != '') && ($objet != '') && ($message != ''))
	{
		$message = "Message envoyé automatiquement depuis la page de contact :\n\n".$message."\n\n\n\nDe la part de ".$email; 
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'From:'.$nom_expediteur.' <'.$email_expediteur.'>' . "\r\n" .
				'Reply-To:'.$email. "\r\n" .
				'Content-Type: text/plain; charset="utf-8"; DelSp="Yes"; format=flowed '."\r\n" .
				'Content-Disposition: inline'. "\r\n" .
				'Content-Transfer-Encoding: 7bit'." \r\n" .
				'X-Mailer:PHP/'.phpversion();
 
		// envoyer une copie au visiteur ?
		if ($copie == 'oui')
		{
			$cible = $destinataire.';'.$email;
		}
		else
		{
			$cible = $destinataire;
		};
		
		// Remplacement de certains caractères spéciaux
		$caracteres_speciaux     = array('&#039;', '&#8217;', '&quot;', '<br>', '<br />', '&lt;', '&gt;', '&amp;', '…',   '&rsquo;', '&lsquo;');
		$caracteres_remplacement = array("'",      "'",        '"',      '',    '',       '<',    '>',    '&',     '...', '>>',      '<<'     );
 
		$objet = html_entity_decode($objet);
		$objet = str_replace($caracteres_speciaux, $caracteres_remplacement, $objet);
 
		
		$message = html_entity_decode($message);
		$message = str_replace($caracteres_speciaux, $caracteres_remplacement, $message);
 
		
		// Envoi du mail
		$cible = str_replace(',', ';', $cible); // antibug : j'ai vu plein de forums où ce script était mis, les gens ne font pas attention à ce détail parfois
		$num_emails = 0;
		$tmp = explode(';', $cible);
		foreach($tmp as $email_destinataire)
		{
			if (mail($email_destinataire, $objet, $message, $headers))
				$num_emails++;
		}
 
		if ((($copie == 'oui') && ($num_emails == 2)) || (($copie == 'non') && ($num_emails == 1)))
		{
			echo '<p id="envoie">'.$message_envoye.'</p>';
		}
		else
		{
			echo '<p id="non-envoie">'.$message_non_envoye.'</p>';
		};
	}
	else
	{
		// une des 3 variables (ou plus) est vide ...
		echo '<p id="erreur">'.$message_formulaire_invalide.'</p>';
		$err_formulaire = true;
	};
}; // fin du if (!isset($_POST['envoi']))
 
if (($err_formulaire) || (!isset($_POST['envoi'])))

{

	// afficher le formulaire
	echo '

	<div class="background">
		<div class="container">
			<div class="screen">
				<div class="screen-header">
					<div class="screen-header-left">
						<div class="screen-header-button close"></div>
						<div class="screen-header-button maximize"></div>
						<div class="screen-header-button minimize"></div>
					</div>
					<div class="screen-header-right">
						<div class="screen-header-ellipsis"></div>
						<div class="screen-header-ellipsis"></div>
						<div class="screen-header-ellipsis"></div>
					</div>
				</div>
				<div class="screen-body">
					<div class="screen-body-item left">
						<div class="app-title">
							<span>ME</span>
							<span>CONTACTER</span>
						</div>
					</div>
					<form id="contact" method="post" action="'.$form_action.'">
						<div class="screen-body-item">
							<div class="app-form">
								<div class="app-form-group">
									<input class="app-form-control" type="text" id="nom" name="nom" value="'.stripslashes($nom).'" placeholder="Nom" />
								</div>
								<div class="app-form-group">
									<input type="text" class="app-form-control" id="email" name="email" value="'.stripslashes($email).'" placeholder="Email" />
								</div>
								<div class="app-form-group">
									<input class="app-form-control" type="text" id="objet" name="objet" value="'.stripslashes($objet).'" placeholder="Objet" />
								</div>
								<div class="app-form-group message">
									<textarea class="app-form-control" id="message" name="message" cols="20" rows="5" placeholder="Message" >'.stripslashes($message).'</textarea>
								</div>
								<div class="app-form-group buttons">
									<button class="app-form-button">ANNULER</button>
									<input class="app-form-button" type="submit" name="envoi" id="envoyer" value="ENVOYER" />
								</div>
							</div>
						</div>
					</form>	
				</div>
			</div>
		</div>
	</div>';

};

?>

<style>
	*, *:before, *:after {
  box-sizing: border-box;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}



body, button, input {
  font-family: 'Montserrat', sans-serif;
  font-weight: 100;
  letter-spacing: 1.4px;
}

.background {
  display: flex;
	margin-top: 150px;
}

.container {
  flex: 0 1 700px;
  margin: auto;
  padding: 10px;
}

.screen {
  position: relative;
  background: #3e3e3e;
  border-radius: 15px;
}

.screen:after {
  content: '';
  display: block;
  position: absolute;
  top: 0;
  left: 20px;
  right: 20px;
  bottom: 0;
  border-radius: 15px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, .4);
  z-index: -1;
}

#envoie{	
	margin-top: 50px;
		text-align: center;
		font-size: 25px;
		color: green;
		font-weight: 1000;
}	

#non-envoie{	
	margin-top: 50px;
		text-align: center;
		font-size: 25px;
		color: red;
		font-weight: 1000;
}	

#erreur{	
		margin-top: 50px;
		text-align: center;
		font-size: 25px;
		color: red;
		font-weight: 1000;
}	


.screen-header {
  display: flex;
  align-items: center;
  padding: 10px 20px;
  background: #4d4d4f;
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
}

.screen-header-left {
  margin-right: auto;
}

.screen-header-button {
  display: inline-block;
  width: 8px;
  height: 8px;
  margin-right: 3px;
  border-radius: 8px;
  background: white;
}

.screen-header-button.close {
  background: red;
}

.screen-header-button.maximize {
  background: #e8e925;
}

.screen-header-button.minimize {
  background: #74c54f;
}

.screen-header-right {
  display: flex;
}

.screen-header-ellipsis {
  width: 3px;
  height: 3px;
  margin-left: 2px;
  border-radius: 8px;
  background: #999;
}

.screen-body {
  display: flex;
}

.screen-body-item {
  flex: 1;
  padding: 50px;
}

.screen-body-item.left {
  display: flex;
  flex-direction: column;
}

.app-title {
  display: flex;
  flex-direction: column;
  position: relative;
  color: white;
  font-size: 26px;
}

.app-title:after {
  content: '';
  display: block;
  position: absolute;
  left: 0;
  bottom: -10px;
  width: 25px;
  height: 4px;
  background: white;
}

.app-contact {
  margin-top: auto;
  font-size: 8px;
  color: #888;
}

.app-form-group {
  margin-bottom: 15px;
}


.app-form-group.buttons {
  margin-bottom: 0;
  text-align: right;
}

.app-form-control {
  width: 100%;
  padding: 10px 0;
  background: none;
  border: none;
  border-bottom: 1px solid #666;
  color: #ddd;
  font-size: 14px;
  text-transform: uppercase;
  outline: none;
  transition: border-color .2s;
}

.app-form-control::placeholder {
  color: #666;
}

.app-form-control:focus {
  border-bottom-color: #ddd;
}

.app-form-button {
  background: none;
  border: none;
  color: white;
  font-size: 14px;
  cursor: pointer;
  outline: none;
}

.app-form-button{
	transition: 0.3s
}

.app-form-button:hover {
  color: rgb(133, 133, 133);
}



.credits-link {
  display: flex;
  align-items: center;
  color: #fff;
  font-weight: bold;
  text-decoration: none;
}

.dribbble {
  width: 20px;
  height: 20px;
  margin: 0 5px;
}

@media screen and (max-width: 520px) {
  .screen-body {
    flex-direction: column;
  }

  .screen-body-item.left {
    margin-bottom: 30px;
  }

  .app-title {
    flex-direction: row;
  }

  .app-title span {
    margin-right: 12px;
  }

  .app-title:after {
    display: none;
  }
}

@media screen and (max-width: 600px) {
  .screen-body {
    padding: 40px;
  }

  .screen-body-item {
    padding: 0;
  }
	.background {
  display: flex;
	margin-top: 0px;
}
}

</style>

<?php
    include("utils/structure/footer.php")
  ?>
