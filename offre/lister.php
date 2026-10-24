<?php
session_start(); include("../config/db.php");
$fruits=$conn->query("SELECT * FROM type_fruit ORDER BY libelle")->fetchAll();
$gouvs=$conn->query("SELECT * FROM gouvernorat ORDER BY libelle")->fetchAll();
$ff=isset($_GET['fruit'])?(int)$_GET['fruit']:0;
$fg=isset($_GET['gouv'])?(int)$_GET['gouv']:0;
$fp=isset($_GET['prix'])?$_GET['prix']:'';

$sql="SELECT o.*,tf.libelle AS fruit,g.libelle AS gouvernorat,(SELECT COUNT(*) FROM candidature c WHERE c.id_offre=o.id_offre AND c.decision='accepte') AS nb_acc FROM offre o JOIN type_fruit tf ON o.id_type_fruit=tf.id_type_fruit JOIN gouvernorat g ON o.id_gouvernorat=g.id_gouvernorat WHERE o.date_limite>=CURDATE()";
$params=[];
if ($ff>0){$sql.=" AND o.id_type_fruit=?";$params[]=$ff;}
if ($fg>0){$sql.=" AND o.id_gouvernorat=?";$params[]=$fg;}
$sql.=($fp==='asc')?" ORDER BY o.prix_journee ASC":(($fp==='desc')?" ORDER BY o.prix_journee DESC":" ORDER BY o.date_debut ASC");
$stmt=$conn->prepare($sql); $stmt->execute($params);
$offres=array_filter($stmt->fetchAll(),fn($o)=>$o['nb_acc']<$o['nombre_ouvriers']);
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Offres de recolte</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<?php include("../config/navbar.php"); ?>
<div class="page-wide">
  <div class="page-header">
    <div><p class="breadcrumb">Accueil &rsaquo; Offres</p><h2>Offres de recolte disponibles</h2></div>
    <span class="badge badge-active" style="font-size:13px;padding:6px 14px;"><?= count($offres) ?> offre(s)</span>
  </div>

  <form method="GET">
    <div class="filter-bar">
      <div class="filter-group">
        <label>Fruit</label>
        <select name="fruit" onchange="this.form.submit()">
          <option value="0">— Tous —</option>
          <?php foreach($fruits as $f): ?><option value="<?= $f['id_type_fruit'] ?>" <?= $ff==$f['id_type_fruit']?'selected':'' ?>><?= htmlspecialchars($f['libelle']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="filter-group">
        <label>Gouvernorat</label>
        <select name="gouv" onchange="this.form.submit()">
          <option value="0">— Tous —</option>
          <?php foreach($gouvs as $g): ?><option value="<?= $g['id_gouvernorat'] ?>" <?= $fg==$g['id_gouvernorat']?'selected':'' ?>><?= htmlspecialchars($g['libelle']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="filter-group">
        <label>Prix journalier</label>
        <select name="prix" onchange="this.form.submit()">
          <option value="">— Defaut —</option>
          <option value="asc"  <?= $fp==='asc'?'selected':'' ?>>Croissant</option>
          <option value="desc" <?= $fp==='desc'?'selected':'' ?>>Decroissant</option>
        </select>
      </div>
      <div class="filter-group" style="align-self:flex-end;">
        <a href="lister.php" class="btn btn-ghost btn-sm" style="width:auto;">Reinitialiser</a>
      </div>
    </div>
  </form>

  <?php if (empty($offres)): ?>
    <div class="empty-state"><div class="empty-icon">&#128269;</div><p>Aucune offre ne correspond a vos criteres.</p></div>
  <?php else: ?>
    <?php foreach($offres as $offre): ?>
    <div class="offre-card">
      <div class="offre-card-header">
        <div>
          <div class="offre-card-title"><?= htmlspecialchars($offre['fruit']) ?></div>
          <div class="offre-card-sub"><?= htmlspecialchars($offre['gouvernorat']) ?> &mdash; <?= htmlspecialchars($offre['adresse']) ?></div>
        </div>
        <span class="chip chip-yellow" style="font-size:15px;font-weight:700;"><?= $offre['prix_journee'] ?> DT/j</span>
      </div>
      <div class="offre-card-meta">
        <span class="chip chip-gray"><?= $offre['date_debut'] ?> &rarr; <?= $offre['date_fin'] ?></span>
        <span class="chip chip-blue"><?= $offre['nombre_ouvriers']-$offre['nb_acc'] ?> places restantes</span>
        <span class="chip chip-gray">Limite : <?= $offre['date_limite'] ?></span>
      </div>

      <?php if (isset($_SESSION['id_ouvrier'])): ?>
        <a href="../ouvrier/postuler.php?id=<?= $offre['id_offre'] ?>" class="btn btn-yellow btn-sm" style="width:auto;" onclick="return confirm('Confirmer votre candidature ?')">Postuler</a>
      <?php else: ?>
        <a href="../connexion.php" class="btn btn-ghost btn-sm" style="width:auto;">Se connecter pour postuler</a>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
</body></html>
