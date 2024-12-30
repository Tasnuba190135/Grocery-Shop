-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2024 at 07:32 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_grocery`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_discount_buyxgety`
--

CREATE TABLE `tbl_discount_buyxgety` (
  `id` int(11) NOT NULL,
  `p_id` int(11) DEFAULT NULL,
  `buy` int(11) DEFAULT NULL,
  `get` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `discount_statement` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_discount_buyxgety`
--

INSERT INTO `tbl_discount_buyxgety` (`id`, `p_id`, `buy`, `get`, `status`, `discount_statement`, `created`, `last_modified`) VALUES
(1, 1, 2, 1, 1, 'Must return the free items if return', '2024-06-02 17:29:01', '2024-06-04 03:24:21'),
(2, 8, 1, 1, 1, '', '2024-06-02 18:02:10', '2024-06-02 18:02:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `id` int(11) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `u_id` int(11) DEFAULT NULL,
  `p_id` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `p_price` varchar(255) DEFAULT NULL,
  `discount_p_id` varchar(255) DEFAULT NULL,
  `d_quantity` varchar(255) DEFAULT NULL,
  `total_price` float DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`id`, `status`, `u_id`, `p_id`, `quantity`, `p_price`, `discount_p_id`, `d_quantity`, `total_price`, `address`, `region`, `created`, `last_modified`) VALUES
(19, 'return accepted', 2, '1,2,3,4,5', '1,1,1,1,1', NULL, NULL, NULL, 2470, '15205 North Kierland Blvd. Suite 100 Scottsdale', 'us', '2024-05-31 16:59:14', '2024-05-31 18:01:36'),
(20, 'completed', 1, '1,6,7,4', '1,1,1,1', NULL, NULL, NULL, 1280, '101 Marlow Street #12-05\nSingapore 059020', 'as', '2024-05-31 17:00:37', '2024-06-04 05:30:44'),
(21, 'pending', 3, '1,2,5,7', '1,1,1,1', NULL, NULL, NULL, 2130, '49 Featherstone Street,EC1Y 8SY,UNITED KINGDOM', 'uk', '2024-05-31 17:02:21', '2024-05-31 17:39:26'),
(22, 'pending', 4, '1,2,3,6', '1,1,1,1', NULL, NULL, NULL, 1420, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-05-31 17:04:00', '2024-05-31 17:39:32'),
(23, 'pending', 5, '1,3,5', '1,1,1', NULL, NULL, NULL, 2100, 'KABUL province\n1001 postcode\nAFGHANISTAN', 'af', '2024-05-31 17:05:01', '2024-05-31 17:39:39'),
(24, 'pending', 5, '3,4', '1,1', NULL, NULL, NULL, 790, 'KABUL province\n1001 postcode\nAFGHANISTAN', 'af', '2024-05-31 17:06:14', '2024-05-31 17:39:50'),
(25, 'pending', 5, '5,7', '1,1', NULL, NULL, NULL, 1650, 'KABUL province\n1001 postcode\nAFGHANISTAN', 'af', '2024-05-31 17:06:41', '2024-05-31 17:40:00'),
(26, 'pending', 5, '2', '1', NULL, NULL, NULL, 180, 'KABUL province\n1001 postcode\nAFGHANISTAN', 'af', '2024-05-31 17:07:38', '2024-05-31 17:40:06'),
(27, 'pending', 1, '4,2', '1,1', NULL, NULL, NULL, 370, '101 Marlow Street #12-05\nSingapore 059020', 'as', '2024-05-31 17:08:20', '2024-05-31 17:40:11'),
(28, 'pending', 1, '5', '1', NULL, NULL, NULL, 1200, '101 Marlow Street #12-05\nSingapore 059020', 'as', '2024-05-31 17:08:42', '2024-05-31 17:40:17'),
(29, 'pending', 1, '5', '1', NULL, NULL, NULL, 1200, '101 Marlow Street #12-05\nSingapore 059020', 'as', '2024-05-31 17:09:03', '2024-05-31 17:43:53'),
(30, 'completed', 2, '2,4', '1,1', NULL, NULL, NULL, 370, '15205 North Kierland Blvd. Suite 100 Scottsdale', 'us', '2024-05-31 17:09:48', '2024-05-31 18:00:17'),
(31, 'pending', 2, '5,6', '1,1', NULL, NULL, NULL, 1540, '15205 North Kierland Blvd. Suite 100 Scottsdale', 'us', '2024-05-31 17:10:17', '2024-05-31 17:44:13'),
(32, 'cancelled', 2, '3,4', '1,1', NULL, NULL, NULL, 790, '15205 North Kierland Blvd. Suite 100 Scottsdale', 'us', '2024-05-31 17:10:49', '2024-05-31 18:03:45'),
(33, 'pending', 4, '1,3', '1,1', NULL, NULL, NULL, 900, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-05-31 17:11:47', '2024-05-31 17:44:32'),
(34, 'pending', 4, '5', '1', NULL, NULL, NULL, 1200, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-05-31 17:12:08', '2024-05-31 17:42:01'),
(35, 'pending', 4, '6', '1', NULL, NULL, NULL, 340, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-05-31 17:12:33', '2024-05-31 17:41:56'),
(36, 'pending', 4, '1', '2', NULL, NULL, NULL, 710, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-02 10:55:01', '2024-06-02 10:57:45'),
(37, 'pending', 4, '1', '1', NULL, NULL, NULL, 410, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-02 10:57:02', '2024-06-02 10:57:02'),
(38, 'pending', 4, '1,8', '2,1', '300,350', '1,8', '1,1', 1060, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-02 19:24:07', '2024-06-02 19:24:07'),
(39, 'pending', 4, '8', '2', '350', '8', '1', 810, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-02 19:26:11', '2024-06-02 19:26:11'),
(40, 'completed', 4, '8', '2', '350', '8', '2', 810, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-02 19:29:52', '2024-06-02 20:29:53'),
(41, 'cancelled', 4, '2,1', '1,2', '180,300', '1', '2', 890, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-02 20:20:10', '2024-06-02 20:24:19'),
(42, 'completed', 4, '2,3', '4,1', '180,600', '', '', 1430, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-03 02:43:51', '2024-06-03 02:44:24'),
(43, 'pending', 4, '1,2,8', '1,1,1', '300,180,350', '1,8', '1,1', 940, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-03 04:24:29', '2024-06-03 04:24:29'),
(44, 'pending', 4, '1,2,8', '1,1,1', '300,180,350', '1,8', '1,1', 940, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-03 04:43:59', '2024-06-03 04:43:59'),
(45, 'return accepted', 4, '1,2,8', '1,1,1', '300,180,350', '1,8', '1,1', 940, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-03 04:48:56', '2024-06-03 05:48:42'),
(46, 'return rejected', 4, '1,2,8', '1,1,1', '300,180,350', '1,8', '1,1', 940, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-03 04:55:45', '2024-06-03 05:49:49'),
(47, 'cancelled', 4, '1,2', '2,1', '300,180', '1', '1', 890, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-04 03:27:10', '2024-06-04 03:27:29'),
(48, 'pending', 4, '4', '10', '190', '', '', 2010, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-04 03:28:31', '2024-06-04 03:28:31'),
(49, 'return rejected', 4, '4', '10', '190', '', '', 2010, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-04 03:29:55', '2024-06-04 03:55:55'),
(50, 'cancelled', 4, '4', '10', '190', '', '', 2010, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-04 03:33:26', '2024-06-04 03:41:56'),
(51, 'cancelled', 4, '4', '10', '190', '', '', 2010, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-04 03:35:10', '2024-06-04 03:41:14'),
(52, 'cancelled', 4, '4', '7', '190', '', '', 1440, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-04 03:38:06', '2024-06-04 03:38:29'),
(53, 'cancelled', 4, '3', '5', '600', '', '', 3110, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-04 03:45:03', '2024-06-04 03:45:31'),
(54, 'return accepted', 4, '3', '10', '600', '', '', 6110, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-04 03:45:55', '2024-06-04 03:54:35'),
(55, 'return accepted', 4, '3,5', '3,4', '600,1200', '', '', 6710, '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', '2024-06-04 05:29:20', '2024-06-04 05:31:34');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `id` int(11) NOT NULL,
  `product_image_name` varchar(255) DEFAULT NULL,
  `product_title` varchar(255) NOT NULL,
  `product_description` text DEFAULT NULL,
  `product_stock` int(11) NOT NULL,
  `price` float DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `last_modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`id`, `product_image_name`, `product_title`, `product_description`, `product_stock`, `price`, `created`, `last_modified`) VALUES
