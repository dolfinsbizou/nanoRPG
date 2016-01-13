<?php

include_once('model/connectBDD.php');
include_once('model/sessions.php');

/*
 * Ajoute un utilisateur en base de donnée
 */
function addUser($pseudo, $nom, $genre, $email, $date_naissance, $pswd)
{
	global $bdd;

	$req = $bdd->prepare('INSERT INTO users(pseudo, name, gender, email, pswd, birth) VALUES(:pseudo, :name, :gender, :email, :pswd, :birth)');

	$req->execute(array(
			'pseudo' => $pseudo,
			'name' => $nom,
			'gender' => $genre,
			'email' => $email,
			'pswd' => sha1($pswd),
			'birth' => $date_naissance));
}

/*
 * Modifie les données utilisateur
 */
function updateUser($id, $pseudo, $nom, $genre, $email, $date_naissance, $bio, $pswd, $hasAvatar)
{
	global $bdd;
	
	$uid = ($id == null)?getUserId():$id;

	$reqString = 'UPDATE users SET ';
	$putComma = false;
	$reqArray = array();

	if($pseudo != null)
	{
		$reqString.= ' pseudo = :pseudo';
		$putComma = true;
		$reqArray['pseudo'] = $pseudo;
	}
	if($nom != null)
	{
		$reqString.= ($putComma?',':'') . ' name = :name';
		$putComma = true;
		$reqArray['name'] = $nom;
	}
	if($genre != null)
	{
		$reqString.= ($putComma?',':'') . ' gender = :gender';
		$putComma = true;
		$reqArray['gender'] = $genre;
	}
	if($email != null)
	{
		$reqString.= ($putComma?',':'') . ' email = :email';
		$putComma = true;
		$reqArray['email'] = $email;
	}
	if($bio != null)
	{
		$reqString.= ($putComma?',':'') . ' bio = :bio';
		$putComma = true;
		$reqArray['bio'] = $bio;
	}
	if($date_naissance != null)
	{
		$reqString.= ($putComma?',':'') . ' birth = :birth';
		$putComma = true;
		$reqArray['birth'] = $date_naissance;
	}
	if($pswd != null)
	{
		$reqString.= ($putComma?',':'') . ' pswd = :pswd';
		$putComma = true;
		$reqArray['pswd'] = sha1($pswd);
	}
	$reqString.= ($putComma?',':'') . ' avatar = :avatar';
	$reqArray['avatar'] = $hasAvatar;
	
	$reqString.= ' WHERE id = :id';
	$reqArray['id'] = $uid;

	$req = $bdd->prepare($reqString);
	$req->execute($reqArray);
}

/*
 * Vérifie si le pseudo existe ou pas dans la BDD
 */
function isExistingPseudo($pseudo)
{
	global $bdd;
	$pseudos = $bdd->query('SELECT pseudo FROM users')->fetchall(PDO::FETCH_COLUMN);

	foreach($pseudos as $user)
		if($user == $pseudo)
			return true;
	return false;
}

/*
 * Vérifie si le mail existe ou pas dans la BDD
 */
function isExistingEmail($email)
{
	global $bdd;
	$emails = $bdd->query('SELECT email FROM users')->fetchall(PDO::FETCH_COLUMN);
	$email = strtolower($email);

	foreach($emails as $m)
		if(strtolower($m) == $email)
			return true;
	return false;
}

/*
 * Récupère les infos utilisateurs sur la base du pseudo
 */
function getUserInfoByPseudo($pseudo)
{
	global $bdd;

	$req = $bdd->prepare('SELECT * FROM users WHERE pseudo = :pseudo');
	$req->execute(array('pseudo' => $pseudo));
	$userInfo = $req->fetch();
	
	return $userInfo;
}

/*
 * Récupère les infos utilisateurs sur la base de l'id
 */
function getUserInfoById($id)
{
	global $bdd;

	$req = $bdd->prepare('SELECT * FROM users WHERE id = :id');
	$req->execute(array('id' => $id));
	$userInfo = $req->fetch();
	
	return $userInfo;
}
