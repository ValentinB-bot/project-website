
<?php session_start(); ?>

<?php 

if(isset($_POST["nextPage"])) {
	$page = $_GET['p'];
	if(!isset($page)){
		$page = 1;
	}
	$page++;
	header('Location: Candidatures.php?p='.$page.''); 
}

if(isset($_POST["previousPage"])) {
	$page = $_GET['p'];
	if(!isset($page)){
		$page = 1;
	}
	$page--;
	header('Location: Candidatures.php?p='.$page.''); 
}

if(isset($_POST["searchOffer"])) {
	//1. Vérification du champ
	
	$enter_search = $_POST["Search"];
	$checker_search = trim($enter_search);
	if(!empty($checker_search)){
		$banword1 = "SELECT";
		$banword2 = "DELETE";
		$banword3 = "INSERT";
		$banword4 = "UPDATE";
		$banword5 = ";";
		
		$checker2 = false;
		if(strpos(strtoupper($checker_search), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_search), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_search), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_search), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_search), $banword5) !== false){
			$checker2 = true;}
			
		if($checker2 == false){
			$searchFinal = "$checker_search";
			
		}
		
	}
}

?>

<?php if (!isset($_SESSION['LOGGED_USER'])) {
        header('Location: index.php'); ?>
<?php } ?>

<!DOCTYPE html>
<html lang="en">
<head>

	<?php 
		include 'global/header.php';
	?>
    <link rel="stylesheet" type="text/css" href="css/candidatures.css">
    <title>Stage à CESI'R - Candidatures</title>


    <?php if (!isset($_SESSION['LOGGED_USER'])) { 
		header('Location: index.php'); ?>
    <?php }else { ?>	
			<a href="\unlog.php"><button class = login>Déconnexion</button></a>
			<a href="\profil.php"><button class = login>Mon Profil</button></a>
		
	<?php } ?>

    <?php 
		include 'global/navbar.php';
	?>
    


</head>

<body>

			<center>
				<label id = "return">
					<p>
					<?php 
						$usernameStudent = "NULL";
						if(isset($_COOKIE['applyOfAStudent'])){
							$usernameStudent = $_COOKIE['applyOfAStudent'];
						}
						
						try
						{
							$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
							$user = 'root';
							$password = '';

							$dbh = new PDO($dsn, $user, $password);
						}
						catch(Exception $e){

							echo 'Erreur de connexion à la base de données';

						}
						

						//On récupère le nom et le prénom de l'étudiant
						
						
						$query_get_username = "SELECT * FROM utilisateur WHERE login_utilisateur='$usernameStudent'";
						$get_username = $dbh->query($query_get_username);
						if (!$get_username){
							$identifierStudent = "Erreur dans l'obtention de l'utilisateur : <br><br>$query_get_username";
							
						}
						$str_username = $get_username->fetch(PDO::FETCH_OBJ);
						$nom = $str_username->nom_utilisateur;
						$prenom = $str_username->prenom_utilisateur;
						$get_username->closeCursor();
						$identifierStudent = "Candidatures de: $prenom $nom";
						echo "$identifierStudent";
						?> </p>
				</label>
			</center>

