-- -------------------------------------------------------------
-- TablePlus 5.4.0(504)
--
-- https://tableplus.com/
--
-- Database: bulk_carrier
-- Generation Time: 2023-08-24 15:50:11.9340
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE `oauth_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ops_ports`;
CREATE TABLE `ops_ports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `port_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `port_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(10, '2023_08_17_052809_create_permission_tables', 1),
(11, '2023_08_24_090948_create_ops_ports_table', 2);

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('02c1591619c1bdc13077d26d6539325805d4d2f87abca3e28087d38a8cd965e47c522bee1de9e423', 1, 2, NULL, '[]', 0, '2023-08-24 05:34:11', '2023-08-24 05:34:11', '2024-08-24 05:34:11'),
('0698ca6f21512463f0d39cd5dd6ea13c422b821020ac4102cc72d00655ffa93a41d3e93d55913cec', 1, 2, NULL, '[]', 0, '2023-08-24 05:43:07', '2023-08-24 05:43:07', '2024-08-24 05:43:07'),
('09059c40c82c381cd6fb4ca5cf27995187aecbe07af64fbcc4080ff40dc682ec821a258e172fcb25', 1, 2, NULL, '[]', 0, '2023-08-22 10:48:25', '2023-08-22 10:48:25', '2024-08-22 10:48:25'),
('0dd0600137953caee930e58b6a6b3590cd28742a5baeaf8b3dc7024c5a40ad8f694ae21d71876aa2', 1, 2, NULL, '[]', 0, '2023-08-24 05:23:05', '2023-08-24 05:23:05', '2024-08-24 05:23:05'),
('11cf91ee9f9b877fe7c9dbf043b1740314907f4ad81b0c92374ba147579b15b593dcc1c4d0619140', 2, 2, NULL, '[]', 0, '2023-08-24 05:21:05', '2023-08-24 05:21:05', '2024-08-24 05:21:05'),
('183e50f0e94d2a4fad9283f818fff638ef3439bea2e34fe0d3fa48a1632928ff6a48ffc0450e6cf2', 1, 2, NULL, '[]', 0, '2023-08-24 05:39:34', '2023-08-24 05:39:34', '2024-08-24 05:39:34'),
('1b977fc6feee9485ade71d9af835a50a85fcc74446e6f86ec7aef6b7bd629e870842ce6cda9ce4df', 1, 2, NULL, '[]', 0, '2023-08-24 05:35:15', '2023-08-24 05:35:15', '2024-08-24 05:35:15'),
('20aa52e89b2fbea94551ce21895d640387f711f8d2cdb662b52427fbdd799c02b476d118f16b1a5c', 1, 2, NULL, '[]', 0, '2023-08-23 10:39:00', '2023-08-23 10:39:00', '2024-08-23 10:39:00'),
('2a0de416298a09c44226b7a9873482fb3a7a7ac0cc2bf0777dabd75be80a3bb15e9aadd064c4992e', 1, 2, NULL, '[]', 0, '2023-08-24 06:07:06', '2023-08-24 06:07:06', '2024-08-24 06:07:06'),
('2bc046baebf90467acf753201fe9cf508444367f0ecbda505381ce976b21888f789744127a201d5a', 1, 2, NULL, '[]', 0, '2023-08-24 05:37:19', '2023-08-24 05:37:19', '2024-08-24 05:37:19'),
('3373d9c7aae96b4f9bb18d5f3e222eb681c4314cca671c48144eedcf3a3ca422067cc01d1052ff91', 1, 2, NULL, '[]', 0, '2023-08-24 05:23:48', '2023-08-24 05:23:48', '2024-08-24 05:23:48'),
('35ce27d61aaca56c18d52eff6a47023a6730e81e3497d2b177b310b5a50c58e0a6e562f305f42e2d', 1, 2, NULL, '[]', 0, '2023-08-24 05:18:10', '2023-08-24 05:18:10', '2024-08-24 05:18:10'),
('3a1f2c826f2cc914841c99e82b2b686eea0a6a528d2cda7387935210ed812bacb9417ce378a57517', 1, 2, NULL, '[]', 0, '2023-08-23 06:10:58', '2023-08-23 06:10:58', '2024-08-23 06:10:58'),
('3cda8216fad88cf363bfd5a5e43765b75838f60438f5ae15435d5abb25ddf1dde5a9c32328e1e792', 1, 2, NULL, '[]', 0, '2023-08-24 05:27:47', '2023-08-24 05:27:47', '2024-08-24 05:27:47'),
('43d5d252c1c31f227bb6f4be72402551ccbebd5d44f5e2cef7e59a97b1c03639b9473a6bb0a313e2', 1, 2, NULL, '[]', 0, '2023-08-24 05:11:14', '2023-08-24 05:11:14', '2024-08-24 05:11:14'),
('4445e629657b91fed475e30315339b224fca4b9a06d4b7c7f722428d3335175ed463c1eda9b3b8d4', 1, 2, NULL, '[]', 0, '2023-08-22 05:22:44', '2023-08-22 05:22:44', '2024-08-22 05:22:44'),
('4b06ed859364056903ed3564ab624fe04782e064854c1cc9b78b590ae7575c441c3c7512ef01c709', 1, 2, NULL, '[]', 0, '2023-08-22 05:08:50', '2023-08-22 05:08:50', '2024-08-22 05:08:50'),
('4d940979ee12b72c5eab1f117204296e9979a34e69cbb276ea9e4ed23feca1c675f6ad4f9b7c7835', 1, 2, NULL, '[]', 0, '2023-08-23 06:15:21', '2023-08-23 06:15:21', '2024-08-23 06:15:21'),
('5299e334539b8a7699d1ae6faa39ae2fc147e52e737f387435288b859575d24a587527e07c7e0270', 1, 2, NULL, '[]', 0, '2023-08-23 06:15:09', '2023-08-23 06:15:09', '2024-08-23 06:15:09'),
('52ca71cd0ce5e870a9fc845d4f06c30d5ae5516d6a5f20b31fefe7d20299a3f6b5759de8c1e6ce7b', 1, 2, NULL, '[]', 0, '2023-08-24 05:34:50', '2023-08-24 05:34:50', '2024-08-24 05:34:50'),
('598b0d2f5a341760bed9620626b2d083e4eb8208f5882fe2fd56a389bc83a7ec4846ca13d088f5e4', 1, 2, NULL, '[]', 0, '2023-08-22 05:21:28', '2023-08-22 05:21:28', '2024-08-22 05:21:28'),
('6685e78d0af40885484a92f507e5cb5b1f4e5674421cab9141fe34e30f459f7b8cea0a8367577f24', 1, 2, NULL, '[]', 0, '2023-08-23 08:25:55', '2023-08-23 08:25:55', '2024-08-23 08:25:55'),
('6cb8379f68981b32880cbc812f79cf90f96099a08d2532fc3d881776278486b21a7a7bcce7794bbe', 1, 2, NULL, '[]', 0, '2023-08-24 07:03:05', '2023-08-24 07:03:05', '2024-08-24 07:03:05'),
('6f113d0a0a9585a7017d373f68f0529536da1a1c9f3853cbba9368d6f1ee8346aa8addd71a04e975', 1, 2, NULL, '[]', 0, '2023-08-24 05:11:40', '2023-08-24 05:11:40', '2024-08-24 05:11:40'),
('700d97435cc56b7dcc8a64453760b193895b5be0aaa92730135aea918b15f056d7d59069b64d7d67', 1, 2, NULL, '[]', 0, '2023-08-22 05:37:26', '2023-08-22 05:37:26', '2024-08-22 05:37:26'),
('7423ef146773b87ffdced5c5e56290935738b9962e679f44298ba7a2b2e4a4920b1f6c601c041867', 1, 2, NULL, '[]', 0, '2023-08-21 08:06:29', '2023-08-21 08:06:29', '2024-08-21 08:06:29'),
('755394672ee818fb7ecae99983e9ad4b3ff2a3728936d7ae0eeed10354f9e03ebccc74d25530cf07', 1, 2, NULL, '[]', 0, '2023-08-24 05:30:53', '2023-08-24 05:30:53', '2024-08-24 05:30:53'),
('7d9ff2cc0179f3af406f124c96998797d916b15348f22b24e10fe72030f59a27206c0214f76d2bd6', 1, 2, NULL, '[]', 0, '2023-08-22 11:50:27', '2023-08-22 11:50:27', '2024-08-22 11:50:27'),
('800247552b3fd36ff40ec6b6e73900c57d6b463b93acfe39cf0843ec3219f6a579cf22cfe5038ae3', 1, 2, NULL, '[]', 0, '2023-08-24 07:01:49', '2023-08-24 07:01:49', '2024-08-24 07:01:49'),
('86a3ff77c7792bf20674b2d28ba163d98f6cfba4cd098a9519f3fec488bfb721160ee25b848646b8', 1, 2, NULL, '[]', 0, '2023-08-22 05:10:58', '2023-08-22 05:10:58', '2024-08-22 05:10:58'),
('8b9ee41c480ebda97daa3b5ab4d6102c5eea740f7eaa722b23b7f426b832e376a6e77022c7e25db3', 1, 2, NULL, '[]', 0, '2023-08-22 05:14:37', '2023-08-22 05:14:37', '2024-08-22 05:14:37'),
('8c9745359394bda6024ce529e09b0c9f023d13d3d70150b6d21b0b95cc7edcda9ae1e1ccb6f1c628', 1, 2, NULL, '[]', 0, '2023-08-24 07:02:49', '2023-08-24 07:02:49', '2024-08-24 07:02:49'),
('90a84f8aaae4559c863c6ae35cd9d71b959d02fd744b3df26483d22e8f418f8633452eb443079f5f', 1, 2, NULL, '[]', 0, '2023-08-23 06:14:45', '2023-08-23 06:14:45', '2024-08-23 06:14:45'),
('90adc7b0df4ffa35349f797c7c34f2c835517c6927778e5843aef1a0bec4c176d1b47226085387b6', 1, 2, NULL, '[]', 0, '2023-08-24 05:12:27', '2023-08-24 05:12:27', '2024-08-24 05:12:27'),
('982b37e74f402876e702be40dcd1497e006b67ccf798ea32cbd1914c0e5f34db84fa8b8b70a98bcd', 1, 2, NULL, '[]', 0, '2023-08-22 05:17:20', '2023-08-22 05:17:20', '2024-08-22 05:17:20'),
('9b1298a976229cceca09e9949a83851d6c8c67909289cabfb8b122b57d5c58f2d5a5c23749eff85f', 1, 2, NULL, '[]', 0, '2023-08-23 08:25:49', '2023-08-23 08:25:49', '2024-08-23 08:25:49'),
('9e7fe71546d4459d73cc46a5dd057df9d83b9f1071b53292068f4c52720ff3ff76325391d6f10f2c', 1, 2, NULL, '[]', 0, '2023-08-24 05:21:41', '2023-08-24 05:21:41', '2024-08-24 05:21:41'),
('abd4abee3f0bf226b7f5fad8447e3053d922a5d3a598257daf31de27d9c52d326bd153df0ac6ea35', 1, 2, NULL, '[]', 0, '2023-08-22 05:17:53', '2023-08-22 05:17:53', '2024-08-22 05:17:53'),
('ac77cfde1540a05c4498f138b53d49a15f19f7935b66226a7219e318f75d238f30699b31d86ad337', 1, 2, NULL, '[]', 0, '2023-08-24 05:29:23', '2023-08-24 05:29:23', '2024-08-24 05:29:23'),
('aebb05f0d75bbadad156d9627288a8f33a2396e6c98b5718c1b970563acc8c139b74ccc295fbef57', 1, 2, NULL, '[]', 0, '2023-08-24 06:56:28', '2023-08-24 06:56:28', '2024-08-24 06:56:28'),
('b1e1cbaed6703db6a9070451b19a5f86e98b907cfd4d89e92e8a103e2644eac2d83ce15536e07723', 1, 2, NULL, '[]', 0, '2023-08-24 05:13:48', '2023-08-24 05:13:48', '2024-08-24 05:13:48'),
('b564093c358584e2cd567b458886e78cfb45209ceddbc0e9aaa20e5d123cce80746719fd42a23bbd', 1, 2, NULL, '[]', 0, '2023-08-24 05:40:18', '2023-08-24 05:40:18', '2024-08-24 05:40:18'),
('b58645db3325f62d797a742c3b2fb7202ccbb348f96803b44f5773a686fae2e69e817270173eafa9', 1, 2, NULL, '[]', 0, '2023-08-24 05:36:09', '2023-08-24 05:36:09', '2024-08-24 05:36:09'),
('bbf3acdad289d65a27eae1298e9f925f010659c0a476e48bd7e2abd9a8644808f1fec914d0e0f140', 1, 2, NULL, '[]', 0, '2023-08-24 05:46:17', '2023-08-24 05:46:17', '2024-08-24 05:46:17'),
('c0dbaf485fc0db96489175bff31003e213bc699dae1c12f155b740160eb97b1237f4133fbfd1bad4', 1, 2, NULL, '[]', 0, '2023-08-24 05:36:52', '2023-08-24 05:36:52', '2024-08-24 05:36:52'),
('c2857a5abfc7c1962cca3b14d06a8dc0cddf81168ac9164d567ed8027b617a621f6005767ebdd9fe', 1, 2, NULL, '[]', 0, '2023-08-21 07:27:38', '2023-08-21 07:27:38', '2024-08-21 07:27:38'),
('c5d8c1958032a609cf2e855b88ebd64421736c214f93763894bb7e1b7fdf04e925b4eff6e0384ab9', 1, 2, NULL, '[]', 0, '2023-08-24 05:47:44', '2023-08-24 05:47:44', '2024-08-24 05:47:44'),
('c96e16b4feec74d5fa9faf08adebfa8e461756710ea76c86f9eddd8e72d235ce6a5b61412e178e17', 1, 2, NULL, '[]', 0, '2023-08-23 04:05:47', '2023-08-23 04:05:47', '2024-08-23 04:05:47'),
('d4a5356d7447affe8ad326adf0fc6113d6f315e4630a0ac87df2221c050a7b6157654e8b6c2c4b7c', 1, 2, NULL, '[]', 0, '2023-08-22 05:37:41', '2023-08-22 05:37:41', '2024-08-22 05:37:41'),
('d9db37841ccdfc7f366d08f4639b0ebf879ce999eb242e99f1cee915734a66c937fbfb8f549e1ace', 1, 2, NULL, '[]', 0, '2023-08-24 05:29:44', '2023-08-24 05:29:44', '2024-08-24 05:29:44'),
('dec8d424647a18e342337904986c0a4dd452b45e0a41dfc42ec3346d2d81e3277bfdaa141990e9a3', 1, 2, NULL, '[]', 0, '2023-08-24 05:24:12', '2023-08-24 05:24:12', '2024-08-24 05:24:12'),
('dfc181d262104a9a2916d6a662976c48ea1fc3287ed8cfccc3f20b9fad8a3f27054446de2df8e657', 1, 2, NULL, '[]', 0, '2023-08-24 05:25:17', '2023-08-24 05:25:17', '2024-08-24 05:25:17'),
('e3c5e2276ae0c388e7e672bee1f5e2c750b2e4173ebf30fd19f98ac4b7189a748f03224b099d2cae', 1, 2, NULL, '[]', 0, '2023-08-24 05:23:16', '2023-08-24 05:23:16', '2024-08-24 05:23:16'),
('e3eb2ce58b064d15cab13754a37aae8b7f73f2411f00f6bd022a9b14fd22e3ea7fc4f82c52c70cb6', 1, 2, NULL, '[]', 0, '2023-08-22 05:16:20', '2023-08-22 05:16:20', '2024-08-22 05:16:20'),
('e55bfb53f35821fbf8fc91ac436c8efbe891d262cc0f630fe04482500b4dda1296505591b3f2c151', 1, 2, NULL, '[]', 0, '2023-08-24 05:39:00', '2023-08-24 05:39:00', '2024-08-24 05:39:00'),
('ea47bfbb499873d5de626dab08193d325e713796d3f461080b4bf34a11db278edd29a59920b72834', 1, 2, NULL, '[]', 0, '2023-08-21 08:27:04', '2023-08-21 08:27:04', '2024-08-21 08:27:04');

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, 1, 'Laravel Personal Access Client', 'YUP3ZCq4kMHrlSkNVyJqMMtwCSDw3zXq57uyJ4z9', NULL, 'http://localhost', 1, 0, 0, '2023-08-21 04:55:02', '2023-08-21 04:55:02'),
(2, 1, 'Laravel Password Grant Client', 'cCh0pcWvPsLHL08uFgSjNMaJO6HwvyttwBJpnqRU', 'users', 'http://localhost', 0, 1, 0, '2023-08-21 04:55:02', '2023-08-21 04:55:02');

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-08-21 04:55:02', '2023-08-21 04:55:02');

INSERT INTO `oauth_refresh_tokens` (`id`, `access_token_id`, `revoked`, `expires_at`) VALUES
('0d113413c2ba5709eefd8f4bd76a0a029dcd8ca4db3d5b395c20936053206f4d63419c8f129b0abb', '8c9745359394bda6024ce529e09b0c9f023d13d3d70150b6d21b0b95cc7edcda9ae1e1ccb6f1c628', 0, '2024-08-24 07:02:49'),
('1287ff4aaee73c3aa6d316c67592b19aa635421c0d6caeb549bd4412154300c0de47597d0e020aa1', '7d9ff2cc0179f3af406f124c96998797d916b15348f22b24e10fe72030f59a27206c0214f76d2bd6', 0, '2024-08-22 11:50:27'),
('16405c3c41c6fb3725b3e23ca542c91b3dfe378132bbed552bcc294c1b1aa30e95f476c9e8010d96', 'abd4abee3f0bf226b7f5fad8447e3053d922a5d3a598257daf31de27d9c52d326bd153df0ac6ea35', 0, '2024-08-22 05:17:53'),
('21edaf33c996ffc69b900c240a2343b7a339bcac8537795fe095b3a4cdfb3300f9d3aa6b39eb25f4', 'e3eb2ce58b064d15cab13754a37aae8b7f73f2411f00f6bd022a9b14fd22e3ea7fc4f82c52c70cb6', 0, '2024-08-22 05:16:20'),
('22d31729aee071a6601fa63e4ff86e3dcdc02258a739ed6a512037710c328213767ca02147a80bb5', 'd9db37841ccdfc7f366d08f4639b0ebf879ce999eb242e99f1cee915734a66c937fbfb8f549e1ace', 0, '2024-08-24 05:29:44'),
('28aaf5ec11f9731bc943a384868254a3bd3635c5c425c68194e1088a74102d360219f922cf880ab8', '2a0de416298a09c44226b7a9873482fb3a7a7ac0cc2bf0777dabd75be80a3bb15e9aadd064c4992e', 0, '2024-08-24 06:07:06'),
('28e7b85e1a7c9adcc22c4e9a65758a8fc498d7d1d1f8b044a998e4bf6544db90c36a66583c49f893', '4445e629657b91fed475e30315339b224fca4b9a06d4b7c7f722428d3335175ed463c1eda9b3b8d4', 0, '2024-08-22 05:22:44'),
('29d9b91e7b56a73633f55da9a3fdb4affe794e9f35548b38cb11348b3acc824d8165c6b153d927f3', '09059c40c82c381cd6fb4ca5cf27995187aecbe07af64fbcc4080ff40dc682ec821a258e172fcb25', 0, '2024-08-22 10:48:25'),
('2cb8d8345afc819cfe8c756a62e71c25834ca3ff7ac5e94d6b3cbccf8915b87f29359c6fb11eb985', '4b06ed859364056903ed3564ab624fe04782e064854c1cc9b78b590ae7575c441c3c7512ef01c709', 0, '2024-08-22 05:08:51'),
('2f417d4a8713e3d959dd5050421ba3b72227fc5d00bccc9f50d5600101069806c03805e10c99f180', '4d940979ee12b72c5eab1f117204296e9979a34e69cbb276ea9e4ed23feca1c675f6ad4f9b7c7835', 0, '2024-08-23 06:15:21'),
('30f7ea61c59fbb77614877f3056c804b0c0085127746028f057665d1dccb2f46743de24e1ff738fc', 'dfc181d262104a9a2916d6a662976c48ea1fc3287ed8cfccc3f20b9fad8a3f27054446de2df8e657', 0, '2024-08-24 05:25:17'),
('39cbfc033c7f392c0b716c2e5a51e4dfd991ebbf0749a1d4b5d74648539d6bd5245261920479be39', 'ac77cfde1540a05c4498f138b53d49a15f19f7935b66226a7219e318f75d238f30699b31d86ad337', 0, '2024-08-24 05:29:23'),
('495a3201d76ba0976d7dd4ae8bf13c6eb69f51062fb0c37db81f469ebb747d64e7e969bfd481f08f', '02c1591619c1bdc13077d26d6539325805d4d2f87abca3e28087d38a8cd965e47c522bee1de9e423', 0, '2024-08-24 05:34:11'),
('4a777eef92e9177982db1713ca5f175ddef1de2230070635095cb1ae107534890bf53806224543d7', '6685e78d0af40885484a92f507e5cb5b1f4e5674421cab9141fe34e30f459f7b8cea0a8367577f24', 0, '2024-08-23 08:25:55'),
('5206c617a5536dd424ae3693cb6fd606619bd9eec0884c7f833cc208d3f68f89af722b9ebfb27188', '700d97435cc56b7dcc8a64453760b193895b5be0aaa92730135aea918b15f056d7d59069b64d7d67', 0, '2024-08-22 05:37:26'),
('562bcd0568eef0da7cfb14c6d90e29b51000be18ce3da698ff9e378319aa7062bedd87d58920b5a9', '9e7fe71546d4459d73cc46a5dd057df9d83b9f1071b53292068f4c52720ff3ff76325391d6f10f2c', 0, '2024-08-24 05:21:42'),
('6669658ca22b652cf5426088740a76c627abf2739af8ef2f69031ba861ed45269ade9806be74ff55', '20aa52e89b2fbea94551ce21895d640387f711f8d2cdb662b52427fbdd799c02b476d118f16b1a5c', 0, '2024-08-23 10:39:00'),
('684ec1baa89ac1e31e54d3aaebcc9205d1ecf4e06f67e5504fd5f16ad13cd23cd196870928e28504', '52ca71cd0ce5e870a9fc845d4f06c30d5ae5516d6a5f20b31fefe7d20299a3f6b5759de8c1e6ce7b', 0, '2024-08-24 05:34:50'),
('6c85bf4335e514f89b4251d5cc7e0f1fe10441c485bdcce9173f30464de0feac0eb5ce759868c80e', '598b0d2f5a341760bed9620626b2d083e4eb8208f5882fe2fd56a389bc83a7ec4846ca13d088f5e4', 0, '2024-08-22 05:21:28'),
('6e322c782008eb8a31ed83c74094acb65191eb95ae031f8d0f47155e79198344a94c05ac6de76493', 'c0dbaf485fc0db96489175bff31003e213bc699dae1c12f155b740160eb97b1237f4133fbfd1bad4', 0, '2024-08-24 05:36:52'),
('6f78dfd9f6092bb602668fd2104fcbc5d5c279b6c60946eb5e57ff05291bd1540c32908dbc050f4e', '183e50f0e94d2a4fad9283f818fff638ef3439bea2e34fe0d3fa48a1632928ff6a48ffc0450e6cf2', 0, '2024-08-24 05:39:34'),
('7a57bd6edb4c4005569003695a5ac676b2b446f17f4c03afdcc7d55c2587da7fee12e97193df57b4', 'c96e16b4feec74d5fa9faf08adebfa8e461756710ea76c86f9eddd8e72d235ce6a5b61412e178e17', 0, '2024-08-23 04:05:47'),
('7a5b8319edf04af45a9e26c4c5d175dfef4426fa4d502ca100c3acc02d160909a2acf2abdc17c8e4', '3cda8216fad88cf363bfd5a5e43765b75838f60438f5ae15435d5abb25ddf1dde5a9c32328e1e792', 0, '2024-08-24 05:27:47'),
('8174615b5fe887fec91a795f26890f93d81a05a8b8b18e29648a1bfb4cf6194b60c8e57cc1b80571', '8b9ee41c480ebda97daa3b5ab4d6102c5eea740f7eaa722b23b7f426b832e376a6e77022c7e25db3', 0, '2024-08-22 05:14:37'),
('8bc32264b1bf91d77591c87dfaa20cf60fbc07611c14977792505b3465aa7b96e8438a06e1f10043', '0dd0600137953caee930e58b6a6b3590cd28742a5baeaf8b3dc7024c5a40ad8f694ae21d71876aa2', 0, '2024-08-24 05:23:05'),
('8fe13cd6385d464abadb7527c274cee23c73bd24704713b49e2dd8cfdf5433983e65db77ef66c540', 'd4a5356d7447affe8ad326adf0fc6113d6f315e4630a0ac87df2221c050a7b6157654e8b6c2c4b7c', 0, '2024-08-22 05:37:41'),
('9487011961f2b878a41f30b33762489989e51d0a03c073faa40cc0df253bbfe06f3dee537226ee1d', '90adc7b0df4ffa35349f797c7c34f2c835517c6927778e5843aef1a0bec4c176d1b47226085387b6', 0, '2024-08-24 05:12:27'),
('9521f3495e763452a714ad50f4a820b9827dd6b61da61f56f126793cde77d9d84fbbf4878152f3e4', '11cf91ee9f9b877fe7c9dbf043b1740314907f4ad81b0c92374ba147579b15b593dcc1c4d0619140', 0, '2024-08-24 05:21:05'),
('963b6da429c3a26d1e934c7d1de02b83a9b749b841252a01240f35b5deb121ed6e1b19ec1496a6a6', 'c5d8c1958032a609cf2e855b88ebd64421736c214f93763894bb7e1b7fdf04e925b4eff6e0384ab9', 0, '2024-08-24 05:47:44'),
('970e14076feee5c58cc352633840ab34e55a16c5c4eddd20973a3f02e4fc8d495382358b5b710bbb', '6f113d0a0a9585a7017d373f68f0529536da1a1c9f3853cbba9368d6f1ee8346aa8addd71a04e975', 0, '2024-08-24 05:11:40'),
('9e62bcb2b886fb5652e42c9a468db1feaaace7ef367f41bea4055007cb908872133f2677e49f7abf', 'c2857a5abfc7c1962cca3b14d06a8dc0cddf81168ac9164d567ed8027b617a621f6005767ebdd9fe', 0, '2024-08-21 07:27:38'),
('9f590f6e9355649ffe7886285e39b7a7e4699f0fbeed0a8f92c9b169fba1c3edeb1aa250da059e2a', '755394672ee818fb7ecae99983e9ad4b3ff2a3728936d7ae0eeed10354f9e03ebccc74d25530cf07', 0, '2024-08-24 05:30:53'),
('a31c7875a994f0cab76d3aea228054c5bc3c8c835a4e398d9746cab71878c8d2ab3bf3e2423cb5d5', '3a1f2c826f2cc914841c99e82b2b686eea0a6a528d2cda7387935210ed812bacb9417ce378a57517', 0, '2024-08-23 06:10:58'),
('a677c29c1e2f4b546ba9ddc18d2ae225b24c05982b05e6e691587e3ed9855b4939e7ca1b6bf9c969', '3373d9c7aae96b4f9bb18d5f3e222eb681c4314cca671c48144eedcf3a3ca422067cc01d1052ff91', 0, '2024-08-24 05:23:48'),
('ab50b74a9afea73c8f7f8b4bda1f188fac659d640ca3309b0db14bf75f9df6bde49eaed7fb7ecda1', 'bbf3acdad289d65a27eae1298e9f925f010659c0a476e48bd7e2abd9a8644808f1fec914d0e0f140', 0, '2024-08-24 05:46:18'),
('c0a7c8fd4a9ca3b7c144aeb7be7f65011cec41e678c512b19457f6772b5da69318cb6d9dd7a9f87a', '6cb8379f68981b32880cbc812f79cf90f96099a08d2532fc3d881776278486b21a7a7bcce7794bbe', 0, '2024-08-24 07:03:05'),
('c9901fc76b8fe64cf233e0f4ce63b92cd480a36884f78cec25f39e9413b8685deed170963f9a149e', 'ea47bfbb499873d5de626dab08193d325e713796d3f461080b4bf34a11db278edd29a59920b72834', 0, '2024-08-21 08:27:04'),
('c9fdc0b622765c480b9819703ef34728ff4daad6b78f4ebeb7b0d2b2143dcdb9b970dfb1d2f88c4a', 'dec8d424647a18e342337904986c0a4dd452b45e0a41dfc42ec3346d2d81e3277bfdaa141990e9a3', 0, '2024-08-24 05:24:12'),
('cbd8bb8dd832e31e94e60ff50748dc8a8914bdefda09d7af9fd4ef594b1c7a7846bc4082d6f4b0d3', '9b1298a976229cceca09e9949a83851d6c8c67909289cabfb8b122b57d5c58f2d5a5c23749eff85f', 0, '2024-08-23 08:25:49'),
('ce0d44a656aa1b6feecff2c7b1a3c9cd0e46a218a82d1f6eee843cacb94904c65c9e4d08dd1c7e40', 'b1e1cbaed6703db6a9070451b19a5f86e98b907cfd4d89e92e8a103e2644eac2d83ce15536e07723', 0, '2024-08-24 05:13:48'),
('cea8ffc354f491a3641b366430071cd96346f3408d2abb0030a3ce76a0aa71af8c898dda7bcd4a90', '982b37e74f402876e702be40dcd1497e006b67ccf798ea32cbd1914c0e5f34db84fa8b8b70a98bcd', 0, '2024-08-22 05:17:20'),
('d1f1773a8e141d49a09b254fc768fbcc8143dfb8ea799380a39a781eadcd86f6133cba6d51d5c0a8', 'e3c5e2276ae0c388e7e672bee1f5e2c750b2e4173ebf30fd19f98ac4b7189a748f03224b099d2cae', 0, '2024-08-24 05:23:16'),
('d366b51d2190780b517d928d1660fdb031cf80e788ac415f5237c52b63a67d4e529b5bcae1623466', '7423ef146773b87ffdced5c5e56290935738b9962e679f44298ba7a2b2e4a4920b1f6c601c041867', 0, '2024-08-21 08:06:29'),
('d98bcdd37ce4303b51488be358fd231760ad2cc560e1fe88df5c45f6f9b0534fbcba7bd838ecfa97', 'b58645db3325f62d797a742c3b2fb7202ccbb348f96803b44f5773a686fae2e69e817270173eafa9', 0, '2024-08-24 05:36:09'),
('e02f391be2f49c7df28b9ab0b5bbba6d22d33a7f02cef3b8795af2ea0eca9e9ba3c249bf4463c083', '86a3ff77c7792bf20674b2d28ba163d98f6cfba4cd098a9519f3fec488bfb721160ee25b848646b8', 0, '2024-08-22 05:10:58'),
('e11967d7b26bd20ce08860f715afb62457694b92ac84c28c3c79931267c73569fa0b2901f20dc6f7', '5299e334539b8a7699d1ae6faa39ae2fc147e52e737f387435288b859575d24a587527e07c7e0270', 0, '2024-08-23 06:15:09'),
('e2163506274f66d5934dba9ca93cebcb5fa5d3b85c607a7dad248dd6788ceea41ef5cccc52aab3f9', '35ce27d61aaca56c18d52eff6a47023a6730e81e3497d2b177b310b5a50c58e0a6e562f305f42e2d', 0, '2024-08-24 05:18:10'),
('e22f2493c02888913b404bdd6eab76951652ecc98788672d5f4db6425b1483b52e2ca72f9aec854e', '2bc046baebf90467acf753201fe9cf508444367f0ecbda505381ce976b21888f789744127a201d5a', 0, '2024-08-24 05:37:19'),
('e9119da94ad285fbe2acc32d5819ccd3fa949adbb98df674b0c7909c9cd4bb859838a84c0b560ba3', '1b977fc6feee9485ade71d9af835a50a85fcc74446e6f86ec7aef6b7bd629e870842ce6cda9ce4df', 0, '2024-08-24 05:35:15'),
('eb77579b1a6e08bd8e7347517f268c53970b234cb80181c70ac230e0f781f1bdf43bce46d6dde186', 'aebb05f0d75bbadad156d9627288a8f33a2396e6c98b5718c1b970563acc8c139b74ccc295fbef57', 0, '2024-08-24 06:56:28'),
('ec9130acd7f2d37e0aa80b6171c457d22c5e49dcafa64a14783645dc6ea6db5e4c5f8dce336e97ee', '800247552b3fd36ff40ec6b6e73900c57d6b463b93acfe39cf0843ec3219f6a579cf22cfe5038ae3', 0, '2024-08-24 07:01:49'),
('f4db3d25499873bee2009c044fa1e9cc60825b972ecf146ac720497702d8a1a0597d47a30bd002b6', '43d5d252c1c31f227bb6f4be72402551ccbebd5d44f5e2cef7e59a97b1c03639b9473a6bb0a313e2', 0, '2024-08-24 05:11:14'),
('f5ac4a13f58e89c74b81cf0bff25e79784122ecc4fed26c080d0f2f4d60687d21a313dc3a6d3ec7d', 'e55bfb53f35821fbf8fc91ac436c8efbe891d262cc0f630fe04482500b4dda1296505591b3f2c151', 0, '2024-08-24 05:39:00'),
('f8cffd554e8f5986415e947cef319ace07e68de1afb16844291c2b2174d1f983a405394e38fbe421', '90a84f8aaae4559c863c6ae35cd9d71b959d02fd744b3df26483d22e8f418f8633452eb443079f5f', 0, '2024-08-23 06:14:45'),
('fdda671f513b45e16c5283b53903c059753ad37559df2afeaaeefc44da1ccf3873bdf537640df9b8', '0698ca6f21512463f0d39cd5dd6ea13c422b821020ac4102cc72d00655ffa93a41d3e93d55913cec', 0, '2024-08-24 05:43:07'),
('ff5e6d2193f08b68f2574be910dc0f883641cd8afad718b7569674b0b4fd0944c8e18519801c0e83', 'b564093c358584e2cd567b458886e78cfb45209ceddbc0e9aaa20e5d123cce80746719fd42a23bbd', 0, '2024-08-24 05:40:18');

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'api', '2023-08-21 11:46:32', '2023-08-21 11:46:34');

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'demo@magnetismtech.com', NULL, '$2a$12$ZN7BiiczB7lhjqQqA6yHoOVT/ZB51K/D2lc9czzNdV6CH/d8IFTZy', NULL, '2023-08-21 11:45:06', '2023-08-21 11:45:08'),
(2, 'Admin', 'admin@magnetismtech.com', NULL, '$2a$12$ZN7BiiczB7lhjqQqA6yHoOVT/ZB51K/D2lc9czzNdV6CH/d8IFTZy', NULL, '2023-08-21 11:45:06', '2023-08-21 11:45:08');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;