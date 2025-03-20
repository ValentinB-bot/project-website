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
	
	//On vérifie que l'offre éxiste
	
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
	
	
	$offre_name = "NULL";
	if(isset($_COOKIE['deleteOffre'])){
		$offre_name = $_COOKIE['deleteOffre'];
	}
	
	
	$req_get_offre = "SELECT * FROM offre WHERE nom_offre = '$offre_name'";
	$get_offre = $dbh->query($req_get_offre);
	if (!$get_offre){
		$error = "Erreur dans la requête : <br><br>$req_get_entreprise";
	}		
	$string_offre = $get_offre->fetch(PDO::FETCH_OBJ);
	if(!$string_offre){
		$error = "Cette offre n'éxiste pas.";
		$get_offre->closeCursor();
	} else {
		//On récupère l'ID de l'offre
		
		
		$passive_IDOffre = $string_offre->id;
		$IDOffre = intval($passive_IDOffre);
		
		$get_offre->closeCursor();
	
		
		//On vérifie que l'offre n'est pas utilisé dans une candidature
		

		$req_check_candidature = "SELECT * FROM candidature WHERE IDOffre = '$IDOffre'";
		$check_candidature = $dbh->query($req_check_candidature);
		if (!$check_candidature){
			$error = "Erreur dans la requête : <br><br>$req_check_candidature";
		}		
		$string_check_candidature = $check_candidature->fetch(PDO::FETCH_OBJ);
		if(!$string_check_candidature){
			
			$check_candidature->closeCursor();
			
			//On vérifie que l'offre n'est pas utilisé dans la liste des favoris d'un utilisateur
			
			$req_check_wishlist = "SELECT * FROM utilisateur_favori WHERE IDOffre = '$IDOffre'";
			$check_wishlist = $dbh->query($req_check_wishlist);
			if (!$check_wishlist){
				$error = "Erreur dans la requête : <br><br>$req_check_wishlist";
			}		
			$string_check_wishlist = $check_wishlist->fetch(PDO::FETCH_OBJ);
			if(!$string_check_wishlist){
				
				$check_wishlist->closeCursor();
				
				//On supprime l'offre
		
				$req_delete_offre = "DELETE FROM `offre` WHERE id='$IDOffre'";
				$delete_offre = $dbh->query($req_delete_offre);
				
				$delete_offre->closeCursor();

				//On renvoie l'utilisateur sur la page de choix des offres
				header('Location: RechercheOffre.php');
				unset($_COOKIE['deleteOffre']);
				
			} else {
				$error = "Cette offre est utilisée dans une WishList d'un étudiant. Elle ne peut donc pas être supprimée.";
				$check_wishlist->closeCursor();
			}
		
			
		} else {
			$error = "Cette offre est utilisée dans une candidature. Elle ne peut donc pas être supprimée.";
			$check_candidature->closeCursor();
		}
	}
	
?> 

<?php
//Si l'erreur est définie, alors on affiche la page d'erreur
if (isset($error)) { 
	//Supression du cookie
unset($_COOKIE['deleteOffre']);
//Affichage de la page
?>




	<!DOCTYPE html>
	<html lang="en">
	<head>

		<?php 
			include 'global/header.php';
		?>
		
		<link rel="stylesheet" type="text/css" href="css/modificationoffre.css? v=5">
		<link rel="manifest" href="manifest.json">
		<meta name="theme-color" content="#003366">
		<link rel="apple-touch-icon" sizes="180x180" href="/src/img/apple_touch_icon.png">
		<title>Stage à CESI'R - Offre</title>
		
		
		
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
			<a href="RechercheOffre.php"><button class = back>Retour</button></a>
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