<?php
        $usernameStudent = "NULL";
		if(isset($_COOKIE['applyOfAStudent'])){
			$usernameStudent = $_COOKIE['applyOfAStudent'];
		}
		
		/* Connect to a MySQL database using driver invocation */
        try
        {
            $dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
            $user = 'root';
            $password = '';

            $dbh = new PDO($dsn, $user, $password);
        }
        catch(Exception $e){

            echo 'Erreur de connexion à la base de données';

        }
		
        $maximum = 5;
		//On récupère l'ID de l'utilisateur
		
		$query_get_username = "SELECT * FROM utilisateur WHERE login_utilisateur='$usernameStudent'";
		$get_username = $dbh->query($query_get_username);
		if (!$get_username){
			echo "Erreur dans l'obtention de l'utilisateur : <br><br>$query_get_username";
							
		}
		$str_username = $get_username->fetch(PDO::FETCH_OBJ);
		$passive_IDUser = $str_username->id;
		$IDUser = intval($passive_IDUser);
		$get_username->closeCursor();
		
		
		//On récupère le nombre de candidature de l'utilisateur
		$req_amountOfApply = "SELECT COUNT(id) as counter FROM candidature WHERE IDUtilisateur = '$IDUser';";
		
		$result_amountOfApply = $dbh->query($req_amountOfApply);
		$str_amountOfApply = $result_amountOfApply->fetch(PDO::FETCH_OBJ);
		
		$passive_amountOfApply = $str_amountOfApply->counter;
		$amountOfApply = intval($passive_amountOfApply);
		
		$result_amountOfApply->closeCursor();
		
		if(isset($_GET['p'])){
			$page = $_GET['p'];
			
		} else {
			$page = 1;
			
		}
		
		$startValue = ($page - 1) * $maximum;
		
        $GetOffer = $dbh->query('SELECT link_logo, nom_entreprise, nom_offre, confiance_pilote_promotion, stagiaires_acceptes, remuneration_horaire, date_poste_offre, places_offre, duree_stage, date_debut_stage, date_fin_stage, description_competence, 
        CONCAT(ad_numero, " ", ad_rue, ", ", ad_cp, " ", ad_ville, ", ", ad_pays) AS adresse_complete, 
        ROUND((SELECT AVG(note) FROM candidature INNER JOIN offre on candidature.IDOffre = offre.id WHERE offre.IDEntreprise = entreprise.id), 1) as evaluation_stagiaires FROM adresse
        INNER JOIN entreprise_adresse ON adresse.id = entreprise_adresse.IDAdresse
        INNER JOIN entreprise ON entreprise_adresse.IDEntreprise = entreprise.id
        INNER JOIN offre ON entreprise.id = offre.IDEntreprise
        INNER JOIN competence ON offre.IDCompetence = competence.id
        WHERE adresse.id = adresse_offre AND offre.id = (SELECT IDOffre FROM candidature WHERE IDUtilisateur = (SELECT id FROM Utilisateur WHERE login_utilisateur = "'.$usernameStudent.' "))LIMIT '.$maximum.' OFFSET '.$startValue.';');

        if(!$GetOffer){
            echo "Erreur dans la récupération des données";
            die;
        }

        $html="";
		$amountOfResult = 0;
        foreach ($GetOffer as $OffersList){

        
			
			if(isset($searchFinal)){
				
				$entreprise = $OffersList['nom_entreprise'];
				$adresse = $OffersList['adresse_complete'];
				$stage = $OffersList['nom_offre'];
				$competence = $OffersList['description_competence'];
				$searchstring = "$entreprise $adresse $stage $competence";
				
				
				
				if(preg_match("/{$searchFinal}/i", $searchstring)) {
					
					$html.=
					 '<div class="content">
						<div class = information >
							<div class = profilephoto>
								<form>
									<label for="file-input">
										<img class = profilphoto src="src\upload\\'.$OffersList['link_logo'].'" alt="profilephoto" id="ImageProfile" style = "margin-bottom: 15%; max-width: 200px;">
									</label>
								</form>
							</div>
							<div class = input_info>
								<div style = "margin-top: 5%;margin-bottom: 5%;font-weight: bold;">
									<label for="">' . $OffersList['nom_entreprise'].'</label>
									
								</div>
								<div class="space_info">
									<label for="">' . $OffersList['adresse_complete']. '</label>
								</div>
								<div class="space_info">
									<label for="">' . $OffersList['nom_offre']. '</label>
								</div>
								<div class="space_info">
									<label for="">' . $OffersList['description_competence']. '</label>
								</div>
								<div class="space_info">
									<label for="">Nombre de places disponibles : ' . $OffersList['places_offre']. '</label>
								</div>
								<div class="space_info">
									Offre postée le '. $OffersList['date_poste_offre']. '</label>
								</div>
								<div class="space_info">
									<label for="">Rémunération : ' . $OffersList['remuneration_horaire']. '€/h</label>
								</div>
								<div class="space_info">
									<label for="">Durée du stage (en semaines) : ' . $OffersList['duree_stage']. '</label>
								</div>
								<div class="space_info">
									<label for="">' . $OffersList['date_debut_stage']. ' - ' . $OffersList['date_fin_stage']. '</label>
								</div>
								<div class="space_info">
									<label for="" class="space_info">Nombre de stagiaires acceptés : ' . $OffersList['stagiaires_acceptes']. '</label>
								</div>
							</div> 
							<div  class = input_add>
								<label for=""  style ="display:block;margin-bottom: 10%;">Evaluation des stagiaires : <label for="" style="color:rgb(255, 204, 0);">'.$OffersList['evaluation_stagiaires'].'</label>/10</label>
								<label for="">Confiance des pilotes : <label for="" style="color:rgb(255, 204, 0);">'.$OffersList['confiance_pilote_promotion'].'</label>/10</label>
							</div>   
						</div>
						
					</div>';
					$amountOfResult++;
					
				}
			} else {				
             $html.=
             '<div class="content">
                <div class = information >
                    <div class = profilephoto>
                        <form>
                            <label for="file-input">
                                <img class = profilphoto src="src\upload\\'.$OffersList['link_logo'].'" alt="profilephoto" id="ImageProfile" style = "margin-bottom: 15%; max-width: 200px;">
                            </label>
                        </form>
                    </div>
                    <div class = input_info>
                        <div style = "margin-top: 5%;margin-bottom: 5%;font-weight: bold;">
                            <label for="">' . $OffersList['nom_entreprise'].'</label>
                            
                        </div>
                        <div class="space_info">
                            <label for="">' . $OffersList['adresse_complete']. '</label>
                        </div>
                        <div class="space_info">
                            <label for="">' . $OffersList['nom_offre']. '</label>
                        </div>
                        <div class="space_info">
                            <label for="">' . $OffersList['description_competence']. '</label>
                        </div>
                        <div class="space_info">
                            <label for="">Nombre de places disponibles : ' . $OffersList['places_offre']. '</label>
                        </div>
                        <div class="space_info">
                            Offre postée le '. $OffersList['date_poste_offre']. '</label>
                        </div>
                        <div class="space_info">
                            <label for="">Rémunération : ' . $OffersList['remuneration_horaire']. '€/h</label>
                        </div>
                        <div class="space_info">
                            <label for="">Durée du stage (en semaines) : ' . $OffersList['duree_stage']. '</label>
                        </div>
                        <div class="space_info">
                            <label for="">' . $OffersList['date_debut_stage']. ' - ' . $OffersList['date_fin_stage']. '</label>
                        </div>
                        <div class="space_info">
                            <label for="" class="space_info">Nombre de stagiaires acceptés : ' . $OffersList['stagiaires_acceptes']. '</label>
                        </div>
                    </div> 
                    <div  class = input_add>
                        <label for=""  style ="display:block;margin-bottom: 10%;">Evaluation des stagiaires : <label for="" style="color:rgb(255, 204, 0);">'.$OffersList['evaluation_stagiaires'].'</label>/10</label>
                        <label for="">Confiance des pilotes : <label for="" style="color:rgb(255, 204, 0);">'.$OffersList['confiance_pilote_promotion'].'</label>/10</label>
                    </div>   
                </div>
                
            </div>';
			$amountOfResult++;
			}
        }
		
		if($amountOfApply == 0){
			?><center>
				<label id = "return">
					<p> Aucune candidature n'a été trouvée </p>
				</label>
			</center> <?php
			
		} else {
			if($amountOfResult == 0){
				?><center>
					<label id = "return">
						<p> Aucune candidature n'a été trouvée pour cette recherche </p>
					</label>
				</center> <?php
			}

			?>
			
			<form method="post" action="">
				<div class = "Search">
					<input type="search" name="Search" id="Search" placeholder="🔎Rechercher">
					<input type="submit" value="Rechercher" name="searchOffer" style="visibility: hidden; display:none">
				</div>
				<div>
					<?php
					$offre = $amountOfApply; 
					if(isset($_GET['p'])){
						$page = $_GET['p'];
					} else {
						$page = 1;
					}
					
					if($page > 1){
						?><input for="" type="submit" value="<< Précédent" name="previousPage"><?php	
					}
					
					if($offre > ($maximum * $page)){
						?><input for="" type="submit" value="Suivant >>" name="nextPage"><?php
					}
					$max = $maximum;
					$calc = $page*$max;
					if($calc > $offre){
						$calc = $offre;
					}
					echo "<br><label class = 'pages'>Page: $page, $calc/$offre</label>";
					?>
				</div>
			</form>
				<?php 
		} ?>
		
		
        <div id = "Wishlist">
            <?php echo $html ?>
        </div>
    </body>
	<?php include 'global/footer.php'; ?>
</html>



