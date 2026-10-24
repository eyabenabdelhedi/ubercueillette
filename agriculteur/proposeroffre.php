<?php
session_start(); include("../config/db.php");
if (!isset($_SESSION['id_agriculteur'])) { header("Location: ../connexion.php"); exit; }
$fruits = $conn->query("SELECT * FROM type_fruit ORDER BY libelle")->fetchAll();
$gouvs  = $conn->query("SELECT * FROM gouvernorat ORDER BY libelle")->fetchAll();
$message = ""; $erreur = "";

if (isset($_POST['submit'])) {
    if (!empty(trim($_POST['nouveau_fruit']))) {
        $conn->prepare("INSERT INTO type_fruit(libelle) VALUES(?)")->execute([trim($_POST['nouveau_fruit'])]);
        $fruit_id = $conn->lastInsertId();
    } else { $fruit_id = (int)$_POST['fruit']; }

    if (!empty(trim($_POST['nouveau_gouv']))) {
        $conn->prepare("INSERT INTO gouvernorat(libelle) VALUES(?)")->execute([trim($_POST['nouveau_gouv'])]);
        $gouv_id = $conn->lastInsertId();
    } else { $gouv_id = (int)$_POST['gouvernorat']; }

    $dd=$_POST['date_debut']; $df=$_POST['date_fin']; $dl=$_POST['date_limite'];
    $nb=(int)$_POST['nb']; $prix=(float)$_POST['prix'];

    if ($df <= $dd) $erreur = "La date de fin doit etre posterieure a la date de debut.";
    elseif ($dl >= $dd) $erreur = "La date limite doit etre anterieure a la date de debut.";
    elseif ($nb <= 0)   $erreur = "Le nombre d'ouvriers doit etre > 0.";
    elseif ($prix <= 0) $erreur = "Le prix journalier doit etre > 0.";
    else {
        $conn->prepare("INSERT INTO offre(id_type_fruit,id_gouvernorat,adresse,date_debut,date_fin,nombre_ouvriers,prix_journee,date_limite,id_agriculteur) VALUES(?,?,?,?,?,?,?,?,?)")
             ->execute([$fruit_id,$gouv_id,trim($_POST['adresse']),$dd,$df,$nb,$prix,$dl,$_SESSION['id_agriculteur']]);
        $message = "Offre publiee avec succes !";
    }
}
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Nouvelle offre</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<?php include("../config/navbar.php"); ?>
<div class="page-wrap">
  <div class="page-header">
    <div>
      <p class="breadcrumb"><a href="menu_agriculteur.php">Tableau de bord</a> &rsaquo; Nouvelle offre</p>
      <h2>Publier une offre de recolte</h2>
    </div>
  </div>

  <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?> <a href="voirmesoffres.php" style="color:inherit;font-weight:600;">Voir mes offres</a></div><?php endif; ?>
  <?php if ($erreur):  ?><div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>

  <div class="profil-card">
    <form method="POST" onsubmit="return verifierDatesOffre()">

      <div style="display:flex;gap:14px;flex-wrap:wrap;">
        <div class="form-group" style="flex:1;min-width:180px;">
          <label>Type de fruit</label>
          <select class="form-control" name="fruit">
            <?php foreach($fruits as $f): ?><option value="<?= $f['id_type_fruit'] ?>"><?= htmlspecialchars($f['libelle']) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="form-group" style="flex:1;min-width:180px;">
          <label>Autre:</label>
          <input class="form-control" type="text" name="nouveau_fruit" placeholder="Ex: Banane">
        </div>
      </div>

      <div style="display:flex;gap:14px;flex-wrap:wrap;">
        <div class="form-group" style="flex:1;min-width:180px;">
          <label>Gouvernorat</label>
          <select class="form-control" name="gouvernorat">
            <?php foreach($gouvs as $g): ?><option value="<?= $g['id_gouvernorat'] ?>"><?= htmlspecialchars($g['libelle']) ?></option><?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label>Adresse du site de recolte</label>
        <input class="form-control" type="text" name="adresse" placeholder="Adresse complete" required>
      </div>

      <div style="display:flex;gap:14px;flex-wrap:wrap;">
        <div class="form-group" style="flex:1;">
          <label>Date debut</label>
          <input class="form-control" type="date" name="date_debut" required>
        </div>
        <div class="form-group" style="flex:1;">
          <label>Date fin</label>
          <input class="form-control" type="date" name="date_fin" required>
        </div>
        <div class="form-group" style="flex:1;">
          <label>Date limite postulation</label>
          <input class="form-control" type="date" name="date_limite" required>
        </div>
      </div>

      <div style="display:flex;gap:14px;flex-wrap:wrap;">
        <div class="form-group" style="flex:1;">
          <label>Nombre d'ouvriers</label>
          <input class="form-control" type="number" name="nb" min="1" placeholder="Ex: 10" required>
        </div>
        <div class="form-group" style="flex:1;">
          <label>Prix journalier (DT)</label>
          <input class="form-control" type="number" name="prix" min="0" step="0.5" placeholder="Ex: 25" required>
        </div>
      </div>

      <div class="btn-row mt-16">
        <button type="submit" name="submit" class="btn btn-yellow" style="flex:2;">Publier l'offre</button>
        <a href="menu_agriculteur.php" class="btn btn-ghost" style="flex:1;">Annuler</a>
      </div>
    </form>
  </div>
</div>
<script src="../java/script.js"></script>
</body></html>
