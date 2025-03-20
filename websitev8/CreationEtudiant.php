<?php session_start(); ?>

<?php

if(isset($_POST["addEtudiant"])) {
	//Ajouter un étudiant
	
	//1. On vérifie que tous les champs de textes sont pleins, et qu'ils ne contiennent pas de caractères interdits
	
	$enter_nom = $_POST['nom'];
	$enter_prenom = $_POST['prenom'];
	$enter_username = $_POST['username'];
	$enter_password = $_POST['password'];
	$enter_centre = $_POST['centre'];
	$enter_promotion = $_POST['promotion'];
	
	$checker_nom = trim($enter_nom);
	$checker_prenom = trim($enter_prenom);
	$checker_username = trim($enter_username);
	$checker_password = trim($enter_password);
	$checker_centre = trim($enter_centre);
	$checker_promotion = trim($enter_promotion);
	
	$checker1 = false;
	
	if(empty($checker_promotion)){
		$returnCreateEtudiant = "Vous devez renseigner la promotion de l'étudiant";	
		$checker1 = true;}
	if(empty($checker_centre)){
		$returnCreateEtudiant = "Vous devez renseigner le centre CESI de l'étudiant";
		$checker1 = true;}		
	if(empty($checker_password)){
		$returnCreateEtudiant = "Vous devez renseigner le mot de passe de l'étudiant";
		$checker1 = true;}
	if(empty($checker_username)){
		$returnCreateEtudiant = "Vous devez renseigner le nom d'utilisateur de l'étudiant";
		$checker1 = true;}
	if(empty($checker_prenom)){
		$returnCreateEtudiant = "Vous devez renseigner le prénom de l'étudiant";
		$checker1 = true;}
	if(empty($checker_nom)){
		$returnCreateEtudiant = "Vous devez renseigner le nom de l'étudiant";
		$checker1 = true;}
	if($checker1 == false){
		//On réalise les vérifications pour éviter les injections sql
		$banword1 = "SELECT";
		$banword2 = "DELETE";
		$banword3 = "INSERT";
		$banword4 = "UPDATE";
		$banword5 = ";";
		$checker2 = false;
		if(strpos(strtoupper($checker_nom), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_nom), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_nom), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_nom), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_nom), $banword5) !== false){
			$checker2 = true;}
		
		if(strpos(strtoupper($checker_prenom), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_prenom), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_prenom), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_prenom), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_prenom), $banword5) !== false){
			$checker2 = true;}
		
		if(strpos(strtoupper($checker_username), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_username), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_username), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_username), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($c), $banword5) !== false){
			$checker2 = true;}
		
		if(strpos(strtoupper($checker_password), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_password), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_password), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_password), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_password), $banword5) !== false){
			$checker2 = true;}

		if($checker2 == true){
			$returnCreateEtudiant = "Erreur dans l'envoi de vos données";
		} else {
			//2. On vérifie que l'utilisateur n'existe pas
			
			try
			{
				$dsn = 'mysql:dbname=stageacesir;host=127.0.0.1';
				$user = 'root';
				$password = '';

				$dbh = new PDO($dsn, $user, $password);
			}
			catch(Exception $e){
				// On traite l'erreur
				$returnCreateEtudiant = "Erreur de connexion à la base de données: <br><br>$e";
			}
			
			//Verification
			$req_get_user = "SELECT * FROM utilisateur WHERE login_utilisateur='$checker_username'";
			$get_user = $dbh->query($req_get_user);
			if (!$get_user){
				$returnCreateEtudiant = "Erreur dans la vérification de l'utilisateur : <br><br>$req_get_salt";
			}
			$str_user = $get_user->fetch(PDO::FETCH_OBJ);
			if($str_user){
				$returnCreateEtudiant = "Ce nom d'utilisateur n'est pas disponible";
				$get_user->closeCursor();
			} else {
				$get_user->closeCursor();
			
				//3. On vérifie que l'image reçu est conforme
				$target_dir = "src/upload/";
				$nameOfFile = basename($_FILES["fileToUpload"]["name"]);
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				if (empty($imageFileType)) {
					$returnCreateEtudiant = "Vous devez fournir une image";
				} else {
					$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
					if($check !== false) {
						$uploadOk = 1;
					} else {
						$returnCreateEtudiant = "Ce fichier n'est pas une image";
						$uploadOk = 0;
					}
					// On vérifie si le fichier existe déjà quelquepart dans le dossier
					if (file_exists($target_file)) {
						$returnCreateEtudiant = "Ce fichier existe déjà";
						$uploadOk = 0;
					}
					//On contrôle la taille du fichier, maximum 5000kb ce qui est largement suffisant
					if ($_FILES["fileToUpload"]["size"] > 5000000) {
						$returnCreateEtudiant = "Votre fichier dépasse la taille maximale autorisée (5000KB)";
						$uploadOk = 0;
					}
					// On contrôle le format de l'image en n'autorisant que les fichiers jpg, png et jpeg
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
						$returnCreateEtudiant = "Seuls les fichiers PNG, JPG et JPEG sont autorisés.";
						$uploadOk = 0;
					}
					if($uploadOk == 1){
						//4. On vérifie que le mot de passe fait au moins 8 caractères
						$check_mdp = strlen($checker_password);
						if($check_mdp >= 8){

							//5. On upload l'image
							if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
								
								//6. On encrypte le mot de passe
								
								$req_get_salt = "SELECT * FROM salt WHERE nom='GENERAL_SALT'";
								$get_salt = $dbh->query($req_get_salt);
								if (!$get_salt){
									$returnCreateEtudiant = "Erreur dans l'obtention de la clé SALT : <br><br>$req_get_salt";
									die;
								}
								$salt = $get_salt->fetch(PDO::FETCH_OBJ);
								if($salt){
									$final_salt = $salt->value;
								} else {
									$returnCreateEtudiant = "La clé SALT n'a pas été trouvée dans la base de données.";
									die;
								}
								$get_salt->closeCursor();
								
								$encrypt_mdp = crypt($checker_password, $final_salt);
								
								//7. On récupère l'ID du centre et l'ID de la promotion ($IDCentre ; $IDPromotion)
								
								//Récupération de l'ID de la promotion
								$req_get_id_promotion = "SELECT * FROM promotion WHERE nom_promotion='$checker_promotion'";
								$get_id_promotion = $dbh->query($req_get_id_promotion);
								if (!$get_id_promotion){
									$returnCreateEtudiant = "Erreur dans l'obtention de la promotion : <br><br>$req_get_id_promotion";
									die;
								}
								$str_id_promotion = $get_id_promotion->fetch(PDO::FETCH_OBJ);
								if($str_id_promotion){
									$passive_IDPromotion = $str_id_promotion->id;
								} else {
									$returnCreateEtudiant = "L'ID de promotion n'as pas été trouvé";
									die;
								}
								$get_id_promotion->closeCursor();
								
								
								//Récupération de l'ID du centre CESI
								$req_get_id_centre = "SELECT * FROM adresse WHERE ad_ville='$checker_centre'";
								$get_id_centre = $dbh->query($req_get_id_centre);
								if (!$get_id_centre){
									$returnCreateEtudiant = "Erreur dans l'obtention du centre CESI : <br><br>$req_get_id_centre";
									die;
								}
								$str_id_centre = $get_id_centre->fetch(PDO::FETCH_OBJ);
								if($str_id_centre){
									$IDCentre = $str_id_centre->id;
								} else {
									$returnCreateEtudiant = "L'ID du centre CESI n'as pas été trouvé";
									die;
								}
								$get_id_centre->closeCursor();
								
								
								//8. On ajoute l'utilisateur
								
								$req_finaladd_user = "INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, login_utilisateur, mdp_utilisateur, IDAdresse, IDRole, link_logo) VALUES ('$checker_nom', '$checker_prenom', '$checker_username', '$encrypt_mdp', '$IDCentre', '1', '$nameOfFile')";
								$finaladd_user = $dbh->query($req_finaladd_user);
								$finaladd_user->closeCursor();
								
								//9. On récupère l'ID du nouvel utilisateur qui vient d'être créé ($IDUser)
								
								$req_get_id_user = "SELECT * FROM utilisateur WHERE login_utilisateur='$checker_username'";
								$get_id_user = $dbh->query($req_get_id_user);
								if (!$get_id_user){
									$returnCreateEtudiant = "Erreur dans l'obtention du nouvel utilisateur : <br><br>$req_get_id_user";
									die;
								}
								$str_id_user = $get_id_user->fetch(PDO::FETCH_OBJ);
								if($str_id_centre){
									$passive_IDUser = $str_id_user->id;
								} else {
									$returnCreateEtudiant = "L'ID du nouvel utilisateur n'as pas été trouvé";
									die;
								}
								$get_id_user->closeCursor();
								
								
								//10. On ajoute la liaison utilisateur_promotion ($IDUser; $IDPromotion)
								
								$IDUser = intval($passive_IDUser);
								$IDPromotion = intval($passive_IDPromotion);
								
								$req_add_link_userpromo = "INSERT INTO utilisateur_promotion (IDUtilisateur, IDPromotion) VALUES ('$IDUser', '$IDPromotion')";
								$add_link_userpromo = $dbh->query($req_add_link_userpromo);
								if (!$add_link_userpromo){
									$returnCreateEtudiant = "Erreur dans la requête : <br><br>$req_add_link_userpromo";
								}
								$add_link_userpromo->closeCursor();
								
								$returnCreateEtudiant = "Etudiant: $checker_username créé !";
								
							} else {
								$returnCreateEtudiant = "Impossible d'importer la photo de profil de l'utilisateur";
							}
						} else {
							$returnCreateEtudiant = "Le mot de passe doit contenir au minimum 8 caractères";
						}
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
	<link rel="stylesheet" type="text/css" href="css/creationetudiant.css">
    <title>Stage à CESI'R - Etudiant</title>
    <?php if (!isset($_SESSION['LOGGED_USER'])) { 
		header('Location: index.php'); ?>
    <?php }else { ?>
		<?php if ($_SESSION['PERMS_USER'] == 1) {
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
	<form method="post" action="" enctype="multipart/form-data">
		<center>
		<?php if (isset($returnCreateEtudiant)) { ?>
			<label class = return>
				<p><?php echo $returnCreateEtudiant; ?></p>
			</label>
		<?php } ?>
		</center>
	
	
		<div class = content >
			<div class = photoprofile>
                <img src="src\img\nopp.png" alt="" class = "ImageProfile">
                <input class = inputfilemessage type="file" name="fileToUpload" id="fileToUpload" accept="image/*"/>
                
            </div>
            <div class = input_info>
                <div>
                    <label for="">Nom : <input name="nom" type="text" placeholder="Nom*" style="color: grey;"></label>
                    
                </div>
                <div style="margin-top: 5%;">
                    <label for="">Prénom : <input name="prenom" type="text" placeholder="Prénom*" style="color: grey;"></label>
                    
                </div>
				<div style="margin-top: 5%;">
                    <label for="">Username : <input name="username" type="text" placeholder="Username*" style="color: grey;"></label>
                    
                </div>
				<div style="margin-top: 5%;">
                    <label for="">Mot de passe : <input name="password" type="text" placeholder="Password*" style="color: grey;"></label>
                    
                </div>
				
				
				
				<div style="margin-top: 5%;">
                    <label for="">Centre : 
						<select name="centre" id="pet-select" style="color: grey;">
							<option value="">Choisissez un centre</option>
							
							<?php 
							//Affichage des options de la liste déroulante des centres CESI
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
								$req_get_centres = "SELECT * FROM adresse WHERE id='$ID'";
								$get_centres = $dbh->query($req_get_centres);
								if (!$get_centres){
									echo "Erreur dans l'obtention des promotions : <br>$req_get_adresse\n";
									die;
								}
								$str_centres = $get_centres->fetch(PDO::FETCH_OBJ);
								if(!$str_centres) {
									$get_centres->closeCursor();
									$controller = true;
									
								} else {
									$promo = $str_centres->ad_ville;
									$checkIsCentre = "centre_";
									if(strpos($promo, $checkIsCentre) !== false){
										$affcentreexplode = explode("_",$promo);
										$affcentre = $affcentreexplode[1];
										echo "<option value='".($promo)."'>".($affcentre)."</option>";
										$get_centres->closeCursor();
										
									}
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
							<option value="">Choisissez une promotion</option>
							
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
            </div>
            <div class = input_add>
				<input type="submit" name="addEtudiant" value="Valider" style="padding: 20px 50px;">
            </div>
			<br><br><br>
		</div>
	</form>
</body>
<?php include 'global/footer.php'; ?>
</html>


