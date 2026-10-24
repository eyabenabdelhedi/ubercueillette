-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 24 avr. 2026 à 15:14
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `uber_cueillette`
-- (structure + données de référence uniquement : gouvernorats et types de fruits.
--  Les comptes et données de test ont été retirés.)
--

-- --------------------------------------------------------

--
-- Structure de la table `agriculteur`
--

CREATE TABLE `agriculteur` (
  `id_agriculteur` int(10) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `CIN` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adresse` varchar(500) NOT NULL,
  `pseudo` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `candidature`
--

CREATE TABLE `candidature` (
  `id_candidature` int(10) UNSIGNED NOT NULL,
  `id_offre` int(10) UNSIGNED NOT NULL,
  `id_ouvrier` int(10) UNSIGNED NOT NULL,
  `decision` enum('acceptation','refus','en cours') NOT NULL DEFAULT 'en cours',
  `date_candidature` timestamp NOT NULL DEFAULT current_timestamp(),
  `note` int(10) UNSIGNED DEFAULT NULL COMMENT '0-10 après récolte',
  `commentaire` varchar(255) DEFAULT NULL,
  `remuneration` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `gouvernorat`
--

CREATE TABLE `gouvernorat` (
  `id_gouvernorat` int(10) UNSIGNED NOT NULL,
  `libelle` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `gouvernorat`
--

INSERT INTO `gouvernorat` (`id_gouvernorat`, `libelle`) VALUES
(2, 'Ariana'),
(8, 'Béja'),
(3, 'Ben Arous'),
(7, 'Bizerte'),
(19, 'Gabès'),
(22, 'Gafsa'),
(9, 'Jendouba'),
(12, 'Kairouan'),
(13, 'Kasserine'),
(24, 'Kébili'),
(10, 'Kef'),
(17, 'Mahdia'),
(4, 'Manouba'),
(20, 'Médenine'),
(16, 'Monastir'),
(5, 'Nabeul'),
(18, 'Sfax'),
(14, 'Sidi Bouzid'),
(11, 'Siliana'),
(15, 'Sousse'),
(21, 'Tataouine'),
(23, 'Tozeur'),
(1, 'Tunis'),
(6, 'Zaghouan');

-- --------------------------------------------------------

--
-- Structure de la table `offre`
--

CREATE TABLE `offre` (
  `id_offre` int(10) UNSIGNED NOT NULL,
  `id_type_fruit` int(10) UNSIGNED NOT NULL,
  `id_gouvernorat` int(10) UNSIGNED NOT NULL,
  `adresse` varchar(500) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `nombre_ouvriers` int(10) UNSIGNED NOT NULL,
  `prix_journee` decimal(10,2) NOT NULL,
  `date_limite` date NOT NULL,
  `id_agriculteur` int(10) UNSIGNED NOT NULL,
  `statut` enum('open','closed') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `ouvrier`
--

CREATE TABLE `ouvrier` (
  `id_ouvrier` int(10) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `CIN` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adresse` varchar(500) DEFAULT NULL COMMENT 'Profil ouvrier',
  `photo` longblob DEFAULT NULL,
  `mime_photo` varchar(64) DEFAULT 'image/jpeg',
  `description` text DEFAULT NULL,
  `pseudo` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `type_fruit`
--

CREATE TABLE `type_fruit` (
  `id_type_fruit` int(10) UNSIGNED NOT NULL,
  `libelle` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_fruit`
--

INSERT INTO `type_fruit` (`id_type_fruit`, `libelle`) VALUES
(2, 'Agrumes'),
(5, 'Autre'),
(1, 'Olives'),
(4, 'Pêches'),
(3, 'Tomates');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agriculteur`
--
ALTER TABLE `agriculteur`
  ADD PRIMARY KEY (`id_agriculteur`),
  ADD UNIQUE KEY `uk_agriculteur_email` (`email`),
  ADD UNIQUE KEY `uk_agriculteur_pseudo` (`pseudo`),
  ADD UNIQUE KEY `uk_agriculteur_cin` (`CIN`);

--
-- Index pour la table `candidature`
--
ALTER TABLE `candidature`
  ADD PRIMARY KEY (`id_candidature`),
  ADD UNIQUE KEY `uk_offre_ouvrier` (`id_offre`,`id_ouvrier`),
  ADD KEY `idx_candidature_offre` (`id_offre`),
  ADD KEY `idx_candidature_ouvrier` (`id_ouvrier`);

--
-- Index pour la table `gouvernorat`
--
ALTER TABLE `gouvernorat`
  ADD PRIMARY KEY (`id_gouvernorat`),
  ADD UNIQUE KEY `uk_gouvernorat_libelle` (`libelle`);

--
-- Index pour la table `offre`
--
ALTER TABLE `offre`
  ADD PRIMARY KEY (`id_offre`),
  ADD KEY `idx_offre_agriculteur` (`id_agriculteur`),
  ADD KEY `idx_offre_fruit` (`id_type_fruit`),
  ADD KEY `idx_offre_gouv` (`id_gouvernorat`),
  ADD KEY `idx_offre_statut` (`statut`);

--
-- Index pour la table `ouvrier`
--
ALTER TABLE `ouvrier`
  ADD PRIMARY KEY (`id_ouvrier`),
  ADD UNIQUE KEY `uk_ouvrier_email` (`email`),
  ADD UNIQUE KEY `uk_ouvrier_pseudo` (`pseudo`),
  ADD UNIQUE KEY `uk_ouvrier_cin` (`CIN`);

--
-- Index pour la table `type_fruit`
--
ALTER TABLE `type_fruit`
  ADD PRIMARY KEY (`id_type_fruit`),
  ADD UNIQUE KEY `uk_type_fruit_libelle` (`libelle`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agriculteur`
--
ALTER TABLE `agriculteur`
  MODIFY `id_agriculteur` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `candidature`
--
ALTER TABLE `candidature`
  MODIFY `id_candidature` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gouvernorat`
--
ALTER TABLE `gouvernorat`
  MODIFY `id_gouvernorat` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `offre`
--
ALTER TABLE `offre`
  MODIFY `id_offre` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ouvrier`
--
ALTER TABLE `ouvrier`
  MODIFY `id_ouvrier` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `type_fruit`
--
ALTER TABLE `type_fruit`
  MODIFY `id_type_fruit` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `candidature`
--
ALTER TABLE `candidature`
  ADD CONSTRAINT `fk_candidature_offre` FOREIGN KEY (`id_offre`) REFERENCES `offre` (`id_offre`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_candidature_ouvrier` FOREIGN KEY (`id_ouvrier`) REFERENCES `ouvrier` (`id_ouvrier`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `offre`
--
ALTER TABLE `offre`
  ADD CONSTRAINT `fk_offre_agriculteur` FOREIGN KEY (`id_agriculteur`) REFERENCES `agriculteur` (`id_agriculteur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_offre_gouvernorat` FOREIGN KEY (`id_gouvernorat`) REFERENCES `gouvernorat` (`id_gouvernorat`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_offre_type_fruit` FOREIGN KEY (`id_type_fruit`) REFERENCES `type_fruit` (`id_type_fruit`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
