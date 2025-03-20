<?php session_start(); ?>

<?php

if(isset($_POST["editOffre"])) {
	//Modification de l'offre
	
	//1. Vérification de tous les champs texte (au moins une adresse complète)
	
	$IDEntreprise = $_POST['entreprise'];
	$IDCompetence = $_POST['competence'];
	$IDPromotion = $_POST['promotion'];
	$enter_place = $_POST['place'];
	$enter_duree = $_POST['duree'];
	$enter_remuneration = $_POST['remuneration'];
	$enter_date_debut = $_POST['date_debut'];
	$enter_adresse = $_POST['adresse'];
	
	$checker_entreprise = trim($IDEntreprise);
	$checker_competence = trim($IDCompetence);
	$checker_promotion = trim($IDPromotion);
	$checker_place = trim($enter_place);
	$checker_duree = trim($enter_duree);
	$checker_remuneration = trim($enter_remuneration);
	$checker_date_debut = trim($enter_date_debut);
	$checker_adresse = trim($enter_adresse);
	
	$checker1 = false;
	
	if(empty($checker_date_debut)){
		$returnEditOffer = "Vous devez renseigner la date de début du stage";	
		$checker1 = true;}
	if(empty($checker_remuneration)){
		$returnEditOffer = "Vous devez renseigner la rémunération horaire";	
		$checker1 = true;}
	if(empty($checker_duree)){
		$returnEditOffer = "Vous devez renseigner la durée du stage en semaine";	
		$checker1 = true;}
	if(empty($checker_place)){
		$returnEditOffer = "Vous devez renseigner le nombre de place";	
		$checker1 = true;}
	if(empty($checker_promotion)){
		$returnEditOffer = "Vous devez renseigner la promotion";	
		$checker1 = true;}
	if(empty($checker_competence)){
		$returnEditOffer = "Vous devez renseigner la compétence du stage";	
		$checker1 = true;}
	if(empty($checker_adresse)){
		$returnEditOffer = "Vous devez renseigner l'adresse dans laquelle se déroulera le stage";	
		$checker1 = true;}
	if(empty($checker_entreprise)){
		$returnEditOffer = "Vous devez renseigner l'entreprise";	
		$checker1 = true;}
	
	if($checker1 == false){
		//On réalise les vérifications anti-injéction sql sur les champs texte
		
		$banword1 = "SELECT";
		$banword2 = "DELETE";
		$banword3 = "INSERT";
		$banword4 = "UPDATE";
		$banword5 = ";";
		$checker2 = false;
		
		
		if(strpos(strtoupper($checker_place), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_place), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_place), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_place), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_place), $banword5) !== false){
			$checker2 = true;}
		
		if(strpos(strtoupper($checker_duree), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_duree), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_duree), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_duree), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_duree), $banword5) !== false){
			$checker2 = true;}
		
		if(strpos(strtoupper($checker_remuneration), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_remuneration), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_remuneration), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_remuneration), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_remuneration), $banword5) !== false){
			$checker2 = true;}
		
		if(strpos(strtoupper($checker_adresse), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_adresse), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_adresse), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_adresse), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_adresse), $banword5) !== false){
			$checker2 = true;}

		if($checker2 == true){
			$returnEditOffer = "Erreur dans l'envoi de vos données";
		} else {
			

			//2. On vérifie que la date de début n'est pas dépasse
			
			$timespan_date_debut = strtotime($enter_date_debut);
			$timespan_date_now = time();
				
			if($timespan_date_now > $timespan_date_debut){
				$returnEditOffer = "La date de début du stage ne doit pas être dépassée";
			} else {
				
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
					$returnEditOffer = "Erreur de connexion à la base de données: <br><br>$e";
				}
				
				//3. On calcule la date de fin grâce à la durée en semaine (retirer 3 jours pour faire correspondre)
				
				//On calcule le timespan de fin du stage auquel on retire 
				$timespan_date_fin_calc = $timespan_date_debut + ($enter_duree * 604800);
					
				//On retire trois jours à la date de fin du stage (Lundi -3 jours = Vendredi, jour de fin du stage)
				$timespan_date_fin_final = $timespan_date_fin_calc - 259200;
					
				//On convertit ce timespan en date
				$date_finstage = date( "Y-m-d", $timespan_date_fin_final );
				
				//4. On Récupère la date actuelle
				$date_now = date( "Y-m-d", $timespan_date_now );
				
				
				
				//5. On récupère l'ID de l'offre
				
				$nomOffre = "NULL";
				if(isset($_COOKIE['editOffre'])){
					$nomOffre = $_COOKIE['editOffre'];
				}
				
				
				$req_get_id_offre = "SELECT * FROM offre WHERE nom_offre='$nomOffre'";
				$get_id_offre = $dbh->query($req_get_id_offre);
				if (!$get_id_offre){
					$returnEditOffer = "Erreur dans l'obtention de l'offre : <br><br>$req_get_id_offre";
					
				}
				$str_id_offre = $get_id_offre->fetch(PDO::FETCH_OBJ);
				if($str_id_offre){
					$passive_IDOffre = $str_id_offre->id;
					$IDOffre = intval($passive_IDOffre);
				} else {
					$returnEditOffer = "L'ID de l'offre n'as pas été trouvé";
					
				}
				$get_id_offre->closeCursor();
				
				
				
				
				//6. On vérifie que l'adresse est bien une adresse de l'entreprise
					
					
				$checker3 = false;
				$controller = false;
				$ID = 1;
				while($controller == false){
										
					$req_get_entrepriselink = "SELECT * FROM entreprise_adresse WHERE id='$ID'";
					$get_entrepriselink = $dbh->query($req_get_entrepriselink);
					if (!$get_entrepriselink){
						$error = "Erreur dans l'obtention des link entreprise adresse : <br>$req_get_promotionlink\n";
					}
					$str_entrepriselink = $get_entrepriselink->fetch(PDO::FETCH_OBJ);
					if(!$str_entrepriselink) {
						$get_entrepriselink->closeCursor();
						$controller = true;
															
					} else {
						//On vérifie si active est sur 1:
						$active = $str_entrepriselink->active;
						if($active == 1) {
							//On vérifie si l'ID d'adresse est le bon
							
							$passive_IDLink = $str_entrepriselink->IDAdresse;
							$IDLink = intval($passive_IDLink);
							
							if($IDLink == $enter_adresse){
								//On vérifie si l'ID d'entreprise associé est le bon
								$passive_IDLink = $str_entrepriselink->IDEntreprise;
								$IDLink = intval($passive_IDLink);
								if($IDLink == $IDEntreprise){
									$checker3 = true;
									$get_entrepriselink->closeCursor();
									$controller = true;
								}
							}
						}
						$get_entrepriselink->closeCursor();
						$ID++;				
					}
				}
				if($checker3 == true){
				
				
				
					//7. On modifie l'offre
					
					$req_edit_offer = "UPDATE `offre` SET `adresse_offre`='$enter_adresse', `remuneration_horaire`='$enter_remuneration', `date_poste_offre`='$date_now', `places_offre`='$enter_place', `duree_stage`='$enter_duree', `date_debut_stage`='$enter_date_debut', `date_fin_stage`='$date_finstage', `IDEntreprise`='$IDEntreprise', `IDCompetence`='$IDCompetence', `IDPromotion`='$IDPromotion' WHERE id = '$IDOffre' ";
					$edit_offer = $dbh->query($req_edit_offer);
					if (!$edit_offer){
						$returnEditOffer = "Erreur dans la requête : <br><br>$req_edit_offer";
					}
					$edit_offer->closeCursor();
									
					$returnEditOffer = "Offre: $nomOffre modifiée !";
				} else {
					$returnEditOffer = "L'adresse n'appartient pas aux adresses de l'entreprise";
				}
			
			}
		}
	}
}
?>

