<?php session_start(); ?>

<?php 
//CHANGEMENT DE MOT DE PASSE
//On vérifie que le formulaire de changement de mot de passe a été soumis
if (isset($_POST['changemdp'])) {
	//Récupération des champs
	$npassword1 = $_POST['npassword1'];
	$npassword2 = $_POST['npassword2'];
	//On retire les espaces en début et fin de champs
	$checker_npassword1 = trim($npassword1);
	$checker_npassword2 = trim($npassword2);
	//On vérifie que les champs ne sont pas vide
	if(empty($checker_npassword1)){
		$return = "Vous devez renseigner un mot de passe.";
	} else if(empty($checker_npassword2)){
		$return = "Vous devez confirmer votre mot de passe.";
	} else {
		//On vérifie que les 2 champs sont égaux pour n'avoir à effectuer les vérifications que sur l'un des deux
		if($npassword1 == $npassword2){
				//On vérifie que les champs ne contiennent pas certains éléments de requêtes SQL pour se protéger des injections
			//On définit une liste de mots bannis
			$banword1 = "SELECT";
			$banword2 = "DELETE";
			$banword3 = "INSERT";
			$banword4 = "UPDATE";
			$banword5 = ";";
			
			$checker = false;
			//On effectue nos vérifications
			
			if(strpos(strtoupper($npassword1), $banword1) !== false){
				$checker = true;
			}
			if(strpos(strtoupper($npassword1), $banword2) !== false){
				$checker = true;
			}
			if(strpos(strtoupper($npassword1), $banword3) !== false){
				$checker = true;
			}
			if(strpos(strtoupper($npassword1), $banword4) !== false){
				$checker = true;
			}
			if(strpos(strtoupper($npassword1), $banword5) !== false){
				$checker = true;
			}
			if($checker == true){
				$return = "Erreur dans l'envoi de vos données";
			} else {
				//Force du mot de passe: minimum 8 caractères,
				$check_mdp = strlen($npassword1);
				if($check_mdp >= 8){
					
					//Connexion à la base de données
					
					try
					{
						$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
						$user = 'root';
						$password = '';

						$dbh = new PDO($dsn, $user, $password);
					}
					catch(Exception $e){
						// On traite l'erreur
						$return = "Erreur de connexion à la base de données: <br><br>$e";
					}
					
					//Récupération de la clé SALT
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
					
					//Encryptage du nouveau mot de passe
					$encrypt_mdp = crypt($npassword1, $final_salt);
					
					//Modification éffective du mot de passe
					$logged_user = $_SESSION['LOGGED_USER'];
					$req_change_mdp = "UPDATE `utilisateur` SET `mdp_utilisateur`='$encrypt_mdp' WHERE login_utilisateur = '$logged_user'";
					$change_mdp = $dbh->query($req_change_mdp);
					$change_mdp->closeCursor();
					
					//On vérifie que le mot de passe à bien été modifiée
					$req_get_newmdp = "SELECT * FROM utilisateur WHERE login_utilisateur='$logged_user'";
					$get_newmdp = $dbh->query($req_get_newmdp);
					
					if (!$get_newmdp){
						$return = "Erreur dans la vérification du nouveau mot de passe : <br><br>$req_get_salt";
					}
					$newmdp = $get_newmdp->fetch(PDO::FETCH_OBJ);
					if($newmdp){
						$newpassword = $newmdp->mdp_utilisateur;
						if($newpassword == $encrypt_mdp){
							$return = "Le mot de passe a bien été mis à jour.<br>Nouveau mot de passe: $npassword1";
						} else {
							$return = "Le mot de passe n'a pas pu être mis à jour.";
						}
					} else {
						$return = "Le mot de passe n'a pas pu être mis à jour.";
					}
					
					$get_newmdp->closeCursor();
					
				} else {
					$return = "Le mot de passe doit contenir au minimum 8 caractères.";
				}
			}
		} else {
			$return = "Les deux mots de passe ne correspondent pas.";
		}
	}
}

?>

<?php
//MODIFICATION DE LA PHOTO DE PROFIL

