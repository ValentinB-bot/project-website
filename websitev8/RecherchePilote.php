<?php session_start(); ?>


<?php 

if(isset($_POST["nextPage"])) {
	$page = $_GET['p'];
	if(!isset($page)){
		$page = 1;
	}
	$page++;
	header('Location: RechercheEtudiant.php?p='.$page.''); 
}

if(isset($_POST["previousPage"])) {
	$page = $_GET['p'];
	if(!isset($page)){
		$page = 1;
	}
	$page--;
	header('Location: RechercheEtudiant.php?p='.$page.''); 
}

if(isset($_POST["deletePilot"])) {
	$getPilot = $_POST["username"];
	setcookie('deletePilote', $getPilot);
	header('Location: SupprimerPilote.php'); 
	
}

if(isset($_POST["editPilot"])) {
	
	$getPilot = $_POST["username"];
	setcookie('editPilote', $getPilot);
	header('Location: ModificationPilote.php'); 
}

if(isset($_POST["searchPilot"])) {
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

<!DOCTYPE html>
<html lang="en">
<head>
	
	<?php 
		include 'global/header.php';
	?>
	
	<link rel="stylesheet" type="text/css" href="css/recherchepilote.css">
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

        // Get all tutors information
		
		$maximum = 5;
		
		$req_amountOfPilote = "SELECT COUNT(nom_utilisateur) as counter FROM utilisateur WHERE IDRole = 2";
		
		$result_amountOfPilote = $dbh->query($req_amountOfPilote);
		$str_amountOfPilote = $result_amountOfPilote->fetch(PDO::FETCH_OBJ);
		
		$passive_amountOfPilote = $str_amountOfPilote->counter;
		$amountOfPilote = intval($passive_amountOfPilote);
		
		$result_amountOfPilote->closeCursor();
		
		if(isset($_GET['p'])){
			$page = $_GET['p'];
			
		} else {
			$page = 1;
			
		}
		
		$startValue = ($page - 1) * $maximum;
		
		
        $GetTutor = $dbh->query('SELECT utilisateur.nom_utilisateur, utilisateur.login_utilisateur, utilisateur.link_logo, utilisateur.prenom_utilisateur, 
                                GROUP_CONCAT(DISTINCT promotion.nom_promotion SEPARATOR "<br>") as promotions, adresse.ad_ville 
                                FROM adresse 
                                INNER JOIN utilisateur ON adresse.id = utilisateur.IDAdresse 
                                INNER JOIN utilisateur_promotion ON utilisateur.id = utilisateur_promotion.IDUtilisateur 
                                INNER JOIN promotion ON utilisateur_promotion.IDPromotion = promotion.id 
                                WHERE utilisateur.IDRole = 2 
                                GROUP BY utilisateur.nom_utilisateur, utilisateur.prenom_utilisateur, adresse.ad_ville LIMIT '.$maximum.' OFFSET '.$startValue.';');
        if(!$GetTutor){
            echo "Erreur dans l'affichage des informations";
            die;
        }

        $html="";
		$amountOfResponse = 0;
	
		
        //Display the tutors and their information one by one
        foreach ($GetTutor as $TutorList) {
			$affcentre = $TutorList['ad_ville'];
			$affcentreexplode = explode("_",$affcentre);
			$centre = $affcentreexplode[1];
			
			if(isset($searchFinal)){
				$nom = $TutorList['nom_utilisateur'];
				$prenom = $TutorList['prenom_utilisateur'];
				$searchstring = "$nom $prenom";
				
				if(preg_match("/{$searchFinal}/i", $searchstring)) {
			
					if($TutorList['link_logo']){
						$link_logo = $TutorList['link_logo'];
						
						$html.='
							<form method="post" action="">
							<div class="content">
							<div class = information >
								<div class = profilephoto>
									<form>
										<label for="file-input">
											<img src="src/upload/'.($link_logo).'" alt="Profile Image" id="ImageProfile" style = "margin-bottom: 15%;">
										</label>
									</form>
								</div>
								<div class = input_info>
									<div class="space_info">
										<label for="">' . $TutorList['nom_utilisateur'].' '.$TutorList['prenom_utilisateur'] . '</label>     
									</div>
									<div class="space_info">
										<label for="">' . $centre. '</label>
									</div>
									<div class="space_info">
										<label for="">' . $TutorList['promotions']. '</label>
									</div>
								</div>    
							</div>
								<div class = "button">
									<input type="submit" value="Modifier" name="editPilot" style = "grid-row: 20;">
									<input type="submit" value="Supprimer" name="deletePilot">
									<input name="username" type="text" value="'.$TutorList['login_utilisateur'].'" style="visibility: hidden; display:none" >
								</div>
							</div>
							</form>';
							$amountOfResponse++;
						
						
					} else {
					
						$html.='
							<form method="post" action="">
							<div class="content">
							<div class = information >
								<div class = profilephoto>
									<form>
										<label for="file-input">
											<img src="src/img/nopp.png" alt="Profile Image" id="ImageProfile" style = "margin-bottom: 15%;">
										</label>
									</form>
								</div>
								<div class = input_info>
									<div class="space_info">
										<label for="">' . $TutorList['nom_utilisateur'].' '.$TutorList['prenom_utilisateur'] . '</label>     
									</div>
									<div class="space_info">
										<label for="">' . $centre. '</label>
									</div>
									<div class="space_info">
										<label for="">' . $TutorList['promotions']. '</label>
									</div>
								</div>    
							</div>
								<div class = "button">
									<input type="submit" value="Modifier" name="editPilot" style = "grid-row: 20;">
									<input type="submit" value="Supprimer" name="deletePilot">
									<input name="username" type="text" value="'.$TutorList['login_utilisateur'].'" style="visibility: hidden; display:none" >
								</div>
							</div>
							</form>';
							$amountOfResponse++;
					}
				}
			} else {
				if($TutorList['link_logo']){
					$link_logo = $TutorList['link_logo'];
					
					$html.='
						<form method="post" action="">
						<div class="content">
						<div class = information >
							<div class = profilephoto>
								<form>
									<label for="file-input">
										<img src="src/upload/'.($link_logo).'" alt="Profile Image" id="ImageProfile" style = "margin-bottom: 15%;">
									</label>
								</form>
							</div>
							<div class = input_info>
								<div class="space_info">
									<label for="">' . $TutorList['nom_utilisateur'].' '.$TutorList['prenom_utilisateur'] . '</label>     
								</div>
								<div class="space_info">
									<label for="">' . $centre. '</label>
								</div>
								<div class="space_info">
									<label for="">' . $TutorList['promotions']. '</label>
								</div>
							</div>    
						</div>
							<div class = "button">
								<input type="submit" value="Modifier" name="editPilot" style = "grid-row: 20;">
								<input type="submit" value="Supprimer" name="deletePilot">
								<input name="username" type="text" value="'.$TutorList['login_utilisateur'].'" style="visibility: hidden; display:none" >
							</div>
						</div>
						</form>';
						$amountOfResponse++;

				} else {
					
					$html.='
						<form method="post" action="">
						<div class="content">
						<div class = information >
							<div class = profilephoto>
								<form>
									<label for="file-input">
										<img src="src/img/nopp.png" alt="Profile Image" id="ImageProfile" style = "margin-bottom: 15%;">
									</label>
								</form>
							</div>
							<div class = input_info>
								<div class="space_info">
									<label for="">' . $TutorList['nom_utilisateur'].' '.$TutorList['prenom_utilisateur'] . '</label>     
								</div>
								<div class="space_info">
									<label for="">' . $centre. '</label>
								</div>
								<div class="space_info">
									<label for="">' . $TutorList['promotions']. '</label>
								</div>
							</div>    
						</div>
							<div class = "button">
								<input type="submit" value="Modifier" name="editPilot" style = "grid-row: 20;">
								<input type="submit" value="Supprimer" name="deletePilot">
								<input name="username" type="text" value="'.$TutorList['login_utilisateur'].'" style="visibility: hidden; display:none" >
							</div>
						</div>
						</form>';
						$amountOfResponse++;
				}
			}
		}
			
		
		if($amountOfResponse == 0){
			?><center>
				<label id = "return">
					<p> Aucun pilote de promotion n'a été trouvé avec cette recherche </p>
				</label>
			</center> <?php
		}
?>
        <form method="post" action="">
			<div class = "Search">
				<input type="search" name="Search" id="Search" placeholder="🔎Rechercher">
				<input type="submit" value="Rechercher" name="searchPilot" style="visibility: hidden; display:none">
			</div>
			<div>
				<?php
				$offre = $amountOfPilote; 
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
        <div id = "Liste_Pilote">
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