<?php
	//On simule le choix d'une offre
	setcookie('editOffre', 'qsd');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <?php 
		include 'global/header.php';
	?>
	<link rel="stylesheet" type="text/css" href="css/modificationoffre.css">
	<link rel="manifest" href="manifest.json">
	<meta name="theme-color" content="#003366">
	<link rel="apple-touch-icon" sizes="180x180" href="/src/img/apple_touch_icon.png">
    <title>Stage à CESI'R - Offre</title>
    
    
    <?php if (!isset($_SESSION['LOGGED_USER'])) { 
		header('Location: RechercheOffre.php.php'); ?>
    <?php }else { ?>
		<?php if ($_SESSION['PERMS_USER'] == 1) {
			header('Location: RechercheOffre.php.php'); ?>
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
		<?php if (isset($returnEditOffer)) { ?>
			<label class = return>
				<p><?php echo $returnEditOffer; ?></p>
			</label>
		<?php } ?>
		</center>
		
		<?php
		//On récupère les informations de l'offre
		
		
		$nomOffre = "NULL";
		if(isset($_COOKIE['editOffre'])){
			$nomOffre = $_COOKIE['editOffre'];
		}
		
		
		
		//Récupération et affichage du secteur à partir du cookie
					
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
		$req_get_offre = "SELECT * FROM offre WHERE nom_offre='$nomOffre'";
		$get_offre = $dbh->query($req_get_offre);
		if (!$get_offre){
			echo "Erreur dans l'obtention de l'entreprise : <br><br>$req_get_offre";
			die;
		}
		$str_offre = $get_offre->fetch(PDO::FETCH_OBJ);
		if($str_offre){
			$IDAdresseOffre = $str_offre->adresse_offre;
			$remunerationOffre = $str_offre->remuneration_horaire;
			$placesOffre = $str_offre->places_offre;
			$dureeOffre = $str_offre->duree_stage;
			$dateDebutOffre = $str_offre->date_debut_stage;
			$idEntrepriseOffre = $str_offre->IDEntreprise;
			$idCompetenceOffre = $str_offre->IDCompetence;
			$idPromotionOffre = $str_offre->IDPromotion;
			$get_offre->closeCursor();
						
		} else {
			echo "L'offre n'est pas valide.";
			die;
		}
	
		
		?>
		
		
		
		<div class = content >
            <div class = input_info>
  
				<div style="margin-top: 5%;">
                    <label for="">Entreprise : 
						<select name="entreprise" id="pet-select" style="color: grey;">
							<option value="">Choisissez une entreprise*</option>
							
							<?php 
							//Affichage des options de la liste déroulante des entreprises
							//1. Connexion à la base de données
							try {
								$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
								$user = 'root';
								$password = '';

								$dbh = new PDO($dsn, $user, $password);
							} catch(Exception $e){
								// On traite l'erreur
								echo 'Erreur de connéxion à la base de données:<br>', $e;
								die;
							}
							//2. On récupère la liste des entreprises
							$controller = false;
							$ID = 1;
							while($controller == false){
								$req_get_entreprise = "SELECT * FROM entreprise WHERE id='$ID'";
								$get_entreprise = $dbh->query($req_get_entreprise);
								if (!$get_entreprise){
									echo "Erreur dans l'obtention des entreprises : <br>$req_get_entreprise\n";
									die;
								}
								$str_entreprise = $get_entreprise->fetch(PDO::FETCH_OBJ);
								if(!$str_entreprise) {
									$get_entreprise->closeCursor();
									$controller = true;
									
								} else {
									$entreprise = $str_entreprise->nom_entreprise;
									if($ID == $idEntrepriseOffre){
										echo "<option selected value='".($ID)."'>".($entreprise)." </option>";
									} else {
										echo "<option value='".($ID)."'>".($entreprise)."</option>";
									}

									$get_entreprise->closeCursor();
										
									
									$ID++;
									
								}
							}
							?>
						</select>
					</label>
                </div>
				
				
				
				
				
				<div style="margin-top: 5%;">
                    <label for="">Titre : <?php echo $nomOffre;?></label>
                    
                </div>
               
			   
			    <div style="margin-top: 5%;">
                    <label for="">Adresse : 
						<select name="adresse" id="pet-select" style="color: grey;">
							<option value="">Choisissez une adresse*</option>
							
							<?php 
							//Affichage des options de la liste déroulante des villes
							//1. Connexion à la base de données
							try {
								$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
								$user = 'root';
								$password = '';

								$dbh = new PDO($dsn, $user, $password);
							} catch(Exception $e){
								// On traite l'erreur
								echo 'Erreur de connéxion à la base de données:<br>', $e;
								die;
							}
							//2. On récupère la liste des villes
							$controller = false;
							$ID = 1;
							while($controller == false){
								$req_get_city = "SELECT * FROM adresse WHERE id='$ID'";
								$get_city = $dbh->query($req_get_city);
								if (!$get_city){
									echo "Erreur dans l'obtention des villes : <br>$req_get_city\n";
									die;
								}
								$str_city = $get_city->fetch(PDO::FETCH_OBJ);
								if(!$str_city) {
									$get_city->closeCursor();
									$controller = true;
									
								} else {
									$searchstring = $str_city->ad_ville;
									$search = "centre_";
									
									
									
									
									if(!preg_match("/{$search}/i", $searchstring)) {
										
										$adRue = $str_city->ad_rue;
										$adNumero = $str_city->ad_numero;
										$adCp = $str_city->ad_cp;
										$adPays = $str_city->ad_pays;
										$myString = "$adNumero $adRue, $adCp $searchstring, $adPays";
										if($ID == $IDAdresseOffre){
											echo "<option selected value='".($ID)."'>".($myString)."</option>";
										} else {
											echo "<option value='".($ID)."'>".($myString)."</option>";
										}
									}
										
									
									
									$ID++;
									$get_city->closeCursor();
								}
							}
							?>
						</select>
					</label>
                </div>
			   
			   
			   
			   
			   <div style="margin-top: 5%;">
                    <label for="">Compétence : 
						<select name="competence" id="pet-select" style="color: grey;">
							<option value="">Choisissez une compétence*</option>
							
							<?php 
							//Affichage des options de la liste déroulante des compétences
							//1. Connexion à la base de données
							try {
								$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
								$user = 'root';
								$password = '';

								$dbh = new PDO($dsn, $user, $password);
							} catch(Exception $e){
								// On traite l'erreur
								echo 'Erreur de connéxion à la base de données:<br>', $e;
								die;
							}
							//2. On récupère la liste des compétences
							$controller = false;
							$ID = 1;
							while($controller == false){
								$req_get_skills = "SELECT * FROM competence WHERE id='$ID'";
								$get_skills = $dbh->query($req_get_skills);
								if (!$get_skills){
									echo "Erreur dans l'obtention des compétences : <br>$req_get_skills\n";
									die;
								}
								$str_skills = $get_skills->fetch(PDO::FETCH_OBJ);
								if(!$str_skills) {
									$get_skills->closeCursor();
									$controller = true;
									
								} else {
									$skills = $str_skills->nom_competence;
									
									
									if($ID == $idCompetenceOffre){
										echo "<option selected value='".($ID)."'>".($skills)." </option>";
									} else {
										echo "<option value='".($ID)."'>".($skills)."</option>";
									}
									$get_skills->closeCursor();
									
									$ID++;
									
								}
							}
							?>
						</select>
					</label>
                </div>
			   
			    
				
				<div style="margin-top: 5%;">
                    <label for="">Promotion : 
						<select name="promotion" id="pet-select" style="color: grey;">
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
								echo 'Erreur de connéxion à la base de données:<br>', $e;
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
				
									
									if($ID == $idPromotionOffre){
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
                    <?php echo "<label for=''>Nombre de place disponible : <input name='place' input type='number' value='".($placesOffre)."' min='1' max='10' style='color: grey;'></label>" ;?>
                    
                </div>
				<div style="margin-top: 5%;">
                    <?php echo "<label for=''>Durée du stage (semaine) : <input name='duree' input type='number' value='".($dureeOffre)."' min='1' max='52' style='color: grey;'></label>" ;?>
                    
                </div>
				
				<div style="margin-top: 5%;">
                    <?php echo "<label for=''>Rémunération horaire : <input name='remuneration' input type='number' step='0.01' value='".($remunerationOffre)."' min='4.05' max='20' style='color: grey;'></label>" ;?>
                    
                </div>
				<div style="margin-top: 5%;">
                    <?php echo " <label for=''>Date de début : <input name='date_debut' type='date' value='$dateDebutOffre' style='color: grey;'></label>";?>
                    
                </div>

            </div>
            <div  class = input_add>
                <input type="submit" name="editOffre" value="Valider" style="padding: 20px 50px;">
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