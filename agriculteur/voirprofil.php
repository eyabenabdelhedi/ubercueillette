<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_agriculteur'])) { header("Location: ../connexion.php"); exit; }
$stmt=$conn->prepare("SELECT * FROM agriculteur WHERE id_agriculteur=?");
$stmt->execute([$_SESSION['id_agriculteur']]); $u=$stmt->fetch();
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mon Profil</title><link rel="stylesheet" href="../css/style.css"></head>
<body><?php include("../config/navbar.php"); ?>
<div class="page-wrap">
  <div class="page-header">
    <div><p class="breadcrumb"><a href="menu_agriculteur.php">Tableau de bord</a> &rsaquo; Profil</p><h2>Mon profil</h2></div>
    <a href="modifierprofil.php" class="btn btn-secondary" style="width:auto;padding:10px 18px;">Modifier</a>
  </div>
  <div class="profil-card">
    <div class="profil-avatar-ph">&#128100;</div>
    <div class="profil-row">
      <div class="profil-field"><label>Nom</label><span><?= htmlspecialchars($u['nom']) ?></span></div>
      <div class="profil-field"><label>Prenom</label><span><?= htmlspecialchars($u['prenom']) ?></span></div>
    </div>
    <div class="profil-row">
      <div class="profil-field"><label>CIN</label><span><?= htmlspecialchars($u['CIN']) ?></span></div>
      <div class="profil-field"><label>Email</label><span><?= htmlspecialchars($u['email']) ?></span></div>
    </div>
    <div class="profil-row">
      <div class="profil-field"><label>Adresse</label><span><?= htmlspecialchars($u['adresse']) ?></span></div>
      <div class="profil-field"><label>Pseudo</label><span><?= htmlspecialchars($u['pseudo']) ?></span></div>
    </div>
  </div>
</div></body></html>
