-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 12 fév. 2026 à 17:19
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
-- Base de données : `room-reservation`
--

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `event`
--

INSERT INTO `event` (`id`, `event_title`, `description`, `start_date`, `end_date`, `creator_id`) VALUES
(5, 'Massage détente luxe', 'test', '2026-02-04 12:00:00', '2026-02-04 14:00:00', 1),
(6, 'Prestation libre', 'RDV', '2026-02-04 16:00:00', '2026-02-04 17:00:00', 1),
(8, 'Massage tonifiant', 'Premier RDV', '2026-02-11 10:00:00', '2026-02-11 11:00:00', 1),
(9, 'Prestation libre', 'Massage complet', '2026-02-12 09:00:00', '2026-02-12 12:00:00', 1),
(10, 'Massage aromathérapie', 'Massage avec des huiles essentielles', '2026-02-13 18:00:00', '2026-02-13 19:00:00', 2),
(11, 'Massage relaxant', 'Bien insister sur les zones du visage', '2026-02-13 13:00:00', '2026-02-13 14:00:00', 1),
(12, 'Massage aux pierres chaudes', 'masser moi bien', '2026-02-13 14:00:00', '2026-02-13 15:00:00', 3);

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `name`, `duration`, `image`) VALUES
(1, 'Massage relaxant', 1, 'relaxant.jpg'),
(2, 'Massage tonifiant', 1, 'tonifiant.jpg'),
(3, 'Massage Shiatsu', 1, 'shiatsu.png'),
(4, 'Massage aux pierres chaudes', 1, 'pierres.jpg'),
(5, 'Massage aromathérapie', 1, 'aroma.jpg'),
(6, 'Massage détente luxe', 2, 'luxe.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1, 'Alice1', '$2y$10$1nkxALxXv5VWjMHncT1fz.b9V3/0x5Sv7CohKUq6kaNBxaqX134iK'),
(2, 'Baptiste', '$2y$10$Zy98VC4VHrMsl4FSuqcPxeHQYLiqxxJ8LQDOZuoM8ebAnOBykpPDy'),
(3, 'aicha@gmail.com', '$2y$10$Pey7qv82/SDwo8oTx5VMv.q.fnCibC5HSa.L6IEvltRypljOAZCkO');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;