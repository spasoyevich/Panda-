<!DOCTYPE html>
<html lang="fr">
<head>
  <?php
  //Si le titre est indiqué, on l'affiche entre le balises<title>
  echo (!empty($titre))?'<title>'.$titre.'</title>':'<title> Forum </title>';

  $balises=(isset($balises))?$balises:0;
      if($balises)
      {
      //Inclure le script
      }
        ?>
	 <!--C'est une façon plus rapide d'écrire une condition, ça revient au même que de faire :-->
	 <!--<?php
	//if (!empty($titre))
	// {
	//echo '<title> '.$titre.' </title>';
	//  }
	//else //Sinon, on écrit forum par défaut
	//  {
	//  echo '<title> Forum </title>';
	 //}
	//?>-->

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="../css/design.css">
	</head>
	<?php

	//On inclue les 2 page
	include_once("functions.php");
	include_once("constants.php");

	//Attribution des variables de session
	$lvl = (isset($_SESSION['level']))?(int) $_SESSION['level']:1;
	$id = (isset($_SESSION['id']))?(int) $_SESSION['id']:0;
	$pseudo = (isset($_SESSION['pseudo']))?$_SESSION['pseudo']:'';



	if (isset ($_COOKIE['pseudo']) && empty($id))
	{
	$_SESSION['pseudo'] = $_COOKIE['pseudo'];

	/* On créé la variable de session à partir du cookie pour ne pas avoir à vérifier 2 fois sur les pages qu'un membre est connecté. */

	}
	if (isset ($_COOKIE['pseudo']) && !empty($id))
	{
	//On est connecté
	}
	if (!isset ($_COOKIE['pseudo']) && empty($id))
	{
	//On n'est pas connecté
	}
	?>
