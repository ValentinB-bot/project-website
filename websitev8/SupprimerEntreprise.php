<?php session_start(); ?>


<?php 
	//On vérifie que l'utilisateur à accès à cette page
	
	
	
	if (!isset($_SESSION['LOGGED_USER'])) { 
		header('Location: index.php');
    } else {
		if ($_SESSION['PERMS_USER'] == 1) {
			header('Location: RechercheEntreprise.php');
		}		
	} 
	
	//On vérifie que l'entreprise éxiste
	
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
		$error = "Erreur de connexion à la base de données: <br><br>$e";
	}
	
	$entreprise_name = "NULL";
	if(isset($_COOKIE['deleteEntreprise'])){
		$entreprise_name = $_COOKIE['deleteEntreprise'];
	}
	
	$req_get_entreprise = "SELECT * FROM entreprise WHERE nom_entreprise = '$entreprise_name'";
	$get_entreprise = $dbh->query($req_get_entreprise);
	if (!$get_entreprise){
		$error = "Erreur dans la requête : <br><br>$req_get_entreprise";
	}		
	$string_entreprise = $get_entreprise->fetch(PDO::FETCH_OBJ);
	if(!$string_entreprise){
		$error = "Cette entreprise n'éxiste pas.";
		$get_entreprise->closeCursor();
	} else {
		//On récupère l'ID de l'entreprise
		
		$link_logo = $string_entreprise->link_logo;
		$passive_IDEntreprise = $string_entreprise->id;
		$IDEntreprise = intval($passive_IDEntreprise);
		
		$get_entreprise->closeCursor();
		
		//On vérifie que l'entreprise n'est pas utilisé dans une offre
		
		
		$req_check_offre = "SELECT * FROM offre WHERE IDEntreprise = '$IDEntreprise'";
		$check_offre = $dbh->query($req_check_offre);
		if (!$check_offre){
			$error = "Erreur dans la requête : <br><br>$req_check_offre";
		}		
		$string_check_offre = $check_offre->fetch(PDO::FETCH_OBJ);
		if(!$string_check_offre){
			$check_offre->closeCursor();
		
			//On désactive les liens adresse_entreprise de l'entreprise et on met les id sur NULL
			$controller = false;
			$ID = 1;
			while($controller == false){
							
				$req_get_entrepriselink = "SELECT * FROM entreprise_adresse WHERE id='$ID'";
				$get_entrepriselink = $dbh->query($req_get_entrepriselink);
				if (!$get_entrepriselink){
					$error = "Erreur dans l'obtention des link adresse entreprise : <br>$req_get_entrepriselink\n";
					die;
				}
				$str_entrepriselink = $get_entrepriselink->fetch(PDO::FETCH_OBJ);
				if(!$str_entrepriselink) {
					$get_entrepriselink->closeCursor();
					$controller = true;
												
				} else {
					//On vérifie si active est sur 1:
					$active = $str_entrepriselink->active;
					if($active == 1) {
									
						//On récupère les id d'entreprise
						$passive_IDLink = $str_entrepriselink->IDEntreprise;
						$IDLink = intval($passive_IDLink);
						//On test l'id en cours avec l'id de l'entreprise
						if($IDLink == $IDEntreprise){
						//Si c'est la même, alors on désactive l'ID de link ($ID)

							$req_disable_entrepriselink = "UPDATE `entreprise_adresse` SET `active`='0', `IDEntreprise`=NULL, `IDAdresse`=NULL WHERE id = '$ID'";
							$disable_entrepriselink = $dbh->query($req_disable_entrepriselink);
							$disable_entrepriselink->closeCursor();
						}		
					} 
				$get_entrepriselink->closeCursor();
				$ID++;			
				}
			}

			//On supprime le logo de l'entreprise dans les fichiers
			
			$fichier_link = "src/upload/$link_logo";
			if(unlink($fichier_link)){
					
			} else {
				$error = "Impossible de supprimer le logo de l'entreprise du le serveur. Merci de réessayer.";
			}
			
			
			
			
			
			//On supprime l'entreprise
			
			$req_delete_entreprise = "DELETE FROM `entreprise` WHERE id='$IDEntreprise'";
			$delete_entreprise = $dbh->query($req_delete_entreprise);
			
			$delete_entreprise->closeCursor();
			
			
			
			//On renvoie l'utilisateur sur la page de choix des entreprises
			
			header('Location: RechercheEntreprise.php');
			unset($_COOKIE['deleteEntreprise']);
		} else {
			$error = "Cette entreprise est utilisée dans une offre. Elle ne peut donc pas être supprimée.";
			$check_offre->closeCursor();
		}
	}
	
	
	
?> 




<?php
//Si l'erreur est définie, alors on affiche la page d'erreur
if (isset($error)) { 
	//Supression du cookie
unset($_COOKIE['deleteEntreprise']);
//Affichage de la page
?>




	<!DOCTYPE html>
	<html lang="en">
	<head>


		<?php 
			include 'global/header.php';
		?>
		<link rel="stylesheet" type="text/css" href="css/modificationentreprise.css? v=5">
		<link rel="manifest" href="manifest.json">
		<meta name="theme-color" content="#003366">
		<link rel="apple-touch-icon" sizes="180x180" href="/src/img/apple_touch_icon.png">
		<title>Stage à CESI'R - Entreprise</title>
		
		
		
		<?php if (!isset($_SESSION['LOGGED_USER'])) { 
			header('Location: index.php'); ?>
		<?php }else { ?>
			<?php if ($_SESSION['PERMS_USER'] == 1) {
				header('Location: RechercheOffre.php'); ?>
			<?php } ?>	
				<a href="unlog.php"><button class = login>Déconnexion</button></a>
				<a href="profil.php"><button class = login>Mon Profil</button></a>
			
		<?php } ?>
		
		
		<?php 
		include 'global/navbar.php';
		?>
		


	</head>
	<body>
		<center>
			<label class = return>
				<p><?php echo $error; ?></p>
			</label>
			<a href="RechercheEntreprise.php"><button class = back>Retour</button></a>
		</center>
		

	</body>
	<?php include 'global/footer.php'; ?>
	</html>





<?php } ?>


<script>
	if('serviceWorker' in navigator){
    navigator.serviceWorker.register('ServiceWorker.js')
    .then( (sw) => console.log('Le Service Worker a été enregistrer', sw))
    .catch((err) => console.log('Le Service Worker est introuvable !!!', err));
   }
</script>