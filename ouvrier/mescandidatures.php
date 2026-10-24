<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_ouvrier'])) { header("Location: ../connexion.php"); exit; }
$id = $_SESSION['id_ouvrier'];
$stmt = $conn->prepare("SELECT c.*, o.adresse, o.date_debut, o.date_fin, o.prix_journee,
        tf.libelle AS fruit, g.libelle AS gouvernorat
        FROM candidature c
        JOIN offre o ON c.id_offre = o.id_offre
        JOIN type_fruit tf ON o.id_type_fruit = tf.id_type_fruit
        JOIN gouvernorat g ON o.id_gouvernorat = g.id_gouvernorat
        WHERE c.id_ouvrier = ?
        ORDER BY c.date_candidature DESC");
$stmt->execute([$id]); $cands = $stmt->fetchAll();
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mes candidatures</title><link rel="stylesheet" href="../css/style.css"></head>
<body><?php include("../config/navbar.php"); ?>
<div class="page-wide">
  <div class="page-header">
    <div><p class="breadcrumb"><a href="menu.php">Tableau de bord</a> &rsaquo; Mes candidatures</p><h2>Mes candidatures</h2></div>
    <a href="../offre/lister.php" class="btn btn-yellow" style="width:auto;padding:10px 20px;">Voir les offres</a>
  </div>
  <?php if (empty($cands)): ?>
    <div class="empty-state"><div class="empty-icon">&#128228;</div><p>Vous n'avez pas encore postulé.</p></div>
  <?php else: ?>
    <?php foreach($cands as $c):
      $dec = $c['decision'];
      $bClass = $dec==='acceptation' ? 'badge-ok' : ($dec==='refus' ? 'badge-refused' : 'badge-wait');
      $bLabel = $dec==='acceptation' ? 'Accepté'  : ($dec==='refus' ? 'Refusé'        : 'En attente');
    ?>
    <div class="offre-card">
      <div class="offre-card-header">
        <div>
          <div class="offre-card-title"><?= htmlspecialchars($c['fruit']) ?></div>
          <div class="offre-card-sub"><?= htmlspecialchars($c['gouvernorat']) ?> &mdash; <?= htmlspecialchars($c['adresse']) ?></div>
        </div>
        <span class="badge <?= $bClass ?>"><?= $bLabel ?></span>
      </div>
      <div class="offre-card-meta">
        <span class="chip chip-gray"><?= $c['date_debut'] ?> &rarr; <?= $c['date_fin'] ?></span>
        <span class="chip chip-yellow"><?= number_format($c['prix_journee'], 2) ?> DT/jour</span>
      </div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div></body></html>