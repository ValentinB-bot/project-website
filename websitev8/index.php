<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
		include 'global/header.php';
	?>
	<link rel="stylesheet" type="text/css" href="css/index.css">
    <title>Stage à CESI'R</title>
    
	
	
	<?php if (!isset($_SESSION['LOGGED_USER'])) { ?>
		<a href="login.php"><button class = login>Connexion</button></a>
    <?php }else { ?>
		<a href="unlog.php"><button class = login>Déconnexion</button></a>
		<a href="profil.php"><button class = login>Mon Profil</button></a>
	<?php } ?>
	
	
	
	
    <?php 
		include 'global/navbar.php';
	?>
	
	
</head>
<body>
    
    <p class = textAccueil>Stage à CESI'R, la nouvelle plateforme de gestion de stage</p>

</body>
<?php include 'global/footer.php'; ?>
</html>


