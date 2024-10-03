-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2024 at 11:56 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `3742499_dgtllc`
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

--
-- Dumping data for table `afg_invs`
--

INSERT INTO `afg_invs` (`id`, `type`, `_from`, `third_party`, `_to`, `_date`, `json_data`, `terms`, `through`, `json_final`, `is_active`, `created_at`) VALUES
(1, 'afg', 'lk jsdlk jfslkdj \r\nCountry: \r\nCity: \r\nState: \r\nAddress: \r\n', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS \r\nAREA,DEIRA,DUBAI BARI \r\nEMAIL:DAMAAN.DUBAI@GMAIL.COM \r\nMOBILE NO+971507164963', '3ASMATNAJEEB & COMPANY 2\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT PLAZA AL RAS DUBAI\r\nNTN: 719170-9\r\nST: 00000000\r\n', '2024-08-19', '{\"from_khaata_no\":\"DC5\",\"from_kd_id\":\"5\",\"from_khaata_id\":\"5\",\"imp_khaata_no\":\"DC1\",\"imp_kd_id\":\"3\",\"imp_khaata_id\":\"1\",\"no1\":\"111\",\"_date1\":\"2024-08-19\",\"recordSubmit\":\"\",\"afg\":\"Afghan Transit Form Bill Of Loading..\",\"no2\":\"00\",\"_date2\":\"2024-06-10\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"inv_id_hidden\":\"1\"}', '00', 'VIA; KABUL TO TORKHAM PAKISTAN TRANSIT C&F TO ATTARI INDIA INCLUDES ALL OF CHARGES ', '{\"t_date\":\"2024-03-14\",\"tt_amount\":\"42160\",\"final_amount\":\"3498648\",\"curr\":\"INR\",\"bank_details\":\"bank me tt ki bless bill me\",\"inv_id_hidden\":\"1\"}', 1, '2024-06-10 19:30:45'),
(2, 'draft', 'JAVID S/O ABDUL MALIK\r\nT.L NO: 62754 KANDAHAR AFGHANISTAN.\r\nTEL: 0093704862191', 'TRUENUT FOOD MANUFACTYRING\r\n OFFICE: 1006,OPEL TOWER, BUSINESS BAY, DUBAI, UAE TAXID/NIT\r\n/VAT: 100502086000003', 'NAKSHATRA OVERSEAS AHATA BANWARILLA TURAB\r\nNAGAR.GHAZIABAD UTTAR\r\nPRADESH201001.GSTIN:09ASKPA0595||21, FASSAI LICENSE NO.\r\n12721999000521,IEC CODE:ASKPA595)', '0001-01-01', '{\"no1\":\"000\",\"_date1\":\"0001-01-01\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"000\",\"_date2\":\"2024-06-15\",\"letter\":\"0\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"2\"}', 'SDAASVD ADFGFDGAGFAD', 'Via:BY ,Nimroz BORDER C&F BY SEA BANDAR ABBAS IN TRANSIT TO MUNDRA PORT AND DELIVERY NT ICD GREL SALINEWAL\r\nPORT CODE INSGEG LUDLIANA', NULL, 1, '2024-06-15 03:54:05'),
(3, 'afg', 'QUETTA OFFICE\r\nmsaif3850@gmail.com\r\n03057434424\r\n', ' DAMAAN GENERAL TRADING L.L.C \r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS \r\nAREA,DEIRA,DUBAI BARI \r\nEMAIL:DAMAAN.DUBAI@GMAIL.COM \r\nMOBILE NO+971507164963', 'DAMODAR  EXPORT\r\n OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK\r\n', '2024-06-15', '{\"from_khaata_no\":\"dc1\",\"from_khaata_id\":\"1\",\"imp_khaata_no\":\"DC6\",\"kd_id\":\"6\",\"imp_khaata_id\":\"6\",\"no1\":\"47\",\"_date1\":\"2024-06-15\",\"recordSubmit\":\"\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-15\",\"letter\":\"0\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"inv_id_hidden\":\"3\"}', 'AA', 'Via:NIMROZ IN TRANSIT TO INDIA BY SEA BANDAR ABBAS IRAN T 0\r\nNHAVA SHEVA INDIA\r\n', '{\"t_date\":\"2024-08-18\",\"tt_amount\":\"47659\",\"final_amount\":\"1000\",\"curr\":\"USD\",\"bank_details\":\"saif\",\"inv_id_hidden\":\"3\"}', 1, '2024-06-15 04:04:27'),
(4, 'afg', 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2\r\nWEIGHT: 322\r\n', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', '3ASMATNAJEEB & COMPANY 2\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT PLAZA AL RAS DUBAI\r\nNTN: 719170-9\r\nST: 00000000\r\n', '2023-12-28', '{\"from_khaata_no\":\"DC1\",\"from_kd_id\":\"2\",\"from_khaata_id\":\"1\",\"imp_khaata_no\":\"DC1\",\"imp_kd_id\":\"3\",\"imp_khaata_id\":\"1\",\"no1\":\"48\",\"_date1\":\"2023-12-28\",\"recordSubmit\":\"\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"1\",\"_date2\":\"2024-06-27\",\"letter\":\"0gh\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"inv_id_hidden\":\"4\"}', '00', 'KABUL TO TORKHAM PAKISTAN IN TRANSIT C & f to attari india include of all charges ', NULL, 1, '2024-06-27 08:56:49'),
(5, 'afg', 'ALI KHAN AND AHMAD KHAN TRADING;\r\nT.L NO 83047 ADD; JALA ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788             ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'damodar EXPORT ; \r\nOFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '2024-01-01', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"1\",\"_date1\":\"2024-01-01\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"5\"}', '0', 'kabul tto torkham pakistan in transit c& f to attari india include of all charges ', '{\"t_date\":\"2024-03-11\",\"tt_amount\":\"74410\",\"final_amount\":\"6158172\",\"curr\":\"INR\",\"bank_details\":\"bank me tt ki bless bill me\",\"inv_id_hidden\":\"5\"}', 1, '2024-06-27 09:09:35'),
(6, 'afg', 'ALI KHAN AND AHMAD KHAN TRADING;\r\nT.L NO 83047 ADD; JALA ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788       ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'DAMODAR  EXPORT\r\nOFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '2024-02-12', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"12\",\"_date1\":\"2024-02-12\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"0\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"6\"}', '00', 'KABUL TO TORKHAM PAKISTAN IN TRANSIT C& f to attari india include of all charges ', '{\"t_date\":\"2024-06-26\",\"tt_amount\":\"69335\",\"final_amount\":\"5804792\",\"curr\":\"INR\",\"bank_details\":\"bank me tt ki bless bill me\",\"inv_id_hidden\":\"6\"}', 1, '2024-06-27 09:16:24'),
(7, 'afg', 'ALI KHAN AND AHMAD KHAN TRADING;\r\nT.L NO 83047 ADD; JALA ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788       ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', ' DAMODAR  EXPORT ;\r\nOFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '2024-01-06', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"2\",\"_date1\":\"2024-01-06\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"0\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"7\"}', '00', 'KABUL TO TORKHAM PAKISTAN IN TRANSIT C& f  attari india include of all charges ', '{\"t_date\":\"2024-06-04\",\"tt_amount\":\"25130\",\"final_amount\":\"5210772\",\"curr\":\"INR\",\"bank_details\":\"bank me tt kia bless bill \",\"inv_id_hidden\":\"7\"}', 1, '2024-06-27 09:25:42'),
(8, 'afg', 'ALI KHAN AND AHMAD KHAN TRADING;\r\nT.L NO 83047 ADD; JALA ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788   ', 'DAMAAN GENERAL TRADING L.L.\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', ' DAMODAR  EXPORT ;\r\nOFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '2024-01-10', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"3\",\"_date1\":\"2024-01-10\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"0\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"8\"}', '00', 'KABUL TO TORKHAM PAKISTAN IN TRANSIT C& f to attari india includes all of charges ', NULL, 1, '2024-06-27 09:29:23'),
(9, 'afg', 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2\r\nWEIGHT: 322\r\n', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'lk jsdlk jfslkdj \r\nCountry: \r\nCity: \r\nState: \r\nAddress: \r\n', '2024-01-13', '{\"from_khaata_no\":\"dc1\",\"from_kd_id\":\"2\",\"from_khaata_id\":\"1\",\"imp_khaata_no\":\"DC5\",\"imp_kd_id\":\"5\",\"imp_khaata_id\":\"5\",\"no1\":\"4\",\"_date1\":\"2024-01-13\",\"recordSubmit\":\"\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"0\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"inv_id_hidden\":\"9\"}', '00', 'kabuL  to torkham pakistan in transit c& F TO ATTARI INDIA INCLUDE ALL OF CHARGES ', NULL, 1, '2024-06-27 09:37:44'),
(10, 'afg', 'ALI KHAN AND AHMAD KHAN TRADING;\r\nT.L NO 83047 ADD; JALA ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788       ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'DAMODAR  EXPORT ;  \r\n OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '2024-01-25', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"5\",\"_date1\":\"2024-01-25\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"10\"}', '00', 'KABUL TO TORKHAM PAKISTAN IN TRANSIT C & F TO ATTARI INDIA INCLUDE ALL OF CHARGES ', '{\"t_date\":\"2024-04-29\",\"tt_amount\":\"70210\",\"final_amount\":\"5869313\",\"curr\":\"INR\",\"bank_details\":\"bank me tt kia bless bill \",\"inv_id_hidden\":\"10\"}', 1, '2024-06-27 09:41:12'),
(11, 'afg', 'ALI KHAN AND AHMAD KHAN TRADING.\r\nT.L NO 83047 ADD; JALA ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788                                                                                                     ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', ' DAMODAR  EXPORT ;\r\nOFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '2024-01-27', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"6\",\"_date1\":\"2024-01-27\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"11\"}', '00', 'KABUL TO TORKHAM PAKISTAN IN TRANSIT C & f TO ATTARI INDIA INCLUDES ALL OF CHARGES ', NULL, 1, '2024-06-27 09:44:11'),
(12, 'afg', 'ALI KHAN AND AHMAD KHAN TRADING;   \r\nT.L NO 83047 ADD; JALA ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788   ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'DAMODAR  EXPORT ;    \r\n    OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '2024-01-27', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"7\",\"_date1\":\"2024-01-27\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"12\"}', '00', 'KABUL TO TORKHAM PAKISTAN C& f  TO ATTARI INDIA INCLUDES ALL OF CHARGES ', NULL, 1, '2024-06-27 09:46:39'),
(13, 'afg', 'ALI KHAN AND AHMAD KHAN TRADING;  \r\nT.L NO 83047 ADD; JALA ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788   ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'DAMODAR  EXPORt\r\n OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '2024-01-31', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"8\",\"_date1\":\"2024-01-31\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"0\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"13\"}', '00', 'KABUL TO TORKHAM PAKISTAN IN TRANSIT C&  F TO ATTARI INDIA INCLUDES ALL OF CHARGES ', '{\"t_date\":\"2024-03-14\",\"tt_amount\":\"55507\",\"final_amount\":\"4617089\",\"curr\":\"INR\",\"bank_details\":\"bank me tt ki bless bill me\",\"inv_id_hidden\":\"13\"}', 1, '2024-06-27 09:49:06'),
(14, 'afg', 'ALI KHAN AND AHMAD KHAN TRADING;    \r\n  T.L NO 83047 ADD; JALA ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788   ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'DAMODAR  EXPORT ; \r\n OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '0024-02-03', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"9\",\"_date1\":\"0024-02-03\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"14\"}', '00', 'KABUL TO TORKHAM PAKISTAN IN TRANSIT C& F ATARRI INDIA INCLUDES ALL OF CHARGES ', '{\"t_date\":\"2024-04-03\",\"tt_amount\":\"74340\",\"final_amount\":\"6205124\",\"curr\":\"INR\",\"bank_details\":\"mashri\",\"inv_id_hidden\":\"14\"}', 1, '2024-06-27 09:51:50'),
(15, 'afg', ': ALI KHAN AND AHMAD KHAN TRADING;   \r\n T.L NO 83047 ADD; JALAL ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788         ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'DAMODAR  EXPORT ;    \r\n   OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '2024-02-10', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"10\",\"_date1\":\"2024-02-10\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"15\"}', '00', 'VIA KABUL TO TORKHAM PAKISTAN IN TRANSIT C&f TO ATTAARI INDIA INCLUDES ALL OF CHARGES ', NULL, 1, '2024-06-27 10:09:06'),
(16, 'afg', 'ALI KHAN AND AHMAD KHAN TRADING;     \r\nT.L NO 83047 ADD; JALA ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788   ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'DAMODAR  EXPORT  \r\nOFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '2024-02-11', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"11\",\"_date1\":\"2024-02-11\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"000\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"16\"}', '00', 'VIA KABUL TO TORKHAM PAKISTAN IN TRANSIT C&f TO ATTARI INDIA ', '{\"t_date\":\"2024-04-15\",\"tt_amount\":\"66080\",\"final_amount\":\"5516406\",\"curr\":\"INR\",\"bank_details\":\"bank me tt kia bless bill \",\"inv_id_hidden\":\"16\"}', 1, '2024-06-27 10:14:43'),
(17, 'afg', 'ALI KHAN AND AHMAD KHAN TRADING;     \r\n   T.L NO 83047 ADD; JALA ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788   ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'DAMODAR  EXPORT ;      \r\n OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '2023-02-12', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"12\",\"_date1\":\"2023-02-12\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"17\"}', '00', 'VIA KABUL TO TORKHAM PAKISTAN IN TRANSIT C&F TO ATTARI INDIA INCLUDES ALL OF CHARGES ', NULL, 1, '2024-06-27 10:17:48'),
(18, 'afg', 'ALI KHAN AND AHMAD KHAN TRADING;    \r\n   T.L NO 83047 ADD; JALA ABAD CITY ZONE 5 NANGARHAR AFGHANISTAN            TIN : 9016772650 TEL : +93794818788   ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'DAMODAR  EXPORT \r\n OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA  EMAIL ID : DAMODAREXPORTS43@GMAIL.COM  FSSAI LIC 10019022010092 IES  NO ; AAQFD1336 EGST NO 27 AAQFD1336E1ZK', '2024-02-24', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"13\",\"_date1\":\"2024-02-24\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"000\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"18\"}', '00', 'VIA KABUL TO TORKHAM  PAKISTAN IN TRANSIT C&F to attari india includes all of charges ', '{\"t_date\":\"2024-04-12\",\"tt_amount\":\"74340\",\"final_amount\":\"6200664\",\"curr\":\"INR\",\"bank_details\":\"bank me tt kia bless bill \",\"inv_id_hidden\":\"18\"}', 1, '2024-06-27 10:21:10'),
(19, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 \r\n\r\n', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-03-12', '{\"imp_khaata_no\":\"dc6\",\"imp_khaata_id\":\"9\",\"no1\":\"14\",\"_date1\":\"2024-03-12\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"19\"}', '00', 'VIA ; NIMROZ IN TRANSIT TO INDIA BY SEA BANDAR ABBAS IRAN TO NHAVA SHEVA INDIA ', NULL, 1, '2024-06-27 11:53:31'),
(20, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-02-21', '{\"imp_khaata_no\":\"dc6\",\"imp_khaata_id\":\"9\",\"no1\":\"206\",\"_date1\":\"2024-02-21\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"20\"}', '00', 'VIA ; NIMROZ TRANSIT BY SEA BANDAR ABBAS IN TRANSIt to india by sea bandar abbas iran to nhava shava ', NULL, 1, '2024-06-27 12:59:10'),
(21, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-02-25', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"230\",\"_date1\":\"2024-02-25\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"21\"}', '00', 'VIA ; NIMROZ IN TRANSIT TO INDIA BY SEA BANDAR ABASS   iran to NHAVA SHEVA INDIA ', NULL, 1, '2024-06-27 13:02:03'),
(22, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-02-25', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"230\",\"_date1\":\"2024-02-25\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"000\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"22\"}', '000', 'VIA ; NIMROZ IN TRANSIT TO INDIA BY SEA  BANDAR ABBASS  IRAN TO NHAVA SHEVA  INDIA ', NULL, 1, '2024-06-27 13:04:57'),
(23, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-03-24', '{\"imp_khaata_no\":\"DC6\",\"imp_khaata_id\":\"9\",\"no1\":\"285\",\"_date1\":\"2024-03-24\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"23\"}', '00', 'VIA ;NIMROZ IN TRANSIT TO INDIA BY SEA BANDAR ABASS IRAN TO NHAVA SHEVA  INDIA ', NULL, 1, '2024-06-27 13:10:31'),
(24, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-03-24', '{\"imp_khaata_no\":\"dc6\",\"imp_khaata_id\":\"9\",\"no1\":\"206\",\"_date1\":\"2024-03-24\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-06-27\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"24\"}', '00', 'VIA; NIMROZ IN TRANSIT TO INDIA BY SEA BANDAR ABBAS IRAN TO NHAVA SHEVA  INDIA ', NULL, 1, '2024-06-27 13:18:55'),
(25, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-01-03', '{\"imp_khaata_no\":\"dc6\",\"imp_khaata_id\":\"9\",\"no1\":\"24\",\"_date1\":\"2024-01-03\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-07-03\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"25\"}', '00', 'nimroz transit to india ny sea bandar abbas to nhava shava india ', NULL, 1, '2024-07-03 12:52:40'),
(26, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-01-10', '{\"imp_khaata_no\":\"dc6\",\"imp_khaata_id\":\"9\",\"no1\":\"66\",\"_date1\":\"2024-01-10\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-07-03\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"26\"}', '00', 'via nimroz in transit to india by sea bandar abbas iran to nhava shava ', NULL, 1, '2024-07-03 19:14:57'),
(27, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-01-10', '{\"imp_khaata_no\":\"dc6\",\"imp_khaata_id\":\"9\",\"no1\":\"65\",\"_date1\":\"2024-01-10\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"0\",\"_date2\":\"2024-07-03\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"27\"}', '00', 'via nimroz in transit to india by sea bandar abbas iran to nhava shava ', NULL, 1, '2024-07-03 19:17:53'),
(28, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-01-22', '{\"imp_khaata_no\":\"dc6\",\"imp_khaata_id\":\"9\",\"no1\":\"106\",\"_date1\":\"2024-01-22\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"000\",\"_date2\":\"2024-07-03\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"28\"}', '00', 'via nimroz in transit to india by sea bandar abbas to nhava shava ', NULL, 1, '2024-07-03 19:20:56'),
(29, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-01-24', '{\"imp_khaata_no\":\"dc6\",\"imp_khaata_id\":\"9\",\"no1\":\"107\",\"_date1\":\"2024-01-24\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-07-03\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"29\"}', '00', 'via nimroz in transit to india by sea bandar abbas to nhava shava ', NULL, 1, '2024-07-03 19:23:35'),
(30, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-01-24', '{\"imp_khaata_no\":\"dc6\",\"imp_khaata_id\":\"9\",\"no1\":\"108\",\"_date1\":\"2024-01-24\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-07-03\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"30\"}', '00', 'via nimroz in transit to india by sea bandar abbas to nhava shava', NULL, 1, '2024-07-03 19:32:33'),
(31, 'afg', 'muhibullah popal s/o MOHMMAD SHAFIQ \r\nT.L NO ; 88347 KANDHAR AFGHANISTAN \r\nTEL ; 0093700206290', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM IMPEX \r\n1ST  FLOOR B/6 LOYALKA ESTATE J.MT. ROAD ASHLPA \r\nGHATKOPAR .W. MUMBAI MAHARASHTRA 400084 INDIA \r\nPAN NO; AHJPG48818 GST NO; 27AHJPG4881B1ZF \r\nFSSAI NO; 11522998001603 CONTACT SELL NO; RAKESH MAVJI GORI \r\nEMAIL ; RAKESHGORI878@GMAIL.COM', '2024-03-21', '{\"imp_khaata_no\":\"dg19\",\"imp_khaata_id\":\"14\",\"no1\":\"050\",\"_date1\":\"2024-03-21\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-07-03\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"31\"}', '00', 'VIA BLODAK BORDER C&f ATTARI BORDER IN TRANSIT TO INDIA ', NULL, 1, '2024-07-03 19:33:46'),
(32, 'afg', 'AYAZ NOORI LTD \r\nT.L; 49443  TIN NO 9006069785 \r\nLICENCE NO: 49443 REG NO; 123754 TEL NO ;0093704862191 ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM SAI SOHAM ENTERPRISES \r\nBARKHA APARTMENT SHOP NO 2 PLOT NO 6 SECTOR AIROLI NAVI MUMBAI THANE MAHRSHTRA 400708 INDIA \r\nFSSAI NO; 10021022000502 LEC NO ;AUEPT3579K \r\nCONSIGNEE ; NHAVA  SHEVA BUSINESS PARK PVT LTD PLOT NO 5/1 IN PLOT NO. 406 /FTWZ1 . SECTOR 4 INPASEZ . VILLAGE . SAWARKHAR URAN RAIGAD ', '2024-02-28', '{\"imp_khaata_no\":\"dc6\",\"imp_khaata_id\":\"9\",\"no1\":\"240\",\"_date1\":\"2024-02-28\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-07-24\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"32\"}', '00', 'via nimroz toindia by sea bandar abbas iran to nhava sheva india ', NULL, 1, '2024-07-24 17:19:33'),
(33, 'afg', 'muhibullah popal s/o MOHMMAD SHAFIQ \r\nT.L NO ; 88347 KANDHAR AFGHANISTAN \r\nTEL ; 0093700206290', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'OM IMPEX \r\n1ST  FLOOR B/6 LOYALKA ESTATE J.MT. ROAD ASHLPA \r\nGHATKOPAR .W. MUMBAI MAHARASHTRA 400084 INDIA \r\nPAN NO; AHJPG48818 GST NO; 27AHJPG4881B1ZF \r\nFSSAI NO; 11522998001603 CONTACT SELL NO; RAKESH MAVJI GORI \r\nEMAIL ; RAKESHGORI878@GMAIL.COM', '2024-03-21', '{\"imp_khaata_no\":\"dc6\",\"imp_khaata_id\":\"9\",\"no1\":\"49\",\"_date1\":\"2024-03-21\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"00\",\"_date2\":\"2024-07-24\",\"letter\":\"00\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"recordSubmit\":\"\",\"inv_id_hidden\":\"33\"}', '00', 'viaa boldak border c&f ATTARI BORDER IN TRANSIT TO INDIA ', NULL, 1, '2024-07-24 18:02:18'),
(34, 'afg', 'DAMODAR  EXPORT\r\nCountry: INDIA\r\nCity: IBD\r\nState: INDIA\r\nAddress:  OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA\r\nFSSAI: 10019022010092\r\nIEC: AAQFD1336 \r\nGST: 27 AAQFD1336E1ZK\r\n', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'DAMODAR  EXPORT\r\nCountry: INDIA:City: IBD:State: INDIA\r\nAddress:  OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA\r\nFSSAI: 10019022010092\r\nIEC: AAQFD1336 \r\nGST: 27 AAQFD1336E1ZK\r\n', '2024-08-18', '{\"from_khaata_no\":\"dc6\",\"from_kd_id\":\"6\",\"from_khaata_id\":\"6\",\"imp_khaata_no\":\"dc6\",\"imp_kd_id\":\"6\",\"imp_khaata_id\":\"6\",\"no1\":\"34\",\"_date1\":\"2024-08-18\",\"recordSubmit\":\"\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"lkjlk\",\"_date2\":\"2024-08-18\",\"letter\":\"sdfjlsdf\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"inv_id_hidden\":\"34\"}', 'ljkljklj', 'sdlkjflsd', NULL, 1, '2024-08-18 22:59:41'),
(35, 'afg', 'ASMATNAJEEB& COMPANY\r\nIMPOTR EXPOTR\r\nPakistan\r\nHIDAYAT ', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'DAMAAN GENERAL TRADING L L C\r\nSLE\r\nUnited Arab Emirates\r\nAS RAL', '2024-08-19', '{\"from_khaata_no\":\"dc1\",\"from_kd_id\":\"2\",\"from_khaata_id\":\"1\",\"imp_khaata_no\":\"du2\",\"imp_kd_id\":\"1\",\"imp_khaata_id\":\"2\",\"no1\":\"35\",\"_date1\":\"2024-08-19\",\"recordSubmit\":\"\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"1212-219318293-12\",\"_date2\":\"2024-08-19\",\"letter\":\"8932-IO\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"inv_id_hidden\":\"35\"}', 'these are some terms fo sjdflk', 'via backjsdlkjsd falkdfj aklsdjflk sajd ', NULL, 1, '2024-08-19 22:04:47'),
(36, 'afg', 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2\r\nWEIGHT: 322\r\n', 'DAMAAN GENERAL TRADING L.L.C\r\nADDRESS: AL HABTOOR BUILDING OFFICE NO201AL RAS\r\nAREA,DEIRA,DUBAI BARI\r\nEMAIL:damaan.dubai@gmail.com\r\nMOBILE NO+971507164963', 'DAMAAN GENERAL TRADING L L C\r\nCountry: United Arab Emirates\r\nCity: dubai\r\nState: dubai\r\nAddress: AS RAL\r\n', '2024-08-20', '{\"from_khaata_no\":\"dc1\",\"from_kd_id\":\"2\",\"from_khaata_id\":\"1\",\"imp_khaata_no\":\"du2\",\"imp_kd_id\":\"1\",\"imp_khaata_id\":\"2\",\"no1\":\"36\",\"_date1\":\"2024-08-20\",\"recordSubmit\":\"\",\"afg\":\"Afghan Transit Form Bill Of Loading\",\"no2\":\"32892\",\"_date2\":\"\",\"letter\":\"28923\",\"collection\":\"Collection Basis Da Afghaistan Bank\",\"inv_id_hidden\":\"0\"}', 'terms ajslkdjaf lksdj ', '239823sdjfklsdj flksajd klf asbk bank', NULL, 1, '2024-08-20 21:31:33');

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

--
-- Dumping data for table `afg_inv_details`
--

INSERT INTO `afg_inv_details` (`id`, `parent_id`, `json_data`, `created_at`) VALUES
(8, 6, '{\"qty1\":\"840\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"8400\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"69384.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"6\",\"saveInvoiceItemSubmit\":\"\"}', '2024-06-27 09:18:22'),
(12, 8, '{\"qty1\":\"872\",\"qty2\":\"cartn\",\"qty3\":\"kgs \",\"kgs\":\"8720\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"72027.20\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"8\",\"saveInvoiceItemSubmit\":\"\"}', '2024-06-27 09:32:20'),
(21, 1, '{\"qty1\":\"527\",\"qty2\":\"cartn\",\"qty3\":\"kgs \",\"kgs\":\"6500\",\"goods\":\"walnut kernal \",\"unit_price\":\"8\",\"total_price\":\"52000.00\",\"d_id_hidden\":\"21\",\"inv_id_hidden\":\"1\",\"saveInvoiceItemSubmit\":\"\"}', '2024-06-27 10:03:49'),
(34, 25, '{\"qty1\":\"1400\",\"qty2\":\"ctn\",\"qty3\":\"kgs\",\"kgs\":\"14000\",\"goods\":\" walnut kernal \",\"unit_price\":\"6.20\",\"total_price\":\"86800.00\",\"d_id_hidden\":\"34\",\"inv_id_hidden\":\"25\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 12:54:30'),
(35, 26, '{\"qty1\":\"2200\",\"qty2\":\"cartn\",\"qty3\":\"kgs \",\"kgs\":\"22000\",\"goods\":\" walnut kernal \",\"unit_price\":\"4.40\",\"total_price\":\"96800.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"26\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 19:15:37'),
(36, 27, '{\"qty1\":\"2200\",\"qty2\":\"carton\",\"qty3\":\"kgs\",\"kgs\":\"22000\",\"goods\":\"walnut kernal \",\"unit_price\":\"4.40\",\"total_price\":\"96800.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"27\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 19:18:34'),
(37, 28, '{\"qty1\":\"2200\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"22000\",\"goods\":\"walnut kernal \",\"unit_price\":\"5.50\",\"total_price\":\"121000.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"28\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 19:21:20'),
(38, 29, '{\"qty1\":\"2200\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"22000\",\"goods\":\"walnut kernal \",\"unit_price\":\"5.50\",\"total_price\":\"121000.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"29\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 19:23:58'),
(40, 30, '{\"qty1\":\"2200\",\"qty2\":\"cartn\",\"qty3\":\"kgs \",\"kgs\":\"22000\",\"goods\":\"walnut kernal \",\"unit_price\":\"4.00\",\"total_price\":\"88000.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"30\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 19:35:51'),
(41, 3, '{\"qty1\":\"623\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"6230\",\"goods\":\"walnut kernal \",\"unit_price\":\"7.65\",\"total_price\":\"47659.50\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"3\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:04:21'),
(43, 5, '{\"qty1\":\"650\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"6500\",\"goods\":\"walnut kernal \",\"unit_price\":\"7.00\",\"total_price\":\"45500.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"5\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:07:46'),
(44, 5, '{\"qty1\":\"350\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"3500\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"28910.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"5\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:08:10'),
(45, 7, '{\"qty1\":\"500\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"500\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"4130.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"7\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:09:21'),
(46, 7, '{\"qty1\":\"300\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"3000\",\"goods\":\"walnut kernal \",\"unit_price\":\"7.00\",\"total_price\":\"21000.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"7\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:09:38'),
(47, 8, '{\"qty1\":\"400\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"4000\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"33040.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"8\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:10:21'),
(48, 8, '{\"qty1\":\"200\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"2000\",\"goods\":\"walnut kernal \",\"unit_price\":\"7.00\",\"total_price\":\"14000.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"8\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:10:38'),
(49, 9, '{\"qty1\":\"650\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"6500\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"53690.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"9\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:12:02'),
(50, 10, '{\"qty1\":\"850\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"8500\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"70210.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"10\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:13:08'),
(51, 11, '{\"qty1\":\"900\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"9000\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"74340.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"11\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:14:28'),
(52, 12, '{\"qty1\":\"832\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"8320\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"68723.20\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"12\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:16:01'),
(53, 13, '{\"qty1\":\"672\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"6720\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"55507.20\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"13\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:17:20'),
(54, 14, '{\"qty1\":\"900\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"9000\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"74340.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"14\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:18:31'),
(55, 15, '{\"qty1\":\"872\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"8720\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"72027.20\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"15\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:19:42'),
(56, 16, '{\"qty1\":\"800\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"8000\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"66080.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"16\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:20:54'),
(57, 17, '{\"qty1\":\"840\",\"qty2\":\"crtn\",\"qty3\":\"kgs\",\"kgs\":\"8400\",\"goods\":\" walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"69384.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"17\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:22:21'),
(58, 18, '{\"qty1\":\"900\",\"qty2\":\"crtn\",\"qty3\":\"kgs\",\"kgs\":\"9000\",\"goods\":\" walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"74340.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"18\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:23:31'),
(59, 19, '{\"qty1\":\"1033\",\"qty2\":\"crtn\",\"qty3\":\"kgs\",\"kgs\":\"10330\",\"goods\":\" walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"85325.80\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"19\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-03 21:24:37'),
(60, 20, '{\"qty1\":\"525\",\"qty2\":\"BAGS\",\"qty3\":\"kgs\",\"kgs\":\"26000\",\"goods\":\"TUKHMARIA \",\"unit_price\":\"2.00\",\"total_price\":\"52000.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"20\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-04 07:10:25'),
(61, 21, '{\"qty1\":\"500\",\"qty2\":\"BAGS\",\"qty3\":\"kgs\",\"kgs\":\"25000\",\"goods\":\"TUKHMARIA \",\"unit_price\":\"2.00\",\"total_price\":\"50000.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"21\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-04 07:13:03'),
(62, 22, '{\"qty1\":\"500\",\"qty2\":\"BAGS\",\"qty3\":\"kgs\",\"kgs\":\"25000\",\"goods\":\"TUKHMARIA \",\"unit_price\":\"2.00\",\"total_price\":\"50000.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"22\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-04 07:15:23'),
(63, 23, '{\"qty1\":\"525\",\"qty2\":\"BAGS\",\"qty3\":\"kgs\",\"kgs\":\"26000\",\"goods\":\"TUKHMARIA \",\"unit_price\":\"2.00\",\"total_price\":\"52000.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"23\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-04 07:18:05'),
(64, 24, '{\"qty1\":\"520\",\"qty2\":\"BAGS\",\"qty3\":\"kgs\",\"kgs\":\"26000\",\"goods\":\"TUKHMARIA \",\"unit_price\":\"2.00\",\"total_price\":\"52000.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"24\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-04 07:33:05'),
(66, 31, '{\"qty1\":\"1072\",\"qty2\":\"cartn\",\"qty3\":\"kgs \",\"kgs\":\"10720\",\"goods\":\"walnut kernel\",\"unit_price\":\"8.26\",\"total_price\":\"88547.20\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"31\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-05 18:29:37'),
(67, 2, '{\"qty1\":\"2200\",\"qty2\":\"CTNS\",\"qty3\":\"KGS\",\"kgs\":\"22000\",\"goods\":\" WALNUT KERNELS\",\"unit_price\":\"5.50\",\"total_price\":\"121000.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"2\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-09 14:35:54'),
(68, 32, '{\"qty1\":\"2200\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"22000\",\"goods\":\"walnut kernal \",\"unit_price\":\"4.80\",\"total_price\":\"105600.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"32\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-24 17:20:15'),
(69, 33, '{\"qty1\":\"1262\",\"qty2\":\"cartn\",\"qty3\":\"kgs\",\"kgs\":\"12620\",\"goods\":\"walnut kernal \",\"unit_price\":\"8.26\",\"total_price\":\"104241.20\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"33\",\"saveInvoiceItemSubmit\":\"\"}', '2024-07-24 18:02:51'),
(70, 4, '{\"qty1\":\"78\",\"qty2\":\"bags\",\"qty3\":\"kgs\",\"kgs\":\"199\",\"goods\":\"sdlfj\",\"unit_price\":\"199\",\"total_price\":\"39601.00\",\"d_id_hidden\":\"0\",\"inv_id_hidden\":\"4\",\"saveInvoiceItemSubmit\":\"\"}', '2024-08-19 21:41:48');

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
(1, 61, 'purchase_contract', 'Ledger_DC29_2024_09_16.pdf', '2024-09-16 23:07:02'),
(2, 59, 'purchase_contract', 'WhatsApp Image 2024-09-04 at 10.00.12 PM (1).jpeg', '2024-09-16 23:07:43'),
(3, 44, 'purchase_contract', 'Ledger_DC29_2024_09_16.pdf', '2024-09-16 23:08:00'),
(4, 47, 'purchase_contract', 'Ledger_DC29_2024_09_16.pdf', '2024-09-16 23:08:18'),
(5, 56, 'purchase_contract', 'Contract34_2024_09_07-15_11_41.pdf', '2024-09-16 23:09:02'),
(6, 43, 'purchase_contract', 'LEDGER_ALL_CATEGORIES_2024_09_11.pdf', '2024-09-16 23:09:26'),
(7, 49, 'purchase_contract', 'LEDGER_ALL_CATEGORIES_2024_09_111.pdf', '2024-09-16 23:09:45'),
(8, 54, 'purchase_contract', 'Contract34_2024_09_07-15_11_41.pdf', '2024-09-16 23:09:57'),
(9, 5, 'purchase_contract', 'Contract34_2024_09_07-15_11_41.pdf', '2024-09-16 23:11:56'),
(10, 7, 'purchase_contract', 'file.enc', '2024-09-16 23:12:43');

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
(1, 1, 'DB', 'BANK CATEGORY', '2023-12-09 21:30:00', 1),
(2, 1, 'DC', 'CUSTOMER CATEGORY', '2023-12-09 21:28:54', 1),
(4, 1, 'DU', 'DUBAI EXPENSES CATEGORY', '2023-12-09 21:30:34', 1),
(6, 1, 'DG', 'CLEARING AGENT/WAREHOUSE CATEGORY', '2023-12-09 21:31:48', 1),
(7, 1, 'DA', 'INVESTMENT ACCOUNT', '2023-12-13 22:32:26', 1),
(10, 1, 'DP', 'SALE & PURCHASE', '2024-07-27 16:37:16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

--
-- Dumping data for table `exchanges`
--

INSERT INTO `exchanges` (`id`, `p_s`, `curr1`, `qty`, `per_price`, `opr`, `curr2`, `amount`, `details`, `khaata_exchange`, `branch_id`, `created_at`) VALUES
(1, 'p', 'IRR', 1130000000, 16650, '/', 'AED', 67867.87, ' khan m ot accontan fareed ullah ', '{\"dr_khaata_no\":\"DC1\",\"dr_khaata_id\":\"1\",\"cr_khaata_no\":\"DC5\",\"cr_khaata_id\":\"5\",\"transfer_date\":\"2024-07-02\",\"first_amount\":\"67867.87\",\"aed_rate\":\"1\",\"opr\":\"/\",\"final_amount\":\"67867.87\",\"details\":\"IRR 1130000000 16650 AED 67867.87 :Lee Khan Tsafar Kiya\",\"exch_id_hidden\":\"1\",\"type\":\"\",\"r_id\":[\"67\",\"68\"]}', 1, '2024-06-29 23:43:36'),
(2, 's', 'IRR', 1130000000, 16000, '/', 'AED', 70625, 'bismullah sles ', '{\"dr_khaata_no\":\"dc1\",\"dr_khaata_id\":\"1\",\"cr_khaata_no\":\"dc3\",\"cr_khaata_id\":\"3\",\"transfer_date\":\"2024-08-13\",\"first_amount\":\"70625\",\"aed_rate\":\"3\",\"opr\":\"*\",\"final_amount\":\"211875\",\"details\":\"IRR 1130000000 16000 AED 70625(Lee Khan Tsafar Kiya)\",\"exch_id_hidden\":\"2\",\"type\":\"\",\"r_id\":[\"65\",\"66\"]}', 1, '2024-06-29 23:44:26'),
(6, 'p', 'IRR', 1200000000, 16650, '/', 'AED', 72072.07, 'khan m transit me kibll me ', '{\"dr_khaata_no\":\"dg11\",\"dr_khaata_id\":\"16\",\"cr_khaata_no\":\"DP00005\",\"cr_khaata_id\":\"55\",\"transfer_date\":\"2024-07-05\",\"first_amount\":\"72072.07\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"72072.07\",\"details\":\"IRR 1200000000 16650 AED 72072.07 to  fareed \",\"exch_id_hidden\":\"6\",\"type\":\"\",\"r_id\":[\"942\",\"943\"]}', 1, '2024-07-05 21:27:04'),
(7, 's', 'IRR', 1200000000, 16000, '/', 'AED', 75000, 'fareed accounts me tr ke', '{\"dr_khaata_no\":\"DP00005\",\"dr_khaata_id\":\"55\",\"cr_khaata_no\":\"dc1\",\"cr_khaata_id\":\"1\",\"transfer_date\":\"2024-07-05\",\"first_amount\":\"75000\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"75000\",\"details\":\"IRR 1200000000 16000 AED 75000 fareed ullah \",\"exch_id_hidden\":\"7\",\"type\":\"\"}', 1, '2024-07-05 21:30:01'),
(8, 'p', 'IRR', 1500000000, 16450, '/', 'AED', 91185.41, 'haji khan to fareed Purchase ke ', '{\"dr_khaata_no\":\"dg11\",\"dr_khaata_id\":\"16\",\"cr_khaata_no\":\"DP00005\",\"cr_khaata_id\":\"55\",\"transfer_date\":\"2024-07-05\",\"first_amount\":\"91185.41\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"91185.41\",\"details\":\"IRR 1500000000 16450 AED 91185.41 khan faree ullah ne to Purchase\",\"exch_id_hidden\":\"8\",\"type\":\"\"}', 1, '2024-07-05 21:33:14'),
(9, 's', 'IRR', 1500000000, 16450, '/', 'AED', 91185.41, 'fareed accounts me tr ke ', '{\"dr_khaata_no\":\"dp00005\",\"dr_khaata_id\":\"55\",\"cr_khaata_no\":\"dc1\",\"cr_khaata_id\":\"1\",\"transfer_date\":\"2024-07-05\",\"first_amount\":\"91185.41\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"91185.41\",\"details\":\"IRR 1500000000 16450 AED 91185.41\",\"exch_id_hidden\":\"9\",\"type\":\"\"}', 1, '2024-07-05 21:34:50'),
(10, 'p', 'IRR', 1142000000, 16000, '/', 'AED', 71375, 'bism ullah to dc1 accoutsan me ', '{\"dr_khaata_no\":\"dc56\",\"dr_khaata_id\":\"42\",\"cr_khaata_no\":\"dp00005\",\"cr_khaata_id\":\"55\",\"transfer_date\":\"2024-07-05\",\"first_amount\":\"71375\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"71375\",\"details\":\"IRR 1142000000 16000 AED 71375 Bism ullah to queeta Accounts me to\",\"exch_id_hidden\":\"10\",\"type\":\"\"}', 1, '2024-07-05 21:37:37'),
(11, 's', 'IRR', 1142000000, 16000, '/', 'AED', 71375, 'Bismullah  ', '{\"dr_khaata_no\":\"dp00005\",\"dr_khaata_id\":\"55\",\"cr_khaata_no\":\"dc1\",\"cr_khaata_id\":\"1\",\"transfer_date\":\"2024-07-05\",\"first_amount\":\"71375\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"71375\",\"details\":\"INR 1142000000 16000 AED 71375 bismullah bill \",\"exch_id_hidden\":\"11\",\"type\":\"\"}', 1, '2024-07-05 21:39:14'),
(12, 'p', 'IRR', 1200000000, 15930, '/', 'AED', 75329.57, 'KHAN TO BISMULLAH TR ', '{\"dr_khaata_no\":\"DC58\",\"dr_khaata_id\":\"57\",\"cr_khaata_no\":\"DP00005\",\"cr_khaata_id\":\"55\",\"transfer_date\":\"2024-07-11\",\"first_amount\":\"75329.57\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"75329.57\",\"details\":\"IRR 1200000000 15930 AED 75329.57 BISMULLAK TR\",\"exch_id_hidden\":\"12\",\"type\":\"\",\"r_id\":[\"966\",\"967\"]}', 1, '2024-07-11 01:40:41'),
(15, 's', 'INR', 4720000, 23.6, '/', 'AED', 200000, 'kamlim purchasa ', '{\"dr_khaata_no\":\"dc1\",\"dr_khaata_id\":\"1\",\"cr_khaata_no\":\"dc5\",\"cr_khaata_id\":\"5\",\"transfer_date\":\"2024-07-15\",\"first_amount\":\"200000\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"200000\",\"details\":\"INR 4720000 23.6 AED 200000( kaml purchase\",\"exch_id_hidden\":\"15\",\"type\":\"\"}', 1, '2024-07-15 15:34:28'),
(16, 'p', 'INR', 4720000, 22.75, '/', 'AED', 207472.53, 'kamlim purchasa  bank me', '{\"dr_khaata_no\":\"dp00005\",\"dr_khaata_id\":\"55\",\"cr_khaata_no\":\"dc13\",\"cr_khaata_id\":\"12\",\"transfer_date\":\"2024-07-15\",\"first_amount\":\"207472.53\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"207472.53\",\"details\":\"INR 4720000 22.75 AED 207472.53( kaml sale\",\"exch_id_hidden\":\"16\",\"type\":\"\",\"r_id\":[\"980\",\"981\"]}', 1, '2024-07-15 15:35:08'),
(18, 'p', 'INR', 3142370, 22.75, '/', 'AED', 138126.15, 'kamlim purchasa  kumar qyt 583 ctan ', '{\"dr_khaata_no\":\"dp00005\",\"dr_khaata_id\":\"55\",\"cr_khaata_no\":\"dc13\",\"cr_khaata_id\":\"12\",\"transfer_date\":\"2024-07-15\",\"first_amount\":\"138126.15\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"138126.15\",\"details\":\"INR 3142370 22.75 AED 138126.15  kumar qyt 583 ctan \",\"exch_id_hidden\":\"18\",\"type\":\"\",\"r_id\":[\"986\",\"987\"]}', 1, '2024-07-15 15:57:24'),
(19, 's', 'INR', 3142370, 23.6, '/', 'AED', 133151.27, 'kamli kumr qyt 583', '{\"dr_khaata_no\":\"m1\",\"dr_khaata_id\":\"43\",\"cr_khaata_no\":\"dp00005\",\"cr_khaata_id\":\"55\",\"transfer_date\":\"2024-07-24\",\"first_amount\":\"133151.27\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"133151.27\",\"details\":\"INR 3142370 23.6 AED 133151.27\",\"exch_id_hidden\":\"19\",\"type\":\"\",\"r_id\":[\"997\",\"998\"]}', 1, '2024-07-24 15:57:55'),
(28, 's', 'IRR', 1200000000, 15830, '/', 'AED', 75805.43, 'bisim ullah to', '{\"dr_khaata_no\":\"dp00005\",\"dr_khaata_id\":\"55\",\"cr_khaata_no\":\"dc1\",\"cr_khaata_id\":\"1\",\"transfer_date\":\"2024-07-24\",\"first_amount\":\"75805.43\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"75805.43\",\"details\":\"IRR 1200000000 15830 AED 75805.43\",\"exch_id_hidden\":\"28\",\"type\":\"\"}', 1, '2024-07-24 17:30:58'),
(29, 'p', 'USD', 12500, 3.66, '*', 'AED', 45750, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', '{\"dr_khaata_no\":\"dc1\",\"dr_khaata_id\":\"1\",\"cr_khaata_no\":\"dc6\",\"cr_khaata_id\":\"6\",\"transfer_date\":\"2024-09-06\",\"first_amount\":\"45750\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"45750\",\"details\":\"USD 12500 3.66 AED 45750\",\"exch_id_hidden\":\"29\",\"type\":\"\"}', 1, '2024-09-06 19:38:16'),
(30, 'p', 'USD', 123000, 3.66, '*', 'INR', 450180, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', '{\"dr_khaata_no\":\"dc1\",\"dr_khaata_id\":\"1\",\"cr_khaata_no\":\"dc6\",\"cr_khaata_id\":\"6\",\"transfer_date\":\"2024-09-06\",\"first_amount\":\"450180\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"450180\",\"details\":\"USD 123000 3.66 INR 450180\",\"exch_id_hidden\":\"30\",\"type\":\"\"}', 1, '2024-09-06 19:39:01'),
(31, 'p', 'AED', 25000, 3.66, '*', 'AED', 91500, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', '{\"dr_khaata_no\":\"dc1\",\"dr_khaata_id\":\"1\",\"cr_khaata_no\":\"dc6\",\"cr_khaata_id\":\"6\",\"transfer_date\":\"2024-09-06\",\"first_amount\":\"91500\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"91500\",\"details\":\"AED 25000 3.66 AED 91500\",\"exch_id_hidden\":\"31\",\"type\":\"\"}', 1, '2024-09-06 19:39:20'),
(32, 'p', 'USD', 25000, 3.67, '*', 'AED', 91750, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', NULL, 1, '2024-09-06 19:39:41'),
(33, 'p', 'AED', 230000, 3.67, '*', 'AED', 844100, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', NULL, 1, '2024-09-06 19:39:57'),
(34, 'p', 'AED', 1245000, 23, '*', 'USD', 28635000, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', NULL, 1, '2024-09-06 19:40:27'),
(35, 'p', 'USD', 23000, 3.65, '*', 'AED', 83950, ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام ', NULL, 1, '2024-09-06 19:40:44'),
(36, 'p', 'AED', 36000, 3.67, '*', 'AED', 132120, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', NULL, 1, '2024-09-06 19:41:00'),
(37, 's', 'IRR', 25000000, 3.67, '*', 'INR', 91750000, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', NULL, 1, '2024-09-06 19:42:22'),
(38, 'p', 'PKR', 250000, 3.67, '*', 'PKR', 917500, 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD', NULL, 1, '2024-09-06 19:42:38'),
(39, 's', 'AED', 23000, 3.67, '*', 'AED', 84410, 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f', NULL, 1, '2024-09-06 19:42:54'),
(40, 's', 'AED', 250000, 3.67, '*', 'AED', 917500, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', NULL, 1, '2024-09-06 19:43:11'),
(41, 'p', 'AED', 230000, 3.67, '*', 'PKR', 844100, 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD', '{\"dr_khaata_no\":\"dc1\",\"dr_khaata_id\":\"1\",\"cr_khaata_no\":\"dc6\",\"cr_khaata_id\":\"6\",\"transfer_date\":\"2024-09-06\",\"first_amount\":\"844100\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"844100\",\"details\":\"AED 230000 3.67 PKR 844100\",\"exch_id_hidden\":\"41\",\"type\":\"\"}', 1, '2024-09-06 19:43:29'),
(42, 'p', 'AED', 23000, 230, '*', 'AED', 5290000, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', NULL, 1, '2024-09-06 19:43:57'),
(43, 'p', 'AED', 256000, 3.6, '*', 'USD', 921600, 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', '{\"dr_khaata_no\":\"dc1\",\"dr_khaata_id\":\"1\",\"cr_khaata_no\":\"dc6\",\"cr_khaata_id\":\"6\",\"transfer_date\":\"2024-09-06\",\"first_amount\":\"921600\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"921600\",\"details\":\"AED 256000 3.6 USD 921600\",\"exch_id_hidden\":\"43\",\"type\":\"\"}', 1, '2024-09-06 19:44:25'),
(44, 'p', 'AED', 250000, 3.67, '*', 'INR', 917500, 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', '{\"dr_khaata_no\":\"dc1\",\"dr_khaata_id\":\"1\",\"cr_khaata_no\":\"dc6\",\"cr_khaata_id\":\"6\",\"transfer_date\":\"2024-09-06\",\"first_amount\":\"917500\",\"aed_rate\":\"1\",\"opr\":\"*\",\"final_amount\":\"917500\",\"details\":\"AED 250000 3.67 INR 917500\",\"exch_id_hidden\":\"44\",\"type\":\"\"}', 1, '2024-09-06 19:45:05'),
(45, 'p', 'USD', 125000, 3.66, '*', 'AED', 457500, 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', NULL, 1, '2024-09-06 20:17:45'),
(46, 'p', 'USD', 1256000, 20, '*', 'INR', 25120000, 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', NULL, 1, '2024-09-06 20:18:02'),
(47, 'p', 'USD', 230000, 250, '/', 'AED', 920, 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', NULL, 1, '2024-09-06 20:18:24'),
(48, 'p', 'AED', 120000, 23, '*', 'INR', 2760000, 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', NULL, 1, '2024-09-06 20:18:42'),
(49, 'p', 'USD', 125000, 3.67, '*', 'INR', 458750, 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', NULL, 1, '2024-09-06 20:18:59'),
(50, 'p', 'USD', 123000, 23, '*', 'INR', 2829000, 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', NULL, 1, '2024-09-06 20:19:14'),
(51, 'p', 'USD', 13000, 23, '*', 'PKR', 299000, 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', NULL, 1, '2024-09-06 20:19:29'),
(52, 's', 'INR', 230000, 23, '*', 'PKR', 5290000, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', NULL, 1, '2024-09-06 20:19:55'),
(53, 's', 'AED', 250000, 36, '*', 'USD', 9000000, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', '{\"dr_khaata_no\":\"db70\",\"dr_khaata_id\":\"73\",\"cr_khaata_no\":\"db75\",\"cr_khaata_id\":\"78\",\"transfer_date\":\"2024-09-06\",\"first_amount\":\"9000000\",\"aed_rate\":\"1\",\"opr\":\"/\",\"final_amount\":\"9000000\",\"details\":\"AED 250000 36 USD 9000000\",\"exch_id_hidden\":\"53\",\"type\":\"\"}', 1, '2024-09-06 20:21:27'),
(54, 'p', 'AED', 250000, 3.67, '*', 'USD', 917500, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', '{\"dr_khaata_no\":\"db69\",\"dr_khaata_id\":\"72\",\"cr_khaata_no\":\"db68\",\"cr_khaata_id\":\"71\",\"transfer_date\":\"2024-09-06\",\"first_amount\":\"917500\",\"aed_rate\":\"2\",\"opr\":\"/\",\"final_amount\":\"458750\",\"details\":\"AED 250000 3.67 USD 917500\",\"exch_id_hidden\":\"54\",\"type\":\"\"}', 1, '2024-09-06 20:22:25'),
(55, 's', 'AED', 1000000000, 2, '/', 'USD', 500000000, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', '{\"dr_khaata_no\":\"dc1\",\"dr_khaata_id\":\"1\",\"cr_khaata_no\":\"db1\",\"cr_khaata_id\":\"26\",\"transfer_date\":\"2024-09-06\",\"first_amount\":\"500000000\",\"aed_rate\":\"23\",\"opr\":\"/\",\"final_amount\":\"21739130.43478261\",\"details\":\"AED 1000000000 2 USD 500000000\",\"exch_id_hidden\":\"55\",\"type\":\"\"}', 1, '2024-09-06 20:23:12'),
(56, 'p', 'INR', 2500000000000, 201, '/', 'PKR', 12437810945.27, 'INVESTMENT ACOUNTs to transferred  me Total alculation  ', '{\"dr_khaata_no\":\"db69\",\"dr_khaata_id\":\"72\",\"cr_khaata_no\":\"db68\",\"cr_khaata_id\":\"71\",\"transfer_date\":\"2024-09-06\",\"first_amount\":\"12437810945.27\",\"aed_rate\":\"2\",\"opr\":\"*\",\"final_amount\":\"24875621890.54\",\"details\":\"INR 2500000000000 201 PKR 12437810945.27\",\"exch_id_hidden\":\"56\",\"type\":\"\"}', 1, '2024-09-06 20:23:30');

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
(1, 'WALNUT WHITE', '2024-01-04 20:05:46', '2024-01-04 21:09:06'),
(2, 'BADAM', '2024-01-04 20:28:37', '2024-01-04 21:02:20'),
(3, 'WALNUT KERNELS', '2024-01-04 23:55:42', '2024-02-17 01:56:27'),
(4, 'BETEL NUTS', '2024-01-05 00:02:27', '2024-01-05 00:05:41'),
(5, 'ALMOND KERNELS ', '2024-01-05 00:06:59', '2024-01-28 21:03:08'),
(6, 'BLACK RAISIN', '2024-01-09 18:42:25', '2024-01-10 13:32:26'),
(7, 'DRY PIGS (END)', '2024-01-10 13:14:02', '2024-01-10 13:14:27'),
(8, 'AFRICAD', '2024-01-10 13:15:01', NULL),
(9, 'WALNUT IN SHELL', '2024-01-10 13:44:04', '2024-01-12 20:21:42'),
(10, 'BLACK PAPER', '2024-01-12 20:24:11', NULL),
(11, ' FRESH ONIONS', '2024-01-14 17:55:10', NULL),
(12, 'BSSL SEED ', '2024-02-07 13:26:01', NULL),
(13, 'CARDAMOM GUATEMALA ', '2024-04-29 17:55:45', '2024-06-03 13:37:37'),
(15, 'CARDAMOM AKBAR ', '2024-06-03 13:21:19', NULL);

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
(1, 2, '1122', 'CHAMAN', 'PAKISTAN', '2024-01-04 21:28:37'),
(3, 2, '36', 'pak', 'saif', '2024-01-04 21:54:28'),
(4, 1, '185', 'YUNNAN', 'chian', '2024-01-04 21:56:33'),
(5, 1, '90% ', 'YUNNAN', 'chian', '2024-01-04 21:57:37'),
(6, 3, '100 GRAM/25PIECE', 'YUNNAN/CHIAN', 'CHIAN', '2024-01-04 19:55:42'),
(7, 3, '100 GRAM/25PIECE', 'XINFU/CHIAN', 'CHIAN', '2024-01-04 19:55:58'),
(8, 3, '100 GRAM/25/30 PIECE', 'U$', 'U$', '2024-01-04 20:00:35'),
(9, 4, '80\\85', 'JEMBO', 'INDNOSHIA', '2024-01-04 20:02:27'),
(10, 4, '90/95', 'JEMBO', 'INDNOSHIA', '2024-01-04 20:02:41'),
(11, 4, '60/65', 'JEMBO', 'INDNOSHIA', '2024-01-04 20:03:25'),
(12, 4, '70/75', 'JEMBO', 'INDNOSHIA', '2024-01-04 20:03:51'),
(13, 4, '60/65', 'MADEN', 'INDNOSHIA', '2024-01-04 20:04:42'),
(14, 4, '70/75', 'MADEN', 'INDNOSHIA', '2024-01-04 20:05:06'),
(15, 4, '80/85', 'MADEN', 'INDNOSHIA', '2024-01-04 20:05:21'),
(16, 4, '90/95', 'MADEN', 'INDNOSHIA', '2024-01-04 20:05:41'),
(17, 5, 'NPX.20/22', 'NPX', 'U$', '2024-01-04 20:06:59'),
(18, 5, '22/24', 'NPX', 'U$', '2024-01-04 20:07:10'),
(19, 5, '24/26', 'NPX', 'U$', '2024-01-04 20:07:23'),
(20, 5, '26/28', 'NPX', 'U$', '2024-01-04 20:07:35'),
(21, 5, '28/30', 'NPX', 'U$', '2024-01-04 20:07:48'),
(22, 5, '30/32', 'NPX', 'U$', '2024-01-04 20:08:00'),
(23, 5, '32/34', 'NPX', 'U$', '2024-01-04 20:08:11'),
(24, 5, '34/36', 'NPX', 'U$', '2024-01-04 20:08:29'),
(25, 6, '180/190', 'DGT', 'AFGHISTAN', '2024-01-09 14:42:25'),
(26, 7, 'JOMBO SUPER', 'DGT', 'AFGHISTAN', '2024-01-10 09:14:02'),
(27, 7, 'JOMBO ', 'DGT', 'AFGHISTAN', '2024-01-10 09:14:13'),
(28, 7, 'MEDIUM', 'DGT', 'AFGHISTAN', '2024-01-10 09:14:27'),
(29, 8, 'JOMBO ', 'DGT', 'AFGHISTAN', '2024-01-10 09:15:01'),
(30, 3, '100 GRAM/42PIECE', 'UZB/DGT', 'UZBEKISTAN', '2024-01-10 09:31:37'),
(31, 6, '100 GRAM130/140', 'DGT', 'uzbekistan', '2024-01-10 09:32:26'),
(32, 9, '30/32', 'DGT', 'CHILI', '2024-01-10 09:44:04'),
(33, 9, '34/36', 'DGT', 'CHILI', '2024-01-10 09:44:14'),
(34, 9, 'JOMBO ', 'DGT', 'U$', '2024-01-10 09:44:42'),
(35, 9, '30/32', 'DGT', 'AFGHISTAN', '2024-01-12 16:21:42'),
(36, 10, '5 MIM', 'DGT', 'VIETNAM', '2024-01-12 16:24:11'),
(37, 5, '1/PIECA 2', 'DGT', 'UZBEKISTAN', '2024-01-13 09:41:34'),
(38, 5, '100 GRAM/45 PIECE', 'DGT', 'UZBEKISTAN', '2024-01-13 09:42:35'),
(39, 3, '100 GRAM/95PIECE', 'UZB/DGT', 'UZBEKISTAN', '2024-01-13 09:44:26'),
(40, 3, '100 GRAM/25/30 PIECE', 'CHILAI', 'CHIALI', '2024-01-13 09:44:39'),
(41, 11, 'RED COLOR', 'DGT', 'UZBEKISTAN', '2024-01-14 13:55:10'),
(42, 5, '100 GRAM/90 PIECE', 'DGT', 'KERGISTAN', '2024-01-15 09:49:46'),
(43, 3, '100 GRAM/42 PIECE', 'KYR/DGT', 'KYRGISTAN', '2024-01-15 09:50:31'),
(44, 3, '100 GRAM/180 PIECE', 'KYR/DGT', 'KYRGISTAN', '2024-01-15 09:50:42'),
(45, 12, '0', 'DG', 'AFGHANI', '2024-02-07 09:26:01'),
(46, 13, '8mm', 'AKBAR', 'india', '2024-04-29 13:55:45'),
(47, 13, '8 MA', 'AKBAR', 'INDIA', '2024-04-29 13:56:22'),
(48, 13, '7 MA', 'SB', 'U$', '2024-05-29 11:12:49'),
(49, 13, '8 MA', 'KAB', 'INDAI', '2024-05-29 11:13:06'),
(52, 13, '1kg ', 'akbar', 'india', '2024-05-29 13:46:05'),
(53, 13, '8MM', 'AKBAR', 'INDIA', '2024-06-03 09:20:00'),
(54, 15, '8MM 10*1KG', 'AKBAR', 'INDIA', '2024-06-03 09:21:19');

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
(1, 1, 1, 2, 'client', 'DC1', 'QUETTA OFFICE', 'msaif3850@gmail.com', '03057434424', NULL, '{\"full_name\":\"NAJEEBULLAH\",\"father_name\":\"ABDULLAH\",\"gender\":\"male\",\"identity\":\"cnic\",\"idn_no\":\"542014745352-5\",\"idn_reg\":\"2024-07-29\",\"idn_expiry\":\"2024-07-29\",\"idn_country\":\"pk\",\"country\":\"Pakistan\",\"state\":\"BALOCHISTAN\",\"city\":\"CHAMAN\",\"address\":\"slkdjflk sdjfklas jdklfj sda\",\"postcode\":\"0000\",\"mobile\":\"+923337764088\",\"phone\":\"+92812820432\",\"whatsapp\":\"+923168000339\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"1\"}', NULL, '2024-07-29 17:25:29', 1, '2024-08-11 14:48:42', 1),
(2, 1, 1, 4, 'client', 'DU2', 'OFFICE E', 'ibrahim@gmail.com', '03139329', NULL, '{\"full_name\":\"ASMATULLAH \",\"father_name\":\"ABDULLAH\",\"gender\":\"male\",\"identity\":\"uae\",\"idn_no\":\"784-1987-8811995-7\",\"idn_reg\":\"2024-06-04\",\"idn_expiry\":\"2024-08-01\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"AS RAL\",\"postcode\":\"0000\",\"mobile\":\"+971544816664\",\"phone\":\"+97142278608\",\"whatsapp\":\"+971544816664\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"2\"}', NULL, '2024-07-29 17:34:50', 1, '2024-08-11 14:48:55', 1),
(3, 1, 1, 2, 'client', 'DC3', 'CHAMAN OFFICE/AGENT', 'anitcoq@gmail.com', '+923188088901', NULL, '{\"full_name\":\"NASEEBULLAH\",\"father_name\":\"ABDULLAH\",\"gender\":\"male\",\"identity\":\"cnic\",\"idn_no\":\"00000000\",\"idn_reg\":\"2023-01-06\",\"idn_expiry\":\"2024-01-06\",\"idn_country\":\"PAKISTAN\",\"country\":\"BILS T\",\"state\":\"BALOCHISTAN\",\"city\":\"CHAMAN\",\"address\":\"SANATAN BAZAR HIDAYAT THE PLAZA \",\"postcode\":\"78600\",\"mobile\":\"+923023988899\",\"phone\":\"+92826614073\",\"whatsapp\":\"+923023988899\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"3\"}', NULL, '2024-07-29 21:06:26', 1, '2024-08-11 14:49:42', 1),
(4, 1, 1, 4, 'agent', 'DU4', 'Customs Clearing Account', 'dgtllc@dgt.llc', '+9714228000', NULL, '{\"full_name\":\"ASMATULLAH \",\"father_name\":\"ABDULLAH\",\"gender\":\"male\",\"idn_no\":\"784-1987-8811995-7\",\"idn_reg\":\"2024-11-06\",\"idn_expiry\":\"2034-10-06\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"AS RAL\",\"postcode\":\"+97142278608\",\"mobile\":\"+971544816664\",\"phone\":\"+97142278608\",\"whatsapp\":\"+971544816664\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"4\"}', NULL, '2024-07-31 13:04:35', 1, '2024-08-11 14:49:52', 1),
(5, 1, 1, 2, 'client', 'DC5', 'KUMAR CUSTOM CLEARING AGENT', 'NIL@gamil.com', '+971502705588', NULL, NULL, NULL, '2024-07-31 13:48:23', 1, '2024-09-09 20:57:58', 1),
(6, 1, 1, 2, 'client', 'dc6', 'SANJAY BROKER', 'DAMODAREXPORTS43@GMAIL.COM', '+918879762371', NULL, NULL, NULL, '2024-07-31 13:50:00', 1, '2024-08-12 15:21:26', 1),
(7, 1, 1, 2, 'agent', 'DC7', 'VAJI AY ', 'NIL@gamil.com', '+919417056746', NULL, NULL, NULL, '2024-07-31 13:52:34', 1, '2024-08-11 14:50:20', 1),
(8, 1, 1, 10, 'client', 'DP8', 'PURCHASE&SALES ', 'dgtllc@dgt.llc', '+97142278608', NULL, '{\"full_name\":\"ASMATULLAH ABDULLAH\",\"father_name\":\"ABDULLAH\",\"gender\":\"male\",\"identity\":\"uae\",\"idn_no\":\"784-1987-8811995-7\",\"idn_reg\":\"2024-11-06\",\"idn_expiry\":\"2034-10-06\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"AS RAL\",\"postcode\":\"+97142278608\",\"mobile\":\"0544816664\",\"phone\":\"042278608\",\"whatsapp\":\"+971544816664\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"8\"}', NULL, '2024-07-31 14:01:03', 1, '2024-08-11 14:50:53', 1),
(9, 1, 1, 10, 'client', 'DU9', 'EXCHANGES', 'dgtllc@dgt.llc', '+97142278608', NULL, '{\"full_name\":\"ASMATULLAH ABDULLAH\",\"father_name\":\"ABDULLAH\",\"gender\":\"male\",\"identity\":\"uae\",\"idn_no\":\"784-1987-8811995-7\",\"idn_reg\":\"2024-06-11\",\"idn_expiry\":\"2034-06-10\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"AS RAL\",\"postcode\":\"+97142278608\",\"mobile\":\"0544816664\",\"phone\":\"0544816664\",\"whatsapp\":\"+971544816664\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"9\"}', NULL, '2024-07-31 14:03:28', 1, '2024-08-11 14:51:06', 1),
(10, 1, 1, 4, 'client', 'DU10', 'TRANSET FARM ACOUNT', 'dgtllc@dgt.llc', '+9142278608', NULL, NULL, NULL, '2024-07-31 14:05:39', 1, '2024-08-11 14:52:02', 1),
(11, 1, 1, 4, 'client', 'DU11', 'HAJI ASMATULLAH PERSONAL', 'asmat@dgt.llc', '+9142278608', NULL, '{\"full_name\":\"ASMATULLAH ABDULLAH\",\"father_name\":\"ABDULLAH\",\"idn_no\":\"784-1987-8811995-7\",\"idn_reg\":\"2024-11-06\",\"idn_expiry\":\"2034-11-06\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"AS RAL\",\"postcode\":\"+97142278608\",\"mobile\":\"0544816664\",\"phone\":\"0544816664\",\"whatsapp\":\"+971544816664\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"11\"}', NULL, '2024-07-31 14:07:42', 1, '2024-08-11 14:52:53', 1),
(12, 1, 1, 4, 'client', 'DU12', 'NAJEEBULLAH ACCOUNTS', 'Najeeb@dgt.llc', '+971561202687', NULL, NULL, NULL, '2024-07-31 14:14:37', 1, '2024-08-11 14:49:07', 1),
(13, 1, 1, 4, 'client', 'DU13', 'FAREEDULLAH HAJI', 'fareed@dgt.llc', '+971502817143', NULL, NULL, NULL, '2024-07-31 14:16:27', 1, '2024-08-11 14:49:19', 1),
(14, 1, 1, 2, 'client', 'DC14', 'MUZAMMIL/HAJI AKHTERE', 'contacts@dgt.llc', '+971500000', NULL, NULL, NULL, '2024-07-31 14:18:50', 1, '2024-08-11 14:53:05', 1),
(15, 1, 1, 2, 'client', 'DC15', 'KUAM', 'NIL@Ggmail.com', '02143000022', NULL, NULL, NULL, '2024-07-31 14:22:38', 1, '2024-08-11 14:53:18', 1),
(16, 1, 1, 2, 'client', 'DA16', 'INVESTMENT ACOUNT', 'asmat@dgt.llc', '+971544816664', NULL, '{\"full_name\":\"ASMATULLAH \",\"father_name\":\"ABDULLAH\",\"gender\":\"male\",\"identity\":\"uae\",\"idn_no\":\"786000000\",\"idn_reg\":\"2024-08-01\",\"idn_expiry\":\"2024-08-01\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"AS RAL\",\"postcode\":\"0000\",\"mobile\":\"+971544816664\",\"phone\":\"+92812820432\",\"whatsapp\":\"+923168000339\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"16\"}', NULL, '2024-07-31 14:30:39', 1, '2024-09-06 19:32:53', 1),
(17, 1, 1, 2, 'client', 'DC17', 'BILAL NOORZAI', 'NIL@Ggmail.com', '+971588545476', NULL, NULL, NULL, '2024-07-31 14:32:28', 1, '2024-08-11 14:53:33', 1),
(18, 1, 1, 2, 'agent', 'DC18', 'AYAZ NOORI LTD', 'NIL@Ggmail.com', '+93790107000', NULL, NULL, NULL, '2024-07-31 14:33:49', 1, '2024-08-11 14:53:44', 1),
(19, 1, 1, 2, 'client', 'DC19', 'FARM KHATAMZHAN LEGAL', 'NIL@Ggmail.com', '+996707594654', NULL, NULL, NULL, '2024-07-31 14:41:38', 1, '2024-08-11 14:53:54', 1),
(20, 1, 1, 2, 'client', 'DC20', 'KHAN MOHMMAD ', 'NIL@Ggmail.com', '+93728323546', NULL, NULL, NULL, '2024-07-31 14:46:12', 1, '2024-08-11 14:54:05', 1),
(21, 1, 1, 2, 'client', 'DC21', 'ALL /MAHMOOD KIKHA ', 'ali.kikha@yahoo.com', '+97142999800', NULL, NULL, NULL, '2024-07-31 18:10:35', 1, '2024-09-06 16:59:49', 1),
(22, 1, 1, 6, 'agent', 'DC22', ' ALM KHAN /RAHAT AL NOOR SHPPING LLC', 'DAMMAN.DUBAI@GMAIL.COM', '00971558197852', NULL, NULL, NULL, '2024-07-31 18:12:34', 1, '2024-08-11 14:54:36', 1),
(23, 1, 1, 6, 'agent', 'DG23', 'IMRON /CLEARNG ', 'NIL@Ggmail.com', '+971 55 764 9000', NULL, NULL, NULL, '2024-07-31 18:15:18', 1, '2024-08-11 14:54:45', 1),
(24, 1, 1, 2, 'client', 'DC24', 'M.B.R MANU MISHRA', 'NIL@Ggmail.com', '+00000000', NULL, NULL, NULL, '2024-08-01 00:59:30', 1, '2024-08-11 14:54:54', 1),
(25, 1, 1, 2, 'client', 'DC25', 'MIX Credit and debit', 'dgtllc@dgt.llc', '042278608', NULL, NULL, NULL, '2024-08-01 01:02:57', 1, '2024-08-11 14:55:06', 1),
(26, 1, 1, 1, 'bank', 'DB1', 'MASHREQ BANK', 'dgtllc@dgt.llc', '+971500000', NULL, NULL, '{\"acc_no\":\"019101139621\",\"acc_name\":\"MUSHIR BANK\",\"company\":\"United Arab Emirates\",\"iban\":\"AE670330000019101139621\",\"branch_code\":\"0000\",\"currency\":\"AED\",\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"AS RAL\",\"bankDetailsSubmit\":\"\",\"hidden_id\":\"26\",\"hidden_id_details\":\"0\",\"hidden_type\":\"warehouse\"}', '2024-08-01 01:20:26', 1, '2024-08-11 14:55:20', 1),
(27, 1, 1, 1, 'bank', 'DB2', 'FAB BANK / ASMATULLAH ', 'accounts@dgt.llc', '+970000', NULL, NULL, NULL, '2024-08-03 19:54:18', 1, '2024-08-11 14:55:30', 1),
(28, 1, 1, 1, 'bank', 'DB3', 'EIM BANK', 'accounts@dgt.llc', '+970000', NULL, NULL, NULL, '2024-08-03 19:55:02', 1, '2024-08-11 14:55:39', 1),
(29, 1, 1, 7, 'agent', 'DC29', 'GGGGGGGGGGGGGGGGGG', 'zaki@jsdalk.com', '832923', NULL, NULL, NULL, '2024-08-05 20:47:35', 1, '2024-08-11 14:55:51', 1),
(30, 1, 1, 2, 'agent', 'dc1000', 'MASHREQ BANK', 'hidayat@dgt.llc', '1544816664', NULL, NULL, NULL, '2024-09-06 16:28:55', 1, NULL, NULL),
(31, 1, 1, 2, 'client', 'DC30', 'haji najeeb ', 'NIL@gmail.com', 'NIll', NULL, '{\"full_name\":\"najeeb ullah \",\"father_name\":\"abdullah \",\"gender\":\"male\",\"identity\":\"passport\",\"idn_no\":\"00\",\"idn_reg\":\"2024-09-18\",\"idn_expiry\":\"2024-10-08\",\"idn_country\":\"Iran\",\"country\":\"Iran\",\"state\":\"\",\"city\":\"\",\"address\":\"NIL\",\"postcode\":\"\",\"mobile\":\"05138513510\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"31\"}', NULL, '2024-09-06 16:50:53', 1, '2024-09-06 16:52:58', 1),
(32, 1, 1, 2, 'client', 'dc31', 'khan mohammad ', 'NIL@gmail.com', '07909122126', NULL, '{\"full_name\":\"khan mohmmad \",\"father_name\":\"khan \",\"gender\":\"male\",\"idn_no\":\"00\",\"idn_reg\":\"2024-09-10\",\"idn_expiry\":\"2024-09-03\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"NIL\",\"postcode\":\"0000\",\"mobile\":\"00\",\"phone\":\"000000\",\"whatsapp\":\"000\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"32\"}', NULL, '2024-09-06 16:54:45', 1, '2024-09-06 16:56:45', 1),
(33, 1, 1, 2, 'agent', 'dc33', 'FAISAL BROKER ', 'NIL@gmail.com', '07909122126', NULL, '{\"full_name\":\"faisal broker\",\"father_name\":\"faisal \",\"identity\":\"uae\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"NIL\",\"postcode\":\"0000\",\"mobile\":\"00\",\"phone\":\"000000\",\"whatsapp\":\"000\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"33\"}', NULL, '2024-09-06 16:57:37', 1, '2024-09-06 16:58:59', 1),
(34, 1, 1, 2, 'agent', 'DC32', 'usama ', 'dgt.llc.com@gmail.com', '000', NULL, NULL, NULL, '2024-09-06 17:02:18', 1, '2024-09-06 17:09:35', 1),
(35, 1, 1, 2, 'client', 'DC34', 'usama ', 'dgt.llc.com@gmail.com', '000', NULL, '{\"full_name\":\"usama \",\"father_name\":\"ABDULLAH\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"pak\",\"country\":\"pak\",\"state\":\"chaman\",\"city\":\"CHAMAN \",\"address\":\"NIL\",\"postcode\":\"0000\",\"mobile\":\"05138513510\",\"phone\":\"000000\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"35\"}', NULL, '2024-09-06 17:03:43', 1, NULL, NULL),
(36, 1, 1, 2, 'client', 'dc35', 'HAJI ALI KHAN ', 'NIL@gmail.com', '000', NULL, '{\"full_name\":\"ali khan \",\"father_name\":\"nill\",\"gender\":\"male\",\"identity\":\"uae\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"NIL\",\"postcode\":\"0000\",\"mobile\":\"02188890136\",\"phone\":\"000000\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"36\"}', NULL, '2024-09-06 17:08:07', 1, '2024-09-06 17:10:01', 1),
(37, 1, 1, 2, 'agent', 'dc88', 'garlic ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"garlic \",\"father_name\":\"nill\",\"gender\":\"male\",\"identity\":\"passport\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"37\"}', NULL, '2024-09-06 17:12:19', 1, '2024-09-06 20:07:06', 1),
(38, 1, 1, 2, 'agent', 'dc37', 'onion', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"onion\",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"38\"}', NULL, '2024-09-06 17:13:55', 1, NULL, NULL),
(39, 1, 1, 6, 'agent', 'DC38', 'almond ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"onion\",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"39\"}', NULL, '2024-09-06 17:15:17', 1, NULL, NULL),
(40, 1, 1, 2, 'agent', 'dc39', 'walnut ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"walnuit \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"40\"}', NULL, '2024-09-06 17:16:02', 1, NULL, NULL),
(41, 1, 1, 2, 'agent', 'DC40', '', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"WALNUT IN SHELL\",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"41\"}', NULL, '2024-09-06 17:16:50', 1, NULL, NULL),
(42, 1, 1, 1, 'agent', 'db31', 'haji fazal ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"fazal \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"42\"}', NULL, '2024-09-06 17:20:50', 1, NULL, NULL),
(43, 1, 1, 1, 'agent', 'db32', 'haji niamat ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"haji niamat \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"43\"}', NULL, '2024-09-06 17:21:35', 1, NULL, NULL),
(44, 1, 1, 1, 'agent', 'db33', '=massood ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"masood \",\"father_name\":\"nill\",\"gender\":\"male\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"44\"}', NULL, '2024-09-06 17:22:55', 1, NULL, NULL),
(45, 1, 1, 1, 'agent', 'db34', 'raz mohmmad ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"raz mohmmad \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"45\"}', NULL, '2024-09-06 17:23:56', 1, NULL, NULL),
(46, 1, 1, 1, 'agent', 'db35', 'jalil agha ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"jalail agha \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"46\"}', NULL, '2024-09-06 17:24:51', 1, NULL, NULL),
(47, 1, 1, 1, 'agent', 'db36', 'asmat personal ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"asmat persnal \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"47\"}', NULL, '2024-09-06 17:25:47', 1, NULL, NULL),
(48, 1, 1, 1, 'agent', 'db37', 'cachew ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"cashew \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"48\"}', NULL, '2024-09-06 17:26:45', 1, '2024-09-06 17:26:59', 1),
(49, 1, 1, 1, 'agent', 'db38', 'transit ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"transit \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"49\"}', NULL, '2024-09-06 17:28:23', 1, NULL, NULL),
(50, 1, 1, 1, 'agent', 'db39', 'noor ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"NOOR MOHMMAD SE PERCHASE KIA\",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"50\"}', NULL, '2024-09-06 17:32:15', 1, '2024-09-06 17:32:55', 1),
(51, 1, 1, 1, 'agent', 'db40', 'gfg', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"ggj\",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"51\"}', NULL, '2024-09-06 17:37:57', 1, '2024-09-06 17:38:01', 1),
(52, 1, 1, 2, 'agent', 'db50', 'salam', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"salam \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"52\"}', NULL, '2024-09-06 17:43:27', 1, NULL, NULL),
(53, 1, 1, 1, 'agent', 'db51', 'pistachio', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"pistachio\",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"53\"}', NULL, '2024-09-06 17:46:56', 1, NULL, NULL),
(54, 1, 1, 1, 'agent', 'db52', 'komal ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"komal \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"54\"}', NULL, '2024-09-06 17:49:59', 1, NULL, NULL),
(55, 1, 1, 7, 'agent', 'db53', 'ghafoor', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"ghafoor \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"55\"}', NULL, '2024-09-06 17:51:10', 1, NULL, NULL),
(56, 1, 1, 1, 'agent', 'db54', 'artagrul ghazi ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"artagrul ghazi \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"56\"}', NULL, '2024-09-06 17:52:21', 1, NULL, NULL),
(57, 1, 1, 1, 'agent', 'db55', 'hyma kha toon ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"artagrul ghazi \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"57\"}', NULL, '2024-09-06 17:53:20', 1, NULL, NULL),
(58, 1, 1, 1, 'agent', 'db56', 'suliman shah ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"saliman shah \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"58\"}', NULL, '2024-09-06 17:54:36', 1, NULL, NULL),
(59, 1, 1, 1, 'agent', 'db56 ', 'kondoghdu  ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"saliman shah \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"59\"}', NULL, '2024-09-06 17:55:56', 1, NULL, NULL),
(60, 1, 1, 1, 'agent', 'db57', 'usman sardar ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"saliman shah \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"60\"}', NULL, '2024-09-06 17:56:54', 1, NULL, NULL),
(61, 1, 1, 1, 'agent', 'db58', 'seed', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"seed\",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"61\"}', NULL, '2024-09-06 19:55:39', 1, NULL, NULL),
(62, 1, 1, 1, 'agent', 'db59', 'spises', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"spises \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"62\"}', NULL, '2024-09-06 19:56:23', 1, NULL, NULL),
(63, 1, 1, 1, 'agent', 'db60', 'FRESH GARLIC', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"frsh galrioc\",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"63\"}', NULL, '2024-09-06 19:57:10', 1, NULL, NULL),
(64, 1, 1, 1, 'agent', 'db61', 'kamdhar ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"kamdhar \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"64\"}', NULL, '2024-09-06 19:57:56', 1, '2024-09-06 19:58:12', 1),
(65, 1, 1, 1, 'agent', 'db62', 'saifullah ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"saifullah \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"65\"}', NULL, '2024-09-06 19:59:11', 1, NULL, NULL),
(66, 1, 1, 1, 'agent', 'db63', 'anwar ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"anwar \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"66\"}', NULL, '2024-09-06 19:59:55', 1, NULL, NULL),
(67, 1, 1, 1, 'agent', 'db64', 'kishmesh ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"kishmesh \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"67\"}', NULL, '2024-09-06 20:01:03', 1, NULL, NULL),
(68, 1, 1, 1, 'agent', 'db65', 'shipiing line ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"shppinfg line \",\"father_name\":\"nill\",\"idn_no\":\"00\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"00\",\"whatsapp\":\"00\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"68\"}', NULL, '2024-09-06 20:01:32', 1, '2024-09-06 20:03:49', 1),
(69, 1, 1, 7, 'agent', 'db66', 'potato ac', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"potato ac\",\"father_name\":\"nill\",\"idn_no\":\"\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"69\"}', NULL, '2024-09-06 20:04:36', 1, NULL, NULL),
(70, 1, 1, 1, 'agent', 'db67', 'dawwod bAI ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"DAWOOOD \",\"father_name\":\"nill\",\"idn_no\":\"\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"70\"}', NULL, '2024-09-06 20:05:33', 1, NULL, NULL),
(71, 1, 1, 1, 'agent', 'DB68', 'MUZAAMIL HAJI AKHTER ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"MUZZAMIL HAJI AKHTAR \",\"father_name\":\"nill\",\"idn_no\":\"\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"71\"}', NULL, '2024-09-06 20:06:36', 1, '2024-09-06 20:06:44', 1),
(72, 1, 1, 1, 'agent', 'DB69', 'SADIQ KHAN ', '', '', NULL, '{\"full_name\":\"SADIQ KHAN \",\"father_name\":\"nill\",\"idn_no\":\"\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"72\"}', NULL, '2024-09-06 20:08:22', 1, NULL, NULL),
(73, 1, 1, 1, 'client', 'DB70', 'HAJI LALAY ', '', '', NULL, '{\"full_name\":\"HAJI LALY \",\"father_name\":\"nill\",\"idn_no\":\"\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"73\"}', NULL, '2024-09-06 20:09:14', 1, '2024-09-06 20:09:18', 1),
(74, 1, 1, 1, 'agent', 'DB71', 'HAJI LAJBAR ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"HAJI LAJBAR \",\"father_name\":\"nill\",\"idn_no\":\"\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"74\"}', NULL, '2024-09-06 20:10:13', 1, NULL, NULL),
(75, 1, 1, 1, 'agent', 'DB72', 'HAJI LALAY ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"SULAGTN \",\"father_name\":\"nill\",\"idn_no\":\"\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"75\"}', NULL, '2024-09-06 20:10:51', 1, NULL, NULL),
(76, 1, 1, 1, 'agent', 'DB73', 'MALAK ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"MALAK \",\"father_name\":\"nill\",\"idn_no\":\"\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"76\"}', NULL, '2024-09-06 20:11:23', 1, NULL, NULL),
(77, 1, 1, 1, 'agent', 'DB74', 'FAIZULLAH ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"MALAK \",\"father_name\":\"nill\",\"idn_no\":\"\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"77\"}', NULL, '2024-09-06 20:12:17', 1, NULL, NULL),
(78, 1, 1, 1, 'agent', 'DB75', 'PALAWAN AKA ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"PALWAN \",\"father_name\":\"nill\",\"idn_no\":\"\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"78\"}', NULL, '2024-09-06 20:12:54', 1, NULL, NULL),
(79, 1, 1, 1, 'agent', 'DB76', 'AZIZ ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"AZIZ\",\"father_name\":\"nill\",\"idn_no\":\"\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"79\"}', NULL, '2024-09-06 20:13:38', 1, NULL, NULL),
(80, 1, 1, 1, 'agent', 'DB77', 'MANSHI ', 'dgt.llc.com@gmail.com', '0544816664', NULL, '{\"full_name\":\"MANSHI \",\"father_name\":\"nill\",\"identity\":\"passport\",\"idn_no\":\"\",\"idn_reg\":\"\",\"idn_expiry\":\"\",\"idn_country\":\"United Arab Emirates\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"postcode\":\"\",\"mobile\":\"0544816664\",\"phone\":\"\",\"whatsapp\":\"\",\"contactDetailsSubmit\":\"\",\"hidden_id\":\"80\"}', NULL, '2024-09-06 20:14:15', 1, NULL, NULL);

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
(1, 'company', 2, '{\"owner_name\":\"ASMATULLAH ABDULLAH\",\"company_name\":\"DAMAAN GENERAL TRADING L L C\",\"business_title\":\"SLE\",\"vals1\":[\"1099620\"],\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"AS RAL\",\"indexes2\":[\"Office\",\"WhatsApp\"],\"vals2\":[\"+971400000\",\"+971544816664\"],\"companyDetailsSubmit\":\"\",\"hidden_id\":\"2\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-08-01 00:57:11', 1, NULL, NULL),
(2, 'company', 1, '{\"owner_name\":\"NAJEEBULLAH\",\"company_name\":\"ASMATNAJEEB& COMPANY\",\"business_title\":\"IMPOTR EXPOTR\",\"indexes1\":[\"FSSAI\",\"WEIGHT\"],\"vals1\":[\"FS-392-US23-2\",\"322\"],\"country\":\"Pakistan\",\"state\":\"BALOCHISTAN\",\"city\":\"QUETTA\",\"address\":\"HIDAYAT \",\"indexes2\":[\"Mobile\",\"WhatsApp\",\"Email\"],\"vals2\":[\"+923337764088\",\"+923188088900\",\"quetta.office@dgt.llc\"],\"companyDetailsSubmit\":\"\",\"hidden_id\":\"1\",\"hidden_id_details\":\"2\",\"hidden_type\":\"company\"}', '2024-08-01 13:17:21', 1, '2024-08-01 23:30:39', 1),
(3, 'company', 1, '{\"owner_name\":\"3NAJEEBULLAH\",\"company_name\":\"3ASMATNAJEEB & COMPANY 2\",\"business_title\":\"IMPOTR EXPOTR\",\"indexes1\":[\"NTN\",\"ST\"],\"vals1\":[\"719170-9\",\"00000000\"],\"country\":\"Pakistan\",\"state\":\"BALOCHISTAN\",\"city\":\"QUETTA\",\"address\":\"HIDAYAT PLAZA AL RAS DUBAI\",\"indexes2\":[\"Phone\",\"WhatsApp\"],\"vals2\":[\"+92812842032\",\"+923188088900\"],\"companyDetailsSubmit\":\"\",\"hidden_id\":\"1\",\"hidden_id_details\":\"3\",\"hidden_type\":\"company\"}', '2024-08-01 13:26:10', 1, '2024-08-19 19:04:41', 1),
(4, 'company', 16, '{\"owner_name\":\"ASMATULLAH ABDULLAH\",\"company_name\":\"DAMAAN GENERAL TRADING L L C\",\"business_title\":\"DAMAAN GENERAL TRADING L L C\",\"indexes1\":[\"License\"],\"vals1\":[\"1099620\"],\"country\":\"United Arab Emirates\",\"state\":\"dubai\",\"city\":\"dubai\",\"address\":\"AS RAL\",\"indexes2\":[\"Phone\",\"WhatsApp\"],\"vals2\":[\"+914\",\"+971544816664\"],\"companyDetailsSubmit\":\"\",\"hidden_id\":\"16\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-08-01 14:18:56', 1, NULL, NULL),
(5, 'company', 5, '{\"owner_name\":\"sdfkalkdsjf j\",\"company_name\":\"lk jsdlk jfslkdj \",\"business_title\":\"kljs flksd\",\"country\":\"\",\"state\":\"\",\"city\":\"\",\"address\":\"\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"5\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-08-01 23:34:07', 1, NULL, NULL),
(6, 'company', 6, '{\"owner_name\":\"SANJAI\",\"company_name\":\"DAMODAR  EXPORT\",\"business_title\":\"IMPOTR EXPOTR\",\"indexes1\":[\"FSSAI\",\"IEC\",\"GST\"],\"vals1\":[\"10019022010092\",\"AAQFD1336 \",\"27 AAQFD1336E1ZK\"],\"country\":\"INDIA\",\"state\":\"INDIA\",\"city\":\"IBD\",\"address\":\" OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA\",\"indexes2\":[\"Email\"],\"vals2\":[\"DAMODAREXPORTS43@GMAIL.COM \"],\"companyDetailsSubmit\":\"\",\"hidden_id\":\"6\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-08-12 15:19:48', 1, NULL, NULL),
(7, 'company', 31, '{\"owner_name\":\"najeebullah \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"31\",\"hidden_id_details\":\"7\",\"hidden_type\":\"company\"}', '2024-09-06 16:52:45', 1, '2024-09-06 16:52:48', 1),
(8, 'company', 32, '{\"owner_name\":\"khan mohammad\",\"company_name\":\" nil\",\"business_title\":\"import export \",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"NIL\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"32\",\"hidden_id_details\":\"8\",\"hidden_type\":\"company\"}', '2024-09-06 16:56:38', 1, '2024-09-06 16:56:40', 1),
(9, 'company', 33, '{\"owner_name\":\"faisal \",\"company_name\":\"broker \",\"business_title\":\"import export \",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"NIL\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"33\",\"hidden_id_details\":\"9\",\"hidden_type\":\"company\"}', '2024-09-06 16:58:50', 1, '2024-09-06 16:58:52', 1),
(10, 'company', 35, '{\"owner_name\":\"usama \",\"company_name\":\"usama\",\"business_title\":\"FHS LINEAGE SHPPING SDN. BHD\",\"country\":\"pak\",\"state\":\"chaman\",\"city\":\"CHAMAN \",\"address\":\"NIL\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"35\",\"hidden_id_details\":\"10\",\"hidden_type\":\"company\"}', '2024-09-06 17:04:18', 1, '2024-09-06 17:04:21', 1),
(11, 'company', 36, '{\"owner_name\":\"ALI KHAN \",\"company_name\":\"mirsana food stuff \",\"business_title\":\"import export \",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"NIL\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"36\",\"hidden_id_details\":\"11\",\"hidden_type\":\"company\"}', '2024-09-06 17:09:52', 1, '2024-09-06 17:09:55', 1),
(12, 'company', 37, '{\"owner_name\":\"uae \",\"company_name\":\"uae \",\"business_title\":\"CLIPLINE SHPPING\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"DUBAI\",\"address\":\"CLIP LINE SHPPING INdia  PVT LTD OFFICE O E 408 TOWER NO2 LEVEL 6 FLOOR SEAWOC GRAND CENTRAL PLOT NO R1 SECTOOR 40 NERAL NAVI MUMBAI\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"37\",\"hidden_id_details\":\"12\",\"hidden_type\":\"company\"}', '2024-09-06 17:13:11', 1, '2024-09-06 17:13:13', 1),
(13, 'company', 38, '{\"owner_name\":\"onion\",\"company_name\":\"onion \",\"business_title\":\"import export \",\"country\":\"India\",\"state\":\"\",\"city\":\"\",\"address\":\"SHELTON SAPHIR , A WING OFFICE NO 907  PLM BEACH ROAD  NEAR SEASON COURT SECTOR 15 MUMBAI\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"38\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:14:29', 1, NULL, NULL),
(14, 'company', 39, '{\"owner_name\":\"almomd \",\"company_name\":\"onion \",\"business_title\":\"import export \",\"country\":\"India\",\"state\":\"\",\"city\":\"\",\"address\":\"SHELTON SAPHIR , A WING OFFICE NO 907  PLM BEACH ROAD  NEAR SEASON COURT SECTOR 15 MUMBAI\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"39\",\"hidden_id_details\":\"14\",\"hidden_type\":\"company\"}', '2024-09-06 17:15:35', 1, '2024-09-06 17:15:38', 1),
(15, 'company', 40, '{\"owner_name\":\"walnut \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"40\",\"hidden_id_details\":\"15\",\"hidden_type\":\"company\"}', '2024-09-06 17:16:24', 1, '2024-09-06 17:16:25', 1),
(16, 'company', 41, '{\"owner_name\":\"walnut insheell\",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"chaman\",\"city\":\"idnia\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"41\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:17:30', 1, NULL, NULL),
(17, 'company', 42, '{\"owner_name\":\"fazal\",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"chaman\",\"city\":\"idnia\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"42\",\"hidden_id_details\":\"17\",\"hidden_type\":\"company\"}', '2024-09-06 17:21:14', 1, '2024-09-06 17:21:16', 1),
(18, 'company', 43, '{\"owner_name\":\"haji niamat \",\"company_name\":\"niamat \",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"43\",\"hidden_id_details\":\"18\",\"hidden_type\":\"company\"}', '2024-09-06 17:22:21', 1, '2024-09-06 17:22:25', 1),
(19, 'company', 44, '{\"owner_name\":\"masood \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"44\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:23:23', 1, NULL, NULL),
(20, 'company', 45, '{\"owner_name\":\"raz mohmmad \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"45\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:24:24', 1, NULL, NULL),
(21, 'company', 46, '{\"owner_name\":\"jalil aggha \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"46\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:25:12', 1, NULL, NULL),
(22, 'company', 47, '{\"owner_name\":\"asmat personal \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"47\",\"hidden_id_details\":\"22\",\"hidden_type\":\"company\"}', '2024-09-06 17:26:31', 1, '2024-09-06 17:26:33', 1),
(23, 'company', 48, '{\"owner_name\":\"cashew\",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"48\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:27:23', 1, NULL, NULL),
(24, 'company', 49, '{\"owner_name\":\"trnsit \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"49\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:28:44', 1, NULL, NULL),
(25, 'company', 50, '{\"owner_name\":\"noor \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"50\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:33:03', 1, NULL, NULL),
(26, 'company', 51, '{\"owner_name\":\"noor \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"51\",\"hidden_id_details\":\"26\",\"hidden_type\":\"company\"}', '2024-09-06 17:38:44', 1, '2024-09-06 17:38:47', 1),
(27, 'company', 52, '{\"owner_name\":\"SALAM\",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"52\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:44:05', 1, NULL, NULL),
(28, 'company', 53, '{\"owner_name\":\"SALAM\",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"53\",\"hidden_id_details\":\"28\",\"hidden_type\":\"company\"}', '2024-09-06 17:48:55', 1, '2024-09-06 17:48:59', 1),
(29, 'company', 54, '{\"owner_name\":\"kmal \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"54\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:50:25', 1, NULL, NULL),
(30, 'company', 55, '{\"owner_name\":\"ghafoor \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"55\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:51:32', 1, NULL, NULL),
(31, 'company', 56, '{\"owner_name\":\"artagrul ghazi \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"56\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:52:51', 1, NULL, NULL),
(32, 'company', 57, '{\"owner_name\":\"artagrul ghazi \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"57\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:53:59', 1, NULL, NULL),
(33, 'company', 58, '{\"owner_name\":\"saliman sha \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"58\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:55:09', 1, NULL, NULL),
(34, 'company', 59, '{\"owner_name\":\"saliman sha \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"59\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:56:15', 1, NULL, NULL),
(35, 'company', 60, '{\"owner_name\":\"saliman sha \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"60\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 17:57:07', 1, NULL, NULL),
(36, 'company', 61, '{\"owner_name\":\"seed  \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"61\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 19:55:55', 1, NULL, NULL),
(37, 'company', 62, '{\"owner_name\":\"spises\",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"62\",\"hidden_id_details\":\"37\",\"hidden_type\":\"company\"}', '2024-09-06 19:56:43', 1, '2024-09-06 19:56:45', 1),
(38, 'company', 63, '{\"owner_name\":\"fresh garlic \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"63\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 19:57:39', 1, NULL, NULL),
(39, 'company', 64, '{\"owner_name\":\"kamdhar\",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"64\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 19:58:41', 1, NULL, NULL),
(40, 'company', 65, '{\"owner_name\":\"saifullah \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"65\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 19:59:34', 1, NULL, NULL),
(41, 'company', 66, '{\"owner_name\":\"anwar \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"66\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:00:12', 1, NULL, NULL),
(42, 'company', 67, '{\"owner_name\":\"kishmesh \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"67\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:01:20', 1, NULL, NULL),
(43, 'company', 68, '{\"owner_name\":\"shippinfg line \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"68\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:04:01', 1, NULL, NULL),
(44, 'company', 69, '{\"owner_name\":\"potato ac \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"69\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:05:03', 1, NULL, NULL),
(45, 'company', 70, '{\"owner_name\":\"DAWOOOD \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"\",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"70\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:05:54', 1, NULL, NULL),
(46, 'company', 71, '{\"owner_name\":\"MUIZAMMIL HAJI AKHTER \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"71\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:07:22', 1, NULL, NULL),
(47, 'company', 72, '{\"owner_name\":\"SADIQ KHAN \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"72\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:08:43', 1, NULL, NULL),
(48, 'company', 73, '{\"owner_name\":\"HAJI LALAY \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"73\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:09:40', 1, NULL, NULL),
(49, 'company', 74, '{\"owner_name\":\"HAJI LAJBAR\",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"74\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:10:39', 1, NULL, NULL),
(50, 'company', 75, '{\"owner_name\":\"HAJI LAJBAR\",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"75\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:11:06', 1, NULL, NULL),
(51, 'company', 76, '{\"owner_name\":\"MALAK \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"76\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:11:39', 1, NULL, NULL),
(52, 'company', 77, '{\"owner_name\":\"MALAK \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"77\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:12:27', 1, NULL, NULL),
(53, 'company', 78, '{\"owner_name\":\"PALALWAN \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"78\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:13:13', 1, NULL, NULL),
(54, 'company', 79, '{\"owner_name\":\"AZIZ \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"79\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:13:56', 1, NULL, NULL),
(55, 'company', 80, '{\"owner_name\":\"MANSHI \",\"company_name\":\"DAMAAN GENERAL TRADING L.L.C\",\"business_title\":\"DAMAAN GENERAL TRADING L.L.C\",\"country\":\"United Arab Emirates\",\"state\":\"dubai \",\"city\":\"\",\"address\":\"HABTOOR BUILDING OFFICE NO: 201 AL RAS ARES DEIRA DUBAI U.A.E\",\"companyDetailsSubmit\":\"\",\"hidden_id\":\"80\",\"hidden_id_details\":\"0\",\"hidden_type\":\"company\"}', '2024-09-06 20:14:44', 1, NULL, NULL);

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
(33, 32, 'Carry Bill', '#', 1, 1),
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
(44, 45, 'Bill Transfer Form', '#', 2, 1),
(45, 40, 'PURCHASES', '#', 1, 0),
(46, 45, 'Full Payment Form', 'PURCHASE/SALES', 3, 1),
(48, 58, 'PACKING LIST/CUSTO', '#', 3, 1),
(49, 41, 'SLAES INVOCE', '#', 3, 1),
(50, 41, 'PURCHASE.INVOCE', 'VAT/TAX', 4, 1),
(51, 12, ' LEDGER ACCOUNT', 'ledger', 1, 1),
(52, 45, 'PURCHASE ORDERS', 'purchases', 1, 1),
(53, 40, 'SALES', '#', 2, 0),
(54, 53, 'SALE ORDERS', '#', 1, 0),
(55, 53, 'BILL TRANSFER FORM', '#', 2, 1),
(56, 53, 'FULL PAYMENT FORM', '#', 3, 1),
(57, 36, 'AFGHAN INVOICES ENTERY', 'afghan-invoices', 1, 1),
(58, 35, 'CLEARING', '#', 2, 0);

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
(1, 'Business', 'dr', 1, 'DC1', 281234, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 2, 2, 2, '2024-08-05', '23', 'cheque', '9823', 'jsdlkf  Currency:AED Qty:0 PerPrice:0', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 22:42:37', '2024-08-15 18:26:42', 1),
(2, 'Bill', 'dr', 1, 'dc1', 276, 1, 120, 2.3, '*', 'AED', '', '', NULL, 0, 1, 13, 1, 2, '2024-08-05', 'as1', 'cheque', '1231233', 'jshfsdh hfdsk fvn cxkjhv;ods   fdowilfvd  dsifu odituyr Currency:AED Qty:120 PerPrice:2.3', 26, '2024-08-08', NULL, 'admin', 1, '2024-08-05 22:54:58', '2024-08-13 21:25:41', 1),
(3, 'Business', 'dr', 1, 'dc00001', 100000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 1, 1, 2, '2024-08-05', 'as1', 'chk', '1231233', 'jshfsdh hfdsk fvn cxkjhv;ods   fdowilfvd  dsifu odituyr', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 22:55:13', NULL, NULL),
(4, 'Business', 'cr', 5, 'DC00005', 100000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 2, 1, 2, '2024-08-05', 'as1', 'chk', '1231233', 'jshfsdh hfdsk fvn cxkjhv;ods   fdowilfvd  dsifu odituyr', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 22:55:38', NULL, NULL),
(5, 'Bank', 'cr', 11, 'DU00011', 1300000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 2, 1, 4, '2024-08-05', 'AS1', 'TRUCK POAN', '00', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', 27, '2024-08-08', NULL, 'admin', 1, '2024-08-05 22:56:59', NULL, NULL),
(6, 'Cash', 'dr', 11, 'DU00011', 1300000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 13, 1, 4, '2024-08-05', 'AS1', 'cheque', '00', 'this a sk fasdf ', 0, '2024-08-05', 'r_uploads/f.jpg', 'admin', 1, '2024-08-05 22:57:40', '2024-08-11 14:43:52', 1),
(7, 'Bank', 'dr', 11, 'DU00011', 1300000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 3, 1, 4, '2024-08-05', 'AS1', 'TRUCK POAN', '00', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', 0, '2024-09-10', NULL, 'admin', 1, '2024-08-05 22:57:59', NULL, NULL),
(8, 'Bank', 'dr', 11, 'DU00011', 1300000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 4, 1, 4, '2024-08-05', 'AS1', 'TRUCK POAN', '00', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', 0, '2024-08-20', NULL, 'admin', 1, '2024-08-05 22:58:16', NULL, NULL),
(9, 'Bank', 'dr', 11, 'DU00011', 1300000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 5, 1, 4, '2024-08-05', 'AS1', 'TRUCK POAN', '00', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', 0, '2024-08-20', NULL, 'admin', 1, '2024-08-05 22:58:32', NULL, NULL),
(10, 'Bank', 'dr', 11, 'DU00011', 1350000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 6, 1, 4, '2024-08-05', 'AS1', 'TRUCK POAN', '00', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW', 0, '2024-08-25', NULL, 'admin', 1, '2024-08-05 22:58:53', NULL, NULL),
(11, 'Bank', 'dr', 1, 'dc1', 1000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 18, 1, 2, '2024-08-05', 'as1', 'cheque', '00', 'bank islamic se cash ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:09:35', '2024-08-12 13:24:57', 1),
(12, 'Cash', 'cr', 1, 'dc00001', 250200, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 9, 1, 2, '2024-05-05', 'as1', 'cheque', '00', 'bank islamic se cash ', 26, '2024-08-05', 'r_uploads/blob.jpeg', 'admin', 1, '2024-08-05 23:09:55', '2024-08-10 23:25:29', 1),
(13, 'Bank', 'dr', 1, 'dc00001', 250200, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 15, 1, 2, '2024-08-05', 'as1', 'cheque', '00', 'bank islamic se cash ', 26, '2024-05-15', NULL, 'admin', 1, '2024-08-05 23:10:17', '2024-08-07 01:37:45', 1),
(14, 'Bank', 'dr', 24, 'dc00024', 25000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 15, 1, 2, '2024-08-05', 'as1', 'cheque', '00', 'bank islamic se cash ', 27, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:10:58', '2024-08-07 01:38:06', 1),
(15, 'Bank', 'cr', 21, 'dc00021', 25000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 18, 1, 2, '2024-08-03', 'as1', 'cheque', '00', 'bank islamic se cash ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:11:27', '2024-08-07 20:14:37', 1),
(16, 'Bank', 'dr', 21, 'dc00021', 10000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 18, 1, 2, '2024-06-05', 'as1', 'cheque', '00', 'bank islamic se cash ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:11:51', '2024-08-07 20:14:53', 1),
(17, 'Bank', 'dr', 22, 'dc00022', 10000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 18, 1, 6, '2024-08-05', 'as1', 'cheque', '00', 'bank islamic se cash ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:12:26', '2024-08-07 20:15:05', 1),
(18, 'Cash', 'dr', 15, 'dc00015', 10000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 10, 1, 2, '2024-06-20', 'as1', 'cheque', '00', 'bank islamic se cash ', 0, '2024-08-05', 'r_uploads/blob.jpeg', 'admin', 1, '2024-08-05 23:14:08', '2024-08-10 23:26:05', 1),
(19, 'Bank', 'dr', 15, 'dc00015', 10000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 18, 1, 2, '2024-08-05', 'as1', 'receipts', '00', 'bank islamic se cash ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:21:59', '2024-08-07 20:17:39', 1),
(20, 'Cash', 'cr', 15, 'dc00015', 36700, 0, 10000, 3.67, '*', 'AED', NULL, '', NULL, 0, 1, 7, 1, 2, '2024-08-05', 'as1', 'cheque', '00', 'bank islamic se cash ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:22:13', '2024-08-07 20:18:29', 1),
(21, 'Cash', 'dr', 15, 'dc00015', 522, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 8, 1, 2, '2024-08-05', 'as1', 'cheque', '00', 'bank islamic se cash ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:22:24', '2024-08-07 20:17:21', 1),
(22, 'Bank', 'cr', 15, 'dc00015', 522, 0, 0, 0, '*', 'AED', NULL, '0544816664', NULL, 0, 1, 19, 1, 2, '2024-08-05', 'as1', 'transfer', '00', 'bank islamic se cash ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:22:38', '2024-08-07 20:17:56', 1),
(23, 'Bill', 'dr', 15, 'dc00015', 18300, 0, 5000, 3.66, '*', 'AED', NULL, '', NULL, 0, 1, 7, 1, 2, '2024-08-05', 'as1', 'cheque', '00', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:37:25', '2024-08-07 20:15:46', 1),
(24, 'Bill', 'dr', 15, 'dc00015', 1835, 0, 500, 3.67, '*', 'AED', NULL, '', NULL, 0, 1, 7, 1, 2, '2024-02-05', 'as1', 'cheque', '00', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:38:07', '2024-08-07 20:16:54', 1),
(25, 'Bill', 'dr', 17, 'dc00017', 1835, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 7, 1, 2, '2024-04-05', 'as1', 'cheque', '00', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:38:36', '2024-08-07 20:16:14', 1),
(26, 'Bill', 'dr', 18, 'dc00018', 1835, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 7, 1, 2, '2024-08-01', 'as1', 'cheque', '00', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:39:00', '2024-08-07 20:15:59', 1),
(27, 'Cash', 'dr', 18, 'dc00018', 1835, 0, 0, 0, '*', 'AED', NULL, '0544816664', NULL, 0, 1, 3, 1, 2, '2024-08-08', 'as1', 'asmatullah abdullah', '00', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:39:22', NULL, NULL),
(28, 'Cash', 'dr', 19, 'dc00019', 1835, 0, 0, 0, '*', 'AED', NULL, '0528373943', NULL, 0, 1, 8, 1, 2, '2024-08-05', 'as1', 'cheque', '00', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:39:36', '2024-08-07 20:16:30', 1),
(29, 'Cash', 'dr', 21, 'dc00021', 1835, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 8, 1, 2, '2024-08-05', 'as1', 'cheque', '00', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:40:04', '2024-08-07 20:16:43', 1),
(30, 'Cash', 'dr', 24, 'dc00024', 2000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 8, 1, 2, '2024-08-05', 'as1', 'cheque', '00', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:40:30', '2024-08-07 20:15:33', 1),
(31, 'Cash', 'dr', 24, 'dc00024', 2000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 8, 1, 2, '2024-08-05', 'as1', 'cheque', '00', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:40:43', '2024-08-07 20:15:19', 1),
(32, 'Bank', 'cr', 21, 'dc00021', 2000, 0, 0, 0, '*', 'AED', NULL, '0544816664', NULL, 0, 1, 16, 1, 2, '2024-08-05', 'as1', 'cheque', '00', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:41:00', '2024-08-07 01:38:52', 1),
(33, 'Bank', 'dr', 14, 'dc00014', 5000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 15, 1, 2, '2024-08-05', 'as1', 'cheque', '00', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-05', NULL, 'admin', 1, '2024-08-05 23:41:28', '2024-08-07 01:38:26', 1),
(34, 'Bank', 'dr', 1, 'DC00001', 250000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 18, 1, 2, '2024-08-07', 'AS1', 'cheque', '1245236', 'SAFSDAFSFQEWRF WRTWRTGVWREERGFERWFV', 0, '2024-08-07', NULL, 'admin', 1, '2024-08-07 01:42:25', '2024-08-07 01:42:34', 1),
(35, 'Cash', 'dr', 1, 'dc1', 36700, 0, 10000, 3.67, '*', 'AED', NULL, '1544816664', NULL, 0, 1, 13, 1, 2, '2024-08-10', 'AS1', 'cheque', '101', 'SAFSDAFSFQEWRF', 0, '2024-08-10', 'r_uploads/blob.jpeg', 'admin', 1, '2024-08-10 23:46:02', '2024-08-12 18:11:00', 1),
(36, 'Cash', 'dr', 1, 'DC00001', 1105260, 0, 0, 0, '*', 'AED', NULL, '111111111', NULL, 0, 1, 13, 1, 2, '2024-08-10', '12', 'cheque', '123132156', 'INVESTMENT ACOUNTs to transferred  me Total calculation ', 0, '2024-08-10', 'r_uploads/blob.jpeg', 'admin', 1, '2024-08-10 23:48:22', '2024-08-10 23:50:08', 1),
(37, 'Business', 'dr', 3, 'dc3', 55000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 4, 1, 2, '2024-08-05', 'as1', 'cheque', '0000', 'jama cash du 1 se ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:24:07', '2024-08-12 23:25:50', 1),
(38, 'Business', 'dr', 29, 'dc29', 150000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 5, 1, 7, '2024-08-12', 'as1', 'cheque', '000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:26:22', '2024-08-12 23:26:33', 1),
(39, 'Business', 'cr', 1, 'dc1', 140000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 5, 1, 2, '2024-08-12', 'as1', 'cheque', '0000', 'bank islamic se cash ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:26:51', NULL, NULL),
(40, 'Bank', 'dr', 1, 'dc1', 140000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 18, 1, 2, '2024-06-25', 'as1', 'cheque', '0000', 'bank islamic se cash ', 26, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:27:40', '2024-08-12 23:27:50', 1),
(41, 'Bank', 'dr', 20, 'dc20', 120000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 19, 1, 2, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 26, '2024-08-06', NULL, 'admin', 1, '2024-08-12 23:30:17', '2024-08-12 23:30:40', 1),
(42, 'Bank', 'cr', 14, 'dc14', 120000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 20, 1, 2, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 26, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:31:01', NULL, NULL),
(43, 'Business', 'cr', 26, 'db1', 2000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 6, 1, 1, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:33:18', NULL, NULL),
(44, 'Bank', 'dr', 11, 'du11', 2000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 21, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:35:04', NULL, NULL),
(45, 'Bank', 'cr', 12, 'du12', 2000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 22, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 27, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:35:35', NULL, NULL),
(46, 'Bill', 'dr', 13, 'du13', 4404, 0, 1200, 3.67, '*', 'AED', NULL, '', NULL, 0, 1, 5, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:36:31', NULL, NULL),
(47, 'Bill', 'cr', 2, 'du2', 51747, 0, 14100, 3.67, '*', 'AED', NULL, '', NULL, 0, 1, 6, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', ' banam NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:37:25', NULL, NULL),
(48, 'Bill', 'dr', 13, 'du13', 4404, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 7, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:37:39', NULL, NULL),
(49, 'Bill', 'cr', 12, 'du12', 4404, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 8, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:37:58', NULL, NULL),
(50, 'Business', 'dr', 20, 'dc20', 44040, 1, 12000, 3.67, '*', 'AED', 'saif', '0544816664', NULL, 0, 1, 9, 1, 2, '2024-08-13', 'as1', 'cheque', '0000', 'jama meshriq bank se cash babat jalil agha se check tabdeel kia Currency:AED Qty:12000 PerPrice:3.67', 0, '2024-08-12', 'r_uploads/OIP.jpg', 'admin', 1, '2024-08-12 23:39:24', '2024-08-13 21:24:41', 1),
(51, 'Cash', 'cr', 13, 'du13', 30000, 0, 1200, 25, '*', 'AED', NULL, '0544816664', NULL, 0, 1, 15, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', 'r_uploads/OIP.jpg', 'admin', 1, '2024-08-12 23:39:53', '2024-08-12 23:39:58', 1),
(52, 'Cash', 'cr', 6, 'DC6', 1000, 0, 0, 0, '*', 'AED', NULL, '0544816664', NULL, 0, 1, 16, 1, 2, '2024-08-12', 'd19', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:40:31', '2024-08-12 23:41:12', 1),
(53, 'Cash', 'dr', 29, 'dc29', 40000, 0, 0, 0, '*', 'AED', NULL, '0544816664', NULL, 0, 1, 16, 1, 7, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:41:57', NULL, NULL),
(54, 'Cash', 'cr', 24, 'DC24', 40000, 0, 0, 0, '*', 'AED', NULL, '05138513510', NULL, 0, 1, 17, 1, 2, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', 'r_uploads/Screenshot 2023-10-23 173936.png', 'admin', 1, '2024-08-12 23:42:36', NULL, NULL),
(55, 'Bank', 'dr', 24, 'DC24', 40000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 23, 1, 2, '2024-05-06', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 27, '2024-08-05', NULL, 'admin', 1, '2024-08-12 23:43:23', NULL, NULL),
(56, 'Business', 'dr', 24, 'DC24', 40000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 7, 1, 2, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:44:31', NULL, NULL),
(57, 'Business', 'cr', 11, 'du11', 42000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 9, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:44:50', '2024-08-12 23:47:50', 1),
(58, 'Bill', 'dr', 12, 'du12', 4404, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 9, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:49:11', NULL, NULL),
(59, 'Bill', 'cr', 11, 'du11', 1000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 11, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'bank islamic se cash ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:49:25', '2024-08-12 23:49:30', 1),
(60, 'Bill', 'dr', 12, 'du12', 55000, 0, 2500, 22, '*', 'AED', NULL, '', NULL, 0, 1, 11, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'bank islamic se cash ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:49:54', NULL, NULL),
(61, 'Bank', 'dr', 2, 'du2', 55000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 24, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'bank islamic se cash ', 26, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:53:35', NULL, NULL),
(62, 'Bank', 'cr', 4, 'du4', 55000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 25, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'bank islamic se cash ', 27, '2024-08-12', NULL, 'admin', 1, '2024-08-12 23:54:01', NULL, NULL),
(63, 'Bank', 'dr', 4, 'du4', 55000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 26, 1, 4, '2024-08-12', 'as1', 'cheque', '0000', 'bank islamic se cash ', 0, '2024-08-12', NULL, 'admin', 1, '2024-08-13 00:03:28', NULL, NULL),
(64, 'Bill', 'cr', 9, 'du9', 55000, 0, 0, 0, '*', 'AED', NULL, '', NULL, 0, 1, 12, 1, 10, '2024-08-13', 'as1', 'cheque', '0000', 'bank islamic se cash ', 27, '2024-08-13', NULL, 'admin', 1, '2024-08-13 00:04:02', NULL, NULL),
(65, 'Business', 'dr', 1, 'dc1', 211875, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 2, 1, 10, 1, 2, '2024-07-02', '2', ' Exchange', '2', 'Dr. A/c:dc3 IRR 1130000000 16000 AED 70625(Lee Khan Tsafar Kiya)', NULL, NULL, NULL, 'admin', 1, '2024-08-13 21:40:27', NULL, NULL),
(66, 'Business', 'cr', 3, 'dc3', 211875, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 2, 1, 11, 1, 2, '2024-07-02', '2', ' Exchange', '2', 'Cr. A/c:dc1 IRR 1130000000 16000 AED 70625(Lee Khan Tsafar Kiya)', NULL, NULL, NULL, 'admin', 1, '2024-08-13 21:40:27', NULL, NULL),
(67, 'Business', 'dr', 1, 'DC1', 67867.87, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 1, 1, 12, 1, 2, '2024-07-02', '1', ' Exchange', '1', 'Dr. A/c:DC5 IRR 1130000000 16650 AED 67867.87 :Lee Khan Tsafar Kiya', NULL, NULL, NULL, 'admin', 1, '2024-08-13 21:43:56', NULL, NULL),
(68, 'Business', 'cr', 5, 'DC5', 67867.87, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 1, 1, 13, 1, 2, '2024-07-02', '1', ' Exchange', '1', 'Cr. A/c:DC1 IRR 1130000000 16650 AED 67867.87 :Lee Khan Tsafar Kiya', NULL, NULL, NULL, 'admin', 1, '2024-08-13 21:43:56', NULL, NULL),
(69, 'Business', 'dr', 1, 'dc1', 200000, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 15, 1, 14, 1, 2, '2024-07-15', '15', ' Exchange', '15', 'Cr. A/c:dc5 INR 4720000 23.6 AED 200000( kaml purchase', NULL, NULL, NULL, 'admin', 1, '2024-08-15 18:29:00', NULL, NULL),
(70, 'Business', 'cr', 5, 'dc5', 200000, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 15, 1, 15, 1, 2, '2024-07-15', '15', ' Exchange', '15', 'Dr. A/c:dc1 INR 4720000 23.6 AED 200000( kaml purchase', NULL, NULL, NULL, 'admin', 1, '2024-08-15 18:29:00', NULL, NULL),
(71, 'Cash', 'dr', 17, 'dc17', 440400, 1, 120000, 3.67, '*', 'USD', 'najeebullah', '+923337764088', NULL, 0, 1, 17, 1, 2, '2024-09-06', '125', 'cheque', '12345', 'SANJAY NE MESHRIQ BANK ME TT  Asmatullah owi tt bank me Quetta brnch umsmdnsan sads jh  adhjs skdsakdhj  ahdsa  jsdfh  jskdhflasdhf Currency:USD Qty:120000 PerPrice:3.67', 0, '2024-09-06', 'r_uploads/OIP.jpg', 'admin', 1, '2024-09-06 14:11:55', NULL, NULL),
(72, 'Cash', 'cr', 1, 'dc1', 440400, 1, 120000, 3.67, '*', 'AED', 'najeebullah', '+923337764088', NULL, 0, 1, 18, 1, 2, '2024-09-06', '125', 'cheque', '12345', 'SANJAY NE MESHRIQ BANK ME TT  Asmatullah owi tt bank me Quetta brnch umsmdnsan sads jh  adhjs skdsakdhj  ahdsa  jsdfh  jskdhflasdhf Currency:AED Qty:120000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:12:40', NULL, NULL),
(73, 'Bill', 'cr', 5, 'DC5', 6000, 1, 138000, 23, '/', 'INR', '', '', NULL, 0, 1, 14, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:INR Qty:138000 PerPrice:23', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:14:55', NULL, NULL),
(74, 'Cash', 'dr', 5, 'DC5', 440400, 0, 0, 0, '*', 'AED', 'najeebullah', '+923337764088', NULL, 0, 1, 19, 1, 2, '2024-09-06', '125', 'cheque', '12345', 'SANJAY NE MESHRIQ BANK ME TT  Asmatullah owi tt bank me Quetta brnch umsmdnsan sads jh  adhjs skdsakdhj  ahdsa  jsdfh  jskdhflasdhf Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:15:16', NULL, NULL),
(75, 'Business', 'cr', 5, 'DC5', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 16, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:15:26', NULL, NULL),
(76, 'Business', 'dr', 6, 'DC6', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 17, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:15:35', NULL, NULL),
(77, 'Business', 'cr', 6, 'DC6', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 18, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:15:44', NULL, NULL),
(78, 'Business', 'dr', 15, 'DC15', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 19, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:16:00', NULL, NULL),
(79, 'Business', 'cr', 15, 'DC15', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 20, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:16:07', NULL, NULL),
(80, 'Business', 'dr', 15, 'DC15', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 21, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:16:14', NULL, NULL),
(81, 'Business', 'dr', 1, 'DC1', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 22, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:16:20', NULL, NULL),
(82, 'Business', 'cr', 1, 'DC1', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 23, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:16:27', NULL, NULL),
(83, 'Cash', 'dr', 1, 'DC1', 6000, 0, 0, 0, '*', 'AED', 'najeebullah', '+923337764088', NULL, 0, 1, 20, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', 'r_uploads/Screenshot 2023-10-23 173936.png', 'admin', 1, '2024-09-06 14:16:42', NULL, NULL),
(84, 'Bill', 'cr', 1, 'DC1', 183500, 1, 50000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 15, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:50000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:17:00', NULL, NULL),
(85, 'Bill', 'cr', 21, 'DC21', 183500, 1, 50000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 16, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:50000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:17:23', NULL, NULL),
(86, 'Business', 'dr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 24, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:17:32', NULL, NULL),
(87, 'Business', 'cr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 25, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:17:38', NULL, NULL),
(88, 'Business', 'dr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 26, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:17:43', NULL, NULL),
(89, 'Business', 'dr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 27, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:17:49', NULL, NULL),
(90, 'Business', 'cr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 28, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:17:56', NULL, NULL),
(91, 'Business', 'cr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 29, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:18:03', NULL, NULL),
(92, 'Business', 'dr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 30, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:18:09', NULL, NULL),
(93, 'Business', 'cr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 31, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:18:16', NULL, NULL),
(94, 'Cash', 'cr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', 'ENTRUS SHIPPING LLC', '07909122126', NULL, 0, 1, 21, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', 'r_uploads/290438179_414562123978661_87844885375616270_n.jpg', 'admin', 1, '2024-09-06 14:18:30', NULL, NULL),
(95, 'Bill', 'cr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 17, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:18:53', NULL, NULL),
(96, 'Bill', 'cr', 1, 'DC1', 183500, 1, 50000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 18, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:50000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:19:13', NULL, NULL),
(97, 'Bank', 'cr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 26, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:19:25', NULL, NULL),
(98, 'Bill', 'dr', 1, 'DC1', 183500, 1, 50000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 19, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:50000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:19:47', NULL, NULL),
(99, 'Cash', 'cr', 1, 'DC1', 311950, 1, 85000, 3.67, '*', 'AED', 'AQUA . CONTAINER LINE', '05138513510', NULL, 0, 1, 22, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:85000 PerPrice:3.67', 0, '2024-09-06', 'r_uploads/290438179_414562123978661_87844885375616270_n.jpg', 'admin', 1, '2024-09-06 14:20:19', NULL, NULL),
(100, 'Bill', 'cr', 1, 'DC1', 220200, 1, 60000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 20, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:60000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:20:37', NULL, NULL),
(101, 'Bill', 'dr', 1, 'DC1', 183500, 1, 50000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 21, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:50000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:21:12', NULL, NULL),
(102, 'Business', 'cr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 32, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:21:20', NULL, NULL),
(103, 'Business', 'dr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 33, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:21:27', NULL, NULL),
(104, 'Business', 'dr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 34, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:21:34', NULL, NULL),
(105, 'Business', 'cr', 1, 'DC1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 35, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:21:42', NULL, NULL),
(106, 'Business', 'cr', 20, 'dc20', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 36, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:21:57', NULL, NULL),
(107, 'Business', 'dr', 20, 'dc20', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 37, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:22:02', NULL, NULL),
(108, 'Business', 'dr', 1, 'dc1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 38, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:22:10', NULL, NULL),
(109, 'Business', 'cr', 1, 'dc1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 39, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:22:19', NULL, NULL),
(110, 'Business', 'dr', 1, 'dc1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 40, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:22:24', NULL, NULL),
(111, 'Business', 'cr', 1, 'dc1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 41, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:22:32', NULL, NULL),
(112, 'Business', 'cr', 1, 'dc1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 42, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:22:41', NULL, NULL),
(113, 'Business', 'cr', 1, 'dc1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 43, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:22:49', NULL, NULL),
(114, 'Bank', 'dr', 1, 'dc1', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 27, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:22:59', NULL, NULL),
(115, 'Bill', 'cr', 1, 'dc1', 205520, 1, 56000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 22, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:56000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:23:19', NULL, NULL),
(116, 'Bill', 'cr', 1, 'dc1', 110100, 1, 30000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 23, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:30000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:23:37', NULL, NULL),
(117, 'Bank', 'cr', 1, 'dc1', 110100, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 28, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 28, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:23:51', NULL, NULL),
(118, 'Bill', 'cr', 1, 'dc1', 293600, 1, 80000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 24, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:80000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:24:10', NULL, NULL),
(119, 'Bank', 'dr', 1, 'dc1', 293600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 29, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-12-06', NULL, 'admin', 1, '2024-09-06 14:24:26', '2024-09-06 14:24:33', 1),
(120, 'Bank', 'dr', 1, 'dc1', 293600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 30, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 26, '2024-12-09', NULL, 'admin', 1, '2024-09-06 14:24:51', NULL, NULL),
(121, 'Bank', 'dr', 1, 'dc1', 293600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 31, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:25:08', NULL, NULL),
(122, 'Bank', 'dr', 1, 'dc1', 293600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 32, 1, 2, '2024-09-06', 'XSC', 'receipts', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:25:22', NULL, NULL),
(123, 'Bank', 'dr', 1, 'dc1', 220200, 1, 60000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 33, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:60000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:25:48', NULL, NULL),
(124, 'Bank', 'dr', 1, 'dc1', 220200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 34, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:26:03', NULL, NULL),
(125, 'Bank', 'cr', 1, 'dc1', 220200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 35, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:26:17', NULL, NULL),
(126, 'Bank', 'dr', 1, 'dc1', 220200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 36, 1, 2, '2024-09-12', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:26:51', NULL, NULL),
(127, 'Bank', 'dr', 1, 'dc1', 220200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 37, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 28, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:27:08', NULL, NULL),
(128, 'Bank', 'dr', 1, 'dc1', 220200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 38, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:27:21', NULL, NULL),
(129, 'Bank', 'dr', 1, 'dc1', 220200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 39, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:27:34', NULL, NULL),
(130, 'Bank', 'dr', 1, 'dc1', 150000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 40, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 27, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:27:50', NULL, NULL),
(131, 'Business', 'dr', 1, 'dc1', 150000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 44, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'SANJAY NE MESHRIQ BANK ME TT KIA  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:28:02', NULL, NULL),
(132, 'Bank', 'dr', 1, 'dc1', 150000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 41, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'SANJAY NE MESHRIQ BANK ME TT KIA  Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:28:15', NULL, NULL),
(133, 'Bank', 'dr', 1, 'dc1', 150000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 42, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'SANJAY NE MESHRIQ BANK ME TT KIA  Currency:AED', 26, '2024-09-11', NULL, 'admin', 1, '2024-09-06 14:28:29', NULL, NULL),
(134, 'Bank', 'dr', 1, 'dc1', 150000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 43, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'SANJAY NE MESHRIQ BANK ME TT KIA  Currency:AED', 26, '2024-09-11', NULL, 'admin', 1, '2024-09-06 14:28:40', NULL, NULL),
(135, 'Bank', 'dr', 1, 'dc1', 150000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 44, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'SANJAY NE MESHRIQ BANK ME TT KIA  Currency:AED', 26, '2024-09-11', NULL, 'admin', 1, '2024-09-06 14:28:54', NULL, NULL),
(136, 'Bank', 'dr', 1, 'dc1', 150000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 45, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'SANJAY NE MESHRIQ BANK ME TT KIA  Currency:AED', 26, '2024-09-11', NULL, 'admin', 1, '2024-09-06 14:29:07', NULL, NULL),
(137, 'Bank', 'dr', 1, 'dc1', 150000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 46, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'SANJAY NE MESHRIQ BANK ME TT KIA  Currency:AED', 27, '2024-09-11', NULL, 'admin', 1, '2024-09-06 14:29:21', NULL, NULL),
(138, 'Bank', 'dr', 1, 'dc1', 150000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 47, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'SANJAY NE MESHRIQ BANK ME TT KIA  Currency:AED', 28, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:29:36', NULL, NULL),
(139, 'Bank', 'dr', 29, 'DC29', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 48, 1, 7, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 26, '2024-09-13', NULL, 'admin', 1, '2024-09-06 14:32:25', NULL, NULL),
(140, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 49, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:32:39', NULL, NULL),
(141, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 50, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:32:52', NULL, NULL),
(142, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 51, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 27, '2024-09-14', NULL, 'admin', 1, '2024-09-06 14:33:06', NULL, NULL),
(143, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 52, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 27, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:33:19', NULL, NULL),
(144, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 53, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 27, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:33:35', NULL, NULL),
(145, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 54, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 27, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:33:53', NULL, NULL),
(146, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 55, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 27, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:34:07', NULL, NULL),
(147, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 56, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:34:19', NULL, NULL),
(148, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 57, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:34:31', NULL, NULL),
(149, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 58, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:34:44', NULL, NULL),
(150, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 59, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 27, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:34:54', NULL, NULL),
(151, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 60, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 27, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:35:06', NULL, NULL),
(152, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 61, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 27, '2024-09-11', NULL, 'admin', 1, '2024-09-06 14:35:18', NULL, NULL),
(153, 'Bank', 'dr', 1, 'DC1', 160000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 62, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:35:33', NULL, NULL),
(154, 'Bank', 'dr', 1, 'DC1', 1600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 63, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:35:47', NULL, NULL),
(155, 'Bank', 'dr', 1, 'DC1', 1600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 64, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 27, '2024-09-15', NULL, 'admin', 1, '2024-09-06 14:36:13', NULL, NULL),
(156, 'Bank', 'dr', 1, 'DC1', 800000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 65, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 28, '2024-09-12', NULL, 'admin', 1, '2024-09-06 14:36:30', NULL, NULL),
(157, 'Bank', 'dr', 1, 'DC1', 800000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 66, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 27, '2024-10-06', NULL, 'admin', 1, '2024-09-06 14:36:46', NULL, NULL),
(158, 'Bank', 'dr', 1, 'DC1', 80000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 67, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 26, '2024-10-06', NULL, 'admin', 1, '2024-09-06 14:36:59', NULL, NULL),
(159, 'Bank', 'dr', 1, 'DC1', 700000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 68, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 26, '2024-10-12', NULL, 'admin', 1, '2024-09-06 14:37:18', NULL, NULL),
(160, 'Bank', 'dr', 1, 'DC1', 700000, 0, 0, 0, '*', 'INR', '', '', NULL, 0, 1, 70, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:INR Currency:INR Qty:0 PerPrice:0', 27, '2024-10-12', NULL, 'admin', 1, '2024-09-06 14:37:37', '2024-09-06 14:37:41', 1),
(161, 'Bank', 'dr', 1, 'DC1', 700000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 70, 1, 2, '2024-09-06', 'XSC ', 'receipts', 'CDSD ', 'meshriq bank se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 14:37:57', NULL, NULL),
(162, 'Business', 'dr', 1, 'DC1', 4000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 45, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'meshriq bank se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:14:10', NULL, NULL),
(163, 'Business', 'cr', 24, 'DC24', 3000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 46, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'meshriq bank se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:14:33', NULL, NULL),
(164, 'Business', 'dr', 24, 'DC24', 80000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 47, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'ADDSAF bank se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:14:49', NULL, NULL),
(165, 'Business', 'cr', 20, 'dc20', 80000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 48, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'ADDSAF bank se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:15:18', NULL, NULL),
(166, 'Business', 'dr', 1, 'dc1', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 49, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'ADDSAF bank se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:15:35', NULL, NULL),
(167, 'Business', 'cr', 15, 'DC15', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 50, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'ADDSAF bank se cash CASH  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:16:01', NULL, NULL),
(168, 'Business', 'cr', 1, 'DC1', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 51, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'ADDSAF bank se cash CASH  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:16:14', NULL, NULL),
(169, 'Business', 'dr', 6, 'DC6', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 52, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'ADDSAF bank se cash CASH  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:16:37', NULL, NULL),
(170, 'Business', 'cr', 7, 'DC7', 6000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 53, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'ADDSAF bank se cash CASH  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:16:54', NULL, NULL),
(171, 'Bill', 'dr', 1, 'DC1', 10000, 1, 1000, 10, '*', 'AED', '', '', NULL, 0, 1, 25, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:1000 PerPrice:10', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:17:42', NULL, NULL),
(172, 'Bill', 'cr', 15, 'DC15', 1000, 1, 100, 10, '*', 'AED', '', '', NULL, 0, 1, 26, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:100 PerPrice:10', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:18:03', NULL, NULL),
(173, 'Bill', 'dr', 6, 'DC6', 4000, 1, 200, 20, '*', 'AED', '', '', NULL, 0, 1, 27, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:200 PerPrice:20', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:18:23', NULL, NULL),
(174, 'Bill', 'cr', 24, 'DC24', 4000, 1, 200, 20, '*', 'AED', '', '', NULL, 0, 1, 28, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:200 PerPrice:20', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:18:40', NULL, NULL),
(175, 'Business', 'dr', 20, 'dc20', 70000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 54, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'meshriq bank se cash  Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:19:16', NULL, NULL),
(176, 'Business', 'cr', 1, 'dc1', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 55, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'meshriq bank se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:19:38', NULL, NULL),
(177, 'Cash', 'dr', 24, 'DC24', 183500, 0, 0, 0, '*', 'AED', 'ENTRUS SHIPPING LLC', '07909122126', NULL, 0, 1, 23, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:19:55', NULL, NULL),
(178, 'Cash', 'cr', 20, 'dc20', 183500, 0, 0, 0, '*', 'AED', 'ENTRUS SHIPPING LLC', '07909122126', NULL, 0, 1, 24, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:20:09', NULL, NULL);
INSERT INTO `roznamchaas` (`r_id`, `r_type`, `dr_cr`, `khaata_id`, `khaata_no`, `amount`, `is_qty`, `qty`, `per_price`, `operator`, `currency`, `c_name`, `mobile`, `transfered_from`, `transfered_from_id`, `khaata_branch_id`, `branch_serial`, `branch_id`, `cat_id`, `r_date`, `roznamcha_no`, `r_name`, `r_no`, `details`, `bank_id`, `r_date_payment`, `img`, `username`, `user_id`, `created_at`, `updated_at`, `updated_by`) VALUES
(179, 'Business', 'dr', 1, 'dc1', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 56, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'meshriq bank se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:20:18', NULL, NULL),
(180, 'Business', 'cr', 24, 'DC24', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 58, 1, 2, '2024-09-06', 'XSC ', 'cheque', 'CDSD ', 'meshriq bank se cash  Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:20:26', '2024-09-06 15:20:33', 1),
(181, 'Cash', 'cr', 6, 'DC6', 10000, 1, 1000, 10, '*', 'AED', 'AQUA . CONTAINER LINE', '05138513510', NULL, 0, 1, 25, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:1000 PerPrice:010', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:21:13', NULL, NULL),
(182, 'Cash', 'dr', 20, 'dc20', 183500, 0, 0, 0, '*', 'AED', 'ENTRUS SHIPPING LLC', '07909122126', NULL, 0, 1, 26, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:21:33', NULL, NULL),
(183, 'Business', 'dr', 24, 'DC24', 183500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 58, 1, 2, '2024-09-06', 'XSC ', 'cheque', '000', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:22:10', NULL, NULL),
(184, 'Business', 'dr', 26, 'db1 ', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 59, 1, 1, '2024-09-06', 'as1', 'cheque', '0000', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:22:44', NULL, NULL),
(185, 'Business', 'cr', 24, 'DC24', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 60, 1, 2, '2024-09-06', 'as1', 'cheque', '0000', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:23:02', NULL, NULL),
(186, 'Business', 'dr', 24, 'DC24', 1000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 61, 1, 2, '2024-09-06', 'XSC ', 'cheque', '0000', 'bank islamic se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:23:35', NULL, NULL),
(187, 'Business', 'cr', 26, 'db1 ', 1000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 62, 1, 1, '2024-09-06', 'XSC ', 'cheque', '0000', 'bank islamic se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:23:48', NULL, NULL),
(188, 'Business', 'dr', 26, 'db1 ', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 63, 1, 1, '2024-09-06', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:24:18', NULL, NULL),
(189, 'Business', 'cr', 6, 'DC6', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 64, 1, 2, '2024-09-06', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:24:39', NULL, NULL),
(190, 'Business', 'cr', 5, 'DC5', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 66, 1, 2, '2024-09-06', 'as1', 'cheque', '0000', 'SANJAY NE MESHRIQ BANK ME TT KIA   Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:31:46', '2024-09-06 15:31:58', 1),
(191, 'Business', 'dr', 24, 'DC24', 10000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 66, 1, 2, '2024-09-06', 'XSC', 'cheque', '00', 'fareed ullah ne liye office kharcha kiye  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:34:11', NULL, NULL),
(192, 'Business', 'cr', 1, 'dc1', 10000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 67, 1, 2, '2024-09-06', 'XSC', 'cheque', '00', 'fareed ullah ne liye office kharcha kiye  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:34:22', NULL, NULL),
(193, 'Business', 'dr', 1, 'dc1', 1000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 68, 1, 2, '2024-09-06', 'XSC', 'cheque', '00', 'fareed ullah ne liye office kharcha kiye  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:34:36', NULL, NULL),
(194, 'Business', 'cr', 6, 'DC6', 1000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 69, 1, 2, '2024-09-06', 'XSC', 'cheque', '00', 'fareed ullah ne liye office kharcha kiye  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:34:46', NULL, NULL),
(195, 'Business', 'cr', 24, 'DC24', 1000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 71, 1, 2, '2024-09-06', 'XSC', 'cheque', '00', 'fareed ullah ne liye office kharcha kiye  Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:35:09', '2024-09-06 15:46:31', 1),
(196, 'Business', 'dr', 26, 'db1 ', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 71, 1, 1, '2024-09-06', 'as1', 'cheque', '0000', 'bank islamic se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:46:48', NULL, NULL),
(197, 'Business', 'cr', 26, 'db1 ', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 72, 1, 1, '2024-09-06', 'as1', 'cheque', '0000', 'bank islamic se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:46:59', NULL, NULL),
(198, 'Business', 'dr', 26, 'db1 ', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 73, 1, 1, '2024-09-06', 'as1', 'cheque', '0000', 'bank islamic se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:47:10', NULL, NULL),
(199, 'Business', 'cr', 26, 'db1 ', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 74, 1, 1, '2024-09-06', 'as1', 'cheque', '0000', 'bank islamic se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:47:18', NULL, NULL),
(200, 'Business', 'dr', 1, 'dc1', 5000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 75, 1, 2, '2024-09-06', 'as1', 'cheque', '0000', 'bank islamic se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:47:42', NULL, NULL),
(201, 'Business', 'cr', 15, 'DC15', 71111, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 76, 1, 2, '2024-09-06', 'as1', 'cheque', '0000', 'bank islamic se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:48:03', NULL, NULL),
(202, 'Business', 'dr', 15, 'DC15', 71111, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 77, 1, 2, '2024-09-06', 'as1', 'cheque', '0000', 'bank islamic se cash  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:48:11', NULL, NULL),
(203, 'Business', 'cr', 6, 'DC6', 10000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 78, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:48:23', NULL, NULL),
(204, 'Business', 'dr', 1, 'dc1', 10000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 79, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:48:34', NULL, NULL),
(205, 'Business', 'cr', 15, 'DC15', 45000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 80, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 15:48:48', NULL, NULL),
(206, 'Cash', 'dr', 1, 'Dc1', 36700, 1, 10000, 3.67, '*', 'AED', 'Asmatullah ', '0000000', NULL, 0, 1, 27, 1, 2, '2024-09-06', 'Qq', 'cheque', '111', 'Ek Mushir, Bank check received purchase walnut Walnut total quantity 800 received Najeebullah And Company Currency:AED Qty:10000 PerPrice:3.67', 0, '2024-09-06', 'r_uploads/IMG_5969.jpeg', 'Admin', 1, '2024-09-06 15:50:46', NULL, NULL),
(207, 'Bill', 'dr', 28, 'Db3', 127716, 1, 34800, 3.67, '*', 'USD', '', '', NULL, 0, 1, 29, 1, 1, '2024-09-06', 'Dgt123', 'cheque', '12345779', 'Say Nahi Mata Present received najeebullah Currency:USD Qty:34800 PerPrice:3.67', 0, '2024-09-06', NULL, 'Admin', 1, '2024-09-06 15:56:09', NULL, NULL),
(208, 'Cash', 'dr', 1, 'Dc1', 147826.09, 1, 3400000, 23, '/', 'AED', 'Sunjay ', '000000000886655', NULL, 0, 1, 28, 1, 2, '2024-09-06', 'Wrt', 'cheque', '567489', 'Transfer to Kyrgyzstan Pachu purchase Bill Currency:AED Qty:3400000 PerPrice:23', 0, '2024-09-06', 'r_uploads/fc207ee1-16ed-4458-8b77-3c53aa735991.jpeg', 'Admin', 1, '2024-09-06 15:57:50', NULL, NULL),
(209, 'Bill', 'dr', 1, 'Dc1', 439440, 1, 120000, 3.662, '*', 'AED', '', '', NULL, 0, 1, 30, 1, 2, '2024-09-06', 'R', 'cheque', '12/:', 'Transport, Jan Agha Kandahar Bissell seeds Purchase Money changes Puzzle  Currency:AED Qty:120000 PerPrice:3.662', 0, '2024-09-06', NULL, 'Admin', 1, '2024-09-06 16:00:14', NULL, NULL),
(210, 'Bill', 'dr', 1, 'Dc1', 10000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 31, 1, 2, '2024-09-06', 'Qw345', 'cheque', 'Ddgv', 'Transport money changers a puzzle Kandahar 19.30 purchase one currency Currency:AED', 0, '2024-09-06', NULL, 'Admin', 1, '2024-09-06 16:01:20', NULL, NULL),
(211, 'Bank', 'dr', 26, 'db1', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 71, 1, 1, '2024-09-12', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-13', NULL, 'admin', 1, '2024-09-06 16:31:34', NULL, NULL),
(212, 'Cash', 'dr', 30, 'dc1000', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 29, 1, 2, '2024-09-12', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:31:47', '2024-09-06 16:32:00', 1),
(213, 'Business', 'dr', 30, 'dc1000', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 81, 1, 2, '2024-09-12', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:32:19', NULL, NULL),
(214, 'Business', 'dr', 30, 'dc1000', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 82, 1, 2, '2024-09-12', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:32:30', NULL, NULL),
(215, 'Business', 'dr', 30, 'dc1000', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 83, 1, 2, '2024-09-12', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:33:01', NULL, NULL),
(216, 'Bank', 'dr', 30, 'dc1000', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 72, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:33:22', NULL, NULL),
(217, 'Bank', 'dr', 30, 'dc1000', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 73, 1, 2, '2024-09-02', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 16:33:38', NULL, NULL),
(218, 'Business', 'dr', 30, 'dc1000', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 84, 1, 2, '2024-09-12', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:33:46', NULL, NULL),
(219, 'Business', 'dr', 30, 'dc1000', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 85, 1, 2, '2024-09-12', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:33:54', NULL, NULL),
(220, 'Bank', 'dr', 30, 'dc1000', 1000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 74, 1, 2, '2024-09-12', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:34:26', NULL, NULL),
(221, 'Business', 'dr', 30, 'dc1000', 1000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 86, 1, 2, '2024-09-12', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:34:34', NULL, NULL),
(222, 'Business', 'dr', 30, 'dc1000', 1000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 87, 1, 2, '2024-09-12', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:34:43', NULL, NULL),
(223, 'Bank', 'dr', 30, 'dc1000', 1000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 75, 1, 2, '2024-09-09', 'AS1', 'receipts', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 27, '2024-09-12', NULL, 'admin', 1, '2024-09-06 16:34:58', NULL, NULL),
(224, 'Bank', 'dr', 30, 'dc1000', 1000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 76, 1, 2, '2024-09-08', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 27, '2024-09-12', NULL, 'admin', 1, '2024-09-06 16:35:13', NULL, NULL),
(225, 'Bank', 'dr', 30, 'dc1000', 1000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 77, 1, 2, '2024-09-08', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-18', NULL, 'admin', 1, '2024-09-06 16:35:28', NULL, NULL),
(226, 'Bank', 'dr', 30, 'dc1000', 1000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 78, 1, 2, '2024-09-08', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-18', NULL, 'admin', 1, '2024-09-06 16:35:29', NULL, NULL),
(227, 'Bank', 'dr', 30, 'dc1000', 1000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 79, 1, 2, '2024-09-12', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-28', NULL, 'admin', 1, '2024-09-06 16:35:45', NULL, NULL),
(228, 'Bank', 'dr', 30, 'dc1000', 1000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 80, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-26', NULL, 'admin', 1, '2024-09-06 16:35:55', NULL, NULL),
(229, 'Bank', 'dr', 30, 'dc1000', 1000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 81, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-27', NULL, 'admin', 1, '2024-09-06 16:36:06', NULL, NULL),
(230, 'Bank', 'dr', 30, 'dc1000', 250000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 82, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-27', NULL, 'admin', 1, '2024-09-06 16:36:21', NULL, NULL),
(231, 'Bank', 'dr', 30, 'dc1000', 250000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 83, 1, 2, '2024-09-08', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-10-04', NULL, 'admin', 1, '2024-09-06 16:36:40', NULL, NULL),
(232, 'Bank', 'dr', 30, 'dc1000', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 84, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-19', NULL, 'admin', 1, '2024-09-06 16:36:57', NULL, NULL),
(233, 'Bank', 'dr', 30, 'dc1000', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 85, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-21', NULL, 'admin', 1, '2024-09-06 16:37:08', NULL, NULL),
(234, 'Business', 'dr', 26, 'db1', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 88, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:37:37', NULL, NULL),
(235, 'Business', 'dr', 26, 'db1', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 89, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:37:43', NULL, NULL),
(236, 'Bank', 'dr', 26, 'db1', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 86, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-27', NULL, 'admin', 1, '2024-09-06 16:37:54', NULL, NULL),
(237, 'Business', 'dr', 30, 'dc1000', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 90, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:38:21', NULL, NULL),
(238, 'Business', 'dr', 30, 'dc1000', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 91, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:38:25', NULL, NULL),
(239, 'Business', 'dr', 26, 'db1', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 92, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:38:34', NULL, NULL),
(240, 'Business', 'dr', 26, 'db1', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 93, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:38:39', NULL, NULL),
(241, 'Business', 'dr', 26, 'db1', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 94, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:38:45', NULL, NULL),
(242, 'Business', 'dr', 26, 'db1', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 95, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:38:51', NULL, NULL),
(243, 'Business', 'dr', 26, 'db1', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 96, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:38:55', NULL, NULL),
(244, 'Business', 'dr', 26, 'db1', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 97, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:38:59', NULL, NULL),
(245, 'Business', 'dr', 26, 'db1', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 98, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:39:03', NULL, NULL),
(246, 'Business', 'dr', 10, 'du10', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 99, 1, 4, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:39:26', NULL, NULL),
(247, 'Business', 'dr', 10, 'du10', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 100, 1, 4, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:39:31', NULL, NULL),
(248, 'Business', 'dr', 9, 'du9', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 101, 1, 10, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:39:40', NULL, NULL),
(249, 'Business', 'dr', 9, 'du9', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 102, 1, 10, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:39:45', NULL, NULL),
(250, 'Bill', 'dr', 26, 'db1', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 32, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:39:53', NULL, NULL),
(251, 'Bill', 'dr', 9, 'du9', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 33, 1, 10, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:40:01', NULL, NULL),
(252, 'Business', 'dr', 19, 'dc19', 258000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 104, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:40:06', '2024-09-06 16:40:20', 1),
(253, 'Business', 'dr', 19, 'dc19', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 104, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:40:34', NULL, NULL),
(254, 'Business', 'dr', 19, 'dc19', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 105, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:40:39', NULL, NULL),
(255, 'Business', 'dr', 19, 'dc19', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 106, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:40:44', NULL, NULL),
(256, 'Business', 'dr', 19, 'dc19', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 107, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:40:49', NULL, NULL),
(257, 'Bank', 'dr', 19, 'dc19', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 87, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:40:56', NULL, NULL),
(258, 'Bank', 'dr', 23, 'DG23', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 88, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:41:49', NULL, NULL),
(259, 'Bank', 'dr', 23, 'DG23', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 89, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:41:49', NULL, NULL),
(260, 'Bank', 'dr', 23, 'DG23', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 90, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:41:58', NULL, NULL),
(261, 'Bank', 'dr', 23, 'DG23', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 91, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:42:07', NULL, NULL),
(262, 'Bank', 'dr', 23, 'DG23', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 92, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:42:16', NULL, NULL),
(263, 'Bill', 'dr', 23, 'DG23', 36700, 1, 10000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 34, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Qty:10000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:42:32', NULL, NULL),
(264, 'Bill', 'dr', 23, 'DG23', 625000, 1, 25000, 25, '*', 'AED', '', '', NULL, 0, 1, 35, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Qty:25000 PerPrice:25', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:42:48', NULL, NULL),
(265, 'Cash', 'dr', 6, 'Dc6', 625000, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 31, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 16:43:25', '2024-09-06 16:43:28', 1),
(266, 'Business', 'dr', 6, 'Dc6', 625000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 108, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:43:33', NULL, NULL),
(267, 'Bill', 'dr', 15, 'DC15', 45000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 36, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, '', 0, '2024-09-06 16:43:41', NULL, NULL),
(268, 'Bank', 'dr', 6, 'Dc6', 365217.39, 1, 8400000, 23, '/', 'INR', '', '', NULL, 0, 1, 94, 1, 2, '2024-09-06', 'AS1', 'receipts', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Qty:360000 PerPrice:3.67 Currency:INR Qty:8400000 PerPrice:23', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:43:47', '2024-09-06 16:44:40', 1),
(269, 'Bank', 'dr', 15, 'DC15', 1000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 93, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:44:40', NULL, NULL),
(270, 'Business', 'dr', 6, 'Dc6', 1321200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 109, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:44:47', NULL, NULL),
(271, 'Bank', 'dr', 6, 'Dc6', 1321200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 95, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:44:56', NULL, NULL),
(272, 'Business', 'dr', 6, 'Dc6', 1321200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 110, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:45:01', NULL, NULL),
(273, 'Cash', 'dr', 6, 'DC6', 3670, 1, 1000, 3.67, '*', 'AED', 'AQUA . CONTAINER LINE', '05138513510', NULL, 0, 1, 31, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:1000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:45:05', NULL, NULL),
(274, 'Cash', 'dr', 6, 'Dc6', 1321200, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 32, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 16:45:14', NULL, NULL),
(275, 'Cash', 'dr', 6, 'Dc6', 1321200, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 33, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 16:45:27', NULL, NULL),
(276, 'Cash', 'cr', 1, 'DC1', 7340, 1, 2000, 3.67, '*', 'AED', 'AQUA . CONTAINER LINE', '05138513510', NULL, 0, 1, 34, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:2000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:45:35', NULL, NULL),
(277, 'Bank', 'dr', 6, 'Dc6', 1321200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 96, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:45:35', NULL, NULL),
(278, 'Business', 'dr', 6, 'Dc6', 1321200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 111, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:45:40', NULL, NULL),
(279, 'Bank', 'dr', 17, 'dc17', 1321200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 98, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Currency:AED Qty:0 PerPrice:0', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:45:52', '2024-09-06 16:46:06', 1),
(280, 'Business', 'dr', 6, 'Dc6', 1321200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 112, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:46:11', NULL, NULL),
(281, 'Cash', 'dr', 15, 'DC15', 7340, 1, 2000, 3.67, '*', 'AED', 'ENTRUS SHIPPING LLC', '07909122126', NULL, 0, 1, 35, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:2000 PerPrice:3.67', 0, '2024-09-06', 'r_uploads/290438179_414562123978661_87844885375616270_n.jpg', 'admin', 1, '2024-09-06 16:46:18', NULL, NULL),
(282, 'Cash', 'dr', 30, 'dc1000', 1321200, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 36, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:46:26', NULL, NULL),
(283, 'Bank', 'dr', 6, 'Dc6', 1321200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 98, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:46:38', NULL, NULL),
(284, 'Bill', 'dr', 24, 'DC24', 73400, 1, 20000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 37, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:20000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:46:57', NULL, NULL),
(285, 'Business', 'cr', 1, 'DC1', 73400, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 113, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:47:11', NULL, NULL),
(286, 'Cash', 'dr', 6, 'Dc6', 1321200, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 37, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 16:47:17', NULL, NULL),
(287, 'Business', 'dr', 6, 'Dc6', 1321200, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 114, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:47:29', NULL, NULL),
(288, 'Bill', 'dr', 6, 'Dc6', 367000, 1, 100000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 38, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Qty:100000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:47:46', NULL, NULL),
(289, 'Cash', 'dr', 6, 'DC6', 2018.5, 1, 550, 3.67, '*', 'AED', 'ENTRUS SHIPPING LLC', '07909122126', NULL, 0, 1, 39, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:550 PerPrice:3.67 Currency:AED Qty:550 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:47:46', '2024-09-06 16:47:58', 1),
(290, 'Business', 'dr', 6, 'Dc6', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 115, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:47:51', NULL, NULL),
(291, 'Business', 'dr', 6, 'Dc6', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 116, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:47:56', NULL, NULL),
(292, 'Business', 'dr', 6, 'Dc6', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 117, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:48:01', NULL, NULL),
(293, 'Bank', 'dr', 6, 'Dc6', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 99, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 27, '2024-09-26', NULL, 'admin', 1, '2024-09-06 16:48:12', NULL, NULL),
(294, 'Bank', 'cr', 6, 'Dc6', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 100, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:48:21', NULL, NULL),
(295, 'Bank', 'cr', 6, 'Dc6', 367000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 101, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:48:30', NULL, NULL),
(296, 'Cash', 'dr', 26, 'db1 ', 11010, 1, 3000, 3.67, '*', 'AED', 'TT  mashriq bank', '0544816664', NULL, 0, 1, 39, 1, 1, '2024-09-06', 'as1', 'cheque', '00', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:3000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:48:31', NULL, NULL),
(297, 'Bill', 'cr', 6, 'Dc6', 891000, 1, 891000, 1, '*', 'AED', '', '', NULL, 0, 1, 39, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Qty:891000 PerPrice:1', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:48:46', NULL, NULL),
(298, 'Cash', 'cr', 1, 'DC1', 22020, 1, 6000, 3.67, '*', 'AED', 'TT  mashriq bank', '0544816664', NULL, 0, 1, 41, 1, 2, '2024-09-06', 'XSC', 'cheque', 'CDSD', 'aik t,t mashriq bank leason ke hesab me dollar 37180 one dollar price 3.66 total darham 136078 Currency:AED Qty:6000 PerPrice:3.67 Currency:AED Qty:6000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:48:58', '2024-09-06 16:49:06', 1),
(299, 'Business', 'cr', 6, 'Dc6', 891000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 118, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:49:00', NULL, NULL),
(300, 'Business', 'dr', 6, 'Dc6', 891000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 119, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:49:07', NULL, NULL),
(301, 'Business', 'dr', 6, 'Dc6', 891000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 120, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:49:12', NULL, NULL),
(302, 'Bank', 'cr', 6, 'Dc6', 891000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 102, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:49:21', NULL, NULL),
(303, 'Business', 'cr', 6, 'Dc6', 891000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 121, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:49:28', NULL, NULL),
(304, 'Business', 'dr', 6, 'Dc6', 891000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 122, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:49:35', NULL, NULL),
(305, 'Business', 'dr', 6, 'DC25', 891000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 124, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:49:40', '2024-09-06 16:50:04', 1),
(306, 'Business', 'dr', 25, 'DC25', 891000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 124, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:50:23', NULL, NULL),
(307, 'Bank', 'dr', 25, 'DC25', 891000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 103, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 27, '2024-09-13', NULL, 'admin', 1, '2024-09-06 16:50:35', NULL, NULL),
(308, 'Bank', 'dr', 25, 'DC25', 891000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 104, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:50:50', NULL, NULL),
(309, 'Bank', 'dr', 25, 'DC25', 1500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 105, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:51:03', NULL, NULL),
(310, 'Bank', 'dr', 25, 'DC25', 1500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 106, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:51:13', NULL, NULL),
(311, 'Bill', 'dr', 25, 'DC25', 999000, 1, 999000, 1, '*', 'AED', '', '', NULL, 0, 1, 40, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Qty:999000 PerPrice:1', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:51:28', NULL, NULL),
(312, 'Bill', 'dr', 25, 'DC25', 690000, 1, 230000, 3, '*', 'INR', '', '', NULL, 0, 1, 41, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:INR Qty:230000 PerPrice:3', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:51:45', NULL, NULL),
(313, 'Bill', 'dr', 25, 'DC25', 112000, 1, 56000, 2, '*', 'AED', '', '', NULL, 0, 1, 42, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Qty:56000 PerPrice:2', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:52:01', NULL, NULL),
(314, 'Bill', 'dr', 25, 'DC25', 750000, 1, 250000, 3, '*', 'AED', '', '', NULL, 0, 1, 43, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Qty:250000 PerPrice:3', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:52:19', NULL, NULL),
(315, 'Business', 'dr', 27, 'DB2', 1500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 125, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:53:08', NULL, NULL),
(316, 'Business', 'dr', 27, 'DB2', 1500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 126, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:53:12', NULL, NULL),
(317, 'Bill', 'dr', 27, 'DB2', 1500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 44, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:53:20', NULL, NULL),
(318, 'Bank', 'dr', 27, 'DB2', 1500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 107, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:53:28', NULL, NULL),
(319, 'Bank', 'cr', 27, 'DB2', 1500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 108, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:53:36', NULL, NULL),
(320, 'Bank', 'cr', 27, 'DB2', 1500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 109, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:53:48', NULL, NULL),
(321, 'Bank', 'cr', 27, 'DB2', 1500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 110, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:53:56', NULL, NULL),
(322, 'Cash', 'dr', 27, 'DB2', 500000, 1, 250000, 2, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 41, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Qty:250000 PerPrice:2', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 16:54:16', NULL, NULL),
(323, 'Cash', 'dr', 27, 'DB2', 500000, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 42, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 16:54:27', NULL, NULL),
(324, 'Cash', 'cr', 27, 'DB2', 500000, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 43, 1, 1, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 16:54:41', NULL, NULL),
(325, 'Business', 'dr', 20, 'DC20', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 127, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:55:57', NULL, NULL),
(326, 'Business', 'dr', 20, 'DC20', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 128, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:55:57', NULL, NULL),
(327, 'Bank', 'dr', 20, 'DC20', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 111, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:56:06', NULL, NULL),
(328, 'Bank', 'dr', 20, 'DC20', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 112, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:56:14', NULL, NULL),
(329, 'Bank', 'dr', 20, 'DC20', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 113, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:56:21', NULL, NULL),
(330, 'Business', 'cr', 20, 'DC20', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 129, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:56:27', NULL, NULL),
(331, 'Bank', 'cr', 20, 'DC20', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 114, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:56:35', NULL, NULL),
(332, 'Bank', 'cr', 20, 'DC20', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 115, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:56:44', NULL, NULL),
(333, 'Bank', 'dr', 20, 'DC20', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 116, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:56:53', NULL, NULL),
(334, 'Bank', 'dr', 11, 'DU11', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 118, 1, 4, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Currency:AED Qty:0 PerPrice:0 Currency:AED Qty:0 PerPrice:0', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:57:01', '2024-09-06 16:57:30', 1),
(335, 'Business', 'dr', 11, 'DU11', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 130, 1, 4, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:57:41', NULL, NULL),
(336, 'Cash', 'dr', 11, 'DU11', 500000, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 44, 1, 4, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:57:50', NULL, NULL),
(337, 'Business', 'cr', 11, 'DU11', 250000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 131, 1, 4, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:58:01', NULL, NULL),
(338, 'Business', 'cr', 11, 'DU11', 250000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 132, 1, 4, '2024-09-06', 'AS1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:58:02', NULL, NULL),
(339, 'Business', 'dr', 11, 'DU11', 250000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 133, 1, 4, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:58:11', NULL, NULL),
(340, 'Bill', 'cr', 11, 'DU11', 231210, 1, 63000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 45, 1, 4, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED Qty:63000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:58:30', NULL, NULL),
(341, 'Business', 'dr', 11, 'DU11', 231210, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 134, 1, 4, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:58:35', NULL, NULL),
(342, 'Business', 'dr', 11, 'DU11', 231210, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 135, 1, 4, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:58:40', NULL, NULL),
(343, 'Business', 'dr', 11, 'DU11', 231210, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 136, 1, 4, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 16:58:44', NULL, NULL),
(344, 'Business', 'dr', 22, 'DC22', 22440, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 137, 1, 6, '2024-09-06', 'AS1', 'cheque', '2', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:00:47', NULL, NULL),
(345, 'Business', 'cr', 22, 'DC22', 22440, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 138, 1, 6, '2024-09-06', 'AS1', 'cheque', '2', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:00:53', NULL, NULL),
(346, 'Bill', 'dr', 22, 'DC22', 22440, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 46, 1, 6, '2024-09-06', 'AS1', 'cheque', '2', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:01:00', NULL, NULL),
(347, 'Bank', 'dr', 22, 'DC22', 22440, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 118, 1, 6, '2024-09-06', 'AS1', 'cheque', '2', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:01:08', NULL, NULL),
(348, 'Cash', 'dr', 22, 'DC22', 22440, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 45, 1, 6, '2024-09-06', 'AS1', 'cheque', '2', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:01:16', NULL, NULL),
(349, 'Cash', 'dr', 22, 'DC22', 22440, 0, 0, 0, '*', 'USD', 'TRUCK POAN', '02143000022', NULL, 0, 1, 46, 1, 6, '2024-09-06', 'AS1', 'cheque', '2', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:USD', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 17:01:31', NULL, NULL),
(350, 'Business', 'dr', 29, 'DC29', 22440, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 139, 1, 7, '2024-09-06', 'AS1', 'cheque', '2', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:02:12', NULL, NULL),
(351, 'Business', 'dr', 29, 'DC29', 22440, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 140, 1, 7, '2024-09-06', 'AS1', 'cheque', '2', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:02:16', NULL, NULL),
(352, 'Business', 'dr', 22, 'DC22', 224400, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 141, 1, 6, '2024-09-06', 'AS', 'cheque', '22', 'INVESTMENT ACOUNTs to transferred0  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:03:11', NULL, NULL);
INSERT INTO `roznamchaas` (`r_id`, `r_type`, `dr_cr`, `khaata_id`, `khaata_no`, `amount`, `is_qty`, `qty`, `per_price`, `operator`, `currency`, `c_name`, `mobile`, `transfered_from`, `transfered_from_id`, `khaata_branch_id`, `branch_serial`, `branch_id`, `cat_id`, `r_date`, `roznamcha_no`, `r_name`, `r_no`, `details`, `bank_id`, `r_date_payment`, `img`, `username`, `user_id`, `created_at`, `updated_at`, `updated_by`) VALUES
(353, 'Bank', 'dr', 22, 'DC22', 224400, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 119, 1, 6, '2024-09-06', 'AS', 'cheque', '22', 'INVESTMENT ACOUNTs to transferred0  me Total alculation   Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:03:19', NULL, NULL),
(354, 'Business', 'dr', 31, 'DC30', 224400, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 143, 1, 2, '2024-09-06', 'AS', 'cheque', '22', 'INVESTMENT ACOUNTs to transferred0  me Total alculation   Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:03:26', '2024-09-06 17:03:32', 1),
(355, 'Business', 'dr', 31, 'DC30', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 143, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'عصمت اللہ نے ٹسفر کیا ٹوٹل حصاب  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:03:47', NULL, NULL),
(356, 'Business', 'dr', 31, 'DC30', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 144, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'عصمت اللہ نے ٹسفر کیا ٹوٹل حصاب  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:03:52', NULL, NULL),
(357, 'Business', 'dr', 31, 'DC30', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 145, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:04:01', NULL, NULL),
(358, 'Business', 'dr', 31, 'DC30', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 146, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:04:06', NULL, NULL),
(359, 'Bill', 'cr', 31, 'DC30', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 47, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:04:14', NULL, NULL),
(360, 'Business', 'cr', 31, 'DC30', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 147, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:04:20', NULL, NULL),
(361, 'Cash', 'dr', 31, 'DC30', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 47, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:04:27', NULL, NULL),
(362, 'Business', 'cr', 31, 'DC30', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 148, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:04:34', NULL, NULL),
(363, 'Bill', 'dr', 31, 'DC30', 108000, 1, 36000, 3, '*', 'AED', '', '', NULL, 0, 1, 48, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED Qty:36000 PerPrice:3', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:04:49', NULL, NULL),
(364, 'Business', 'dr', 31, 'DC30', 108000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 149, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:04:55', NULL, NULL),
(365, 'Bank', 'dr', 31, 'DC30', 108000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 120, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:05:03', NULL, NULL),
(366, 'Bank', 'dr', 31, 'DC30', 108000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 121, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:05:10', NULL, NULL),
(367, 'Business', 'dr', 32, 'DC31', 108000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 150, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:05:19', NULL, NULL),
(368, 'Business', 'dr', 32, 'DC31', 108000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 151, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:05:24', NULL, NULL),
(369, 'Business', 'cr', 32, 'DC31', 108000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 152, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:05:30', NULL, NULL),
(370, 'Business', 'cr', 32, 'DC31', 108000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 153, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:05:35', NULL, NULL),
(371, 'Business', 'dr', 33, 'DC33', 108000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 154, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:05:47', NULL, NULL),
(372, 'Bank', 'dr', 33, 'DC33', 108000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 122, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:05:55', NULL, NULL),
(373, 'Bill', 'dr', 33, 'DC33', 3600000, 1, 3600000, 1, '*', 'AED', '', '', NULL, 0, 1, 49, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED Qty:3600000 PerPrice:1', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:06:10', NULL, NULL),
(374, 'Business', 'dr', 33, 'DC33', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 155, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:06:17', NULL, NULL),
(375, 'Bank', 'dr', 33, 'DC33', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 123, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:06:25', NULL, NULL),
(376, 'Business', 'cr', 33, 'DC33', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 156, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:06:31', NULL, NULL),
(377, 'Business', 'cr', 33, 'DC33', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 157, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:06:37', NULL, NULL),
(378, 'Business', 'dr', 33, 'DC33', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 158, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:06:41', NULL, NULL),
(379, 'Business', 'dr', 33, 'DC33', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 159, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:06:45', NULL, NULL),
(380, 'Business', 'dr', 33, 'DC33', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 160, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:06:49', NULL, NULL),
(381, 'Business', 'dr', 34, 'DC34', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 161, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:06:57', NULL, NULL),
(382, 'Business', 'cr', 34, 'DC34', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 162, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:07:03', NULL, NULL),
(383, 'Business', 'cr', 34, 'DC34', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 163, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:07:32', NULL, NULL),
(384, 'Bank', 'dr', 34, 'DC34', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 124, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:07:41', NULL, NULL),
(385, 'Bank', 'cr', 34, 'DC34', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 125, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 28, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:07:49', NULL, NULL),
(386, 'Bank', 'dr', 34, 'DC34', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 126, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:07:57', NULL, NULL),
(387, 'Bank', 'dr', 34, 'DC34', 3600000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 127, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdbdzfzdfbzd zdfbdzbzfbdfb dsfgr  dggrW Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:08:04', NULL, NULL),
(388, 'Bank', 'dr', 34, 'DC34', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 128, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:08:25', NULL, NULL),
(389, 'Bill', 'dr', 34, 'DC34', 50000, 1, 25000, 2, '*', 'AED', '', '', NULL, 0, 1, 50, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED Qty:25000 PerPrice:2', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:08:49', NULL, NULL),
(390, 'Cash', 'dr', 34, 'DC34', 50000, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 48, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:08:57', NULL, NULL),
(391, 'Cash', 'dr', 34, 'DC34', 50000, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 49, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:09:08', NULL, NULL),
(392, 'Bill', 'dr', 36, 'DC35', 50000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 52, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:09:14', '2024-09-06 17:09:49', 1),
(393, 'Business', 'dr', 36, 'DC35', 50000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 164, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:09:56', NULL, NULL),
(394, 'Business', 'cr', 36, 'DC35', 50000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 165, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:10:03', NULL, NULL),
(395, 'Business', 'cr', 36, 'DC35', 50000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 166, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:10:10', NULL, NULL),
(396, 'Bill', 'dr', 36, 'DC35', 900000, 1, 300000, 3, '*', 'AED', '', '', NULL, 0, 1, 52, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED Qty:300000 PerPrice:3', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:10:24', NULL, NULL),
(397, 'Bank', 'cr', 36, 'DC35', 900000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 129, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:10:32', NULL, NULL),
(398, 'Cash', 'dr', 36, 'DC35', 900000, 0, 0, 0, '*', 'AED', 'ASMATULLAH', '+54 544816664', NULL, 0, 1, 50, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 17:10:48', NULL, NULL),
(399, 'Bank', 'cr', 36, 'DC35', 900000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 130, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:10:58', NULL, NULL),
(400, 'Cash', 'dr', 36, 'DC35', 900000, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 51, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 17:11:06', NULL, NULL),
(401, 'Bank', 'dr', 38, 'DC37', 900000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 131, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:07:53', NULL, NULL),
(402, 'Bank', 'dr', 38, 'DC37', 900000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 132, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 26, '2024-09-14', NULL, 'admin', 1, '2024-09-06 19:08:06', NULL, NULL),
(403, 'Bank', 'dr', 38, 'DC37', 900000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 133, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 26, '2024-09-15', NULL, 'admin', 1, '2024-09-06 19:08:24', NULL, NULL),
(404, 'Bank', 'dr', 38, 'DC37', 900000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 134, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:08:33', NULL, NULL),
(405, 'Bank', 'dr', 38, 'DC37', 900000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 135, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:08:42', NULL, NULL),
(406, 'Bank', 'dr', 38, 'DC37', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 136, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 26, '2024-09-13', NULL, 'admin', 1, '2024-09-06 19:08:59', NULL, NULL),
(407, 'Business', 'cr', 38, 'DC37', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 167, 1, 2, '2024-09-12', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:09:10', NULL, NULL),
(408, 'Business', 'cr', 38, 'DC37', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 168, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'dfgdb dfdfgh ffd fghbb klav qrhfgq;o rw.qlkjf o\';,rfwqrthqr;o\'iascn,.amhgfdq;o3 v qlrjt;qor3i vrutjLKFRHEG C Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:09:18', NULL, NULL),
(409, 'Business', 'dr', 38, 'DC37', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 169, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:09:42', NULL, NULL),
(410, 'Business', 'dr', 38, 'DC37', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 170, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:10:01', NULL, NULL),
(411, 'Bank', 'dr', 39, 'DC38', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 137, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 26, '2024-09-13', NULL, 'admin', 1, '2024-09-06 19:10:29', NULL, NULL),
(412, 'Business', 'dr', 39, 'DC38', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 171, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:10:38', NULL, NULL),
(413, 'Bank', 'dr', 39, 'DC38', 500000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 138, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 26, '2024-09-20', NULL, 'admin', 1, '2024-09-06 19:10:50', NULL, NULL),
(414, 'Bill', 'dr', 39, 'DC38', 458750, 1, 125000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 53, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED Qty:125000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:11:08', NULL, NULL),
(415, 'Bill', 'dr', 39, 'DC38', 1284500, 1, 350000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 54, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED Qty:350000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:11:24', NULL, NULL),
(416, 'Bill', 'dr', 39, 'DC38', 4404000, 1, 1200000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 55, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED Qty:1200000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:11:40', NULL, NULL),
(417, 'Cash', 'cr', 39, 'DC38', 4404000, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 52, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 19:11:55', NULL, NULL),
(418, 'Cash', 'cr', 39, 'DC38', 4404000, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 53, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 19:11:56', NULL, NULL),
(419, 'Cash', 'cr', 39, 'DC38', 575000, 1, 25000, 23, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 54, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED Qty:25000 PerPrice:23', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 19:12:19', NULL, NULL),
(420, 'Bill', 'cr', 39, 'DC38', 917500, 1, 250000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 56, 1, 6, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED Qty:250000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:12:38', NULL, NULL),
(421, 'Bank', 'dr', 40, 'DC39', 917500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 139, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 26, '2024-09-10', NULL, 'admin', 1, '2024-09-06 19:12:53', NULL, NULL),
(422, 'Bank', 'dr', 40, 'DC39', 917500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 140, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 19:13:06', NULL, NULL),
(423, 'Bill', 'dr', 40, 'DC39', 917500, 1, 250000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 57, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED Qty:250000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:13:25', NULL, NULL),
(424, 'Bill', 'cr', 40, 'DC39', 912500, 1, 250000, 3.65, '*', 'AED', '', '', NULL, 0, 1, 58, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED Qty:250000 PerPrice:3.65', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:13:43', NULL, NULL),
(425, 'Bill', 'dr', 40, 'DC39', 18421.05, 1, 350000, 19, '/', 'AED', '', '', NULL, 0, 1, 59, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED Qty:350000 PerPrice:19', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:14:04', NULL, NULL),
(426, 'Cash', 'cr', 40, 'DC39', 18421.05, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 55, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 19:14:17', NULL, NULL),
(427, 'Bank', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 141, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:14:30', NULL, NULL),
(428, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 172, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:14:36', NULL, NULL),
(429, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 173, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:14:40', NULL, NULL),
(430, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 174, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:14:45', NULL, NULL),
(431, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 175, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:14:50', NULL, NULL),
(432, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 176, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:14:54', NULL, NULL),
(433, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 177, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:14:59', NULL, NULL),
(434, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 178, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:15:03', NULL, NULL),
(435, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 179, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:15:08', NULL, NULL),
(436, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 180, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:15:12', NULL, NULL),
(437, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 181, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:15:18', NULL, NULL),
(438, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 182, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:15:23', NULL, NULL),
(439, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 183, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:15:29', NULL, NULL),
(440, 'Business', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 184, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:15:35', NULL, NULL),
(441, 'Bank', 'cr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 142, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 27, '2024-09-28', NULL, 'admin', 1, '2024-09-06 19:16:16', NULL, NULL),
(442, 'Bank', 'dr', 41, 'DC40', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 143, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:16:33', NULL, NULL),
(443, 'Bill', 'dr', 41, 'DC40', 1250000, 1, 1250000, 1, '*', 'AED', '', '', NULL, 0, 1, 60, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED Qty:1250000 PerPrice:1', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:16:49', NULL, NULL),
(444, 'Business', 'cr', 41, 'DC40', 1250000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 185, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:16:57', NULL, NULL),
(445, 'Bill', 'dr', 41, 'DC40', 5897169002.9, 1, 2563986523, 2.3, '*', 'AED', '', '', NULL, 0, 1, 61, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED Qty:2563986523 PerPrice:2.30', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:17:17', NULL, NULL),
(446, 'Cash', 'dr', 41, 'DC40', 5897169002.9, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 56, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', 'r_uploads/89jhxh1q.png', 'admin', 1, '2024-09-06 19:17:32', NULL, NULL),
(447, 'Business', 'dr', 41, 'DC40', 5897169002.9, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 186, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:17:39', NULL, NULL),
(448, 'Business', 'dr', 41, 'DC40', 5897169002.9, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 187, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:17:44', NULL, NULL),
(449, 'Business', 'dr', 41, 'DC40', 5897169002.9, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 188, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:17:49', NULL, NULL),
(450, 'Business', 'dr', 52, 'db50', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 189, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:19:02', NULL, NULL),
(451, 'Bank', 'dr', 52, 'db50', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 144, 1, 2, '2024-09-12', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:19:17', NULL, NULL),
(452, 'Business', 'dr', 52, 'db50', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 190, 1, 2, '2024-09-13', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:19:25', NULL, NULL),
(453, 'Business', 'dr', 52, 'db50', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 191, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:19:31', NULL, NULL),
(454, 'Business', 'dr', 52, 'db50', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 192, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:19:37', NULL, NULL),
(455, 'Business', 'dr', 52, 'db50', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 193, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:19:43', NULL, NULL),
(456, 'Business', 'cr', 52, 'db50', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 194, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:19:49', NULL, NULL),
(457, 'Business', 'cr', 52, 'db50', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 195, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:19:55', NULL, NULL),
(458, 'Business', 'dr', 52, 'db50', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 196, 1, 2, '2024-09-06', 'AS1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:20:00', NULL, NULL),
(459, 'Business', 'dr', 52, 'db50', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 197, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:20:09', NULL, NULL),
(460, 'Business', 'dr', 53, 'db51', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 198, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:20:18', NULL, NULL),
(461, 'Business', 'dr', 53, 'db51', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 199, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:20:23', NULL, NULL),
(462, 'Business', 'dr', 53, 'db51', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 200, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:20:28', NULL, NULL),
(463, 'Business', 'dr', 53, 'db51', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 201, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:20:35', NULL, NULL),
(464, 'Business', 'dr', 53, 'db51', 18421.05, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 202, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:20:40', NULL, NULL),
(465, 'Bill', 'dr', 53, 'db51', 6250000, 1, 12500000, 2, '/', 'AED', '', '', NULL, 0, 1, 62, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'bv  vmfm  bvjhg ,mnyliuymbilkyl kjyloiuh bjjojjjjjjjjjjjjjjjjjjjjjjjjkiyhhgfjhigg;uvvjbfli7uygjfjhgfjhgjhfghfjgfjhffjhjhjff,hgfhjhjfjf,f Currency:AED Qty:12500000 PerPrice:2', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:20:55', NULL, NULL),
(466, 'Business', 'dr', 53, 'db51', 6250000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 203, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:21:22', NULL, NULL),
(467, 'Cash', 'dr', 53, 'db51', 6250000, 0, 0, 0, '*', 'AED', 'ASMATULLAH ABDULLAH', '1544816664', NULL, 0, 1, 57, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:21:34', NULL, NULL),
(468, 'Cash', 'cr', 53, 'db51', 917500, 1, 250000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 58, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED Qty:250000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:21:53', NULL, NULL),
(469, 'Business', 'dr', 53, 'db51', 917500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 204, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:21:58', NULL, NULL),
(470, 'Business', 'dr', 54, 'db52', 917500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 205, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:22:08', NULL, NULL),
(471, 'Bank', 'dr', 54, 'db52', 917500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 145, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:22:17', NULL, NULL),
(472, 'Bank', 'dr', 54, 'db52', 917500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 146, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 27, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:22:25', NULL, NULL),
(473, 'Bank', 'dr', 54, 'db52', 917500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 147, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 27, '2024-09-12', NULL, 'admin', 1, '2024-09-06 19:22:39', NULL, NULL),
(474, 'Bank', 'cr', 54, 'db52', 917500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 148, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 19:22:56', NULL, NULL),
(475, 'Business', 'dr', 54, 'db52', 917500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 206, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:23:02', NULL, NULL),
(476, 'Bank', 'dr', 54, 'db52', 917500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 149, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 26, '2024-09-12', NULL, 'admin', 1, '2024-09-06 19:23:17', NULL, NULL),
(477, 'Cash', 'cr', 54, 'db52', 917500, 0, 0, 0, '*', 'AED', 'ASMATULLAH', '+54 544816664', NULL, 0, 1, 59, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:23:28', NULL, NULL),
(478, 'Business', 'dr', 55, 'db53', 917500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 207, 1, 7, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:23:36', NULL, NULL),
(479, 'Business', 'dr', 55, 'db53', 917500, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 208, 1, 7, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:23:41', NULL, NULL),
(480, 'Bill', 'dr', 55, 'db53', 458750, 1, 125000, 3.67, '*', 'AFN', '', '', NULL, 0, 1, 63, 1, 7, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AFN Qty:125000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:23:59', NULL, NULL),
(481, 'Bill', 'dr', 55, 'db53', 40201600, 1, 1256300, 32, '*', 'AED', '', '', NULL, 0, 1, 64, 1, 7, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED Qty:1256300 PerPrice:32', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:24:15', NULL, NULL),
(482, 'Bank', 'dr', 55, 'db53', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 150, 1, 7, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 26, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:24:24', NULL, NULL),
(483, 'Business', 'dr', 56, 'db54', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 209, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:25:09', NULL, NULL),
(484, 'Business', 'dr', 56, 'db54', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 210, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:25:15', NULL, NULL),
(485, 'Business', 'dr', 56, 'db54', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 211, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:25:24', NULL, NULL),
(486, 'Business', 'dr', 56, 'db54', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 212, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:25:29', NULL, NULL),
(487, 'Business', 'dr', 57, 'db55', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 213, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:25:36', NULL, NULL),
(488, 'Business', 'dr', 57, 'db55', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 214, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:25:41', NULL, NULL),
(489, 'Business', 'dr', 57, 'db55', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 215, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:25:46', NULL, NULL),
(490, 'Business', 'dr', 57, 'db55', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 216, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:25:50', NULL, NULL),
(491, 'Business', 'dr', 58, 'db56', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 217, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:25:58', NULL, NULL),
(492, 'Business', 'dr', 58, 'db56', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 218, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:26:02', NULL, NULL),
(493, 'Business', 'dr', 58, 'db56', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 219, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:26:07', NULL, NULL),
(494, 'Business', 'dr', 58, 'db56', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 220, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:26:13', NULL, NULL),
(495, 'Business', 'cr', 58, 'db56', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 221, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:26:18', NULL, NULL),
(496, 'Business', 'dr', 58, 'db56', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 222, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:26:23', NULL, NULL),
(497, 'Business', 'cr', 58, 'db56', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 223, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:26:29', NULL, NULL),
(498, 'Business', 'cr', 58, 'db56', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 224, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:26:36', NULL, NULL),
(499, 'Business', 'cr', 58, 'db56', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 225, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:26:43', NULL, NULL),
(500, 'Business', 'cr', 58, 'db56', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 226, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:26:49', NULL, NULL),
(501, 'Business', 'dr', 60, 'db57', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 227, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:27:00', NULL, NULL),
(502, 'Business', 'dr', 60, 'db57', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 228, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:27:05', NULL, NULL),
(503, 'Business', 'cr', 60, 'db57', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 229, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:27:11', NULL, NULL),
(504, 'Business', 'cr', 60, 'db57', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 230, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:27:17', NULL, NULL),
(505, 'Business', 'cr', 60, 'db57', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 231, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:27:24', NULL, NULL),
(506, 'Business', 'cr', 60, 'db57', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 232, 1, 1, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:27:31', NULL, NULL),
(507, 'Business', 'cr', 5, 'dc5', 40201600, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 234, 1, 2, '2024-09-06', 'as1', 'cheque', '1', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:27:37', '2024-09-06 19:28:07', 1),
(508, 'Business', 'dr', 5, 'dc5', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 234, 1, 2, '2024-09-06', 'as1', 'cheque', '000', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:28:22', NULL, NULL),
(509, 'Business', 'dr', 5, 'dc5', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 235, 1, 2, '2024-09-06', 'as1', 'cheque', '000', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:28:26', NULL, NULL),
(510, 'Business', 'dr', 4, 'DU4', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 236, 1, 4, '2024-09-06', 'as1', 'cheque', '000', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:28:44', NULL, NULL),
(511, 'Business', 'dr', 4, 'DU4', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 237, 1, 4, '2024-09-06', 'as1', 'cheque', '000', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:28:48', NULL, NULL),
(512, 'Business', 'dr', 3, 'DC3', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 239, 1, 2, '2024-09-06', 'as1', 'cheque', '000', ')  عصمت اللب نے نجیب اللہ   کو وصول کران  59 لاکھہ سے 60 لاکھہ درام  Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:28:52', '2024-09-06 19:29:05', 1);
INSERT INTO `roznamchaas` (`r_id`, `r_type`, `dr_cr`, `khaata_id`, `khaata_no`, `amount`, `is_qty`, `qty`, `per_price`, `operator`, `currency`, `c_name`, `mobile`, `transfered_from`, `transfered_from_id`, `khaata_branch_id`, `branch_serial`, `branch_id`, `cat_id`, `r_date`, `roznamcha_no`, `r_name`, `r_no`, `details`, `bank_id`, `r_date_payment`, `img`, `username`, `user_id`, `created_at`, `updated_at`, `updated_by`) VALUES
(513, 'Business', 'dr', 4, 'Du4', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 239, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:29:57', NULL, NULL),
(514, 'Business', 'dr', 4, 'Du4', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 240, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:30:02', NULL, NULL),
(515, 'Business', 'dr', 4, 'Du4', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 241, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:30:06', NULL, NULL),
(516, 'Business', 'dr', 4, 'Du4', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 242, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:30:11', NULL, NULL),
(517, 'Business', 'dr', 4, 'Du4', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 243, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:30:16', NULL, NULL),
(518, 'Business', 'dr', 4, 'Du4', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 244, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:30:20', NULL, NULL),
(519, 'Business', 'dr', 2, 'Du2', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 246, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:30:25', '2024-09-06 19:30:38', 1),
(520, 'Business', 'dr', 2, 'Du2', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 246, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:31:04', NULL, NULL),
(521, 'Business', 'dr', 2, 'Du2', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 247, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:31:08', NULL, NULL),
(522, 'Business', 'dr', 2, 'Du2', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 248, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:31:13', NULL, NULL),
(523, 'Business', 'dr', 2, 'Du2', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 249, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:31:18', NULL, NULL),
(524, 'Bank', 'dr', 2, 'Du2', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 151, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:31:28', NULL, NULL),
(525, 'Business', 'dr', 2, 'DA16', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 251, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'kjkjkhhl khjk hjkhjhhhhhhhhhhhhhhhhhhhhhhhhhhh Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:31:33', '2024-09-06 19:31:56', 1),
(526, 'Business', 'dr', 16, 'Da16', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 251, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:33:13', NULL, NULL),
(527, 'Business', 'cr', 16, 'Da16', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 252, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:33:20', NULL, NULL),
(528, 'Bill', 'dr', 16, 'Da16', 928510, 1, 253000, 3.67, '*', 'AED', '', '', NULL, 0, 1, 65, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED Qty:253000 PerPrice:3.67', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:33:35', NULL, NULL),
(529, 'Business', 'dr', 8, 'DP8', 928510, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 253, 1, 10, '2024-09-06', 'as1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:34:02', NULL, NULL),
(530, 'Business', 'dr', 1, 'dc1', 45750, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 29, 1, 254, 1, 2, '2024-09-06', '29', ' Exchange', '29', 'Cr. A/c:dc6 USD 12500 3.66 AED 45750', NULL, NULL, NULL, 'admin', 1, '2024-09-06 19:38:42', NULL, NULL),
(531, 'Business', 'cr', 6, 'dc6', 45750, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 29, 1, 255, 1, 2, '2024-09-06', '29', ' Exchange', '29', 'Dr. A/c:dc1 USD 12500 3.66 AED 45750', NULL, NULL, NULL, 'admin', 1, '2024-09-06 19:38:42', NULL, NULL),
(532, 'Business', 'dr', 1, 'dc1', 450180, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 30, 1, 256, 1, 2, '2024-09-06', '30', ' Exchange', '30', 'Cr. A/c:dc6 USD 123000 3.66 INR 450180', NULL, NULL, NULL, 'admin', 1, '2024-09-06 19:39:06', NULL, NULL),
(533, 'Business', 'cr', 6, 'dc6', 450180, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 30, 1, 257, 1, 2, '2024-09-06', '30', ' Exchange', '30', 'Dr. A/c:dc1 USD 123000 3.66 INR 450180', NULL, NULL, NULL, 'admin', 1, '2024-09-06 19:39:06', NULL, NULL),
(534, 'Business', 'dr', 1, 'dc1', 91500, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 31, 1, 258, 1, 2, '2024-09-06', '31', ' Exchange', '31', 'Cr. A/c:dc6 AED 25000 3.66 AED 91500', NULL, NULL, NULL, 'admin', 1, '2024-09-06 19:39:24', NULL, NULL),
(535, 'Business', 'cr', 6, 'dc6', 91500, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 31, 1, 259, 1, 2, '2024-09-06', '31', ' Exchange', '31', 'Dr. A/c:dc1 AED 25000 3.66 AED 91500', NULL, NULL, NULL, 'admin', 1, '2024-09-06 19:39:24', NULL, NULL),
(536, 'Business', 'dr', 1, 'dc1', 844100, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 41, 1, 260, 1, 2, '2024-09-06', '41', ' Exchange', '41', 'Cr. A/c:dc6 AED 230000 3.67 PKR 844100', NULL, NULL, NULL, 'admin', 1, '2024-09-06 19:43:36', NULL, NULL),
(537, 'Business', 'cr', 6, 'dc6', 844100, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 41, 1, 261, 1, 2, '2024-09-06', '41', ' Exchange', '41', 'Dr. A/c:dc1 AED 230000 3.67 PKR 844100', NULL, NULL, NULL, 'admin', 1, '2024-09-06 19:43:36', NULL, NULL),
(538, 'Business', 'dr', 1, 'dc1', 921600, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 43, 1, 262, 1, 2, '2024-09-06', '43', ' Exchange', '43', 'Cr. A/c:dc6 AED 256000 3.6 USD 921600', NULL, NULL, NULL, 'admin', 1, '2024-09-06 19:44:48', NULL, NULL),
(539, 'Business', 'cr', 6, 'dc6', 921600, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 43, 1, 263, 1, 2, '2024-09-06', '43', ' Exchange', '43', 'Dr. A/c:dc1 AED 256000 3.6 USD 921600', NULL, NULL, NULL, 'admin', 1, '2024-09-06 19:44:48', NULL, NULL),
(540, 'Business', 'dr', 1, 'dc1', 917500, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 44, 1, 264, 1, 2, '2024-09-06', '44', ' Exchange', '44', 'Cr. A/c:dc6 AED 250000 3.67 INR 917500', NULL, NULL, NULL, 'admin', 1, '2024-09-06 19:45:24', NULL, NULL),
(541, 'Business', 'cr', 6, 'dc6', 917500, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 44, 1, 265, 1, 2, '2024-09-06', '44', ' Exchange', '44', 'Dr. A/c:dc1 AED 250000 3.67 INR 917500', NULL, NULL, NULL, 'admin', 1, '2024-09-06 19:45:24', NULL, NULL),
(542, 'Business', 'dr', 2, 'du2', 928510, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 267, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'Asmatullah transferred Najibullah\'s total account from old account to new account.2023YEAR OLD RECORD Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:50:57', '2024-09-06 19:51:06', 1),
(543, 'Business', 'dr', 3, 'dc3', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 267, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:51:36', NULL, NULL),
(544, 'Business', 'dr', 4, 'du4', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 268, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:51:51', NULL, NULL),
(545, 'Business', 'dr', 5, 'dc5', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 269, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:52:03', NULL, NULL),
(546, 'Business', 'dr', 6, 'dc6', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 270, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:52:18', NULL, NULL),
(547, 'Business', 'dr', 7, 'dc7', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 271, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:52:25', NULL, NULL),
(548, 'Business', 'cr', 7, 'dc7', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 272, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:52:42', NULL, NULL),
(549, 'Business', 'dr', 8, 'dp8', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 273, 1, 10, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:52:53', NULL, NULL),
(550, 'Business', 'dr', 9, 'du9', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 274, 1, 10, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:53:08', NULL, NULL),
(551, 'Business', 'dr', 10, 'du10', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 275, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:53:24', NULL, NULL),
(552, 'Business', 'dr', 11, 'du11', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 276, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:53:32', NULL, NULL),
(553, 'Business', 'dr', 12, 'du12', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 277, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:53:43', NULL, NULL),
(554, 'Business', 'dr', 13, 'du13', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 278, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:53:55', NULL, NULL),
(555, 'Business', 'dr', 12, 'du12', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 279, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:54:09', NULL, NULL),
(556, 'Business', 'dr', 13, 'du13', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 280, 1, 4, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:54:19', NULL, NULL),
(557, 'Business', 'dr', 14, 'dc14', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 281, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:54:29', NULL, NULL),
(558, 'Business', 'dr', 15, 'dc15', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 282, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:54:36', NULL, NULL),
(559, 'Business', 'cr', 16, 'da16', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 283, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:54:53', NULL, NULL),
(560, 'Business', 'dr', 17, 'dc17', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 284, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:55:07', NULL, NULL),
(561, 'Business', 'dr', 18, 'dc18', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 285, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:55:21', NULL, NULL),
(562, 'Business', 'dr', 19, 'dc19', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 286, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:55:30', NULL, NULL),
(563, 'Business', 'dr', 20, 'dc20', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 287, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:55:38', NULL, NULL),
(564, 'Business', 'dr', 21, 'dc21', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 288, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:55:46', NULL, NULL),
(565, 'Business', 'dr', 22, 'dc22', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 289, 1, 6, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:55:54', NULL, NULL),
(566, 'Business', 'dr', 23, 'dg23', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 290, 1, 6, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:56:09', NULL, NULL),
(567, 'Business', 'dr', 24, 'dc24', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 291, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:56:27', NULL, NULL),
(568, 'Business', 'dr', 25, 'dc25', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 292, 1, 2, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:56:41', NULL, NULL),
(569, 'Business', 'dr', 47, 'db36', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 293, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:58:08', NULL, NULL),
(570, 'Business', 'dr', 48, 'db37', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 294, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:58:15', NULL, NULL),
(571, 'Business', 'dr', 61, 'db58', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 295, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:58:25', NULL, NULL),
(572, 'Business', 'dr', 42, 'db31', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 296, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:58:49', NULL, NULL),
(573, 'Business', 'dr', 50, 'db39', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 297, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:59:03', NULL, NULL),
(574, 'Business', 'cr', 51, 'db40', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 298, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 19:59:15', NULL, NULL),
(575, 'Business', 'dr', 28, 'db30', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 299, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:00:10', NULL, NULL),
(576, 'Business', 'dr', 42, 'db31', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 300, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:00:18', NULL, NULL),
(577, 'Business', 'dr', 43, 'db32', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 301, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:00:25', NULL, NULL),
(578, 'Business', 'dr', 44, 'db33', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 302, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:00:34', NULL, NULL),
(579, 'Business', 'dr', 45, 'db34', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 303, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:00:42', NULL, NULL),
(580, 'Business', 'dr', 46, 'db35', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 304, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:00:49', NULL, NULL),
(581, 'Business', 'dr', 47, 'db36', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 305, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:00:57', NULL, NULL),
(582, 'Business', 'dr', 54, 'db52', 1200000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 307, 1, 1, '2024-09-06', 'as1', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED Currency:AED Qty:0 PerPrice:0', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:01:04', '2024-09-06 20:01:40', 1),
(583, 'Business', 'dr', 54, 'db52', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 307, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:01:53', NULL, NULL),
(584, 'Business', 'dr', 55, 'db53', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 308, 1, 7, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:02:00', NULL, NULL),
(585, 'Business', 'dr', 56, 'db54', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 309, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:02:08', NULL, NULL),
(586, 'Business', 'dr', 57, 'db55', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 310, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:02:15', NULL, NULL),
(587, 'Business', 'dr', 58, 'db56', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 311, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:02:23', NULL, NULL),
(588, 'Business', 'dr', 60, 'db57', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 312, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:02:35', NULL, NULL),
(589, 'Business', 'dr', 61, 'db58', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 313, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:02:42', NULL, NULL),
(590, 'Business', 'dr', 62, 'db59', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 314, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:02:50', NULL, NULL),
(591, 'Business', 'dr', 63, 'db60', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 315, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:03:12', NULL, NULL),
(592, 'Business', 'dr', 63, 'db60', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 316, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:03:18', NULL, NULL),
(593, 'Business', 'dr', 27, 'db2', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 317, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:04:00', NULL, NULL),
(594, 'Business', 'dr', 28, 'db3', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 318, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:04:07', NULL, NULL),
(595, 'Business', 'dr', 65, 'db62', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 319, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:04:17', NULL, NULL),
(596, 'Business', 'dr', 64, 'db61', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 320, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:04:26', NULL, NULL),
(597, 'Business', 'dr', 64, 'db61', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 321, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:04:26', NULL, NULL),
(598, 'Business', 'dr', 66, 'db63', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 322, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:04:34', NULL, NULL),
(599, 'Business', 'dr', 66, 'db63', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 323, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:04:34', NULL, NULL),
(600, 'Business', 'dr', 67, 'db64', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 324, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:04:46', NULL, NULL),
(601, 'Business', 'dr', 68, 'db65', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 325, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:04:53', NULL, NULL),
(602, 'Business', 'dr', 69, 'db66', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 326, 1, 7, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:05:01', NULL, NULL),
(603, 'Business', 'dr', 63, 'db60', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 327, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:05:37', NULL, NULL),
(604, 'Business', 'dr', 63, 'db60', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 328, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:05:37', NULL, NULL),
(605, 'Business', 'dr', 40, 'dc39', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 329, 1, 2, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:05:51', NULL, NULL),
(606, 'Business', 'dr', 41, 'dc40', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 330, 1, 2, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:05:59', NULL, NULL),
(607, 'Business', 'dr', 70, 'db67', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 331, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:06:24', NULL, NULL),
(608, 'Business', 'dr', 37, 'dc88', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 332, 1, 2, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:07:13', NULL, NULL),
(609, 'Business', 'dr', 71, 'db68', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 333, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:08:03', NULL, NULL),
(610, 'Business', 'dr', 14, 'dc14', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 334, 1, 2, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:08:17', NULL, NULL),
(611, 'Business', 'dr', 14, 'dc14', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 335, 1, 2, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:08:17', NULL, NULL),
(612, 'Business', 'dr', 72, 'db69', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 336, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:09:32', NULL, NULL),
(613, 'Business', 'cr', 72, 'db69', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 337, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:09:39', NULL, NULL),
(614, 'Business', 'cr', 72, 'db69', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 338, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:09:45', NULL, NULL),
(615, 'Business', 'cr', 72, 'db69', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 339, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:09:51', NULL, NULL),
(616, 'Business', 'cr', 72, 'db69', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 340, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:09:57', NULL, NULL),
(617, 'Business', 'dr', 72, 'db69', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 341, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:10:02', NULL, NULL),
(618, 'Business', 'dr', 73, 'db70', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 342, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:10:12', NULL, NULL),
(619, 'Business', 'cr', 73, 'db70', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 343, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:10:17', NULL, NULL),
(620, 'Business', 'dr', 73, 'db70', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 344, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:10:25', NULL, NULL),
(621, 'Business', 'cr', 73, 'db70', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 345, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:10:32', NULL, NULL),
(622, 'Business', 'cr', 73, 'db70', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 346, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:10:38', NULL, NULL),
(623, 'Business', 'cr', 73, 'db70', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 347, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:10:44', NULL, NULL),
(624, 'Business', 'cr', 73, 'db70', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 348, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:10:50', NULL, NULL),
(625, 'Business', 'cr', 73, 'db70', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 349, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:10:55', NULL, NULL),
(626, 'Business', 'cr', 73, 'db70', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 350, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:11:01', NULL, NULL),
(627, 'Business', 'dr', 19, 'dc19', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 351, 1, 2, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:11:19', NULL, NULL),
(628, 'Business', 'dr', 73, 'db70', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 352, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:11:51', NULL, NULL),
(629, 'Business', 'dr', 73, 'db70', 22720.5, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 353, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:11:57', NULL, NULL),
(630, 'Business', 'cr', 73, 'db70', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 354, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:12:07', NULL, NULL),
(631, 'Business', 'cr', 74, 'db71', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 355, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:12:16', NULL, NULL),
(632, 'Business', 'cr', 74, 'db71', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 356, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:12:23', NULL, NULL),
(633, 'Business', 'cr', 74, 'db71', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 357, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:12:28', NULL, NULL),
(634, 'Business', 'cr', 75, 'db72', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 358, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:12:37', NULL, NULL),
(635, 'Business', 'cr', 75, 'db72', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 359, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:12:43', NULL, NULL),
(636, 'Business', 'cr', 75, 'db72', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 360, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:12:49', NULL, NULL),
(637, 'Business', 'dr', 75, 'db72', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 361, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:12:57', NULL, NULL),
(638, 'Business', 'dr', 76, 'db73', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 362, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:13:05', NULL, NULL),
(639, 'Business', 'cr', 76, 'db73', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 363, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:13:11', NULL, NULL),
(640, 'Business', 'cr', 76, 'db73', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 364, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:13:16', NULL, NULL),
(641, 'Business', 'cr', 76, 'db73', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 365, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:13:21', NULL, NULL),
(642, 'Business', 'dr', 77, 'db74', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 366, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:13:29', NULL, NULL),
(643, 'Business', 'cr', 77, 'db74', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 367, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:13:35', NULL, NULL),
(644, 'Business', 'cr', 77, 'db74', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 368, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:13:40', NULL, NULL),
(645, 'Business', 'cr', 77, 'db74', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 369, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:13:49', NULL, NULL),
(646, 'Business', 'cr', 77, 'db74', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 370, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:13:54', NULL, NULL),
(647, 'Business', 'cr', 77, 'db74', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 371, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:14:03', NULL, NULL),
(648, 'Business', 'cr', 77, 'db74', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 372, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:14:09', NULL, NULL),
(649, 'Business', 'dr', 78, 'db75', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 373, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:14:17', NULL, NULL),
(650, 'Business', 'cr', 78, 'db75', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 374, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:14:23', NULL, NULL),
(651, 'Business', 'dr', 78, 'db75', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 375, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:14:27', NULL, NULL),
(652, 'Business', 'dr', 79, 'db76', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 376, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:14:34', NULL, NULL),
(653, 'Business', 'dr', 79, 'db76', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 377, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:14:40', NULL, NULL),
(654, 'Business', 'dr', 79, 'db76', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 378, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:14:45', NULL, NULL),
(655, 'Business', 'dr', 79, 'db76', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 379, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:14:51', NULL, NULL),
(656, 'Business', 'dr', 79, 'db76', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 380, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:14:56', NULL, NULL),
(657, 'Business', 'dr', 79, 'db76', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 381, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:15:00', NULL, NULL),
(658, 'Business', 'dr', 80, 'db77', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 382, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:15:08', NULL, NULL),
(659, 'Business', 'dr', 80, 'db77', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 383, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:15:14', NULL, NULL),
(660, 'Business', 'dr', 80, 'db77', 5000000, 0, 0, 0, '*', 'AED', '', '', NULL, 0, 1, 384, 1, 1, '2024-09-06', 'as', 'cheque', '1', 'INVESTMENT ACOUNTs to transferred  me Total alculation   Currency:AED', 0, '2024-09-06', NULL, 'admin', 1, '2024-09-06 20:15:18', NULL, NULL),
(661, 'Business', 'dr', 73, 'db70', 9000000, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 53, 1, 385, 1, 1, '2024-09-06', '53', ' Exchange', '53', 'Cr. A/c:db75 AED 250000 36 USD 9000000', NULL, NULL, NULL, 'admin', 1, '2024-09-06 20:21:56', NULL, NULL),
(662, 'Business', 'cr', 78, 'db75', 9000000, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 53, 1, 386, 1, 1, '2024-09-06', '53', ' Exchange', '53', 'Dr. A/c:db70 AED 250000 36 USD 9000000', NULL, NULL, NULL, 'admin', 1, '2024-09-06 20:21:56', NULL, NULL),
(663, 'Business', 'dr', 72, 'db69', 458750, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 54, 1, 387, 1, 1, '2024-09-06', '54', ' Exchange', '54', 'Cr. A/c:db68 AED 250000 3.67 USD 917500', NULL, NULL, NULL, 'admin', 1, '2024-09-06 20:22:54', NULL, NULL),
(664, 'Business', 'cr', 71, 'db68', 458750, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 54, 1, 388, 1, 1, '2024-09-06', '54', ' Exchange', '54', 'Dr. A/c:db69 AED 250000 3.67 USD 917500', NULL, NULL, NULL, 'admin', 1, '2024-09-06 20:22:54', NULL, NULL),
(665, 'Business', 'dr', 72, 'db69', 24875621890.54, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 56, 1, 389, 1, 1, '2024-09-06', '56', ' Exchange', '56', 'Cr. A/c:db68 INR 2500000000000 201 PKR 12437810945.27', NULL, NULL, NULL, 'admin', 1, '2024-09-06 20:23:34', NULL, NULL),
(666, 'Business', 'cr', 71, 'db68', 24875621890.54, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 56, 1, 390, 1, 1, '2024-09-06', '56', ' Exchange', '56', 'Dr. A/c:db69 INR 2500000000000 201 PKR 12437810945.27', NULL, NULL, NULL, 'admin', 1, '2024-09-06 20:23:34', NULL, NULL),
(667, 'Business', 'dr', 1, 'dc1', 21739130.43478261, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 55, 1, 391, 1, 2, '2024-09-06', '55', ' Exchange', '55', 'Cr. A/c:db1 AED 1000000000 2 USD 500000000', NULL, NULL, NULL, 'admin', 1, '2024-09-06 20:24:45', NULL, NULL),
(668, 'Business', 'cr', 26, 'db1', 21739130.43478261, 0, NULL, NULL, '\'*\'', NULL, NULL, NULL, 'exchange', 55, 1, 392, 1, 1, '2024-09-06', '55', ' Exchange', '55', 'Dr. A/c:dc1 AED 1000000000 2 USD 500000000', NULL, NULL, NULL, 'admin', 1, '2024-09-06 20:24:45', NULL, NULL),
(669, 'Cash', 'dr', 6, 'dc6', 110100, 1, 30000, 3.67, '*', 'USD', '', '', NULL, 0, 1, 61, 1, 2, '2024-09-14', 'AS1', 'receipts', '1', 'RECDEIVED against asmat contract @ 334   Currency:USD Qty:30000 PerPrice:3.67', 26, '2024-09-14', 'r_uploads/WhatsApp Image 2024-09-04 at 10.00.12 PM (1).jpeg', 'admin', 1, '2024-09-14 11:16:12', '2024-09-14 11:18:34', 1);

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
  `_date` date NOT NULL,
  `country` varchar(255) NOT NULL,
  `sea_road` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
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

INSERT INTO `transactions` (`id`, `p_s`, `sr`, `type`, `_date`, `country`, `sea_road`, `is_doc`, `locked`, `payments`, `active`, `branch_id`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'p', 0, 'booking', '2024-08-25', 'UAE', '{\"sea_road\":\"road\",\"l_country_road\":\"CHINA\",\"l_border_road\":\"ljkdf\",\"l_date_road\":\"2022-08-29\",\"truck_container\":\"container\",\"r_country_road\":\"RUSSIA\",\"r_border_road\":\"8923skld\",\"r_date_road\":\"2026-08-29\",\"d_date_road\":\"2026-10-30\",\"is_loading\":1,\"l_country\":\"UAE\",\"l_port\":\"2398\",\"l_date\":\"2024-08-29\",\"ctr_name\":\"ctrname\",\"is_receiving\":1,\"r_country\":\"paksd flas\",\"r_port\":\"jlsda8f\",\"r_date\":\"2026-09-29\",\"arrival_date\":\"2024-08-25\",\"report\":\"this is report\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"1\"}', 0, 0, NULL, 1, 1, '2024-08-25 14:56:53', 1, '2024-09-02 21:52:55', 1),
(2, 'p', 0, 'local', '2024-08-26', 'UAEdd', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-08-29\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-08-29\",\"d_date_road\":\"2024-08-29\",\"is_loading\":1,\"l_country\":\"UAE\",\"l_port\":\"21\",\"l_date\":\"2024-08-29\",\"ctr_name\":\"CTR\",\"is_receiving\":1,\"r_country\":\"PAKISTAN\",\"r_port\":\"93032\",\"r_date\":\"2024-08-29\",\"arrival_date\":\"2022-08-28\",\"indexes1\":[\"Office\"],\"vals1\":[\"234\"],\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"2\"}', 0, 0, NULL, 1, 3, '2024-08-25 15:31:04', 1, '2024-08-25 22:09:09', 1),
(3, 'p', 0, 'booking', '2024-08-27', 'pak', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"3\"}', 0, 0, NULL, 1, 1, '2024-08-27 23:43:48', 1, NULL, NULL),
(4, 'p', 0, 'booking', '2024-08-28', 'Pakistan', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"4\"}', 0, 0, NULL, 1, 1, '2024-08-28 09:58:03', 1, NULL, NULL),
(5, 'p', 0, 'booking', '2024-08-28', 'Aruba', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-08-31\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-08-31\",\"d_date_road\":\"2024-08-31\",\"is_loading\":1,\"l_country\":\"PAKISTAN\",\"l_port\":\"BANDAR\",\"l_date\":\"2024-08-31\",\"ctr_name\":\"8239\",\"is_receiving\":1,\"r_country\":\"CHINA\",\"r_port\":\"NSDFAKL\",\"r_date\":\"2006-07-29\",\"arrival_date\":\"2035-08-31\",\"report\":\"THIS IS SA REPORT OF P NO. 5\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"5\"}', 1, 0, NULL, 1, 1, '2024-08-28 10:08:11', 1, '2024-09-16 23:11:39', 1),
(6, 'p', 0, 'booking', '2024-08-28', 'Iran', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"6\"}', 0, 0, NULL, 1, 1, '2024-08-28 10:28:58', 1, NULL, NULL),
(7, 'p', 0, 'booking', '2024-08-28', 'United Arab Emirates', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"7\"}', 1, 0, NULL, 1, 1, '2024-08-28 16:57:39', 1, NULL, NULL),
(8, 'p', 0, 'booking', '2024-08-28', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"8\"}', 0, 0, NULL, 1, 1, '2024-08-28 17:07:15', 1, '2024-08-28 17:08:32', 1),
(9, 'p', 0, 'booking', '2024-08-28', 'United Arab Emirates', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"9\"}', 0, 0, NULL, 1, 1, '2024-08-28 17:12:09', 1, NULL, NULL),
(10, 'p', 0, 'booking', '2024-08-28', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"10\"}', 0, 0, NULL, 1, 1, '2024-08-28 17:15:31', 1, NULL, NULL),
(11, 'p', 0, 'booking', '2024-08-28', 'United Arab Emirates', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"11\"}', 0, 0, NULL, 1, 1, '2024-08-28 17:17:18', 1, NULL, NULL),
(12, 'p', 0, 'booking', '2024-08-28', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"12\"}', 0, 0, NULL, 1, 1, '2024-08-28 17:29:19', 1, NULL, NULL),
(13, 'p', 0, 'booking', '2024-08-28', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"13\"}', 0, 0, NULL, 1, 1, '2024-08-28 17:35:47', 1, NULL, NULL),
(14, 'p', 0, 'booking', '2024-08-28', 'Iran', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"14\"}', 0, 0, NULL, 1, 1, '2024-08-28 19:11:26', 1, NULL, NULL),
(15, 'p', 0, 'booking', '2024-08-28', 'Uzbekistan', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"15\"}', 0, 0, NULL, 1, 1, '2024-08-28 19:18:10', 1, NULL, NULL),
(16, 'p', 0, 'booking', '2024-08-28', 'Iran', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"16\"}', 0, 0, NULL, 1, 1, '2024-08-28 19:22:32', 1, NULL, NULL),
(17, 'p', 0, 'booking', '2024-08-28', 'Uzbekistan', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"17\"}', 0, 0, NULL, 1, 1, '2024-08-28 19:25:06', 1, NULL, NULL),
(18, 'p', 0, 'booking', '2024-08-28', 'El Salvador', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"18\"}', 0, 0, NULL, 1, 1, '2024-08-28 19:28:32', 1, NULL, NULL),
(19, 'p', 0, 'booking', '2024-08-28', 'Uzbekistan', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"19\"}', 0, 0, NULL, 1, 1, '2024-08-28 19:33:24', 1, NULL, NULL),
(20, 'p', 0, 'booking', '2024-08-29', 'Iran', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"20\"}', 0, 0, NULL, 1, 1, '2024-08-29 10:52:17', 1, NULL, NULL),
(21, 'p', 0, 'booking', '2024-09-05', 'United Arab Emirates', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-05\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-05\",\"d_date_road\":\"2024-09-05\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"CHIAN PORTSE\",\"l_date\":\"2024-09-05\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-05\",\"arrival_date\":\"2024-09-05\",\"report\":\"Through: Via:KABUL TO TORKHAM PAKISTAN IN TRANSIT C&F TO ATTARI INDIA INCLUDE OF ALL CHARGES\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"21\"}', 0, 0, NULL, 1, 1, '2024-09-05 16:57:44', 1, NULL, NULL),
(22, 'p', 0, 'booking', '2024-09-05', 'United Arab Emirates', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"22\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:10:44', 1, '2024-09-05 17:11:27', 1),
(23, 'p', 0, 'booking', '2024-09-05', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"23\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:12:32', 1, NULL, NULL),
(24, 'p', 0, 'booking', '2024-09-05', 'Iran', '{\"sea_road\":\"road\",\"l_country_road\":\"United Arab Emirates\",\"l_border_road\":\"mand\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"Iran\",\"r_border_road\":\"br\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"l_country\":\"\",\"l_port\":\"\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"\",\"r_country\":\"\",\"r_port\":\"\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"5 ctbas jahgs jasb\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"24\",\"is_loading\":0,\"is_receiving\":0}', 0, 0, NULL, 1, 1, '2024-09-05 17:13:42', 1, NULL, NULL),
(25, 'p', 0, 'booking', '2024-09-05', 'United Arab Emirates', '{\"sea_road\":\"road\",\"l_country_road\":\"United Arab Emirates\",\"l_border_road\":\"mand\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"Iran\",\"r_border_road\":\"br\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"l_country\":\"\",\"l_port\":\"\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"\",\"r_country\":\"\",\"r_port\":\"\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"5 ctbas jahgs jasb\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"25\",\"is_loading\":0,\"is_receiving\":0}', 0, 0, NULL, 1, 1, '2024-09-05 17:14:44', 1, '2024-09-05 17:16:25', 1),
(26, 'p', 0, 'booking', '2024-09-05', 'Afghanistan', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"26\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:17:30', 1, NULL, NULL),
(27, 'p', 0, 'booking', '2024-09-05', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"27\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:18:30', 1, '2024-09-05 17:19:15', 1),
(28, 'p', 0, 'booking', '2024-09-05', 'Iran', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"28\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:20:06', 1, NULL, NULL),
(29, 'p', 0, 'booking', '2024-09-05', 'Iran', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"29\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:23:54', 1, NULL, NULL),
(30, 'p', 0, 'booking', '2024-09-05', 'United Arab Emirates', NULL, 0, 0, NULL, 1, 1, '2024-09-05 17:24:34', 1, NULL, NULL),
(31, 'p', 0, 'booking', '2024-09-05', 'Iran', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"31\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:26:25', 1, NULL, NULL),
(32, 'p', 0, 'booking', '2024-09-05', 'Afghanistan', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"32\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:27:10', 1, NULL, NULL),
(33, 'p', 0, 'booking', '2024-09-05', 'Iran', '{\"sea_road\":\"road\",\"l_country_road\":\"United Arab Emirates\",\"l_border_road\":\"mand\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"Iran\",\"r_border_road\":\"br\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"l_country\":\"\",\"l_port\":\"\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"\",\"r_country\":\"\",\"r_port\":\"\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"5 ctbas jahgs jasb\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"33\",\"is_loading\":0,\"is_receiving\":0}', 0, 0, NULL, 1, 1, '2024-09-05 17:28:45', 1, NULL, NULL),
(34, 'p', 0, 'booking', '2024-09-05', 'Iran', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"34\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:29:27', 1, NULL, NULL),
(35, 'p', 0, 'booking', '2024-09-05', 'United Arab Emirates', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"35\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:30:08', 1, NULL, NULL),
(36, 'p', 0, 'booking', '2024-09-05', 'Iran', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"36\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:30:52', 1, NULL, NULL),
(37, 'p', 0, 'booking', '2024-09-05', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"37\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:31:20', 1, NULL, NULL),
(38, 'p', 0, 'booking', '2024-09-05', 'United Arab Emirates', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"38\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:31:54', 1, NULL, NULL),
(39, 'p', 0, 'booking', '2024-09-05', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"39\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:32:31', 1, NULL, NULL),
(40, 'p', 0, 'booking', '2024-09-05', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"40\"}', 0, 0, NULL, 1, 1, '2024-09-05 17:33:25', 1, NULL, NULL),
(41, 'p', 0, 'booking', '2024-09-05', 'United Arab Emirates', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"41\"}', 0, 0, NULL, 1, 1, '2024-09-05 18:18:03', 1, NULL, NULL),
(42, 'p', 0, 'booking', '2024-09-05', 'United Arab Emirates', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"42\"}', 0, 0, NULL, 1, 1, '2024-09-05 18:18:31', 1, NULL, NULL),
(43, 'p', 0, 'booking', '2024-09-05', 'United Arab Emirates', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"43\"}', 1, 0, NULL, 1, 1, '2024-09-05 18:19:07', 1, NULL, NULL),
(44, 'p', 0, 'booking', '2024-09-05', 'Afghanistan', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"44\"}', 1, 0, NULL, 1, 1, '2024-09-05 18:19:38', 1, NULL, NULL),
(45, 'p', 0, 'booking', '2024-09-05', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"45\"}', 0, 0, NULL, 1, 1, '2024-09-05 18:20:54', 1, NULL, NULL),
(46, 'p', 0, 'booking', '2024-09-05', 'Iran', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"46\"}', 0, 0, NULL, 1, 1, '2024-09-05 18:21:51', 1, NULL, NULL),
(47, 'p', 0, 'booking', '2024-09-05', 'India', '{\"sea_road\":\"road\",\"l_country_road\":\"United Arab Emirates\",\"l_border_road\":\"mand\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"Iran\",\"r_border_road\":\"br\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"l_country\":\"\",\"l_port\":\"\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"\",\"r_country\":\"\",\"r_port\":\"\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"5 ctbas jahgs jasb\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"47\",\"is_loading\":0,\"is_receiving\":0}', 1, 0, NULL, 1, 1, '2024-09-05 18:22:26', 1, NULL, NULL),
(48, 'p', 0, 'booking', '2024-09-05', 'Afghanistan', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"48\"}', 0, 0, NULL, 1, 1, '2024-09-05 18:22:57', 1, NULL, NULL),
(49, 'p', 0, 'booking', '2024-09-05', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"49\"}', 1, 0, NULL, 1, 1, '2024-09-05 18:23:37', 1, NULL, NULL),
(50, 'p', 0, 'booking', '2024-09-05', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"50\"}', 0, 0, NULL, 1, 1, '2024-09-05 18:24:06', 1, NULL, NULL),
(51, 'p', 0, 'booking', '2024-09-05', 'United Arab Emirates', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"51\"}', 0, 0, NULL, 1, 1, '2024-09-05 18:24:32', 1, NULL, NULL),
(52, 'p', 0, 'booking', '2024-09-05', 'India', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"52\"}', 0, 0, NULL, 1, 1, '2024-09-05 18:25:03', 1, NULL, NULL),
(53, 'p', 0, 'booking', '2024-09-05', 'Iran', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"United Arab Emirates\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"53\"}', 0, 0, NULL, 1, 1, '2024-09-05 18:25:35', 1, NULL, NULL),
(54, 'p', 0, 'booking', '2024-09-12', 'Afghanistan', '{\"sea_road\":\"road\",\"l_country_road\":\"United Arab Emirates\",\"l_border_road\":\"mand\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"Iran\",\"r_border_road\":\"br\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"l_country\":\"\",\"l_port\":\"\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"\",\"r_country\":\"\",\"r_port\":\"\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"5 ctbas jahgs jasb\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"54\",\"is_loading\":0,\"is_receiving\":0}', 1, 0, NULL, 1, 1, '2024-09-05 18:26:07', 1, NULL, NULL),
(55, 'p', 0, 'booking', '2024-09-05', 'United Arab Emirates', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"Pakistan\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"55\"}', 0, 0, NULL, 1, 1, '2024-09-05 18:26:37', 1, '2024-09-05 18:27:21', 1),
(56, 'p', 0, 'booking', '2024-09-05', 'Iran', '{\"sea_road\":\"road\",\"l_country_road\":\"kah\",\"l_border_road\":\"mand\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"Iran\",\"r_border_road\":\"br\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"l_country\":\"\",\"l_port\":\"\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"\",\"r_country\":\"\",\"r_port\":\"\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"5 ctbas jahgs jasb\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"56\",\"is_loading\":0,\"is_receiving\":0}', 1, 0, NULL, 1, 1, '2024-09-05 18:27:53', 1, NULL, NULL),
(57, 'p', 0, 'booking', '2024-09-05', 'Afghanistan', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"SHEKOU.SHENZEN\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"REPPAR\",\"is_receiving\":1,\"r_country\":\"Pakistan\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"ADKHF AK AHFDLIA AGYHPW9E AKFHGLAI AKDHFALUIDF LADHF\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"57\"}', 0, 0, '{\"full_advance\":\"advance\",\"pct_value\":\"16\",\"partial_date1\":\"2020-08-19\",\"partial_report1\":\"16% ki report yha asjdlf\",\"partial_date2\":\"2039-09-20\",\"partial_report2\":\"klsdj fjsdlfjsklad f\",\"p_total_amount\":\"31488.6\",\"partial_amount1\":\"5038.18\",\"partial_amount2\":\"26450.42\"}', 1, 1, '2024-09-05 18:28:17', 1, NULL, NULL),
(58, 'p', 0, 'booking', '2024-09-05', 'Iran', '{\"sea_road\":\"road\",\"l_country_road\":\"kah\",\"l_border_road\":\"mand\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"Iran\",\"r_border_road\":\"br\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"l_country\":\"\",\"l_port\":\"\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"\",\"r_country\":\"\",\"r_port\":\"\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"5 ctbas jahgs jasb\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"58\",\"is_loading\":0,\"is_receiving\":0}', 0, 0, NULL, 1, 1, '2024-09-05 18:29:47', 1, NULL, NULL),
(59, 'p', 0, 'booking', '2024-09-05', 'India', '{\"sea_road\":\"road\",\"l_country_road\":\"kah\",\"l_border_road\":\"mand\",\"l_date_road\":\"2024-09-09\",\"truck_container\":\"open_truck\",\"r_country_road\":\"Iran\",\"r_border_road\":\"br\",\"r_date_road\":\"2024-09-09\",\"d_date_road\":\"2024-09-09\",\"l_country\":\"\",\"l_port\":\"\",\"l_date\":\"2024-09-09\",\"ctr_name\":\"\",\"r_country\":\"\",\"r_port\":\"\",\"r_date\":\"2024-09-09\",\"arrival_date\":\"2024-09-09\",\"report\":\"5 ctbas jahgs jasb\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"59\",\"is_loading\":0,\"is_receiving\":0}', 1, 0, NULL, 1, 1, '2024-09-05 18:31:06', 1, '2024-09-05 18:31:46', 1),
(60, 'p', 0, 'booking', '2024-09-05', 'Afghanistan', '{\"sea_road\":\"sea\",\"l_country_road\":\"\",\"l_border_road\":\"\",\"l_date_road\":\"2024-09-05\",\"truck_container\":\"open_truck\",\"r_country_road\":\"\",\"r_border_road\":\"\",\"r_date_road\":\"2024-09-05\",\"d_date_road\":\"2024-09-05\",\"is_loading\":1,\"l_country\":\"United Arab Emirates\",\"l_port\":\"CHIAN PORTSE\",\"l_date\":\"2024-09-05\",\"ctr_name\":\"open truck \",\"is_receiving\":1,\"r_country\":\"China\",\"r_port\":\"JEBEL ALI UEA\",\"r_date\":\"2024-09-05\",\"arrival_date\":\"2024-09-05\",\"report\":\"Through: Via:KABUL TO TORKHAM PAKISTAN IN TRANSIT C&F TO ATTARI INDIA INCLUDE OF ALL CHARGES\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"60\"}', 0, 0, NULL, 1, 1, '2024-09-05 18:32:19', 1, NULL, NULL),
(61, 'p', 0, 'booking', '2024-09-08', 'Pakistan', '{\"sea_road\":\"road\",\"l_country_road\":\"kah\",\"l_border_road\":\"mandb,mnbm,b,b,m,bm,\",\"l_date_road\":\"2024-09-08\",\"truck_container\":\"open_truck\",\"r_country_road\":\"bnvcmhfunvb m\",\"r_border_road\":\"gvjghydfcvbncnbvgcd\",\"r_date_road\":\"2024-09-08\",\"d_date_road\":\"2024-09-08\",\"l_country\":\"\",\"l_port\":\"\",\"l_date\":\"2024-09-08\",\"ctr_name\":\"\",\"r_country\":\"\",\"r_port\":\"\",\"r_date\":\"2024-09-08\",\"arrival_date\":\"2024-09-08\",\"report\":\"5 ctbas jahgs jasb\",\"seaRoadDetailsSubmit\":\"\",\"hidden_id\":\"61\",\"is_loading\":0,\"is_receiving\":0}', 1, 0, NULL, 1, 1, '2024-09-08 16:32:46', 1, NULL, NULL);

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
(1, 1, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 2, 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan City: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2\r\nWEIGHT: 322', '2024-09-18 20:56:29'),
(2, 1, 'purchase', 'cr', 'dc3', 'CHAMAN OFFICE/AGENT', 3, 0, '', '2024-09-18 20:56:29'),
(3, 2, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 2, 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2\r\nWEIGHT: 322788766', '2024-09-18 20:56:29'),
(4, 2, 'purchase', 'cr', 'dc1', 'QUETTA OFFICE', 1, 3, '3ASMATNAJEEB & COMPANY 2\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT PLAZA AL RAS DUBAI\r\nNTN: 719170-9\r\nST: 00000000DDDDD', '2024-09-18 20:56:29'),
(5, 3, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 3, '3ASMATNAJEEB & COMPANY 2\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT PLAZA AL RAS DUBAI\r\nNTN: 719170-9\r\nST: 00000000', '2024-09-18 20:56:29'),
(6, 3, 'purchase', 'cr', 'dc5', 'KUMAR CUSTOM CLEARING AGENT', 5, 0, '', '2024-09-18 20:56:29'),
(7, 4, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 3, '3ASMATNAJEEB & COMPANY 2\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT PLAZA AL RAS DUBAI\r\nNTN: 719170-9\r\nST: 00000000', '2024-09-18 20:56:29'),
(8, 4, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 6, 'DAMODAR  EXPORT\r\nCountry: INDIA\r\nCity: IBD\r\nState: INDIA\r\nAddress:  OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA\r\nFSSAI: 10019022010092\r\nIEC: AAQFD1336 \r\nGST: 27 AAQFD1336E1ZK', '2024-09-18 20:56:29'),
(9, 5, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 2, 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan:City: QUETTA:State: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2:WEIGHT: 322', '2024-09-18 20:56:29'),
(10, 5, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 6, 'DAMODAR  EXPORT\r\nCountry: INDIA\r\nCity: IBD\r\nState: INDIA\r\nAddress:  OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA\r\nFSSAI: 10019022010092:IEC: AAQFD1336 :GST: 27 AAQFD1336E1ZK', '2024-09-18 20:56:29'),
(11, 6, 'purchase', 'dr', 'dc3', 'CHAMAN OFFICE/AGENT', 3, 0, '', '2024-09-18 20:56:29'),
(12, 6, 'purchase', 'cr', 'dc5', 'KUMAR CUSTOM CLEARING AGENT', 5, 5, 'lk jsdlk jfslkdj \r\nCountry: \r\nCity: \r\nState: \r\nAddress: ', '2024-09-18 20:56:29'),
(13, 7, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 3, '3ASMATNAJEEB & COMPANY 2\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT PLAZA AL RAS DUBAI\r\nNTN: 719170-9\r\nST: 00000000', '2024-09-18 20:56:29'),
(14, 7, 'purchase', 'cr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(15, 8, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 3, '3ASMATNAJEEB & COMPANY 2\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT PLAZA AL RAS DUBAI\r\nNTN: 719170-9\r\nST: 00000000', '2024-09-18 20:56:29'),
(16, 8, 'purchase', 'cr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(17, 9, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 2, 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2\r\nWEIGHT: 322', '2024-09-18 20:56:29'),
(18, 9, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, 'al ras    \r\n', '2024-09-18 20:56:29'),
(19, 10, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(20, 10, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(21, 11, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(22, 11, 'purchase', 'cr', 'dc5', 'KUMAR CUSTOM CLEARING AGENT', 5, 0, '', '2024-09-18 20:56:29'),
(23, 12, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(24, 12, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(25, 13, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(26, 13, 'purchase', 'cr', 'dc5', 'KUMAR CUSTOM CLEARING AGENT', 5, 0, '', '2024-09-18 20:56:29'),
(27, 14, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 2, 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2\r\nWEIGHT: 322', '2024-09-18 20:56:29'),
(28, 14, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 6, 'DAMODAR  EXPORT\r\nCountry: INDIA\r\nCity: IBD\r\nState: INDIA\r\nAddress:  OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA\r\nFSSAI: 10019022010092\r\nIEC: AAQFD1336 \r\nGST: 27 AAQFD1336E1ZK', '2024-09-18 20:56:29'),
(29, 15, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 2, 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2\r\nWEIGHT: 322', '2024-09-18 20:56:29'),
(30, 15, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 6, 'DAMODAR  EXPORT\r\nCountry: INDIA\r\nCity: IBD\r\nState: INDIA\r\nAddress:  OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA\r\nFSSAI: 10019022010092\r\nIEC: AAQFD1336 \r\nGST: 27 AAQFD1336E1ZK', '2024-09-18 20:56:29'),
(31, 16, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 6, 'DAMODAR  EXPORT\r\nCountry: INDIA\r\nCity: IBD\r\nState: INDIA\r\nAddress:  OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA\r\nFSSAI: 10019022010092\r\nIEC: AAQFD1336 \r\nGST: 27 AAQFD1336E1ZK', '2024-09-18 20:56:29'),
(32, 16, 'purchase', 'cr', 'dc1', 'QUETTA OFFICE', 1, 3, '3ASMATNAJEEB & COMPANY 2\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT PLAZA AL RAS DUBAI\r\nNTN: 719170-9\r\nST: 00000000', '2024-09-18 20:56:29'),
(33, 17, 'purchase', 'dr', 'du11', 'HAJI ASMATULLAH PERSONAL', 11, 0, '', '2024-09-18 20:56:29'),
(34, 17, 'purchase', 'cr', 'dc19', 'FARM KHATAMZHAN LEGAL', 19, 0, '', '2024-09-18 20:56:29'),
(35, 18, 'purchase', 'dr', 'DC17', 'BILAL NOORZAI', 17, 0, '', '2024-09-18 20:56:29'),
(36, 18, 'purchase', 'cr', 'dc20', 'KHAN MOHMMAD ', 20, 0, '', '2024-09-18 20:56:29'),
(37, 19, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 2, 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2\r\nWEIGHT: 322', '2024-09-18 20:56:29'),
(38, 19, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 6, 'DAMODAR  EXPORT\r\nCountry: INDIA\r\nCity: IBD\r\nState: INDIA\r\nAddress:  OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA\r\nFSSAI: 10019022010092\r\nIEC: AAQFD1336 \r\nGST: 27 AAQFD1336E1ZK', '2024-09-18 20:56:29'),
(39, 20, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 2, 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2\r\nWEIGHT: 322', '2024-09-18 20:56:29'),
(40, 20, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 6, 'DAMODAR  EXPORT\r\nCountry: INDIA\r\nCity: IBD\r\nState: INDIA\r\nAddress:  OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA\r\nFSSAI: 10019022010092\r\nIEC: AAQFD1336 \r\nGST: 27 AAQFD1336E1ZK', '2024-09-18 20:56:29'),
(41, 21, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 2, 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2\r\nWEIGHT: 322', '2024-09-18 20:56:29'),
(42, 21, 'purchase', 'cr', 'dc22', ' ALM KHAN /RAHAT AL NOOR SHPPING LLC', 22, 0, '', '2024-09-18 20:56:29'),
(43, 22, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(44, 22, 'purchase', 'cr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(45, 23, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(46, 23, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(47, 24, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(48, 24, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(49, 25, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(50, 25, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(51, 26, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(52, 26, 'purchase', 'cr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(53, 27, 'purchase', 'dr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(54, 27, 'purchase', 'cr', 'dc5', 'KUMAR CUSTOM CLEARING AGENT', 5, 0, '', '2024-09-18 20:56:29'),
(55, 28, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(56, 28, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(57, 29, 'purchase', 'dr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(58, 29, 'purchase', 'cr', 'dc5', 'KUMAR CUSTOM CLEARING AGENT', 5, 0, '', '2024-09-18 20:56:29'),
(59, 30, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(60, 30, 'purchase', 'cr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(61, 31, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(62, 31, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(63, 32, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(64, 32, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(65, 33, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(66, 33, 'purchase', 'cr', 'dc5', 'KUMAR CUSTOM CLEARING AGENT', 5, 0, '', '2024-09-18 20:56:29'),
(67, 34, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(68, 34, 'purchase', 'cr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(69, 35, 'purchase', 'dr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(70, 35, 'purchase', 'cr', 'dc5', 'KUMAR CUSTOM CLEARING AGENT', 5, 0, '', '2024-09-18 20:56:29'),
(71, 36, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(72, 36, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(73, 37, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(74, 37, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(75, 38, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(76, 38, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(77, 39, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(78, 39, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(79, 40, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(80, 40, 'purchase', 'cr', 'dc18', 'AYAZ NOORI LTD', 18, 0, '', '2024-09-18 20:56:29'),
(81, 41, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(82, 41, 'purchase', 'cr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(83, 42, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(84, 42, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(85, 43, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(86, 43, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(87, 44, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(88, 44, 'purchase', 'cr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(89, 45, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(90, 45, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(91, 46, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(92, 46, 'purchase', 'cr', 'dc18', 'AYAZ NOORI LTD', 18, 0, '', '2024-09-18 20:56:29'),
(93, 47, 'purchase', 'dr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(94, 47, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(95, 48, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(96, 48, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(97, 49, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(98, 49, 'purchase', 'cr', 'dc5', 'KUMAR CUSTOM CLEARING AGENT', 5, 0, '', '2024-09-18 20:56:29'),
(99, 50, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(100, 50, 'purchase', 'cr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(101, 51, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(102, 51, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(103, 52, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(104, 52, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(105, 53, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(106, 53, 'purchase', 'cr', 'dc5', 'KUMAR CUSTOM CLEARING AGENT', 5, 0, '', '2024-09-18 20:56:29'),
(107, 54, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(108, 54, 'purchase', 'cr', 'dc18', 'AYAZ NOORI LTD', 18, 0, '', '2024-09-18 20:56:29'),
(109, 55, 'purchase', 'dr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(110, 55, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(111, 56, 'purchase', 'dr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(112, 56, 'purchase', 'cr', 'dc14', 'MUZAMMIL/HAJI AKHTERE', 14, 0, '', '2024-09-18 20:56:29'),
(113, 57, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(114, 57, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(115, 58, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(116, 58, 'purchase', 'cr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(117, 59, 'purchase', 'dr', 'dc15', 'KUAM', 15, 0, '', '2024-09-18 20:56:29'),
(118, 59, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 0, '', '2024-09-18 20:56:29'),
(119, 60, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 0, '', '2024-09-18 20:56:29'),
(120, 60, 'purchase', 'cr', 'dc5', 'KUMAR CUSTOM CLEARING AGENT', 5, 0, '', '2024-09-18 20:56:29'),
(121, 61, 'purchase', 'dr', 'dc1', 'QUETTA OFFICE', 1, 2, 'ASMATNAJEEB& COMPANY\r\nCountry: Pakistan\r\nCity: QUETTA\r\nState: BALOCHISTAN\r\nAddress: HIDAYAT \r\nFSSAI: FS-392-US23-2\r\nWEIGHT: 322', '2024-09-18 20:56:29'),
(122, 61, 'purchase', 'cr', 'dc6', 'SANJAY BROKER', 6, 6, 'DAMODAR  EXPORT\r\nCountry: INDIA\r\nCity: IBD\r\nState: INDIA\r\nAddress:  OFFICE NO 5 GROUND  FLOOR  GOLD CITY PLOT NO ;11 SECTOR 19  D VASHI NAVI MUMBAI 4000703 INDIA\r\nFSSAI: 10019022010092\r\nIEC: AAQFD1336 \r\nGST: 27 AAQFD1336E1ZK', '2024-09-18 20:56:29');

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
  `final_amount` double NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`id`, `parent_id`, `p_s`, `sr`, `goods_id`, `size`, `brand`, `origin`, `qty_name`, `qty_no`, `qty_kgs`, `total_kgs`, `empty_kgs`, `total_qty_kgs`, `net_kgs`, `divide`, `weight`, `total`, `price`, `currency1`, `rate1`, `amount`, `currency2`, `rate2`, `opr`, `final_amount`, `created_at`) VALUES
(1, 1, 'p', 1, 4, '70/75', 'JEMBO', 'INDNOSHIA', 'KG', 1000, 82, 82000, 0, 0, 82000, 'TON', 52, 1576.923, 'CARTON', 'USD', 4, 6307.692, 'INR', 3.41, '*', 21509.23, '2024-08-27 20:15:46'),
(3, 3, 'p', 1, 11, 'RED COLOR', 'DGT', 'UZBEKISTAN', 'kkk', 29, 38, 1102, 0, 0, 1102, 'TON', 8, 137.75, 'TON', 'USD', 9, 1239.75, 'AED', 2, '*', 2479.5, '2024-08-27 19:44:14'),
(4, 4, 'p', 1, 3, '100 GRAM/25PIECE', 'YUNNAN/CHIAN', 'CHIAN', 'BAGS', 2200, 10.1, 22220, 0.1, 220, 22000, 'TON', 1000, 22, 'TON', 'USD', 4200, 92400, 'AED', 3.67, '*', 339108, '2024-08-28 05:59:24'),
(5, 4, 'p', 2, 3, '100 GRAM/25/30 PIECE', 'KYR/DGT', 'CHIAN', 'CTAN', 2100, 10.05, 21105, 0.05, 105, 21000, 'KG', 1, 21000, 'KG', 'USD', 4.25, 89250, 'AED', 3.67, '*', 327547.5, '2024-08-28 06:04:20'),
(6, 5, 'p', 1, 6, '180/190', 'DGT', 'AFGHISTAN', 'CTAN', 2400, 10.5, 25200, 0.5, 1200, 24000, 'CARTON', 4.4, 5454.545, 'CARTON', 'AFN', 300, 1636363.5, 'AED', 19.3, '/', 84785.674, '2024-08-28 06:10:36'),
(7, 5, 'p', 2, 7, 'JOMBO SUPER', 'DGT', 'AFGHISTAN', 'CTAN', 1500, 10.05, 15075.000000000002, 0.5, 750, 14325.000000000002, 'CARTON', 4.4, 3255.682, 'CARTON', 'AFN', 1670, 5436988.94, 'AED', 19.3, '/', 281709.272, '2024-08-28 06:13:46'),
(8, 5, 'p', 3, 9, '30/32', 'DGT', 'AFGHISTAN', 'BAGS', 800, 25.25, 20200, 0.25, 200, 20000, 'CARTON', 1, 20000, 'CARTON', 'USD', 0.75, 15000, 'AED', 3.675, '*', 55125, '2024-08-28 06:18:02'),
(9, 5, 'p', 4, 12, '0', 'DG', 'AFGHANI', 'PP BAGS', 520, 50.25, 26130, 0.25, 130, 26000, 'KG', 4.4, 5909.091, 'KG', 'AFN', 345, 2038636.395, 'AED', 19.3, '/', 105628.829, '2024-08-28 06:25:06'),
(10, 5, 'p', 5, 4, '80\\85', 'JEMBO', 'INDNOSHIA', 'BAGS', 330, 82, 27060, 2, 660, 26400, 'CARTON', 1000, 26.4, 'TON', 'USD', 850, 22440, 'AED', 3.67, '*', 82354.8, '2024-08-28 06:27:26'),
(11, 6, 'p', 1, 3, '100 GRAM/25PIECE', 'YUNNAN/CHIAN', 'CHIAN', 'CTAN', 12000, 10.5, 126000, 0.5, 6000, 120000, 'KG', 1, 120000, 'TON', 'AED', 4.25, 510000, 'AED', 3.675, '*', 1874250, '2024-08-28 06:30:20'),
(12, 7, 'p', 1, 1, '185', 'YUNNAN', 'chian', 'BAGS', 500, 25.5, 12750, 0.5, 250, 12500, 'KG', 25, 500, 'KG', 'AED', 180, 90000, 'AED', 1, '*', 90000, '2024-08-28 12:58:38'),
(13, 7, 'p', 2, 4, '60/65', 'JEMBO', 'INDNOSHIA', 'BAGS', 800, 25.2, 20160, 0.2, 160, 20000, 'CARTON', 1, 20000, 'TON', 'USD', 1.5, 30000, 'AED', 3.675, '*', 110250, '2024-08-28 13:00:21'),
(14, 7, 'p', 3, 15, '8MM 10*1KG', 'AKBAR', 'INDIA', 'BAGS', 1500, 25.2, 37800, 0.2, 300, 37500, 'KG', 1, 37500, 'TON', 'USD', 0.89, 33375, 'AED', 3.67, '*', 122486.25, '2024-08-28 13:01:14'),
(15, 7, 'p', 4, 5, 'NPX.20/22', 'NPX', 'U$', 'CARTON', 880, 23, 20240, 0.32, 281.6, 19958.4, 'KG', 1, 19958.4, 'TON', 'USD', 2.5, 49896, 'AED', 3.675, '*', 183367.8, '2024-08-28 13:02:34'),
(16, 7, 'p', 5, 5, '22/24', 'NPX', 'U$', 'ctan', 880, 23, 20240, 0.32, 281.6, 19958.4, 'TON', 1, 19958.4, 'TON', 'USD', 2.25, 44906.4, 'AED', 3.675, '*', 165031.02, '2024-08-28 13:04:22'),
(17, 7, 'p', 6, 5, '26/28', 'NPX', 'U$', 'CARTON', 880, 23, 20240, 0.3, 264, 19976, 'TON', 1, 19976, 'KG', 'USD', 2.3, 45944.8, 'AED', 3.67, '*', 168617.416, '2024-08-28 13:05:31'),
(18, 7, 'p', 7, 5, '34/36', 'NPX', 'U$', 'ctan', 880, 23, 20240, 0.32, 281.6, 19958.4, 'KG', 1, 19958.4, 'TON', 'USD', 2.3, 45904.32, 'AED', 3.67, '*', 168468.854, '2024-08-28 13:06:31'),
(19, 8, 'p', 1, 1, '90% ', 'YUNNAN', 'chian', 'BAGS', 220, 23, 5060, 0.5, 110, 4950, 'TON', 10, 495, 'TON', 'USD', 180, 89100, 'AED', 3.67, '*', 326997, '2024-08-28 13:11:20'),
(20, 9, 'p', 1, 3, '100 GRAM/25PIECE', 'YUNNAN/CHIAN', 'CHIAN', 'BAGS', 220, 10.75, 2365, 0.5, 110, 2255, 'TON', 10, 225.5, 'TON', 'USD', 180, 40590, 'AED', 3.67, '*', 148965.3, '2024-08-28 13:14:59'),
(21, 10, 'p', 1, 8, 'JOMBO ', 'DGT', 'AFGHISTAN', 'CARTON', 700, 10.5, 7350, 0.5, 350, 7000, 'CARTON', 10.5, 666.667, 'CARTON', 'USD', 100, 66666.7, 'AED', 3.67, '*', 244666.789, '2024-08-28 13:16:47'),
(22, 11, 'p', 1, 7, 'MEDIUM', 'DGT', 'AFGHISTAN', 'BAGS', 1600, 10.5, 16800, 0.5, 800, 16000, 'KG', 10.5, 1523.81, 'KG', 'USD', 80, 121904.8, 'AED', 3.67, '*', 447390.616, '2024-08-28 13:24:59'),
(23, 12, 'p', 1, 1, '185', 'YUNNAN', 'chian', 'BAGS', 2700, 10.5, 28350, 0.5, 1350, 27000, 'TON', 10.5, 2571.429, 'KG', 'USD', 570, 1465714.53, 'AED', 3.67, '*', 5379172.325, '2024-08-28 13:30:28'),
(24, 14, 'p', 1, 6, '180/190', 'DGT', 'AFGHISTAN', 'BAGS', 8800, 25.2, 221760, 0.2, 1760, 220000, 'TON', 1, 220000, 'CARTON', 'USD', 1, 220000, 'AED', 3.67, '*', 807400, '2024-08-28 15:12:28'),
(25, 15, 'p', 1, 5, 'NPX.20/22', 'DGT', 'U$', 'CTAN', 8800, 23, 202400, 0.32, 2816, 199584, 'KG', 1, 199584, 'TON', 'USD', 2.65, 528897.6, 'AED', 3.67, '*', 1941054.192, '2024-08-28 15:19:01'),
(26, 15, 'p', 2, 5, '22/24', 'NPX', 'U$', 'CTAN', 4400, 23, 101200, 0.32, 1408, 99792, 'KG', 1, 99792, 'TON', 'AED', 2.35, 234511.2, 'AED', 3.67, '*', 860656.104, '2024-08-28 15:20:09'),
(27, 15, 'p', 3, 5, '26/28', 'NPX', 'U$', 'CTAN', 1720, 23, 39560, 0.32, 550.4, 39009.6, 'KG', 1, 39009.6, 'KG', 'USD', 2.25, 87771.6, 'AED', 3.67, '*', 322121.772, '2024-08-28 15:21:22'),
(28, 16, 'p', 1, 9, '30/32', 'DGT', 'CHILI', 'PP BAGS', 8000, 25.2, 201600, 0.2, 1600, 200000, 'KG', 1, 200000, 'KG', 'USD', 0.95, 190000, 'AED', 3.67, '*', 697300, '2024-08-28 15:23:22'),
(29, 17, 'p', 1, 12, '0', 'DG', 'AFGHANI', 'PP BAGS', 5400, 50.2, 271080, 0.2, 1080, 270000, 'KG', 4.4, 61363.636, 'KG', 'AFN', 345, 21170454.42, 'AED', 19.3, '/', 1096914.737, '2024-08-28 15:26:07'),
(30, 18, 'p', 1, 15, '8MM 10*1KG', 'AKBAR', 'INDIA', 'BAGS', 3300, 82, 270600, 2, 6600, 264000, 'TON', 1000, 264, 'TON', 'USD', 850, 224400, 'AED', 3.67, '*', 823548, '2024-08-28 15:30:09'),
(31, 19, 'p', 1, 9, '34/36', 'DGT', 'CHILI', 'BAGS', 8800, 25.2, 221760, 0.2, 1760, 220000, 'KG', 1, 220000, 'KG', 'USD', 0.78, 171600, 'AED', 3.67, '*', 629772, '2024-08-28 15:34:28'),
(32, 1, 'p', 2, 3, '100 GRAM/42PIECE', 'YUNNAN/CHIAN', 'UZBEKISTAN', 'kgs', 299, 31, 9269, 0, 0, 9269, 'CARTON', 21, 441.381, 'CARTON', 'USD', 3.3, 1456.557, 'USD', 3.1, '*', 4515.327, '2024-09-02 23:45:59'),
(33, 19, 'p', 2, 9, '30/32', 'DGT', 'U$', 'Bags', 5000, 25.5, 127500, 0.25, 1250, 126250, 'KG', 1, 126250, 'KG', 'AED', 2, 252500, 'AED', 3.67, '*', 926675, '2024-09-05 08:04:35'),
(34, 19, 'p', 3, 3, '100 GRAM/25PIECE', 'UZB/DGT', 'CHIALI', ' Ags', 8000, 25.25, 202000, 1, 8000, 194000, 'KG', 1, 194000, 'TON', 'AED', 1.9, 368600, 'AED', 3.67, '*', 1352762, '2024-09-05 08:06:33'),
(35, 21, 'p', 1, 1, '185', 'YUNNAN', 'chian', 'BAGS', 10000, 25.5, 255000, 0.25, 2500, 252500, 'KG', 1, 252500, 'TON', 'AED', 0.95, 239875, 'AED', 3.67, '*', 880341.25, '2024-09-05 12:58:35'),
(36, 22, 'p', 1, 2, '1122', 'CHAMAN', 'PAKISTAN', 'BAGS', 100, 10.75, 1075, 0, 0, 1075, 'KG', 10, 107.5, 'TON', 'AED', 570, 61275, 'USD', 3.67, '*', 224879.25, '2024-09-05 13:11:24'),
(37, 23, 'p', 1, 2, '1122', 'pak', 'PAKISTAN', 'BAGS', 1600, 10.75, 17200, 0.5, 800, 16400, 'TON', 10.5, 1561.905, 'CARTON', 'USD', 520, 812190.6, 'AED', 3.67, '*', 2980739.502, '2024-09-05 13:13:29'),
(38, 24, 'p', 1, 5, '22/24', 'NPX', 'U$', 'BAGS', 583, 10.75, 6267.25, 0, 0, 6267.25, 'TON', 10.5, 596.881, 'CARTON', 'USD', 520, 310378.12, 'AED', 3.67, '*', 1139087.7, '2024-09-05 13:14:28'),
(39, 25, 'p', 1, 12, '0', 'DG', 'AFGHANI', 'BAGS', 1600, 10.5, 16800, 0.5, 800, 16000, 'KG', 10, 1600, 'TON', 'AED', 520, 832000, 'USD', 3.67, '*', 3053440, '2024-09-05 13:17:15'),
(40, 26, 'p', 1, 2, '36', 'CHAMAN', 'PAKISTAN', 'BAGS', 1600, 25.25, 40400, 0.5, 800, 39600, 'CARTON', 10, 3960, 'TON', 'AED', 850, 3366000, 'AED', 3.67, '*', 12353220, '2024-09-05 13:18:07'),
(41, 27, 'p', 1, 4, '90/95', 'JEMBO', 'INDNOSHIA', 'PP BAGS', 880, 10.75, 9460, 0.5, 440, 9020, 'KG', 10, 902, 'TON', 'AED', 570, 514140, 'AED', 3.67, '*', 1886893.8, '2024-09-05 13:19:49'),
(42, 28, 'p', 1, 9, '30/32', 'DGT', 'CHILI', 'PP BAGS', 880, 23, 20240, 0, 0, 20240, 'KG', 10, 2024, 'TON', 'USD', 570, 1153680, 'AED', 3.67, '*', 4234005.6, '2024-09-05 13:23:27'),
(43, 29, 'p', 1, 7, 'JOMBO ', 'DGT', 'AFGHISTAN', 'PP BAGS', 880, 10.75, 9460, 0, 0, 9460, 'KG', 10, 946, 'TON', 'AED', 570, 539220, 'AED', 3.67, '*', 1978937.4, '2024-09-05 13:24:23'),
(44, 30, 'p', 1, 15, '8MM 10*1KG', 'AKBAR', 'INDIA', 'PP BAGS', 880, 10.75, 9460, 0.5, 440, 9020, 'KG', 10, 902, 'TON', 'USD', 850, 766700, 'USD', 3.67, '*', 2813789, '2024-09-05 13:26:12'),
(45, 31, 'p', 1, 5, '28/30', 'NPX', 'U$', 'CARTON', 880, 23, 20240, 0.5, 440, 19800, 'TON', 10, 1980, 'TON', 'USD', 850, 1683000, 'AED', 3.67, '*', 6176610, '2024-09-05 13:26:58'),
(46, 32, 'p', 1, 3, '100 GRAM/25PIECE', 'YUNNAN/CHIAN', 'CHIAN', 'CARTON', 1600, 10.75, 17200, 0, 0, 17200, 'TON', 10, 1720, 'TON', 'USD', 850, 1462000, 'AED', 3.67, '*', 5365540, '2024-09-05 13:27:32'),
(47, 33, 'p', 1, 5, '32/34', 'NPX', 'U$', ' COTTON', 1600, 14, 22400, 0.5, 800, 21600, 'TON', 10, 2160, 'KG', 'USD', 850, 1836000, 'AED', 3.67, '*', 6738120, '2024-09-05 13:29:15'),
(48, 34, 'p', 1, 7, 'JOMBO SUPER', 'DGT', 'AFGHISTAN', 'BAGS', 1600, 14, 22400, 0, 0, 22400, 'TON', 10, 2240, 'TON', 'USD', 850, 1904000, 'AED', 3.67, '*', 6987680, '2024-09-05 13:29:53'),
(49, 35, 'p', 1, 3, '100 GRAM/42PIECE', 'YUNNAN/CHIAN', 'CHIAN', 'BAGS', 1600, 10.75, 17200, 0.5, 800, 16400, 'KG', 10, 1640, 'TON', 'AED', 850, 1394000, 'AED', 3.67, '*', 5115980, '2024-09-05 13:30:42'),
(50, 36, 'p', 1, 5, '22/24', 'NPX', 'U$', 'BAGS', 1600, 10.75, 17200, 0, 0, 17200, 'TON', 10, 1720, 'TON', 'USD', 570, 980400, 'AED', 3.67, '*', 3598068, '2024-09-05 13:31:09'),
(51, 37, 'p', 1, 8, 'JOMBO ', 'DGT', 'AFGHISTAN', 'PP BAGS', 1600, 10.75, 17200, 0, 0, 17200, 'TON', 10, 1720, 'TON', 'AED', 570, 980400, 'AED', 3.67, '*', 3598068, '2024-09-05 13:31:42'),
(52, 38, 'p', 1, 4, '70/75', 'JEMBO', 'INDNOSHIA', 'CARTON', 1600, 25.25, 40400, 0, 0, 40400, 'TON', 10, 4040, 'TON', 'USD', 570, 2302800, 'AED', 3.67, '*', 8451276, '2024-09-05 13:32:16'),
(53, 39, 'p', 1, 7, 'JOMBO ', 'DGT', 'AFGHISTAN', 'BAGS', 160, 10.75, 1720, 0.5, 80, 1640, 'TON', 10, 164, 'TON', 'AED', 570, 93480, 'AED', 3.67, '*', 343071.6, '2024-09-05 13:32:57'),
(54, 40, 'p', 1, 10, '5 MIM', 'DGT', 'VIETNAM', 'BAGS', 160, 10.75, 1720, 0, 0, 1720, 'TON', 10, 172, 'TON', 'USD', 520, 89440, 'AED', 3.67, '*', 328244.8, '2024-09-05 13:33:49'),
(55, 41, 'p', 1, 3, '100 GRAM/25/30 PIECE', 'YUNNAN/CHIAN', 'CHIAN', 'BAGS', 160, 10.75, 1720, 0, 0, 1720, 'TON', 10, 172, 'TON', 'USD', 520, 89440, 'AED', 3.67, '*', 328244.8, '2024-09-05 14:18:22'),
(56, 42, 'p', 1, 13, '8mm', 'AKBAR', 'india', 'BAGS', 160, 10.75, 1720, 0, 0, 1720, 'TON', 10, 172, 'TON', 'USD', 520, 89440, 'AED', 3.67, '*', 328244.8, '2024-09-05 14:18:56'),
(57, 43, 'p', 1, 4, '80/85', 'JEMBO', 'INDNOSHIA', 'BAGS', 1600, 10.75, 17200, 0, 0, 17200, 'TON', 10, 1720, 'TON', 'AED', 520, 894400, 'AED', 3.67, '*', 3282448, '2024-09-05 14:19:29'),
(58, 44, 'p', 1, 11, 'RED COLOR', 'DGT', 'UZBEKISTAN', 'BAGS', 1600, 10.75, 17200, 0.5, 800, 16400, 'TON', 10, 1640, 'TON', 'USD', 520, 852800, 'AED', 3.67, '*', 3129776, '2024-09-05 14:20:10'),
(59, 45, 'p', 1, 2, '1122', 'CHAMAN', 'PAKISTAN', 'BAGS', 1600, 23, 36800, 0, 0, 36800, 'TON', 1000, 36.8, 'TON', 'USD', 570, 20976, 'AED', 3.67, '*', 76981.92, '2024-09-05 14:21:16'),
(60, 46, 'p', 1, 6, '180/190', 'DGT', 'AFGHISTAN', 'BAGS', 2700, 23, 62100, 0, 0, 62100, 'TON', 1000, 62.1, 'TON', 'AED', 570, 35397, 'AED', 3.67, '*', 129906.99, '2024-09-05 14:22:12'),
(61, 47, 'p', 1, 4, '90/95', 'JEMBO', 'INDNOSHIA', 'BAGS', 1600, 23, 36800, 0, 0, 36800, 'TON', 10, 3680, 'TON', 'USD', 520, 1913600, 'AED', 3.67, '*', 7022912, '2024-09-05 14:22:45'),
(62, 48, 'p', 1, 7, 'JOMBO SUPER', 'DGT', 'AFGHISTAN', 'BAGS', 1600, 23, 36800, 0, 0, 36800, 'TON', 10, 3680, 'TON', 'USD', 520, 1913600, 'AED', 3.67, '*', 7022912, '2024-09-05 14:23:18'),
(63, 49, 'p', 1, 2, '1122', 'CHAMAN', 'PAKISTAN', 'BAGS', 1600, 10.75, 17200, 0, 0, 17200, 'TON', 1000, 17.2, 'TON', 'AED', 520, 8944, 'AED', 3.67, '*', 32824.48, '2024-09-05 14:23:57'),
(64, 50, 'p', 1, 10, '5 MIM', 'DGT', 'VIETNAM', 'BAGS', 1600, 10.75, 17200, 0, 0, 17200, 'TON', 1, 17200, 'TON', 'USD', 520, 8944000, 'AED', 3.67, '*', 32824480, '2024-09-05 14:24:23'),
(65, 51, 'p', 1, 11, 'RED COLOR', 'DGT', 'UZBEKISTAN', 'BAGS', 1600, 10.75, 17200, 0, 0, 17200, 'TON', 100, 172, 'TON', 'USD', 520, 89440, 'AED', 3.67, '*', 328244.8, '2024-09-05 14:24:54'),
(66, 52, 'p', 1, 9, '34/36', 'DGT', 'CHILI', 'BAGS', 160, 10.75, 1720, 0, 0, 1720, 'TON', 1000, 1.72, 'TON', 'AED', 4.3, 7.396, 'AED', 3.67, '*', 27.143, '2024-09-05 14:25:23'),
(67, 53, 'p', 1, 5, '22/24', 'NPX', 'U$', 'BAGS', 1650, 10.75, 17737.5, 0.5, 825, 16912.5, 'TON', 1000, 16.913, 'TON', 'USD', 4.3, 72.726, 'AED', 3.67, '*', 266.904, '2024-09-05 14:25:54'),
(68, 54, 'p', 1, 13, '7 MA', 'AKBAR', 'india', 'BAGS', 1650, 10.75, 17737.5, 0, 0, 17737.5, 'TON', 1000, 17.738, 'TON', 'AED', 4.3, 76.273, 'AED', 3.67, '*', 279.922, '2024-09-05 14:26:26'),
(69, 55, 'p', 1, 4, '70/75', 'JEMBO', 'INDNOSHIA', 'BAGS', 1650, 10.5, 17325, 0, 0, 17325, 'TON', 1000, 17.325, 'TON', 'INR', 520, 9009, 'AED', 3.67, '*', 33063.03, '2024-09-05 14:27:42'),
(70, 56, 'p', 1, 2, '1122', 'CHAMAN', 'PAKISTAN', 'BAGS', 1650, 10.5, 17325, 0, 0, 17325, 'TON', 1000, 17.325, 'TON', 'AED', 520, 9009, 'AED', 3.67, '*', 33063.03, '2024-09-05 14:28:05'),
(71, 57, 'p', 1, 4, '80\\85', 'JEMBO', 'INDNOSHIA', 'BAGS', 1650, 10, 16500, 0, 0, 16500, 'KG', 1000, 16.5, 'TON', 'AED', 520, 8580, 'AED', 3.67, '*', 31488.6, '2024-09-05 14:29:14'),
(72, 58, 'p', 1, 4, '90/95', 'JEMBO', 'INDNOSHIA', 'BAGS', 2700, 10.5, 28350, 0, 0, 28350, 'TON', 10, 2835, 'TON', 'USD', 520, 1474200, 'AED', 3.67, '*', 5410314, '2024-09-05 14:30:06'),
(73, 59, 'p', 1, 15, '8MM 10*1KG', 'AKBAR', 'INDIA', 'BAGS', 1600, 10.75, 17200, 0, 0, 17200, 'TON', 1000, 17.2, 'TON', 'AED', 570, 9804, 'AED', 3.67, '*', 35980.68, '2024-09-05 14:32:05'),
(74, 60, 'p', 1, 11, 'RED COLOR', 'DGT', 'UZBEKISTAN', 'BAGS', 1600, 10.5, 16800, 0, 0, 16800, 'TON', 1000, 16.8, 'TON', 'USD', 520, 8736, 'AED', 3.67, '*', 32061.12, '2024-09-05 14:32:40'),
(75, 61, 'p', 1, 12, '0', 'DG', 'AFGHANI', 'PP BAGS', 21600, 50.5, 1090800, 0.5, 10800, 1080000, 'KG', 4.4, 245454.545, 'KG', 'USD', 370, 90818181.65, 'AED', 19.3, '/', 4705605.267, '2024-09-08 12:35:08'),
(76, 5, 'p', 6, 5, '100 GRAM/45 PIECE', 'DGT', 'U$', 'CTAN', 880, 23, 20240, 0.32, 281.6, 19958.4, 'KG', 1, 19958.4, 'KG', 'USD', 2.67, 53288.928, 'AED', 3.67, '*', 195570.366, '2024-09-14 07:12:40');

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
(6, 'agent', 1, 1, 'manager', 'ubaid', 'pass', 'ubaid', NULL, NULL, '{\"khaata_id\":\"29\",\"khaata_no\":\"m3\",\"hidden_id\":\"6\"}', '2024-08-05 20:51:47', 1, NULL),
(7, 'office', 1, 1, 'manager', 'Kalim@dgt.llc', '123456789', 'kalimullah', 'uploads/89jhxh1q.png', NULL, NULL, '2024-09-05 16:53:10', 1, NULL),
(8, 'office', 1, 1, 'manager', 'hidayat@dgt.llc', '12345678', 'hidayat', NULL, NULL, NULL, '2024-09-06 11:44:01', 1, NULL);

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
(2, 2, '[\"branches\",\"categories\",\"khaata\",\"users\",\"roznamcha\",\"ledger\",\"ledger-categories\",\"purchase-orders\",\"purchases\",\"purchase-advance\",\"purchase-full\",\"purchase-rem\",\"loading\",\"transfer\",\"agents\",\"stock\",\"#\",\"admin-agent-bills\",\"admin-agent-forms\",\"#\",\"warehouse-details\",\"sales-booking\",\"sales-local\",\"sale-advance\",\"sale-rem\",\"sale-full\",\"#\",\"loading-sale-booking\",\"loading-purchase-booking\",\"loading-purchase-local\",\"loading-sale-local\",\"sales-market-final\",\"sales-local-final\",\"stock-qty\",\"stock-checking\",\"#\",\"#\",\"#\",\"#\",\"#\",\"#\",\"purchase-local-orders-final\",\"sale-account\",\"purchase-account\",\"contracts\",\"#\",\"#\",\"#\",\"#\",\"#\",\"#\",\"afghan-invoices\",\"vat-sales\",\"#\",\"#\",\"purchase-market\",\"#\",\"vat-purchases\",\"vat-general\",\"draft-invoices\",\"invoices\",\"#\",\"exchanges\",\"exchanges-stock\"]'),
(3, 3, '[\"khaata\",\"roznamcha\",\"ledger\",\"ledger-categories\",\"purchase-orders\"]'),
(4, 4, '[\"roznamcha\"]'),
(5, 5, '[\"users\"]'),
(6, 6, '[\"branches\"]'),
(7, 7, '[\"#\",\"users\",\"#\",\"categories\",\"goods\",\"#\",\"compose\",\"inbox\",\"ledger-categories\",\"roznamcha-banks\",\"exchanges\",\"exchanges-stock\",\"roznamcha-office\",\"#.\",\"#.\",\"#.\",\"#.\",\"#\",\"draft-invoices\",\"#\",\"#\",\"AVAT/TAX\",\"AVAT/TAX\",\"#\",\"PURCHASE/SALES\",\"#\",\"#\",\"VAT/TAX\",\"ledger\",\"purchases\",\"#\",\"#\",\"afghan-invoices\"]'),
(8, 8, '[\"users\",\"#\",\"categories\",\"exchanges-stock\",\"ledger-categories\",\"roznamcha-banks\",\"exchanges\",\"#\",\"#\",\"purchases\",\"ledger\",\"VAT/TAX\",\"#\",\"#\",\"PURCHASE/SALES\",\"#\",\"AVAT/TAX\",\"AVAT/TAX\",\"#\",\"#\",\"draft-invoices\",\"#\",\"#.\",\"#.\",\"#.\",\"roznamcha-office\",\"#.\"]');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `afg_inv_details`
--
ALTER TABLE `afg_inv_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `good_details`
--
ALTER TABLE `good_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `khaata`
--
ALTER TABLE `khaata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `khaata_details`
--
ALTER TABLE `khaata_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `navbar`
--
ALTER TABLE `navbar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roznamchaas`
--
ALTER TABLE `roznamchaas`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=670;

--
-- AUTO_INCREMENT for table `roznamchaas_deleted`
--
ALTER TABLE `roznamchaas_deleted`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `static_types`
--
ALTER TABLE `static_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `transaction_accounts`
--
ALTER TABLE `transaction_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
