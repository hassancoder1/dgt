-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 31, 2024 at 07:48 AM
-- Server version: 10.11.9-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u152432976_dgt_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `afg_invs`
--

CREATE TABLE `afg_invs` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'afg',
  `_from` longtext NOT NULL,
  `third_party` longtext NOT NULL,
  `_to` longtext NOT NULL,
  `_date` date NOT NULL,
  `json_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`json_data`)),
  `terms` longtext NOT NULL,
  `through` longtext NOT NULL,
  `json_final` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`json_final`)),
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `afg_inv_details`
--

CREATE TABLE `afg_inv_details` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `json_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`json_data`)),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agent_payments`
--

CREATE TABLE `agent_payments` (
  `id` int(11) NOT NULL,
  `loading_id` varchar(50) NOT NULL,
  `loading_bl_no` varchar(255) NOT NULL,
  `bill_no` varchar(50) NOT NULL,
  `date` varchar(50) NOT NULL,
  `bill_details` varchar(255) NOT NULL,
  `transfer_details` longtext NOT NULL,
  `sr_no` varchar(50) NOT NULL,
  `details` varchar(255) NOT NULL,
  `quantity` varchar(50) NOT NULL,
  `rate` varchar(50) NOT NULL,
  `total` varchar(50) NOT NULL,
  `tax_percentage` varchar(50) NOT NULL,
  `tax_amount` varchar(50) NOT NULL,
  `grand_total` varchar(50) NOT NULL,
  `agent_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agent_payments`
--

INSERT INTO `agent_payments` (`id`, `loading_id`, `loading_bl_no`, `bill_no`, `date`, `bill_details`, `transfer_details`, `sr_no`, `details`, `quantity`, `rate`, `total`, `tax_percentage`, `tax_amount`, `grand_total`, `agent_file`, `created_at`) VALUES
(1, '2', 'az.0022', '1', 'Oct-24-30', ' NIM TO IRAN POT', '{\"bill_of_entry_no\":\"6050\",\"transferred_to_admin\":true,\"transferred_to_accounts\":false,\"child_ids\":\"\",\"total_amount\":\"5000\",\"total_bill_amount\":\"5000\",\"total_tax_amount\":\"0\"}', '1', 'TRAZ', '1', '5000', '5000', '0', '0.00', '5000', '[]', '2024-10-30 09:15:19');

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `source_name` varchar(255) NOT NULL,
  `attachment` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `source_id`, `source_name`, `attachment`, `created_at`) VALUES
(1, 4, 'purchase_contract', 'Purchase_29-Oct-24.pdf', '2024-10-29 23:31:19'),
(2, 1, 'purchase_contract', 'Purchase_29-Oct-24.pdf', '2024-10-29 23:33:32'),
(3, 2, 'purchase_contract', '290438179_414562123978661_87844885375616270_n.jpg', '2024-10-30 13:22:52');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `b_code` varchar(255) NOT NULL,
  `b_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `country_id` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `whatsapp` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `user_id`, `b_code`, `b_name`, `name`, `father_name`, `address`, `country_id`, `city`, `zip_code`, `mobile`, `phone`, `email`, `whatsapp`, `created_at`, `created_by`) VALUES
(1, 2, 'UAE-1', 'DUBAI OFFICE', 'ASMATULLAH ', 'ABDULLAH', 'ALHABTOOR BUILDING, ALRAS OFFICE NO 201. 2ND FLOOR ', 8, 'dubai', '0000', '+971544816664', '+9142278608', 'dubai.office@dgt.llc', '+971544816664', '2024-07-29 21:55:02', 1),
(2, 3, 'P-22', 'PAKISTAN', 'NAJEEBULLAH ', 'ABDULLAH', 'SANATANN BAZAR. 2ND FLOOR OFFICE NO 2', 165, 'QUETTA', '0000', '0561202687', '+92826614073', 'Najeeb@dgt.llc', '+923188088899', '2024-07-29 21:55:19', 1),
(3, 9, 'AF 3', 'AFGHANISTAN OFFICE ', 'NASEEEB ULLAH ', 'ABDULLAHA', 'KANDAHAR AFGHISTAN', 2, 'KANDHGAR ', '0000', '+93704862191', '+923023988899', 'anitcoq@gmail.com', '+923023988899', '2024-07-25 23:34:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `business_settings`
--

CREATE TABLE `business_settings` (
  `id` int(11) NOT NULL,
  `siteurl` text NOT NULL,
  `sitename` text NOT NULL,
  `sitedescription` text DEFAULT NULL,
  `copy` text DEFAULT NULL,
  `vat_acc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`vat_acc`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_settings`
--

INSERT INTO `business_settings` (`id`, `siteurl`, `sitename`, `sitedescription`, `copy`, `vat_acc`) VALUES
(1, 'https://accounts2.dgt.llc/', 'DGT L.L.C', 'Asmatullah New Software', '© All Rights Reserved', '{\"khaata_id\":\"52\",\"khaata_no\":\"DU1987\"}');

-- --------------------------------------------------------

--
-- Table structure for table `cats`
--

CREATE TABLE `cats` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `details` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cats`
--

INSERT INTO `cats` (`id`, `branch_id`, `name`, `details`, `created_at`, `created_by`) VALUES
(1, 1, 'dc', 'Customer ', '2024-10-29 16:58:51', 0),
(4, 1, 'db', 'bank', '2024-10-29 16:59:42', 0),
(5, 1, 'Du', 'Customs Cleaner', '2024-10-29 17:00:05', 0),
(6, 1, 'dp', 'PURCHAS/ESSLEE', '2024-10-29 17:00:51', 0),
(7, 1, 'DG', 'Customs Cleaner', '2024-10-29 17:37:26', 0);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`) VALUES
(1, 'Aruba', '297'),
(2, 'Afghanistan', '93'),
(3, 'Angola', '244'),
(4, 'Anguilla', '1-264'),
(5, 'Albania', '355'),
(6, 'Andorra', '376'),
(7, 'Netherlands Antilles', '599'),
(8, 'United Arab Emirates', ''),
(9, 'Argentina', '54'),
(10, 'Armenia', '374'),
(11, 'American Samoa', '1-684'),
(12, 'Antarctica', '672'),
(13, 'French Southern territories', ''),
(14, 'Antigua and Barbuda', '1-268	'),
(15, 'Australia', '61'),
(16, 'Austria', '43'),
(17, 'Azerbaijan', '994'),
(18, 'Burundi', '257'),
(19, 'Belgium', '32'),
(20, 'Benin', '229	'),
(21, 'Burkina Faso', '226'),
(22, 'Bangladesh', '880'),
(23, 'Bulgaria', '359'),
(24, 'Bahrain', '973'),
(25, 'Bahamas', '1-242	'),
(26, 'Bosnia and Herzegovina', '387'),
(27, 'Belarus', '375'),
(28, 'Belize', '501'),
(29, 'Bermuda', '1-441	'),
(30, 'Bolivia', '591'),
(31, 'Brazil', '55'),
(32, 'Barbados', '1-246	'),
(33, 'Brunei', '673'),
(34, 'Bhutan', '975'),
(35, 'Bouvet Island', ''),
(36, 'Botswana', '267'),
(37, 'Central African Republic', '236'),
(38, 'Canada', '1'),
(39, 'Cocos (Keeling) Islands', ''),
(40, 'Switzerland', ''),
(41, 'Chile', '56'),
(42, 'China', '86'),
(43, 'CÃ´te dâ€™Ivoire', ''),
(44, 'Cameroon', '237'),
(45, 'Congo, The Democratic Republic', '243'),
(46, 'Congo', '242'),
(47, 'Cook Islands', '682'),
(48, 'Colombia', '57'),
(49, 'Comoros', '269'),
(50, 'Cape Verde', '238'),
(51, 'Costa Rica', '506'),
(52, 'Cuba', '53'),
(53, 'Christmas Island', '61'),
(54, 'Cayman Islands', '1-345'),
(55, 'Cyprus', ''),
(56, 'Czech Republic', ''),
(57, 'Germany', ''),
(58, 'Djibouti', ''),
(59, 'Dominica', ''),
(60, 'Denmark', ''),
(61, 'Dominican Republic', ''),
(62, 'Algeria', '213'),
(63, 'Ecuador', ''),
(64, 'Egypt', ''),
(65, 'Eritrea', ''),
(66, 'Western Sahara', ''),
(67, 'Spain', ''),
(68, 'Estonia', ''),
(69, 'Ethiopia', ''),
(70, 'Finland', ''),
(71, 'Fiji Islands', ''),
(72, 'Falkland Islands', ''),
(73, 'France', ''),
(74, 'Faroe Islands', ''),
(75, 'Micronesia, Federated States o', ''),
(76, 'Gabon', ''),
(77, 'United Kingdom', ''),
(78, 'Georgia', ''),
(79, 'Ghana', ''),
(80, 'Gibraltar', ''),
(81, 'Guinea', ''),
(82, 'Guadeloupe', ''),
(83, 'Gambia', ''),
(84, 'Guinea-Bissau', ''),
(85, 'Equatorial Guinea', ''),
(86, 'Greece', ''),
(87, 'Grenada', ''),
(88, 'Greenland', ''),
(89, 'Guatemala', ''),
(90, 'French Guiana', ''),
(91, 'Guam', ''),
(92, 'Guyana', ''),
(93, 'Hong Kong', ''),
(94, 'Heard Island and McDonald Isla', ''),
(95, 'Honduras', ''),
(96, 'Croatia', '385'),
(97, 'Haiti', ''),
(98, 'Hungary', ''),
(99, 'Indonesia', ''),
(100, 'India', '91'),
(101, 'British Indian Ocean Territory', '246'),
(102, 'Ireland', ''),
(103, 'Iran', ''),
(104, 'Iraq', ''),
(105, 'Iceland', ''),
(106, 'Israel', ''),
(107, 'Italy', ''),
(108, 'Jamaica', ''),
(109, 'Jordan', ''),
(110, 'Japan', ''),
(111, 'Kazakstan', ''),
(112, 'Kenya', ''),
(113, 'Kyrgyzstan', ''),
(114, 'Cambodia', '855'),
(115, 'Kiribati', ''),
(116, 'Saint Kitts and Nevis', ''),
(117, 'South Korea', ''),
(118, 'Kuwait', ''),
(119, 'Laos', ''),
(120, 'Lebanon', ''),
(121, 'Liberia', ''),
(122, 'Libyan Arab Jamahiriya', ''),
(123, 'Saint Lucia', ''),
(124, 'Liechtenstein', ''),
(125, 'Sri Lanka', ''),
(126, 'Lesotho', ''),
(127, 'Lithuania', ''),
(128, 'Luxembourg', ''),
(129, 'Latvia', ''),
(130, 'Macao', ''),
(131, 'Morocco', ''),
(132, 'Monaco', ''),
(133, 'Moldova', ''),
(134, 'Madagascar', ''),
(135, 'Maldives', ''),
(136, 'Mexico', ''),
(137, 'Marshall Islands', ''),
(138, 'Macedonia', ''),
(139, 'Mali', ''),
(140, 'Malta', ''),
(141, 'Myanmar', ''),
(142, 'Mongolia', ''),
(143, 'Northern Mariana Islands', ''),
(144, 'Mozambique', ''),
(145, 'Mauritania', ''),
(146, 'Montserrat', ''),
(147, 'Martinique', ''),
(148, 'Mauritius', ''),
(149, 'Malawi', ''),
(150, 'Malaysia', ''),
(151, 'Mayotte', ''),
(152, 'Namibia', ''),
(153, 'New Caledonia', ''),
(154, 'Niger', ''),
(155, 'Norfolk Island', ''),
(156, 'Nigeria', ''),
(157, 'Nicaragua', ''),
(158, 'Niue', ''),
(159, 'Netherlands', ''),
(160, 'Norway', ''),
(161, 'Nepal', ''),
(162, 'Nauru', ''),
(163, 'New Zealand', ''),
(164, 'Oman', ''),
(165, 'Pakistan', '92'),
(166, 'Panama', ''),
(167, 'Pitcairn', ''),
(168, 'Peru', ''),
(169, 'Philippines', ''),
(170, 'Palau', ''),
(171, 'Papua New Guinea', ''),
(172, 'Poland', ''),
(173, 'Puerto Rico', ''),
(174, 'North Korea', ''),
(175, 'Portugal', ''),
(176, 'Paraguay', ''),
(177, 'Palestine', ''),
(178, 'French Polynesia', ''),
(179, 'Qatar', ''),
(180, 'RÃ©union', ''),
(181, 'Romania', ''),
(182, 'Russian Federation', ''),
(183, 'Rwanda', ''),
(184, 'Saudi Arabia', '966'),
(185, 'Sudan', ''),
(186, 'Senegal', ''),
(187, 'Singapore', ''),
(188, 'South Georgia and the South Sa', ''),
(189, 'Saint Helena', ''),
(190, 'Svalbard and Jan Mayen', ''),
(191, 'Solomon Islands', ''),
(192, 'Sierra Leone', ''),
(193, 'El Salvador', ''),
(194, 'San Marino', ''),
(195, 'Somalia', ''),
(196, 'Saint Pierre and Miquelon', ''),
(197, 'Sao Tome and Principe', ''),
(198, 'Suriname', ''),
(199, 'Slovakia', ''),
(200, 'Slovenia', ''),
(201, 'Sweden', ''),
(202, 'Swaziland', ''),
(203, 'Seychelles', ''),
(204, 'Syria', ''),
(205, 'Turks and Caicos Islands', ''),
(206, 'Chad', '235'),
(207, 'Togo', ''),
(208, 'Thailand', ''),
(209, 'Tajikistan', ''),
(210, 'Tokelau', ''),
(211, 'Turkmenistan', ''),
(212, 'East Timor', ''),
(213, 'Tonga', ''),
(214, 'Trinidad and Tobago', ''),
(215, 'Tunisia', ''),
(216, 'Turkey', ''),
(217, 'Tuvalu', ''),
(218, 'Taiwan', ''),
(219, 'Tanzania', ''),
(220, 'Uganda', ''),
(221, 'Ukraine', ''),
(222, 'United States Minor Outlying I', ''),
(223, 'Uruguay', ''),
(224, 'United States', ''),
(225, 'Uzbekistan', ''),
(226, 'Holy See (Vatican City State)', ''),
(227, 'Saint Vincent and the Grenadin', ''),
(228, 'Venezuela', ''),
(229, 'Virgin Islands, British', ''),
(230, 'Virgin Islands, U.S.', ''),
(231, 'Vietnam', ''),
(232, 'Vanuatu', ''),
(233, 'Wallis and Futuna', ''),
(234, 'Samoa', ''),
(235, 'Yemen', ''),
(236, 'Yugoslavia', ''),
(237, 'South Africa', ''),
(238, 'Zambia', ''),
(239, 'Zimbabwe', '');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `symbol`) VALUES
(1, 'USD', '$'),
(2, 'AED', 'د.إ'),
(3, 'INR', '₹'),
(4, 'PKR', '₨'),
(5, 'AFN', '؋;'),
(6, 'IRR', '﷼');

