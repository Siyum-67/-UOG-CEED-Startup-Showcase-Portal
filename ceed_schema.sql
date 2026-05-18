-- CEED Startup Showcase schema (cms database)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'founder',
  `is_restricted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `startups` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `short_pitch` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `team` text NOT NULL,
  `stage` varchar(50) NOT NULL,
  `drive_link` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `startup_id` int(11) NOT NULL,
  `author_name` varchar(120) NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `startup_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

ALTER TABLE `startups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `category_id` (`category_id`);

ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `startup_id` (`startup_id`);

ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `startup_id` (`startup_id`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `startups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `categories` (`name`, `slug`) VALUES
('Tech', 'tech'),
('Agriculture', 'agriculture'),
('Social Impact', 'social-impact');

COMMIT;
