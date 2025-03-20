<?php session_start(); ?>



<?php 
	//On supprime la session de l'utilisateur
	//On le renvoie sur la page d'accueil qui lui demandera une nouvelle connexion
	session_destroy();
	header('Location: index.php');
?>

<link rel="manifest" href="manifest.json">
<meta name="theme-color" content="#003366">
<link rel="apple-touch-icon" sizes="180x180" href="/src/img/apple_touch_icon.png">

<script>
	if('serviceWorker' in navigator){
    navigator.serviceWorker.register('ServiceWorker.js')
    .then( (sw) => console.log('Le Service Worker a été enregistrer', sw))
    .catch((err) => console.log('Le Service Worker est introuvable !!!', err));
   }
</script>