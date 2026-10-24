<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_agriculteur'])) { header("Location: ../connexion.php"); exit; }
$id=$_SESSION['id_agriculteur']; $message=""; $erreur="";
if (isset($_POST['submit'])) {
    if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) $erreur="Email invalide.";
    else { $conn->prepare("UPDATE agriculteur SET nom=?,email=?,adresse=? WHERE id_agriculteur=?")->execute([$_POST['nom'],trim($_POST['email']),$_POST['adresse'],$id]); $message="Profil mis a jour."; }
}
$stmt=$conn->prepare("SELECT * FROM agriculteur WHERE id_agriculteur=?"); $stmt->execute([$id]); $u=$stmt->fetch();
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Modifier Profil</title><link rel="stylesheet" href="../css/style.css"></head>
<body><?php include("../config/navbar.php"); ?>
<div class="page-wrap">
  <div class="page-header"><div><p class="breadcrumb"><a href="voirprofil.php">Profil</a> &rsaquo; Modifier</p><h2>Modifier mon profil</h2></div></div>
  <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <?php if ($erreur):  ?><div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
  <div class="profil-card">
    <form method="POST">
      <div class="form-group"><label>Nom</label><input class="form-control" type="text" name="nom" value="<?= htmlspecialchars($u['nom']) ?>" required></div>
      <div class="form-group"><label>Email</label><input class="form-control" type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>" required></div>
      <div class="form-group"><label>Adresse</label><input class="form-control" type="text" name="adresse" value="<?= htmlspecialchars($u['adresse']) ?>"></div>
      <div class="btn-row mt-16">
        <button type="submit" name="submit" class="btn btn-yellow" style="flex:2;">Enregistrer</button>
        <a href="voirprofil.php" class="btn btn-ghost" style="flex:1;">Annuler</a>
      </div>
    </form>
  </div>
</div></body></html>
