<?php
session_start();
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UberCueillette — Plateforme de recolte</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("config/navbar.php"); ?>

<!-- HERO -->
<div class="hero">
  <div class="hero-left">
    <span class="hero-tag">Plateforme de recolte</span>
    <h1 class="hero-title">Uber<span>Cueillette</span></h1>
    <p class="hero-desc">
      Mettez en relation agriculteurs et ouvriers pour les saisons de recolte d'olives, d'agrumes et de tomates. Simple, rapide, efficace.
    </p>
  </div>

  <div class="hero-card">
    
    
    <a href="connexion.php" class="btn btn-primary btn-block">Se connecter</a>

    <div class="hero-card-sep">ou s'inscrire</div>

    <div class="btn-row">
      <a href="agriculteur/inscri.php" class="btn btn-secondary" style="flex:1;">Agriculteur</a>
      <a href="ouvrier/inscri.php"     class="btn btn-ghost"     style="flex:1;">Ouvrier</a>
    </div>
  </div>
</div>

<!-- FEATURES -->
<div class="features">
  <h2 class="features-title">Comment ca fonctionne</h2>
  <p class="features-sub">Une plateforme pensee pour les deux acteurs de la recolte</p>

  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon icon-yellow">
        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%237A5A0D' viewBox='0 0 24 24'%3E%3Cpath d='M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z'/%3E%3C/svg%3E" width="22" height="22" alt="">
      </div>
      <h4>Agriculteurs</h4>
      <p>Publiez vos besoins de recolte, choisissez vos ouvriers et suivez les candidatures en temps reel.</p>
    </div>

    <div class="feature-card">
      <div class="feature-icon icon-blue">
        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%231A4A8A' viewBox='0 0 24 24'%3E%3Cpath d='M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z'/%3E%3C/svg%3E" width="22" height="22" alt="">
      </div>
      <h4>Ouvriers</h4>
      <p>Parcourez les offres disponibles, filtrez par fruit ou gouvernorat et postulez en un clic.</p>
    </div>

    <div class="feature-card">
      <div class="feature-icon icon-gray">
        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23555' viewBox='0 0 24 24'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E" width="22" height="22" alt="">
      </div>
      <h4>Apres la recolte</h4>
      <p>Notes, commentaires et suivi des gains pour chaque mission terminee.</p>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-inner">
    <div>
      <div class="footer-brand">Uber<span>Cueillette</span></div>
      <p style="font-size:13px;max-width:260px;line-height:1.6;">Plateforme de mise en relation entre agriculteurs et ouvriers de recolte en Tunisie.</p>
    </div>

    <div class="footer-col">
      <h5>Navigation</h5>
      <a href="offre/lister.php">Offres de recolte</a>
      <a href="connexion.php">Connexion</a>
      <a href="agriculteur/inscri.php">Inscription agriculteur</a>
      <a href="ouvrier/inscri.php">Inscription ouvrier</a>
    </div>

    <div class="footer-col">
      <h5>Responsables</h5>
      <a href="https://www.linkedin.com/in/ghofrane-araar-952960329/">Ghofrane Araar</a>
      <a href="https://www.linkedin.com/in/eya-ben-abdelhedi-a09062308/">Eya Ibn Abdelhedi</a>
      <a href="https://www.linkedin.com/in/amira-midani-3695b2361/">Amira Midani</a>
    </div>
  </div>

  <div class="footer-bottom">
    &copy; 2026 UberCueillette — Institut Superieur de Gestion de Tunis
  </div>
</footer>

</body>
</html>
