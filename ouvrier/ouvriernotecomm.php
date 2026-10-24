<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_ouvrier'])) { header("Location: ../connexion.php"); exit; }
$id=$_SESSION['id_ouvrier'];
$stmt=$conn->prepare("SELECT c.note,c.commentaire,c.remuneration,o.adresse,o.date_debut,o.date_fin,tf.libelle AS fruit,g.libelle AS gouvernorat FROM candidature c JOIN offre o ON c.id_offre=o.id_offre JOIN type_fruit tf ON o.id_type_fruit=tf.id_type_fruit JOIN gouvernorat g ON o.id_gouvernorat=g.id_gouvernorat WHERE c.id_ouvrier=? AND c.note IS NOT NULL ORDER BY o.date_fin DESC");
$stmt->execute([$id]); $chantiers=$stmt->fetchAll();
$gains=$conn->prepare("SELECT SUM(remuneration) as total FROM candidature WHERE id_ouvrier=? AND remuneration IS NOT NULL");
$gains->execute([$id]); $gains=$gains->fetch();
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mes evaluations</title><link rel="stylesheet" href="../css/style.css"></head>
<body><?php include("../config/navbar.php"); ?>
<div class="page-wide">
  <div class="page-header">
    <div><p class="breadcrumb"><a href="menu.php">Tableau de bord</a> &rsaquo; Evaluations</p><h2>Mes chantiers evalues</h2></div>
    <?php if ($gains['total']): ?>
      <div class="stat-box" style="text-align:center;min-width:0;"><div class="stat-num"><?= number_format($gains['total'],0) ?> DT</div><div class="stat-label">Total gains</div></div>
    <?php endif; ?>
  </div>
  <?php if (empty($chantiers)): ?>
    <div class="empty-state"><div class="empty-icon">&#9733;</div><p>Aucun chantier evalue pour le moment.</p></div>
  <?php else: ?>
    <?php foreach($chantiers as $c): ?>
    <div class="offre-card">
      <div class="offre-card-header">
        <div>
          <div class="offre-card-title"><?= htmlspecialchars($c['fruit']) ?></div>
          <div class="offre-card-sub"><?= htmlspecialchars($c['gouvernorat']) ?> &mdash; <?= htmlspecialchars($c['adresse']) ?></div>
        </div>
        <span class="badge badge-ok">Note : <?= $c['note'] ?>/10</span>
      </div>
      <div class="offre-card-meta">
        <span class="chip chip-gray"><?= $c['date_debut'] ?> &rarr; <?= $c['date_fin'] ?></span>
        <?php if ($c['remuneration']): ?><span class="chip chip-yellow"><?= number_format($c['remuneration'],2) ?> DT</span><?php endif; ?>
      </div>
      <?php if ($c['commentaire']): ?>
        <p style="font-size:13px;color:#888;margin-top:8px;font-style:italic;">"<?= htmlspecialchars($c['commentaire']) ?>"</p>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div></body></html>
