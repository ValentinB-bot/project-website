<?php session_start(); ?>

<?php if (!isset($_SESSION['LOGGED_USER'])) {
        header('Location: index.php'); ?>
<?php } ?>

<!DOCTYPE html>
<html lang="en">
<head>
   

	<?php 
		include 'global/header.php';
	?>
	<link rel="stylesheet" type="text/css" href="css/notificationscandidatures.css">
    <title>Stage à CESI'R</title>
    
    
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

            echo 'Erreur de connexion à la base de données';

        }
        if($_SESSION['PERMS_USER'] == 1){
            $GetInfo = $dbh->query("SELECT utilisateur.nom_utilisateur, utilisateur.prenom_utilisateur, promotion.nom_promotion, CONCAT(adresse.ad_numero, ' ', adresse.ad_rue, ', ', adresse.ad_cp, ' ', adresse.ad_ville, ', ', adresse.ad_pays) AS adresse_complete, utilisateur.link_logo AS utilisateur_logo, entreprise.link_logo AS entreprise_logo, entreprise.nom_entreprise, offre.nom_offre, offre.remuneration_horaire, offre.date_poste_offre, offre.places_offre, offre.duree_stage, offre.date_debut_stage, offre.date_fin_stage, competence.description_competence
            FROM adresse
            INNER JOIN entreprise_adresse ON adresse.id = entreprise_adresse.IDAdresse
            INNER JOIN entreprise ON entreprise_adresse.IDEntreprise = entreprise.id
            INNER JOIN offre ON entreprise.id = offre.IDEntreprise
            INNER JOIN Competence ON offre.IDCompetence = competence.id
            INNER JOIN candidature ON candidature.IDOffre = offre.id
            INNER JOIN utilisateur ON utilisateur.id = candidature.IDUtilisateur
            INNER JOIN utilisateur_promotion ON utilisateur_promotion.IDUtilisateur = utilisateur.id
            INNER JOIN promotion ON promotion.id = utilisateur_promotion.IDPromotion
            WHERE adresse_offre = adresse.id 
            AND utilisateur.nom_utilisateur = '" . $_SESSION['NOM_USER'] . "'  
            AND utilisateur.prenom_utilisateur = '" . $_SESSION['PRENOM_USER'] . "';");

        }
        elseif($_SESSION['PERMS_USER'] == 2){
            $GetInfo = $dbh->query("SELECT utilisateur.nom_utilisateur,utilisateur.prenom_utilisateur,promotion.nom_promotion,CONCAT(adresse.ad_numero, '', adresse.ad_rue, ', ', adresse.ad_cp, ' ', adresse.ad_ville, ', ', adresse.ad_pays) AS adresse_complete,
                utilisateur.link_logo AS utilisateur_logo,entreprise.link_logo AS entreprise_logo,entreprise.nom_entreprise,offre.nom_offre,offre.remuneration_horaire,
                offre.date_poste_offre,offre.places_offre,offre.duree_stage,offre.date_debut_stage,offre.date_fin_stage,competence.description_competence
                FROM adresse
                INNER JOIN entreprise_adresse ON adresse.id = entreprise_adresse.IDAdresse
                INNER JOIN entreprise ON entreprise_adresse.IDEntreprise = entreprise.id
                INNER JOIN offre ON entreprise.id = offre.IDEntreprise
                INNER JOIN Competence ON offre.IDCompetence = competence.id
                INNER JOIN candidature ON candidature.IDOffre = offre.id
                INNER JOIN utilisateur ON utilisateur.id = candidature.IDUtilisateur
                INNER JOIN utilisateur_promotion ON utilisateur_promotion.IDUtilisateur = utilisateur.id
                INNER JOIN promotion ON promotion.id = utilisateur_promotion.IDPromotion
                WHERE adresse_offre = adresse.id and promotion.nom_promotion IN (
                    SELECT promotion.nom_promotion 
                    FROM `utilisateur` 
                    INNER JOIN utilisateur_promotion ON utilisateur.id = utilisateur_promotion.IDUtilisateur 
                    INNER JOIN promotion ON utilisateur_promotion.IDPromotion = promotion.id 
                    WHERE utilisateur.nom_utilisateur='". $_SESSION['NOM_USER'] . "' AND utilisateur.prenom_utilisateur='" . $_SESSION['PRENOM_USER'] . "'
                );");

        }
        elseif($_SESSION['PERMS_USER'] == 3){
            $GetInfo = $dbh->query('SELECT utilisateur.nom_utilisateur,utilisateur.prenom_utilisateur,promotion.nom_promotion,CONCAT(adresse.ad_numero, " ", adresse.ad_rue, ", ", adresse.ad_cp, " ", adresse.ad_ville, ", ", adresse.ad_pays) AS adresse_complete,
            utilisateur.link_logo AS utilisateur_logo,entreprise.link_logo AS entreprise_logo,entreprise.nom_entreprise,offre.nom_offre,offre.remuneration_horaire,
            offre.date_poste_offre,offre.places_offre,offre.duree_stage,offre.date_debut_stage,offre.date_fin_stage,competence.description_competence
                                    FROM adresse
                                    INNER JOIN entreprise_adresse ON adresse.id = entreprise_adresse.IDAdresse
                                    INNER JOIN entreprise ON entreprise_adresse.IDEntreprise = entreprise.id
                                    INNER JOIN offre ON entreprise.id = offre.IDEntreprise
                                    INNER JOIN Competence ON offre.IDCompetence = competence.id
                                    INNER JOIN candidature ON candidature.IDOffre = offre.id
                                    INNER JOIN utilisateur ON utilisateur.id = candidature.IDUtilisateur
                                    INNER JOIN utilisateur_promotion ON utilisateur_promotion.IDUtilisateur = utilisateur.id
                                    INNER JOIN promotion ON promotion.id = utilisateur_promotion.IDPromotion
                                    WHERE adresse_offre = adresse.id;');
        }

        if(!$GetInfo){
            echo "Erreur dans la récupération des données";
            die;
        }

        $html="";

        foreach ($GetInfo as $InfoList)
		

        {
             $html.='<div class="notification">
                        <img class = ProfilePhoto src="src/upload//'.$InfoList['utilisateur_logo'].'" alt="ProfilePhoto" id="ImageProfile">
                        <div class = "student_info">
                            <label for="" style = "display: flex;margin-top: 10%;">'.$InfoList['nom_utilisateur'].' '.$InfoList['prenom_utilisateur'] . '</label>
                            <label for="" style = "display: flex;margin-top: 10%;">'.$InfoList['nom_promotion'].'</label>
                        </div>
                        <img class = "Application_Img" src="src/img/Postule_pour.png" alt="">
                        <img class = EnterprisePhoto src="src\upload\\'.$InfoList['entreprise_logo'].'" alt="ProfilePhoto" id="ImageProfile">
                        <div class = "enterprise_info">
                            <label for="" style = "display: flex;margin-top: 7.5%;">'.$InfoList['nom_entreprise'].'</label>
                            <label for="" style = "display: flex;margin-top: 7.5%;">'.$InfoList['description_competence'].'(2 offres)</label>
                            <label for="" style = "display: flex;margin-top: 7.5%;">'.$InfoList['adresse_complete'].'</label>
                            <label for="" style = "display: flex;margin-top: 7.5%;">'.$InfoList['duree_stage'].' Semaine</label>
                            <label for="" style = "display: flex;margin-top: 7.5%;">Rémunération par heures : '.$InfoList['remuneration_horaire'].'€</label>
                        </div>
                    </div>';
        }
        ?>
       
	   
       <div>
       <?php echo $html ?>
       </div>
       


</body>
<?php include 'global/footer.php'; ?>
</html>



