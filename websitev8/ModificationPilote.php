<?php session_start(); ?>

<?php
if(isset($_POST["editPilote"])) {
	//Modification d'un pilote
	
	//1. On vérifie que tous les champs texte sont pleins, et qu'ils ne contiennent pas de caractères interdits
	
	$enter_nom = $_POST['nom'];
	$enter_prenom = $_POST['prenom'];
	$enter_password = $_POST['password'];
	$enter_centre = $_POST['centre'];
	$enter_promotion_1 = $_POST['promotion1'];
	$enter_promotion_2 = $_POST['promotion2'];
	$enter_promotion_3 = $_POST['promotion3'];
	
	$checker_nom = trim($enter_nom);
	$checker_prenom = trim($enter_prenom);
	$checker_password = trim($enter_password);
	$checker_centre = trim($enter_centre);
	$checker_promotion_1 = trim($enter_promotion_1);
	$checker_promotion_2 = trim($enter_promotion_2);
	$checker_promotion_3 = trim($enter_promotion_3);
	
	$checker1 = false;
	
	if(empty($checker_promotion_1)){
		$returnEditPilote = "Vous devez au moins renseigner une promotion du pilote";	
		$checker1 = true;}
	if(empty($checker_centre)){
		$returnEditPilote = "Vous devez renseigner le centre CESI du pilote";
		$checker1 = true;}		
	if(empty($checker_prenom)){
		$returnEditPilote = "Vous devez renseigner le prénom du pilote";
		$checker1 = true;}
	if(empty($checker_nom)){
		$returnEditPilote = "Vous devez renseigner le nom du pilote";
		$checker1 = true;}
	if($checker1 == false){
		//On réalise les vérifications pour éviter les injéctions sql
		$banword1 = "SELECT";
		$banword2 = "DELETE";
		$banword3 = "INSERT";
		$banword4 = "UPDATE";
		$banword5 = ";";
		$checker2 = false;
		if(strpos(strtoupper($checker_nom), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_nom), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_nom), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_nom), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_nom), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_prenom), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_prenom), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_prenom), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_prenom), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_prenom), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_password), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_password), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_password), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_password), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_password), $banword5) !== false){
			$checker2 = true;}
			
		if($checker2 == true){
			$returnEditPilote = "Erreur dans l'envoie de vos données";
		} else {
	
			//2. On compte le nombre de promotion reçu
			
			if(empty($checker_promotion_2)){
				$amountOfPromo = 1;
			} else {
				if(empty($checker_promotion_3)){
					$amountOfPromo = 2;
				} else {
					$amountOfPromo = 3;
				}
			}
			
			
			
			//3. On vérifie que deux promotions ne sont pas les mêmes
			
			$checker3 = false;
			if($amountOfPromo == 1){
				$checker3 = true;
			} else if($amountOfPromo == 2){
				if($checker_promotion_1 == $checker_promotion_2){
					$returnEditPilote = "Les promotions 1 et 2 ne peuvent pas être les mêmes";
					$checker3 = false;
				} else {
					$checker3 = true;
				}
			} else if($amountOfPromo == 3){
				if($checker_promotion_1 == $checker_promotion_2){
					$returnEditPilote = "Les promotions 1 et 2 ne peuvent pas être les mêmes";
					$checker3 = false;
				} else {
					if($checker_promotion_2 == $checker_promotion_3){
						$returnEditPilote = "Les promotions 2 et 3 ne peuvent pas être les mêmes";
						$checker3 = false;
					} else {
						if($checker_promotion_1 == $checker_promotion_3){
							$returnEditPilote = "Les promotions 1 et 3 ne peuvent pas être les mêmes";
							$checker3 = false;
						} else {
							$checker3 = true;
						}
					}
				}
			}
							
			if($checker3 == true){
				
				try
				{
					$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
					$user = 'root';
					$password = '';

					$dbh = new PDO($dsn, $user, $password);
				}
				catch(Exception $e){
					// On traite l'erreur
					$returnEditPilote = "Erreur de connexion à la base de données: <br><br>$e";
					$checker4 = false;
				}
				
				
				
				
				//4. On vérifie si un mot de passe est reçu > 8 caractères > On l'encrypte
				
				$checker4 = true;
				if(empty($checker_password)){
					$hasReceivePassword = false;
				} else {
					$hasReceivePassword = true;
					//On réalise les vérifications
					$check_mdp = strlen($checker_password);
					if($check_mdp >= 8){
						$req_get_salt = "SELECT * FROM salt WHERE nom='GENERAL_SALT'";
						$get_salt = $dbh->query($req_get_salt);
						if (!$get_salt){
							$returnEditPilote = "Erreur dans l'obtention de la clé SALT : <br><br>$req_get_salt";
							$checker4 = false;
						}
						$salt = $get_salt->fetch(PDO::FETCH_OBJ);
						if($salt){
							$final_salt = $salt->value;
						} else {
							$returnEditPilote = "La clé SALT n'a pas été trouvée dans la base de données.";
							$checker4 = false;
						}
						$get_salt->closeCursor();	
						//On encrypte le mot de passe
						$encrypt_mdp = crypt($checker_password, $final_salt);
						
						
						
						
					} else {
						$returnEditPilote = "Le mot de passe doit contenir au moins 8 caractères";
						$checker4 = false;
					}
					
					
					
				}
				if($checker4 == true){
					
					//5. On vérifie si une image est reçu > Vérification sur l'image
					$hasReceiveImage = false;
			
					$target_dir = "src/upload/";
					$nameOfFile = basename($_FILES["fileToUpload"]["name"]);
					$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
					$uploadOk = 1;
					$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
					if (!empty($imageFileType)) {
						$hasReceiveImage = true;
						//Si c'est le cas on effectue les vérifications dessus
						
						$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
						if($check !== false) {
							$uploadOk = 1;
						} else {
							$returnEditPilote = "Ce fichier n'est pas une image";
							$uploadOk = 0;
						}
							// On vérifie si le fichier existe déjà quelque part dans le dossier
						if (file_exists($target_file)) {
							$returnEditPilote = "Ce fichier existe déjà";
							$uploadOk = 0;
						}
							//On contrôle la taille du fichier, maximum 5000kb ce qui est largement suffisant
						if ($_FILES["fileToUpload"]["size"] > 5000000) {
							$returnEditPilote = "Votre fichier dépasse la taille maximale autorisée (5000KB)";
							$uploadOk = 0;
						}
						// On contrôle le format de l'image en n'autorisant que les fichiers jpg, png et jpeg
						if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
							$returnEditPilote = "Seuls les fichiers PNG, JPG et JPEG sont autorisés.";
							$uploadOk = 0;
						}
					}
					
					if($uploadOk == 1){
						
						//6. On récupère l'ID de l'utilisateur
						
						$usernamePilote = "NULL";
						if(isset($_COOKIE['editPilote'])){
							$usernamePilote = $_COOKIE['editPilote'];
						}
						
						$req_get_utilisateurid = "SELECT * FROM utilisateur WHERE login_utilisateur = '$usernamePilote'";
						$get_utilisateurid = $dbh->query($req_get_utilisateurid);
						if (!$get_utilisateurid){
							$returnEditPilote = "Erreur dans la requête : <br><br>$req_get_utilisateurid";
						}
						$string_get_utilisateurid = $get_utilisateurid->fetch(PDO::FETCH_OBJ);
						$passive_IDUtilisateur = $string_get_utilisateurid->id;
						$IDUtilisateur = intval($passive_IDUtilisateur);
						$get_utilisateurid->closeCursor();
								
					
						
						//7. Si on à une image: On supprime l'ancienne image, on upload l'image et on modifie le link_logo uniquement dans l'utilisateur
						
						$imgpass = true;
						if($hasReceiveImage == true){
							
							//Si on à une image
							$checker5 = false;
							$imgpass = false;
							
							//On commence par récupérer l'ancienne image de profil
							
							$req_get_photo = "SELECT * FROM utilisateur WHERE id='$IDUtilisateur'";
							$get_photo = $dbh->query($req_get_photo);
							
							if (!$get_photo){
								$returnEditPilote = "Erreur dans l'obtention de vos informations : <br><br>$req_get_photo";
							}
							$user_obj = $get_photo->fetch(PDO::FETCH_OBJ);
							if($user_obj){
								$link_logo = $user_obj->link_logo;
							} else {
								$checkerprofilenpp = false;
							}
							
							$get_photo->closeCursor();
							
							//On vérifie si l'utilisateur possède une image de profil
							if($link_logo){
								//On supprime cette image du fichier
								
								$fichier_link = "src/upload/$link_logo";
								if(unlink($fichier_link)){
									$checker5 = true;		
								} else {
									$returnEditPilote = "Impossible de remplacer votre image de profil sur le serveur. Merci de réessayer.";
								}
							} else {
								$checker5 = true;	
							}
							
							if($checker5 == true){

								//On upload le nouveau fichier
								$checker6 = false;
								
								if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
									$checker6 = true;
								}
								
								
								if($checker6 == true){
									//On modifie le lien link_logo en base de données
									
									$req_change_pp = "UPDATE `utilisateur` SET `link_logo`='$nameOfFile' WHERE id = '$IDUtilisateur'";
									$change_pp = $dbh->query($req_change_pp);
									$change_pp->closeCursor();
									$imgpass = true;
								}
								
							}
							
						} 
						if($imgpass == true){
						
							//8. On modifie directement le nom, le prénom et le centre
							
							$req_change_utilisateur = "UPDATE `utilisateur` SET `nom_utilisateur`='$checker_nom', `prenom_utilisateur`='$checker_prenom', `IDAdresse`='$enter_centre' WHERE id = '$IDUtilisateur'";
							$change_utilisateur = $dbh->query($req_change_utilisateur);
							$change_utilisateur->closeCursor();
							
							//9. Si un mot de passe est reçu: On modifie le mot de passe
							
							if($hasReceivePassword == true){
								
								$req_change_utilisateur_password = "UPDATE `utilisateur` SET `mdp_utilisateur`='$encrypt_mdp' WHERE id = '$IDUtilisateur'";
								$change_utilisateur_password = $dbh->query($req_change_utilisateur_password);
								$change_utilisateur_password->closeCursor();
								
							}
							
							//10. On désactive toutes les liaison utilisateur_promotion de cette utilisateur
							
							$controller = false;
							$ID = 1;
							while($controller == false){
								
								$req_get_promotionlink = "SELECT * FROM utilisateur_promotion WHERE id='$ID'";
								$get_promotionlink = $dbh->query($req_get_promotionlink);
								if (!$get_promotionlink){
									echo "Erreur dans l'obtention des link utilisateur promotion : <br>$req_get_promotionlink\n";
									die;
								}
								$str_promotionlink = $get_promotionlink->fetch(PDO::FETCH_OBJ);
								if(!$str_promotionlink) {
									$get_promotionlink->closeCursor();
									$controller = true;
													
								} else {
									//On vérifie si active est sur 1:
									$active = $str_promotionlink->active;
									if($active == 1) {
									
										//On récupère les id d'utilisateur
										$passive_IDLink = $str_promotionlink->IDUtilisateur;
										$IDLink = intval($passive_IDLink);
										//On test l'id en cours avec l'id de l'utilisateur
										if($IDLink == $IDUtilisateur){
											//Si c'est la même, alors on désactive l'ID de link ($ID)

											$req_disable_promotionlink = "UPDATE `utilisateur_promotion` SET `active`='0', `IDUtilisateur`=NULL, `IDPromotion`=NULL  WHERE id = '$ID'";
											$disable_promotionlink = $dbh->query($req_disable_promotionlink);
											$disable_promotionlink->closeCursor();
											
										}	
											
									} 
									$get_promotionlink->closeCursor();
									$ID++;			
								}
							}
							
							//11. On insère les nouveaux liens de promotion dans la table utilisateur_promotion
							
							if($amountOfPromo >= 1){
										
										
								$req_add_link_userpromo = "INSERT INTO utilisateur_promotion (IDUtilisateur, IDPromotion, active) VALUES ('$IDUtilisateur', '$enter_promotion_1', 1)";
								$add_link_userpromo = $dbh->query($req_add_link_userpromo);
								if (!$add_link_userpromo){
									$returnEditPilote = "Erreur dans la requête : <br><br>$req_add_link_userpromo";
								}
								$add_link_userpromo->closeCursor();
							}
							
							if($amountOfPromo >= 2){
										
										
								$req_add_link_userpromo = "INSERT INTO utilisateur_promotion (IDUtilisateur, IDPromotion, active) VALUES ('$IDUtilisateur', '$enter_promotion_2', 1)";
								$add_link_userpromo = $dbh->query($req_add_link_userpromo);
								if (!$add_link_userpromo){
									$returnEditPilote = "Erreur dans la requête : <br><br>$req_add_link_userpromo";
								}
								$add_link_userpromo->closeCursor();
							}
							
							if($amountOfPromo >= 3){
										
										
								$req_add_link_userpromo = "INSERT INTO utilisateur_promotion (IDUtilisateur, IDPromotion, active) VALUES ('$IDUtilisateur', '$enter_promotion_3', 1)";
								$add_link_userpromo = $dbh->query($req_add_link_userpromo);
								if (!$add_link_userpromo){
									$returnEditPilote = "Erreur dans la requête : <br><br>$req_add_link_userpromo";
								}
								$add_link_userpromo->closeCursor();
							}
							
							unset($_COOKIE['editPilote']);	
							header('Location: RecherchePilote.php');
							
						}
					}
				}
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
	<link rel="stylesheet" type="text/css" href="css/modificationpilote.css">
	<link rel="manifest" href="manifest.json">
	<meta name="theme-color" content="#003366">
	<link rel="apple-touch-icon" sizes="180x180" href="/src/img/apple_touch_icon.png">
    <title>Stage à CESI'R - Pilote</title>
    
    
	<?php if (!isset($_SESSION['LOGGED_USER'])) { 
		header('Location: index.php'); ?>
    <?php }else { ?>
		<?php if ($_SESSION['PERMS_USER'] == 1) {
			header('Location: index.php'); ?>
		<?php } else if ($_SESSION['PERMS_USER'] == 2) {	
			header('Location: index.php'); ?>
		<?php } ?>	
			<a href="unlog.php"><button class = login>Déconnexion</button></a>
			<a href="profil.php"><button class = login>Mon Profil</button></a>
		
	<?php } ?>
	
    
    <?php 
		include 'global/navbar.php';
	?>
    


