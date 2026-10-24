<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_agriculteur'])) { header("Location: ../connexion.php"); exit; }
$ida = $_SESSION['id_agriculteur'];
$id_offre_filtre = isset($_GET['id_offre']) ? (int)$_GET['id_offre'] : null;

$nombre_max = 0; $nbAcceptes = 0;
if ($id_offre_filtre) {
    $of = $conn->prepare("SELECT nombre_ouvriers FROM offre WHERE id_offre=? AND id_agriculteur=?");
    $of->execute([$id_offre_filtre, $ida]); $of = $of->fetch();
    if (!$of) die("Acces refuse.");
    $nombre_max = $of['nombre_ouvriers'];
    $cnt = $conn->prepare("SELECT COUNT(*) as nb FROM candidature WHERE id_offre=? AND decision='acceptation'");
    $cnt->execute([$id_offre_filtre]); $nbAcceptes = $cnt->fetch()['nb'];
}

$sql = "SELECT c.*, o.nom, o.prenom, o.photo,
        (SELECT AVG(c2.note) FROM candidature c2 WHERE c2.id_ouvrier=o.id_ouvrier AND c2.note IS NOT NULL) AS moy,
        offre.adresse AS adr, offre.id_offre, offre.date_limite, offre.date_fin
        FROM candidature c
        JOIN offre ON c.id_offre=offre.id_offre
        JOIN ouvrier o ON c.id_ouvrier=o.id_ouvrier
        WHERE offre.id_agriculteur=?";
$params = [$ida];
if ($id_offre_filtre) { $sql .= " AND offre.id_offre=?"; $params[] = $id_offre_filtre; }
$sql .= " ORDER BY c.date_candidature DESC";
$stmt = $conn->prepare($sql); $stmt->execute($params);
$candidatures = $stmt->fetchAll();

$mesOffres = $conn->prepare("SELECT id_offre,adresse,date_debut FROM offre WHERE id_agriculteur=? ORDER BY date_debut DESC");
$mesOffres->execute([$ida]); $mesOffres = $mesOffres->fetchAll();

$today = date('Y-m-d');
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Candidatures</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<?php include("../config/navbar.php"); ?>
<div class="page-wide">
  <div class="page-header">
    <div>
      <p class="breadcrumb"><a href="menu_agriculteur.php">Tableau de bord</a> &rsaquo; Candidatures</p>
      <h2>Gestion des candidatures</h2>
    </div>
  </div>

  <form method="GET">
    <div class="filter-bar">
      <div class="filter-group" style="flex:2;">
        <label>Filtrer par offre</label>
        <select name="id_offre" onchange="this.form.submit()">
          <option value="">— Toutes mes offres —</option>
          <?php foreach($mesOffres as $of): ?>
            <option value="<?= $of['id_offre'] ?>" <?= $id_offre_filtre==$of['id_offre']?'selected':'' ?>>
              <?= htmlspecialchars($of['adresse']) ?> (<?= $of['date_debut'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php if ($id_offre_filtre && $nombre_max): ?>
        <div style="align-self:flex-end;padding-bottom:4px;">
          <span class="badge <?= $nbAcceptes>=$nombre_max?'badge-closed':'badge-active' ?>"><?= $nbAcceptes ?>/<?= $nombre_max ?> places</span>
        </div>
      <?php endif; ?>
    </div>
  </form>

  <?php if (empty($candidatures)): ?>
    <div class="empty-state"><div class="empty-icon">&#128101;</div><p>Aucune candidature pour le moment.</p></div>
  <?php else: ?>
    <?php foreach($candidatures as $c):
      $dec         = $c['decision'];
      $date_limite = $c['date_limite'];
      $date_fin    = $c['date_fin'];
      $bClass = $dec==='acceptation' ? 'badge-ok'  : ($dec==='refus' ? 'badge-refused' : 'badge-wait');
      $bLabel = $dec==='acceptation' ? 'Accepté'   : ($dec==='refus' ? 'Refusé'        : 'En attente');
    ?>
    <div class="cand-card">
      <?php if (!empty($c['photo'])): ?>
        <img src="data:image/jpeg;base64,<?= base64_encode($c['photo']) ?>" class="cand-avatar" alt="photo">
      <?php else: ?>
        <div class="cand-avatar-ph">&#128100;</div>
      <?php endif; ?>
      <div class="cand-info">
        <div class="cand-name"><?= htmlspecialchars($c['nom'].' '.$c['prenom']) ?></div>
        <div class="cand-meta">
          Offre : <?= htmlspecialchars($c['adr']) ?> &nbsp;&middot;&nbsp;
          Note moy. : <?= $c['moy']!==null ? round($c['moy'],1).'/10' : 'Non évalué' ?>
        </div>
        <span class="badge <?= $bClass ?> mt-8"><?= $bLabel ?></span>
      </div>
      <div class="cand-actions">

        <?php if ($today <= $date_limite): ?>
          <?php if ($dec !== 'acceptation' && $nbAcceptes < $nombre_max): ?>
            <form method="POST" action="decision.php">
              <input type="hidden" name="id_candidature" value="<?= $c['id_candidature'] ?>">
              <input type="hidden" name="decision" value="acceptation">
              <input type="hidden" name="id_offre" value="<?= $c['id_offre'] ?>">
              <button class="btn btn-secondary btn-sm" onclick="return confirm('Accepter ce candidat ?')">Accepter</button>
            </form>
          <?php endif; ?>
          <?php if ($dec !== 'refus'): ?>
            <form method="POST" action="decision.php">
              <input type="hidden" name="id_candidature" value="<?= $c['id_candidature'] ?>">
              <input type="hidden" name="decision" value="refus">
              <input type="hidden" name="id_offre" value="<?= $c['id_offre'] ?>">
              <button class="btn btn-danger btn-sm" onclick="return confirm('Refuser ce candidat ?')">Refuser</button>
            </form>
          <?php endif; ?>
        <?php else: ?>
          <span style="font-size:12px; color:#e65100;">Date limite dépassée</span>
        <?php endif; ?>

        <?php if ($dec === 'acceptation' && $today > $date_fin && $today <= $date_limite): ?>
          <a href="noter.php?id=<?= $c['id_candidature'] ?>" class="btn btn-ghost btn-sm">Évaluer</a>
        <?php elseif ($dec === 'acceptation' && $today <= $date_fin && $today <= $date_limite): ?>
          <span style="font-size:12px; color:#1565c0;">Évaluation après le <?= $date_fin ?></span>
        <?php endif; ?>

      </div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
</body></html>