

	<nav>
        <ul>
            <li class = navbar_home ><a href="/index.php" style="color: white;">Home</a></li>
            <?php if (isset($_SESSION['LOGGED_USER'])) { ?>
				<li class = navbar><a href="" style="color: rgb(255, 204, 0);">Gestion des entreprises</a>

					<ul class = sous>
						<li><a href="RechercheEntreprise.php">Recherche d'entreprises</a></li>  
						<?php if ($_SESSION['PERMS_USER'] == 2 || $_SESSION['PERMS_USER'] == 3) { ?>
							<li><a href="CreationEntreprise.php">Création d'entreprises</a></li> 
						<?php } ?>	
					</ul>

				</li>
				<li class = navbar><a href="" style="color: rgb(255, 204, 0);">Gestion des offres de stage</a>

					<ul class = sous>
						<li><a href="RechercheOffre.php">Recherche d'offres</a></li> 
						<?php if ($_SESSION['PERMS_USER'] == 2 || $_SESSION['PERMS_USER'] == 3) { ?>
							<li><a href="CreationOffre.php">Création d'offres</a></li> 
						<?php } ?>	
					</ul>

				</li>
				<?php if ($_SESSION['PERMS_USER'] == 3) { ?>
					<li class = navbar><a href="" style="color: rgb(255, 204, 0);">Gestion des pilotes de promotions</a>

						<ul class = sous>
							<li><a href="RecherchePilote.php">Recherche de comptes pilotes</a></li> 
							<li><a href="CreationPilote.php">Création d'un compte pilote</a></li> 
						</ul>

					</li>
				<?php } ?>	
				<?php if ($_SESSION['PERMS_USER'] == 2 || $_SESSION['PERMS_USER'] == 3) { ?>
					<li class = navbar><a href="" style="color: rgb(255, 204, 0);">Gestion des étudiants</a>

						<ul class = sous>
							<li><a href="RechercheEtudiant.php">Recherche de comptes étudiants</a></li> 
							<li><a href="CreationEtudiant.php">Création d'un compte étudiant</a></li> 
						</ul>

					</li>
				<?php } ?>
				
				<li class = navbar><a href="" style="color: rgb(255, 204, 0);">Gestion des candidatures</a>

					<ul class = sous>
						<?php if ($_SESSION['PERMS_USER'] == 1 || $_SESSION['PERMS_USER'] == 3) { ?>
						<li><a href="Wishlist.php">Wishlist</a></li> 
						
						<?php } ?>
						
						<li><a href="NotificationsCandidatures.php">Notifications Candidatures</a></li> 
						
						
					</ul>

				</li>
				
			<?php } ?>
        </ul>
    </nav>