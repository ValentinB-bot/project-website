<?php session_start(); ?>

<?php
// Vérifie si l'utilisateur a soumis le formulaire
if (isset($_POST['submit'])) {
	try
	{
		$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
		$user = 'root';
		$password = '';

		$dbh = new PDO($dsn, $user, $password);
	}
	catch(Exception $e){
		// On traite l'erreur
		$return = "Erreur de connéxion à la base de données: <br><br>$e";
	}
	//On récupère la clé SALT
	$req_get_salt = "SELECT * FROM salt WHERE nom='GENERAL_SALT'";
	$get_salt = $dbh->query($req_get_salt);
	if (!$get_salt){
		$return = "Erreur dans l'obtention de la clé SALT : <br><br>$req_get_salt";
	}
	$salt = $get_salt->fetch(PDO::FETCH_OBJ);
	if($salt){
		$final_salt = $salt->value;
	} else {
		$return = "La clé SALT n'a pas été trouvée dans la base de données.";
	}
	$get_salt->closeCursor();
	//Récupération de l'identifiant et du mot de passe entrée sur le site
	$enter_users = $_POST['username'];
	$enter_password = $_POST['password'];
	
	
	//On réalise la vérification, sécurisation de l'argument pour la requête.
	
	
	
	
	//1: On supprime tous les espaces dans le nom d'utilisateur
	$checker_users = trim($enter_users);
	$checker_password = trim($enter_password);
	//2: On vérifie que les champs utilisateur / mot de passe ne sont pas vides
	
	if(empty($checker_users)){
		$return = "Vous devez renseigner un nom d'utilisateur";
	} else if(empty($checker_password)){
		$return = "Vous devez renseigner un mot de passe.";
	} else {
		//3: On vérifie que les champs utilisateur / mot de passe ne contiennent pas certains elements de requêtes SQL pour se proteger des injéctions
		
		//On définie une liste de mots banni
		$banword1 = "SELECT";
		$banword2 = "DELETE";
		$banword3 = "INSERT";
		$banword4 = "UPDATE";
		$banword5 = ";";
		
		$checker = false;
		//On effectue nos vérifications
		
		if(strpos(strtoupper($enter_users), $banword1) !== false){
			$checker = true;
		}
		if(strpos(strtoupper($enter_users), $banword2) !== false){
			$checker = true;
		}
		if(strpos(strtoupper($enter_users), $banword3) !== false){
			$checker = true;
		}
		if(strpos(strtoupper($enter_users), $banword4) !== false){
			$checker = true;
		}
		if(strpos(strtoupper($enter_users), $banword5) !== false){
			$checker = true;
		}
		if(strpos(strtoupper($enter_password), $banword1) !== false){
			$checker = true;
		}
		if(strpos(strtoupper($enter_password), $banword2) !== false){
			$checker = true;
		}
		if(strpos(strtoupper($enter_password), $banword3) !== false){
			$checker = true;
		}
		if(strpos(strtoupper($enter_password), $banword4) !== false){
			$checker = true;
		}
		if(strpos(strtoupper($enter_password), $banword5) !== false){
			$checker = true;
		}
		if($checker == true){
			$return = "Erreur dans l'envoie de vos données";
		} else {
			
			// Encryptage du mot de passe
			$mdpcrypt = crypt($enter_password, $final_salt);

			// Ouverture de la session sur l'utilisateur entrer
			$req_login = "SELECT * FROM utilisateur WHERE login_utilisateur='$enter_users'";
			$str_login = $dbh->query($req_login);
			if (!$str_login){
				$return = "Erreur dans la requête : <br><br>$req_login";
			}
			$string_login = $str_login->fetch(PDO::FETCH_OBJ);
			if($string_login){
				if($string_login->mdp_utilisateur == $mdpcrypt){
					// Redirige l'utilisateur vers la page d'accueil s'il est authentifié (Reswitch entre les deux lignes com/pascom pour la création des cookies)
					//Ouverture d'une séssion ici, redirection vers la page d'accueil
					$perms_user = $string_login->IDRole;
					$nom_user = $string_login->nom_utilisateur;
					$prenom_user = $string_login->prenom_utilisateur;
					$idadresse_user = $string_login->IDAdresse;
					$_SESSION['LOGGED_USER'] = $enter_users;
					$_SESSION['PERMS_USER'] = $perms_user;
					$_SESSION['NOM_USER'] = $nom_user;
					$_SESSION['PRENOM_USER'] = $prenom_user;
					$_SESSION['IDADRESSE_USER'] = $idadresse_user;
					
					header('Location: index.php');
				} else {
					// Affiche un message d'erreur si les informations d'identification sont incorrectes
					$return = "Le mot de passe est incorrect";
				}
			} else {
				// Affiche un message d'erreur si les informations d'identification sont incorrectes
				$return = "Le nom d'utilisateur n'éxiste pas.";
			}
			$str_login->closeCursor();
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/login.css">
  <head>
	<link rel="shortcut icon" href="src/img/logo.jpg" type="image/x-icon">
	<meta charset="utf-8"/>
	<title>Stage à CESI'R - Login</title>
  </head>
  <body>
	<div class = box>
	  <img id="logo" src="src/img/logo_stageacesir.png" alt="logo">
	  <form method="post" action="">
		<br><input id="forms" type="text" id="username" name="username" placeholder="Identifiant*"><br>
		<input id="forms" type="password" id="password" name="password" placeholder="Mot de passe*"><br>
		<?php if (isset($return)) { ?>
            <label class = return>
				<center><p><?php echo $return; ?></p></center>
			</label>
        <?php } ?>
		<input type="submit" name="submit" value="Se connecter">
	  </form>
	</div>
  </body>
</html>