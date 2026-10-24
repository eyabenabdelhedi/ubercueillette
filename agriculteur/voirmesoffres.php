<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_agriculteur'])) { header("Location: ../connexion.php"); exit; }
$id = $_SESSION['id_agriculteur'];
$msg = "";

if (isset($_POST['supprimer'], $_POST['id_offre'])) {
    $io = (int)$_POST['id_offre'];
    $nb = $conn->prepare("SELECT COUNT(*) as nb FROM candidature WHERE id_offre=?");
    $nb->execute([$io]); $nb = $nb->fetch()['nb'];
    if ($nb == 0) { $conn->prepare("DELETE FROM offre WHERE id_offre=? AND id_agriculteur=?")->execute([$io,$id]); $msg="success:Offre supprimee."; }
    else $msg = "error:Impossible : des candidatures existent pour cette offre.";
}

$stmt = $conn->prepare("SELECT o.*,tf.libelle AS fruit,g.libelle AS gouvernorat,(SELECT COUNT(*) FROM candidature c WHERE c.id_offre=o.id_offre AND c.decision='accepte') AS nb_acc FROM offre o JOIN type_fruit tf ON o.id_type_fruit=tf.id_type_fruit JOIN gouvernorat g ON o.id_gouvernorat=g.id_gouvernorat WHERE o.id_agriculteur=? ORDER BY o.date_debut DESC");
$stmt->execute([$id]); $offres = $stmt->fetchAll();
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mes offres</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<?php include("../config/navbar.php"); ?>
<div class="page-wide">
  <div class="page-header">
    <div>
      <p class="breadcrumb"><a href="menu_agriculteur.php">Tableau de bord</a> &rsaquo; Mes offres</p>
      <h2>Mes offres de recolte</h2>
    </div>
    <a href="proposeroffre.php" class="btn btn-yellow" style="width:auto;padding:10px 20px;">+ Nouvelle offre</a>
  </div>

  <?php if ($msg): [$t,$tx]=explode(':',$msg,2); ?>
    <div class="alert alert-<?= $t==='success'?'success':'error' ?>"><?= htmlspecialchars($tx) ?></div>
  <?php endif; ?>

  <?php if (empty($offres)): ?>
    <div class="empty-state">
      <div class="empty-icon">&#128203;</div>
      <p>Aucune offre publiee pour le moment.</p>
      <a href="proposeroffre.php" class="btn btn-yellow mt-16" style="width:auto;padding:12px 24px;">Publier ma premiere offre</a>
    </div>
  <?php else: ?>
    <?php foreach($offres as $o):
      $clos = ($o['date_limite'] < date('Y-m-d')) || ($o['nb_acc'] >= $o['nombre_ouvriers']);
    ?>
    <div class="offre-card">
      <div class="offre-card-header">
        <div>
          <div class="offre-card-title"><?= htmlspecialchars($o['fruit']) ?></div>
          <div class="offre-card-sub"><?= htmlspecialchars($o['gouvernorat']) ?> &mdash; <?= htmlspecialchars($o['adresse']) ?></div>
        </div>
        <span class="badge <?= $clos?'badge-closed':'badge-active' ?>"><?= $clos?'Cloturee':'Active' ?></span>
      </div>
      <div class="offre-card-meta">
        <span class="chip chip-gray"><?= $o['date_debut'] ?> &rarr; <?= $o['date_fin'] ?></span>
        <span class="chip chip-yellow"><?= $o['prix_journee'] ?> DT/jour</span>
        <span class="chip chip-blue"><?= $o['nb_acc'] ?>/<?= $o['nombre_ouvriers'] ?> ouvriers</span>
        <span class="chip chip-gray">Limite : <?= $o['date_limite'] ?></span>
      </div>
      <div class="btn-row">
        <a href="voircandidatures.php?id_offre=<?= $o['id_offre'] ?>" class="btn btn-secondary btn-sm">Candidatures</a>
        <?php if ($o['nb_acc'] == 0): ?>
          <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette offre ?')">
            <input type="hidden" name="id_offre" value="<?= $o['id_offre'] ?>">
            <button type="submit" name="supprimer" class="btn btn-danger btn-sm">Supprimer</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
</body></html>
