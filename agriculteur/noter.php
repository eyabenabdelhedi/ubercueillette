<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_agriculteur'])) { header("Location: ../connexion.php"); exit; }
$id_cand = (int)($_GET['id'] ?? 0);

$check = $conn->prepare("SELECT c.*, o.nom, o.prenom, of2.date_fin
        FROM candidature c
        JOIN offre of2 ON c.id_offre = of2.id_offre
        JOIN ouvrier o ON c.id_ouvrier = o.id_ouvrier
        WHERE c.id_candidature = ?
          AND of2.id_agriculteur = ?
          AND c.decision = 'acceptation'");
$check->execute([$id_cand, $_SESSION['id_agriculteur']]);
$cand = $check->fetch();

if (!$cand) die("Accès refusé.");

// Évaluation uniquement après la fin du chantier
if (date('Y-m-d') < $cand['date_fin']) {
    die("Évaluation disponible uniquement après la fin du chantier (après le " . $cand['date_fin'] . ").");
}

$message = ""; $erreur = "";
if (isset($_POST['submit'])) {
    $note = (int)$_POST['note'];
    $rem  = (float)$_POST['remuneration'];
    if ($note < 0 || $note > 10) $erreur = "La note doit être entre 0 et 10.";
    elseif ($rem < 0)             $erreur = "La rémunération ne peut pas être négative.";
    else {
        $conn->prepare("UPDATE candidature SET note=?, commentaire=?, remuneration=? WHERE id_candidature=?")
             ->execute([$note, trim($_POST['commentaire']), $rem, $id_cand]);
        $message = "Évaluation enregistrée !";
    }
}
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Évaluer</title><link rel="stylesheet" href="../css/style.css"></head>
<body><?php include("../config/navbar.php"); ?>
<div class="page-wrap">
  <div class="page-header">
    <div>
      <p class="breadcrumb"><a href="voircandidatures.php">Candidatures</a> &rsaquo; Évaluer</p>
      <h2>Évaluer <?= htmlspecialchars($cand['nom'].' '.$cand['prenom']) ?></h2>
    </div>
  </div>
  <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <?php if ($erreur):  ?><div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
  <div class="profil-card">
    <form method="POST">
      <div class="form-group">
        <label>Note (0 à 10)</label>
        <input class="form-control" type="number" name="note" min="0" max="10"
               value="<?= htmlspecialchars($cand['note'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Commentaire</label>
        <textarea class="form-control" name="commentaire"><?= htmlspecialchars($cand['commentaire'] ?? '') ?></textarea>
      </div>
      <div class="form-group">
        <label>Rémunération (DT)</label>
        <input class="form-control" type="number" name="remuneration" min="0" step="0.5"
               value="<?= htmlspecialchars($cand['remuneration'] ?? '') ?>">
      </div>
      <div class="btn-row mt-16">
        <button type="submit" name="submit" class="btn btn-yellow" style="flex:2;">Enregistrer</button>
        <a href="voircandidatures.php" class="btn btn-ghost" style="flex:1;">Retour</a>
      </div>
    </form>
  </div>
</div></body></html>