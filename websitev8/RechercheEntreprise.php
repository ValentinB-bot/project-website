<?php session_start(); ?>

<?php

if(isset($_POST["nextPage"])) {
	$page = $_GET['p'];
	if(!isset($page)){
		$page = 1;
	}
	$page++;
	header('Location: RechercheEntreprise.php?p='.$page.''); 
}

if(isset($_POST["previousPage"])) {
	$page = $_GET['p'];
	if(!isset($page)){
		$page = 1;
	}
	$page--;
	header('Location: RechercheEntreprise.php?p='.$page.''); 
}

if(isset($_POST["searchEntreprise"])) {
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


if(isset($_POST["deleteEntreprise"])) {
	$getEntreprise = $_POST["entreprise"];
	setcookie('deleteEntreprise', $getEntreprise);
	header('Location: SupprimerEntreprise.php'); 
}


if(isset($_POST["editEntreprise"])) {
	$getEntreprise = $_POST["entreprise"];
	setcookie('editEntreprise', $getEntreprise);
	header('Location: ModificationEntreprise.php'); 
}

if(isset($_POST["invisibleButton"])) {
	
	try
	{
	$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
	$user = 'root';
	$password = '';

	$dbh = new PDO($dsn, $user, $password);
	}
	catch(Exception $e){
	// On traite l'erreur
		echo "Erreur de connexion à la base de données: <br><br>$e";
	}
	
	$getEntreprise = $_POST["entreprise"];
	$req_change_visibility = "UPDATE `entreprise` SET `visible`='0' WHERE nom_entreprise = '$getEntreprise'";
	$change_visibility = $dbh->query($req_change_visibility);
	$change_visibility->closeCursor();
}

if(isset($_POST["visibleButton"])) {
	try
	{
	$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
	$user = 'root';
	$password = '';

	$dbh = new PDO($dsn, $user, $password);
	}
	catch(Exception $e){
	// On traite l'erreur
		echo "Erreur de connexion à la base de données: <br><br>$e";
	}
	
	$getEntreprise = $_POST["entreprise"];
	$req_change_visibility = "UPDATE `entreprise` SET `visible`='1' WHERE nom_entreprise = '$getEntreprise'";
	$change_visibility = $dbh->query($req_change_visibility);
	$change_visibility->closeCursor();
}

if(isset($_POST["filterEntreprise"])) {
	$activeFilter = "entreprise";
}

if(isset($_POST["filterSecteur"])) {
	$activeFilter = "secteur";
}

if(isset($_POST["filterVille"])) {
	$activeFilter = "ville";
}

if(isset($_POST["filterStagiaires"])) {
	$activeFilter = "stagiaires";
}

if(isset($_POST["filterConfiance"])) {
	$activeFilter = "confiance";
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
		include 'global/header.php';
	?>
	<link rel="stylesheet" type="text/css" href="css/rechercheentreprise.css">
	<link rel="manifest" href="manifest.json">
	<meta name="theme-color" content="#003366">
	<link rel="apple-touch-icon" sizes="180x180" href="/src/img/apple_touch_icon.png">
    <title>Stage à CESI'R - Entreprise</title>
   
    
	
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
<?php
        /* Connect to a MySQL database using driver invocation */
        try
        {
            $dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
            $user = 'root';
            $password = '';

            $dbh = new PDO($dsn, $user, $password);
        }
        catch(Exception $e){

            echo "Erreur de connexion à la base de données: <br><br>$e";

        }
		
		if(isset($activeFilter)){
			if($activeFilter == "entreprise"){
				$order = "entreprise.nom_entreprise";
			} else if ($activeFilter == "secteur") {
				$order = "competence.nom_competence";
			} else if ($activeFilter == "ville") {
				$order = "adresse.ad_ville";
			} else if ($activeFilter == "stagiaires") {
				$order = "evaluation_stagiaires";
			} else if ($activeFilter == "confiance") {
				$order = "confiance_pilote_promotion";
			}
		} else {
			$order = "entreprise.nom_entreprise";
		}
		
		
		$maximum = 5;
		
		$req_amountOfEntreprise = "SELECT COUNT(nom_entreprise) as counter FROM entreprise";
		
		$result_amountOfEntreprise = $dbh->query($req_amountOfEntreprise);
		$str_amountOfEntreprise = $result_amountOfEntreprise->fetch(PDO::FETCH_OBJ);
		
		$passive_amountOfEntreprise = $str_amountOfEntreprise->counter;
		$amountOfEntreprise = intval($passive_amountOfEntreprise);
		
		$result_amountOfEntreprise->closeCursor();
		
		if(isset($_GET['p'])){
			$page = $_GET['p'];
			
		} else {
			$page = 1;
			
		}
		
		$startValue = ($page - 1) * $maximum;
		
		
		
        $GetEnterprise = $dbh->query('SELECT 
        entreprise.nom_entreprise, 
        competence.nom_competence, 
        confiance_pilote_promotion,
        entreprise.link_logo,
        entreprise.visible,
		adresse.ad_ville,
        stagiaires_acceptes,
        GROUP_CONCAT(CONCAT(ad_numero, " ", ad_rue, ", ", ad_cp, " ", ad_ville, " ", ad_pays) SEPARATOR "<br> ") AS adresse_complete,
        ROUND((SELECT AVG(note) FROM candidature INNER JOIN offre on candidature.IDOffre = offre.id WHERE offre.IDEntreprise = entreprise.id), 1) as evaluation_stagiaires
    FROM 
        competence
    INNER JOIN entreprise ON competence.id = entreprise.secteur_activite
    INNER JOIN entreprise_adresse ON entreprise.id = entreprise_adresse.IDEntreprise 
    INNER JOIN adresse ON entreprise_adresse.IDAdresse = adresse.id 
    GROUP BY 
        nom_entreprise, 
        nom_competence, 
        confiance_pilote_promotion, 
        stagiaires_acceptes ORDER BY '.$order.' LIMIT '.$maximum.' OFFSET '.$startValue.';
                                ');
        if(!$GetEnterprise){
            echo "Erreur dans l'affichage des informations";
            die;
        }

        $html="";
		$amountOfResponse = 0;
		
        foreach ($GetEnterprise as $EnterpriseList) {
			
			
			
			$evaluationStagiaires = $EnterpriseList['evaluation_stagiaires'];
			if(!isset($evaluationStagiaires)){
				$evaluationStagiairesFinal = "Pas de note";
			} else {
				$evaluationStagiairesFinal = "$evaluationStagiaires/10";
			}
			
			if($EnterpriseList['visible'] == 1){
				$nameOfButton = "Rendre Invisible";
				$actionOfButton = "invisibleButton";
			} else {
				$nameOfButton = "Rendre Visible";
				$actionOfButton = "visibleButton";
			}
			
			if(isset($searchFinal)){
				$name = $EnterpriseList['nom_entreprise'];
				$skill = $EnterpriseList['nom_competence'];
				$address = $EnterpriseList['adresse_complete'];
				$searchstring = "$name $skill $address";
				if(preg_match("/{$searchFinal}/i", $searchstring)) {
					
					if ($_SESSION['PERMS_USER'] == 1){
						if($EnterpriseList['visible'] == 1){
							$html.='
								<form method="post" action="">
								<div class="content">
									<div class = information >
										<div class = profilephoto>
											<form>
												<label for="file-input">
													<img class = profilephoto src="src\upload\\'.$EnterpriseList['link_logo'].'" alt="PhotoProfil" id="ImageProfil" style = "margin-bottom: 15%; max-width: 200px;">
												</label>
											</form>
										</div>
										<div class = input_info>
											<div class="space_info">
												<label for="">' . $EnterpriseList['nom_entreprise'].'</label>
												
											</div>
											<div class="space_info">
												<label for="">' . $EnterpriseList['adresse_complete'].'</label>
											</div>
											<div class="space_info">
												<label for="">' . $EnterpriseList['nom_competence'].'</label>
											</div>
											<div class="space_info">
												<label for="">'.'Stagiaires acceptes : ' . $EnterpriseList['stagiaires_acceptes'].'</label>
											</div>
										</div>
										<div  class = input_add style="margin-top: 35%">
											<label for=""  style ="display:block; margin-bottom: 10%;">Evaluation des stagiaires : <label for="" style="color:rgb(255, 204, 0);">'.$evaluationStagiairesFinal.'</label></label>
											<label for="">Confiance des pilotes : <label for="" style="color:rgb(255, 204, 0);">'.$EnterpriseList['confiance_pilote_promotion'].'</label>/10</label>
										</div>      
									</div>
									<div class = "button">
									
										
										
										<input name="entreprise" type="text" value="'.$EnterpriseList['nom_entreprise'].'" style="visibility: hidden; display:none" >
									
										
									</div>
								</div>
								</form>';
								$amountOfResponse++;
						}
					} else {
						$html.='
								<form method="post" action="">
								<div class="content">
									<div class = information >
										<div class = profilephoto>
											<form>
												<label for="file-input">
													<img class = profilephoto src="src\upload\\'.$EnterpriseList['link_logo'].'" alt="PhotoProfil" id="ImageProfil" style = "margin-bottom: 15%; max-width: 200px;">
												</label>
											</form>
										</div>
										<div class = input_info>
											<div class="space_info">
												<label for="">' . $EnterpriseList['nom_entreprise'].'</label>
												
											</div>
											<div class="space_info">
												<label for="">' . $EnterpriseList['adresse_complete'].'</label>
											</div>
											<div class="space_info">
												<label for="">' . $EnterpriseList['nom_competence'].'</label>
											</div>
											<div class="space_info">
												<label for="">'.'Stagiaires acceptes : ' . $EnterpriseList['stagiaires_acceptes'].'</label>
											</div>
										</div>
										<div  class = input_add style="margin-top: 35%">
											<label for=""  style ="display:block; margin-bottom: 10%;">Evaluation des stagiaires : '.$evaluationStagiairesFinal.'</label>
											<label for="">Confiance des pilotes : '.$EnterpriseList['confiance_pilote_promotion'].'/10</label>
										</div>      
									</div>
									<div class = "button">
									
										<input type="submit" value="Supprimer" name="deleteEntreprise" style = "grid-row: 30;">
										
										
										<input type="submit" value="'.$nameOfButton.'" name="'.$actionOfButton.'" style = "grid-row: 15;">
										
											
										<input type="submit" value="Modifier" name="editEntreprise" style = "grid-row: 0;">
										
										
										<input name="entreprise" type="text" value="'.$EnterpriseList['nom_entreprise'].'" style="visibility: hidden; display:none" >
									
										
									</div>
								</div>
								</form>';
								$amountOfResponse++;
					}
				
				}
			} else {
				
				if ($_SESSION['PERMS_USER'] == 1){
					if($EnterpriseList['visible'] == 1){
						$html.='
							<form method="post" action="">
							<div class="content">
								<div class = information >
									<div class = profilephoto>
										<form>
											<label for="file-input">
												<img class = profilephoto src="src\upload\\'.$EnterpriseList['link_logo'].'" alt="PhotoProfil" id="ImageProfil" style = "margin-bottom: 15%; max-width: 200px;">
											</label>
										</form>
									</div>
									<div class = input_info>
										<div class="space_info">
											<label for="">' . $EnterpriseList['nom_entreprise'].'</label>
											
										</div>
										<div class="space_info">
											<label for="">' . $EnterpriseList['adresse_complete'].'</label>
										</div>
										<div class="space_info">
											<label for="">' . $EnterpriseList['nom_competence'].'</label>
										</div>
										<div class="space_info">
											<label for="">'.'Stagiaires acceptes : ' . $EnterpriseList['stagiaires_acceptes'].'</label>
										</div>
									</div>
									<div  class = input_add style="margin-top: 35%">
										<label for=""  style ="display:block; margin-bottom: 10%;">Evaluation des stagiaires : '.$evaluationStagiairesFinal.'</label>
										<label for="">Confiance des pilotes : '.$EnterpriseList['confiance_pilote_promotion'].'/10</label>
									</div>      
								</div>
								<div class = "button">
								
									
									
									<input name="entreprise" type="text" value="'.$EnterpriseList['nom_entreprise'].'" style="visibility: hidden; display:none" >
								
									
								</div>
							</div>
							</form>';
							$amountOfResponse++;
					}
				} else {
					$html.='
							<form method="post" action="">
							<div class="content">
								<div class = information >
									<div class = profilephoto>
										<form>
											<label for="file-input">
												<img class = profilephoto src="src\upload\\'.$EnterpriseList['link_logo'].'" alt="PhotoProfil" id="ImageProfil" style = "margin-bottom: 15%; max-width: 200px;">
											</label>
										</form>
									</div>
									<div class = input_info>
										<div class="space_info">
											<label for="">' . $EnterpriseList['nom_entreprise'].'</label>
											
										</div>
										<div class="space_info">
											<label for="">' . $EnterpriseList['adresse_complete'].'</label>
										</div>
										<div class="space_info">
											<label for="">' . $EnterpriseList['nom_competence'].'</label>
										</div>
										<div class="space_info">
											<label for="">'.'Stagiaires acceptes : ' . $EnterpriseList['stagiaires_acceptes'].'</label>
										</div>
									</div>
									<div  class = input_add style="margin-top: 35%">
										<label for=""  style ="display:block; margin-bottom: 10%;">Evaluation des stagiaires : '.$evaluationStagiairesFinal.'</label>
										<label for="">Confiance des pilotes : '.$EnterpriseList['confiance_pilote_promotion'].'/10</label>
									</div>      
								</div>
								<div class = "button">
								
									<input type="submit" value="Supprimer" name="deleteEntreprise" style = "grid-row: 30;">
									
									
									<input type="submit" value="'.$nameOfButton.'" name="'.$actionOfButton.'" style = "grid-row: 15;">
									
										
									<input type="submit" value="Modifier" name="editEntreprise" style = "grid-row: 0;">
									
									
									<input name="entreprise" type="text" value="'.$EnterpriseList['nom_entreprise'].'" style="visibility: hidden; display:none" >
								
									
								</div>
							</div>
							</form>';
							$amountOfResponse++;
				}
			}
        }
?>

		<?php if($amountOfResponse == 0){
			?><center>
				<label id = "return">
					<p> Aucune entreprise n'a été trouvée avec cette recherche </p>
				</label>
			</center> <?php
		} ?>

        <form method="post" action="">
			<div class = "Search">
				<input type="search" name="Search" id="Search" placeholder="🔎Rechercher">
				<input type="submit" value="Rechercher" name="searchEntreprise" style="visibility: hidden; display:none">
			</div>
			
			<div class = "filter" style="border: solid;display: grid;float: right;margin-top: 5%;margin-right: 5%;padding: 0.5%;">
				<label for="" style="text-align: center;border: solid;width: 100%;">Filtres</label>
				<input class = "filterButton" for="" type="submit" value="Entreprise" name="filterEntreprise">
				<input class = "filterButton" for="" type="submit" value="Secteur" name="filterSecteur">
				<input class = "filterButton" for="" type="submit" value="Ville" name="filterVille">
				<input class = "filterButton" for="" type="submit" value="Evaluation des stagiaires" name="filterStagiaires">
				<input class = "filterButton" for="" type="submit" value="Confiance des pilote" name="filterConfiance">

			   
			</div>
			<div>
				<?php
				$offre = $amountOfEntreprise; 
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
        <div id = "EnterpriseList">
            <?php echo $html ?>
        </div>
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