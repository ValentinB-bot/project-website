<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
		include 'global/header.php';
	?>
	<link rel="stylesheet" type="text/css" href="css/cgu.css? v=1">
    <title>Stage à CESI'R - CGU</title>
    
	
	
	<?php if (!isset($_SESSION['LOGGED_USER'])) { ?>
		<a href="\login.php"><button class = login>Connexion</button></a>
    <?php }else { ?>
		<a href="\unlog.php"><button class = login>Déconnexion</button></a>
		<a href="\profil.php"><button class = login>Mon Profil</button></a>
		
		
		
		
	<?php } ?>
	
	
	
	
    <?php 
		include 'global/navbar.php';
	?>
</head>
<body>
    <center><div class = CGU_panel> 
		<h2 class = CGU_Title>Conditions générales d'utilisation</h2>
		<p class = CGU_Date>En vigueur au 29/03/2023</p><br>
		<p class = CGU_Text>
		Les présentes conditions générales d'utilisation (dites « <strong>CGU</strong> ») ont pour objet l'encadrement juridique
		<br>des modalités de mise à disposition du site et des services par CESI ainsi que la définition des conditions
		<br>d’accès et d’utilisation des services par « <strong>l'Utilisateur</strong> ».
		<br>Les présentes <strong>CGU</strong> sont accessibles sur le site depuis le footer.
		
		</p>
		
		
		<p class = CGU_Text>
		
		Toute inscription ou utilisation du site implique l'acceptation sans aucune réserve ni restriction des
		présentes CGU par l’utilisateur. Lors de la connexion sur le site depuis le formulaire de connexion, chaque
		utilisateur accepte expressément les présentes CGU.
		En cas de non-acceptation des CGU stipulées dans le présent contrat, l'Utilisateur se doit de renoncer
		à l'accès des services proposés par le site.
		http://www.stageacesir.fr se réserve le droit de modifier unilatéralement et à tout moment le contenu
		des présentes CGU.
		
		</p><br>
		<h3 class = CGU_Article>Article 1 : Les mentions légales</h3><br>
		
		<p class = CGU_Text>
		
		L'édition du site http://www.stageacesir.fr est assurée par la Société CPI A2 - G2 CESI au capital de 0
		euros, immatriculée au RCS de Saint-Nazaire sous le numéro 0000-0000-0000-0000-0000, dont le
		siège social est situé au 24 Le Paquebot, 44600 Saint-Nazaire
		Numéro de téléphone 0123456789
		Adresse e-mail : contact@stageacesir.fr.
		Le Directeur de la publication est : Valhona Morlan
		</p>
		<p class = CGU_Text>
		L'hébergeur du site http://www.stageacesir.fr est la société XAMPP, dont le siège social est situé au
		***, avec le numéro de téléphone : 0123456788.
		
		</p>
		
		
		</p><br>
		<h3 class = CGU_Article>ARTICLE 2 : Accès au site</h3><br>
		
		<p class = CGU_Text>
		
		Le site http://www.stageacesir.fr permet à l'Utilisateur un accès gratuit aux services suivants :
		> Recherche de stage pour les étudiants du CESI
		> Connexion à son compte pour postuler aux divers offres de stage.
		Le site est accessible gratuitement en tout lieu à tout Utilisateur ayant un accès à Internet. Tous les
		frais supportés par l'Utilisateur pour accéder au service (matériel informatique, logiciels, connexion
		Internet, etc.) sont à sa charge.
		</p>
		<p class = CGU_Text>
		
		L’Utilisateur non membre n'a pas accès aux services réservés.
		Pour cela, il doit demander une inscription auprès d'une personne compétente (pilote de promotion). 
		En acceptant de s’inscrire aux services réservés, l’Utilisateur membre
		s’engage à fournir des informations sincères et exactes concernant son état civil et ses coordonnées,
		notamment son adresse email.
		
		</p>
		<p class = CGU_Text>
		
		Pour accéder aux services, l’Utilisateur doit ensuite s'identifier à l'aide de son identifiant et de son mot
		de passe qui lui seront communiqués après son inscription.
		La desinscription est automatique et survient à l'arrêt de la formation de l'étudiant ou à l'arrêt des fonctions du pilote.
		Celle-ci sera effective dans un délai raisonnable.
		</p>
		<p class = CGU_Text>
		Tout événement dû à un cas de force majeure ayant pour conséquence un dysfonctionnement du site
		ou serveur et sous réserve de toute interruption ou modification en cas de maintenance, n'engage pas
		la responsabilité de http://www.stageacesir.fr. Dans ces cas, l’Utilisateur accepte ainsi ne pas tenir
		rigueur à l’éditeur de toute interruption ou suspension de service, même sans préavis.
		</p>
		<p class = CGU_Text>
		L'Utilisateur a la possibilité de contacter le site par messagerie électronique à l’adresse email de
		l’éditeur communiqué à l’ARTICLE 1.
		
		</p><br>
		<h3 class = CGU_Article>ARTICLE 3 : Collecte des données</h3><br>
		<p class = CGU_Text>
		Le site assure à l'Utilisateur une collecte et un traitement d'informations personnelles dans le respect
		de la vie privée conformément à la loi n°78-17 du 6 janvier 1978 relative à l'informatique, aux fichiers
		et aux libertés. Le site est déclaré à la CNIL sous le numéro 0000000000000.
		</p>
		<p class = CGU_Text>
		En vertu de la loi Informatique et Libertés, en date du 6 janvier 1978, l'Utilisateur dispose d'un droit
		d'accès, de rectification, de suppression et d'opposition de ses données personnelles. 
		</p>
		<p class = CGU_Text>
		L'Utilisateur exerce ce droit :<br>
		> par mail à l'adresse email contact@stageacesir.fr
		</p><br>
		<h3 class = CGU_Article>ARTICLE 4 : Propriété intellectuelle</h3><br>
		
		<p class = CGU_Text>
		Les marques, logos, signes ainsi que tous les contenus du site (textes, images, son…) font l'objet
		d'une protection par le Code de la propriété intellectuelle et plus particulièrement par le droit d'auteur.
		</p>
		<p class = CGU_Text>
		L'Utilisateur doit solliciter l'autorisation préalable du site pour toute reproduction, publication, copie des
		différents contenus. Il s'engage à une utilisation des contenus du site dans un cadre strictement privé,
		toute utilisation à des fins commerciales et publicitaires est strictement interdite.
		</p>
		<p class = CGU_Text>
		Toute représentation totale ou partielle de ce site par quelque procédé que ce soit, sans l’autorisation
		expresse de l’exploitant du site Internet constituerait une contrefaçon sanctionnée par l’article L 335-2
		et suivants du Code de la propriété intellectuelle.
		</p>
		<p class = CGU_Text>
		Il est rappelé conformément à l’article L122-5 du Code de propriété intellectuelle que l’Utilisateur qui
		reproduit, copie ou publie le contenu protégé doit citer l’auteur et sa source.
		</p><br>
		
		<h3 class = CGU_Article>ARTICLE 5 : Responsabilité</h3><br>
		
		<p class = CGU_Text>
		
		Les sources des informations diffusées sur le site http://www.stageacesir.fr sont réputées fiables mais
		le site ne garantit pas qu’il soit exempt de défauts, d’erreurs ou d’omissions.
		Les informations communiquées sont présentées à titre indicatif et général sans valeur contractuelle.
		</p>
		<p class = CGU_Text>
		Malgré des mises à jour régulières, le site http://www.stageacesir.fr ne peut être tenu responsable de 
		la modification des dispositions administratives et juridiques survenant après la publication. De même,
		le site ne peut être tenu responsable de l’utilisation et de l’interprétation de l’information contenue
		dans ce site.
		</p>
		<p class = CGU_Text>
		L'Utilisateur s'assure de garder son mot de passe secret. Toute divulgation du mot de passe, quelle
		que soit sa forme, est interdite. Il assume les risques liés à l'utilisation de son identifiant et mot de
		passe. Le site décline toute responsabilité.
		</p>
		<p class = CGU_Text>
		Le site http://www.stageacesir.fr ne peut être tenu pour responsable d’éventuels virus qui pourraient
		infecter l’ordinateur ou tout matériel informatique de l’Internaute, suite à une utilisation, à l’accès, ou
		au téléchargement provenant de ce site.
		La responsabilité du site ne peut être engagée en cas de force majeure ou du fait imprévisible et
		insurmontable d'un tiers.
		</p><br>
		
		<h3 class = CGU_Article>ARTICLE 6 : Liens hypertextes</h3><br>
		<p class = CGU_Text>
		Des liens hypertextes peuvent être présents sur le site. L’Utilisateur est informé qu’en cliquant sur ces
		liens, il sortira du site http://www.stageacesir.fr. Ce dernier n’a pas de contrôle sur les pages web sur
		lesquelles aboutissent ces liens et ne saurait, en aucun cas, être responsable de leur contenu.
		</p><br>
		
		<h3 class = CGU_Article>ARTICLE 7 : Cookies</h3><br>
		<p class = CGU_Text>
		
		L’Utilisateur est informé que lors de ses visites sur le site, un cookie peut s’installer automatiquement
		sur son logiciel de navigation.
		Les cookies sont de petits fichiers stockés temporairement sur le disque dur de l’ordinateur de
		l’Utilisateur par votre navigateur et qui sont nécessaires à l’utilisation du site http://www.stageacesir.fr.
		</p>
		<p class = CGU_Text>
		Les cookies ne contiennent pas d’informations personnelles et ne peuvent pas être utilisés pour identifier
		quelqu’un. Un cookie contient un identifiant unique, généré aléatoirement et donc anonyme. Certains
		cookies expirent à la fin de la visite de l’Utilisateur, d’autres restent.
		L’information contenue dans les cookies est utilisée pour améliorer le site http://www.stageacesir.fr.
		En naviguant sur le site, L’Utilisateur les accepte.
		</p>
		<p class = CGU_Text>
		L’Utilisateur pourra désactiver ces cookies par l’intermédiaire des paramètres figurant au sein de son
		logiciel de navigation.

		
		</p><br>
		
		<h3 class = CGU_Article>ARTICLE 8 : Publication par l’Utilisateur</h3><br>
		<p class = CGU_Text>
		
		Le site permet aux membres de publier les contenus suivants :
		Photo de profil, dans le cas d'un pilote de promotion, publication d'offres de stage.
		Dans ses publications, le membre s’engage à respecter les règles de la Netiquette (règles de bonne
		conduite de l’internet) et les règles de droit en vigueur.
		</p>
		<p class = CGU_Text>
		Le site peut exercer une modération sur les publications et se réserve le droit de refuser leur mise en
		ligne, sans avoir à s’en justifier auprès du membre.
		</p>
		<p class = CGU_Text>
		Le membre reste titulaire de l’intégralité de ses droits de propriété intellectuelle. Mais en publiant une
		publication sur le site, il cède à la société éditrice le droit non exclusif et gratuit de représenter,
		reproduire, adapter, modifier, diffuser et distribuer sa publication, directement ou par un tiers autorisé,
		dans le monde entier, sur tout support (numérique ou physique), pour la durée de la propriété
		intellectuelle. Le Membre cède notamment le droit d'utiliser sa publication sur internet et sur les
		réseaux de téléphonie mobile.
		La société éditrice s'engage à faire figurer le nom du membre à proximité de chaque utilisation de sa
		publication.
		</p>
		<p class = CGU_Text>
		Tout contenu mis en ligne par l'Utilisateur est de sa seule responsabilité. L'Utilisateur s'engage à ne
		pas mettre en ligne de contenus pouvant porter atteinte aux intérêts de tierces personnes. Tout
		recours en justice engagé par un tiers lésé contre le site sera pris en charge par l'Utilisateur.
		</p>
		<p class = CGU_Text>
		Le contenu de l'Utilisateur peut être à tout moment et pour n'importe quelle raison supprimé ou modifié
		par le site, sans préavis.
		
		</p><br>
		
		<h3 class = CGU_Article>ARTICLE 9 : Droit applicable et juridiction compétente</h3><br>
		<p class = CGU_Text>
		La législation française s'applique au présent contrat. En cas d'absence de résolution amiable d'un
		litige né entre les parties, les tribunaux français seront seuls compétents pour en connaître.
		</p>
		<p class = CGU_Text>
		Pour toute question relative à l’application des présentes CGU, vous pouvez joindre l’éditeur aux
		coordonnées inscrites à l’ARTICLE 1.
		</p><br>
		<p class = CGU_Date>Dernière mise à jour: 22/03/2023</p><br>

	</div></center>
</body>
<?php include 'global/footer.php'; ?>
</html>