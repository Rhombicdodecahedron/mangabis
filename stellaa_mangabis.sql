-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : Dim 14 mars 2021 à 14:15
-- Version du serveur :  10.3.27-MariaDB-cll-lve
-- Version de PHP : 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `stellaa_mangabis`
--

-- --------------------------------------------------------

--
-- Structure de la table `t_annonce`
--

CREATE TABLE `t_annonce` (
  `pk_annonce` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  `prix` int(11) NOT NULL,
  `fk_utilisateur` int(11) NOT NULL,
  `fk_etat` int(11) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `t_annonce`
--

INSERT INTO `t_annonce` (`pk_annonce`, `titre`, `description`, `prix`, `fk_utilisateur`, `fk_etat`, `date_creation`) VALUES
(46, 'Ma première annonce', 'Ceci est une annonce', 2, 1, 2, '2021-03-13 22:16:24');

-- --------------------------------------------------------

--
-- Structure de la table `t_etat`
--

CREATE TABLE `t_etat` (
  `pk_etat` int(11) NOT NULL,
  `etat` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `t_etat`
--

INSERT INTO `t_etat` (`pk_etat`, `etat`) VALUES
(1, 'Neuf'),
(2, 'D\'occation - comme neuf'),
(3, 'D\'occation - bon état'),
(4, 'D\'occation - assez bon état');

-- --------------------------------------------------------

--
-- Structure de la table `t_image`
--

CREATE TABLE `t_image` (
  `pk_image` int(11) NOT NULL,
  `image` varchar(700) NOT NULL,
  `fk_annonce` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `t_image`
--

INSERT INTO `t_image` (`pk_image`, `image`, `fk_annonce`) VALUES
(45, 'uploads/46/78jyvzqnbeu21-1615673784.jpg', 46);

-- --------------------------------------------------------

--
-- Structure de la table `t_utilisateur`
--

CREATE TABLE `t_utilisateur` (
  `pk_utilisateur` int(11) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `prenom` varchar(45) NOT NULL,
  `email` varchar(320) NOT NULL,
  `telephone` char(10) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `estAdmin` tinyint(4) NOT NULL,
  `motdepasse` char(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `t_utilisateur`
--

INSERT INTO `t_utilisateur` (`pk_utilisateur`, `nom`, `prenom`, `email`, `telephone`, `date_creation`, `estAdmin`, `motdepasse`) VALUES
(1, 'Stella', 'Alexis', 'velsac.123@gmail.com', '1767781633', '2021-02-13 13:07:19', 1, '$2y$10$bXtqVzWXZ5QfO8kSYTwWHO7t8wCr1mUeOiDlhuaZrnpyS1SKeUZcK'),
(2, 'Marley', 'Bob', 'stellaa16@outlook.com', '0269150816', '2021-02-14 11:08:12', 0, '$2y$10$H1b96aqvItRYIw.lihCZu.yCzgrBP/rAmgwTokgTwBug3hGV4VB8W'),
(4, 'Marcé', 'Alexandre', 'example@gmail.com', '0767767676', '2021-03-11 22:29:55', 0, '$2y$10$.3SXmN22A4HNJ3JQMNfvyu4p/M57gXPuLrmEeuBxc/pNDoZYVfkE6');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `t_annonce`
--
ALTER TABLE `t_annonce`
  ADD PRIMARY KEY (`pk_annonce`),
  ADD KEY `fk_utilisateur` (`fk_utilisateur`),
  ADD KEY `fk_etat` (`fk_etat`);

--
-- Index pour la table `t_etat`
--
ALTER TABLE `t_etat`
  ADD PRIMARY KEY (`pk_etat`);

--
-- Index pour la table `t_image`
--
ALTER TABLE `t_image`
  ADD PRIMARY KEY (`pk_image`),
  ADD KEY `fk_annonce` (`fk_annonce`);

--
-- Index pour la table `t_utilisateur`
--
ALTER TABLE `t_utilisateur`
  ADD PRIMARY KEY (`pk_utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `t_annonce`
--
ALTER TABLE `t_annonce`
  MODIFY `pk_annonce` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `t_etat`
--
ALTER TABLE `t_etat`
  MODIFY `pk_etat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `t_image`
--
ALTER TABLE `t_image`
  MODIFY `pk_image` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `t_utilisateur`
--
ALTER TABLE `t_utilisateur`
  MODIFY `pk_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `t_annonce`
--
ALTER TABLE `t_annonce`
  ADD CONSTRAINT `t_annonce_ibfk_1` FOREIGN KEY (`fk_etat`) REFERENCES `t_etat` (`pk_etat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_annonce_ibfk_2` FOREIGN KEY (`fk_utilisateur`) REFERENCES `t_utilisateur` (`pk_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `t_image`
--
ALTER TABLE `t_image`
  ADD CONSTRAINT `t_image_ibfk_1` FOREIGN KEY (`fk_annonce`) REFERENCES `t_annonce` (`pk_annonce`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
