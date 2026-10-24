<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_ouvrier'])) { header("Location: ../connexion.php"); exit; }
$id=$_SESSION['id_ouvrier'];
$user=$conn->prepare("SELECT * FROM ouvrier WHERE id_ouvrier=?"); $user->execute([$id]); $user=$user->fetch();
$st=$conn->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN decision='acceptation' THEN 1 ELSE 0 END) as acc, SUM(CASE WHEN decision='en cours' THEN 1 ELSE 0 END) as att FROM candidature WHERE id_ouvrier=?");
$st->execute([$id]); $st=$st->fetch();
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mon Espace</title><link rel="stylesheet" href="../css/style.css"></head>
<body><?php include("../config/navbar.php"); ?>
<div class="page-wrap">
  <div class="page-header"><div><p class="breadcrumb">Tableau de bord</p><h2>Bienvenue, <?= htmlspecialchars($user['nom'].' '.$user['prenom']) ?></h2></div></div>
  <div class="stats-row">
    <div class="stat-box"><div class="stat-num"><?= $st['total'] ?></div><div class="stat-label">Candidatures</div></div>
    <div class="stat-box"><div class="stat-num"><?= $st['acc'] ?></div><div class="stat-label">Acceptees</div></div>
    <div class="stat-box"><div class="stat-num"><?= $st['att'] ?></div><div class="stat-label">En attente</div></div>
  </div>
  <div class="menu-grid">
    <a href="../offre/lister.php" class="menu-item"><div class="menu-icon">&#128203;</div><div><div class="menu-label">Voir les offres</div><div class="menu-desc">Postuler en un clic</div></div></a>
    <a href="mescandidatures.php" class="menu-item"><div class="menu-icon">&#128228;</div><div><div class="menu-label">Mes candidatures</div><div class="menu-desc">Suivi des statuts</div></div></a>
    <a href="ouvrierprofil.php" class="menu-item"><div class="menu-icon">&#128100;</div><div><div class="menu-label">Mon profil</div><div class="menu-desc">Voir et modifier</div></div></a>
    <a href="ouvriernotecomm.php" class="menu-item"><div class="menu-icon">&#9733;</div><div><div class="menu-label">Mes evaluations</div><div class="menu-desc">Notes et gains</div></div></a>
    <a href="../index.php?logout=1" class="menu-item" onclick="return confirm('Se deconnecter ?')" style="border-color:#F9CECE;"><div class="menu-icon" style="background:#FEF0F0;color:#C0392B;">&#10005;</div><div><div class="menu-label" style="color:#C0392B;">Deconnexion</div></div></a>
  </div>
</div></body></html>