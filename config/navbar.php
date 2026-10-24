<?php
$is_agriculteur = isset($_SESSION['id_agriculteur']);
$is_ouvrier     = isset($_SESSION['id_ouvrier']);
$projectRoot = realpath(__DIR__ . '/..');
$scriptDir   = dirname(realpath($_SERVER['SCRIPT_FILENAME']));
$rel = '';
$tmp = $scriptDir;
while (realpath($tmp) !== $projectRoot && strlen($tmp) > 3) {
    $rel .= '../';
    $tmp = dirname($tmp);
}
if (realpath($tmp) !== $projectRoot) $rel = '';
?>
<nav class="navbar">
  <a class="navbar-brand" href="<?= $rel ?>index.php">Uber<span>Cueillette</span></a>

  <?php if ($is_agriculteur): ?>
    <a href="<?= $rel ?>agriculteur/menu_agriculteur.php" class="nav-link">Mon espace</a>
    <a href="<?= $rel ?>offre/lister.php" class="nav-link">Offres</a>
    <a href="<?= $rel ?>index.php?logout=1" class="nav-btn nav-btn-outline" onclick="return confirm('Se deconnecter ?')">Deconnexion</a>

  <?php elseif ($is_ouvrier): ?>
    <a href="<?= $rel ?>ouvrier/menu.php" class="nav-link">Mon espace</a>
    <a href="<?= $rel ?>offre/lister.php" class="nav-link">Offres</a>
    <a href="<?= $rel ?>index.php?logout=1" class="nav-btn nav-btn-outline" onclick="return confirm('Se deconnecter ?')">Deconnexion</a>

  <?php else: ?>
    <a href="<?= $rel ?>offre/lister.php" class="nav-link">Offres</a>
    <a href="<?= $rel ?>agriculteur/inscri.php" class="nav-link">Inscription agriculteur</a>
    <a href="<?= $rel ?>ouvrier/inscri.php" class="nav-link">Inscription ouvrier</a>
    <a href="<?= $rel ?>connexion.php" class="nav-btn nav-btn-primary">Connexion</a>
  <?php endif; ?>
</nav>
