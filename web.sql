-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: 127.0.0.1
-- Χρόνος δημιουργίας: 16 Σεπ 2024 στις 14:27:07
-- Έκδοση διακομιστή: 10.4.32-MariaDB
-- Έκδοση PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `web`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `admin`
--

CREATE TABLE `admin` (
  `admin_user` varchar(25) NOT NULL,
  `latitude_vehicle` decimal(10,8) DEFAULT NULL,
  `longitude_vehicle` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `admin`
--

INSERT INTO `admin` (`admin_user`, `latitude_vehicle`, `longitude_vehicle`) VALUES
('admin', 38.26410095, 21.75428345);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `announcements`
--

CREATE TABLE `announcements` (
  `announce_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `announcements`
--

INSERT INTO `announcements` (`announce_id`, `created_at`) VALUES
(3000, '2024-09-16 11:33:24'),
(3001, '2024-09-16 11:33:36');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `announcement_items`
--

CREATE TABLE `announcement_items` (
  `announce_id` int(11) NOT NULL,
  `announce_product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `announcement_items`
--

INSERT INTO `announcement_items` (`announce_id`, `announce_product`, `quantity`) VALUES
(3000, 16, 1),
(3000, 36, 0),
(3001, 72, 4);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(5, 'Food'),
(6, 'Beverages'),
(7, 'Clothing'),
(14, 'Flood'),
(16, 'Medical Supplies'),
(19, 'Shoes'),
(21, 'Personal Hygiene '),
(22, 'Cleaning Supplies'),
(23, 'Tools'),
(24, 'Kitchen Supplies'),
(25, 'Baby Essentials'),
(26, 'Insect Repellents'),
(27, 'Electronic Devices'),
(28, 'Cold weather'),
(29, 'Animal Food'),
(30, 'Financial support'),
(33, 'Cleaning Supplies.'),
(34, 'Hot Weather'),
(35, 'First Aid '),
(41, 'Pet Supplies'),
(43, 'Energy Drinks'),
(44, 'Disability and Assistance Items'),
(45, 'Communication items'),
(46, 'Communications'),
(47, 'Humanitarian Shelters'),
(48, 'Water Purification'),
(49, 'Animal Care'),
(50, 'Earthquake Safety'),
(51, 'Sleep Essentilals'),
(52, 'Navigation Tools'),
(53, 'Clothing and cover'),
(54, 'Tools and Equipment'),
(56, 'Special items'),
(57, 'Household Items'),
(59, 'Books'),
(60, 'Fuel and Energy'),
(66, 'Animal Flood'),
(67, 'Solar-Powered Devices'),
(68, 'Mental Health Support'),
(69, 'Sanitary Products');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `civilian`
--

CREATE TABLE `civilian` (
  `civilian_user` varchar(25) NOT NULL,
  `full_name` varchar(25) NOT NULL,
  `phone` char(10) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `civilian`
--

INSERT INTO `civilian` (`civilian_user`, `full_name`, `phone`, `latitude`, `longitude`) VALUES
('dimitris', 'Dimitris Papageorgiou', '6976244517', 38.21663600, 21.71872400),
('georgepap', 'Giorgos Papadopoulos', '6910112887', 38.24663600, 21.74502000),
('johngrg', 'Giannis Georgiou', '6989733324', 38.21057600, 21.73457400),
('mariak', 'Maria Kouri', '1234567890', 38.22439600, 21.73457400),
('miltos', 'Miltiadis Mantes', '6985932076', 38.24663611, 21.73457400);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `inquiry`
--

CREATE TABLE `inquiry` (
  `inquiry_id` int(11) NOT NULL,
  `inquiry_user` varchar(25) NOT NULL,
  `inquiry_product` int(11) NOT NULL,
  `inquiry_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `inquiry`
--

INSERT INTO `inquiry` (`inquiry_id`, `inquiry_user`, `inquiry_product`, `inquiry_quantity`) VALUES
(7, 'dimitris', 17, 1),
(8, 'dimitris', 40, 2),
(9, 'georgepap', 16, 1),
(10, 'georgepap', 72, 4),
(11, 'miltos', 36, 5);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `inquiry_details`
--

CREATE TABLE `inquiry_details` (
  `details_id` int(11) DEFAULT NULL,
  `inquiry_status` enum('pending','approved','finished','cancelled') NOT NULL,
  `inquiry_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_vehicle_id` varchar(25) DEFAULT NULL,
  `approved_timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `inquiry_details`
--

INSERT INTO `inquiry_details` (`details_id`, `inquiry_status`, `inquiry_date`, `approved_vehicle_id`, `approved_timestamp`) VALUES
(7, 'finished', '2024-09-15 20:07:39', 'CAR001', '2024-09-15 21:53:18'),
(8, 'approved', '2024-09-15 20:07:50', 'CAR002', '2024-09-15 22:25:44'),
(11, 'approved', '2024-09-15 20:09:45', 'CAR001', '2024-09-15 21:58:41'),
(10, 'pending', '2024-09-15 20:09:09', NULL, NULL),
(9, 'pending', '2024-09-15 20:08:31', NULL, NULL);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `inquiry_history`
--

CREATE TABLE `inquiry_history` (
  `inquiry_history_id` int(11) DEFAULT NULL,
  `history_status` enum('pending','approved','finished','cancelled') DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `inquiry_history`
--

INSERT INTO `inquiry_history` (`inquiry_history_id`, `history_status`, `timestamp`) VALUES
(7, 'pending', '2024-09-15 23:07:39'),
(8, 'pending', '2024-09-15 23:07:50'),
(11, 'pending', '2024-09-15 23:09:45'),
(10, 'pending', '2024-09-15 23:09:09'),
(9, 'pending', '2024-09-15 23:08:31'),
(7, 'approved', '2024-09-15 23:09:39'),
(7, 'finished', '2024-09-15 23:11:39'),
(8, 'approved', '2024-09-15 23:10:50'),
(11, 'approved', '2024-09-15 23:10:45');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `offers`
--

CREATE TABLE `offers` (
  `offer_id` int(11) NOT NULL,
  `offer_user` varchar(25) NOT NULL,
  `offer_product` int(11) NOT NULL,
  `offer_quantity` int(11) NOT NULL,
  `announce_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `offers`
--

INSERT INTO `offers` (`offer_id`, `offer_user`, `offer_product`, `offer_quantity`, `announce_id`) VALUES
(2000, 'mariak', 72, 2, 3001),
(2001, 'mariak', 16, 3, 3000),
(2002, 'mariak', 36, 7, 3000),
(2003, 'johngrg', 72, 6, 3001),
(2004, 'johngrg', 16, 1, 3000);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `offers_details`
--

CREATE TABLE `offers_details` (
  `details_id` int(11) DEFAULT NULL,
  `offer_status` enum('pending','approved','finished','cancelled') NOT NULL,
  `offer_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_vehicle_id` varchar(25) DEFAULT NULL,
  `approved_timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `offers_details`
--

INSERT INTO `offers_details` (`details_id`, `offer_status`, `offer_date`, `approved_vehicle_id`, `approved_timestamp`) VALUES
(2001, 'approved', '2024-09-16 11:34:32', 'CAR001', '2024-09-16 14:40:32'),
(2002, 'approved', '2024-09-16 11:34:37', 'CAR001', '2024-09-16 14:45:37'),
(2003, 'approved', '2024-09-16 11:36:10', 'CAR002', '2024-09-16 14:46:10'),
(2004, 'pending', '2024-09-16 11:36:14', NULL, NULL);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `offer_history`
--

CREATE TABLE `offer_history` (
  `offer_history_id` int(11) DEFAULT NULL,
  `history_status` enum('pending','approved','finished','cancelled') DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `offer_history`
--

INSERT INTO `offer_history` (`offer_history_id`, `history_status`, `timestamp`) VALUES
(2000, 'pending', '2024-09-16 14:34:28'),
(2001, 'pending', '2024-09-16 14:34:32'),
(2002, 'pending', '2024-09-16 14:34:37'),
(2000, 'cancelled', '2024-09-16 14:34:56'),
(2003, 'pending', '2024-09-16 14:36:10'),
(2004, 'pending', '2024-09-16 14:36:14'),
(2001, 'approved', '2024-09-16 14:40:32'),
(2002, 'approved', '2024-09-16 14:45:37'),
(2003, 'approved', '2024-09-16 14:46:10');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item` varchar(25) NOT NULL,
  `description` longtext NOT NULL,
  `available` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `item`, `description`, `available`) VALUES
(16, 6, 'Water', 'Water', 0),
(17, 6, 'Orange juice', 'Orange juice', 0),
(18, 5, 'Sardines', 'Sardines', 0),
(19, 5, 'Canned corn', 'Canned corn', 0),
(20, 5, 'Bread', 'Bread', 0),
(21, 5, 'Chocolate', 'Chocolate', 0),
(22, 7, 'Men Sneakers', 'Men Sneakers', 0),
(25, 5, 'Spaghetti', 'Spaghetti', 0),
(26, 5, 'Croissant', 'Croissant', 0),
(29, 5, 'Biscuits', 'Biscuits', 0),
(30, 16, 'Bandages', 'Bandages', 0),
(31, 16, 'Disposable gloves', 'Disposable gloves', 0),
(32, 16, 'Gauze', 'Gauze', 0),
(33, 16, 'Antiseptic', 'Antiseptic', 0),
(34, 16, 'First Aid Kit', 'First Aid Kit', 0),
(35, 16, 'Painkillers', 'Painkillers', 0),
(36, 7, 'Blanket', 'Blanket', 0),
(37, 5, 'Fakes', 'Fakes', 0),
(38, 21, 'Menstrual Pads', 'Menstrual Pads', 0),
(39, 21, 'Tampon', 'Tampon', 0),
(40, 21, 'Toilet Paper', 'Toilet Paper', 0),
(41, 21, 'Baby wipes', 'Baby wipes', 0),
(42, 21, 'Toothbrush', 'Toothbrush', 0),
(43, 21, 'Toothpaste', 'Toothpaste', 0),
(44, 16, 'Vitamin C', 'Vitamin C', 0),
(45, 16, 'Multivitamines', 'Multivitamines', 0),
(46, 16, 'Paracetamol', 'Paracetamol', 0),
(47, 16, 'Ibuprofen', 'Ibuprofen', 0),
(51, 22, 'Cleaning rag', 'Cleaning rag', 0),
(52, 22, 'Detergent', 'Detergent', 0),
(53, 22, 'Disinfectant', 'Disinfectant', 0),
(54, 22, 'Mop', 'Mop', 0),
(55, 22, 'Plastic bucket', 'Plastic bucket', 0),
(56, 22, 'Scrub brush', 'Scrub brush', 0),
(57, 22, 'Dust mask', 'Dust mask', 0),
(58, 22, 'Broom', 'Broom', 0),
(59, 23, 'Hammer', 'Hammer', 0),
(60, 23, 'Skillsaw', 'Skillsaw', 0),
(61, 23, 'Prybar', 'Prybar', 0),
(62, 23, 'Shovel', 'Shovel', 0),
(63, 23, 'Flashlight', 'Flashlight', 0),
(64, 23, 'Duct tape', 'Duct tape', 0),
(65, 7, 'Underwear', 'Underwear', 0),
(66, 7, 'Socks', 'Socks', 0),
(67, 7, 'Warm Jacket', 'Warm Jacket', 0),
(68, 7, 'Raincoat', 'Raincoat', 0),
(69, 7, 'Gloves', 'Gloves', 0),
(70, 7, 'Pants', 'Pants', 0),
(71, 7, 'Boots', 'Boots', 0),
(72, 24, 'Dishes', 'Dishes', 0),
(73, 24, 'Pots', 'Pots', 0),
(74, 24, 'Paring knives', 'Paring knives', 0),
(75, 24, 'Pan', 'Pan', 0),
(76, 24, 'Glass', 'Glass', 0),
(85, 6, 'Coca Cola', 'Coca Cola', 0),
(86, 26, 'spray', 'spray', 0),
(87, 26, 'Outdoor spiral', 'Outdoor spiral', 0),
(88, 25, 'Baby bottle', 'Baby bottle', 0),
(89, 25, 'Pacifier', 'Pacifier', 0),
(90, 5, 'Condensed milk', 'Condensed milk', 0),
(91, 5, 'Cereal bar', 'Cereal bar', 0),
(92, 23, 'Pocket Knife', 'Pocket Knife', 0),
(93, 16, 'Water Disinfection Tablet', 'Water Disinfection Tablets', 0),
(94, 27, 'Radio', 'Radio', 0),
(95, 14, 'Kitchen appliances', 'Kitchen appliances', 0),
(96, 28, 'Winter hat', 'Winter hat', 0),
(97, 28, 'Winter gloves', 'Winter gloves', 0),
(98, 28, 'Scarf', 'Scarf', 0),
(99, 28, 'Thermos', 'Thermos', 0),
(100, 6, 'Tea', 'Tea', 0),
(101, 29, 'Dog Food ', 'Dog Food ', 0),
(102, 29, 'Cat Food', 'Cat Food', 0),
(103, 5, 'Canned', 'Canned', 0),
(104, 22, 'Chlorine', 'Chlorine', 0),
(105, 22, 'Medical gloves', 'Medical gloves', 0),
(106, 7, 'T-Shirt', 'T-Shirt', 0),
(107, 34, 'Cooling Fan', 'Cooling Fan', 0),
(108, 34, 'Cool Scarf', 'Cool Scarf', 0),
(109, 23, 'Whistle', 'Whistle', 0),
(110, 28, 'Blankets', 'Blankets', 0),
(111, 28, 'Sleeping Bag', 'Sleeping Bag', 0),
(114, 16, 'Thermometer', 'Thermometer', 0),
(115, 5, 'Rice', 'Rice', 0),
(117, 22, 'Towels', 'Towels', 0),
(118, 22, 'Wet Wipes', 'Wet Wipes', 0),
(119, 23, 'Fire Extinguisher', 'Fire Extinguisher', 0),
(120, 5, 'Fruits', 'Fruits', 0),
(123, 19, 'Αθλητικά', 'Αθλητικά', 0),
(124, 5, 'Πασατέμπος', 'Πασατέμπος', 0),
(126, 35, 'Betadine', 'Betadine', 0),
(127, 35, 'cotton wool', 'cotton wool', 0),
(128, 5, 'Crackers', 'Crackers', 0),
(129, 21, 'Sanitary Pads', 'Sanitary Pads', 0),
(130, 21, 'Sanitary wipes', 'Sanitary wipes', 0),
(131, 16, 'Electrolytes', 'Electrolytes', 0),
(132, 16, 'Pain killers', 'Pain killers', 0),
(134, 6, 'Juice', 'Juice', 0),
(136, 16, 'Sterilized Saline', 'Sterilized Saline', 0),
(138, 16, 'Antihistamines', 'Antihistamines', 0),
(139, 5, 'Instant Pancake Mix', 'Instant Pancake Mix', 0),
(140, 5, 'Lacta', 'Lacta', 0),
(141, 5, 'Canned Tuna', 'Canned Tuna', 0),
(142, 23, 'Batteries', 'Batteries', 0),
(144, 23, 'Can Opener', 'Can Opener', 0),
(146, 5, 'Πατατάκια', 'Πατατάκια', 0),
(147, 21, 'Σερβιέτες', 'Σερβιέτες', 0),
(148, 5, 'Dry Cranberries', 'Dry Cranberries', 0),
(149, 5, 'Dry Apricots', 'Dry Apricots', 0),
(150, 5, 'Dry Figs', 'Dry Figs', 0),
(151, 5, 'Παξιμάδια', 'Παξιμάδια', 0),
(155, 16, 'Tampons', 'Tampons', 0),
(156, 41, 'plaster set', 'plaster set', 0),
(157, 41, 'elastic bandages', 'elastic bandages', 0),
(158, 41, 'traumaplast', 'traumaplast', 0),
(159, 41, 'thermal blanket', 'thermal blanket', 0),
(160, 41, 'burn gel', 'burn gel', 0),
(161, 41, 'pet carrier', 'pet carrier', 0),
(162, 41, 'pet dishes', 'pet dishes', 0),
(163, 41, 'plastic bags', 'plastic bags', 0),
(164, 41, 'toys', 'toys', 0),
(165, 41, 'burn pads', 'burn pads', 0),
(166, 5, 'cheese', 'cheese', 0),
(167, 5, 'lettuce', 'lettuce', 0),
(168, 5, 'eggs', 'eggs', 0),
(169, 5, 'steaks', 'steaks', 0),
(170, 5, 'beef burgers', 'beef burgers', 0),
(171, 5, 'tomatoes', 'tomatoes', 0),
(172, 5, 'onions', 'onions', 0),
(173, 5, 'flour', 'flour', 0),
(174, 5, 'pastel', 'pastel', 0),
(175, 5, 'nuts', 'nuts', 0),
(188, 6, 'cold coffee', 'cold coffee', 0),
(189, 43, 'Hell', 'Hell', 0),
(190, 43, 'Monster', 'Monster', 0),
(191, 43, 'Redbull', 'Redbull', 0),
(192, 43, 'Powerade', 'Powerade', 0),
(193, 43, 'PRIME', 'PRIME', 0),
(194, 23, 'Lighter', 'Lighter', 0),
(195, 28, 'isothermally shirts', 'isothermally shirts', 0),
(198, 34, 'Shorts', 'Shorts', 0),
(199, 5, 'Chicken', 'Chicken', 0),
(202, 21, 'sanitary napkins', 'sanitary napkins', 0),
(203, 16, 'COVID-19 Tests', 'COVID-19 Tests', 0),
(204, 6, 'Club Soda', 'Club Soda', 0),
(205, 44, 'Wheelchairs', 'Wheelchairs', 0),
(206, 45, 'mobile phones', 'mobile phones', 0),
(207, 24, 'spoon', 'spoon', 0),
(208, 24, 'fork', 'fork', 0),
(209, 45, 'MOTOTRBO R7', 'MOTOTRBO R7', 0),
(210, 45, 'RM LA 250 (VHF Linear Ενι', 'RM LA 250 (VHF Linear Ενισχυτής 140-150MHz)', 0),
(211, 47, 'Humanitarian General Purp', 'Humanitarian General Purpose Tent System (HGPTS)', 0),
(212, 47, 'CELINA Dynamic Small Shel', 'CELINA Dynamic Small Shelter ', 0),
(213, 47, 'Multi-purpose Area Shelte', 'Multi-purpose Area Shelter System, Type-I', 0),
(214, 7, 'Trousers', 'Trousers', 0),
(215, 7, 'Shoes', 'Shoes', 0),
(216, 7, 'Hoodie', 'Hoodie', 0),
(220, 5, 'macaroni', 'macaroni', 0),
(225, 50, 'Silver blanket', 'Silver blanket', 0),
(226, 50, 'Helmet', 'Helmet', 0),
(227, 50, 'Disposable toilet', 'Disposable toilet', 0),
(228, 50, 'Self-generated flashlight', 'Self-generated flashlight', 0),
(229, 51, 'Mattresses ', 'Mattresses ', 0),
(231, 51, 'matches', 'matches', 0),
(232, 51, 'Heater', 'Heater', 0),
(233, 51, 'Earplugs', 'Earplugs', 0),
(234, 52, 'Compass', 'Compass', 0),
(235, 52, 'Map', 'Map', 0),
(236, 52, 'GPS', 'GPS', 0),
(237, 16, 'First Aid', 'First Aid', 0),
(238, 16, 'Bandage', 'Bandage', 0),
(239, 16, 'Mask', 'Mask', 0),
(240, 16, 'Medicines', 'Medicines', 0),
(242, 5, 'Canned Goods', 'Canned Goods', 0),
(243, 5, 'Snacks', 'Snacks', 0),
(244, 5, 'Cereals', 'Cereals', 0),
(246, 53, 'Shirt', 'Shirt', 0),
(250, 53, 'Caps', 'Caps', 0),
(254, 54, 'Repair Tools', 'Repair Tools', 0),
(255, 21, 'Soap and Shampoo', 'Soap and Shampoo', 0),
(256, 21, 'Toothpastes and Toothbrus', 'Toothpastes and Toothbrushes', 0),
(258, 56, 'Diapers', 'Diapers', 0),
(259, 56, 'Animal food', 'Animal food', 0),
(261, 57, 'Plates', 'Plates', 0),
(262, 57, 'Cups', 'Cups', 0),
(263, 57, 'Cutlery ', 'Cutlery ', 0),
(264, 57, 'Cleaning Supplies', 'Cleaning Supplies', 0),
(266, 57, 'Home Repair Tools', 'Home Repair Tools', 0),
(268, 59, 'Lord of the Rings', 'Lord of the Rings', 0),
(272, 60, 'Gasoline', 'Gasoline', 0),
(273, 60, 'Power Banks', 'Power Banks', 0),
(279, 67, 'Solar Charger', 'Solar Charger', 0),
(280, 67, 'Solar-Powered Radio', 'Solar-Powered Radio', 0),
(281, 67, 'Solar Torch', 'Solar Torch', 0),
(282, 68, 'Stress Ball', 'Stress Ball', 0),
(283, 68, 'Guided Meditation Audio', 'Guided Meditation Audio', 0),
(286, 69, 'Frezyderm Baby Bath ', 'Frezyderm Baby Bath ', 0),
(287, 59, 'Love me again ', 'Love me again ', 0),
(288, 16, 'Apotel', 'Apotel', 0),
(290, 16, 'Medrol', 'Medrol', 0),
(291, 19, 'Nike shoes', 'Nike shoes', 0);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `product_details`
--

CREATE TABLE `product_details` (
  `product_id` int(11) NOT NULL,
  `detail_name` varchar(255) DEFAULT NULL,
  `detail_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `product_details`
--

INSERT INTO `product_details` (`product_id`, `detail_name`, `detail_value`) VALUES
(16, 'volume', '1.5l'),
(16, 'pack size', '6'),
(17, 'volume', '250ml'),
(17, 'pack size', '12'),
(18, 'brand', 'Trata'),
(18, 'weight', '200g'),
(19, 'weight', '500g'),
(20, 'weight', '1kg'),
(20, 'type', 'white'),
(21, 'weight', '100g'),
(21, 'type', 'milk chocolate'),
(21, 'brand', 'ION'),
(22, 'size', '44'),
(25, 'grams', '500'),
(26, 'calories', '200'),
(29, '', ''),
(30, '', '25 pcs'),
(31, '', '100 pcs'),
(32, '', ''),
(33, '', '250ml'),
(34, '', ''),
(35, 'volume', '200mg'),
(36, 'size', '50\" x 60\"'),
(37, '', ''),
(38, 'stock', '500'),
(38, 'size', '3'),
(38, '', ''),
(39, 'stock', '500'),
(39, 'size', 'regular'),
(40, 'stock', '300'),
(40, 'ply', '3'),
(41, 'volume', '500gr'),
(41, 'stock ', '500'),
(41, 'scent', 'aloe'),
(42, 'stock', '500'),
(43, 'stock', '250'),
(44, 'stock', '200'),
(45, 'stock', '200'),
(46, 'stock', '2000'),
(46, 'dosage', '500mg'),
(47, 'stock ', '10'),
(47, 'dosage', '200mg'),
(51, '', ''),
(52, '', ''),
(53, '', ''),
(54, '', ''),
(55, '', ''),
(56, '', ''),
(57, '', ''),
(58, '', ''),
(59, '', ''),
(60, '', ''),
(61, '', ''),
(62, '', ''),
(63, '', ''),
(64, '', ''),
(65, '', ''),
(66, '', ''),
(67, '', ''),
(68, '', ''),
(69, '', ''),
(70, '', ''),
(71, '', ''),
(72, '', ''),
(73, '', ''),
(74, '', ''),
(75, '', ''),
(76, '', ''),
(85, 'Volume', '500ml'),
(86, 'volume', '75ml'),
(87, 'duration', '7 hours'),
(88, 'volume', '250ml'),
(89, 'material', 'silicone'),
(90, 'weight', '400gr'),
(91, 'weight', '23,5gr'),
(92, 'Number of different tools', '3'),
(92, 'Tool', 'Knife'),
(92, 'Tool', 'Screwdriver'),
(92, 'Tool', 'Spoon'),
(93, 'Basic Ingredients', 'Iodine'),
(93, 'Suggested for', 'Everyone expept pregnant women'),
(94, 'Power', 'Batteries'),
(94, 'Frequencies Range', '3 kHz - 3000 GHz'),
(95, '', '(scrubbers, rubber gloves, kitchen detergent, laundry soap)'),
(96, '', ''),
(97, '', ''),
(98, '', ''),
(99, '', ''),
(100, 'volume', '500ml'),
(101, 'volume', '500g'),
(102, 'volume', '500g'),
(103, '', ''),
(104, 'volume', '500ml'),
(105, 'volume', '20pieces'),
(106, 'size', 'XL'),
(107, '', ''),
(108, '', ''),
(109, '', ''),
(110, '', ''),
(111, '', ''),
(114, '', ''),
(115, '', ''),
(117, '', ''),
(118, '', ''),
(119, '', ''),
(120, '', ''),
(120, '', ''),
(123, 'Νο 46', ''),
(124, '', ''),
(126, 'Povidone iodine 10%', '240 ml'),
(127, '100% Hydrofile', '70gr'),
(128, 'Quantity per package', '10'),
(128, 'Packages', '2'),
(129, 'piece', '10 pieces'),
(129, '', ''),
(129, '', ''),
(130, 'pank', '10 packs'),
(131, 'packet of pills', '20 pills'),
(132, 'packet of pills', '20 pills'),
(134, 'volume', '500ml'),
(136, 'volume', '100ml'),
(138, 'pills', '10 pills'),
(139, '', ''),
(140, 'weight', '105g'),
(141, '', ''),
(142, '6 pack', ''),
(144, '1', ''),
(146, 'weight', '45g'),
(147, 'pcs', '18'),
(148, 'weight', '100'),
(149, 'weight', '100'),
(150, 'weight', '100'),
(151, 'weight', '200g'),
(155, '', ''),
(156, '1', ''),
(156, '', ''),
(157, '', '12'),
(158, '', '20'),
(158, '', ''),
(159, '', '2'),
(160, 'ml', '500'),
(161, '', '2'),
(162, '', '10'),
(163, '', '20'),
(164, '', '5'),
(165, '', '5'),
(166, 'grams', '1000'),
(167, 'grams', '500'),
(168, 'pair', '10'),
(169, 'grams', '1000'),
(170, 'grams', '500'),
(171, 'grams', '1000'),
(172, 'grams', '500'),
(173, 'grams', '1000'),
(174, '', '7'),
(175, 'grams', '500'),
(188, '10', '330ml'),
(189, '22', '330'),
(190, '31', '500ml'),
(191, '40', '330ml'),
(192, '23', '500ml'),
(193, '15', '500ml'),
(194, '16', 'Mini'),
(195, '5', 'Medium'),
(195, '6', 'Large'),
(195, '10', 'Small'),
(195, '2', 'XL'),
(198, '20', ''),
(198, '', ''),
(199, '5', '1.5kg'),
(202, '30', '500g'),
(203, '20', ''),
(204, 'volume', '500ml'),
(205, 'quantity', '100'),
(206, 'iphone', '200'),
(207, '', ''),
(208, '', ''),
(209, 'band', 'UHF/VHF'),
(209, 'Wi-Fi', '2,4/5,0 GHz'),
(209, 'Bluetooth', '5.2'),
(209, 'Οθόνη', '2,4” 320 x 240 px. QVGA'),
(209, 'διάρκεια ζωής της μπαταρίας', '28 ώρες'),
(210, 'Frequency', '140-150Mhz'),
(210, 'Power Supply', '13VDC /- 1V 40A'),
(210, 'Output RF Power (Nominal)', '30 – 210W ; 230W max AM/FM/CW'),
(210, 'Modulation Types', 'SSB,CW,AM, FM, data etc (All narrowband modes)'),
(211, 'PART NUMBER', 'C14Y016X016-T'),
(211, 'CONTRACTOR NAME:', 'CELINA Tent, Inc'),
(211, 'COLOR', 'Tan'),
(211, 'SET-UP TIME/NUMBER OF PERSONS', '4 People/30 Minutes'),
(212, 'dimensions', ' 20’x32.5’'),
(212, 'TYPE', 'Frame Structure, Expandable, Air-Transportable'),
(212, 'WEIGHT', '1,200 lbs'),
(213, 'TYPE', 'Frame Structure, Expandable, Air- Transportable'),
(213, 'DIMENSIONS', 'E I-40’x80’'),
(213, 'WEIGHT', '24,000 lbs'),
(214, '', ''),
(215, '', ''),
(216, '', ''),
(220, '', ''),
(225, '', ''),
(226, '', ''),
(227, '', ''),
(228, '', ''),
(229, 'size', '1.90X60'),
(231, 'pack', '60'),
(232, 'Volts', '208'),
(233, 'material', 'plastic'),
(234, 'Type', 'Digital'),
(235, 'Material', 'Paper'),
(236, 'Type', 'Waterproof'),
(237, '1', '1'),
(237, '', ''),
(238, '', '5'),
(239, '', '10'),
(240, '', ''),
(242, '2', '80g'),
(243, '3', '100g'),
(244, '1', '800g'),
(246, '', ''),
(250, '', ''),
(254, '', ''),
(255, '1', '200ml'),
(256, '', ''),
(258, '', ''),
(259, '', ''),
(261, '', ''),
(262, '', ''),
(263, '', ''),
(264, '', ''),
(266, '', ''),
(268, 'pages', '230'),
(272, 'galons', '20'),
(273, 'quantity', '5'),
(279, '', ''),
(279, '', ''),
(280, '', ''),
(281, '', ''),
(282, '', ''),
(283, '', ''),
(286, 'volume', '700ml'),
(287, 'pages', '654'),
(288, 'mg', '100'),
(290, 'mg', '16'),
(291, 'size', '42');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `username` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  `profile` enum('civilian','administrator','volunteer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`username`, `password`, `profile`) VALUES
('admin', 'admin', 'administrator'),
('chryssa', '1234', 'volunteer'),
('dimitris', '12345678', 'civilian'),
('georgepap', 'georgepap', 'civilian'),
('helen', 'ABCD1234', 'volunteer'),
('johngrg', 'abcd1234', 'civilian'),
('mariak', 'mariak', 'civilian'),
('miltos', '29092002', 'civilian');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `vehicle_load`
--

CREATE TABLE `vehicle_load` (
  `vehicle_id` varchar(25) NOT NULL,
  `item` varchar(25) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `vehicle_load`
--

INSERT INTO `vehicle_load` (`vehicle_id`, `item`, `quantity`) VALUES
('CAR001', 'Orange juice', 0),
('CAR001', 'Blanket', 8),
('CAR002', 'Toilet Paper', 2);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `volunteer`
--

CREATE TABLE `volunteer` (
  `volunteer_user` varchar(25) NOT NULL,
  `vehicle` varchar(25) DEFAULT NULL,
  `latitude_vehicle` decimal(10,8) DEFAULT NULL,
  `longtitude_vehicle` decimal(10,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `volunteer`
--

INSERT INTO `volunteer` (`volunteer_user`, `vehicle`, `latitude_vehicle`, `longtitude_vehicle`) VALUES
('chryssa', 'CAR001', 38.24952100, 21.73882600),
('helen', 'CAR002', 34.24852100, 21.73882600);

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_user`),
  ADD UNIQUE KEY `admin_user` (`admin_user`);

--
-- Ευρετήρια για πίνακα `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announce_id`);

--
-- Ευρετήρια για πίνακα `announcement_items`
--
ALTER TABLE `announcement_items`
  ADD KEY `ANNOUNCE` (`announce_id`),
  ADD KEY `ANNOUNCE_OFFER` (`announce_product`);

--
-- Ευρετήρια για πίνακα `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Ευρετήρια για πίνακα `civilian`
--
ALTER TABLE `civilian`
  ADD PRIMARY KEY (`civilian_user`);

--
-- Ευρετήρια για πίνακα `inquiry`
--
ALTER TABLE `inquiry`
  ADD PRIMARY KEY (`inquiry_id`,`inquiry_user`),
  ADD KEY `INQUIRYUSER` (`inquiry_user`),
  ADD KEY `PRODUCT_INQUIRY` (`inquiry_product`);

--
-- Ευρετήρια για πίνακα `inquiry_details`
--
ALTER TABLE `inquiry_details`
  ADD KEY `INQUIRYDETAILS` (`details_id`);

--
-- Ευρετήρια για πίνακα `inquiry_history`
--
ALTER TABLE `inquiry_history`
  ADD KEY `INQUIRYHISTORY` (`inquiry_history_id`);

--
-- Ευρετήρια για πίνακα `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`,`offer_user`),
  ADD KEY `OFFERUSER` (`offer_user`),
  ADD KEY `PRODUCT_OFFER` (`offer_product`),
  ADD KEY `ANNOUNCEOFFER` (`announce_id`);

--
-- Ευρετήρια για πίνακα `offers_details`
--
ALTER TABLE `offers_details`
  ADD KEY `OFFERDETAILS` (`details_id`);

--
-- Ευρετήρια για πίνακα `offer_history`
--
ALTER TABLE `offer_history`
  ADD KEY `OFFERHISTORY` (`offer_history_id`);

--
-- Ευρετήρια για πίνακα `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Ευρετήρια για πίνακα `product_details`
--
ALTER TABLE `product_details`
  ADD KEY `product_id` (`product_id`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Ευρετήρια για πίνακα `vehicle_load`
--
ALTER TABLE `vehicle_load`
  ADD KEY `VEHICLE_LOAD` (`vehicle_id`);

--
-- Ευρετήρια για πίνακα `volunteer`
--
ALTER TABLE `volunteer`
  ADD PRIMARY KEY (`volunteer_user`),
  ADD UNIQUE KEY `vehicle` (`vehicle`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announce_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3002;

--
-- AUTO_INCREMENT για πίνακα `inquiry`
--
ALTER TABLE `inquiry`
  MODIFY `inquiry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT για πίνακα `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2005;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `ADMINUSER` FOREIGN KEY (`admin_user`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `announcement_items`
--
ALTER TABLE `announcement_items`
  ADD CONSTRAINT `ANNOUNCE` FOREIGN KEY (`announce_id`) REFERENCES `announcements` (`announce_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ANNOUNCE_OFFER` FOREIGN KEY (`announce_product`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `civilian`
--
ALTER TABLE `civilian`
  ADD CONSTRAINT `CIVILIANUSER` FOREIGN KEY (`civilian_user`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `inquiry`
--
ALTER TABLE `inquiry`
  ADD CONSTRAINT `INQUIRYUSER` FOREIGN KEY (`inquiry_user`) REFERENCES `civilian` (`civilian_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `PRODUCT_INQUIRY` FOREIGN KEY (`inquiry_product`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `inquiry_details`
--
ALTER TABLE `inquiry_details`
  ADD CONSTRAINT `INQUIRYDETAILS` FOREIGN KEY (`details_id`) REFERENCES `inquiry` (`inquiry_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `inquiry_history`
--
ALTER TABLE `inquiry_history`
  ADD CONSTRAINT `INQUIRYHISTORY` FOREIGN KEY (`inquiry_history_id`) REFERENCES `inquiry` (`inquiry_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `ANNOUNCEOFFER` FOREIGN KEY (`announce_id`) REFERENCES `announcements` (`announce_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `OFFERUSER` FOREIGN KEY (`offer_user`) REFERENCES `civilian` (`civilian_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `PRODUCT_OFFER` FOREIGN KEY (`offer_product`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `offers_details`
--
ALTER TABLE `offers_details`
  ADD CONSTRAINT `OFFERDETAILS` FOREIGN KEY (`details_id`) REFERENCES `offers` (`offer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `offer_history`
--
ALTER TABLE `offer_history`
  ADD CONSTRAINT `OFFERHISTORY` FOREIGN KEY (`offer_history_id`) REFERENCES `offers` (`offer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `product_details`
--
ALTER TABLE `product_details`
  ADD CONSTRAINT `product_details_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `vehicle_load`
--
ALTER TABLE `vehicle_load`
  ADD CONSTRAINT `VEHICLE_LOAD` FOREIGN KEY (`vehicle_id`) REFERENCES `volunteer` (`vehicle`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `volunteer`
--
ALTER TABLE `volunteer`
  ADD CONSTRAINT `VOLUNTEERUSER` FOREIGN KEY (`volunteer_user`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