-- --------------------------------------------------------

--
-- Table structure for table `exchanges`
--

CREATE TABLE `exchanges` (
  `id` int(11) NOT NULL,
  `p_s` varchar(50) NOT NULL DEFAULT 'p',
  `curr1` varchar(255) NOT NULL,
  `qty` double NOT NULL,
  `per_price` double NOT NULL,
  `opr` varchar(100) NOT NULL DEFAULT '*',
  `curr2` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `details` text DEFAULT NULL,
  `khaata_exchange` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`khaata_exchange`)),
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_loading`
--

CREATE TABLE `general_loading` (
  `id` int(11) NOT NULL,
  `sr_no` varchar(50) NOT NULL,
  `p_id` varchar(255) NOT NULL,
  `p_type` varchar(255) NOT NULL,
  `p_date` varchar(255) NOT NULL,
  `p_branch` varchar(255) NOT NULL,
  `p_cr_acc` varchar(255) NOT NULL,
  `p_cr_acc_name` varchar(255) NOT NULL,
  `loading_details` longtext NOT NULL,
  `receiving_details` longtext NOT NULL,
  `bl_no` varchar(50) NOT NULL,
  `importer_details` longtext NOT NULL,
  `notify_party_details` longtext NOT NULL,
  `exporter_details` longtext NOT NULL,
  `goods_details` longtext NOT NULL,
  `shipping_details` longtext NOT NULL,
  `report` longtext NOT NULL,
  `agent_details` longtext NOT NULL,
  `attachments` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `general_loading`
--

INSERT INTO `general_loading` (`id`, `sr_no`, `p_id`, `p_type`, `p_date`, `p_branch`, `p_cr_acc`, `p_cr_acc_name`, `loading_details`, `receiving_details`, `bl_no`, `importer_details`, `notify_party_details`, `exporter_details`, `goods_details`, `shipping_details`, `report`, `agent_details`, `attachments`, `created_at`) VALUES
(2, '2', '1', 'booking', '2024-10-29', 'UAE-1', 'DC1', 'NAJEEBULLAH/QUETTA OFFICE', '{\"loading_date\":\"2024-09-23\",\"loading_country\":\"Afghanistan\",\"loading_port_name\":\"nimroz \"}', '{\"receiving_date\":\"2024-10-10\",\"receiving_country\":\"iran\",\"receiving_port_name\":\"bandar abbas\"}', 'az.0022', '{\"im_acc_id\":\"12\",\"im_acc_no\":\"dc12\",\"im_acc_name\":\"Sanjay Broker India\",\"im_acc_kd_id\":\"7\",\"im_acc_details\":\"DAMODAR EXPORT\\r\\nCountry: India \\r\\nCity: Mumbai\\r\\nState: Bombay\\r\\nAddress: B-27, Apmc Market I, Phase II, Sector 19, Vashi Navi Mumbai.400 705 GSTIN/UIN: 27AAQFD1336E1ZK\\r\\nFSSAI: 10019022010092\"}', '{\"np_acc_id\":\"2\",\"np_acc_no\":\"dp2\",\"np_acc_name\":\"PURCHASE&SALES \",\"np_acc_kd_id\":\"3\",\"np_acc_details\":\"DAMAAN GENERAL TRADING L L C\\r\\nCountry: United Arab Emirates\\r\\nCity: dubai\\r\\nState: dubai\\r\\nAddress: OFFICE NO 201 HADTOOR BUNNN\\r\\nLicense: 1099620\\r\\nWEIGHT: 104127559300003\"}', '{\"xp_acc_id\":\"3\",\"xp_acc_no\":\"dg3\",\"xp_acc_name\":\"Noor Muhammad\",\"xp_acc_kd_id\":\"9\",\"xp_acc_details\":\"AYAZ NOORI L TD,.\\r\\nCountry: Afghanistan \\r\\nCity: Rose\\r\\nState: Brody\\r\\nAddress: Nnnn\\r\\nLicense: 49443\\r\\nNTN: 9006069785\"}', '{\"goods_id\":\"12\",\"quantity_no\":\"540\",\"quantity_name\":\"bags\",\"size\":\"\",\"brand\":\"\",\"origin\":\"\",\"net_weight\":\"27000\",\"gross_weight\":\"27150\",\"container_no\":\"afg 48775\",\"container_name\":\"truck ou\"}', '{\"shipping_name\":\"AYEZ NOORI LTD\",\"shipping_phone\":\"+93704862191\",\"shipping_whatsapp\":\"+93704862191\",\"shipping_email\":\"NOOR@dgt.llc\",\"shipping_address\":\"NIMROZ AFGHISTAN\",\"transfer_by\":\"road\"}', '5 days free ', '{\"ag_acc_no\":\"DG3\",\"ag_name\":\"Noor Muhammad\",\"ag_id\":\"noor@dgt.llc\",\"row_id\":\"2\",\"transferred\":false,\"permission_to_edit\":\"yes\",\"ag_billNumber\":1,\"received_date\":\"2024-09-23\",\"clearing_date\":\"2024-09-24\",\"bill_of_entry_no\":\"6050\",\"loading_truck_number\":\"TRUCK ;48775\",\"truck_returning_date\":\"2024-11-05\",\"report\":\"11 days free \",\"attachments\":[],\"cargo_transfer_warehouse\":null}', '[]', '2024-10-30 09:07:48'),
(7, '3', '2', 'booking', '2024-10-29', 'UAE-1', 'DC1', 'NAJEEBULLAH/QUETTA OFFICE', '{\"loading_date\":\"2024-09-23\",\"loading_country\":\"Afghanistan\",\"loading_port_name\":\"nimroz \"}', '{\"receiving_date\":\"2024-10-04\",\"receiving_country\":\"iran\",\"receiving_port_name\":\"bandar abbas\"}', 'az.0025', '{\"im_acc_id\":\"12\",\"im_acc_no\":\"dc12\",\"im_acc_name\":\"Sanjay Broker India\",\"im_acc_kd_id\":\"7\",\"im_acc_details\":\"DAMODAR EXPORT\\r\\nCountry: India \\r\\nCity: Mumbai\\r\\nState: Bombay\\r\\nAddress: B-27, Apmc Market I, Phase II, Sector 19, Vashi Navi Mumbai.400 705 GSTIN/UIN: 27AAQFD1336E1ZK\\r\\nFSSAI: 10019022010092\"}', '{\"np_acc_id\":\"2\",\"np_acc_no\":\"dp2\",\"np_acc_name\":\"PURCHASE&SALES \",\"np_acc_kd_id\":\"3\",\"np_acc_details\":\"DAMAAN GENERAL TRADING L L C\\r\\nCountry: United Arab Emirates\\r\\nCity: dubai\\r\\nState: dubai\\r\\nAddress: OFFICE NO 201 HADTOOR BUNNN\\r\\nLicense: 1099620\\r\\nWEIGHT: 104127559300003\"}', '{\"xp_acc_id\":\"3\",\"xp_acc_no\":\"dg3\",\"xp_acc_name\":\"Noor Muhammad\",\"xp_acc_kd_id\":\"9\",\"xp_acc_details\":\"AYAZ NOORI L TD,.\\r\\nCountry: Afghanistan \\r\\nCity: Rose\\r\\nState: Brody\\r\\nAddress: Nnnn\\r\\nLicense: 49443\\r\\nNTN: 9006069785\"}', '{\"goods_id\":\"12\",\"quantity_no\":\"540\",\"quantity_name\":\"PP BAGS\",\"size\":\"00\",\"brand\":\"DGTLLC\",\"origin\":\"AFGHISTAN\",\"net_weight\":\"27000\",\"gross_weight\":\"27054\",\"container_no\":\"afg 48775\",\"container_name\":\"truck ou\"}', '{\"shipping_name\":\"AYEZ NOORI LTD\",\"shipping_phone\":\"+93704862191\",\"shipping_whatsapp\":\"+93704862191\",\"shipping_email\":\"NOOR@dgt.llc\",\"shipping_address\":\"NIMROZ AFGHISTAN\",\"transfer_by\":\"road\"}', '5 days free ', '{\"ag_acc_no\":\"DG3\",\"ag_name\":\"Noor Muhammad\",\"ag_id\":\"noor@dgt.llc\",\"row_id\":\"2\",\"transferred\":false,\"permission_to_edit\":\"yes\",\"ag_billNumber\":2,\"received_date\":\"2024-10-30\",\"clearing_date\":\"2024-10-30\",\"bill_of_entry_no\":\"6050\",\"loading_truck_number\":\"TRUCK ;48775\",\"truck_returning_date\":\"2024-10-31\",\"report\":\"11 days free \",\"attachments\":[],\"cargo_transfer_warehouse\":null}', '[]', '2024-10-30 09:24:03');

-- --------------------------------------------------------

--
-- Table structure for table `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goods`
--

INSERT INTO `goods` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'PISTACHIOS KERNEL', '2024-01-04 20:05:46', '2024-10-29 20:43:09'),
(2, 'BADAM', '2024-01-04 20:28:37', '2024-01-04 21:02:20'),
(3, 'WALNUT KERNELS', '2024-01-04 23:55:42', '2024-10-29 20:46:54'),
(4, 'BETEL NUTS', '2024-01-05 00:02:27', '2024-10-30 00:27:37'),
(5, 'ALMOND KERNELS ', '2024-01-05 00:06:59', '2024-10-30 00:22:04'),
(6, 'BLACK PAPER', '2024-01-09 18:42:25', '2024-10-30 00:24:20'),
(7, 'DRY PIGS (END)', '2024-01-10 13:14:02', '2024-01-10 13:14:27'),
(8, 'ALMOND INSHELL ', '2024-01-10 13:15:01', '2024-10-30 15:12:37'),
(9, 'WALNUT IN SHELL', '2024-01-10 13:44:04', '2024-10-30 00:13:16'),
(10, 'BLACK PAPER', '2024-01-12 20:24:11', NULL),
(11, ' FRESH ONIONS', '2024-01-14 17:55:10', NULL),
(12, 'BSSL SEED ', '2024-02-07 13:26:01', '2024-10-30 13:07:02'),
(13, 'CARDAMOM GUATEMALA ', '2024-04-29 17:55:45', '2024-06-03 13:37:37'),
(15, 'CARDAMOM AKBAR ', '2024-06-03 13:21:19', '2024-10-30 15:11:21'),
(16, 'PISTACHIOS', '2024-10-30 00:15:59', '2024-10-30 00:17:07');

-- --------------------------------------------------------

--
-- Table structure for table `good_details`
--

CREATE TABLE `good_details` (
  `id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `size` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `origin` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `good_details`
