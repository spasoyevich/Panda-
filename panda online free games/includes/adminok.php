<?php

session_start();
$titre="Administration";
$balises = true;
include("identifiants.php");
include("debut.php");
include("menu.php");
include("functions.php");


$cat = htmlspecialchars($_GET['cat']); //on récupère dans l'url la variable cat
switch($cat) //1er switch
{
case "config":
    echo'<h1>Configuration du forum</h1>';
    //On récupère les valeurs et le nom de chaque entrée de la table
    $query=$db->query('SELECT config_nom, config_valeur FROM forum_config');
    //Avec cette boucle, on va pouvoir contrôler le résultat pour voir s'il a changé
    while($data = $query->fetch())
    {
        if ($data['config_valeur'] != $_POST[$data['config_nom']])
  {
            //On met ensuite à jour
            $valeur = htmlspecialchars($_POST[$data['config_nom']]);
      $query=$db->prepare('UPDATE forum_config SET config_valeur = :valeur
            WHERE config_nom = :nom');
            $query->bindValue(':valeur', $valeur, PDO::PARAM_STR);
            $query->bindValue(':nom', $data['config_nom'],PDO::PARAM_STR);
            $query->execute();
  }
    }
    $query->CloseCursor();
    //Et le message !
    echo'<br /><br />Les nouvelles configurations ont été mises à jour !<br />
    Cliquez <a href="./admin.php">ici</a> pour revenir à l administration';
break;

case "forum":
    //Ici forum
    $action = htmlspecialchars($_GET['action']); //On récupère la valeur de action
    switch($action) //2ème switch
    {
    case "creer":

        //On commence par les forums
    if ($_GET['c'] == "f")
    {
        $titre = $_POST['nom'];
        $desc = $_POST['desc'];
        $cat = (int) $_POST['cat'];


        $query=$db->prepare('INSERT INTO forum_forum (forum_cat_id, forum_name, forum_desc)
        VALUES (:cat, :titre, :desc)');
            $query->bindValue(':cat',$cat,PDO::PARAM_INT);
            $query->bindValue(':titre',$titre, PDO::PARAM_STR);
            $query->bindValue(':desc',$desc,PDO::PARAM_STR);
            $query->execute();
        echo'<br /><br />Le forum a été créé !<br />
        Cliquez <a href="./admin.php">ici</a> pour revenir à l administration';
        $query->CloseCursor();
        }
        //Puis par les catégories
        elseif ($_GET['c'] == "c")
        {
            $titre = $_POST['nom'];
            $query=$db->prepare('INSERT INTO forum_categorie (cat_nom) VALUES (:titre)');
            $query->bindValue(':titre',$titre, PDO::PARAM_STR);
            $query->execute();
            echo'<p>La catégorie a été créée !<br /> Cliquez <a href="./admin.php">ici</a>
            pour revenir à l administration</p>';
        $query->CloseCursor();
        }
    break;

case "edit":
        echo'<h1>Edition d un forum</h1>';

        if($_GET['e'] == "editf")
        {
            //Récupération d'informations

        $titre = $_POST['nom'];
        $desc = $_POST['desc'];
        $cat = (int) $_POST['depl'];

            //Vérification
            $query=$db->prepare('SELECT COUNT(*)
            FROM forum_forum WHERE forum_id = :id');
            $query->bindValue(':id',(int) $_POST['forum_id'],PDO::PARAM_INT);
            $query->execute();
            $forum_existe=$query->fetchColumn();
            $query->CloseCursor();
            if ($forum_existe == 0) erreur(ERR_FOR_EXIST);


            //Mise à jour
            $query=$db->prepare('UPDATE forum_forum
            SET forum_cat_id = :cat, forum_name = :name, forum_desc = :desc
            WHERE forum_id = :id');
            $query->bindValue(':cat',$cat,PDO::PARAM_INT);
            $query->bindValue(':name',$titre,PDO::PARAM_STR);
            $query->bindValue(':desc',$desc,PDO::PARAM_STR);
            $query->bindValue(':id',(int) $_POST['forum_id'],PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();
            //Message
            echo'<p>Le forum a été modifié !<br />Cliquez <a href="./admin.php">ici</a>
            pour revenir à l administration</p>';

        }elseif($_GET['e'] == "editc")
        {
            //Récupération d'informations
            $titre = $_POST['nom'];

            //Vérification
            $query=$db->prepare('SELECT COUNT(*)
            FROM forum_categorie WHERE cat_id = :cat');
            $query->bindValue(':cat',(int) $_POST['cat'],PDO::PARAM_INT);
            $query->execute();
            $cat_existe=$query->fetchColumn();
            $query->CloseCursor();
            if ($cat_existe == 0) erreur(ERR_CAT_EXIST);

            //Mise à jour
            $query=$db->prepare('UPDATE forum_categorie
            SET cat_nom = :name WHERE cat_id = :cat');
            $query->bindValue(':name',$titre,PDO::PARAM_STR);
            $query->bindValue(':cat',(int) $_POST['cat'],PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();

            //Message
            echo'<p>La catégorie a été modifiée !<br />
            Cliquez <a href="./admin.php">ici</a>
            pour revenir à l administration</p>';

        }elseif($_GET['e'] == "ordref")
        {
            //On récupère les id et l'ordre de tous les forums
            $query=$db->query('SELECT forum_id, forum_ordre FROM forum_forum');

            //On boucle les résultats
            while($data= $query->fetch())
            {
                $ordre = (int) $_POST[$data['forum_id']];

                //Si et seulement si l'ordre est différent de l'ancien, on le met à jour
                if ($data['forum_ordre'] != $ordre)
                {
                    $query=$db->prepare('UPDATE forum_forum SET forum_ordre = :ordre
                    WHERE forum_id = :id');
                    $query->bindValue(':ordre',$ordre,PDO::PARAM_INT);
                    $query->bindValue(':id',$data['forum_id'],PDO::PARAM_INT);
                    $query->execute();
                    $query->CloseCursor();
                }
            }
        $query->CloseCursor();
        //Message
        echo'<p>L ordre a été modifié !<br />
        Cliquez <a href="./admin.php">ici</a> pour revenir à l administration</p>';
        }elseif($_GET['e'] == "ordrec")
        {

            //On récupère les id et les ordres de toutes les catégories
            $query=$db->query('SELECT cat_id, cat_ordre FROM forum_categorie');

            //On boucle le tout
            while($data = $query->fetch())
            {
                $ordre = (int) $_POST[$data['cat_id']];

                //On met à jour si l'ordre a changé
                if($data['cat_ordre'] != $ordre)
                {
                    $query=$db->prepare('UPDATE forum_categorie SET cat_ordre = :ordre
                    WHERE cat_id = :id');
                    $query->bindValue(':ordre',$ordre,PDO::PARAM_INT);
                    $query->bindValue(':id',$data['cat_id'],PDO::PARAM_INT);
                    $query->execute();
                    $query->CloseCursor();
                }
            }
        echo'<p>L ordre a été modifié !<br />
        Cliquez <a href="./admin.php">ici</a> pour revenir à l administration</p>';
        }
    break;

    case "droits":
        //Récupération d'informations
        $auth_view = (int) $_POST['auth_view'];
        $auth_post = (int) $_POST['auth_post'];
        $auth_topic = (int) $_POST['auth_topic'];
        $auth_annonce = (int) $_POST['auth_annonce'];
        $auth_modo = (int) $_POST['auth_modo'];

        //Mise à jour
        $query=$db->prepare('UPDATE forum_forum
        SET auth_view = :view, auth_post = :post, auth_topic = :topic,
        auth_annonce = :annonce, auth_modo = :modo WHERE forum_id = :id');
        $query->bindValue(':view',$auth_view,PDO::PARAM_INT);
        $query->bindValue(':post',$auth_post,PDO::PARAM_INT);
        $query->bindValue(':topic',$auth_topic,PDO::PARAM_INT);
        $query->bindValue(':annonce',$auth_annonce,PDO::PARAM_INT);
        $query->bindValue(':modo',$auth_modo,PDO::PARAM_INT);
        $query->bindValue(':id',(int) $_POST['forum_id'],PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();

        //Message
        echo'<p>Les droits ont été modifiés !<br />
        Cliquez <a href="./admin.php">ici</a> pour revenir à l administration</p>';
    break;












case "edit":
            echo'<h1>Edition du profil d un membre</h1>';

            if(!isset($_POST['membre'])) //Si la variable $_POST['membre'] n'existe pas
            {
                echo'De quel membre voulez-vous éditer le profil ?<br />';
                echo'<br /><form method="post" action="./admin.php?cat=membres&amp;action=edit">
                <p><label for="membre">Inscrivez le pseudo : </label>
                <input type="text" id="membre" name="membre"><input type="submit" name="Chercher">
                </p></form>';
            }
        else //sinon
        {
            $pseudo_d = $_POST['membre'];

            //Requête qui ramène des info sur le membre à
            //Partir de son pseudo
            $query = $db->prepare('SELECT membre_id,
            membre_pseudo, membre_email,
            membre_siteweb, membre_signature,
            membre_msn, membre_localisation, membre_avatar
            FROM forum_membres WHERE LOWER(membre_pseudo)=:pseudo');
            $query->bindValue(':pseudo',strtolower($pseudo_d),PDO::PARAM_STR);
            $query->execute();
            //Si la requête retourne un truc, le membre existe
            if ($data = $query->fetch())
            {
                ?>
                <form method="post" action="adminok.php?cat=membres&amp;action=edit" enctype="multipart/form-data">
                <fieldset><legend>Identifiants</legend>
                <label for="pseudo">Pseudo :</label>
                <input type="text" name="pseudo" id="pseudo"
                value="<?php echo stripslashes(htmlspecialchars($data['membre_pseudo'])) ?>" /><br />
                </fieldset>

                <fieldset><legend>Contacts</legend>
                <label for="email">Adresse E_Mail :</label>
                <input type = "text" name="email" id="email"
                value="<?php echo stripslashes(htmlspecialchars($data['membre_email'])) ?>" /><br />
                <label for="msn">Adresse MSN :</label>
                <input type = "text" name="msn" id="msn"
                value="<?php echo stripslashes(htmlspecialchars($data['membre_msn'])) ?>" /><br />
                <label for="website">Site web :</label>
                <input type = "text" name="website" id="website"
                value="<?php echo stripslashes(htmlspecialchars($data['membre_siteweb'])) ?>"/><br />
                </fieldset>

                <fieldset><legend>Informations supplémentaire</legend>
                <label for="localisation">Localisation :</label>
                <input type = "text" name="localisation" id="localisation"
                value="<?php echo stripslashes(htmlspecialchars($data['membre_localisation'])) ?>" />
                <br />
                </fieldset>

                <fieldset><legend>Profil sur le forum</legend>
                <label for="avatar">Changer l avatar :</label>
                <input type="file" name="avatar" id="avatar" />
                <br /><br />
                <label><input type="checkbox" name="delete" value="Delete" /> Supprimer l avatar</label>
                Avatar actuel :
                <?php echo'
                <img src="./images/avatars/'.$data['membre_avatar'].'" alt="pas d avatar" />' ?>

                <br /><br />
                <label for="signature">Signature :</label>
                <textarea cols=40 rows=4 name="signature" id="signature">
                <?php echo $data['membre_signature'] ?></textarea>

                <br /></h2>
                </fieldset>
                <?php
                echo'<input type="hidden" value="'.stripslashes($pseudo_d).'" name="pseudo_d">
                <input type="submit" value="Modifier le profil" /></form>';
                $query->CloseCursor();

            }
            else echo' <p>Erreur : Ce membre n existe pas, <br />
            cliquez <a href="./admin.php?cat=membres&amp;action=edit">ici</a> pour réessayez</p>';
        }
    break;














    case "droits":
    $membre =$_POST['pseudo'];
    $rang = (int) $_POST['droits'];
    $query=$db->prepare('UPDATE forum_membres SET membre_rang = :rang
    WHERE LOWER(membre_pseudo) = :pseudo');
        $query->bindValue(':rang',$rang,PDO::PARAM_INT);
        $query->bindValue(':pseudo',strtolower($membre), PDO::PARAM_STR);
        $query->execute();
        $query->CloseCursor();
    echo'<p>Le niveau du membre a été modifié !<br />
    Cliquez <a href="./admin.php">ici</a> pour revenir à l administration</p>';
    break;

    case "ban":
        //Bannissement dans un premier temps
        //Si jamais on n'a pas laissé vide le champ pour le pseudo
        if (isset($_POST['membre']) AND !empty($_POST['membre']))
        {
            $membre = $_POST['membre'];
            $query=$db->prepare('SELECT membre_id
            FROM forum_membres WHERE LOWER(membre_pseudo) = :pseudo');
            $query->bindValue(':pseudo',strtolower($membre), PDO::PARAM_STR);
            $query->execute();
            //Si le membre existe
            if ($data = $query->fetch())
            {
                //On le bannit
                $query=$db->prepare('UPDATE forum_membres SET membre_rang = 0
                WHERE membre_id = :id');
                $query->bindValue(':id',$data['membre_id'], PDO::PARAM_INT);
                $query->execute();
                $query->CloseCursor();
                echo'<br /><br />
                Le membre '.stripslashes(htmlspecialchars($membre)).' a bien été banni !<br />';
            }
            else
            {
                echo'<p>Désolé, le membre '.stripslashes(htmlspecialchars($membre)).' n existe pas !
                <br />
                Cliquez <a href="./admin.php?cat=membres&action=ban">ici</a>
                pour réessayer</p>';
            }
        }
        //Debannissement ici
        $query = $db->query('SELECT membre_id FROM forum_membres
        WHERE membre_rang = 0');
        //Si on veut débannir au moins un membre
        if ($query->rowCount() > 0)
        {
        $i=0;
            while($data= $query->fetch())
            {
                if(isset($_POST[$data'membre_id']))
                {
                $i++;
                    //On remet son rang à 2
                    $query=$db->prepare('UPDATE forum_membres SET membre_rang = 2
                    WHERE membre_id = :id');
                    $query->bindValue(':id',$data['membre_id'],PDO::PARAM_INT);
                    $query->execute();
                    $query->CloseCursor();
                }
            }
        if ($i!=0)
            echo'<p>Les membres ont été débannis<br />
            Cliquez <a href="./admin.php">ici</a> pour retourner à l administration</p>';
        }
    break;
    }
break;
}

