// Confirmation générale
function confirmer(message) {
    return confirm(message);
}

// Message de succès
function succes(msg) {
    alert(msg);
}

// Vérification formulaire inscription (agriculteur & ouvrier)
function verifierFormulaire() {
    let cinEl = document.getElementsByName("CIN")[0];
    let pseudoEl = document.getElementById("pseudo");
    let passwordEl = document.getElementById("password");

    if (!cinEl || !pseudoEl || !passwordEl) return true;

    let cin = cinEl.value.trim();
    let pseudo = pseudoEl.value.trim();
    let password = passwordEl.value;

    // CIN = exactement 8 chiffres
    if (!/^[0-9]{8}$/.test(cin)) {
        alert("Le CIN doit contenir exactement 8 chiffres.");
        cinEl.focus();
        return false;
    }

    // pseudo = lettres uniquement
    if (!/^[A-Za-z]+$/.test(pseudo)) {
        alert("Le pseudo doit contenir uniquement des lettres.");
        pseudoEl.focus();
        return false;
    }

    // password = au moins 8 caractères alphanumériques + finit par $ ou #
    if (!/^[A-Za-z0-9]{7,}[$#]$/.test(password)) {
        alert("Mot de passe invalide : minimum 8 caractères (lettres/chiffres) et doit finir par $ ou #.");
        passwordEl.focus();
        return false;
    }

    return true;
}

// Vérification dates d'une offre
function verifierDatesOffre() {
    let debut = document.getElementsByName("date_debut")[0].value;
    let fin = document.getElementsByName("date_fin")[0].value;
    let limite = document.getElementsByName("date_limite")[0].value;

    if (debut && fin && debut >= fin) {
        alert("La date de fin doit être postérieure à la date de début.");
        return false;
    }
    if (limite && debut && limite >= debut) {
        alert("La date limite de postulation doit être antérieure à la date de début.");
        return false;
    }
    return true;
}

// Filtrer les offres (fruit, gouvernorat, prix)
function filtrerOffres() {
    let fruit = document.getElementById("filtreFruit") ? document.getElementById("filtreFruit").value.toLowerCase() : "";
    let lieu = document.getElementById("filtreLieu") ? document.getElementById("filtreLieu").value.toLowerCase() : "";
    let prixMin = document.getElementById("filtrePrixMin") ? parseFloat(document.getElementById("filtrePrixMin").value) : 0;
    let prixMax = document.getElementById("filtrePrixMax") ? parseFloat(document.getElementById("filtrePrixMax").value) : Infinity;

    let offres = document.querySelectorAll(".offre");

    offres.forEach(offre => {
        let fruitText = offre.querySelector(".fruit") ? offre.querySelector(".fruit").innerText.toLowerCase() : "";
        let lieuText = offre.querySelector(".lieu") ? offre.querySelector(".lieu").innerText.toLowerCase() : "";
        let prixText = offre.querySelector(".prix") ? parseFloat(offre.querySelector(".prix").innerText) : 0;

        let ok = true;
        if (fruit && !fruitText.includes(fruit)) ok = false;
        if (lieu && !lieuText.includes(lieu)) ok = false;
        if (!isNaN(prixMin) && prixMin > 0 && prixText < prixMin) ok = false;
        if (!isNaN(prixMax) && prixMax < Infinity && prixText > prixMax) ok = false;

        offre.style.display = ok ? "block" : "none";
    });
}

// Désactiver bouton après clic
function desactiverBouton(btn) {
    btn.disabled = true;
    btn.innerText = "Traitement...";
}
