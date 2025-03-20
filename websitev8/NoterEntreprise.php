<?php session_start(); ?>


<?php 
	
if(isset($_POST["noteEntreprise"])) {
	//1. On vérifie qu'une information est reçu et son contenu pour éviter les injection SQL
	
	$enter_note = $_POST["note"];
	$checker_note = trim($enter_note);
	if(!empty($checker_note)){
		$banword1 = "SELECT";
		$banword2 = "DELETE";
		$banword3 = "INSERT";
		$banword4 = "UPDATE";
		$banword5 = ";";
		
		$checker2 = false;
		if(strpos(strtoupper($checker_note), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_note), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_note), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_note), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_note), $banword5) !== false){
			$checker2 = true;}
			
		if($checker2 == false){
			
			//2. On vérifie si l'utilisateur à déjà noté l'entreprise
			
			
			//On récupère les informations de l'offre et de l'utilisateur
			
			$nameOffre = "NULL";
			if(isset($_COOKIE['noteEntreprise'])){
				$nameOffre = $_COOKIE['noteEntreprise'];
			}
			
			
			
			//Récupération des informations de l'offre
						
			try
			{
				$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
				$user = 'root';
				$password = '';
					$dbh = new PDO($dsn, $user, $password);
			}
			catch(Exception $e){
				// On traite l'erreur
				$returnNoteEntreprise = "Erreur de connexion à la base de données: <br><br>$e";
			}
			$req_get_offre = "SELECT * FROM offre WHERE nom_offre='$nameOffre'";
			$get_offre = $dbh->query($req_get_offre);
			if (!$get_offre){
				$returnNoteEntreprise = "Erreur dans l'obtention de l'offre : <br><br>$req_get_offre";
			}
			$str_offre = $get_offre->fetch(PDO::FETCH_OBJ);
			if($str_offre){
				$passive_IDOffre = $str_offre->id;
				$IDOffre = intval($passive_IDOffre);
		
			} else {
				$returnNoteEntreprise = "Cette offre n'éxiste pas";
			
			}
			$get_offre->closeCursor();
			
			
			
			
			//On récupère l'ID de l'utilisateur
			$loginUtilisateur = $_SESSION['LOGGED_USER'];
			$req_get_user = "SELECT * FROM utilisateur WHERE login_utilisateur='$loginUtilisateur'";
			$get_user = $dbh->query($req_get_user);
			if (!$get_user){
				$returnNoteEntreprise = "Erreur dans l'obtention de l'utilisateur : <br><br>$req_get_user";
			}
			$str_user = $get_user->fetch(PDO::FETCH_OBJ);
			if($str_user){
				$passive_IDUser = $str_user->id;
				$IDUser = intval($passive_IDUser);
				
							
			} else {
				$returnNoteEntreprise = "Cet utilisateur n'existe pas";
				
			}
			$get_user->closeCursor();
			
			//On vérifie si l'utilisateur à déjà noté l'entreprise
			
			$req_get_candidature = "SELECT * FROM candidature WHERE IDUtilisateur='$IDUser' AND IDOffre='$IDOffre'";
			$get_candidature = $dbh->query($req_get_candidature);
			if (!$get_candidature){
				$returnNoteEntreprise = "Erreur dans l'obtention de la candidature : <br><br>$req_get_user";
				
			}
			$str_candidature = $get_candidature->fetch(PDO::FETCH_OBJ);
			if($str_candidature){
				$passive_IDCandidature = $str_candidature->id;
				$IDCandidature = intval($passive_IDCandidature);
				
				$passive_note = $str_candidature->note;
				if($passive_note){
					$note = intval($passive_note);
				}
			}
			$get_candidature->closeCursor();
			
			
			//3. On note l'entreprise en fonction
			
			if(isset($note)){
				//On modifie la note de l'utilisateur
				
				$req_update_note = "UPDATE `candidature` SET `note`='$enter_note' WHERE id='$IDCandidature'";
				$update_note = $dbh->query($req_update_note);
				if (!$update_note){
					$returnNoteEntreprise = "Erreur dans la modification de la candidature : <br><br>$req_update_note";
				}
				$update_note->closeCursor();
				

			} else {
				$req_create_candidature = "INSERT INTO `candidature`(`etat`, `effectue`, `note`, `file_cv`, `file_lm`, `IDUtilisateur`, `IDOffre`, `texte`) VALUES (FALSE,FALSE,'$enter_note',NULL,NULL,'$IDUser','$IDOffre','empty')";
				$create_candidature = $dbh->query($req_create_candidature);
				if (!$create_candidature){
					$returnNoteEntreprise = "Erreur dans la modification de la candidature : <br><br>$req_create_candidature";
				}
				$create_candidature->closeCursor();
			}
			
			
			//4. On supprime le cookie
			unset($_COOKIE['noteEntreprise']);
			//5. On renvoie l'utilisateur sur la liste des offres
			header('Location: RechercheOffre.php'); 
		} else {
			$returnNoteEntreprise = "Erreur dans l'envoi de vos données";
		}
		
	} else {
		$returnNoteEntreprise = "Vous devez noter l'entreprise";
	}
	
	
	
}	
	
	
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
		
		<a href="unlog.php"><button class = login>Déconnexion</button></a>
		<a href="profil.php"><button class = login>Mon Profil</button></a>
		
	<?php } ?>
	
	
	<?php 
		include 'global/navbar.php';
	?>
		