--

INSERT INTO `good_details` (`id`, `goods_id`, `size`, `brand`, `origin`, `created_at`) VALUES
(1, 1, '000', 'DGT.LLC', 'IRAN', '2024-10-29 16:43:09'),
(2, 3, 'XIN2 HALVES:90%', 'DGTLLC', 'CHIAN', '2024-10-29 16:45:18'),
(3, 3, '185 /HALVE:90%(EXTRALIGHT90%', 'DGTLLC', 'CHIAN', '2024-10-29 16:46:54'),
(4, 12, '00', 'DGTLLC', 'AFGHISTAN', '2024-10-29 16:47:37'),
(5, 9, 'Jambo ', 'Shira', 'U$', '2024-10-29 19:26:05'),
(6, 9, '32/34', 'No ', 'Chii', '2024-10-29 20:11:40'),
(7, 9, '34/36', 'No', 'Chil', '2024-10-29 20:11:58'),
(8, 9, '30/32', 'No', 'Chil', '2024-10-29 20:12:16'),
(9, 9, '185/32mm', 'No', 'Chian', '2024-10-29 20:12:39'),
(10, 9, '185/31mm', 'No', 'Chian ', '2024-10-29 20:13:16'),
(11, 6, '5mm', 'No', 'VIETNAM', '2024-10-29 20:14:56'),
(12, 6, 'Ahah', 'No', 'Brazil', '2024-10-29 20:15:17'),
(13, 16, '26/28 Mother Bhai', 'No', 'Iran', '2024-10-29 20:15:59'),
(14, 16, '28/30 Ahmed Agha', 'No', 'Iran', '2024-10-29 20:16:28'),
(15, 16, '24/26 mad guy', 'No', 'Iran', '2024-10-29 20:17:07'),
(16, 5, '18/20NPX', 'No', 'U$', '2024-10-29 20:18:39'),
(17, 5, '20/22NPX', 'No', 'U$', '2024-10-29 20:18:56'),
(18, 5, '22/24 NPX', 'No', 'U$', '2024-10-29 20:19:26'),
(19, 5, '24/26NPX', 'No', 'U$', '2024-10-29 20:20:00'),
(20, 5, '26/28 NPX', 'No', 'U$', '2024-10-29 20:20:33'),
(21, 5, '28/30 NPX', 'No', 'U$', '2024-10-29 20:21:15'),
(22, 5, '30/32 NPX', 'No', 'U$', '2024-10-29 20:21:39'),
(23, 5, '32/34NPX', 'No', 'U$', '2024-10-29 20:22:04'),
(24, 4, '80/85', 'DGTLLC', 'Malaysia', '2024-10-29 20:26:44'),
(25, 4, '90/95', 'DGTLLC', 'Malaysia', '2024-10-29 20:27:21'),
(26, 4, '60/65', 'No', 'Malaysia', '2024-10-29 20:27:37'),
(27, 12, '0', 'DGT', 'AFGHISTAN', '2024-10-30 09:07:02'),
(28, 15, '8mm', 'KBR', 'India', '2024-10-30 11:10:50'),
(29, 15, '7mm', 'KBR', 'India ', '2024-10-30 11:11:05'),
(30, 15, 'SB', 'SB', 'U$', '2024-10-30 11:11:21'),
(31, 8, '18/20', 'N', 'U$', '2024-10-30 11:12:37');

-- --------------------------------------------------------

--
-- Table structure for table `khaata`
--

