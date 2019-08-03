<?php
session_start();
$titre="Messages Privés";
$balises = true;
include("identifiants.php");
include("debut.php");
include("bbcode.php");
include("menu.php");
include("functions.php");


$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';
switch($action) //On switch sur $action
{

case "consulter": //Si on veut lire un message

    echo'<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> <a href="./messagesprives.php">Messagerie privée</a> --> Consulter un message</p>';
    $id_mess = (int) $_GET['id']; //On récupère la valeur de l'id
    echo '<h1>Consulter un message</h1><br /><br />';

    //La requête nous permet d'obtenir les infos sur ce message :
    $query = $db->prepare('SELECT  mp_expediteur, mp_receveur, mp_titre,
    mp_time, mp_text, mp_lu, membre_id, membre_pseudo, membre_avatar,
    membre_localisation, membre_inscrit, membre_post, membre_signature
    FROM forum_mp
    LEFT JOIN forum_membres ON membre_id = mp_expediteur
    WHERE mp_id = :id');
    $query->bindValue(':id',$id_mess,PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();

    // Attention ! Seul le receveur du mp peut le lire !
    if ($id != $data['mp_receveur']) erreur(ERR_WRONG_USER);

    //bouton de réponse
    echo'<p><a href="./messagesprives.php?action=repondre&amp;dest='.$data['mp_expediteur'].'">
    <img src="../images/repondre.gif" alt="Répondre"
    title="Répondre à ce message" /></a></p>';
?>

<table>
    <tr>
    <th class="vt_auteur"><strong>Auteur</strong></th>
    <th class="vt_mess"><strong>Message</strong></th>
    </tr>
    <tr>
    <td>
    <?php echo'<strong>
    <a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">
    '.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a></strong></td>
    <td>Posté à '.date('H\hi \l\e d M Y',$data['mp_time']).'</td>';
    ?>
    </tr>
    <tr>
    <td>
    <?php

    //Ici des infos sur le membre qui a envoyé le mp
    echo'<p><img src="./images/avatars/'.$data['membre_avatar'].'" alt="" />
    <br />Membre inscrit le '.date('d/m/Y',$data['membre_inscrit']).'
    <br />Messages : '.$data['membre_post'].'
    <br />Localisation : '.stripslashes(htmlspecialchars($data['membre_localisation'])).'</p>
    </td><td>';

    echo code(nl2br(stripslashes(htmlspecialchars($data['mp_text'])))).'
    <hr />'.code(nl2br(stripslashes(htmlspecialchars($data['membre_signature'])))).'
    </td></tr></table>';

    if ($data['mp_lu'] == 0) //Si le message n'a jamais été lu
    {
        $query->CloseCursor();
        $query=$db->prepare('UPDATE forum_mp
        SET mp_lu = :lu
        WHERE mp_id= :id');
        $query->bindValue(':id',$id_mess, PDO::PARAM_INT);
        $query->bindValue(':lu','1', PDO::PARAM_STR);
        $query->execute();
        $query->CloseCursor();
    }

break; //La fin !

case "nouveau": //Nouveau mp

   echo'<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> <a href="../includes/messagesprives.php">Messagerie privée</a> --> Ecrire un message</p>';
   echo '<h1>Nouveau message privé</h1><br /><br />';
   ?>
   <form method="post" action="postok.php?action=nouveaump" name="formulaire">
   <p>
   <label for="to">Envoyer à : </label>
   <input type="text" size="30" id="to" name="to" />
   <br />
   <label for="titre">Titre : </label>
   <input type="text" size="80" id="titre" name="titre" />
   <br /><br />
   <input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
   <input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
   <input type="button" id="souligné" name="souligné" value="Souligné" onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
   <input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
   <br /><br />
   <img src="./images/smileys/heureux.gif" title="heureux" alt="heureux" onClick="javascript:smilies(':D');return(false)" />
   <img src="./images/smileys/lol.gif" title="lol" alt="lol" onClick="javascript:smilies(':lol:');return(false)" />
   <img src="./images/smileys/triste.gif" title="triste" alt="triste" onClick="javascript:smilies(':triste:');return(false)" />
   <img src="./images/smileys/cool.gif" title="cool" alt="cool" onClick="javascript:smilies(':frime:');return(false)" />
   <img src="./images/smileys/rire.gif" title="rire" alt="rire" onClick="javascript:smilies('XD');return(false)" />
   <img src="./images/smileys/confus.gif" title="confus" alt="confus" onClick="javascript:smilies(':s');return(false)" />
   <img src="./images/smileys/choc.gif" title="choc" alt="choc" onClick="javascript:smilies(':O');return(false)" />
   <img src="./images/smileys/question.gif" title="?" alt="?" onClick="javascript:smilies(':interrogation:');return(false)" />
   <img src="./images/smileys/exclamation.gif" title="!" alt="!" onClick="javascript:smilies(':exclamation:');return(false)" />

   <textarea cols="80" rows="8" id="message" name="message"></textarea>
   <br />
   <input type="submit" name="submit" value="Envoyer" />
   <input type="reset" name="Effacer" value="Effacer" /></p>
   </form>

<?php
break;

case "repondre": //On veut répondre
   echo'<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> <a href="./messagesprives.php">Messagerie privée</a> --> Ecrire un message</p>';
   echo '<h1>Répondre à un message privé</h1><br /><br />';

   $dest = (int) $_GET['dest'];
   ?>
   <form method="post" action="postok.php?action=repondremp&amp;dest=<?php echo $dest ?>" name="formulaire">
   <p>
   <label for="titre">Titre : </label><input type="text" size="80" id="titre" name="titre" />
   <br /><br />
   <input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
   <input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
   <input type="button" id="souligné" name="souligné" value="Souligné" onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
   <input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
   <br /><br />
   <img src="./images/smileys/heureux.gif" title="heureux" alt="heureux" onClick="javascript:smilies(':D');return(false)" />
   <img src="./images/smileys/lol.gif" title="lol" alt="lol" onClick="javascript:smilies(':lol:');return(false)" />
   <img src="./images/smileys/triste.gif" title="triste" alt="triste" onClick="javascript:smilies(':triste:');return(false)" />
   <img src="./images/smileys/cool.gif" title="cool" alt="cool" onClick="javascript:smilies(':frime:');return(false)" />
   <img src="./images/smileys/rire.gif" title="rire" alt="rire" onClick="javascript:smilies('XD');return(false)" />
   <img src="./images/smileys/confus.gif" title="confus" alt="confus" onClick="javascript:smilies(':s');return(false)" />
   <img src="./images/smileys/choc.gif" title="choc" alt="choc" onClick="javascript:smilies(':O');return(false)" />
   <img src="./images/smileys/question.gif" title="?" alt="?" onClick="javascript:smilies(':interrogation:');return(false)" />
   <img src="./images/smileys/exclamation.gif" title="!" alt="!" onClick="javascript:smilies(':exclamation:');return(false)" />

   <br /><br />
   <textarea cols="80" rows="8" id="message" name="message"></textarea>
   <br />
   <input type="submit" name="submit" value="Envoyer" />
   <input type="reset" name="Effacer" value="Effacer"/>
   </p></form>

   <?php
   break;

    default;
    echo'<p>Cette action est impossible</p>';
} //Fin du Switch
?>



</div>
</body>
</html>

