-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 02:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `homestays`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `homestay_id` int(10) UNSIGNED DEFAULT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_price` decimal(15,0) NOT NULL,
  `guests_count` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT 'confirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `homestay_id`, `check_in`, `check_out`, `total_price`, `guests_count`, `created_at`, `deleted_at`, `status`) VALUES
(10, 4, 32, '2025-12-05', '2025-12-08', 4350000, 1, '2025-12-02 18:55:42', NULL, 'cancelled'),
(11, 4, 32, '2025-12-11', '2025-12-15', 5700000, 2, '2025-12-02 20:07:22', NULL, 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `homestays`
--

CREATE TABLE `homestays` (
  `homestay_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `district` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `price_weekday` decimal(15,0) DEFAULT 0,
  `price_weekend` decimal(15,0) DEFAULT 0,
  `price_extra_guest` decimal(15,0) DEFAULT 0,
  `max_guests` int(11) DEFAULT 2,
  `num_bedrooms` int(11) DEFAULT 1,
  `num_beds` int(11) DEFAULT 1,
  `rating` float DEFAULT 5,
  `deleted_at` datetime DEFAULT NULL,
  `num_reviews` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `homestays`
--

INSERT INTO `homestays` (`homestay_id`, `name`, `district`, `address`, `description`, `price_weekday`, `price_weekend`, `price_extra_guest`, `max_guests`, `num_bedrooms`, `num_beds`, `rating`, `deleted_at`, `num_reviews`, `created_at`, `updated_at`) VALUES
(3, 'M Library Apartment - Navy Penthouse', 'Tây Hồ', 'Tây Hồ, Hà Nội', 'Căn hộ Penthouse sang trọng, view thành phố.\r\nVị trí đắc địa giữa trung tâm quận Tây Hồ, không gian yên tĩnh, đẳng cấp 5 sao.\r\nCách Thung lũng hoa Hồ Tây 1.4km.', 1790000, 1950000, 0, 2, 1, 1, 5, NULL, 174, '2025-12-02 02:50:40', '2025-12-03 00:44:30'),
(4, 'Hygge House Hồ Tây', 'Tây Hồ', 'Âu Cơ, Tây Hồ', 'Không gian xanh mát, nguyên khu sân vườn rộng, thích hợp BBQ, party.\r\nThiết kế đặc biệt gần gũi thiên nhiên. Phòng khách bài trí ghế sofa thổ cẩm.\r\nKhuôn viên có thể chứa tối đa 20 khách.', 5700000, 6500000, 150000, 20, 3, 3, 4.7, NULL, 264, '2025-12-02 02:50:40', '2025-12-03 00:45:41'),
(5, 'Nice Lakeview Apartment', 'Tây Hồ', 'Thụy Khuê, Tây Hồ', 'Căn hộ 80m2 view hồ, ban công rộng, đầy đủ tiện nghi.\r\nPhong cách hiện đại, đơn giản với tông màu trắng chủ đạo.\r\nTiện ích tòa nhà: Bể bơi, Gym, Tennis.', 1829900, 1416870, 0, 3, 2, 2, 4.4, NULL, 207, '2025-12-02 02:50:40', '2025-12-03 00:46:47'),
(6, 'Minimalism Home - Balcony', 'Tây Hồ', 'Lạc Long Quân, Bưởi', 'Cách Hồ Tây 3 phút đi bộ, nội thất thủ công đơn giản, yên tĩnh.\r\nKhông gian nội thất mô phỏng văn hóa các dân tộc vùng Tây Bắc.\r\nGần Phủ Tây Hồ và Bảo tàng Dân tộc học.', 432000, 485000, 0, 2, 1, 1, 4.6, NULL, 235, '2025-12-02 02:50:40', '2025-12-03 00:47:44'),
(7, 'Mochi Homestay Phố Cổ', 'Hoàn Kiếm', 'Hàng Bồ, Hoàn Kiếm', 'Phòng 4 người ngay trung tâm phố cổ, gần hồ Gươm.\r\nThiết kế có ban công xinh xắn ngắm phố phường.\r\nPhù hợp cho gia đình hoặc nhóm bạn 5-6 người.', 600000, 600000, 100000, 6, 1, 3, 4.3, NULL, 252, '2025-12-02 02:50:40', '2025-12-03 00:48:30'),
(8, 'Hà Nội Central Hotel', 'Hoàn Kiếm', 'Hàng Cót, Hoàn Kiếm', 'Khách sạn phong cách truyền thống Hà Nội.\r\nGiá bao gồm ăn sáng, đồ uống chào mừng, sử dụng phòng Gym.\r\nCách chợ Đồng Xuân chỉ vài bước chân.', 1700000, 1700000, 0, 2, 1, 1, 4.5, NULL, 259, '2025-12-02 02:50:40', '2025-12-03 00:49:49'),
(9, 'L\'amant De Hanoi Hotel', 'Hoàn Kiếm', 'Nguyễn Hữu Huân, Hoàn Kiếm', 'Khách sạn boutique sang trọng, gần Nhà hát múa rối nước Thăng Long.\r\nDịch vụ lễ tân 24h, đưa đón sân bay.\r\nPhòng đầy đủ tiện nghi tiêu chuẩn quốc tế.', 1300000, 1300000, 0, 2, 1, 1, 4.8, NULL, 238, '2025-12-02 02:50:40', '2025-12-03 00:51:01'),
(10, 'LH Homestay Hàng Giấy', 'Hoàn Kiếm', 'Hàng Giấy, Đồng Xuân', 'Căn hộ khép kín có bồn tắm, phù hợp cặp đôi.\r\nNằm gần chợ Đồng Xuân, Bốt Hàng Đậu, Ga Long Biên.\r\nBếp nấu ăn đầy đủ dụng cụ.', 1000000, 1000000, 0, 2, 1, 1, 4.2, NULL, 112, '2025-12-02 02:50:40', '2025-12-03 00:51:46'),
(11, 'HNC Premier Hotel Penthouse', 'Hoàn Kiếm', '8 Lý Nam Đế, Hoàn Kiếm', 'Penthouse view panorama, 2 phòng ngủ king size, bếp lớn.\r\nCách Ô Quan Chưởng chưa đến 1km.\r\nSân hiên rộng nhìn ra toàn cảnh thành phố.', 2900000, 2900000, 0, 4, 2, 2, 4.9, NULL, 128, '2025-12-02 02:50:40', '2025-12-03 00:52:51'),
(12, 'LH Homestay Hàng Bông', 'Hoàn Kiếm', 'Hàng Bông, Cửa Nam', 'Căn hộ 1PN có bồn tắm ngay phòng khách cực chill.\r\nGần Văn Miếu, Nhà Thờ Lớn.\r\nDiện tích 40m2, thiết kế hiện đại.', 900000, 1000000, 0, 2, 1, 1, 4.6, NULL, 294, '2025-12-02 02:50:40', '2025-12-03 00:54:10'),
(13, 'Minasi Premium Hotel', 'Ba Đình', 'Nguyễn Trường Tộ, Ba Đình', 'Khách sạn 4 sao gần hồ Trúc Bạch, tiện nghi sang trọng.\r\nNhà hàng, dịch vụ tiền sảnh, thu đổi ngoại tệ.\r\nPhòng nghỉ gắn máy điều hòa, TV màn hình phẳng.', 1400000, 1400000, 0, 2, 1, 1, 4.4, NULL, 206, '2025-12-02 02:50:40', '2025-12-03 00:55:41'),
(14, 'Alaya Apartment Đào Tấn', 'Ba Đình', 'Đào Tấn, Cống Vị', 'Căn hộ studio hiện đại, cửa kính lớn, view thoáng.\r\nCó bếp, nồi cơm điện, tủ lạnh.\r\nPhù hợp khách công tác hoặc cặp đôi.', 547000, 565000, 0, 2, 1, 1, 4.5, NULL, 138, '2025-12-02 02:50:40', '2025-12-03 01:08:57'),
(15, 'The Autumn Homestay', 'Ba Đình', 'Trúc Bạch, Ba Đình', 'Nằm trên ốc đảo Trúc Bạch, không gian xanh, view hồ thơ mộng.\nĐiểm nhấn là quán bar và rooftop view panorama ra hồ.\nKhông gian yên bình giữa lòng phố thị.', 600000, 600000, 0, 2, 1, 1, 4.7, NULL, 64, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(16, 'BHome Apartment 701', 'Ba Đình', 'Kim Mã, Ba Đình', 'Căn hộ hiện đại 37m2, có bồn tắm riêng, gần Lotte Center.\nKhu bếp chung lớn đầy đủ tiện nghi.\nAn ninh 24/7, có chỗ để xe máy.', 317000, 327000, 0, 4, 2, 2, 4.3, NULL, 188, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(17, 'En’s Homestay Quán Thánh', 'Ba Đình', '150 Quán Thánh, Ba Đình', 'Vị trí trung tâm văn hóa, gần Lăng Bác và Hoàng Thành.\nThiết kế nhỏ xinh, ấm cúng như gia đình.\nKhách có thể tự check-in.', 467000, 526000, 0, 2, 1, 1, 4.6, NULL, 157, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(18, 'Lovely Lake View Apt', 'Ba Đình', 'Phạm Huy Thông, Ba Đình', 'Căn hộ view hồ Ngọc Khánh, gần Lotte và sở thú Thủ Lệ.\nRộng 65m2, phòng khách sofa lớn, TV 40 inch.\nĐầy đủ máy giặt, máy sấy miễn phí.', 673500, 680000, 0, 2, 1, 1, 4.5, NULL, 211, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(19, 'Nhà Bên Rừng Ulesa - Lemon Tree', 'Sóc Sơn', 'Minh Phú, Sóc Sơn', 'Biệt thự nghỉ dưỡng giữa rừng thông, bể bơi, BBQ.\nGồm 3 phòng ngủ riêng + 1 phòng dorm.\nSân vườn rộng, bàn ăn ngoài trời, bể bơi bên hồ.', 5300000, 9500000, 250000, 20, 4, 11, 4.8, NULL, 287, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(20, 'Omely Villa - 7PN', 'Sóc Sơn', 'Minh Phú, Sóc Sơn', 'Biệt thự 4000m2, bể bơi riêng, sân cỏ team building.\nPhòng khách ốp gỗ sa mộc thơm, lò sưởi ấm cúng.\nKaraoke, Bi-a, khu vui chơi trẻ em.', 16000000, 18000000, 300000, 50, 7, 12, 4.2, NULL, 209, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(21, 'CANA Glamping & Homestay', 'Sóc Sơn', 'Hồ Đồng Đò, Sóc Sơn', 'Khuôn viên trên đồi thông, view trọn hồ Đồng Đò.\nBao gồm 1 căn nhà kính và các lều glamping.\nMiễn phí chèo thuyền SUP, ăn sáng.', 1800000, 2200000, 0, 6, 2, 2, 4.9, NULL, 177, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(22, 'Mộc Hương Villa', 'Sóc Sơn', 'Thanh Lãm, Sóc Sơn', 'Khuôn viên 6500m2, bể bơi view cánh đồng.\n3 phòng ngủ khép kín.\nTiện ích: Kayak, Bi-a, Loa kéo, Lửa trại.', 4000000, 4500000, 0, 15, 3, 7, 4.5, NULL, 247, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(23, 'Cheery House 7PN', 'Sóc Sơn', 'Minh Phú, Sóc Sơn', 'Khu nghỉ dưỡng 1200m2, bể bơi, bi-a, xe đạp, BBQ.\nGồm 1 villa lớn và 4 bungalow.\nPhù hợp đoàn đông người.', 10400000, 12480000, 200000, 26, 7, 9, 4.1, NULL, 113, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(24, '72 Lake House - Nguyên Khu', 'Ba Vì', 'Xuân Hòa, Vân Hòa, Ba Vì', 'Villa ven hồ tuyệt đẹp, 6 phòng ngủ, bể bơi vô cực.\nKhu nhà kính độc đáo.\nMiễn phí Bàn Bi-a, bếp nướng BBQ.', 10300000, 12700000, 250000, 35, 6, 10, 4.7, NULL, 108, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(25, 'Casa La Mita - Khu 5PN', 'Ba Vì', 'Yên Bài, Ba Vì', 'Villa sân vườn rộng, thiết kế hiện đại, bể bơi lớn.\nPhòng ngủ áp mái view đẹp, phòng tập thể giường tầng.\nTV 86 inch, tủ lạnh Side by side.', 6800000, 8800000, 0, 25, 5, 8, 4.9, NULL, 189, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(26, 'Thung Mây Resort - Nhà sàn', 'Ba Vì', 'Vân Hòa, Ba Vì', 'Nhà sàn tập thể rộng rãi, bể bơi chung, không gian xanh mát.\nThích hợp cho đoàn sinh viên, họp lớp.\nDịch vụ ăn uống, lửa trại, loa kéo.', 3500000, 4000000, 200000, 18, 1, 4, 4, NULL, 33, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(27, 'Mường Dy Retreat Bungalow', 'Ba Vì', 'Minh Quang, Ba Vì', 'Bungalow riêng tư, view cánh đồng, bể bơi, không gian văn hóa.\nKiến trúc nhà sàn, bungalow gỗ.\nMiễn phí: Bi-a, bi-lac, xe đạp.', 1190000, 1190000, 0, 2, 1, 1, 4.4, NULL, 170, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(28, 'Quốc Bảo Villa 6PN', 'Ba Vì', 'Hồ Thiên Nga, Yên Bài', 'Villa 2ha view hồ, bể bơi vách kính độc đáo.\nTổng 6 phòng ngủ.\nSân cỏ Teambuilding rộng 250m2.', 5900000, 8000000, 200000, 20, 6, 12, 4.7, NULL, 159, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(29, 'Sơn Tây Mansion - Villa 4PN', 'Sơn Tây', 'Cổ Đông, Sơn Tây', 'Biệt thự sang trọng, bể bơi riêng, sân vườn rộng 6000m2.\nVilla 01 (4PN) thiết kế hiện đại.\nMiễn phí Bi-a, Bể bơi riêng.', 7000000, 10500000, 200000, 30, 4, 10, 4.8, NULL, 278, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(30, 'Helena Garden - Nguyên Khu', 'Sơn Tây', 'Kim Sơn, Sơn Tây', 'Khu nghỉ dưỡng 4000m2, bể bơi siêu rộng (8x20m).\nGồm 1 villa 7PN và 3 bungalow.\nPhòng Karaoke, Bi-a riêng biệt.', 10300000, 12700000, 300000, 50, 10, 12, 4.5, NULL, 32, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(31, 'Nhà của An Homestay', 'Sơn Tây', 'Cam Thượng, Sơn Tây', 'Kiến trúc đá ong cổ kính, bể bơi riêng.\nKhuôn viên khổng lồ, thảm cỏ xanh mướt.\nCách làng cổ Đường Lâm 2km.', 3500000, 4000000, 150000, 15, 5, 4, 4.9, NULL, 186, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(32, 'Đường Lâm House', 'Sơn Tây', 'Đường Lâm, Sơn Tây', 'Nhà riêng 250m2, 2 phòng ngủ, không gian yên tĩnh.\nPhù hợp gia đình nhỏ nghỉ dưỡng cuối tuần.\nGiá đã bao gồm bữa sáng.', 1350000, 1400000, 0, 6, 2, 2, 4.6, NULL, 245, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(33, 'Đồng Mô Glamping Stay', 'Sơn Tây', 'Sơn Đông, Sơn Tây', 'Nhà chính 2PN, bể bơi 4 mùa, sân bóng rổ.\r\nCách trung tâm Hà Nội 40km.\r\nMiễn phí xe đạp, bàn bi-lac.', 4000000, 5000000, 150000, 15, 2, 2, 4.4, NULL, 77, '2025-12-02 02:50:40', '2025-12-02 02:50:40'),
(34, 'An Bình Homestay', 'Sơn Tây', 'Cổ Đông, Sơn Tây', 'Kiến trúc nhà cổ Bắc Bộ, bể bơi nước mặn đá ong độc đáo.\r\nKhông gian mở với bộ trường kỷ truyền thống.\r\nVườn cây ăn quả xanh mát.', 4000000, 5500000, 150000, 15, 3, 4, 4.5, NULL, 222, '2025-12-02 02:50:40', '2025-12-02 02:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `image_id` int(11) NOT NULL,
  `homestay_id` int(10) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`image_id`, `homestay_id`, `image_path`, `deleted_at`, `is_primary`) VALUES
(7, 3, '1764722670_0_M Library1.jpg', NULL, 0),
(8, 3, '1764722670_1_M Library2.jpg', NULL, 0),
(9, 3, '1764722670_2_M Library3.jpg', NULL, 0),
(10, 4, '1764722741_0_Hygge house1.jpg', NULL, 0),
(11, 4, '1764722741_1_Hygge house2.jpg', NULL, 0),
(12, 4, '1764722741_2_Hygge house3.jpg', NULL, 0),
(13, 5, '1764722807_0_nice lakeview1.jpg', NULL, 0),
(14, 5, '1764722807_1_nice lakeview2.jpg', NULL, 0),
(15, 5, '1764722807_2_nice lakeview3.jpg', NULL, 0),
(16, 6, '1764722864_0_minimalism1.jpg', NULL, 0),
(17, 6, '1764722864_1_minimalism2.jpg', NULL, 0),
(18, 7, '1764722910_0_mochi1.jpg', NULL, 0),
(19, 7, '1764722910_1_mochi2.jpg', NULL, 0),
(20, 8, '1764722989_0_central hotel1.jpg', NULL, 0),
(21, 8, '1764722989_1_central hotel2.jpg', NULL, 0),
(22, 8, '1764722989_2_central hotel3.jpg', NULL, 0),
(23, 9, '1764723061_0_de hanoi1.jpg', NULL, 0),
(24, 9, '1764723061_1_de hanoi2.jpg', NULL, 0),
(25, 9, '1764723061_2_de hanoi3.jpg', NULL, 0),
(26, 10, '1764723106_0_lh1.jpg', NULL, 0),
(27, 10, '1764723106_1_lh2.jpg', NULL, 0),
(28, 10, '1764723106_2_lh3.jpg', NULL, 0),
(29, 11, '1764723171_0_hnc premier1.jpg', NULL, 0),
(30, 11, '1764723171_1_hnc premier2.jpg', NULL, 0),
(31, 11, '1764723171_2_hnc premier3.jpg', NULL, 0),
(32, 12, '1764723250_0_lh_hangbong1.jpg', NULL, 0),
(33, 12, '1764723250_1_lh_hangbong2.jpg', NULL, 0),
(34, 12, '1764723250_2_lh_hangbong3.jpg', NULL, 0),
(35, 13, '1764723341_0_minasi1.jpg', NULL, 0),
(36, 13, '1764723341_1_minasi2.jpg', NULL, 0),
(37, 13, '1764723341_2_minasi3.jpg', NULL, 0),
(38, 14, '1764724137_0_alaya1.jpg', NULL, 0),
(39, 14, '1764724137_1_alaya2.jpg', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `content` longtext NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `created_at` date DEFAULT curdate(),
  `author` varchar(50) DEFAULT 'Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `category`, `description`, `content`, `image_url`, `created_at`, `author`) VALUES
(1, 'Khám phá Hội An: Không chỉ có đèn lồng và phố cổ', 'Điểm đến', 'Hội An luôn mang một vẻ đẹp trầm mặc, cổ kính. Nhưng bạn có biết, nơi đây còn ẩn chứa những góc check-in bí mật?', 'Hội An không chỉ có những ngôi nhà vàng hay đèn lồng rực rỡ. Hãy thử một lần dậy sớm đạp xe ra biển An Bàng, thưởng thức bánh mì Phượng hay ngồi thuyền thả hoa đăng trên sông Hoài...\n\n(Nội dung chi tiết bài viết sẽ dài hơn ở đây...)', 'https://images.unsplash.com/photo-1528127269322-539801943592?q=80&w=1920', '2025-11-28', 'Admin'),
(2, '5 Mẹo săn Homestay giá rẻ tại Đà Lạt mùa lễ hội', 'Kinh nghiệm', 'Đà Lạt luôn cháy phòng dịp lễ. Làm sao để vừa có chỗ ở view đẹp, vừa không bị chặt chém?', '1. Đặt phòng sớm ít nhất 1 tháng.\n2. Săn deal trên các hội nhóm uy tín.\n3. Tránh các khu vực quá trung tâm như chợ Đà Lạt.\n4. Đi nhóm đông để share tiền phòng.\n5. Chọn homestay xa trung tâm một chút để có view đồi thông đẹp hơn.', 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=800', '2025-11-25', 'Admin'),
(3, 'Top 10 món ngon không thể bỏ qua khi đến Hà Nội', 'Ẩm thực', 'Phở, bún chả, chả cá Lã Vọng... Hà Nội là thiên đường ẩm thực. Đừng bỏ lỡ danh sách này.', 'Danh sách các quán ăn ngon:\n1. Phở Lý Quốc Sư\n2. Bún chả Hương Liên (Bún chả Obama)\n3. Chả cá Lã Vọng\n4. Bún đậu mắm tôm Hàng Khay\n5. Cà phê trứng Giảng...', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=800', '2025-11-20', 'Admin'),
(4, 'Kinh nghiệm cắm trại qua đêm tại Hồ Đồng Đò', 'Cẩm nang', 'Cuối tuần đi trốn khói bụi thành phố. Hướng dẫn chi tiết đường đi, thuê lều trại.', 'Hồ Đồng Đò thuộc huyện Sóc Sơn, cách Hà Nội khoảng 40km. Bạn có thể di chuyển bằng xe máy hoặc ô tô. Tại đây có dịch vụ cho thuê lều trại, bếp nướng BBQ và chèo thuyền SUP cực chill.', 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?q=80&w=800', '2025-11-18', 'Admin'),
(5, 'Review chi tiết 3 Villa có bể bơi đẹp nhất Sóc Sơn', 'Review', 'Tổng hợp những căn biệt thự chanh sả, có bể bơi vô cực view rừng thông.', '1. Ulesa - Nhà Bên Rừng: Thiết kế độc đáo, hòa mình vào thiên nhiên.\n2. Amaya Home: Bể bơi vô cực view núi tuyệt đẹp.\n3. De\'bay Villa: Kiến trúc châu Âu sang trọng giữa rừng thông.', 'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?q=80&w=800', '2025-11-15', 'Admin'),
(6, 'Săn mây Tà Xùa: Đi mùa nào đẹp nhất?', 'Check-in', 'Hành trình chinh phục sống lưng khủng long và biển mây bồng bềnh.', 'Thời điểm săn mây đẹp nhất là từ tháng 10 đến tháng 4 năm sau. Bạn nên đi xe khách lên thị trấn Bắc Yên rồi thuê xe máy lên Tà Xùa để đảm bảo an toàn.', 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?q=80&w=800', '2025-11-10', 'Admin'),
(7, 'Bí kíp tổ chức tiệc BBQ ngoài trời tại Homestay', 'Tips', 'Chuẩn bị thực đơn, ướp thịt sao cho ngon, cách nhóm than nhanh.', 'Để có buổi tiệc nướng ngon, bạn cần chuẩn bị:\n- Thịt ba chỉ bò, nầm heo, cánh gà.\n- Rau củ: Ngô, khoai, đậu bắp.\n- Gia vị ướp đồ nướng (sốt BBQ).\n- Than hoa không khói.', 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=800', '2025-11-05', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `qtrivien`
--

CREATE TABLE `qtrivien` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager','staff') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `qtrivien`
--

INSERT INTO `qtrivien` (`id`, `username`, `fullname`, `email`, `password`, `role`, `created_at`, `updated_at`, `last_login`, `status`) VALUES
(1, 'admin', 'lâm cout', 'admin@homestay.com', '$2y$10$QX/4TvFCmFTHZSV6MPb22ujJLG3tO.OgBn2CamSmH9r0DicJyPPBu', 'admin', '2025-11-28 02:37:47', '2025-11-28 02:37:47', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL COMMENT 'Tên đăng nhập',
  `email` varchar(100) NOT NULL COMMENT 'Email',
  `password` varchar(255) NOT NULL COMMENT 'Mật khẩu đã hash',
  `fullname` varchar(100) NOT NULL COMMENT 'Họ và tên',
  `phone` varchar(20) DEFAULT NULL COMMENT 'Số điện thoại',
  `address` text DEFAULT NULL COMMENT 'Địa chỉ',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn ảnh đại diện',
  `role` enum('customer','vip') DEFAULT 'customer' COMMENT 'Loại khách hàng',
  `status` enum('active','inactive','banned') DEFAULT 'active' COMMENT 'Trạng thái tài khoản',
  `email_verified` tinyint(1) DEFAULT 0 COMMENT 'Email đã xác thực chưa',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày đăng ký',
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `last_login` timestamp NULL DEFAULT NULL COMMENT 'Lần đăng nhập gần nhất'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng khách hàng';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `fullname`, `phone`, `address`, `avatar`, `role`, `status`, `email_verified`, `created_at`, `deleted_at`, `updated_at`, `last_login`) VALUES
(3, 'lamc62501', '2311060394@hunre.edu.vn', '$2y$10$Pl.kuxlKULjQ6aXfaFmnPOtu32dLBN2rMb.SZyoMx5Op/6aUJWVL.', 'lam cout', '0947626662', NULL, NULL, 'vip', 'active', 0, '2025-12-01 11:08:01', '2025-12-03 02:27:40', '2025-12-02 19:27:40', NULL),
(4, 'lamc6250', 'lamc6250@gmail.com', '$2y$10$FR3tQtF0sl4/rxyqu.mCp.3pNojQr2XvHv9WQ0rlopgLnbJBqpwrW', 'lam cout', '0373654414', 'hà nội', NULL, 'customer', 'active', 0, '2025-12-02 18:44:03', NULL, '2025-12-02 18:55:11', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dates` (`check_in`,`check_out`),
  ADD KEY `fk_bookings_usersv2` (`user_id`),
  ADD KEY `fk_bookings_homestaysv2` (`homestay_id`);

--
-- Indexes for table `homestays`
--
ALTER TABLE `homestays`
  ADD PRIMARY KEY (`homestay_id`),
  ADD KEY `idx_district` (`district`),
  ADD KEY `idx_price` (`price_weekday`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `idx_primary` (`is_primary`),
  ADD KEY `fk_images_homestaysv2` (`homestay_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qtrivien`
--
ALTER TABLE `qtrivien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `homestays`
--
ALTER TABLE `homestays`
  MODIFY `homestay_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `qtrivien`
--
ALTER TABLE `qtrivien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_homestays` FOREIGN KEY (`homestay_id`) REFERENCES `homestays` (`homestay_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_homestaysv2` FOREIGN KEY (`homestay_id`) REFERENCES `homestays` (`homestay_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_usersv2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `fk_images_homestaysv2` FOREIGN KEY (`homestay_id`) REFERENCES `homestays` (`homestay_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`homestay_id`) REFERENCES `homestays` (`homestay_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