// On vérifie si le fichier est bien une image
if(isset($_POST["changepp"])) {

	
		
	$target_dir = "src/upload/";
	$nameOfFile = basename($_FILES["fileToUpload"]["name"]);
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if (empty($imageFileType)) {
		$ppreturn = "Vous devez fournir une image";
		
	} else {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$ppreturn = "Ce fichier n'est pas une image";
			$uploadOk = 0;
		}
		// On vérifie si le fichier existe déjà quelquepart dans le dossier
		if (file_exists($target_file)) {
			$ppreturn = "Ce fichier existe déjà";
			$uploadOk = 0;
		}
		//On contrôle la taille du fichier, maximum 5000kb ce qui est largement suffisant
		if ($_FILES["fileToUpload"]["size"] > 5000000) {
			$ppreturn = "Votre fichier dépasse la taille maximale autorisée (5000KB)";
			$uploadOk = 0;
		}
		// On contrôle le format de l'image en n'autorisant que les fichiers jpg, png et jpeg
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
			$ppreturn = "Seuls les fichiers PNG, JPG et JPEG sont autorisés.";
			$uploadOk = 0;
		}
		
		
		
		if($uploadOk == 1){
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				
				//Connexion à la base de données
				try
				{
					$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
					$user = 'root';
					$password = '';

					$dbh = new PDO($dsn, $user, $password);
				}
				catch(Exception $e){
					// On traite l'erreur
					$ppreturn = "Erreur de connexion à la base de données: <br><br>$e";
				}
				
				//Récupération de l'ancienne image de profil dans la base de données
				$logged_user = $_SESSION['LOGGED_USER'];
				$req_get_photo = "SELECT * FROM utilisateur WHERE login_utilisateur='$logged_user'";
				$get_photo = $dbh->query($req_get_photo);
				
				if (!$get_photo){
					$ppreturn = "Erreur dans l'obtention de vos informations : <br><br>$req_get_salt";
				}
				$user_obj = $get_photo->fetch(PDO::FETCH_OBJ);
				if($user_obj){
					$link_logo = $user_obj->link_logo;
					if($link_logo){
						$checkerprofilenpp = true;
					} else {
						$checkerprofilenpp = false;
					}
				} else {
					$checkerprofilenpp = false;
				}
				
				$get_photo->closeCursor();
				
				//Supression de l'ancienne image de profil des fichiers serveurs
				if($checkerprofilenpp == true){
					$fichier_link = "src/upload/$link_logo";
					if(isset($fichier_link)){
						if(unlink($fichier_link)){
							
						} else {
							$ppreturn = "Impossible de remplacer votre image de profil sur le serveur. Merci de réessayer.";
						}
					}
				}
				//Mise à jour du lien du fichier de l'image de profil dans la base de données
				
				$logged_user = $_SESSION['LOGGED_USER'];
				$req_change_pp = "UPDATE `utilisateur` SET `link_logo`='$nameOfFile' WHERE login_utilisateur = '$logged_user'";
				$change_pp = $dbh->query($req_change_pp);
				$change_pp->closeCursor();
				$ppreturn = "Votre image de profil a bien été mise à jour.";
				
			} else {
				$ppreturn = "Un problème est survenu dans l'envoi de votre fichier";
			}
		}
	}
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
		include 'global/header.php';
	?>
	<link rel="stylesheet" type="text/css" href="css/profil.css? v=6">
    <title>Stage à CESI'R - Profil</title>
    
	
	
	<?php if (!isset($_SESSION['LOGGED_USER'])) {
		header('Location: index.php'); ?>
    <?php }else { ?>
		<a href="unlog.php"><button class = login>Déconnexion</button></a>
		<a href="index.php"><button class = login>Accueil</button></a>
	<?php } ?>
	
	
	
	<?php 
		include 'global/navbar.php';
	?>
    
	
	