CREATE TABLE `khaata` (
  `id` int(11) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `acc_for` varchar(255) NOT NULL DEFAULT 'client',
  `khaata_no` varchar(255) NOT NULL,
  `khaata_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `contact_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`contact_details`)),
  `bank_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bank_details`)),
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khaata`
--

INSERT INTO `khaata` (`id`, `is_active`, `branch_id`, `cat_id`, `acc_for`, `khaata_no`, `khaata_name`, `email`, `phone`, `image`, `contact_details`, `bank_details`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 1, 1, 'client', 'dc1', 'NAJEEBULLAH/QUETTA OFFICE', 'Najeeb@dgt.llc', '+971561202687', NULL, '{\"full_name\":\"ASMATULLAH \",\"father_name\":\"ABDULLAH\",\"gender\":\"male\",\"identity\":\"cnic\",\"idn_no\":\"520115023741\",\"idn_reg\":\"2020-11-23\",\"idn_expiry\":\"2030-11-23\",\"idn_country\":\"United Arab Emirates\",\"country\":\"Pakistan\",\"state\":\"CHAMAN\",\"city\":\"CHAMAN\",\"address\":\"CHAMAN \",\"postcode\":\"+92812820432\",\"mobile\":\"+923337764088\",\"phone\":\"+923168000339\",\"whatsapp\":\"+923337764088\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"1\"}', '{\"acc_no\":\"PK76ALFH0903001006073400\",\"acc_name\":\"NAJEEBULLAH GENERAL TRADERS\",\"company\":\"NAJEEBULLAH GENERAL TRADERS\",\"iban\":\"PK76ALFH0903001006073400\",\"branch_code\":\"0000\",\"currency\":\"PKR\",\"country\":\"NAJEEBULLAH GENERAL TRADERS\",\"state\":\"BALOCHISTAN\",\"city\":\"QUETTA\",\"address\":\"HHHH\",\"bankDetailsSubmit\":\"\",\"hidden_id\":\"1\",\"hidden_id_details\":\"0\",\"hidden_type\":\"warehouse\"}', '2024-10-29 21:01:17', 1, '2024-10-29 21:37:58', 1),
(2, 1, 1, 5, 'client', 'DP2', 'PURCHASE&SALES ', 'contacts@dgt.llc', '+9715448166664', NULL, '{\"full_name\":\"ASMATULLAH \",\"father_name\":\"ABDULLAH\",\"gender\":\"male\",\"identity\":\"uae\",\"idn_no\":\"784-1987-8811995-7\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"AS RAL\",\"postcode\":\"+97142278608\",\"mobile\":\"1544816664\",\"phone\":\"1544816664\",\"whatsapp\":\"+971544816664\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"2\"}', '{\"acc_no\":\"02-01-01-120311-105-1075750\",\"acc_name\":\"DAMAAN GENERAL TRADING LLC\",\"company\":\"DAMAAN GENERAL TRADING LLC\",\"iban\":\"AE14 0290 1953 1050 1075 750\",\"branch_code\":\"Main Branch, Dubai\",\"currency\":\"AED\",\"country\":\"DAMAAN GENERAL TRADING LLC\",\"state\":\"DUBAI\",\"city\":\"DUBAIA\",\"address\":\"DUBAI\",\"bankDetailsSubmit\":\"\",\"hidden_id\":\"2\",\"hidden_id_details\":\"0\",\"hidden_type\":\"warehouse\"}', '2024-10-29 21:25:40', 1, '2024-10-29 21:38:09', 1),
(3, 1, 1, 7, 'agent', 'DG3', 'Noor Muhammad', 'NOOR@DGT.LLC', '+93790107000', NULL, NULL, NULL, '2024-10-29 21:40:19', 1, NULL, NULL),
(4, 1, 1, 5, 'agent', 'DG4', 'IMRAN ALI', 'IM@DGT.LLC', '+971557649000', NULL, '{\"full_name\":\"IMRAN ALI\",\"father_name\":\"SYED ANWAR ALI\",\"gender\":\"male\",\"identity\":\"uae\",\"idn_no\":\"784-1979-6876530-1\",\"idn_reg\":\"2023-02-13\",\"idn_expiry\":\"2023-02-12\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"SEA, AIR, LAND CARGO, TRANSPORTATION Deira Al-Ras Dubai U.A.E. Al-Sheikh Building Office #102 P.O Box. 64959\",\"postcode\":\"+9710000\",\"mobile\":\"+971557649000\",\"phone\":\"+971557649000\",\"whatsapp\":\"+971557649000\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"4\"}', NULL, '2024-10-29 21:44:45', 1, '2024-10-29 21:57:19', 1),
(5, 1, 1, 1, 'client', 'DC5', 'SAF', 'NI@GMAIL.COM', '+97140000', NULL, NULL, NULL, '2024-10-29 22:19:14', 1, '2024-10-29 22:19:36', 1),
(6, 1, 1, 4, 'bank', 'DB6', 'Habib Bank AG Zurich', 'coounts@dgt.llc', '+971800777', NULL, NULL, NULL, '2024-10-29 22:56:58', 1, '2024-10-30 00:29:28', 1),
(7, 1, 1, 4, 'bank', 'DB7', 'Mashreq Business Banking', 'accounts@dgt.llc', '0000', NULL, NULL, NULL, '2024-10-29 22:58:03', 1, '2024-10-30 00:31:17', 1),
(8, 1, 1, 4, 'bank', 'DB8', 'IE bank asmatullah personal', 'accounts@dgt.llc', '0000', NULL, NULL, NULL, '2024-10-29 22:58:57', 1, '2024-10-30 00:32:17', 1),
(9, 1, 1, 5, 'agent', 'DG9', 'Nadeem Chaman office', 'chaman@dgt.llc', '00926614073', NULL, NULL, NULL, '2024-10-29 23:01:12', 1, NULL, NULL),
(10, 1, 1, 5, 'client', 'Du10', 'Dubai office expenses', 'dgtllc@dgt.llc', '+97140000', NULL, NULL, NULL, '2024-10-29 23:10:04', 1, NULL, NULL),
(11, 1, 1, 5, 'client', 'Du11', 'TrazetAccounts', 'dgtllc@dgt.llc', '40000', NULL, NULL, NULL, '2024-10-29 23:14:14', 1, '2024-10-29 23:14:30', 1),
(12, 1, 1, 1, 'client', 'DC12', 'Sanjay Broker India', 'damodarexports43@gmail.com', '+91 88797 62371', NULL, NULL, NULL, '2024-10-29 23:19:41', 1, '2024-10-29 23:22:49', 1),
(13, 1, 1, 5, 'client', 'DU13', 'FAREEDULLAH PRESIDENT ', 'fareed@dgt.com', '00971000000', NULL, NULL, NULL, '2024-10-30 00:34:14', 1, '2024-10-30 00:34:53', 1),
(14, 1, 1, 5, 'client', 'DU14', 'DGTLLC VAT ACCOUNTS ', 'dgtllc@dgt.llc', '40000', NULL, NULL, NULL, '2024-10-30 00:37:06', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `khaata_details`
--

CREATE TABLE `khaata_details` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'company',
  `khaata_id` int(11) NOT NULL,
  `json_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khaata_details`
--

INSERT INTO `khaata_details` (`id`, `type`, `khaata_id`, `json_data`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'company', 1, '{\"owner_name\":\"NAJEEBDULLAH\",\"company_name\":\"NAJEEBULLAH GENERAL TRADERS\",\"business_title\":\"IMPOTR EXPOTR\",\"country\":\"PAKISTAN\",\"state\":\"CHAMAN\",\"city\":\"CHAMAN\",\"address\":\"MAL ROAD CHAMAN\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"1\",\"hidden_id_details\":\"1\",\"hidden_type\":\"company\"}', '2024-10-29 21:03:10', 1, '2024-10-29 21:15:19', 1),
(2, 'company', 1, '{\"owner_name\":\"ASMATULLAH ABDULLAH\",\"company_name\":\"T ,NAJEEB ULLAH GENERAL TRADERS\",\"business_title\":\"IMPOTR EXPOTR\",\"country\":\"Pakistan\",\"state\":\"CHAMAN\",\"city\":\"CHAMAN\",\"address\":\"CHAMAN \",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"1\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-10-29 21:09:15', 1, NULL, NULL),
(3, 'company', 2, '{\"owner_name\":\"ASMATULLAH ABDULLAH\",\"company_name\":\"DAMAAN GENERAL TRADING L L C\",\"business_title\":\"IMPOTR EXPOTR\",\"indexes1\":[\"License\",\"WEIGHT\"],\"vals1\":[\"1099620\",\"104127559300003\"],\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"OFFICE NO 201 HADTOOR BUNNN\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"2\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-10-29 21:36:07', 1, NULL, NULL),
(4, 'company', 4, '{\"owner_name\":\"IMRAN AIL\",\"company_name\":\"EMSS SHIPPING L.L.C\",\"business_title\":\"AG\",\"indexes1\":[\"License\",\"WEIGHT\"],\"vals1\":[\"656211\",\"u00a0100427791700003\"],\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"SEA, AIR, LAND CARGO, TRANSPORTATION Deira Al-Ras Dubai U.A.E. Al-Sheikh Building Office #102 P.O Box. 64959\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"4\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-10-29 21:51:22', 1, NULL, NULL),
(5, 'company', 9, '{\"owner_name\":\"Naseebullah\",\"company_name\":\"Abdullah\",\"business_title\":\"Ag\",\"country\":\"NAJEEBULLAH GENERAL TRADERS\",\"state\":\"Baluchi store\",\"city\":\"Chaman\",\"address\":\"Hi what Plaza floor number2office 1\",\"indexes2\":[\"WhatsApp\"],\"vals2\":[\"00923023988899\"],\"companyDetailsSubmit\":\"\",\"hidden_id\":\"9\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-10-29 23:05:56', 1, NULL, NULL),
(6, 'company', 11, '{\"owner_name\":\"Asmatullah \",\"company_name\":\"DAMAAN GENERAL TRADING L L C\",\"business_title\":\"DAMAAN GENERAL TRADING L L C\",\"indexes1\":[\"License\",\"WEIGHT\"],\"vals1\":[\"1099620\",\"104127559300003\"],\"country\":\"United Arab Emirates\",\"state\":\"Dubai\",\"city\":\"dubai\",\"address\":\"AS RAL\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"11\",\"hidden_id_details\":\"6\",\"hidden_type\":\"company\"}', '2024-10-29 23:14:58', 1, '2024-10-29 23:16:16', 1),
(7, 'company', 12, '{\"owner_name\":\"Sanji\",\"company_name\":\"DAMODAR EXPORT\",\"business_title\":\"Import exports \",\"indexes1\":[\"FSSAI\"],\"vals1\":[\"10019022010092\"],\"country\":\"India \",\"state\":\"Bombay\",\"city\":\"Mumbai\",\"address\":\"B-27, Apmc Market I, Phase II, Sector 19, Vashi Navi Mumbai.400 705 GSTIN/UIN: 27AAQFD1336E1ZK\",\"indexes2\":[\"Email\"],\"vals2\":[\"damodarexports43@gmail.com\"],\"companyDetailsSubmit\":\"\",\"hidden_id\":\"12\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-10-29 23:22:36', 1, NULL, NULL),
(8, 'company', 14, '{\"owner_name\":\"ASMATULLAH ABDULLAH\",\"company_name\":\"DAMAAN GENERAL TRADING L L C\",\"business_title\":\"Import export \",\"indexes1\":[\"License\",\"WEIGHT\"],\"vals1\":[\"1099620\",\"10000\"],\"country\":\"United Arab Emirates\",\"state\":\"Dear\",\"city\":\"dubai\",\"address\":\"AS RAL\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"14\",\"hidden_id_details\":\"8\",\"hidden_type\":\"company\"}', '2024-10-30 00:38:24', 1, '2024-10-30 00:39:08', 1),
(9, 'company', 3, '{\"owner_name\":\"Noor Muhammad\",\"company_name\":\"AYAZ NOORI L TD,.\",\"business_title\":\"agen\",\"indexes1\":[\"License\",\"NTN\"],\"vals1\":[\"49443\",\"9006069785\"],\"country\":\"Afghanistan \",\"state\":\"Brody\",\"city\":\"Rose\",\"address\":\"Nnnn\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"3\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-10-30 00:51:58', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `navbar`
--

CREATE TABLE `navbar` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `is_view` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `navbar`
--

INSERT INTO `navbar` (`id`, `parent_id`, `label`, `url`, `position`, `is_view`) VALUES
(1, 0, 'ENTERIES', '#', 2, 1),
(2, 1, 'USERS', 'users', 1, 1),
(4, 13, 'AFGHAN INVOICES', '#', 2, 1),
(5, 1, 'CATEGORIES', 'categories', 3, 1),
(6, 1, 'NEW ACCOUNT', 'khaata', 4, 0),
(7, 1, 'GOODS', 'goods', 5, 1),
(8, 0, 'EMAIL', '#', 10, 1),
(9, 8, 'COMPOSE', 'compose', 1, 1),
(10, 8, 'INBOX', 'inbox', 2, 1),
(11, 0, 'DAILY PAYMNET ENTRY', '#', 3, 0),
(12, 0, 'LEDGER', '#', 4, 0),
(14, 12, 'LEDGER CATEGORIES', 'ledger-categories', 2, 1),
(15, 16, 'PYAMENT ', 'roznamcha-banks', 2, 1),
(16, 11, 'ROZNAMCHA MONEY', '#', 1, 0),
(17, 31, 'Exchanges Entry', 'exchanges', 1, 1),
(18, 31, 'Exchanges Stock', 'exchanges-stock', 2, 1),
(19, 16, 'Office Expense', 'roznamcha-office', 4, 1),
(20, 16, 'ROZNAMCHA', 'roznamcha', 1, 0),
(22, 21, 'PURCAHSE 20% PAYMENT', 'roznamcha', 1, 0),
(23, 21, 'PURCAHSE 80% PAYMENT', 'roznamcha', 2, 0),
(24, 21, 'PURCAHSE 100% PAYMENT', 'roznamcha', 3, 0),
(25, 21, 'PURCAHSE FULL PAYMENT', '#.', 4, 0),
(26, 11, 'SALE PAYMENTS', '#', 7, 0),
(27, 26, 'SALE 20% PAYMENT', '#.', 1, 1),
(28, 26, 'SALE 80% PAYMENT', '#.', 2, 1),
(29, 26, 'SALE 100% PAYMENT', '#.', 3, 1),
(30, 26, 'SALE FULL PAYMENTS', '#.', 4, 1),
(31, 11, 'Exch.', '#.', 3, 0),
(32, 11, 'Clearing Bill', '#', 4, 0),
(33, 32, 'Carry Bill', 'carry-bill', 1, 1),
(34, 13, 'DRAFT INVOICES', 'CUSTOM', 1, 0),
(35, 0, 'CUSTOM', 'CUSTOM', 8, 0),
(36, 35, 'AFGHAN', '#', 1, 0),
(37, 36, 'DRAFT INVOICES', 'draft-invoices', 2, 1),
(38, 58, 'INVOCE ENTRY CUSTOM', '#', 1, 1),
(39, 58, 'BILL ENTRY', '#', 2, 1),
(40, 0, 'PURCHASE/SALES', 'PURCHASE/SALES', 5, 0),
(41, 0, 'VAT/TAX ', 'AVAT/TAX', 9, 0),
(42, 41, 'VAT PURCHASES', 'AVAT/TAX', 1, 1),
(43, 41, 'VAT SALES ', 'AVAT/TAX', 2, 1),
(44, 45, 'Bill Transfer Form', 'purchases-bill-transfer', 2, 1),
(45, 40, 'PURCHASES', '#', 1, 0),
(46, 45, 'Final Bill Payment', 'purchase-final', 3, 1),
(48, 58, 'PACKING LIST/CUSTO', '#', 3, 1),
(49, 41, 'SLAES INVOCE', '#', 3, 1),
(50, 41, 'PURCHASE.INVOCE', 'VAT/TAX', 4, 1),
(51, 12, ' LEDGER ACCOUNT', 'ledger', 1, 1),
(52, 45, 'PURCHASE ORDERS', 'purchases', 1, 1),
(53, 40, 'SALES', '#', 2, 0),
(54, 53, 'SALE ORDERS', 'sales', 1, 1),
(55, 53, 'BILL TRANSFER FORM', '#', 2, 1),
(56, 53, 'FULL PAYMENT FORM', '#', 3, 1),
(57, 36, 'AFGHAN INVOICES ENTERY', 'afghan-invoices', 1, 1),
(58, 35, 'CLEARING', '#', 2, 0),
(59, 11, 'PURCHASE PAYMENT ENTRY', '#', 6, 1),
(60, 59, 'ADVANCE PAYMENT ENTRY', 'purchase-advance', 1, 1),
(61, 59, 'REMAINING BALANCE PAYMENT ENTRY', 'purchase-remaining', 2, 1),
(62, 59, 'CREDIT PAYMENT ENTRY', 'purchase-credit', 3, 1),
(63, 0, 'SHIP', '#', 6, 0),
(64, 63, 'LOADING', '#', 1, 1),
(65, 64, 'LOADING TRANSFER', 'loading-transfer', 2, 1),
(66, 63, 'AGENT', '#', 2, 1),
(67, 0, 'GOOD STOCK', '#', 7, 1),
(68, 67, 'GENERAL STOCK FORM', 'general-stock-form', 1, 1),
(69, 67, 'CONFRIM STOCK', 'confirm-stock', 2, 1),
(70, 64, 'GENERAL LOADING', 'general-loading', 1, 1),
(71, 66, 'CUSTOM CLEARING FORM', 'agent-form', 1, 0),
(72, 66, 'AGENT PAYMENTS FORM', 'agent-payments-form', 2, 1),
(73, 63, 'CARGO LANE', '#', 3, 1),
(74, 73, 'LANE', 'cargo-lane', 1, 1),
(75, 73, 'FINAL LANE', 'cargo-final-lane', 2, 1),
(76, 0, 'G.ALL FAR', '#', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_pays`
--

