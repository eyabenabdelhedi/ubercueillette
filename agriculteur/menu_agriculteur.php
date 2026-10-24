<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_agriculteur'])) { header("Location: ../connexion.php"); exit; }
$id = $_SESSION['id_agriculteur'];
$stmt = $conn->prepare("SELECT * FROM agriculteur WHERE id_agriculteur=?");
$stmt->execute([$id]); $user = $stmt->fetch();
$st = $conn->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN date_limite>=CURDATE() THEN 1 ELSE 0 END) as actives FROM offre WHERE id_agriculteur=?");
$st->execute([$id]); $st = $st->fetch();
$cand = $conn->prepare("SELECT COUNT(*) as nb FROM candidature c JOIN offre o ON c.id_offre=o.id_offre WHERE o.id_agriculteur=? AND c.decision='en_attente'");
$cand->execute([$id]); $cand = $cand->fetch();
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mon Espace</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<?php include("../config/navbar.php"); ?>
<div class="page-wrap">
  <div class="page-header">
    <div>
      <p class="breadcrumb">Tableau de bord</p>
      <h2>Bienvenue, <?= htmlspecialchars($user['nom'].' '.$user['prenom']) ?></h2>
    </div>
  </div>

  <div class="stats-row">
    <div class="stat-box"><div class="stat-num"><?= $st['total'] ?></div><div class="stat-label">Offres publiees</div></div>
    <div class="stat-box"><div class="stat-num"><?= $st['actives'] ?></div><div class="stat-label">Offres actives</div></div>
    <div class="stat-box"><div class="stat-num"><?= $cand['nb'] ?></div><div class="stat-label">En attente</div></div>
  </div>

  <div class="menu-grid">
    <a href="voirprofil.php" class="menu-item">
      <div class="menu-icon">&#128100;</div>
      <div><div class="menu-label">Mon profil</div><div class="menu-desc">Voir et modifier</div></div>
    </a>
    <a href="proposeroffre.php" class="menu-item">
      <div class="menu-icon">&#43;</div>
      <div><div class="menu-label">Ajouter une offre</div><div class="menu-desc">Nouveau chantier</div></div>
    </a>
    <a href="voirmesoffres.php" class="menu-item">
      <div class="menu-icon">&#128203;</div>
      <div><div class="menu-label">Mes offres</div><div class="menu-desc">Gerer et supprimer</div></div>
    </a>
    <a href="voircandidatures.php" class="menu-item">
      <div class="menu-icon">&#128101;</div>
      <div><div class="menu-label">Candidatures</div><div class="menu-desc">Accepter / Refuser</div></div>
    </a>
    <a href="../index.php?logout=1" class="menu-item" onclick="return confirm('Se deconnecter ?')" style="border-color:#F9CECE;">
      <div class="menu-icon" style="background:#FEF0F0;color:#C0392B;">&#10005;</div>
      <div><div class="menu-label" style="color:#C0392B;">Deconnexion</div></div>
    </a>
  </div>
</div>
</body></html>
