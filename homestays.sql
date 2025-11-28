-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 28, 2025 lúc 02:43 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `homestays`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `homestay_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_price` decimal(15,0) NOT NULL,
  `guests_count` int(11) DEFAULT 1,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `homestay_id`, `check_in`, `check_out`, `total_price`, `guests_count`, `status`, `created_at`) VALUES
(1, 3, 34, '2025-10-30', '2025-11-01', 8150000, 1, 'pending', '2025-11-28 01:14:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `homestays`
--

CREATE TABLE `homestays` (
  `id` int(11) NOT NULL,
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
  `main_image` varchar(500) DEFAULT NULL,
  `rating` float DEFAULT 5,
  `num_reviews` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `homestays`
--

INSERT INTO `homestays` (`id`, `name`, `district`, `address`, `description`, `price_weekday`, `price_weekend`, `price_extra_guest`, `max_guests`, `num_bedrooms`, `num_beds`, `main_image`, `rating`, `num_reviews`) VALUES
(1, 'Hanoi Home 2 - View Hồ Tây', 'Tây Hồ', 'Làng Yên Phụ, Tây Hồ', 'Căn hộ dịch vụ view Hồ Tây, miễn phí xe đạp. Đầy đủ tiện nghi máy lạnh, máy sưởi ấm cho mùa đông, đồ nấu ăn, hệ thống bếp, máy giặt sấy...\nPhù hợp cho cặp vợ chồng, người thích ngắm cảnh, không gian yên tĩnh.', 700000, 850000, 0, 2, 1, 1, 'hanoi_home_2.jpg', 4.8, 206),
(2, 'Dudy\'s House - 1PN', 'Tây Hồ', '20 Mạc Đĩnh Chi, Trúc Bạch', 'Nằm trung tâm Đảo Ngọc Trúc Bạch, cách hồ Tây 5 phút đi bộ.\nDiện tích 60m2, gồm 1 phòng ngủ, 1 phòng khách, bếp nấu ăn.\nTrang bị: Điều hòa, Smart TV, Sofa bed, Thang máy.', 950000, 1100000, 200000, 3, 1, 1, 'dudys_house.jpg', 4.5, 102),
(3, 'M Library Apartment - Navy Penthouse', 'Tây Hồ', 'Tây Hồ, Hà Nội', 'Căn hộ Penthouse sang trọng, view thành phố.\nVị trí đắc địa giữa trung tâm quận Tây Hồ, không gian yên tĩnh, đẳng cấp 5 sao.\nCách Thung lũng hoa Hồ Tây 1.4km.', 1790000, 1950000, 0, 2, 1, 1, 'mlibrary.jpg', 5, 174),
(4, 'Hygge House Hồ Tây', 'Tây Hồ', 'Âu Cơ, Tây Hồ', 'Không gian xanh mát, nguyên khu sân vườn rộng, thích hợp BBQ, party.\nThiết kế đặc biệt gần gũi thiên nhiên. Phòng khách bài trí ghế sofa thổ cẩm.\nKhuôn viên có thể chứa tối đa 20 khách.', 5700000, 6500000, 150000, 20, 3, 3, 'hygge_house.jpg', 4.7, 264),
(5, 'Nice Lakeview Apartment', 'Tây Hồ', 'Thụy Khuê, Tây Hồ', 'Căn hộ 80m2 view hồ, ban công rộng, đầy đủ tiện nghi.\nPhong cách hiện đại, đơn giản với tông màu trắng chủ đạo.\nTiện ích tòa nhà: Bể bơi, Gym, Tennis.', 1829900, 1416870, 0, 3, 2, 2, 'nice_lakeview.jpg', 4.4, 207),
(6, 'Minimalism Home - Balcony', 'Tây Hồ', 'Lạc Long Quân, Bưởi', 'Cách Hồ Tây 3 phút đi bộ, nội thất thủ công đơn giản, yên tĩnh.\nKhông gian nội thất mô phỏng văn hóa các dân tộc vùng Tây Bắc.\nGần Phủ Tây Hồ và Bảo tàng Dân tộc học.', 432000, 485000, 0, 2, 1, 1, 'minimalism_home.jpg', 4.6, 235),
(7, 'Mochi Homestay Phố Cổ', 'Hoàn Kiếm', 'Hàng Bồ, Hoàn Kiếm', 'Phòng 4 người ngay trung tâm phố cổ, gần hồ Gươm.\nThiết kế có ban công xinh xắn ngắm phố phường.\nPhù hợp cho gia đình hoặc nhóm bạn 5-6 người.', 600000, 600000, 100000, 6, 1, 3, 'mochi_homestay.jpg', 4.3, 252),
(8, 'Hà Nội Central Hotel', 'Hoàn Kiếm', 'Hàng Cót, Hoàn Kiếm', 'Khách sạn phong cách truyền thống Hà Nội.\nGiá bao gồm ăn sáng, đồ uống chào mừng, sử dụng phòng Gym.\nCách chợ Đồng Xuân chỉ vài bước chân.', 1700000, 1700000, 0, 2, 1, 1, 'hanoi_central.jpg', 4.5, 259),
(9, 'L\'amant De Hanoi Hotel', 'Hoàn Kiếm', 'Nguyễn Hữu Huân, Hoàn Kiếm', 'Khách sạn boutique sang trọng, gần Nhà hát múa rối nước Thăng Long.\nDịch vụ lễ tân 24h, đưa đón sân bay.\nPhòng đầy đủ tiện nghi tiêu chuẩn quốc tế.', 1300000, 1300000, 0, 2, 1, 1, 'lamant_hotel.jpg', 4.8, 238),
(10, 'LH Homestay Hàng Giấy', 'Hoàn Kiếm', 'Hàng Giấy, Đồng Xuân', 'Căn hộ khép kín có bồn tắm, phù hợp cặp đôi.\nNằm gần chợ Đồng Xuân, Bốt Hàng Đậu, Ga Long Biên.\nBếp nấu ăn đầy đủ dụng cụ.', 1000000, 1000000, 0, 2, 1, 1, 'lh_hanggiay.jpg', 4.2, 112),
(11, 'HNC Premier Hotel Penthouse', 'Hoàn Kiếm', '8 Lý Nam Đế, Hoàn Kiếm', 'Penthouse view panorama, 2 phòng ngủ king size, bếp lớn.\nCách Ô Quan Chưởng chưa đến 1km.\nSân hiên rộng nhìn ra toàn cảnh thành phố.', 2900000, 2900000, 0, 4, 2, 2, 'hnc_penthouse.jpg', 4.9, 128),
(12, 'LH Homestay Hàng Bông', 'Hoàn Kiếm', 'Hàng Bông, Cửa Nam', 'Căn hộ 1PN có bồn tắm ngay phòng khách cực chill.\nGần Văn Miếu, Nhà Thờ Lớn.\nDiện tích 40m2, thiết kế hiện đại.', 900000, 1000000, 0, 2, 1, 1, 'lh_hangbong.jpg', 4.6, 294),
(13, 'Minasi Premium Hotel', 'Ba Đình', 'Nguyễn Trường Tộ, Ba Đình', 'Khách sạn 4 sao gần hồ Trúc Bạch, tiện nghi sang trọng.\nNhà hàng, dịch vụ tiền sảnh, thu đổi ngoại tệ.\nPhòng nghỉ gắn máy điều hòa, TV màn hình phẳng.', 1400000, 1400000, 0, 2, 1, 1, 'minasi_premium.jpg', 4.4, 206),
(14, 'Alaya Apartment Đào Tấn', 'Ba Đình', 'Đào Tấn, Cống Vị', 'Căn hộ studio hiện đại, cửa kính lớn, view thoáng.\nCó bếp, nồi cơm điện, tủ lạnh.\nPhù hợp khách công tác hoặc cặp đôi.', 547000, 565000, 0, 2, 1, 1, 'alaya_daotan.jpg', 4.5, 138),
(15, 'The Autumn Homestay', 'Ba Đình', 'Trúc Bạch, Ba Đình', 'Nằm trên ốc đảo Trúc Bạch, không gian xanh, view hồ thơ mộng.\nĐiểm nhấn là quán bar và rooftop view panorama ra hồ.\nKhông gian yên bình giữa lòng phố thị.', 600000, 600000, 0, 2, 1, 1, 'the_autumn.jpg', 4.7, 64),
(16, 'BHome Apartment 701', 'Ba Đình', 'Kim Mã, Ba Đình', 'Căn hộ hiện đại 37m2, có bồn tắm riêng, gần Lotte Center.\nKhu bếp chung lớn đầy đủ tiện nghi.\nAn ninh 24/7, có chỗ để xe máy.', 317000, 327000, 0, 4, 2, 2, 'bhome_701.jpg', 4.3, 188),
(17, 'En’s Homestay Quán Thánh', 'Ba Đình', '150 Quán Thánh, Ba Đình', 'Vị trí trung tâm văn hóa, gần Lăng Bác và Hoàng Thành.\nThiết kế nhỏ xinh, ấm cúng như gia đình.\nKhách có thể tự check-in.', 467000, 526000, 0, 2, 1, 1, 'ens_homestay.jpg', 4.6, 157),
(18, 'Lovely Lake View Apt', 'Ba Đình', 'Phạm Huy Thông, Ba Đình', 'Căn hộ view hồ Ngọc Khánh, gần Lotte và sở thú Thủ Lệ.\nRộng 65m2, phòng khách sofa lớn, TV 40 inch.\nĐầy đủ máy giặt, máy sấy miễn phí.', 673500, 680000, 0, 2, 1, 1, 'lovely_lakeview.jpg', 4.5, 211),
(19, 'Nhà Bên Rừng Ulesa - Lemon Tree', 'Sóc Sơn', 'Minh Phú, Sóc Sơn', 'Biệt thự nghỉ dưỡng giữa rừng thông, bể bơi, BBQ.\nGồm 3 phòng ngủ riêng + 1 phòng dorm.\nSân vườn rộng, bàn ăn ngoài trời, bể bơi bên hồ.', 5300000, 9500000, 250000, 20, 4, 11, 'ulesa_lemon.jpg', 4.8, 287),
(20, 'Omely Villa - 7PN', 'Sóc Sơn', 'Minh Phú, Sóc Sơn', 'Biệt thự 4000m2, bể bơi riêng, sân cỏ team building.\nPhòng khách ốp gỗ sa mộc thơm, lò sưởi ấm cúng.\nKaraoke, Bi-a, khu vui chơi trẻ em.', 16000000, 18000000, 300000, 50, 7, 12, 'omely_villa.jpg', 4.2, 209),
(21, 'CANA Glamping & Homestay', 'Sóc Sơn', 'Hồ Đồng Đò, Sóc Sơn', 'Khuôn viên trên đồi thông, view trọn hồ Đồng Đò.\nBao gồm 1 căn nhà kính và các lều glamping.\nMiễn phí chèo thuyền SUP, ăn sáng.', 1800000, 2200000, 0, 6, 2, 2, 'cana_glamping.jpg', 4.9, 177),
(22, 'Mộc Hương Villa', 'Sóc Sơn', 'Thanh Lãm, Sóc Sơn', 'Khuôn viên 6500m2, bể bơi view cánh đồng.\n3 phòng ngủ khép kín.\nTiện ích: Kayak, Bi-a, Loa kéo, Lửa trại.', 4000000, 4500000, 0, 15, 3, 7, 'moc_huong.jpg', 4.5, 247),
(23, 'Cheery House 7PN', 'Sóc Sơn', 'Minh Phú, Sóc Sơn', 'Khu nghỉ dưỡng 1200m2, bể bơi, bi-a, xe đạp, BBQ.\nGồm 1 villa lớn và 4 bungalow.\nPhù hợp đoàn đông người.', 10400000, 12480000, 200000, 26, 7, 9, 'cheery_house.jpg', 4.1, 113),
(24, '72 Lake House - Nguyên Khu', 'Ba Vì', 'Xuân Hòa, Vân Hòa, Ba Vì', 'Villa ven hồ tuyệt đẹp, 6 phòng ngủ, bể bơi vô cực.\nKhu nhà kính độc đáo.\nMiễn phí Bàn Bi-a, bếp nướng BBQ.', 10300000, 12700000, 250000, 35, 6, 10, '72_lake_house.jpg', 4.7, 108),
(25, 'Casa La Mita - Khu 5PN', 'Ba Vì', 'Yên Bài, Ba Vì', 'Villa sân vườn rộng, thiết kế hiện đại, bể bơi lớn.\nPhòng ngủ áp mái view đẹp, phòng tập thể giường tầng.\nTV 86 inch, tủ lạnh Side by side.', 6800000, 8800000, 0, 25, 5, 8, 'casa_la_mita.jpg', 4.9, 189),
(26, 'Thung Mây Resort - Nhà sàn', 'Ba Vì', 'Vân Hòa, Ba Vì', 'Nhà sàn tập thể rộng rãi, bể bơi chung, không gian xanh mát.\nThích hợp cho đoàn sinh viên, họp lớp.\nDịch vụ ăn uống, lửa trại, loa kéo.', 3500000, 4000000, 200000, 18, 1, 4, 'thung_may.jpg', 4, 33),
(27, 'Mường Dy Retreat Bungalow', 'Ba Vì', 'Minh Quang, Ba Vì', 'Bungalow riêng tư, view cánh đồng, bể bơi, không gian văn hóa.\nKiến trúc nhà sàn, bungalow gỗ.\nMiễn phí: Bi-a, bi-lac, xe đạp.', 1190000, 1190000, 0, 2, 1, 1, 'muong_dy.jpg', 4.4, 170),
(28, 'Quốc Bảo Villa 6PN', 'Ba Vì', 'Hồ Thiên Nga, Yên Bài', 'Villa 2ha view hồ, bể bơi vách kính độc đáo.\nTổng 6 phòng ngủ.\nSân cỏ Teambuilding rộng 250m2.', 5900000, 8000000, 200000, 20, 6, 12, 'quoc_bao_villa.jpg', 4.7, 159),
(29, 'Sơn Tây Mansion - Villa 4PN', 'Sơn Tây', 'Cổ Đông, Sơn Tây', 'Biệt thự sang trọng, bể bơi riêng, sân vườn rộng 6000m2.\nVilla 01 (4PN) thiết kế hiện đại.\nMiễn phí Bi-a, Bể bơi riêng.', 7000000, 10500000, 200000, 30, 4, 10, 'sontay_mansion.jpg', 4.8, 278),
(30, 'Helena Garden - Nguyên Khu', 'Sơn Tây', 'Kim Sơn, Sơn Tây', 'Khu nghỉ dưỡng 4000m2, bể bơi siêu rộng (8x20m).\nGồm 1 villa 7PN và 3 bungalow.\nPhòng Karaoke, Bi-a riêng biệt.', 10300000, 12700000, 300000, 50, 10, 12, 'helena_garden.jpg', 4.5, 32),
(31, 'Nhà của An Homestay', 'Sơn Tây', 'Cam Thượng, Sơn Tây', 'Kiến trúc đá ong cổ kính, bể bơi riêng.\nKhuôn viên khổng lồ, thảm cỏ xanh mướt.\nCách làng cổ Đường Lâm 2km.', 3500000, 4000000, 150000, 15, 5, 4, 'nha_cua_an.jpg', 4.9, 186),
(32, 'Đường Lâm House', 'Sơn Tây', 'Đường Lâm, Sơn Tây', 'Nhà riêng 250m2, 2 phòng ngủ, không gian yên tĩnh.\nPhù hợp gia đình nhỏ nghỉ dưỡng cuối tuần.\nGiá đã bao gồm bữa sáng.', 1350000, 1400000, 0, 6, 2, 2, 'duonglam_house.jpg', 4.6, 245),
(33, 'Đồng Mô Glamping Stay', 'Sơn Tây', 'Sơn Đông, Sơn Tây', 'Nhà chính 2PN, bể bơi 4 mùa, sân bóng rổ.\nCách trung tâm Hà Nội 40km.\nMiễn phí xe đạp, bàn bi-lac.', 4000000, 5000000, 150000, 15, 2, 2, 'dongmo_glamping.jpg', 4.4, 77),
(34, 'An Bình Homestay', 'Sơn Tây', 'Cổ Đông, Sơn Tây', 'Kiến trúc nhà cổ Bắc Bộ, bể bơi nước mặn đá ong độc đáo.\nKhông gian mở với bộ trường kỷ truyền thống.\nVườn cây ăn quả xanh mát.', 4000000, 5500000, 150000, 15, 3, 4, 'anbinh_homestay.jpg', 4.5, 222);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
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
-- Đang đổ dữ liệu cho bảng `posts`
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
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`) VALUES
(1, 'Admin System', 'admin@homestay.com', '123456', 'admin');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `homestays`
--
ALTER TABLE `homestays`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `homestays`
--
ALTER TABLE `homestays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
