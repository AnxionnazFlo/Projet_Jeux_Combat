<?php
// On enregistre notre autoload.
function chargerClasse($classname)
{
  require 'class/' . $classname . '.php';
}

spl_autoload_register('chargerClasse');

session_start(); // On appelle session_start() APRÈS avoir enregistré l'autoload.

if (isset($_GET['deconnexion'])) {
  session_destroy();
  header('Location: test.php');
  exit();
}



try {
  $db = new PDO('mysql:host=localhost;dbname=jeuxCombat', 'root', '');
} catch (Exception $e) {
  die('Erreur : ' . $e->getMessage());
}

$manager = new PersonnagesManager($db);

if (isset($_SESSION['perso'])) // Si la session perso existe, on restaure l'objet.
{
  $perso = $_SESSION['perso'];
}

if (isset($_POST['creer']) && isset($_POST['nom'])) // Si on a voulu créer un personnage. 
{
  $perso = new Personnage(['nom' => $_POST['nom']]); // On crée un nouveau personnage.

  if (!$perso->nomValide()) {
    $message = 'Le nom choisi est invalide.';
    unset($perso);
  } elseif ($manager->exists($perso->getNom())) {
    $message = 'Le nom du personnage est déjà pris.';
    unset($perso);
  } else {
    $manager->add($perso);
  }
} elseif (isset($_POST['utiliser']) && isset($_POST['nom'])) // Si on a voulu utiliser un personnage.
{
  if ($manager->exists($_POST['nom'])) {
    $perso = $manager->get($_POST['nom']);
  } else {
    $message = 'Ce personnage n\'existe pas !'; // S'il n'existe pas, on affichera ce message.
  }
} elseif (isset($_GET['frapper'])) // Si on a cliqué sur un personnage pour le frapper.
{
  if (!isset($perso)) {
    $message = 'Merci de créer un personnage ou de vous identifier.';
  } else {
    if (!$manager->exists((int) $_GET['frapper'])) {
      $message = 'Le personnage que vous voulez frapper n\'existe pas !';
    } else {
      $persoAFrapper = $manager->get((int) $_GET['frapper']);

      $retour = $perso->frapper($persoAFrapper); // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper.

      switch ($retour) {
        case Personnage::CEST_MOI:
          $message = 'Mais... pourquoi voulez-vous vous frapper ???';
          break;

        case Personnage::PERSONNAGE_FRAPPE:
          $message = 'Le personnage a bien été frappé !';

          $manager->update($perso);
          $manager->update($persoAFrapper);

          break;

        case Personnage::PERSONNAGE_TUE:
          $message = 'Vous avez tué ce personnage !';

          $manager->update($perso);
          $manager->delete($persoAFrapper);

          break;
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>TP : Mini jeu de combat</title>

  <meta charset="utf-8">
</head>

<body>
  <p>Nombre de personnages créés : <?= $manager->count() ?></p>
  <?php
  if (isset($message)) // On a un message à afficher ?
  {
    echo '<p>', $message, '</p>'; // Si oui, on l'affiche.
  }
  // Si on utilise un personnage (nouveau ou pas)
  if (isset($perso)) {
  ?>
    <p><a href="?deconnexion=1">Déconnexion</a></p>

    <fieldset>
      <legend>Mes informations</legend>
      <p>
        Nom : <?= htmlspecialchars($perso->getNom()) ?><br>
        Dégâts : <?= $perso->getDegats() ?>
      </p>
    </fieldset>

    <fieldset>
      <legend>Qui frapper ?</legend>
      <p>
        <?php
        $persos = $manager->getList($perso->getNom());

        if (empty($persos)) {
          echo 'Personne à frapper !';
        } else {
          foreach ($persos as $unPerso) {
            echo '<a href="?frapper=', $unPerso->getId(), '">', htmlspecialchars($unPerso->getNom()), '</a> (dégâts : ', $unPerso->getDegats(), ')<br>';
          }
        }
        ?>
      </p>
    </fieldset>
  <?php
  } else {
  ?>
    <form action="" method="post">
      <p>
        Nom : <input type="text" name="nom" maxlength="50">
        <input type="submit" value="Créer ce personnage" name="creer">
        <input type="submit" value="Utiliser ce personnage" name="utiliser">
      </p>
    </form>
  <?php
  }
  ?>
</body>

</html>
<?php
// Si on a créé un personnage, on le stocke dans une variable session afin d'économiser une requête SQL.
if (isset($perso)) {
  $_SESSION['perso'] = $perso;
}
