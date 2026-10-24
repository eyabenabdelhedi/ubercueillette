<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_ouvrier'])) { header("Location: ../connexion.php"); exit; }
$id=$_SESSION['id_ouvrier']; $message=""; $erreur="";
if (isset($_POST['submit'])) {
    if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) $erreur="Email invalide.";
    else {
        if (isset($_FILES['photo'])&&$_FILES['photo']['error']===UPLOAD_ERR_OK) {
            if (in_array($_FILES['photo']['type'],['image/jpeg','image/png','image/gif'])) {
                $photo=file_get_contents($_FILES['photo']['tmp_name']);
                $conn->prepare("UPDATE ouvrier SET nom=?,email=?,description=?,photo=? WHERE id_ouvrier=?")->execute([$_POST['nom'],trim($_POST['email']),$_POST['description'],$photo,$id]);
            } else $erreur="Format photo invalide.";
        } else { $conn->prepare("UPDATE ouvrier SET nom=?,email=?,description=? WHERE id_ouvrier=?")->execute([$_POST['nom'],trim($_POST['email']),$_POST['description'],$id]); }
        if (!$erreur) $message="Profil mis a jour.";
    }
}
$stmt=$conn->prepare("SELECT * FROM ouvrier WHERE id_ouvrier=?"); $stmt->execute([$id]); $u=$stmt->fetch();
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Modifier Profil</title><link rel="stylesheet" href="../css/style.css"></head>
<body><?php include("../config/navbar.php"); ?>
<div class="page-wrap">
  <div class="page-header"><div><p class="breadcrumb"><a href="ouvrierprofil.php">Profil</a> &rsaquo; Modifier</p><h2>Modifier mon profil</h2></div></div>
  <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <?php if ($erreur):  ?><div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
  <div class="profil-card">
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group"><label>Nom</label><input class="form-control" type="text" name="nom" value="<?= htmlspecialchars($u['nom']) ?>" required></div>
      <div class="form-group"><label>Email</label><input class="form-control" type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>" required></div>
      <div class="form-group"><label>Description</label><textarea class="form-control" name="description"><?= htmlspecialchars($u['description']??'') ?></textarea></div>
      <div class="form-group"><label>Changer la photo</label><input class="form-control" type="file" name="photo" accept="image/*"></div>
      <div class="btn-row mt-16">
        <button type="submit" name="submit" class="btn btn-yellow" style="flex:2;">Enregistrer</button>
        <a href="ouvrierprofil.php" class="btn btn-ghost" style="flex:1;">Annuler</a>
      </div>
    </form>
  </div>
</div></body></html>
