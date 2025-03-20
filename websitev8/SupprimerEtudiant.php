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
	
	//On vérifie que l'utilisateur éxiste
	
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
	
	
	$usernameStudent = "NULL";
	if(isset($_COOKIE['deleteStudent'])){
		$usernameStudent = $_COOKIE['deleteStudent'];
	}
	
	
	$req_get_utilisateur = "SELECT * FROM utilisateur WHERE login_utilisateur = '$usernameStudent'";
	$get_utilisateur = $dbh->query($req_get_utilisateur);
	if (!$get_utilisateur){
		$error = "Erreur dans la requête : <br><br>$req_get_utilisateur";
	}		
	$string_utilisateur = $get_utilisateur->fetch(PDO::FETCH_OBJ);
	if(!$string_utilisateur){
		$error = "Ce compte étudiant n'éxiste pas.";
		$get_utilisateur->closeCursor();
	} else {
		//On récupère l'ID de l'étudiant et le lien du logo
	
		$link_logo = $string_utilisateur->link_logo;
		$passive_IDUtilisateur = $string_utilisateur->id;
		$IDUtilisateur = intval($passive_IDUtilisateur);
		
		$get_utilisateur->closeCursor();
		
		//On vérifie que l'étudiant n'as pas de candidature en cours
		
		$req_check_candidature = "SELECT * FROM candidature WHERE IDUtilisateur = '$IDUtilisateur'";
		$check_candidature = $dbh->query($req_check_candidature);
		if (!$check_candidature){
			$error = "Erreur dans la requête : <br><br>$req_check_candidature";
		}		
		$string_check_candidature = $check_candidature->fetch(PDO::FETCH_OBJ);
		if(!$string_check_candidature){
			$check_candidature->closeCursor();
		
			
			
		
		
			//On désactive les liens utilisateur_promotion de l'utilisateur et on met les id sur NULL
			
			$controller = false;
			$ID = 1;
			while($controller == false){
					
				$req_get_promotionlink = "SELECT * FROM utilisateur_promotion WHERE id='$ID'";
				$get_promotionlink = $dbh->query($req_get_promotionlink);
				if (!$get_promotionlink){
					$error = "Erreur dans l'obtention des link utilisateur promotion : <br>$req_get_promotionlink\n";
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
							echo "update $ID<br>";				
							$req_disable_promotionlink = "UPDATE `utilisateur_promotion` SET `active`='0', `IDUtilisateur`=NULL, `IDPromotion`=NULL  WHERE id = '$ID'";
							$disable_promotionlink = $dbh->query($req_disable_promotionlink);
							$disable_promotionlink->closeCursor();
													
						}	
													
					} 
					$get_promotionlink->closeCursor();
					$ID++;			
				}
			}
			
			//On supprime la photo de profil de l'utilisateur dans les fichiers
			
			
			if($link_logo){
				$pass = false;
				$fichier_link = "src/upload/$link_logo";
				
				
				if(unlink($fichier_link)){
					$pass = true;		
				} else {
					$error = "Impossible de supprimer le logo de l'utilisateur du le serveur. Merci de réessayer.";
				}
			} else {
				$pass = true;
			}
			
			if($pass == true){
				
				//On supprime l'utilisateur
				
				$req_delete_utilisateur = "DELETE FROM `utilisateur` WHERE id='$IDUtilisateur'";
				$delete_utilisateur = $dbh->query($req_delete_utilisateur);
				
				$delete_utilisateur->closeCursor();
				//On renvoie l'utilisateur sur la page de choix des pilotes
				unset($_COOKIE['deleteStudent']);
				header('Location: RechercheEtudiant.php');
				
				
				
				
			}
		} else {
			$error = "Cet étudiant à déjà postulé pour une offre et ne peut donc pas être supprimé.";
			$check_candidature->closeCursor();
		}
		
	}
?> 

<?php
//Si l'erreur est définie, alors on affiche la page d'erreur
if (isset($error)) { 
	//Supression du cookie
unset($_COOKIE['deleteStudent']);
//Affichage de la page
?>




	<!DOCTYPE html>
	<html lang="en">
	<head>

		<?php 
			include 'global/header.php';
		?>
		
		<link rel="stylesheet" type="text/css" href="css/modificationetudiant.css? v=5">
		<link rel="manifest" href="manifest.json">
		<meta name="theme-color" content="#003366">
		<link rel="apple-touch-icon" sizes="180x180" href="/src/img/apple_touch_icon.png">
		<title>Stage à CESI'R - Etudiant</title>
		
		
		
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
			<a href="RechercheEtudiant.php"><button class = back>Retour</button></a>
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