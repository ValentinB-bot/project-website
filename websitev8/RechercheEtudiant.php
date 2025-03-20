
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

if(isset($_POST["deleteStudent"])) {
	$getStudent = $_POST["username"];
	setcookie('deleteStudent', $getStudent);
	header('Location: SupprimerEtudiant.php'); 
	
}

if(isset($_POST["editStudent"])) {
	
	$getStudent = $_POST["username"];
	setcookie('editStudent', $getStudent);
	header('Location: ModificationEtudiant.php'); 
}

if(isset($_POST["viewMySupplyOffer"])) {
	
	$getStudent = $_POST["username"];
	setcookie('applyOfAStudent', $getStudent);
	header('Location: Candidatures.php'); 
}


if(isset($_POST["searchStudent"])) {
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
	<link rel="stylesheet" type="text/css" href="css/rechercheetudiant.css">
	<link rel="manifest" href="manifest.json">
	<meta name="theme-color" content="#003366">
	<link rel="apple-touch-icon" sizes="180x180" href="/src/img/apple_touch_icon.png">
    <title>Stage à CESI'R - Etudiant</title>
    
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

            echo 'Erreur';

        }
			

		$maximum = 5;
		
		$req_amountOfStudent = "SELECT COUNT(nom_utilisateur) as counter FROM utilisateur WHERE IDRole = 1;";
		
		$result_amountOfStudent = $dbh->query($req_amountOfStudent);
		$str_amountOfStudent = $result_amountOfStudent->fetch(PDO::FETCH_OBJ);
		
		$passive_amountOfStudent = $str_amountOfStudent->counter;
		$amountOfStudent = intval($passive_amountOfStudent);
		
		$result_amountOfStudent->closeCursor();
		
		if(isset($_GET['p'])){
			$page = $_GET['p'];
			
		} else {
			$page = 1;
			
		}
		
		$startValue = ($page - 1) * $maximum;
			
        $reponse = $dbh->query('SELECT utilisateur.id,nom_utilisateur,prenom_utilisateur,nom_promotion,ad_ville,link_logo,login_utilisateur FROM adresse INNER JOIN utilisateur on adresse.id = utilisateur.IDAdresse INNER JOIN utilisateur_promotion on utilisateur.id = utilisateur_promotion.IDUtilisateur INNER JOIN promotion on utilisateur_promotion.IDPromotion = promotion.id WHERE utilisateur.IDRole = 1 AND utilisateur_promotion.active = 1 LIMIT '.$maximum.' OFFSET '.$startValue.';');
        if(!$reponse){
            echo "ERREUR";
            die;
        }

        $html="";
		
		$amountOfResponse = 0;
		
        foreach ($reponse as $donnees) {

			$affcentre = $donnees['ad_ville'];
			$affcentreexplode = explode("_",$affcentre);
			$centre = $affcentreexplode[1];
			
			if(isset($searchFinal)){
				
				$nom = $donnees['nom_utilisateur'];
				$prenom = $donnees['prenom_utilisateur'];
				$searchstring = "$nom $prenom";
				
				
				
				if(preg_match("/{$searchFinal}/i", $searchstring)) {
				
					if($donnees['link_logo']){
						$link_logo = $donnees['link_logo'];
						
						$html.='
						<form method="post" action="">
						<div class="content">
						<div class = "information" >
							<div class = "photoprofile">
								<form>
									<label for="file-input">
										<img src="src/upload/'.($link_logo).'" alt="Profile Image" id="ImageProfile" style = "margin-bottom: 15%;">
									</label>
								</form>
							</div>
							<div class = "input_info">
								<div class="space_info">
									<label>'.$donnees['nom_utilisateur'].' '.$donnees['prenom_utilisateur'] . '</label>
								</div>
								<div class="space_info">
									<label>' . $centre. '</label>
								</div>
								<div class="space_info">
									<label>' . $donnees['nom_promotion']. '</label>
								</div>
							</div>
								<div class = "input_add">
									<input name="viewMySupplyOffer" value="Candidature" type="submit" style="padding: 20px 50px;">
								</div>    
							</div>
							<div class = "button">
								<input type="submit" value="Modifier" name="editStudent" style = "grid-row: 20;">
								<input type="submit" value="Supprimer" name="deleteStudent">
								<input name="username" type="text" value="'.$donnees['login_utilisateur'].'" style="visibility: hidden; display:none" >
							</div>
						</div></form>';
						$amountOfResponse++;
						
					} else {
					
						$html.='
						<form method="post" action="">
						<div class="content">
						<div class = "information" >
							<div class = "photoprofile">
								<form>
									<label for="file-input">
										<img src="src/img/nopp.png" alt="Profile Image" id="ImageProfile" style = "margin-bottom: 15%;">

									</label>
								</form>
							</div>
							<div class = "input_info">
								<div class="space_info">
									<label>'.$donnees['nom_utilisateur'].' '.$donnees['prenom_utilisateur'] . '</label>
								</div>
								<div class="space_info">
									<label>' . $centre. '</label>
								</div>
								<div class="space_info">
									<label>' . $donnees['nom_promotion']. '</label>
								</div>
							</div>
								<div class = "input_add">
									<input name="viewMySupplyOffer" value="Candidature" type="submit" style="padding: 20px 50px;">
								</div>     
							</div>
							<div class = "button">
								
								<input type="submit" value="Modifier" name="editStudent" style = "grid-row: 20;">
								<input type="submit" value="Supprimer" name="deleteStudent">
								<input name="username" type="text" value="'.$donnees['login_utilisateur'].'" style="visibility: hidden; display:none" >
							</div>
						</div></form>';
						$amountOfResponse++;
					}
				}
				
				
			} else {
				
				if($donnees['link_logo']){
					$link_logo = $donnees['link_logo'];
					
					$html.='
					<form method="post" action="">
					<div class="content">
					<div class = "information" >
						<div class = "photoprofile">
							<form>
								<label for="file-input">
									<img src="src/upload/'.($link_logo).'" alt="Profile Image" id="ImageProfile" style = "margin-bottom: 15%;">
								</label>
							</form>
						</div>
						<div class = "input_info">
							<div class="space_info">
								<label>'.$donnees['nom_utilisateur'].' '.$donnees['prenom_utilisateur'] . '</label>
							</div>
							<div class="space_info">
								<label>' . $centre. '</label>
							</div>
							<div class="space_info">
								<label>' . $donnees['nom_promotion']. '</label>
							</div>
						</div>
							<div class = "input_add">
								<input name="viewMySupplyOffer" value="Candidature" type="submit" style="padding: 20px 50px;">
							</div>     
						</div>
						<div class = "button">
							<input type="submit" value="Modifier" name="editStudent" style = "grid-row: 20;">
							<input type="submit" value="Supprimer" name="deleteStudent">
							<input name="username" type="text" value="'.$donnees['login_utilisateur'].'" style="visibility: hidden; display:none" >
						</div>
					</div></form>';
					$amountOfResponse++;
					
					
				} else {
				
					$html.='
					<form method="post" action="">
					<div class="content">
					<div class = "information" >
						<div class = "photoprofile">
							<form>
								<label for="file-input">
									<img src="src/img/nopp.png" alt="Profile Image" id="ImageProfile" style = "margin-bottom: 15%;">

								</label>
							</form>
						</div>
						<div class = "input_info">
							<div class="space_info">
								<label>'.$donnees['nom_utilisateur'].' '.$donnees['prenom_utilisateur'] . '</label>
							</div>
							<div class="space_info">
								<label>' . $centre. '</label>
							</div>
							<div class="space_info">
								<label>' . $donnees['nom_promotion']. '</label>
							</div>
						</div>
							<div class = "input_add">
								<input name="viewMySupplyOffer" value="Candidature" type="submit" style="padding: 20px 50px;">
							</div>      
						</div>
						<div class = "button">
							
							<input type="submit" value="Modifier" name="editStudent" style = "grid-row: 20;">
							<input type="submit" value="Supprimer" name="deleteStudent">
							<input name="username" type="text" value="'.$donnees['login_utilisateur'].'" style="visibility: hidden; display:none" >
						</div>
					</div></form>';
					$amountOfResponse++;
				}
			}
        }
		if($amountOfResponse == 0){
			?><center>
				<label id = "return">
					<p> Aucun étudiant n'a été trouvé avec cette recherche </p>
				</label>
			</center> <?php
		}
		
		
?>		
		<form method="post" action="">
			<div class = "Search">
				<input type="search" name="Search" id="Search" placeholder="🔎Rechercher">
				<input type="submit" value="Rechercher" name="searchStudent" style="visibility: hidden; display:none">
			</div>
		
			<div>
				<?php
				$offre = $amountOfStudent; 
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
        <div id = "Liste_Etudiant">
            <?php echo $html ?>
        </div>
    </body>
	<?php include 'global/footer.php'; ?>
</html>




<script type="text/javascript"  src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</script><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>


<script>
	if('serviceWorker' in navigator){
    navigator.serviceWorker.register('ServiceWorker.js')
    .then( (sw) => console.log('Le Service Worker a été enregistrer', sw))
    .catch((err) => console.log('Le Service Worker est introuvable !!!', err));
   }
</script>