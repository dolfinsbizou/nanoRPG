<?php
include_once("model/sessions.php");
include_once("model/users.php");
include_once("model/libraries.php");

if(isLogged() and !isset($_GET['id']))
{
	$utilisateur = getUserInfoById(getUserId());
	$uid = getUserId();
}
else
{
	if(isset($_GET['id']))
	{
		$utilisateur = getUserInfoById($_GET['id']);
		$uid = $_GET['id'];

		if($utilisateur == null)
			Header('Location: creations.php');
	}
	else
		Header('Location: ./');
}

//TODO en cas de redirection on a le problème que ce script s'exécute quand même, ce qui est problématique
$qteJeux = getLibraryCountByUserId($uid);
$jeuxParPage = 10;

$debut = 0;
$fin = $jeuxParPage;
$qtePages = (int) ($qteJeux/$jeuxParPage)+($qteJeux%$jeuxParPage == 0?0:1);
if(isset($GET['page']))
{
	$page = (int) $_GET['page'];

	if($page > 1 and $page <= $qtePages)
		$debut = ($page -1)*$jeuxParPage;
}
else
	$page = 1;

$jeux = getLibraryByUserId($uid, $debut, $fin);

if(!empty($jeux))
{
	foreach($jeux as &$jeu)
	{
		$jeu['name'] = htmlspecialchars($jeu['name']);
		$jeu['description'] = htmlspecialchars($jeu['description']);
	}
}

$page_title = 'Librairie de ' . $utilisateur['pseudo'];
include_once("view/librairie.php");
