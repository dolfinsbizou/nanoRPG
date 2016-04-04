<?php
include_once("model/connectBDD.php");

/*
 * Crée un nouveau jeu
 */
function addGame($id, $n, $d, $s)
{
	global $bdd;

	$req = $bdd->prepare('INSERT INTO games(id_creator, name, description, sensible) VALUES(:id, :name, :desc, :sensible)');
	$req->execute(array(
			'id' => $id,
			'name' => $n,
			'desc' => $d,
			'sensible' => $s));
}

/*
 * Supprime un jeu : si $id_creator est différent de null, alors on n'effectue la suppression que si le jeu appartient au membre d'id $id_creator 
 */
function deleteGame($id, $id_creator=NULL)
{
	global $bdd;

	$reqString = 'DELETE FROM games WHERE id = :id';

	if($id_creator!=null)
		$reqString.= ' AND id_creator = :idc';

	$req = $bdd->prepare($reqString);

	$req->bindParam('id', $id, PDO::PARAM_INT);
	if($id_creator!=null) $req->bindParam('idc', $id_creator, PDO::PARAM_INT);

	$req->execute();
}

/*
 * Retourne la liste des jeux d'un utilisateur. Si il y a une limite, sort seulement les $limit derniers jeux (par date de modification)
 */
function getGamesByUserId($id, $debut=NULL, $limite=NULL)
{
	global $bdd;

	$reqString = 'SELECT id, id_creator, name, description, sensible, DATE_FORMAT(last_modified, \'le %d/%m/%Y à %H:%i\') AS last_modified FROM games WHERE id_creator = :id ORDER BY name';
	
	if ($debut!=null or $limite!=null)
	{
		$limite = (int) $limite;
		$debut = (int) $debut;
		$reqString.= ' LIMIT :begin, :limit';
	}
	
	$req = $bdd->prepare($reqString);
	
	$req->bindParam('id', $id, PDO::PARAM_INT);
	if ($debut != null or $limite != null)
	{
		$req->bindParam('begin', $debut, PDO::PARAM_INT);
		$req->bindParam('limit', $limite, PDO::PARAM_INT);
	}
	$req->execute();
	$gamesInfo = $req->fetchAll();

	return $gamesInfo;
}

/*
 * Retourne la quantité de jeux d'un utilisateur
 */
function getGamesCountByUserId($id)
{
	global $bdd;

	$req = $bdd->prepare('SELECT COUNT(id) FROM games WHERE id_creator = :id');
	$req->execute(array('id' => (int) $id));
	$gamesCount = $req->fetch()[0];
	return $gamesCount;
}

/*
 * Sort une liste des derniers jeux
 */
function getLastGames($id, $limite=3)
{
	global $bdd;

	$req = $bdd->prepare('SELECT id, id_creator, name, sensible, DATE_FORMAT(last_modified, \'le %d/%m/%Y à %H:%i\') AS last_modified FROM games WHERE id_creator = :id ORDER BY last_modified DESC LIMIT 0, :limit');

	$req->bindParam('id', $id, PDO::PARAM_INT);
	$req->bindParam('limit', $limite, PDO::PARAM_INT);
	$req->execute();
	$gamesInfo = $req->fetchAll();

	return $gamesInfo;
}

/*
 * Retourne les informations d'un jeu selon son id. Si le flag est à true, joint les infos du créateur
 */
function getGameInfos($id, $userJoin=false)
{
	global $bdd;
	
	$reqString = 'SELECT games.id, games.id_creator, games.name, games.description, games.sensible, DATE_FORMAT(games.last_modified, \'le %d/%m/%Y à %H:%i\') AS last_modified' . ($userJoin?', users.pseudo, users.avatar, users.admin':'') . ' FROM games' . ($userJoin?' INNER JOIN users ON games.id_creator = users.id':'') . ' WHERE games.id = :id';

	$req = $bdd->prepare($reqString);
	$req->execute(array('id' => $id));
	return $req->fetch();
}
