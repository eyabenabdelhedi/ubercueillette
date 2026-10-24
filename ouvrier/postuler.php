<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_ouvrier'])) { header("Location: ../connexion.php"); exit; }
$id_offre=isset($_GET['id'])?(int)$_GET['id']:0;
$id_ouvrier=$_SESSION['id_ouvrier'];
$message=""; $erreur=""; $offre=null;
if ($id_offre>0) {
    $of=$conn->prepare("SELECT o.*,tf.libelle AS fruit,g.libelle AS gouvernorat,(SELECT COUNT(*) FROM candidature c WHERE c.id_offre=o.id_offre AND c.decision='accepte') AS nb_acc FROM offre o JOIN type_fruit tf ON o.id_type_fruit=tf.id_type_fruit JOIN gouvernorat g ON o.id_gouvernorat=g.id_gouvernorat WHERE o.id_offre=?");
    $of->execute([$id_offre]); $offre=$of->fetch();
    if (!$offre) $erreur="Cette offre n'existe pas.";
    elseif ($offre['date_limite']<date('Y-m-d')) $erreur="La date limite de postulation est depassee.";
    elseif ($offre['nb_acc']>=$offre['nombre_ouvriers']) $erreur="Cette offre est complete.";
    else {
        $check=$conn->prepare("SELECT id_candidature FROM candidature WHERE id_offre=? AND id_ouvrier=?");
        $check->execute([$id_offre,$id_ouvrier]);
        if ($check->fetch()) $erreur="Vous avez deja postule a cette offre.";
        else {
            $conn->prepare("INSERT INTO candidature(id_offre,id_ouvrier,decision,date_candidature) VALUES(?,?,'en_attente',NOW())")->execute([$id_offre,$id_ouvrier]);
            $message="Candidature envoyee avec succes !";
        }
    }
} else $erreur="Offre invalide.";
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Postuler</title><link rel="stylesheet" href="../css/style.css"></head>
<body><?php include("../config/navbar.php"); ?>
<div class="page-wrap">
  <div class="page-header"><div><p class="breadcrumb"><a href="../offre/lister.php">Offres</a> &rsaquo; Postuler</p><h2>Candidature</h2></div></div>
  <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <?php if ($erreur):  ?><div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
  <?php if ($offre && !$erreur): ?>
  <div class="offre-card">
    <div class="offre-card-title"><?= htmlspecialchars($offre['fruit']) ?></div>
    <div class="offre-card-sub"><?= htmlspecialchars($offre['gouvernorat']) ?> &mdash; <?= htmlspecialchars($offre['adresse']) ?></div>
    <div class="offre-card-meta mt-8">
      <span class="chip chip-gray"><?= $offre['date_debut'] ?> &rarr; <?= $offre['date_fin'] ?></span>
      <span class="chip chip-yellow"><?= $offre['prix_journee'] ?> DT/jour</span>
    </div>
  </div>
  <?php endif; ?>
  <div class="btn-row mt-16">
    <a href="../offre/lister.php" class="btn btn-secondary">Retour aux offres</a>
    <a href="mescandidatures.php" class="btn btn-ghost">Mes candidatures</a>
  </div>
</div></body></html>
