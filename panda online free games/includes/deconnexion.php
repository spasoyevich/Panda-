<?php
session_start();
//session_destroy();
$titre="Déconnexion";
include_once("debut.php");
include_once("menu.php");
//include_once("functions.php");

//session_start();
if (isset ($_COOKIE['pseudo']))
{
setcookie('pseudo', '', -1);
}
session_destroy();

if ($id==0) erreur(ERR_IS_NOT_CO);


echo '<p>Vous êtes à présent déconnecté <br />
Cliquez <a href="'.htmlspecialchars($_SERVER['HTTP_REFERER']).'">ici</a>
pour revenir à la page précédente.<br />
Cliquez <a href="../includes/index.php">ici</a> pour revenir à la page principale</p>';
echo '</div></body></html>';
?>

