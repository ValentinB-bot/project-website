<?php session_start(); ?>


<?php 
if(isset($_POST["backToListEntreprise"])) {
	//Retour à la liste des entreprises
	header('Location: RechercheEntreprise.php'); 
}
?>


<?php

if(isset($_POST["editEntreprise"])) {
	//Modification d'une entreprise
	
	
	
	//1. Vérification de tous les champs texte (au moins une adresse complète)
	
	//On récupère tous les champs reçu
	$enter_secteur = $_POST['secteur'];
	$enter_stagaires = $_POST['stagaires'];
	$enter_confiance = $_POST['confiance'];
	
	$enter_ad_pays_1 = $_POST['pays1'];
	$enter_ad_ville_1 = $_POST['ville1'];
	$enter_ad_postal_1 = $_POST['postal1'];
	$enter_ad_rue_1 = $_POST['rue1'];
	$enter_numero_1 = $_POST['numero1'];
	
	$enter_ad_pays_2 = $_POST['pays2'];
	$enter_ad_ville_2 = $_POST['ville2'];
	$enter_ad_postal_2 = $_POST['postal2'];
	$enter_ad_rue_2 = $_POST['rue2'];
	$enter_numero_2 = $_POST['numero2'];
	
	$enter_ad_pays_3 = $_POST['pays3'];
	$enter_ad_ville_3 = $_POST['ville3'];
	$enter_ad_postal_3 = $_POST['postal3'];
	$enter_ad_rue_3 = $_POST['rue3'];
	$enter_numero_3 = $_POST['numero3'];
	
	$checker_secteur = trim($enter_secteur);
	$checker_stagiaires = trim($enter_stagaires);
	$checker_confiance = trim($enter_confiance);
	
	$checker_ad_pays_1 = trim($enter_ad_pays_1);
	$checker_ad_ville_1 = trim($enter_ad_ville_1);
	$checker_ad_postal_1 = trim($enter_ad_postal_1);
	$checker_ad_rue_1 = trim($enter_ad_rue_1);
	$checker_ad_numero_1 = trim($enter_numero_1);
	
	$checker_ad_pays_2 = trim($enter_ad_pays_2);
	$checker_ad_ville_2 = trim($enter_ad_ville_2);
	$checker_ad_postal_2 = trim($enter_ad_postal_2);
	$checker_ad_rue_2 = trim($enter_ad_rue_2);
	$checker_ad_numero_2 = trim($enter_numero_2);
	
	$checker_ad_pays_3 = trim($enter_ad_pays_3);
	$checker_ad_ville_3 = trim($enter_ad_ville_3);
	$checker_ad_postal_3 = trim($enter_ad_postal_3);
	$checker_ad_rue_3 = trim($enter_ad_rue_3);
	$checker_ad_numero_3 = trim($enter_numero_3);
	
	//On vérifie que les champs obligatoires ne sont pas vide
	$checker1 = false;
	
	if(empty($checker_ad_numero_1)){
		$returnEditEntreprise = "Vous devez renseigner le numéro de l'adresse n°1 ";	
		$checker1 = true;}
	if(empty($checker_ad_rue_1)){
		$returnEditEntreprise = "Vous devez renseigner la rue de l'adresse n°1 ";	
		$checker1 = true;}
	if(empty($checker_ad_postal_1)){
		$returnEditEntreprise = "Vous devez renseigner le code postal de l'adresse n°1";	
		$checker1 = true;}
	if(empty($checker_ad_ville_1)){
		$returnEditEntreprise = "Vous devez renseigner la ville de l'adresse n°1";	
		$checker1 = true;}
	if(empty($checker_ad_pays_1)){
		$returnEditEntreprise = "Vous devez renseigner le pays de l'adresse n°1";	
		$checker1 = true;}
	if(empty($checker_confiance)){
		$returnEditEntreprise = "Vous devez renseigner la confiance des pilotes";	
		$checker1 = true;}
	if(empty($checker_stagiaires)){
		$returnEditEntreprise = "Vous devez renseigner le nombre de stagiaires";	
		$checker1 = true;}
	if(empty($checker_secteur)){
		$returnEditEntreprise = "Vous devez renseigner la compétence de l'entreprise";	
		$checker1 = true;}
	
	
	if($checker1 == false){
		//Contrôle des injéctions SQL de TOUS les champs
		$banword1 = "SELECT";
		$banword2 = "DELETE";
		$banword3 = "INSERT";
		$banword4 = "UPDATE";
		$banword5 = ";";
		$checker2 = false;
			
		if(strpos(strtoupper($checker_secteur), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_secteur), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_secteur), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_secteur), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_secteur), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_stagiaires), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_stagiaires), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_stagiaires), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_stagiaires), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_stagiaires), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_confiance), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_confiance), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_confiance), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_confiance), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_confiance), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_pays_1), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_pays_1), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_pays_1), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_pays_1), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_pays_1), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_ville_1), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_ville_1), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_ville_1), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_ville_1), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_ville_1), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_postal_1), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_postal_1), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_postal_1), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_postal_1), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_postal_1), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_rue_1), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_rue_1), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_rue_1), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_rue_1), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_rue_1), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_numero_1), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_numero_1), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_numero_1), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_numero_1), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_numero_1), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_pays_2), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_pays_2), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_pays_2), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_pays_2), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_pays_2), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_ville_2), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_ville_2), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_ville_2), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_ville_2), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_ville_2), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_postal_2), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_postal_2), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_postal_2), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_postal_2), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_postal_2), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_rue_2), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_rue_2), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_rue_2), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_rue_2), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_rue_2), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_numero_2), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_numero_2), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_numero_2), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_numero_2), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_numero_2), $banword5) !== false){
			$checker2 = true;}
		
		
		if(strpos(strtoupper($checker_ad_pays_3), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_pays_3), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_pays_3), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_pays_3), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_pays_3), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_ville_3), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_ville_3), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_ville_3), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_ville_3), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_ville_3), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_postal_3), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_postal_3), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_postal_3), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_postal_3), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_postal_3), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_rue_3), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_rue_3), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_rue_3), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_rue_3), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_rue_3), $banword5) !== false){
			$checker2 = true;}
			
		if(strpos(strtoupper($checker_ad_numero_3), $banword1) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_numero_3), $banword2) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_numero_3), $banword3) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_numero_3), $banword4) !== false){
			$checker2 = true;}
		if(strpos(strtoupper($checker_ad_numero_3), $banword5) !== false){
			$checker2 = true;}
		
			
		if($checker2 == true){
			$returnEditEntreprise = "Erreur dans l'envoi de vos données";
		} else {
		
			//2. On compte le nombre d'adresse
			
			$amountOfAddressOfEntreprise = 1;
			
			$checker3 = false;
			if(empty($checker_ad_pays_2)){
				$checker3 = true;}
			if(empty($checker_ad_ville_2)){
				$checker3 = true;}
			if(empty($checker_ad_postal_2)){
				$checker3 = true;}
			if(empty($checker_ad_rue_2)){
				$checker3 = true;}
			if(empty($checker_ad_numero_2)){
				$checker3 = true;}
			if($checker3 == false){
				$amountOfAddressOfEntreprise++;
				
				$checker4 = false;
				if(empty($checker_ad_pays_3)){
					$checker4 = true;}
				if(empty($checker_ad_ville_3)){
					$checker4 = true;}
				if(empty($checker_ad_postal_3)){
					$checker4 = true;}
				if(empty($checker_ad_rue_3)){
					$checker4 = true;}
				if(empty($checker_ad_numero_3)){
					$checker4 = true;}
				if($checker4 == false){
					$amountOfAddressOfEntreprise++;
				}
				
			}

			//3. On vérifie si une image est reçu
			$hasReceiveImage = false;
			
			$target_dir = "src/upload/";
			$nameOfFile = basename($_FILES["fileToUpload"]["name"]);
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			if (!empty($imageFileType)) {
				$hasReceiveImage = true;
				//Si c'est le cas on effectue les vérifications dessus
				
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				if($check !== false) {
					$uploadOk = 1;
				} else {
					$returnEditEntreprise = "Ce fichier n'est pas une image";
					$uploadOk = 0;
				}
					// On vérifie si le fichier existe déjà quelque part dans le dossier
				if (file_exists($target_file)) {
					$returnEditEntreprise = "Ce fichier existe déjà";
					$uploadOk = 0;
				}
					//On contrôle la taille du fichier, maximum 5000kb ce qui est largement suffisant
				if ($_FILES["fileToUpload"]["size"] > 5000000) {
					$returnEditEntreprise = "Votre fichier dépasse la taille maximale autorisée (5000KB)";
					$uploadOk = 0;
				}
				// On contrôle le format de l'image en n'autorisant que les fichiers jpg, png et jpeg
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
					$returnEditEntreprise = "Seuls les fichiers PNG, JPG et JPEG sont autorisés.";
					$uploadOk = 0;
				}
			}
			
			if($uploadOk == 1){
				//A partir d'ici l'envoie est confirmé: On insère les données
				
				
				//4. On récupère l'ID de l'entreprise
				
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
					$returnEditEntreprise = "Erreur de connexion à la base de données: <br><br>$e";
				}
				
				//Recupération de l'ID de l'entreprise
				
				$entreprise_name = "NULL";
				if(isset($_COOKIE['editEntreprise'])){
					$entreprise_name = $_COOKIE['editEntreprise'];
				}
				
				
				$req_get_entrepriseid = "SELECT * FROM entreprise WHERE nom_entreprise = '$entreprise_name'";
				$get_entrepriseid = $dbh->query($req_get_entrepriseid);
				if (!$get_entrepriseid){
					$returnEditEntreprise = "Erreur dans la requête : <br><br>$get_entrepriseid";
				}
				$string_get_entrepriseid = $get_entrepriseid->fetch(PDO::FETCH_OBJ);
				$passive_IDEntreprise = $string_get_entrepriseid->id;
				$IDEntreprise = intval($passive_IDEntreprise);
				$get_entrepriseid->closeCursor();

				$imgpass = true;
				if($hasReceiveImage == true){
					
					//Si on à une image
					//5. On upload l'image et on modifie le link_logo uniquement dans l'entreprise
					$checker5 = false;
					$imgpass = false;
					
					//On commence par récupérer l'ancienne image de profil
					
					$req_get_photo = "SELECT * FROM entreprise WHERE id='$IDEntreprise'";
					$get_photo = $dbh->query($req_get_photo);
					
					if (!$get_photo){
						$returnEditEntreprise = "Erreur dans l'obtention de vos informations : <br><br>$req_get_photo";
					}
					$user_obj = $get_photo->fetch(PDO::FETCH_OBJ);
					if($user_obj){
						$link_logo = $user_obj->link_logo;
					} else {
						$checkerprofilenpp = false;
					}
					
					$get_photo->closeCursor();
					
					//On supprime cette image du fichier
					
					$fichier_link = "src/upload/$link_logo";
					if(unlink($fichier_link)){
						$checker5 = true;		
					} else {
						$returnEditEntreprise = "Impossible de remplacer votre image de profil sur le serveur. Merci de réessayer.";
					}
					
					if($checker5 == true){

						//On upload le nouveau fichier
						$checker6 = false;
						
						if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
							$checker6 = true;
						}
						
						
						if($checker6 == true){
							//On modifie le lien link_logo en base de données
							
							$req_change_pp = "UPDATE `entreprise` SET `link_logo`='$nameOfFile' WHERE id = '$IDEntreprise'";
							$change_pp = $dbh->query($req_change_pp);
							$change_pp->closeCursor();
							$imgpass = true;
						}
						
					}
					
				} 
				if($imgpass == true){
				
					//6. On modifie directement le secteur, le nombre de stagiaires, et la confiance des pilotes
					
					$req_change_enterprise = "UPDATE `entreprise` SET `secteur_activite`='$checker_secteur', `confiance_pilote_promotion`='$checker_confiance', `stagiaires_acceptes`='$checker_stagiaires' WHERE id = '$IDEntreprise'";
					$change_enterprise = $dbh->query($req_change_enterprise);
					$change_enterprise->closeCursor();
					
					
					//7. On désactive toutes les liaison entreprise_adresse de cette entreprise
					
					$controller = false;
					$ID = 1;
					while($controller == false){
						
						$req_get_entrepriselink = "SELECT * FROM entreprise_adresse WHERE id='$ID'";
						$get_entrepriselink = $dbh->query($req_get_entrepriselink);
						if (!$get_entrepriselink){
							echo "Erreur dans l'obtention des link adresse entreprise : <br>$req_get_entrepriselink\n";
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

									$req_disable_entrepriselink = "UPDATE `entreprise_adresse` SET `active`='0', `IDEntreprise`=NULL, `IDAdresse`=NULL  WHERE id = '$ID'";
									$disable_entrepriselink = $dbh->query($req_disable_entrepriselink);
									$disable_entrepriselink->closeCursor();
								}
								
							} 
							$get_entrepriselink->closeCursor();
							$ID++;			
						}
							
					}
	
					//8. On insère les adresses dans la table adresse

					//Vérification de l'existence de l'adresse n°1
					
					
					$req_check_address = "SELECT * FROM adresse WHERE ad_pays='$checker_ad_pays_1' AND ad_cp='$checker_ad_postal_1' AND ad_ville='$checker_ad_ville_1' AND ad_rue='$checker_ad_rue_1' AND ad_numero='$checker_ad_numero_1'";
					$check_address = $dbh->query($req_check_address);
					if (!$check_address){
						$returnEditEntreprise = "Erreur dans la requête : <br><br>$req_check_address";
					} 
					$string_address = $check_address->fetch(PDO::FETCH_OBJ);
					if($string_address){
						//On récupère l'ID de l'adresse
						$passive_id_address_1 = $string_address->id;
						$id_address_1 = intval($passive_id_address_1);
						$check_address->closeCursor();
						
					} else {
						//Si elle n'existe pas on l'ajoute
						$check_address->closeCursor();
						
						
						$req_add_adresse = "INSERT INTO adresse (ad_pays, ad_cp, ad_ville, ad_rue, ad_numero) VALUES ('$checker_ad_pays_1', '$checker_ad_postal_1', '$checker_ad_ville_1', '$checker_ad_rue_1', '$checker_ad_numero_1')";
						$add_adresse = $dbh->query($req_add_adresse);
						if (!$add_adresse){
							$returnEditEntreprise = "Erreur dans la requête : <br><br>$req_add_adresse";
						}
						$add_adresse->closeCursor();
						
						//On récupère l'ID de la nouvelle adresse
						
						
						$req_check_addressnew = "SELECT * FROM adresse WHERE ad_pays='$checker_ad_pays_1' AND ad_cp='$checker_ad_postal_1' AND ad_ville='$checker_ad_ville_1' AND ad_rue='$checker_ad_rue_1' AND ad_numero='$checker_ad_numero_1'";
						$check_addressnew = $dbh->query($req_check_addressnew);
						if (!$check_addressnew){
							$returnEditEntreprise = "Erreur dans la requête : <br><br>$req_check_addressnew";
						} 
						$string_addressnew = $check_addressnew->fetch(PDO::FETCH_OBJ);
						$passive_id_address_1 = $string_addressnew->id;
						$id_address_1 = intval($passive_id_address_1);
						
						
						$check_addressnew->closeCursor();
					}
					
					
					if($amountOfAddressOfEntreprise >= 2){
						//Vérification de l'existence de l'adresse n°2
						
						$req_check_address = "SELECT * FROM adresse WHERE ad_pays='$checker_ad_pays_2' AND ad_cp='$checker_ad_postal_2' AND ad_ville='$checker_ad_ville_2' AND ad_rue='$checker_ad_rue_2' AND ad_numero='$checker_ad_numero_2'";
						$check_address = $dbh->query($req_check_address);
						if (!$check_address){
							$returnEditEntreprise = "Erreur dans la requête : <br><br>$req_check_address";
						} 
						$string_address = $check_address->fetch(PDO::FETCH_OBJ);
						if($string_address){
							//On récupère l'ID de l'adresse
							$passive_id_address_2 = $string_address->id;
							$id_address_2 = intval($passive_id_address_2);
							$check_address->closeCursor();
							
						} else {
							//Si elle n'existe pas on l'ajoute
							$check_address->closeCursor();
							
							
							$req_add_adresse = "INSERT INTO adresse (ad_pays, ad_cp, ad_ville, ad_rue, ad_numero) VALUES ('$checker_ad_pays_2', '$checker_ad_postal_2', '$checker_ad_ville_2', '$checker_ad_rue_2', '$checker_ad_numero_2')";
							$add_adresse = $dbh->query($req_add_adresse);
							if (!$add_adresse){
								$returnEditEntreprise = "Erreur dans la requête : <br><br>$req_add_adresse";
							}
							$add_adresse->closeCursor();
							
							//On récupère l'ID de la nouvelle adresse
							
							
							$req_check_addressnew = "SELECT * FROM adresse WHERE ad_pays='$checker_ad_pays_2' AND ad_cp='$checker_ad_postal_2' AND ad_ville='$checker_ad_ville_2' AND ad_rue='$checker_ad_rue_2' AND ad_numero='$checker_ad_numero_2'";
							$check_addressnew = $dbh->query($req_check_addressnew);
							if (!$check_addressnew){
								$returnEditEntreprise = "Erreur dans la requête : <br><br>$req_check_addressnew";
							} 
							$string_addressnew = $check_addressnew->fetch(PDO::FETCH_OBJ);
							$passive_id_address_2 = $string_addressnew->id;
							$id_address_2 = intval($passive_id_address_2);
							
							
							$check_addressnew->closeCursor();
						}
					}
					
					if($amountOfAddressOfEntreprise >= 3){
						//Vérification de l'existence de l'adresse n°3
						
						$req_check_address = "SELECT * FROM adresse WHERE ad_pays='$checker_ad_pays_3' AND ad_cp='$checker_ad_postal_3' AND ad_ville='$checker_ad_ville_3' AND ad_rue='$checker_ad_rue_3' AND ad_numero='$checker_ad_numero_3'";
						$check_address = $dbh->query($req_check_address);
						if (!$check_address){
							$returnEditEntreprise = "Erreur dans la requête : <br><br>$req_check_address";
						} 
						$string_address = $check_address->fetch(PDO::FETCH_OBJ);
						if($string_address){
							//On récupère l'ID de l'adresse
							$passive_id_address_3 = $string_address->id;
							$id_address_3 = intval($passive_id_address_3);
							$check_address->closeCursor();
							
						} else {
							//Si elle n'existe pas on l'ajoute
							$check_address->closeCursor();
							
							
							$req_add_adresse = "INSERT INTO adresse (ad_pays, ad_cp, ad_ville, ad_rue, ad_numero) VALUES ('$checker_ad_pays_3', '$checker_ad_postal_3', '$checker_ad_ville_3', '$checker_ad_rue_3', '$checker_ad_numero_3')";
							$add_adresse = $dbh->query($req_add_adresse);
							if (!$add_adresse){
								$returnEditEntreprise = "Erreur dans la requête : <br><br>$req_add_adresse";
							}
							$add_adresse->closeCursor();
							
							//On récupère l'ID de la nouvelle adresse
							
							
							$req_check_addressnew = "SELECT * FROM adresse WHERE ad_pays='$checker_ad_pays_3' AND ad_cp='$checker_ad_postal_3' AND ad_ville='$checker_ad_ville_3' AND ad_rue='$checker_ad_rue_3' AND ad_numero='$checker_ad_numero_3'";
							$check_addressnew = $dbh->query($req_check_addressnew);
							if (!$check_addressnew){
								$returnEditEntreprise = "Erreur dans la requête : <br><br>$req_check_addressnew";
							} 
							$string_addressnew = $check_addressnew->fetch(PDO::FETCH_OBJ);
							$passive_id_address_3 = $string_addressnew->id;
							$id_address_3 = intval($passive_id_address_3);
							
							
							$check_addressnew->closeCursor();
						}
					}
					

					//9. On insère les link_adresse dans la table entreprise_adresse
					
					//Lien pour l'adresse n°1
					$req_add_linkentreprise = "INSERT INTO entreprise_adresse (IDEntreprise, IDAdresse, active) VALUES ('$IDEntreprise', '$id_address_1', TRUE)";
					$add_linkentreprise = $dbh->query($req_add_linkentreprise);
					if (!$add_linkentreprise){
						$returnEditEntreprise = "Erreur dans la requête : <br><br>$add_linkentreprise";
					}
					$add_linkentreprise->closeCursor();
					
					if($amountOfAddressOfEntreprise >= 2){
						
						//Lien pour l'adresse n°2
						$req_add_linkentreprise = "INSERT INTO entreprise_adresse (IDEntreprise, IDAdresse, active) VALUES ('$IDEntreprise', '$id_address_2', TRUE)";
						$add_linkentreprise = $dbh->query($req_add_linkentreprise);
						if (!$add_linkentreprise){
							$returnEditEntreprise = "Erreur dans la requête : <br><br>$add_linkentreprise";
						}
						$add_linkentreprise->closeCursor();
						
					}
					
					if($amountOfAddressOfEntreprise >= 3){
						
						//Lien pour l'adresse n°3
						$req_add_linkentreprise = "INSERT INTO entreprise_adresse (IDEntreprise, IDAdresse, active) VALUES ('$IDEntreprise', '$id_address_3', TRUE)";
						$add_linkentreprise = $dbh->query($req_add_linkentreprise);
						if (!$add_linkentreprise){
							$returnEditEntreprise = "Erreur dans la requête : <br><br>$add_linkentreprise";
						}
						$add_linkentreprise->closeCursor();
						
					}
					
					header('Location: RechercheEntreprise.php');
					unset($_COOKIE['editEntreprise']);
					
					
				} else {
					$returnEditEntreprise = "Impossible de remplacer votre image de profil sur le serveur. Merci de réessayer.";
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

    
	<link rel="stylesheet" type="text/css" href="css/modificationentreprise.css? v=5">
	<link rel="manifest" href="manifest.json">
	<meta name="theme-color" content="#003366">
	<link rel="apple-touch-icon" sizes="180x180" href="/src/img/apple_touch_icon.png">
    <title>Stage à CESI'R - Entreprise</title>
   
    
    
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
		<?php if (isset($returnEditEntreprise)) { ?>
			<label class = return>
				<p><?php echo $returnEditEntreprise; ?></p>
			</label>
		<?php } ?>
	</center>

	<?php
		//Récupération et affichage des informations de l'entreprise
		$nomEntreprise = "NULL";
		if(isset($_COOKIE['editEntreprise'])){
			$nomEntreprise = $_COOKIE['editEntreprise'];
		}
		
		
		
		//Récupération et affichage du secteur à partir du cookie
					
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
		$req_get_entreprise = "SELECT * FROM entreprise WHERE nom_entreprise='$nomEntreprise'";
		$get_entreprise = $dbh->query($req_get_entreprise);
		if (!$get_entreprise){
			echo "Erreur dans l'obtention de l'entreprise : <br><br>$req_get_entreprise";
			die;
		}
		$str_entreprise = $get_entreprise->fetch(PDO::FETCH_OBJ);
		if($str_entreprise){
			$secteurEntreprise = $str_entreprise->secteur_activite;
			$confianceEntreprise = $str_entreprise->confiance_pilote_promotion;
			$stagiairesEntreprise = $str_entreprise->stagiaires_acceptes;
			$idEntreprise = $str_entreprise->id;
			$linklogoEntreprise = $str_entreprise->link_logo;
						
		} else {
			echo "L'entreprise n'est pas valide.";
			die;
		}
		
		
		
		
		$get_entreprise->closeCursor();
		?>
	
	
	
	
	
	
	<div class = content >
            <div class = photoprofile>
				<?php echo "<img class = profilphoto src='src/upload/".($linklogoEntreprise)."' style='max-width:150px'>"; ?>
                <input class = inputfilemessage type="file" name="fileToUpload" id="fileToUpload" accept="image/*"/>
                    
                
            </div>
            <div class = input_info>
                <div>
					<?php
					
					echo "<label for=''>Nom : ".($nomEntreprise)."</label>";?>
                      
                    
                </div>
                
				
				
				<div style="margin-top: 5%;">
                    <label for="">Compétence : 
						<select name="secteur" id="pet-select" style="color: grey;">
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
									if($ID == $secteurEntreprise){
										echo "<option selected value='".($ID)."'>".($skills)." </option>";
									} else {
										echo "<option value='".($ID)."'>".($skills)."</option>";
									}
									$get_skills->closeCursor();
										
									
									$ID++;
									
								}
							}
							?>
						</select>
					</label>
                </div>
				
				
				
				<?php
				

				//On va compter le nombre d'adresse de l'entreprise en stockant les informations des adresses
				
				$controller = false;
				$ID = 1;
				$amountOfAdresseEntreprise = 0;
				while($controller == false){
					
					$req_get_entrepriselink = "SELECT * FROM entreprise_adresse WHERE id='$ID'";
					$get_entrepriselink = $dbh->query($req_get_entrepriselink);
					if (!$get_entrepriselink){
						echo "Erreur dans l'obtention des link adresse entreprise : <br>$req_get_entrepriselink\n";
						die;
					}
					$str_entrepriselink = $get_entrepriselink->fetch(PDO::FETCH_OBJ);
					if(!$str_entrepriselink) {
						$get_entrepriselink->closeCursor();
						$controller = true;
										
					} else {
						
						$active = $str_entrepriselink->active;
						if($active == 1) {
						
							//On récupère les id d'entreprise
							$passive_IDLink = $str_entrepriselink->IDEntreprise;
							$IDLink = intval($passive_IDLink);
							//On test l'id en cours avec l'id de l'entreprise
							if($IDLink == $idEntreprise){
								//Si c'est la même, alors on récupère l'ID d'adresse
								
								$amountOfAdresseEntreprise++;
								
								$passive_IDAdresse = $str_entrepriselink->IDAdresse;
								$IDAdresse = intval($passive_IDAdresse);
								
								//On va aller chercher les informations de l'adresse pour les stocker
								
								$req_get_adresse = "SELECT * FROM adresse WHERE id='$IDAdresse'";
								$get_adresse = $dbh->query($req_get_adresse);
								if (!$get_adresse){
									echo "Erreur dans l'obtention des adresses : <br>$req_get_adresse\n";
									die;
								}
								$str_adresse = $get_adresse->fetch(PDO::FETCH_OBJ);
								//Stockage des informations d'adresse
								
								if($amountOfAdresseEntreprise == 1){
								
									$adresse_Pays_1 = $str_adresse->ad_pays;
									$adresse_Postal_1 = $str_adresse->ad_cp;
									$adresse_Ville_1 = $str_adresse->ad_ville;
									$adresse_Rue_1 = $str_adresse->ad_rue;
									$adresse_Numero_1 = $str_adresse->ad_numero;
									
								} else if($amountOfAdresseEntreprise == 2){
									
									$adresse_Pays_2 = $str_adresse->ad_pays;
									$adresse_Postal_2 = $str_adresse->ad_cp;
									$adresse_Ville_2 = $str_adresse->ad_ville;
									$adresse_Rue_2 = $str_adresse->ad_rue;
									$adresse_Numero_2 = $str_adresse->ad_numero;
									
								} else if($amountOfAdresseEntreprise == 3){
									
									$adresse_Pays_3 = $str_adresse->ad_pays;
									$adresse_Postal_3 = $str_adresse->ad_cp;
									$adresse_Ville_3 = $str_adresse->ad_ville;
									$adresse_Rue_3 = $str_adresse->ad_rue;
									$adresse_Numero_3 = $str_adresse->ad_numero;
									
								}

								$get_adresse->closeCursor();

							}

							$ID++;	
							$get_entrepriselink->closeCursor();
						} else {
							$ID++;	
							$get_entrepriselink->closeCursor();
						}
						
					}
				}
				
				
				//En fonction du nombre d'adresses, on les affiche dans le formulaire	
					
					
					
					
				?>
				
				
                <div style="margin-top: 5%;"><label onclick="AffichageAdress('Adress-1')";>Adresse N°1* ▼</label></div>
                <div id="Adress-1" style="display: none;">
				
					
					
					
                    <div style="margin-top: 5%;" class = "Adress">
                        <?php echo "<label for=''>Pays : <input name='pays1' type='text' value='".($adresse_Pays_1)."' style='color: grey;'></label>"; ?>
                        
                    </div>
                    <div style="margin-top: 5%;" class = "Adress">
                        <?php echo "<label for=''>Ville : <input name='ville1' type='text' value='".($adresse_Ville_1)."' style='color: grey;'></label>"; ?>
                        
                    </div>
                    <div style="margin-top: 5%;" class = "Adress">
                        <?php echo "<label for=''>Code Postal : <input name='postal1' type='text' value='".($adresse_Postal_1)."' style='color: grey;'></label>"; ?>
                        
                    </div>
                    <div style="margin-top: 5%;" class = "Adress">
                        <?php echo "<label for=''>Rue : <input name='rue1' type='text' value='".($adresse_Rue_1)."' style='color: grey;'></label>"; ?>
                        
                    </div>
                    <div style="margin-top: 5%;" class = "Adress">
                        <?php echo "<label for=''>Numéro : <input name='numero1' min='1' type='number' value='".($adresse_Numero_1)."' style='color: grey;'></label>"; ?>
                        
                    </div>
                </div>
                <div style="margin-top: 5%;"><label onclick="AffichageAdress('Adress-2')";>Adresse N°2 ▼</label></div>
                <div id="Adress-2" style="display: none;">
					
					<?php 
					if($amountOfAdresseEntreprise >= 2){
						?>
							
							
							<div style="margin-top: 5%;" class = "Adress">
								<?php echo "<label for=''>Pays : <input name='pays2' type='text' value='".($adresse_Pays_2)."' style='color: grey;'></label>"; ?>
								
							</div>
							<div style="margin-top: 5%;" class = "Adress">
								<?php echo "<label for=''>Ville : <input name='ville2' type='text' value='".($adresse_Ville_2)."' style='color: grey;'></label>"; ?>
								
							</div>
							<div style="margin-top: 5%;" class = "Adress">
								<?php echo "<label for=''>Code Postal : <input name='postal2' type='text' value='".($adresse_Postal_2)."' style='color: grey;'></label>"; ?>
								
							</div>
							<div style="margin-top: 5%;" class = "Adress">
								<?php echo "<label for=''>Rue : <input name='rue2' type='text' value='".($adresse_Rue_2)."' style='color: grey;'></label>"; ?>
								
							</div>
							<div style="margin-top: 5%;" class = "Adress">
								<?php echo "<label for=''>Numéro : <input name='numero2' min='1' type='number' value='".($adresse_Numero_2)."' style='color: grey;'></label>"; ?>
                        
                    </div>
							
							
							
					    <?php
					} else {
						?>
						
						
						<div style="margin-top: 5%;" class = "Adress">
							<label for="">Pays : <input name="pays2" type="text" placeholder="Pays*" style="color: grey;"></label>
							
						</div>
						<div style="margin-top: 5%;" class = "Adress">
							<label for="">Ville : <input name="ville2" type="text" placeholder="Ville*" style="color: grey;"></label>
							
						</div>
						<div style="margin-top: 5%;" class = "Adress">
							<label for="">Code Postal : <input name="postal2" type="text" placeholder="Code Postal*" style="color: grey;"></label>
							
						</div>
						<div style="margin-top: 5%;" class = "Adress">
							<label for="">Rue : <input name="rue2" type="text" placeholder="Rue*" style="color: grey;"></label>
							
						</div>
						<div style="margin-top: 5%;" class = "Adress">
							<label for="">Numéro : <input name="numero2" min="1" type="number" placeholder="Numero*" style="color: grey;"></label>
							
						</div>
						
						
						<?php
					}
					?>
                </div>
                <div style="margin-top: 5%;"><label onclick="AffichageAdress('Adress-3')";>Adresse N°3 ▼</label></div>
                <div id="Adress-3" style="display: none;">
                    
					
					
					
					<?php 
					if($amountOfAdresseEntreprise >= 3){
						?>
							
							
							<div style="margin-top: 5%;" class = "Adress">
								<?php echo "<label for=''>Pays : <input name='pays3' type='text' value='".($adresse_Pays_3)."' style='color: grey;'></label>"; ?>
								
							</div>
							<div style="margin-top: 5%;" class = "Adress">
								<?php echo "<label for=''>Ville : <input name='ville3' type='text' value='".($adresse_Ville_3)."' style='color: grey;'></label>"; ?>
								
							</div>
							<div style="margin-top: 5%;" class = "Adress">
								<?php echo "<label for=''>Code Postal : <input name='postal3' type='text' value='".($adresse_Postal_3)."' style='color: grey;'></label>"; ?>
								
							</div>
							<div style="margin-top: 5%;" class = "Adress">
								<?php echo "<label for=''>Rue : <input name='rue3' type='text' value='".($adresse_Rue_3)."' style='color: grey;'></label>"; ?>
								
							</div>
							<div style="margin-top: 5%;" class = "Adress">
								<?php echo "<label for=''>Numéro : <input name='numero3' min='1' type='number' value='".($adresse_Numero_3)."' style='color: grey;'></label>"; ?>
                        
                    </div>
							
							
							
					    <?php
					} else {
						?>
						
						
						<div style="margin-top: 5%;" class = "Adress">
							<label for="">Pays : <input name="pays3" type="text" placeholder="Pays*" style="color: grey;"></label>
							
						</div>
						<div style="margin-top: 5%;" class = "Adress">
							<label for="">Ville : <input name="ville3" type="text" placeholder="Ville*" style="color: grey;"></label>
							
						</div>
						<div style="margin-top: 5%;" class = "Adress">
							<label for="">Code Postal : <input name="postal3" type="text" placeholder="Code Postal*" style="color: grey;"></label>
							
						</div>
						<div style="margin-top: 5%;" class = "Adress">
							<label for="">Rue : <input name="rue3" type="text" placeholder="Rue*" style="color: grey;"></label>
							
						</div>
						<div style="margin-top: 5%;" class = "Adress">
							<label for="">Numéro : <input name="numero3" min="1" type="number" placeholder="Numero*" style="color: grey;"></label>
							
						</div>
						
						
						<?php
					}
					?>
					
					
					
					
					
					
                </div>
                
                
                <div style="margin-top: 5%;">
                    
					<?php
					
					echo "<label for=''>Nombre de stagiaires acceptés : <input type='number' value='".($stagiairesEntreprise)."' style='color: grey;' name='stagaires' max='10' min='1'></label>";?>
					
				
				</div>
				<div style="margin-top: 5%;">
                    
					<?php
					
					echo "<label for=''>Confiance des pilotes : <input type='number' value='".($confianceEntreprise)."' style='color: grey;' name='confiance' max='10' min='1'></label>";?>
					
					
                </div>
            </div>
            <div class = input_add>
				<input type="submit" name="editEntreprise" value="Valider" style="padding: 20px 50px;display: flex;margin-bottom: 5%;">
				
				<input class = buttonBack type="submit" name="backToListEntreprise" value="Retour" style="padding: 20px 50px;">
                   
				
				   
            </div>
            
    </div>
		
    </form>
        

</body>
<?php include 'global/footer.php'; ?>
</html>


<script>
    $Open=false;
    function AffichageAdress(ID){
        if($Open){
            document.getElementById(ID).style.display = 'none'
            $Open=false;
        }else{
            document.getElementById(ID).style.display = 'block'
            $Open=true;
        }
    }
</script>

<script>
	if('serviceWorker' in navigator){
    navigator.serviceWorker.register('ServiceWorker.js')
    .then( (sw) => console.log('Le Service Worker a été enregistrer', sw))
    .catch((err) => console.log('Le Service Worker est introuvable !!!', err));
   }
</script>