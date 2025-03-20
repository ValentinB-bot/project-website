<?php session_start(); ?>

<?php

if(isset($_POST["submitOffer"])) {
	//1. Vérification du texte reçu (max 3000 caractères, min 200 caractères)
	
	$enter_message = $_POST['message'];
	$checker_message = trim($enter_message);
	if(empty($checker_message)){
		$returnApplyOffer = "Vous devez envoyer un message";
	} else {
		
		$banword1 = "SELECT";
		$banword2 = "DELETE";
		$banword3 = "INSERT";
		$banword4 = "UPDATE";
		$banword5 = ";";
		$checker1 = false;
		if(strpos(strtoupper($enter_message), $banword1) !== false){
			$checker1 = true;}
		if(strpos(strtoupper($enter_message), $banword2) !== false){
			$checker1 = true;}
		if(strpos(strtoupper($enter_message), $banword3) !== false){
			$checker1 = true;}
		if(strpos(strtoupper($enter_message), $banword4) !== false){
			$checker1 = true;}
		if(strpos(strtoupper($enter_message), $banword5) !== false){
			$checker1 = true;}
		
		if($checker1 == true){
			$returnApplyOffer = "Erreur dans l'envoie de vos données";
		} else {
			
			$messageSize = strlen($enter_message);
			if($messageSize > 3000){
				$returnApplyOffer = "Votre message doit contenir moins de 3000 caractères";
			} else {
			
				//2. On vérifie si on à bien reçu les deux fichiers
				
				$target_dir = "src/files/";
				$nameOfCV = basename($_FILES["CV"]["name"]);
				$target_file_CV = $target_dir . basename($_FILES["CV"]["name"]);
				$imageFileType = strtolower(pathinfo($target_file_CV,PATHINFO_EXTENSION));
				$uploadCV = 1;
				if (empty($imageFileType)) {
					$returnApplyOffer = "Vous devez fournir votre CV";
				} else {
					// On vérifie si le fichier existe déjà quelquepart dans le dossier
					if (file_exists($target_file_CV)) {
						$returnApplyOffer = "Ce fichier existe déjà";
						$uploadCV = 0;
					} 
						//On contrôle la taille du fichier, maximum 5000kb ce qui est largement suffisant
					if ($_FILES["CV"]["size"] > 5000000) {
						$returnApplyOffer = "Votre fichier dépasse la taille maximale autorisée (5000KB)";
						$uploadCV = 0;
					}
					// On contrôle le format de l'image en n'autorisant que les fichiers pdf
					if($imageFileType != "pdf") {
						$returnApplyOffer = "Seuls les fichiers PDF sont autorisés.";
						$uploadCV = 0;
					}
					if($uploadCV == 1){
						
						
						
						$target_dir = "src/files/";
						$nameOfLM = basename($_FILES["LM"]["name"]);
						$target_file_LM = $target_dir . basename($_FILES["LM"]["name"]);
						$imageFileType = strtolower(pathinfo($target_file_LM,PATHINFO_EXTENSION));
						$uploadLM = 1;
						if (empty($imageFileType)) {
							$returnApplyOffer = "Vous devez fournir votre lettre de motivation";
						} else {
							// On vérifie si le fichier existe déjà quelquepart dans le dossier
							if (file_exists($target_file_LM)) {
								$returnApplyOffer = "Ce fichier existe déjà";
								$uploadLM = 0;
							} 
								//On contrôle la taille du fichier, maximum 5000kb ce qui est largement suffisant
							if ($_FILES["LM"]["size"] > 5000000) {
								$returnApplyOffer = "Votre fichier dépasse la taille maximale autorisée (5000KB)";
								$uploadLM = 0;
							}
							// On contrôle le format de l'image en n'autorisant que les fichiers pdf
							if($imageFileType != "pdf") {
								$returnApplyOffer = "Seuls les fichiers PDF sont autorisés.";
								$uploadLM = 0;
							}
							if($uploadLM == 1){
								
								//On vérifie que les deux fichiers sont différents
								if($nameOfLM == $nameOfCV){
									$returnApplyOffer = "Les deux fichiers doivent être différents";
								} else {
									
									//3. On upload les fichiers
									if (move_uploaded_file($_FILES["CV"]["tmp_name"], $target_file_CV)) {
										if (move_uploaded_file($_FILES["LM"]["tmp_name"], $target_file_LM)) {
											//4. On vérifie si l'utilisateur possède une candidature
											
											//on récupère l'ID de l'utilisateur et l'ID de l'offre
											try
											{
												$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
												$user = 'root';
												$password = '';

												$dbh = new PDO($dsn, $user, $password);
											}
											catch(Exception $e){

												$returnApplyOffer =  "Erreur de connexion à la base de données: <br><br>$e";;

											}
											$nameOfOffer = "NULL";
											if(isset($_COOKIE['applyOffer'])){
												$nameOfOffer = $_COOKIE['applyOffer'];
											}
											$query_get_IDOffer = "SELECT * FROM offre WHERE nom_offre='$nameOfOffer'";
											$get_IDOffer = $dbh->query($query_get_IDOffer);
															
											if (!$get_IDOffer){
												$returnApplyOffer =  "Erreur dans l'obtention de vos informations : <br><br>$query_get_IDOffer";
											}
											$Offer_obj = $get_IDOffer->fetch(PDO::FETCH_OBJ);
											if($Offer_obj){
												$passive_IDOffer = $Offer_obj->id;
												$IDOffer = intval($passive_IDOffer);
																
																
											} else {
												$checkerprofile = false;
											}
															
											$get_IDOffer->closeCursor();
															
											//On récupère l'ID de l'utilisateur
											$logged_user = $_SESSION['LOGGED_USER'];
											$query_get_IDUser = "SELECT * FROM utilisateur WHERE login_utilisateur='$logged_user'";
											$get_IDUser = $dbh->query($query_get_IDUser);
															
											if (!$get_IDUser){
												$returnApplyOffer =  "Erreur dans l'obtention de vos informations : <br><br>$query_get_IDUser";
											}
											$user_obj = $get_IDUser->fetch(PDO::FETCH_OBJ);
											if($user_obj){
												$passive_IDUser = $user_obj->id;
												$IDUser = intval($passive_IDUser);
																
																
											} else {
												$checkerprofile = false;
											}
															
											$get_IDUser->closeCursor();
											
											
											$query_isset_apply = "SELECT * FROM candidature WHERE IDUtilisateur='$IDUser' AND IDOffre='$IDOffer'";
											$isset_apply = $dbh->query($query_isset_apply);
											
											//5. On ajoute/modifie la candidature de l'utilisateur	
											
											if (!$isset_apply){
												$returnApplyOffer = "Erreur dans l'obtention de vos informations : <br><br>$query_isset_apply";
											}
											$str_isset_apply = $isset_apply->fetch(PDO::FETCH_OBJ);
											if($str_isset_apply){
												$query_update_apply = "UPDATE `candidature` SET `etat`='1', `file_cv`='$nameOfCV', `file_lm`='$nameOfLM', `texte`='$enter_message' WHERE IDUtilisateur = '$IDUser' AND IDOffre = '$IDOffer'";
												$update_apply = $dbh->query($query_update_apply);
												$update_apply->closeCursor();
											} else {
												$req_add_active = "INSERT INTO candidature (etat, effectue, note, file_cv, file_lm, IDUtilisateur, IDOffre, texte) VALUES ('1', '0', NULL, '$nameOfCV', '$nameOfLM', '$IDUser', '$IDOffer', '$enter_message')";
												$add_active = $dbh->query($req_add_active);
												$add_active->closeCursor();	
											}
															
											$isset_apply->closeCursor();
											
											
											//6. On détruit le cookie
											unset($_COOKIE['applyOffer']);	
											
											//7. On renvoie l'utilisateur sur la page des candidatures
											header('Location: NotificationsCandidatures.php');
										} else {
											$returnApplyOffer = "Impossible d'envoyer votre lettre de motivation";
										}
									} else {
										$returnApplyOffer = "Impossible d'envoyer votre CV";
									}
								}
							}
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


    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="raterater.css" rel="stylesheet"/>

    <script src="https://code.jquery.com/jquery-1.4.1.min.js"></script>
    <script src="raterater.jquery.js"></script>

	<link rel="manifest" href="manifest.json">
	<meta name="theme-color" content="#003366">
	<link rel="apple-touch-icon" sizes="180x180" href="/src/img/apple_touch_icon.png">

	<link rel="stylesheet" type="text/css" href="css/postuler.css">
    <title>Stage à CESI'R - Postuler</title>
    
    
    <?php if (!isset($_SESSION['LOGGED_USER'])) { 
		header('Location: index.php'); ?>
    <?php }else { ?>
		<?php if ($_SESSION['PERMS_USER'] == 2) {
			header('Location: index.php'); ?>
		<?php } ?>
		
		<a href="\unlog.php"><button class = login>Déconnexion</button></a>
		<a href="\profil.php"><button class = login>Mon Profil</button></a>
		
	<?php } ?>
    
    <?php 
		include 'global/navbar.php';
	?>
    


</head>

<body>
	<form method="post" action="" enctype="multipart/form-data">
	<?php
		$nameOfOffer = "NULL";
		if(isset($_COOKIE['applyOffer'])){
			$nameOfOffer = $_COOKIE['applyOffer'];
		}

        //Connexion à la base de données
        try
        {
            $dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
            $user = 'root';
            $password = '';

            $dbh = new PDO($dsn, $user, $password);
        }
        catch(Exception $e){

            echo 'Erreur';

        }
		
		//On vérifie si l'offre éxiste
		
		$query_get_offer = "SELECT * FROM offre WHERE nom_offre='$nameOfOffer'";
		$get_offer = $dbh->query($query_get_offer);
		if (!$get_offer){
			echo "Erreur dans l'obtention de l'offre : <br><br>$query_get_pilote";
			
		}
		$str_offer = $get_offer->fetch(PDO::FETCH_OBJ);
		if($str_offer){
			//On récupère les informations de l'offre
			$passive_IDAddressOffer = $str_offer->adresse_offre;
			$IDAddressOffer = intval($passive_IDAddressOffer);
			$passive_IDEnterpriseOffer = $str_offer->IDEntreprise;
			$IDEnterpriseOffer = intval($passive_IDEnterpriseOffer);
			$passive_IDSkillOffer = $str_offer->IDCompetence;
			$IDSkillOffer = intval($passive_IDSkillOffer);
			$printSalary = $str_offer->remuneration_horaire;
			$printDuration = $str_offer->duree_stage;
			$printStartDate = $str_offer->date_debut_stage;
			$printEndDate = $str_offer->date_fin_stage;
			
	
			
		} else {
			echo "Cette offre n'éxiste pas";
			die;
		}
		$get_offer->closeCursor();
		
		//On récupère le nom et l'image de l'entreprise
		
		$query_get_enterprise = "SELECT * FROM entreprise WHERE id='$IDEnterpriseOffer'";
		$get_enterprise = $dbh->query($query_get_enterprise);
		if (!$get_enterprise){
			echo "Erreur dans l'obtention de l'entreprise : <br><br>$query_get_enterprise";
			
		}
		$str_enterprise = $get_enterprise->fetch(PDO::FETCH_OBJ);
		$printEnterpriseName = $str_enterprise->nom_entreprise;
		$printEnterpriseLogo = $str_enterprise->link_logo;
		$get_enterprise->closeCursor();
		
		
		
		//On récupère la description de la compétence
		
		$query_get_skill = "SELECT * FROM competence WHERE id='$IDSkillOffer'";
		$get_skill = $dbh->query($query_get_skill);
		if (!$get_skill){
			echo "Erreur dans l'obtention de la compétence : <br><br>$query_get_skill";
			
		}
		$str_skill = $get_skill->fetch(PDO::FETCH_OBJ);
		$printDescSkill = $str_skill->description_competence;
		$get_skill->closeCursor();
		
		
		
		//On récupère l'adresse de l'offre
		
		$query_get_address = "SELECT * FROM adresse WHERE id='$IDAddressOffer'";
		$get_address = $dbh->query($query_get_address);
		if (!$get_address){
			echo "Erreur dans l'obtention de l'adresse : <br><br>$query_get_address";
			
		}
		$str_address = $get_address->fetch(PDO::FETCH_OBJ);
		
		
		
		$pays = $str_address->ad_pays;
		$cp = $str_address->ad_cp;
		$ville = $str_address->ad_ville;
		$rue = $str_address->ad_rue;
		$numero = $str_address->ad_numero;
		
		$printAddress = "$numero $rue, $cp $ville, $pays";
		
		
		$get_address->closeCursor();
		
		
		
		//On affiche les informations de l'offre

        ?>
       
	   <center>
		<?php if (isset($returnApplyOffer)) { ?>
			<label class = return>
				<p><?php echo $returnApplyOffer; ?></p>
			</label>
		<?php } ?>
		</center>
	   
       <div class="info-entreprise">
            <?php echo "<img src='src/upload/$printEnterpriseLogo' alt='LogoEntreprise' style='grid-column: 2;margin-left: -25%;'>"; ?>
            <label for="" style="grid-column: 2;margin-top: 7%;"><?php echo "$printEnterpriseName";?></label>
            <label for="" style="grid-column: 2;margin-bottom: 5%;"><?php echo "$nameOfOffer";?></label>
            <label for="" style="grid-column: 2;margin-bottom: 5%;"><?php echo "$printDescSkill";?></label>
            <label for="" style="grid-column: 2;margin-bottom: 5%;"><?php echo "$printAddress";?></label>
            <label for="" style="grid-column: 2;margin-bottom: 5%;"><?php echo "$printStartDate - $printEndDate";?></label>
            <label for="" style="grid-column: 2;margin-bottom: 5%;"><?php echo "Durée du stage: $printDuration semaines";?></label>
            <label for="" style="grid-column: 2;"><?php echo "Rémunération horaire: $printSalary/h";?></label>

       </div>
        <div class = "message">
            <label for="" style="font-size: 150%;font-weight: bolder;margin-bottom: 5%;">Votre message</label>
            <textarea name="message" placeholder="Exprimez vos motivations.  Maximum 3000 caractères." id="" cols="30" rows="10" style="grid-row: 2;"></textarea>
            <label for="" style="grid-row: 3;grid-column: 1;padding-top: 4.5%;">CV</label>
            <input name="CV" accept=".pdf" type="file" value="CV" for="fileToUpload" style="grid-row: 3;grid-column: 1;width: 30%;margin-top: 5%;padding-bottom: 2.5%;padding-top: 2.5%;place-self: flex-start;">
            <label for="" style="grid-row: 3;grid-column: 1;margin-bottom: 11%;place-self: flex-end;">Lettre de motivation</label>
            <input name="LM" accept=".pdf" type="file" value="Lettre de motivation" style="grid-row: 3;grid-column: 1;width: 30%;margin-top: 5%;margin-bottom: 5%;padding-top: 2.5%;padding-bottom: 2.5%;place-self: flex-end;">
            <input type="submit" value="Envoyer" name="submitOffer" style="width: 20%;padding-bottom: 2.5%;padding-top: 2.5%;">
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