CREATE TABLE `purchase_pays` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'adv' COMMENT 'adv,rem,etc.',
  `purchase_id` int(11) NOT NULL,
  `dr_khaata_no` varchar(255) NOT NULL,
  `dr_khaata_id` int(11) NOT NULL,
  `cr_khaata_no` varchar(255) NOT NULL,
  `cr_khaata_id` int(11) NOT NULL,
  `currency1` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `currency2` varchar(255) NOT NULL,
  `rate` double NOT NULL,
  `opr` varchar(100) NOT NULL,
  `final_amount` double NOT NULL,
  `transfer_date` date NOT NULL,
  `report` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `urdu_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `urdu_name`) VALUES
(1, 'admin', 'ایڈمن'),
(2, 'manager', 'مینیجر'),
(3, 'munshi', 'منشی'),
(4, 'staff', 'ملازم'),
(5, 'agent', 'کلئیرنگ ایجنٹ'),
(6, 'customer', 'کسٹمر');

-- --------------------------------------------------------

--
-- Table structure for table `roznamchaas`
--

CREATE TABLE `roznamchaas` (
  `r_id` int(11) NOT NULL,
  `r_type` varchar(255) NOT NULL,
  `dr_cr` varchar(100) DEFAULT NULL,
  `khaata_id` int(11) NOT NULL,
  `khaata_no` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `is_qty` int(11) NOT NULL DEFAULT 0,
  `qty` double DEFAULT NULL,
  `per_price` double DEFAULT NULL,
  `operator` varchar(100) NOT NULL DEFAULT '''*''',
  `currency` varchar(255) DEFAULT NULL,
  `c_name` varchar(255) DEFAULT NULL COMMENT 'cash',
  `mobile` varchar(255) DEFAULT NULL,
  `transfered_from` varchar(255) DEFAULT NULL,
  `transfered_from_id` int(11) NOT NULL DEFAULT 0,
  `khaata_branch_id` int(11) NOT NULL,
  `branch_serial` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `r_date` date NOT NULL,
  `roznamcha_no` varchar(255) NOT NULL,
  `r_name` varchar(255) NOT NULL,
  `r_no` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `r_date_payment` date DEFAULT NULL,
  `img` text DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roznamchaas`
--

