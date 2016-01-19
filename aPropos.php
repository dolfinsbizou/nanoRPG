<?php
include_once("model/sessions.php");
include_once("model/tech.php");
include_once("model/users.php");

$porteurs = array(getUserInfoByPseudo('dolfsquare'), getUserInfoByPseudo('ayzyhor'));

foreach($porteurs as &$porteur)
{
	if(!empty($porteur))
	{
		$porteur['name'] = htmlspecialchars($porteur['name']);
		$porteur['pseudo'] = htmlspecialchars($porteur['pseudo']);
		$porteur['bio'] = htmlspecialchars(explode(PHP_EOL, $porteur['bio'])[0]);
	}
}

$page_title = "A propos";
include_once("view/aPropos.php");