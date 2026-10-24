<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_ouvrier'])) { header("Location: ../connexion.php"); exit; }
$id=$_SESSION['id_ouvrier'];
$stmt=$conn->prepare("SELECT * FROM ouvrier WHERE id_ouvrier=?"); $stmt->execute([$id]); $u=$stmt->fetch();
$res=$conn->prepare("SELECT AVG(note) as moy, COUNT(note) as nb FROM candidature WHERE id_ouvrier=? AND note IS NOT NULL");
$res->execute([$id]); $res=$res->fetch();
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mon Profil</title><link rel="stylesheet" href="../css/style.css"></head>
<body><?php include("../config/navbar.php"); ?>
<div class="page-wrap">
  <div class="page-header">
    <div><p class="breadcrumb"><a href="menu.php">Tableau de bord</a> &rsaquo; Mon profil</p><h2>Mon profil</h2></div>
    <a href="modifierprofil.php" class="btn btn-secondary" style="width:auto;padding:10px 18px;">Modifier</a>
  </div>
  <div class="profil-card">
    <?php if (!empty($u['photo'])): ?>
      <img src="data:image/jpeg;base64,<?= base64_encode($u['photo']) ?>" class="profil-avatar" alt="photo">
    <?php else: ?>
      <div class="profil-avatar-ph">&#128100;</div>
    <?php endif; ?>
    <div class="profil-row">
      <div class="profil-field"><label>Nom</label><span><?= htmlspecialchars($u['nom']) ?></span></div>
      <div class="profil-field"><label>Prenom</label><span><?= htmlspecialchars($u['prenom']) ?></span></div>
    </div>
    <div class="profil-row">
      <div class="profil-field"><label>CIN</label><span><?= htmlspecialchars($u['CIN']) ?></span></div>
      <div class="profil-field"><label>Email</label><span><?= htmlspecialchars($u['email']) ?></span></div>
    </div>
    <div class="profil-row">
      <div class="profil-field"><label>Pseudo</label><span><?= htmlspecialchars($u['pseudo']) ?></span></div>
      <div class="profil-field"><label>Note moyenne</label><span><?= $res['nb']>0 ? round($res['moy'],1).'/10 ('.$res['nb'].' eval.)' : 'Aucune evaluation' ?></span></div>
    </div>
    <div class="profil-row">
      <div class="profil-field" style="flex:2;"><label>Description</label><span><?= htmlspecialchars($u['description']??'—') ?></span></div>
    </div>
  </div>
</div></body></html>