</head>
<body>
	<center>
		<label class = profilName> <?php echo $_SESSION['NOM_USER'], " ", $_SESSION['PRENOM_USER']; ?> </label>
		<br>
		
		<?php 
			//Première étape: récupération de l'adresse du centre en fonction de la super globale $_SESSION['IDADRESSE_USER']
			//Connexion à la base de données
			try {
				$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
				$user = 'root';
				$password = '';

				$dbh = new PDO($dsn, $user, $password);
			} catch(Exception $e){
				// On traite l'erreur
				echo 'Erreur de connexion à la base de données:<br>', $e;
				die;
			}
			
			
			//Récupération de l'adresse
			$idsession = $_SESSION['IDADRESSE_USER'];
			$req_get_adresse = "SELECT * FROM adresse WHERE id='$idsession'";
			$get_adresse = $dbh->query($req_get_adresse);
			if (!$get_adresse){
				echo "Erreur dans l'obtention de l'adresse : <br>$req_get_adresse\n";
				die;
			}
			$adresse = $get_adresse->fetch(PDO::FETCH_OBJ);
			if($adresse){
				$ad_numero = $adresse->ad_numero;
				$ad_rue = $adresse->ad_rue;
				
				$get_ville = $adresse->ad_ville;
				$affcentreexplode = explode("_",$get_ville);
				$ad_ville = $affcentreexplode[1];
				
				
				
				
				$ad_cp = $adresse->ad_cp;
				$ad_pays = $adresse->ad_pays;
			} else {
				echo "L'adresse n'a pas été trouvée dans la base de données.";
				die;
			}
			$get_adresse->closeCursor();
			//Récupération de la photo de profil de l'utilisateur
			$logged_user = $_SESSION['LOGGED_USER'];
			$req_get_photo = "SELECT * FROM utilisateur WHERE login_utilisateur='$logged_user'";
			$get_photo = $dbh->query($req_get_photo);
			
			if (!$get_photo){
				$return = "Erreur dans l'obtention de votre photo de profil : <br><br>$req_get_photo";
			}
			$user_obj = $get_photo->fetch(PDO::FETCH_OBJ);
			if($user_obj){
				$link_logo = $user_obj->link_logo;
				if($link_logo){
					$checkerprofile = true;
				} else {
					$checkerprofile = false;
				}
				
			} else {
				$checkerprofile = false;
			}
			
			$get_photo->closeCursor();
		?>
		
		<div class = pp>
		<br>
		<?php 
		//Affichage de la photo de profil
			if($checkerprofile == false){
				//Aucune photo de profil trouvée
				?><img class = profilphoto src="src/img/nopp.png" alt="Profile Image"><?php 
			} else {
				//Photo de profil trouvée en bdd, affichage de celle-ci
				echo "<img class = profilphoto src='src/upload/".($link_logo)."'>";
			}
		?>
		<br>
		</div>
		
		<br>
		<br>
		<label class = profil>Votre rôle: </label>
		<?php if ($_SESSION['PERMS_USER'] == 1) { ?>
			<label class = etudiant>Etudiant</label>
		<?php } else if ($_SESSION['PERMS_USER'] == 2) { ?>
			<label class = pilote>Pilote de promotion</label>
		<?php } else if ($_SESSION['PERMS_USER'] == 3) { ?>
			<label class = admin>Administrateur</label>
		<?php } ?>
		<br>
		<label class = usernameMessage>Connecté en tant que: </label>
		<label class = usernameLogger> <?php echo $_SESSION['LOGGED_USER']; ?> </label>
		<br>
		<br>
		<label class = profil>Mon centre CESI</label>
		<br>
		<br>
		<label class = usernameMessage><?php 
			//Affichage final de l'adresse du centre
			echo $ad_numero, " ", $ad_rue, "<br>";
			echo $ad_cp, " ", $ad_ville, "<br>";
			echo $ad_pays;?>
		
		</label>
		
		<?php if ($_SESSION['PERMS_USER'] == 3) {
		} else {?>
		
		<br>
		<br>
		

		<label class = profil>Promotion(s): </label>

		
		
		<?php
		
		//1. Connexion à la base de données
		try {
			$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
			$user = 'root';
			$password = '';

			$dbh = new PDO($dsn, $user, $password);
		} catch(Exception $e){
			// On traite l'erreur
			echo 'Erreur de connexion à la base de données:<br>', $e;
			die;
		}
		
		//2. Récupération l'ID de l'utilisateur
		$logged_user = $_SESSION['LOGGED_USER'];
		
		$req_get_iduser = "SELECT * FROM utilisateur WHERE login_utilisateur='$logged_user'";
		$get_iduser = $dbh->query($req_get_iduser);
			
		if (!$get_iduser){
			$return = "Erreur dans l'obtention de votre photo de profil : <br><br>$req_get_iduser";
		}
		$str_get_iduser = $get_iduser->fetch(PDO::FETCH_OBJ);
		$passive_IDUser = $str_get_iduser->id;
		$IDUser = intval($passive_IDUser);
		$get_iduser->closeCursor();
		
		//3. Affichage des promotions
		
		$controller = false;
		$ID = 1;
		while($controller == false){
			
			$req_get_promotions = "SELECT * FROM utilisateur_promotion WHERE id='$ID'";
			$get_promotions = $dbh->query($req_get_promotions);
			if (!$get_promotions){
				echo "Erreur dans l'obtention des promotions : <br>$req_get_promotions\n";
				die;
			}
			$str_promotions = $get_promotions->fetch(PDO::FETCH_OBJ);
			if(!$str_promotions) {
				$get_promotions->closeCursor();
				$controller = true;
								
			} else {
	
				//On récupère les id d'utilisateur
				$passive_IDLink = $str_promotions->IDUtilisateur;
				$IDLink = intval($passive_IDLink);
				//On test l'id en cours avec l'id de l'utilisateur
				if($IDLink == $IDUser){
					//Si c'est la même, alors on récupère l'ID de promotion
					$passive_IDPromotion = $str_promotions->IDPromotion;
					$IDPromotion = intval($passive_IDPromotion);
					//On va aller chercher le nom de la promotion puis on l'affiche
					$req_get_promotionname = "SELECT * FROM promotion WHERE id='$IDPromotion'";
					$get_promotionname = $dbh->query($req_get_promotionname);
					if (!$get_promotionname){
						echo "Erreur dans l'obtention des promotions : <br>$req_get_promotionname\n";
						die;
					}
					$str_promotionname = $get_promotionname->fetch(PDO::FETCH_OBJ);
					$promotionName = $str_promotionname->nom_promotion;
					
					$get_promotionname->closeCursor();
					//On affiche finalement la promotion de l'utilisateur
					?>
					<br><label class = usernameMessage><?php 
						
						echo "$promotionName";
					
					?></label>
					
					<?php
					
					
				}

				$get_promotions->closeCursor();
				$ID++;	
							
			}
		}
		
		?>
		<?php } ?>
		<br>
		<br>
		<label class = profil>Modification du mot de passe</label>
		<div class = box>
			<form method="post" action="">
				<input id="forms" type="password" id="npassword1" name="npassword1" placeholder="Nouveau mot de passe*"><br>
				<input id="forms" type="password" id="npassword2" name="npassword2" placeholder="Confirmation*"><br>
				
				<?php if (isset($return)) { ?>
					<label class = return>
						<center><p><?php echo $return; ?></p></center>
					</label>
				<?php } ?>
				<br><input type="submit" name="changemdp" value="Confirmer">
			</form>
		</div><br><br><br>
		
		<label class = profil>Modification de la photo de profil</label>
		
		<div class = box>
			<form method="post" action="" enctype="multipart/form-data">
				
				
				<br><input class = inputfilemessage type="file" name="fileToUpload" id="fileToUpload" accept="image/*"/>
				<?php if (isset($ppreturn)) { ?>
					<label class = return>
						<center><p><?php echo $ppreturn; ?></p></center>
					</label>
				<?php } ?>
				
				<br><input type="submit" name="changepp" value="Envoyer">
			</form>
		</div><br><br><br>
		
		
		<br>
		<br>
	</center>
</body>
	<?php include 'global/footer.php'; ?>
</html>