</head>
<body>
	<center>
		
		<?php
		//On récupère les informations de l'offre et de l'utilisateur
		
		$nameOffre = "NULL";
		if(isset($_COOKIE['noteEntreprise'])){
			$nameOffre = $_COOKIE['noteEntreprise'];
		}
		
		
		
		//Récupération des informations de l'offre
					
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
		$req_get_offre = "SELECT * FROM offre WHERE nom_offre='$nameOffre'";
		$get_offre = $dbh->query($req_get_offre);
		if (!$get_offre){
			echo "Erreur dans l'obtention de l'offre : <br><br>$req_get_offre";
		}
		$str_offre = $get_offre->fetch(PDO::FETCH_OBJ);
		if($str_offre){
			$passive_IDOffre = $str_offre->id;
			$IDOffre = intval($passive_IDOffre);
			$passive_IDEntreprise = $str_offre->IDEntreprise;
			$IDEntreprise = intval($passive_IDEntreprise);
			
						
		} else {
			echo "<label class = return>
				<p>Cette offre n'éxiste pas</p>
			</label>";
			die;
		}
		$get_offre->closeCursor();
		
		//On récupère le nom de l'entreprise
		
		
		$req_get_entreprise = "SELECT * FROM entreprise WHERE id='$IDEntreprise'";
		$get_entreprise = $dbh->query($req_get_entreprise);
		if (!$get_entreprise){
			echo "Erreur dans l'obtention de l'entreprise : <br><br>$req_get_entreprise";
		}
		$str_entreprise = $get_entreprise->fetch(PDO::FETCH_OBJ);
		if($str_entreprise){
			$nameEntreprise = $str_entreprise->nom_entreprise;
			
						
		} else {
			echo "<label class = return>
				<p>Cette entreprise n'éxiste pas</p>
			</label>";
			die;
		}
		$get_entreprise->closeCursor();
		
		
		//On récupère l'ID de l'utilisateur
		$loginUtilisateur = $_SESSION['LOGGED_USER'];
		$req_get_user = "SELECT * FROM utilisateur WHERE login_utilisateur='$loginUtilisateur'";
		$get_user = $dbh->query($req_get_user);
		if (!$get_user){
			echo "Erreur dans l'obtention de l'utilisateur : <br><br>$req_get_user";
		}
		$str_user = $get_user->fetch(PDO::FETCH_OBJ);
		if($str_user){
			$passive_IDUser = $str_user->id;
			$IDUser = intval($passive_IDUser);
			
						
		} else {
			echo "<label class = return>
				<p>Cet utilisateur n'éxiste pas</p>
			</label>";
			die;
		}
		$get_user->closeCursor();
		
		//On vérifie si l'utilisateur à déjà noté l'entreprise
		
		$req_get_candidature = "SELECT * FROM candidature WHERE IDUtilisateur='$IDUser' AND IDOffre='$IDOffre'";
		$get_candidature = $dbh->query($req_get_candidature);
		if (!$get_candidature){
			echo "Erreur dans l'obtention de la candidature : <br><br>$req_get_user";
			die;
		}
		$str_candidature = $get_candidature->fetch(PDO::FETCH_OBJ);
		if($str_candidature){
			$passive_IDCandidature = $str_candidature->id;
			$etat = $str_candidature->etat;
			if($etat == 1){
				$IDCandidature = intval($passive_IDCandidature);
				
				$passive_note = $str_candidature->note;
				if($passive_note){
					$note = intval($passive_note);
				}
			} else {
				header('Location: RechercheOffre.php'); 
			}
		
		}
		$get_candidature->closeCursor();

		?>
		
		
		<form method="post" action="">
			
			<label class = myTitle>
				<p>Noter l'entreprise <?php echo "$nameEntreprise"; ?></p>
			</label>

			<?php if (isset($returnNoteEntreprise)) { ?>
				<label class = return>
					<p><?php echo $returnNoteEntreprise; ?></p>
				</label>
			<?php } ?>
			
			<div style="margin-top: 5%;" class = "Adress">
			
				<?php 
				if(isset($note)){
					echo "<label for=''>Votre note : <input name='note' min='1' max='10' type='number' value='".($note)."' style='color: grey;'></label>"; 
				} else {
					echo "<label for=''>Votre note : <input name='note' min='1' max='10' type='number' placeholder='Note' style='color: grey;'></label>"; 
				}
				?>
						
			</div>
			<input class = back type="submit" name="noteEntreprise" value="Confirmer">
			
		</form>
		
		
		
	</center>
		

	</body>
	<?php include 'global/footer.php'; ?>
	</html>