INSERT INTO `roznamchaas` (`r_id`, `r_type`, `dr_cr`, `khaata_id`, `khaata_no`, `amount`, `is_qty`, `qty`, `per_price`, `operator`, `currency`, `c_name`, `mobile`, `transfered_from`, `transfered_from_id`, `khaata_branch_id`, `branch_serial`, `branch_id`, `cat_id`, `r_date`, `roznamcha_no`, `r_name`, `r_no`, `details`, `bank_id`, `r_date_payment`, `img`, `username`, `user_id`, `created_at`, `updated_at`, `updated_by`) VALUES
(1, 'Business', 'dr', 1, 'DC1', 2669598, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'purchase_booking', 1, 1, 1, 1, 1, '2024-10-30', '1', ' P.B', '1', 'Dr. A/c:DP2 ENTRY:1 GOODS:BSSL SEED  COUNTRY:AFGHISTAN ALLOT:12CONTAINER T.Qty:6480 T.KGs:324648 RATE:2250 T.AMNT:729000USD EXCH.:AED', NULL, NULL, NULL, 'Admin', 1, '2024-10-30 00:41:00', NULL, NULL),
(2, 'Business', 'cr', 2, 'DP2', 2669598, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'purchase_booking', 1, 1, 2, 1, 5, '2024-10-30', '1', ' P.B', '1', 'Cr. A/c:DC1 ENTRY:1 GOODS:BSSL SEED  COUNTRY:AFGHISTAN ALLOT:12CONTAINER T.Qty:6480 T.KGs:324648 RATE:2250 T.AMNT:729000USD EXCH.:AED', NULL, NULL, NULL, 'Admin', 1, '2024-10-30 00:41:00', NULL, NULL),
(3, 'Business', 'dr', 1, 'DC1', 889866, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'purchase_booking', 2, 1, 3, 1, 1, '2024-10-30', '2', ' P.B', '2', 'Dr. A/c:DP2 ENTRY:1 GOODS:BSSL SEED  COUNTRY:AFGHISTAN ALLOT:4CONTAINER T.Qty:2160 T.KGs:108216 RATE:2250 T.AMNT:243000USD EXCH.:AED', NULL, NULL, NULL, 'admin', 1, '2024-10-30 13:23:09', NULL, NULL),
(4, 'Business', 'cr', 2, 'DP2', 889866, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'purchase_booking', 2, 1, 4, 1, 5, '2024-10-30', '2', ' P.B', '2', 'Cr. A/c:DC1 ENTRY:1 GOODS:BSSL SEED  COUNTRY:AFGHISTAN ALLOT:4CONTAINER T.Qty:2160 T.KGs:108216 RATE:2250 T.AMNT:243000USD EXCH.:AED', NULL, NULL, NULL, 'admin', 1, '2024-10-30 13:23:09', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roznamchaas_deleted`
--

CREATE TABLE `roznamchaas_deleted` (
  `r_id` int(11) NOT NULL,
  `r_type` varchar(255) NOT NULL,
  `dr_cr` varchar(100) DEFAULT NULL,
  `khaata_id` int(11) NOT NULL,
  `khaata_no` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `qty` double DEFAULT NULL,
  `per_price` double DEFAULT NULL,
  `operator` varchar(100) NOT NULL DEFAULT '''*''',
  `currency` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `transfered_from` varchar(255) DEFAULT NULL,
  `transfered_from_id` int(11) NOT NULL DEFAULT 0,
  `khaata_branch_id` int(11) NOT NULL,
  `branch_serial` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `r_date` date NOT NULL,
  `roznamcha_no` varchar(255) NOT NULL,
  `r_name` varchar(255) NOT NULL,
  `r_no` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `r_date_payment` date DEFAULT NULL,
  `img` text DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sent_emails`
--

CREATE TABLE `sent_emails` (
  `id` int(11) NOT NULL,
  `recipients` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `static_types`
--

CREATE TABLE `static_types` (
  `id` int(11) NOT NULL,
  `type_for` varchar(255) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `other1` varchar(255) DEFAULT NULL,
  `other2` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `static_types`
--

INSERT INTO `static_types` (`id`, `type_for`, `type_name`, `details`, `other1`, `other2`) VALUES
(1, 'purchases', 'Advance 20%', NULL, NULL, NULL),
(2, 'purchases', 'Remaining 80%', NULL, NULL, NULL),
(3, 'purchases', 'Full Payment', NULL, NULL, NULL),
(4, 'khaata', 'Extra', 'Extra Party', NULL, NULL),
(5, 'khaata', 'Bank', 'Bank', NULL, NULL),
(6, 'khaata', 'Warehouse', 'Warehouse ', NULL, NULL),
(9, 'purchase_transfer', 'adv', 'Advance 20%', NULL, NULL),
(10, 'purchase_transfer', 'loading', 'Loading Form', NULL, NULL),
(11, 'purchase_transfer', 'custom', 'Custom Clearing', NULL, NULL),
(12, 'purchase_transfer', 'rem', 'Remaining 80%', NULL, NULL),
(13, 'purchase_transfer', 'full', 'Full Payment', NULL, NULL),
(14, 'purchase_transfer', 'ware', 'Warehouse', NULL, NULL),
(15, 'agent_cc', 'Transit', NULL, NULL, NULL),
(16, 'agent_cc', 'Import', NULL, NULL, NULL),
(17, 'contacts2', 'NTN', 'NTN Number', NULL, NULL),
(18, 'contacts2', 'FSSAI', 'FSSAI License No.', NULL, NULL),
(19, 'contacts2', 'WEIGHT', 'WEIGHT No.', NULL, NULL),
(20, 'contacts2', 'License', 'License No.', NULL, NULL),
(21, 'contacts2', 'IEC', 'IEC No.', NULL, NULL),
(22, 'contacts2', 'ST', 'Sale Tax No.', NULL, NULL),
(23, 'contacts2', 'GST', 'GST No.', NULL, NULL),
(24, 'contacts', 'Mobile', 'Mobile No.', 'tel://', NULL),
(25, 'contacts', 'Phone', 'Phone No.', 'tel://', NULL),
(26, 'contacts', 'WhatsApp', 'WhatsApp', 'https://wa.me/', NULL),
(27, 'contacts', 'Office', 'Office No.', 'tel://', NULL),
(28, 'contacts', 'Email', 'Email', 'mailto://', 'E.'),
(30, 'r_type', 'Business', 'Business Roznamcha', NULL, NULL),
(31, 'r_type', 'Bank', 'Bank Roznamcha', NULL, NULL),
(32, 'r_type', 'Bill', 'Bill Roznamcha', NULL, NULL),
(33, 'r_type', 'Cash', 'Cash Flow', NULL, NULL),
(34, 'purchase_add', 'Condition', 'Goods Condition Report', NULL, NULL),
(35, 'purchase_add', 'Loading', 'Loading Report', NULL, NULL),
(36, 'purchase_add', 'Booking', 'Booking Report', NULL, NULL),
(37, 'purchase_add', 'Final', 'Final Report', NULL, NULL),
(45, 'ps_types', 'booking', 'Booking', NULL, NULL),
(46, 'ps_types', 'local', 'Local', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `p_s` varchar(10) NOT NULL DEFAULT 'p' COMMENT 'p|s',
  `sr` int(11) NOT NULL,
  `type` varchar(200) NOT NULL,
  `khaata_tr1` longtext NOT NULL,
  `_date` date NOT NULL,
  `country` varchar(255) NOT NULL,
  `sea_road` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `reports` longtext NOT NULL,
  `third_party_bank` longtext NOT NULL,
  `notify_party_details` longtext NOT NULL,
  `transfer_level` varchar(255) NOT NULL,
  `from` varchar(255) NOT NULL,
  `is_doc` int(11) NOT NULL DEFAULT 0,
  `locked` int(11) NOT NULL DEFAULT 0,
  `payments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payments`)),
  `active` int(11) NOT NULL DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `p_s`, `sr`, `type`, `khaata_tr1`, `_date`, `country`, `sea_road`, `reports`, `third_party_bank`, `notify_party_details`, `transfer_level`, `from`, `is_doc`, `locked`, `payments`, `active`, `branch_id`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'p', 0, 'booking', '{\"dr_khaata_no\":\"DC1\",\"dr_khaata_id\":\"1\",\"cr_khaata_no\":\"DP2\",\"cr_khaata_id\":\"2\",\"transfer_date\":\"2024-10-30\",\"amount\":\"2669598\",\"details\":\"ENTRY:1 GOODS:BSSL SEED  COUNTRY:AFGHISTAN ALLOT:12CONTAINER T.Qty:6480 T.KGs:324648 RATE:2250 T.AMNT:729000USD EXCH.:AED\",\"check_full_payment\":\"false\",\"p_id_hidden\":\"1\",\"type\":\"booking\"}', '2024-10-29', 'CFR', '{\"sea_road\":\"road\",\"l_country_road\":\"AFGHISTAN\",\"l_border_road\":\"NIMROZ BORDER\",\"l_date_road\":\"2024-09-29\",\"truck_container\":\"open_truck\",\"r_country_road\":\"IRAN\",\"r_border_road\":\"BRAS\",\"r_date_road\":\"2024-10-10\",\"d_date_road\":\"2024-10-12\",\"l_country\":\"\",\"l_port\":\"\",\"l_date\":\"2024-10-29\",\"ctr_name\":\"\",\"r_country\":\"\",\"r_port\":\"\",\"r_date\":\"2024-10-29\",\"arrival_date\":\"2024-10-29\",\"report\":\"TRUCK LOADING \",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"1\",\"is_loading\":0,\"is_receiving\":0}', '{\"payment_details\":\"\",\"goods_details\":\"\",\"contract_details\":\"AD FHDFALKJSYHF9W,AMNFAKLJSDH ASDFASJHDF;AK DFLASHD F,DAFLDKSHFA LFAHWRYAS NFJKLWAEJRYHLA,MSFASK JFH ,AMSDFKLDSA HKAM,S DNFKAJSDH,AS FNDAKDSFH LAM,S FNKJSADH   ASDFA;SDKJLFA;SDKFA  ASDFASDFJ A9IAWAJSFAWEA&#039;;A LDJFKAJ,ZFDAJDFOIASUDFASD\"}', '{\"search_acc_no\":\"DP2\",\"search_acc_id\":\"\",\"acc_no\":\"02-01-01-120311-105-1075750\",\"acc_name\":\"DAMAAN GENERAL TRADING LLC\",\"company\":\"DAMAAN GENERAL TRADING LLC\",\"iban\":\"AE14 0290 1953 1050 1075 750\",\"branch_code\":\"Main Branch, Dubai\",\"currency\":\"IRR\",\"country\":\"DAMAAN GENERAL TRADING LLC\",\"state\":\"DUBAI\",\"city\":\"02-01-01-120311-105-1075750\",\"address\":\"DUBAI\",\"thirdPartyBankSubmit\":\"\",\"hidden_id\":\"1\"}', '{\"np_acc\":\"DP2\",\"np_acc_name\":\"PURCHASE&SALES \",\"np_acc_id\":\"2\",\"np_acc_kd_id\":\"3\",\"np_acc_details\":\"DAMAAN GENERAL TRADING L L C Country: United Arab Emirates City: dubai State: dubai Address: OFFICE NO 201 HADTOOR BUNNN License: 1099620 WEIGHT: 104127559300003\",\"hidden_id\":\"0\"}', '2', 'bill-transfer', 1, 1, '{\"full_advance\":\"credit\",\"pct_value\":\"20\",\"credit_date\":\"2024-11-09\",\"credit_report\":\"1 MANT BANK TRSFR TOTAL AMO\",\"p_total_amount\":\"2669598\",\"partial_amount1\":\"\",\"partial_amount2\":\"\"}', 1, 1, '2024-10-29 22:00:52', 1, NULL, NULL),
(2, 'p', 0, 'booking', '{\"dr_khaata_no\":\"DC1\",\"dr_khaata_id\":\"1\",\"cr_khaata_no\":\"DP2\",\"cr_khaata_id\":\"2\",\"transfer_date\":\"2024-10-30\",\"amount\":\"889866\",\"details\":\"ENTRY:1 GOODS:BSSL SEED  COUNTRY:AFGHISTAN ALLOT:4CONTAINER T.Qty:2160 T.KGs:108216 RATE:2250 T.AMNT:243000USD EXCH.:AED\",\"check_full_payment\":\"false\",\"p_id_hidden\":\"2\",\"type\":\"booking\"}', '2024-10-29', 'C&F', '{\"sea_road\":\"road\",\"l_country_road\":\"AFGHISTAN\",\"l_border_road\":\"NIMROZ BORDER\",\"l_date_road\":\"2024-10-29\",\"truck_container\":\"open_truck\",\"r_country_road\":\"IRAN\",\"r_border_road\":\"BRAS\",\"r_date_road\":\"2024-10-29\",\"d_date_road\":\"2024-10-29\",\"l_country\":\"\",\"l_port\":\"\",\"l_date\":\"2024-10-29\",\"ctr_name\":\"\",\"r_country\":\"\",\"r_port\":\"\",\"r_date\":\"2024-10-29\",\"arrival_date\":\"2024-10-29\",\"report\":\"TRUCK LOADING \",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"2\",\"is_loading\":0,\"is_receiving\":0}', '', '{\"search_acc_no\":\"DP2\",\"search_acc_id\":\"2\",\"acc_no\":\"02-01-01-120311-105-1075750\",\"acc_name\":\"DAMAAN GENERAL TRADING LLC\",\"company\":\"DAMAAN GENERAL TRADING LLC\",\"iban\":\"AE14 0290 1953 1050 1075 750\",\"branch_code\":\"Main Branch, Dubai\",\"currency\":\"AED\",\"country\":\"DAMAAN GENERAL TRADING LLC\",\"state\":\"DUBAI\",\"city\":\"DUBAIA\",\"address\":\"DUBAI\",\"thirdPartyBankSubmit\":\"\",\"hidden_id\":\"2\"}', '{\"np_acc\":\"DP2\",\"np_acc_name\":\"PURCHASE&SALES \",\"np_acc_id\":\"2\",\"np_acc_kd_id\":\"3\",\"np_acc_details\":\"DAMAAN GENERAL TRADING L L C Country: United Arab Emirates City: dubai State: dubai Address: OFFICE NO 201 HADTOOR BUNNN License: 1099620 WEIGHT: 104127559300003\",\"hidden_id\":\"2\"}', '2', 'bill-transfer', 1, 1, '{\"full_advance\":\"credit\",\"pct_value\":\"20\",\"credit_date\":\"2024-11-16\",\"credit_report\":\"2 moy hghj gjkg hglhm,jhgf\",\"p_total_amount\":\"889866\",\"partial_amount1\":\"\",\"partial_amount2\":\"\"}', 1, 1, '2024-10-29 22:06:02', 1, '2024-10-29 22:09:53', 1),
(4, 'p', 0, 'local', '', '2024-10-29', 'cfr', NULL, '[]', '{\"search_acc_no\":\"dp2\",\"search_acc_id\":\"2\",\"acc_no\":\"02-01-01-120311-105-1075750\",\"acc_name\":\"DAMAAN GENERAL TRADING LLC\",\"company\":\"DAMAAN GENERAL TRADING LLC\",\"iban\":\"AE14 0290 1953 1050 1075 750\",\"branch_code\":\"Main Branch, Dubai\",\"currency\":\"AED\",\"country\":\"DAMAAN GENERAL TRADING LLC\",\"state\":\"DUBAI\",\"city\":\"DUBAIA\",\"address\":\"DUBAI\",\"thirdPartyBankSubmit\":\"\",\"hidden_id\":\"4\"}', '{\"np_acc\":null,\"np_acc_name\":null,\"np_acc_id\":null,\"np_acc_kd_id\":null,\"np_acc_details\":\"\",\"hidden_id\":\"4\"}', '', 'purchase-add', 1, 0, '{\"full_advance\":\"advance\",\"pct_value\":\"10\",\"partial_date1\":\"2024-10-29\",\"partial_report1\":\"bv c vbcnbvcnbc\",\"partial_date2\":\"2024-11-09\",\"partial_report2\":\"bcbv nb cmnh nhgf\",\"p_total_amount\":\"13940\",\"partial_amount1\":\"2788.00\",\"partial_amount2\":\"11152.00\"}', 1, 1, '2024-10-29 23:26:40', 1, '2024-10-30 14:12:47', 1),
(6, 'p', 0, 'local', '', '2024-10-30', 'crd', NULL, '', '{\"search_acc_no\":\"dp2\",\"search_acc_id\":\"2\",\"acc_no\":\"02-01-01-120311-105-1075750\",\"acc_name\":\"DAMAAN GENERAL TRADING LLC\",\"company\":\"DAMAAN GENERAL TRADING LLC\",\"iban\":\"AE14 0290 1953 1050 1075 750\",\"branch_code\":\"Main Branch, Dubai\",\"currency\":\"AED\",\"country\":\"DAMAAN GENERAL TRADING LLC\",\"state\":\"DUBAI\",\"city\":\"DUBAIA\",\"address\":\"DUBAI\",\"thirdPartyBankSubmit\":\"\",\"hidden_id\":\"6\"}', '{\"np_acc\":null,\"np_acc_name\":null,\"np_acc_id\":null,\"np_acc_kd_id\":null,\"np_acc_details\":\"\",\"hidden_id\":\"0\"}', '', 'purchase-add', 0, 0, '{\"full_advance\":\"credit\",\"pct_value\":\"20\",\"credit_date\":\"2024-10-30\",\"credit_report\":\"10 dyt\",\"p_total_amount\":\"522750\",\"partial_amount1\":\"\",\"partial_amount2\":\"\"}', 1, 1, '2024-10-30 14:16:49', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_accounts`
--

CREATE TABLE `transaction_accounts` (
  `id` int(11) NOT NULL,
  `trans_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL COMMENT 'p,s,transfer,etc.',
  `dr_cr` varchar(100) NOT NULL DEFAULT 'dr',
  `acc` varchar(255) NOT NULL,
  `acc_name` varchar(255) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `acc_kd_id` int(11) NOT NULL,
  `details` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_accounts`
--

INSERT INTO `transaction_accounts` (`id`, `trans_id`, `type`, `dr_cr`, `acc`, `acc_name`, `acc_id`, `acc_kd_id`, `details`, `created_at`) VALUES
(1, 1, 'purchase', 'dr', 'DP2', 'PURCHASE&SALES ', 2, 3, 'DAMAAN GENERAL TRADING L L C\r\nCountry: United Arab Emirates\r\nCity: dubai\r\nState: dubai\r\nAddress: OFFICE NO 201 HADTOOR BUNNN\r\nLicense: 1099620\r\nWEIGHT: 104127559300003', '2024-10-29 18:00:52'),
(2, 1, 'purchase', 'cr', 'DC1', 'NAJEEBULLAH/QUETTA OFFICE', 1, 1, 'NAJEEBULLAH GENERAL TRADERS\r\nCountry: PAKISTAN\r\nCity: CHAMAN\r\nState: CHAMAN\r\nAddress: MAL ROAD CHAMAN', '2024-10-29 18:00:52'),
(3, 2, 'purchase', 'dr', 'DP2', 'PURCHASE&SALES ', 2, 3, 'DAMAAN GENERAL TRADING L L C\r\nCountry: United Arab Emirates\r\nCity: dubai\r\nState: dubai\r\nAddress: OFFICE NO 201 HADTOOR BUNNN\r\nLicense: 1099620\r\nWEIGHT: 104127559300003', '2024-10-29 18:06:03'),
(4, 2, 'purchase', 'cr', 'DC1', 'NAJEEBULLAH/QUETTA OFFICE', 1, 1, 'NAJEEBULLAH GENERAL TRADERS\r\nCountry: PAKISTAN\r\nCity: CHAMAN\r\nState: CHAMAN\r\nAddress: MAL ROAD CHAMAN', '2024-10-29 18:06:03'),
(7, 4, 'purchase', 'dr', 'Dp2', 'PURCHASE&SALES ', 2, 3, 'DAMAAN GENERAL TRADING L L C\r\nCountry: United Arab Emirates\r\nCity: dubai\r\nState: dubai\r\nAddress: OFFICE NO 201 HADTOOR BUNNN\r\nLicense: 1099620\r\nWEIGHT: 104127559300003', '2024-10-29 19:26:40'),
(8, 4, 'purchase', 'cr', 'Dc1', 'NAJEEBULLAH/QUETTA OFFICE', 1, 1, 'NAJEEBULLAH GENERAL TRADERS\r\nCountry: PAKISTAN\r\nCity: CHAMAN\r\nState: CHAMAN\r\nAddress: MAL ROAD CHAMAN', '2024-10-29 19:26:40'),
(11, 6, 'purchase', 'dr', 'dp2', 'PURCHASE&SALES ', 2, 3, 'DAMAAN GENERAL TRADING L L C\r\nCountry: United Arab Emirates\r\nCity: dubai\r\nState: dubai\r\nAddress: OFFICE NO 201 HADTOOR BUNNN\r\nLicense: 1099620\r\nWEIGHT: 104127559300003', '2024-10-30 10:16:49'),
(12, 6, 'purchase', 'cr', 'dc12', 'Sanjay Broker India', 12, 7, 'DAMODAR EXPORT\r\nCountry: India \r\nCity: Mumbai\r\nState: Bombay\r\nAddress: B-27, Apmc Market I, Phase II, Sector 19, Vashi Navi Mumbai.400 705 GSTIN/UIN: 27AAQFD1336E1ZK\r\nFSSAI: 10019022010092', '2024-10-30 10:16:49');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `p_s` varchar(100) NOT NULL DEFAULT 'p' COMMENT 'p|s',
  `sr` int(11) NOT NULL DEFAULT 0,
  `goods_id` int(11) NOT NULL,
  `allotment_name` varchar(255) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `qty_name` varchar(255) NOT NULL,
  `qty_no` double NOT NULL,
  `qty_kgs` double NOT NULL,
  `total_kgs` double NOT NULL,
  `empty_kgs` double NOT NULL,
  `total_qty_kgs` double NOT NULL,
  `net_kgs` double NOT NULL,
  `divide` varchar(100) NOT NULL,
  `weight` double NOT NULL,
  `total` double NOT NULL,
  `price` varchar(255) NOT NULL,
  `currency1` varchar(255) NOT NULL,
  `rate1` double NOT NULL,
  `amount` double NOT NULL,
  `currency2` varchar(255) DEFAULT NULL,
  `rate2` double DEFAULT NULL,
  `opr` varchar(10) NOT NULL,
  `tax_percent` varchar(255) DEFAULT NULL,
  `tax_amount` varchar(255) DEFAULT NULL,
  `total_with_tax` varchar(255) DEFAULT NULL,
  `final_amount` double NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`id`, `parent_id`, `p_s`, `sr`, `goods_id`, `allotment_name`, `size`, `brand`, `origin`, `qty_name`, `qty_no`, `qty_kgs`, `total_kgs`, `empty_kgs`, `total_qty_kgs`, `net_kgs`, `divide`, `weight`, `total`, `price`, `currency1`, `rate1`, `amount`, `currency2`, `rate2`, `opr`, `tax_percent`, `tax_amount`, `total_with_tax`, `final_amount`, `created_at`) VALUES
(1, 1, 'p', 1, 12, '12CONTAINER', '0', 'DGTLLC', 'AFGHISTAN', 'PP BAGS', 6480, 50.1, 324648, 0.1, 648, 324000, 'TON', 1000, 324, 'TON', 'USD', 2250, 729000, 'AED', 3.662, '*', '', '', '', 2669598, '2024-10-29 18:02:16'),
(2, 2, 'p', 1, 12, '4CONTAINER', '00', 'DGTLLC', 'AFGHISTAN', 'PP BAGS', 2160, 50.1, 108216, 0.1, 216, 108000, 'TON', 1000, 108, 'TON', 'USD', 2250, 243000, 'AED', 3.662, '*', '', '', '', 889866, '2024-10-29 18:08:14'),
(3, 4, 'p', 1, 9, '5container ', 'Jambo ', 'Shira', 'U$', 'PP AGS', 1700, 25.1, 42670, 0.1, 170, 42500, 'CARTON', 25, 1700, 'KG', 'AED', 8.2, 13940, '', 0, '', '5%', '697.00', '14637.00', 13940, '2024-10-29 19:28:27'),
(4, 6, 'p', 1, 9, '5containe', 'Jambo ', 'dorsc', 'U$', 'PP BAGS', 2550, 25.1, 64005, 0.1, 255, 63750, 'KG', 25, 2550, 'CARTON', 'AED', 205, 522750, '', 0, '', '0', '0.00', '522750.00', 522750, '2024-10-30 10:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `image` text DEFAULT NULL,
  `contact_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`contact_details`)),
  `khaata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`khaata`)),
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `type`, `is_active`, `branch_id`, `role`, `username`, `pass`, `full_name`, `image`, `contact_details`, `khaata`, `created_at`, `created_by`, `updated_at`) VALUES
(1, 'office', 1, 1, 'superadmin', 'admin', 'Asmat@123456', 'Haji AsmatUllah', 'uploads/asmat.webp', '{\"full_name\":\"ribaha\",\"father_name\":\"saif\",\"gender\":\"female\",\"identity\":\"passport\",\"idn_no\":\"23988329\",\"idn_reg\":\"2024-07-29\",\"idn_expiry\":\"2024-07-29\",\"idn_country\":\"\",\"country\":\"\",\"state\":\"\",\"city\":\"\",\"address\":\"\",\"postcode\":\"\",\"mobile\":\"\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"1\"}', '{\"khaata_id\":\"11\",\"khaata_no\":\"DU00011\",\"hidden_id\":\"1\"}', '2024-07-25 19:47:55', 0, '2024-08-04 00:10:03'),
(2, 'agent', 1, 1, 'agent', 'noor@dgt.llc', '123456', 'Noor Muhammad', NULL, NULL, '{\"khaata_id\":\"3\",\"khaata_no\":\"DG3\",\"hidden_id\":\"2\"}', '2024-10-29 20:50:40', 1, '2024-10-29 21:40:55'),
(3, 'office', 1, 1, 'manager', 'hidayat@dgt.llc', '123456', 'hidayatullah', NULL, NULL, NULL, '2024-10-29 20:51:42', 1, NULL),
(4, 'agent', 1, 1, 'agent', 'IM@DGT.LLC', '123456', 'IMRAN ALI', NULL, NULL, '{\"khaata_id\":\"4\",\"khaata_no\":\"dg4\",\"hidden_id\":\"4\"}', '2024-10-29 21:43:26', 1, '2024-10-30 20:01:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`permission`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `user_id`, `permission`) VALUES
(1, 1, '[\"\"]'),
(2, 2, '[\"agent-form\",\"agent-payments-form\"]'),
(3, 3, '[\"#\",\"#\",\"categories\",\"goods\",\"#\",\"compose\",\"inbox\",\"ledger-categories\",\"roznamcha-banks\",\"exchanges\",\"exchanges-stock\",\"roznamcha-office\",\"#.\",\"#.\",\"#.\",\"#.\",\"carry-bill\",\"draft-invoices\",\"#\",\"#\",\"AVAT/TAX\",\"AVAT/TAX\",\"purchases-bill-transfer\",\"purchase-final\",\"#\",\"#\",\"VAT/TAX\",\"purchases\",\"sales\",\"#\",\"#\",\"afghan-invoices\",\"#\",\"purchase-advance\",\"purchase-remaining\",\"purchase-credit\",\"#\",\"loading-transfer\",\"#\",\"#\",\"general-stock-form\",\"confirm-stock\",\"general-loading\",\"agent-payments-form\",\"#\",\"cargo-lane\",\"cargo-final-lane\",\"#\"]'),
(4, 4, '[\"agent-form\",\"agent-payments-form\"]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `afg_invs`
--
ALTER TABLE `afg_invs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `afg_inv_details`
--
ALTER TABLE `afg_inv_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `agent_payments`
--
ALTER TABLE `agent_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `business_settings`
--
ALTER TABLE `business_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cats`
--
ALTER TABLE `cats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exchanges`
--
ALTER TABLE `exchanges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_loading`
--
ALTER TABLE `general_loading`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `good_details`
--
ALTER TABLE `good_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_id` (`goods_id`);

