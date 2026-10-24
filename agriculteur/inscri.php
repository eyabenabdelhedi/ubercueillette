<?php
session_start();
include("../config/db.php");
$message = ""; $erreur = "";

if (isset($_POST['submit'])) {
    $nom=$_POST['nom']; $prenom=$_POST['prenom']; $CIN=trim($_POST['CIN']);
    $email=trim($_POST['email']); $adresse=trim($_POST['adresse']);
    $pseudo=trim($_POST['pseudo']); $password=$_POST['password'];

    if (!preg_match('/^[0-9]{8}$/', $CIN))
        $erreur = "Le CIN doit contenir exactement 8 chiffres.";
    elseif (!preg_match('/^[A-Za-z]+$/', $pseudo))
        $erreur = "Le pseudo doit contenir uniquement des lettres.";
    elseif (!preg_match('/^[A-Za-z0-9]{7,}[$#]$/', $password))
        $erreur = "Mot de passe invalide (min 8 caracteres, finit par \$ ou #).";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $erreur = "Adresse email invalide.";
    else {
        $check = $conn->prepare("SELECT id_agriculteur FROM agriculteur WHERE pseudo = ?");
        $check->execute([$pseudo]);
        if ($check->fetch()) {
            $erreur = "Ce pseudo est deja utilise.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $conn->prepare("INSERT INTO agriculteur(nom,prenom,CIN,email,adresse,pseudo,password) VALUES(?,?,?,?,?,?,?)")
                 ->execute([$nom,$prenom,$CIN,$email,$adresse,$pseudo,$hash]);
            $message = "Inscription reussie ! Vous pouvez maintenant vous connecter.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Inscription Agriculteur</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include("../config/navbar.php"); ?>
<div class="auth-wrap">
  <div class="auth-box" style="max-width:500px;">
    <h2>Inscription Agriculteur</h2>
    <p class="auth-sub">Creez votre compte pour publier des offres de recolte.</p>

    <?php if ($message): ?>
      <div class="alert alert-success"><?= htmlspecialchars($message) ?>
        <a href="../connexion.php" style="color:inherit;font-weight:600;"> Connexion</a>
      </div>
    <?php else: ?>
    <?php if ($erreur): ?>
      <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form method="POST" onsubmit="return verifierFormulaire()">
      <div style="display:flex;gap:12px;">
        <div class="form-group" style="flex:1;">
          <label>Nom</label>
          <input class="form-control" type="text" name="nom" value="<?= htmlspecialchars($_POST['nom']??'') ?>" required>
        </div>
        <div class="form-group" style="flex:1;">
          <label>Prenom</label>
          <input class="form-control" type="text" name="prenom" value="<?= htmlspecialchars($_POST['prenom']??'') ?>" required>
        </div>
      </div>
      <div class="form-group">
        <label>CIN (8 chiffres)</label>
        <input class="form-control" type="text" id="CIN" name="CIN" maxlength="8" value="<?= htmlspecialchars($_POST['CIN']??'') ?>" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($_POST['email']??'') ?>" required>
      </div>
      <div class="form-group">
        <label>Adresse</label>
        <input class="form-control" type="text" name="adresse" value="<?= htmlspecialchars($_POST['adresse']??'') ?>">
      </div>
      <div class="form-group">
        <label>Pseudo (lettres uniquement)</label>
        <input class="form-control" type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($_POST['pseudo']??'') ?>" required>
      </div>
      <div class="form-group">
        <label>Mot de passe (min 8 caracteres, finit par $ ou #)</label>
        <input class="form-control" type="password" id="password" name="password" required>
      </div>
      <button type="submit" name="submit" class="btn btn-yellow">Creer mon compte</button>
    </form>
    <?php endif; ?>

    <a href="../connexion.php" class="btn btn-ghost mt-8">Deja inscrit ? Se connecter</a>
  </div>
</div>
<script src="../java/script.js"></script>
</body>
</html>