(1, 'b2529582810621375b8e43faac57cd59.jpg_300x0q75.png', 'Alesto Brazil Nuts - 200g', 'Product details of Alesto Brazil Nuts - 200g\r\nProduct Type:Brazil Nuts\r\nBrand: Alesto\r\nWeight: 200gm\r\n100% Original\r\nOrigin of UK\r\nSpecifications of Alesto Brazil Nuts - 200g\r\nBrandNo BrandSKU212587402_BD-1162117690Country of OriginUKWeight200 gm', 78, 300, '2024-05-31 21:27:57', '2024-06-04 09:27:29'),
(2, '2b71f19a49c87b9720909d424565b258.jpg_300x0q75.png', 'Khan chanachur special badam vaja - 1kg', 'Product details of Khan chanachur special badam vaja - 1kg\r\nbrand name : Khan chanachur and sweets Product name : Badam vajaNet weight : 1KG\r\n১৯৮৮সাল থেকে চলমান সুস্বাদু ও মজাদার খান চানাচুর নিয়ে এলো স্পেশাল বাদাম ভাজা। সব পণ্য আমাদের নিজস্ব কারখানায় উৎপাদিত। তাই দেরি না করে এখনই অর্ডার করুন।\r\n\r\nSpecifications of Khan chanachur special badam vaja - 1kg', 86, 180, '2024-05-31 21:31:21', '2024-06-04 09:27:29'),
(3, '79f94744d597d0ed0bc7cfc8a577e938.jpg_300x0q75.png', 'Almond Nut - Kath Badam - 500gm (Rasak)', 'Product details of Almond Nut - Kath Badam - 500gm (Rasak)\r\nAlmond nut500gHigh Quality ProductRasak Brand\r\nAlmond Nut - Kath Badam - 500gm (Rasak)', 98, 600, '2024-05-31 21:32:49', '2024-06-04 11:31:34'),
(4, '237828eaa1ac777faf42cb74fda595bc.jpg_300x0q75.png', 'PopCorn', 'Product details of Popcorn (Bhutta)-1 Kg\r\nProduct Type: Natural Bhutta ভুট্টা\r\nNet Weight: 1kg\r\nInstant Snacks, yummyTasty\r\nEasy to Make\r\nGood for Your Healthy Life\r\n100% Highest quality\r\nAll thing buy at wholesale price\r\n99% clients satisfaction\r\nমজাদার এবং মুখরোচক নাস্তা\r\nতৈরি করা খুবই সহজ.', 82, 190, '2024-05-31 21:33:51', '2024-06-04 09:41:56'),
(5, '8aad1674c79e739923398a8f18158692.jpg_300x0q75.png', 'Walnut (Akhrot/Walnut)- 1 Kg', 'Product details of Walnut (Akhrot/Walnut)- 1 Kg\r\nFresh & Chemical free\r\nImported Product\r\nGourmet, Savory Flavor\r\nPacked Fresh\r\nHealthy snack , Great Taste\r\nJar Packed\r\nWalnuts - আখরোট 1kg\r\nSuperior Quality Walnuts', 96, 1200, '2024-05-31 21:35:29', '2024-06-04 11:31:34'),
(6, 'Sf9ca3ea8ee0844d98b5b4c3aa4fe245ay.jpg_300x0q75.jpeg', 'Calo Vita ( রুচি ও স্বাস্থ্য বাড়ায় )', 'Specifications of Calo Vita ( রুচি ও স্বাস্থ্য বাড়ায় )\r\nBrandNo Brand', 47, 340, '2024-05-31 21:42:58', '2024-05-31 23:12:33'),
(7, '7e0c3f0688843d7e4ee5cbc4a127f9f4.jpg_300x0q75.png', 'Walnut-Walnut (Akhrot)- 250 Gm', 'Product details of Walnut-Walnut (Akhrot)- 250 Gm\r\nGourmet, Savory Flavor\r\nPacked Fresh\r\nHealthy snack , Great Taste\r\nZipper Packet\r\nHeart Healthy Snack', 49, 450, '2024-05-31 21:45:03', '2024-05-31 22:39:20'),
(8, 'amuldarkchocolate1.jpg', 'Amuls Dark Chocolate 40gm', 'Amuls Dark Chocolate 40gm', 187, 350, '2024-06-02 22:02:35', '2024-06-03 11:48:42');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_return`
--

CREATE TABLE `tbl_return` (
  `id` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `p_id` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `discount_p_id` varchar(255) DEFAULT NULL,
  `discount_quantity` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_return`
