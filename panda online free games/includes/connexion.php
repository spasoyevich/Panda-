<?php
session_start();
$titre="Connexion";
include_once("identifiants.php");
include_once("debut.php");
include_once("menu.php");
//include("functions.php");
?>
<p><i>Vous êtes ici</i> : <a href="index.php">Index du forum</a> --> Connexion

<?php
//if ($id!=0) erreur(ERR_IS_CO);



if (!isset($_POST['pseudo'])) //On est dans la page de formulaire
{
  ?>

<form method="post" action="connexion.php">
  <fieldset>
    <legend>Connexion</legend>
    <p>
      <label for="pseudo">Pseudo :</label><input type="text" name="pseudo" id="pseudo"/><br/>
      <label for="password">Mot de Passe :</label><input type="password" name="password" id="password"/>
    </p>
  </fieldset>
  <p><input type="submit" value="Connexion"/></p>
</form>
<a href="register.php">Pas encore inscrit ?</a>

  <?php
}



//On reprend la suite du code
else
{
    //$message='';
    if (empty($_POST['pseudo']) || empty($_POST['password']) ) //ici on chequesi il y'a Oublie d'un champ
    { //si oui on envoyer un message indiquant que il y'a un erreur
        $message = '<p>une erreur s\'est produite pendant votre identification.
  Vous devez remplir tous les champs</p>
  <p>Cliquez <a href="./connexion.php">ici</a> pour revenir</p>';
    }
    else //Si non, On check le mot de passe !
    { 
        $query=$db->prepare('SELECT membre_mdp, membre_id, membre_rang, membre_pseudo
        FROM forum_membres WHERE membre_pseudo = :pseudo');
        $query->bindValue(':pseudo',$_POST['pseudo'], PDO::PARAM_STR);
        $query->execute();
        $data=$query->fetch();


if ($data['membre_mdp'] == md5($_POST['password'])) // Acces OK !
{
    if ($data['membre_rang'] == 0) //Peu etre que Le membre est banni
    {
        $message="<p>Vous avez été banni, impossible de vous connecter sur ce forum</p>";
    }
    else //Sinon c'est ok, on se connecte
    {
        $_SESSION['pseudo'] = $data['membre_pseudo'];
        $_SESSION['level'] = $data['membre_rang'];
        $_SESSION['id'] = $data['membre_id'];

        //ici nous send le message que le membre est bien connecte !
        $message = '<p>Bienvenue '.$_SESSION['pseudo'].', vous êtes maintenant connecté !</p> <br />
        <p>Cliquez <a href="/index.php">ici</a> pour revenir à la page d accueil</p>';
    }
}

  if (isset($_POST['souvenir']))
{
$expire = time() + 365*24*3600;
setcookie('pseudo', $_SESSION['pseudo'], $expire);
}

  else // Acces pas OK !
  {
      $message = '<p>Une erreur s\'est produite
      pendant votre identification.<br /> Le mot de passe ou le pseudo
            entré n\'est pas correcte.</p><p>Cliquez <a href="./connexion.php">ici</a>
      pour revenir à la page précédente
      <br /><br />Cliquez <a href="../includes/index.php">ici</a>
      pour revenir à la page d accueil</p>';
  }
    $query->CloseCursor();
    }
    echo $message.'</div></body></html>';

}

//$page = htmlspecialchars($_POST['page']);
//echo 'Cliquez <a href="'.$page.'">ici</a> pour revenir à la page précédente';
?>

<!-- a voir u place - da proverim gde treba da stavim
if (isset($_POST['souvenir']))
{
$expire = time() + 365*24*3600;
setcookie('pseudo', $_SESSION['pseudo'], $expire);
}
?>-->

<input type="hidden" name="page" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
