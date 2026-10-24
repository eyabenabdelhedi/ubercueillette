<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['id_agriculteur'])) {
    header("Location: connexion.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_candidature'], $_POST['decision'])) {

    $id_candidature = (int)$_POST['id_candidature'];
    $decision       = $_POST['decision'];
    $id_offre       = (int)($_POST['id_offre'] ?? 0);

    // ✅ CORRIGE : valeurs qui correspondent exactement a ce que la BDD stocke
    if (!in_array($decision, ['acceptation', 'refus'])) {
        die("Décision invalide.");
    }

    $check = $conn->prepare("SELECT c.id_candidature, o.nombre_ouvriers, o.id_offre
                              FROM candidature c
                              JOIN offre o ON c.id_offre = o.id_offre
                              WHERE c.id_candidature = ? AND o.id_agriculteur = ?");
    $check->execute([$id_candidature, $_SESSION['id_agriculteur']]);
    $row = $check->fetch();

    if (!$row) {
        die("Accès refusé.");
    }

    // ✅ CORRIGE : compter avec 'acceptation' (valeur réelle en BDD)
    if ($decision === 'acceptation') {
        $cntStmt = $conn->prepare("SELECT COUNT(*) as nb FROM candidature WHERE id_offre = ? AND decision = 'acceptation'");
        $cntStmt->execute([$row['id_offre']]);
        $nbAcceptes = $cntStmt->fetch()['nb'];
        if ($nbAcceptes >= $row['nombre_ouvriers']) {
            header("Location: voircandidatures.php?id_offre=" . $row['id_offre'] . "&erreur=quota");
            exit;
        }
    }

    $stmt = $conn->prepare("UPDATE candidature SET decision = ? WHERE id_candidature = ?");
    $stmt->execute([$decision, $id_candidature]);
}

$retour = isset($_POST['id_offre']) ? "voircandidatures.php?id_offre=" . (int)$_POST['id_offre'] : "voircandidatures.php";
header("Location: $retour");
exit;
?>