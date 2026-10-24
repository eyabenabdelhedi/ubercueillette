<?php
session_start();
// Rediriger si deja connecte
if (isset($_SESSION['id_agriculteur'])) { header("Location: agriculteur/menu_agriculteur.php"); exit; }
if (isset($_SESSION['id_ouvrier']))     { header("Location: ouvrier/menu.php"); exit; }

include("config/db.php");
$message = "";

if (isset($_POST['login'])) {
    $pseudo   = trim($_POST['pseudo']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    if ($role === 'agriculteur') {
        $stmt = $conn->prepare("SELECT * FROM agriculteur WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id_agriculteur'] = $user['id_agriculteur'];
            header("Location: agriculteur/menu_agriculteur.php"); exit;
        } else { $message = "Pseudo ou mot de passe incorrect."; }

    } elseif ($role === 'ouvrier') {
        $stmt = $conn->prepare("SELECT * FROM ouvrier WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id_ouvrier'] = $user['id_ouvrier'];
            header("Location: ouvrier/menu.php"); exit;
        } else { $message = "Pseudo ou mot de passe incorrect."; }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connexion — UberCueillette</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include("config/navbar.php"); ?>

<div class="auth-wrap">
  <div class="auth-box">
    <h2>Connexion</h2>
    <p class="auth-sub">Connectez-vous a votre espace agriculteur ou ouvrier.</p>

    <?php if ($message): ?>
      <div class="alert alert-error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">

      <div class="form-group">
        <label>Vous etes</label>
        <select class="form-control" name="role" required>
          <option value="agriculteur" <?= (isset($_POST['role']) && $_POST['role']==='agriculteur') ? 'selected' : '' ?>>Agriculteur</option>
          <option value="ouvrier"     <?= (isset($_POST['role']) && $_POST['role']==='ouvrier')     ? 'selected' : '' ?>>Ouvrier</option>
        </select>
      </div>

      <div class="form-group">
        <label>Pseudo</label>
        <input class="form-control" type="text" name="pseudo" value="<?= htmlspecialchars($_POST['pseudo'] ?? '') ?>" placeholder="Votre pseudo" required autofocus>
      </div>

      <div class="form-group">
        <label>Mot de passe</label>
        <input class="form-control" type="password" name="password" placeholder="••••••••" required>
      </div>

      <button type="submit" name="login" class="btn btn-yellow">Se connecter</button>
    </form>

    <div class="divider"></div>

    <p class="text-center text-muted">Pas encore de compte ?</p>
    <div class="btn-row mt-8">
      <a href="agriculteur/inscri.php" class="btn btn-secondary" style="flex:1;">Agriculteur</a>
      <a href="ouvrier/inscri.php"     class="btn btn-secondary" style="flex:1;">Ouvrier</a>
    </div>
  </div>
</div>

</body>
</html>
