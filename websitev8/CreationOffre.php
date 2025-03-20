<?php session_start(); ?>

<?php

if(isset($_POST["addOffre"])) {
	//Ajouter une offre
	
	//1. On vérifie que tous les champs de textes sont pleins, et qu'ils ne contiennent pas de caractères interdits
	
	$enter_entreprise = $_POST['entreprise'];
	$enter_titre = $_POST['titre'];
	$enter_competence = $_POST['competence'];
	$enter_promotion = $_POST['promotion'];
	$enter_place = $_POST['place'];
	$enter_duree = $_POST['duree'];
	$enter_remuneration = $_POST['remuneration'];
	$enter_date_debut = $_POST['date_debut'];
	$enter_adresse = $_POST['adresse'];
	
	$checker_entreprise = trim($enter_entreprise);
	$checker_titre = trim($enter_titre);
	$checker_competence = trim($enter_competence);
	$checker_promotion = trim($enter_promotion);
	$checker_place = trim($enter_place);
	$checker_duree = trim($enter_duree);
	$checker_remuneration = trim($enter_remuneration);
	$checker_date_debut = trim($enter_date_debut);
	$checker_adresse = trim($enter_adresse);
	
	$checker1 = false;
	
	if(empty($checker_date_debut)){
		$returnCreateOffer = "Vous devez renseigner la date de début du stage";	
		$checker1 = true;}
	if(empty($checker_remuneration)){
		$returnCreateOffer = "Vous devez renseigner la rémunération horaire";	
		$checker1 = true;}
	if(empty($checker_duree)){
		$returnCreateOffer = "Vous devez renseigner la durée du stage en semaine";	
		$checker1 = true;}
	if(empty($checker_place)){
		$returnCreateOffer = "Vous devez renseigner le nombre de place";	
		$checker1 = true;}
	if(empty($checker_promotion)){
		$returnCreateOffer = "Vous devez renseigner la promotion";	
		$checker1 = true;}
	if(empty($checker_competence)){
		$returnCreateOffer = "Vous devez renseigner la compétence du stage";	
		$checker1 = true;}
	if(empty($checker_titre)){
		$returnCreateOffer = "Vous devez renseigner le titre du stage";	
		$checker1 = true;}
	if(empty($checker_adresse)){
		$returnCreateOffer = "Vous devez renseigner l'adresse à laquelle se déroulera le stage";	
		$checker1 = true;}
	if(empty($checker_entreprise)){
		$returnCreateOffer = "Vous devez renseigner l'entreprise";	
		$checker1 = true;}
	
	if($checker1 == false){
		//On réalise les vérifications anti-injéction sql sur les champs texte
		
		$banword1 = "SELECT";
		$banword2 = "DELETE";
		$banword3 = "INSERT";
		$banword4 = "UPDATE";
		$banword5 = ";";
		$checker2 = false;
		if(strpos(strtoupper($checker_titre), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_titre), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_titre), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_titre), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_titre), $banword5) !== false){
			$checker2 = true;}
		
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
			$returnCreateOffer = "Erreur dans l'envoi de vos données";
		} else {
		
			//2. On vérifie que l'offre avec ce nom n'existe pas
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
			
			//Verification
			$req_get_offer = "SELECT * FROM offre WHERE nom_offre='$checker_titre'";
			$get_offer = $dbh->query($req_get_offer);
			if (!$get_offer){
				$returnCreateOffer = "Erreur dans la vérification de l'offre : <br><br>$req_get_offer";
			}
			$str_offer = $get_offer->fetch(PDO::FETCH_OBJ);
			if($str_offer){
				$returnCreateOffer = "Le titre de l'offre est déjà utilisé";
				$get_offer->closeCursor();
			} else {
				$get_offer->closeCursor();
				//3. On vérifie que la date de début n'est pas dépasse
				
				$timespan_date_debut = strtotime($enter_date_debut);
				$timespan_date_now = time();
				
				if($timespan_date_now > $timespan_date_debut){
					$returnCreateOffer = "La date de début du stage ne doit pas être dépassée";
				} else {
				
					//4. On calcule la date de fin grâce à la durée en semaine (retirer 3 jours pour faire correspondre)
					
					//On calcule le timespan de fin du stage auquel on retire 
					$timespan_date_fin_calc = $timespan_date_debut + ($enter_duree * 604800);
					
					//On retire trois jours à la date de fin du stage (Lundi -3 jours = Vendredi, jour de fin du stage)
					$timespan_date_fin_final = $timespan_date_fin_calc - 259200;
					
					
					//On convertit ce timespan en date
					$date_finstage = date( "Y-m-d", $timespan_date_fin_final );
					
					//5. On Récupère la date actuelle
					$date_now = date( "Y-m-d", $timespan_date_now );
					
					//6. On récupère l'ID de l'entreprise
					$req_get_id_entreprise = "SELECT * FROM entreprise WHERE nom_entreprise='$enter_entreprise'";
					$get_id_entreprise = $dbh->query($req_get_id_entreprise);
					if (!$get_id_entreprise){
						$returnCreateOffer = "Erreur dans l'obtention de l'entreprise : <br><br>$req_get_id_entreprise";
						die;
					}
					$str_id_entreprise = $get_id_entreprise->fetch(PDO::FETCH_OBJ);
					if($str_id_entreprise){
						$passive_IDEntreprise = $str_id_entreprise->id;
						$IDEntreprise = intval($passive_IDEntreprise);
					} else {
						$returnCreateOffer = "L'ID de l'entreprise n'as pas été trouvé";
						die;
					}
					$get_id_entreprise->closeCursor();
					
					//7. On vérifie que l'adresse est bien une adresse de l'entreprise
					
					
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
					
					
						//8. On récupère l'ID de la compétence
						$req_get_id_competence = "SELECT * FROM competence WHERE nom_competence='$enter_competence'";
						$get_id_competence = $dbh->query($req_get_id_competence);
						if (!$get_id_competence){
							$returnCreateOffer = "Erreur dans l'obtention de la compétence : <br><br>$req_get_id_competence";
							die;
						}
						$str_id_competence = $get_id_competence->fetch(PDO::FETCH_OBJ);
						if($str_id_competence){
							$passive_IDCompetence = $str_id_competence->id;
							$IDCompetence = intval($passive_IDCompetence);
						} else {
							$returnCreateOffer = "L'ID de la compétence n'as pas été trouvé";
							die;
						}
						$get_id_competence->closeCursor();
						
						//9. On récupère l'ID de la promotion
						$req_get_id_promotion = "SELECT * FROM promotion WHERE nom_promotion='$enter_promotion'";
						$get_id_promotion = $dbh->query($req_get_id_promotion);
						if (!$get_id_promotion){
							$returnCreateOffer = "Erreur dans l'obtention de la compétence : <br><br>$req_get_id_promotion";
							die;
						}
						$str_id_promotion = $get_id_promotion->fetch(PDO::FETCH_OBJ);
						if($str_id_promotion){
							$passive_IDPromotion = $str_id_promotion->id;
							$IDPromotion = intval($passive_IDPromotion);
						} else {
							$returnCreateOffer = "L'ID de la compétence n'as pas été trouvé";
							die;
						}
						$get_id_promotion->closeCursor();
						
						
						//10. On ajoute l'offre
						
						$req_add_offer = "INSERT INTO offre (nom_offre, adresse_offre, remuneration_horaire, date_poste_offre, places_offre, duree_stage, date_debut_stage, date_fin_stage, IDEntreprise, IDCompetence, IDPromotion) VALUES ('$checker_titre', '$enter_adresse', '$enter_remuneration', '$date_now', '$enter_place', '$enter_duree', '$enter_date_debut', '$date_finstage', '$IDEntreprise', '$IDCompetence', '$IDPromotion');";
						$add_offer = $dbh->query($req_add_offer);
						if (!$add_offer){
							$returnCreateOffer = "Erreur dans la requête : <br><br>$req_add_offer";
						}
						$add_offer->closeCursor();
									
						$returnCreateOffer = "Offre: $checker_titre créée !";
					
					} else {
						$returnCreateOffer = "L'adresse n'appartient pas aux adresses de l'entreprise";
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
	<link rel="stylesheet" type="text/css" href="css/creationoffre.css">
    <title>Stage à CESI'R - Offre</title>
    
    
    <?php if (!isset($_SESSION['LOGGED_USER'])) { 
		header('Location: index.php'); ?>
    <?php }else { ?>
		<?php if ($_SESSION['PERMS_USER'] == 1) {
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
		<?php if (isset($returnCreateOffer)) { ?>
			<label class = return>
				<p><?php echo $returnCreateOffer; ?></p>
			</label>
		<?php } ?>
		</center>
		
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
									echo "<option value='".($entreprise)."'>".($entreprise)."</option>";
									$get_entreprise->closeCursor();
										
									
									$ID++;
									
								}
							}
							?>
						</select>
					</label>
                </div>
				
				
				
				
				
				<div style="margin-top: 5%;">
                    <label for="">Titre : <input name="titre" input type="text" placeholder="Titre du stage*" style="color: grey;"></label>
                    
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
								$req_get_city = "SELECT DISTINCT * FROM adresse WHERE id='$ID'";
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
										
										echo "<option value='".($ID)."'>".($myString)."</option>";
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
									echo "<option value='".($skills)."'>".($skills)."</option>";
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
				
									echo "<option value='".($promo)."'>".($promo)."</option>";
									$get_promotions->closeCursor();
									$ID++;
								}
							}
							?>
						</select>
					</label>
                </div>
				
				
				
			   
				<div style="margin-top: 5%;">
                    <label for="">Nombre de place disponible : <input name="place" input type="number" placeholder="Place*" min="1" max="10" style="color: grey;"></label>
                    
                </div>
				<div style="margin-top: 5%;">
                    <label for="">Durée du stage (semaine) : <input name="duree" input type="number" placeholder="Durée*" min="1" max="52" style="color: grey;"></label>
                    
                </div>
				
				<div style="margin-top: 5%;">
                    <label for="">Rémunération horaire : <input name="remuneration" input type="number" step="0.01" placeholder="€/h*" min="4.05" max="20" style="color: grey;"></label>
                    
                </div>
				<div style="margin-top: 5%;">
                    <label for="">Date de début : <input name="date_debut" type="date" placeholder="Date de début*" style="color: grey;"></label>
                    
                </div>

            </div>
            <div  class = input_add>
                <input type="submit" name="addOffre" value="Valider" style="padding: 20px 50px;">
            </div>
		</div>
	</form>
</body>
<?php include 'global/footer.php'; ?>
</html>

