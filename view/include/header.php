<div id="header-wrapper">
	<nav>
	<header>
		<h1><img src="img/logo.png" height="150" alt="logo" /> ηRPG</h1>
	</header>
		<ul>
		<li><a href="/">Accueil</a></li>
		<li><?php 
		if(isLogged())
		{
		?>
			<a href="profil.php">Profil</a></li>
			<li><a href="/disconnect.php">Déconnexion</a>
		<?php
		}
		else
		{
		?>
			<a href="/connexion.php">Connexion</a>
		<?php
		}
		?></li>
		<li><a href="#">A propos</a></li>
		</ul>
	</nav>
</div>
