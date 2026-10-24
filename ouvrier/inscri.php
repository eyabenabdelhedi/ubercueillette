<?php
session_start(); include("../config/db.php");
$message=""; $erreur="";
if (isset($_POST['submit'])) {
    $nom=$_POST['nom']; $prenom=$_POST['prenom']; $CIN=trim($_POST['CIN']);
    $email=trim($_POST['email']); $desc=trim($_POST['description']);
    $pseudo=trim($_POST['pseudo']); $password=$_POST['password'];
    if (!preg_match('/^[0-9]{8}$/', $CIN)) $erreur="Le CIN doit contenir exactement 8 chiffres.";
    elseif (!preg_match('/^[A-Za-z]+$/', $pseudo)) $erreur="Le pseudo doit contenir uniquement des lettres.";
    elseif (!preg_match('/^[A-Za-z0-9]{7,}[$#]$/', $password)) $erreur="Mot de passe invalide (min 8 caracteres, finit par \$ ou #).";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreur="Email invalide.";
    else {
        $check=$conn->prepare("SELECT id_ouvrier FROM ouvrier WHERE pseudo=?"); $check->execute([$pseudo]);
        if ($check->fetch()) $erreur="Ce pseudo est deja utilise.";
        else {
            $photo=null;
            if (isset($_FILES['photo'])&&$_FILES['photo']['error']===UPLOAD_ERR_OK) {
                if (in_array($_FILES['photo']['type'],['image/jpeg','image/png','image/gif']))
                    $photo=file_get_contents($_FILES['photo']['tmp_name']);
                else $erreur="Format photo invalide (JPG/PNG/GIF).";
            }
            if (!$erreur) {
                $hash=password_hash($password, PASSWORD_DEFAULT);
                $conn->prepare("INSERT INTO ouvrier(nom,prenom,CIN,email,photo,description,pseudo,password) VALUES(?,?,?,?,?,?,?,?)")
                     ->execute([$nom,$prenom,$CIN,$email,$photo,$desc,$pseudo,$hash]);
                $message="Inscription reussie !";
            }
        }
    }
}
?>
<!DOCTYPE html><html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Inscription Ouvrier</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<?php include("../config/navbar.php"); ?>
<div class="auth-wrap">
  <div class="auth-box" style="max-width:500px;">
    <h2>Inscription Ouvrier</h2>
    <p class="auth-sub">Creez votre profil pour acceder aux offres de recolte.</p>
    <?php if ($message): ?>
      <div class="alert alert-success"><?= htmlspecialchars($message) ?>
        <a href="../connexion.php" style="color:inherit;font-weight:600;"> Connexion</a></div>
    <?php else: ?>
    <?php if ($erreur): ?><div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data" onsubmit="return verifierFormulaire()">
      <div style="display:flex;gap:12px;">
        <div class="form-group" style="flex:1;"><label>Nom</label><input class="form-control" type="text" name="nom" value="<?= htmlspecialchars($_POST['nom']??'') ?>" required></div>
        <div class="form-group" style="flex:1;"><label>Prenom</label><input class="form-control" type="text" name="prenom" value="<?= htmlspecialchars($_POST['prenom']??'') ?>" required></div>
      </div>
      <div class="form-group"><label>CIN (8 chiffres)</label><input class="form-control" type="text" id="CIN" name="CIN" maxlength="8" value="<?= htmlspecialchars($_POST['CIN']??'') ?>" required></div>
      <div class="form-group"><label>Email</label><input class="form-control" type="email" name="email" value="<?= htmlspecialchars($_POST['email']??'') ?>" required></div>
      <div class="form-group"><label>Photo d'identite (optionnel)</label><input class="form-control" type="file" name="photo" accept="image/*"></div>
      <div class="form-group"><label>Description (experience, niveau...)</label><textarea class="form-control" name="description" placeholder="Parlez de votre experience..."><?= htmlspecialchars($_POST['description']??'') ?></textarea></div>
      <div class="form-group"><label>Pseudo (lettres uniquement)</label><input class="form-control" type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($_POST['pseudo']??'') ?>" required></div>
      <div class="form-group"><label>Mot de passe (min 8 caracteres, finit par $ ou #)</label><input class="form-control" type="password" id="password" name="password" required></div>
      <button type="submit" name="submit" class="btn btn-yellow">Creer mon compte</button>
    </form>
    <?php endif; ?>
    <a href="../connexion.php" class="btn btn-ghost mt-8">Deja inscrit ? Se connecter</a>
  </div>
</div>
<script src="../java/script.js"></script>
</body></html>