--

INSERT INTO `tbl_return` (`id`, `status`, `order_id`, `p_id`, `quantity`, `discount_p_id`, `discount_quantity`, `created`, `last_modified`) VALUES
(1, 1, 45, '2,8', '1,1', '1,8', '1,1', '2024-06-03 04:51:13', '2024-06-03 05:48:42'),
(4, 1, 46, '1,8', '1,1', '1,8', '1,1', '2024-06-03 05:03:36', '2024-06-03 05:49:49'),
(5, 1, 54, '3', '10', '', '', '2024-06-04 03:49:10', '2024-06-04 03:54:35'),
(6, 1, 49, '4', '8', '', '', '2024-06-04 03:55:17', '2024-06-04 03:55:55'),
(7, 1, 55, '3,5', '2,3', '', '', '2024-06-04 05:30:31', '2024-06-04 05:31:34');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `fullname`, `email`, `password`, `address`, `region`, `status`, `created`, `last_modified`) VALUES
(1, 'mahmodul ', '190120.cse@student.just.edu.bd', '12345', '101 Marlow Street #12-05\nSingapore 059020', 'as', 1, '2024-05-31 15:37:27', '2024-05-31 16:50:09'),
(2, 'Arafat', '190122.cse@student.just.edu.bd', '12345', '15205 North Kierland Blvd. Suite 100 Scottsdale', 'us', 1, '2024-05-31 15:38:04', '2024-05-31 16:52:48'),
(3, 'Tanvir', '190113.cse@student.just.edu.bd', '12345', '49 Featherstone Street,EC1Y 8SY,UNITED KINGDOM', 'uk', 1, '2024-05-31 15:38:42', '2024-05-31 16:54:48'),
(4, 'Arisha', '190135.cse@student.just.edu.bd', '12345', '92 McPherson Road\nGREAT SOUTHERN, VIC, 3685\nAUSTRALIA', 'au', 1, '2024-05-31 15:39:12', '2024-05-31 16:55:57'),
(5, 'Dipu', '190124.cse@student.just.edu.bd', '12345', 'KABUL province\n1001 postcode\nAFGHANISTAN', 'af', 1, '2024-05-31 15:39:42', '2024-05-31 16:57:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_discount_buyxgety`
--
ALTER TABLE `tbl_discount_buyxgety`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_return`
--
ALTER TABLE `tbl_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_discount_buyxgety`
--
ALTER TABLE `tbl_discount_buyxgety`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_return`
--
ALTER TABLE `tbl_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