</head>
<body>
	<form method="post" action="" enctype="multipart/form-data">
	
		<center>
		<?php if (isset($returnEditPilote)) { ?>
			<label class = return>
				<p><?php echo $returnEditPilote; ?></p>
			</label>
		<?php } ?>
		</center>
		
		
		
		
		<?php
		//On récupère les informations du pilote
		
		$usernamePilote = "NULL";
		if(isset($_COOKIE['editPilote'])){
			$usernamePilote = $_COOKIE['editPilote'];
		}
		
		
		
		//Récupération des informations
					
		try
		{
			$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
			$user = 'root';
			$password = '';

			$dbh = new PDO($dsn, $user, $password);
		}
		catch(Exception $e){
			// On traite l'erreur
			$returnCreateOffer = "Erreur de connexion à la base de données: <br><br>$e";
		}
		$req_get_pilote = "SELECT * FROM utilisateur WHERE login_utilisateur='$usernamePilote'";
		$get_pilote = $dbh->query($req_get_pilote);
		if (!$get_pilote){
			echo "Erreur dans l'obtention du pilote : <br><br>$req_get_pilote";
			die;
		}
		$str_pilote = $get_pilote->fetch(PDO::FETCH_OBJ);
		if($str_pilote){
			$passive_IDPilote = $str_pilote->id;
			$IDPilote = intval($passive_IDPilote);
			$nomPilote = $str_pilote->nom_utilisateur;
			$prenomPilote = $str_pilote->prenom_utilisateur;
			$passive_IDCentre = $str_pilote->IDAdresse;
			$IDCentre = intval($passive_IDCentre);
			$link_logo = $str_pilote->link_logo;
			if($link_logo){
				$checkerprofile = true;
			} else {
				$checkerprofile = false;
			}
						
		} else {
			echo "L'utilisateur n'est pas valide.";
			die;
		}
		$get_pilote->closeCursor();
		
		?>
		
		
		
		
		<div class = content >
            <div class = photoprofile>
				
                <?php 
				if($checkerprofile == false){
					//Aucune photo de profil trouvée
					?><img class = profilphoto src="src/img/nopp.png" alt="Profile Image" style='max-width: 150px;'><?php 
				} else {
					//Photo de profil trouvée en bdd, affichage de celle-ci
					echo "<img class = profilphoto src='src/upload/".($link_logo)."' style='max-width: 150px;'>";
				}
				
				?>
                <input class = inputfilemessage type="file" name="fileToUpload" id="fileToUpload" accept="image/*"/>
               
            </div>
            <div class = input_info>
                <div>
                    <?php echo "<label for=''>Nom : <input name='nom' type='text' value='".($nomPilote)."' style='color: grey;'></label>"; ?>
                    
                </div>
                <div style="margin-top: 5%;">
                    <?php echo " <label for=''>Prénom : <input name='prenom' type='text' value='".($prenomPilote)."' style='color: grey;'></label>"; ?>
                    
                </div>
                <div style="margin-top: 5%;">
                    <label for="">Username : <?php echo "$usernamePilote"; ?></label>
                    
                </div>
				<div style="margin-top: 5%;">
                    <label for="">Mot de passe : <input name="password" type="text" placeholder="Nouveau mot de passe" style="color: grey;"></label>
                    
                </div>
				
				<div style="margin-top: 5%;">
                    <label for="">Centre : 
						<select name="centre" id="pet-select" style="color: grey;">
							<option value="">Choisissez un centre</option>
							
							<?php 
							//Affichage des options de la liste déroulante des centres CESI
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
							//2. On récupère la liste des centres
							$controller = false;
							$ID = 1;
							while($controller == false){
								$req_get_centres = "SELECT * FROM adresse WHERE id='$ID'";
								$get_centres = $dbh->query($req_get_centres);
								if (!$get_centres){
									echo "Erreur dans l'obtention des promotions : <br>$req_get_adresse\n";
									die;
								}
								$str_centres = $get_centres->fetch(PDO::FETCH_OBJ);
								if(!$str_centres) {
									$get_centres->closeCursor();
									$controller = true;
									
								} else {
									$centre = $str_centres->ad_ville;
									$checkIsCentre = "centre_";
									if(strpos($centre, $checkIsCentre) !== false){
										$affcentreexplode = explode("_",$centre);
										$affcentre = $affcentreexplode[1];
										
										if($ID == $IDCentre){
											echo "<option selected value='".($ID)."'>".($affcentre)." </option>";
										} else {
											echo "<option value='".($ID)."'>".($affcentre)."</option>";
										}
										$get_centres->closeCursor();
									}
									$ID++;
									
								}
							}
							?>
						</select>
					</label>
                </div>
				
				<?php
				//On compte le nombre de promotions de l'utilisateur depuis la table utilisateur_promotion
				
				
				
				$controller = false;
				$ID = 1;
				$amountOfPromotionPilote = 0;
				while($controller == false){
					
					$req_get_promotionlink = "SELECT * FROM utilisateur_promotion WHERE id='$ID'";
					$get_promotionlink = $dbh->query($req_get_promotionlink);
					if (!$get_promotionlink){
						echo "Erreur dans l'obtention des link utilisateur promotion : <br>$req_get_promotionlink\n";
						die;
					}
					$str_promotionlink = $get_promotionlink->fetch(PDO::FETCH_OBJ);
					if(!$str_promotionlink) {
						$get_promotionlink->closeCursor();
						$controller = true;
										
					} else {
						
						$active = $str_promotionlink->active;
						if($active == 1) {
						
							//On récupère les id d'utilisateur
							$passive_IDUtilisateur = $str_promotionlink->IDUtilisateur;
							$IDUtilisateur = intval($passive_IDUtilisateur);
							//On test l'id en cours avec l'id de l'utilisateur
							if($IDUtilisateur == $IDPilote){
								//Si c'est la même, alors on récupère l'ID de la promotion
								
								$amountOfPromotionPilote++;
								
								$passive_IDPromotion = $str_promotionlink->IDPromotion;
								$IDPromotion = intval($passive_IDPromotion);
								
								//On va aller chercher les informations de la promotion pour les stocker
								
								$req_get_promotion = "SELECT * FROM promotion WHERE id='$IDPromotion'";
								$get_promotion = $dbh->query($req_get_promotion);
								if (!$get_promotion){
									echo "Erreur dans l'obtention des promotions : <br>$req_get_promotion\n";
									die;
								}
								$str_promotion = $get_promotion->fetch(PDO::FETCH_OBJ);
								//Stockage des informations de promotion
								
								if($amountOfPromotionPilote == 1){

									$id_promotion_1 = $str_promotion->id;
									
									
								} else if($amountOfPromotionPilote == 2){
									
									$id_promotion_2 = $str_promotion->id;
									
								} else if($amountOfPromotionPilote == 3){
									
									$id_promotion_3 = $str_promotion->id;
									
								}

								$get_promotionlink->closeCursor();

							}

							$ID++;	
							$get_promotionlink->closeCursor();
						} else {
							$ID++;	
							$get_promotionlink->closeCursor();
						}
						
					}
				}
				
				
				
				?>
				
				<div style="margin-top: 5%;">
                    <label for="">Promotion n°1: 
						<select name="promotion1" id="pet-select" style="color: grey;">
							<option value="">Choisissez une promotion*</option>
							
							<?php 
							//Affichage des options de la liste déroulante des promotions
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
							//2. On récupère la liste des promotions
							$controller = false;
							$ID = 1;
							while($controller == false){
								$req_get_promotions = "SELECT * FROM promotion WHERE id='$ID'";
								$get_promotions = $dbh->query($req_get_promotions);
								if (!$get_promotions){
									echo "Erreur dans l'obtention des promotions : <br>$req_get_adresse\n";
									die;
								}
								$str_promotions = $get_promotions->fetch(PDO::FETCH_OBJ);
								if(!$str_promotions) {
									$controller = true;
									
								} else {
									$promo = $str_promotions->nom_promotion;
									
									
									if($ID == $id_promotion_1){
										echo "<option selected value='".($ID)."'>".($promo)." </option>";
									} else {
										echo "<option value='".($ID)."'>".($promo)."</option>";
									}
									
									$get_promotions->closeCursor();
									$ID++;
								}
							}
							?>
						</select>
					</label>
                </div>
				<div style="margin-top: 5%;">
                    <label for="">Promotion n°2: 
						<select name="promotion2" id="pet-select" style="color: grey;">
							<option value="">Choisissez une promotion</option>
							
							<?php 
							//Affichage des options de la liste déroulante des promotions
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
							//2. On récupère la liste des promotions
							$controller = false;
							$ID = 1;
							while($controller == false){
								$req_get_promotions = "SELECT * FROM promotion WHERE id='$ID'";
								$get_promotions = $dbh->query($req_get_promotions);
								if (!$get_promotions){
									echo "Erreur dans l'obtention des promotions : <br>$req_get_adresse\n";
									die;
								}
								$str_promotions = $get_promotions->fetch(PDO::FETCH_OBJ);
								if(!$str_promotions) {
									$controller = true;
									
								} else {
									$promo = $str_promotions->nom_promotion;
				
									
									if($amountOfPromotionPilote >= 2){
										if($ID == $id_promotion_2){
											echo "<option selected value='".($ID)."'>".($promo)." </option>";
										} else {
											echo "<option value='".($ID)."'>".($promo)."</option>";
										}
									} else {
										echo "<option value='".($ID)."'>".($promo)."</option>";
									}
									
									$get_promotions->closeCursor();
									$ID++;
								}
							}
							?>
						</select>
					</label>
                </div>
				<div style="margin-top: 5%;">
                    <label for="">Promotion n°3: 
						<select name="promotion3" id="pet-select" style="color: grey;">
							<option value="">Choisissez une promotion</option>
							
							<?php 
							//Affichage des options de la liste déroulante des promotions
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
							//2. On récupère la liste des promotions
							$controller = false;
							$ID = 1;
							while($controller == false){
								$req_get_promotions = "SELECT * FROM promotion WHERE id='$ID'";
								$get_promotions = $dbh->query($req_get_promotions);
								if (!$get_promotions){
									echo "Erreur dans l'obtention des promotions : <br>$req_get_adresse\n";
									die;
								}
								$str_promotions = $get_promotions->fetch(PDO::FETCH_OBJ);
								if(!$str_promotions) {
									$controller = true;
									
								} else {
									$promo = $str_promotions->nom_promotion;
				
									
									if($amountOfPromotionPilote >= 3){
										if($ID == $id_promotion_3){
											echo "<option selected value='".($ID)."'>".($promo)." </option>";
										} else {
											echo "<option value='".($ID)."'>".($promo)."</option>";
										}
									} else {
										echo "<option value='".($ID)."'>".($promo)."</option>";
									}
									
									$get_promotions->closeCursor();
									$ID++;
								}
							}
							?>
						</select>
					</label>
                </div>
                
            </div>
            <div  class = input_add>
                <input type="submit" name="editPilote" value="Valider" style="padding: 20px 50px;">
            </div>
		</div>
	</form>
</body>
<?php include 'global/footer.php'; ?>
</html>


<script>
	if('serviceWorker' in navigator){
    navigator.serviceWorker.register('ServiceWorker.js')
    .then( (sw) => console.log('Le Service Worker a été enregistrer', sw))
    .catch((err) => console.log('Le Service Worker est introuvable !!!', err));
   }
</script>