--
-- Indexes for table `khaata`
--
ALTER TABLE `khaata`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `cat_id` (`cat_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `khaata_details`
--
ALTER TABLE `khaata_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `khaata_id` (`khaata_id`);

--
-- Indexes for table `navbar`
--
ALTER TABLE `navbar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `purchase_pays`
--
ALTER TABLE `purchase_pays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roznamchaas`
--
ALTER TABLE `roznamchaas`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `roznamchaas_deleted`
--
ALTER TABLE `roznamchaas_deleted`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `sent_emails`
--
ALTER TABLE `sent_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `static_types`
--
ALTER TABLE `static_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_accounts`
--
ALTER TABLE `transaction_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trans_id` (`trans_id`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `afg_invs`
--
ALTER TABLE `afg_invs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `afg_inv_details`
--
ALTER TABLE `afg_inv_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agent_payments`
--
ALTER TABLE `agent_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `business_settings`
--
ALTER TABLE `business_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cats`
--
ALTER TABLE `cats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `exchanges`
--
ALTER TABLE `exchanges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_loading`
--
ALTER TABLE `general_loading`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `good_details`
--
ALTER TABLE `good_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `khaata`
--
ALTER TABLE `khaata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `khaata_details`
--
ALTER TABLE `khaata_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `navbar`
--
ALTER TABLE `navbar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `purchase_pays`
--
ALTER TABLE `purchase_pays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roznamchaas`
--
ALTER TABLE `roznamchaas`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roznamchaas_deleted`
--
ALTER TABLE `roznamchaas_deleted`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sent_emails`
--
ALTER TABLE `sent_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `static_types`
--
ALTER TABLE `static_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transaction_accounts`
--
ALTER TABLE `transaction_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `branches_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Constraints for table `cats`
--
ALTER TABLE `cats`
  ADD CONSTRAINT `cats_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `good_details`
--
ALTER TABLE `good_details`
  ADD CONSTRAINT `good_details_ibfk_1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`);

--
-- Constraints for table `khaata`
--
ALTER TABLE `khaata`
  ADD CONSTRAINT `khaata_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `khaata_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `cats` (`id`),
  ADD CONSTRAINT `khaata_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `khaata_details`
--
ALTER TABLE `khaata_details`
  ADD CONSTRAINT `khaata_details_ibfk_1` FOREIGN KEY (`khaata_id`) REFERENCES `khaata` (`id`);

--
-- Constraints for table `transaction_accounts`
--
ALTER TABLE `transaction_accounts`
  ADD CONSTRAINT `transaction_accounts_ibfk_1` FOREIGN KEY (`trans_id`) REFERENCES `transactions` (`id`);

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `transactions` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
