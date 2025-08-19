-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 17, 2025 at 05:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myphamdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia`
--

CREATE TABLE `danh_gia` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `san_pham_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `so_sao` tinyint(1) DEFAULT NULL CHECK (`so_sao` >= 1 and `so_sao` <= 5),
  `binh_luan` text DEFAULT NULL,
  `ngay_danh_gia` datetime DEFAULT current_timestamp(),
  `anh_minh_chung` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danh_gia`
--

INSERT INTO `danh_gia` (`id`, `order_id`, `san_pham_id`, `user_id`, `so_sao`, `binh_luan`, `ngay_danh_gia`, `anh_minh_chung`) VALUES
(1, 0, 2, 5, 1, 'khong co gi hay', '2025-08-12 14:45:55', NULL),
(2, 11, 2, 5, 3, 'deo on', '2025-08-15 00:29:05', 'uploads/reviews/1755192545_689e1ce13b2d5.jpg'),
(3, 12, 3, 1, 4, 'hang khong su dung duoc gi het, nhung ma developver gioi!', '2025-08-15 10:40:50', 'uploads/reviews/1755229250_689eac42c7bbe.jpg'),
(4, 13, 3, 1, 3, 'kcoj hay', '2025-08-15 11:13:38', NULL),
(5, 5, 6, 1, 4, 'dep', '2025-08-15 11:13:53', NULL),
(6, 4, 3, 1, 4, 'dep', '2025-08-15 11:14:04', NULL),
(7, 2, 3, 1, 4, 'xau', '2025-08-15 11:14:20', NULL),
(8, 1, 3, 1, 4, 'thay ghe', '2025-08-15 11:14:33', NULL),
(9, 5, 3, 1, 5, 'xau qua', '2025-08-15 11:14:58', NULL),
(10, 14, 3, 5, 3, 'khong co gi hay', '2025-08-15 11:37:03', 'uploads/reviews/1755232623_689eb96f758c8.jpg'),
(11, 10, 2, 5, 5, 'khong dang mua', '2025-08-15 11:39:29', 'assets/img/reviews/1755232769_689eba017800b.jpg'),
(12, 8, 2, 5, 3, 'khong cái nào sài được', '2025-08-15 11:45:04', 'assets/img/reviews/1755233104_689ebb504c4b1.jpg'),
(13, 22, 76, 5, 5, 'Sản phẩm tuyệt vời, da mình cải thiện rõ rệt sau 1 tuần sử dụng', '2025-08-16 09:15:22', 'uploads/reviews/review76.jpg'),
(14, 23, 77, 1, 4, 'Màu son đẹp, lì nhưng hơi khô môi một chút', '2025-08-16 10:30:45', NULL),
(15, 24, 78, 5, 5, 'Tóc mềm mượt hơn hẳn sau lần gội đầu tiên', '2025-08-16 14:20:33', 'uploads/reviews/review78.jpg'),
(16, 19, 79, 1, 3, 'Hiệu quả tốt nhưng giá hơi cao so với chất lượng', '2025-08-16 16:45:12', NULL),
(17, 18, 80, 5, 4, 'Kem dưỡng ẩm tốt, mùi thơm dễ chịu', '2025-08-16 18:10:55', 'uploads/reviews/review80.jpg'),
(18, 17, 81, 1, 5, 'Hương thơm sang trọng, lưu hương cả ngày', '2025-08-16 19:25:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc`
--

CREATE TABLE `danh_muc` (
  `ma_danh_muc` int(11) NOT NULL,
  `ten_danh_muc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danh_muc`
--

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`) VALUES
(1, 'Dưỡng da'),
(2, 'Trang điểm'),
(3, 'Chăm sóc tóc'),
(4, 'Mặt nạ'),
(5, 'Chăm sóc cơ thể'),
(6, 'Nước hoa'),
(7, 'Dụng cụ làm đẹp');

-- --------------------------------------------------------

--
-- Table structure for table `kho_hang`
--

CREATE TABLE `kho_hang` (
  `id` int(11) NOT NULL,
  `san_pham_id` int(11) NOT NULL,
  `so_luong_ton` int(11) NOT NULL DEFAULT 0,
  `vi_tri_kho` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kho_hang`
--

INSERT INTO `kho_hang` (`id`, `san_pham_id`, `so_luong_ton`, `vi_tri_kho`) VALUES
(1, 62, 3, '1'),
(2, 4, 30, 'A2'),
(3, 30, 1, '1'),
(4, 1, 8, 'A3'),
(5, 23, 30, 'A1'),
(6, 76, 15, 'B2'),
(7, 77, 8, 'C1'),
(8, 78, 12, 'A5'),
(9, 79, 20, 'D3'),
(10, 80, 35, 'E1'),
(11, 81, 5, 'F2'),
(12, 82, 10, 'G4'),
(13, 83, 18, 'B3'),
(14, 84, 6, 'C5'),
(15, 85, 25, 'A4');

-- --------------------------------------------------------

--
-- Table structure for table `khuyen_mai`
--

CREATE TABLE `khuyen_mai` (
  `id` int(11) NOT NULL,
  `ten_chuong_trinh` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `ngay_bat_dau` datetime NOT NULL,
  `ngay_ket_thuc` datetime NOT NULL,
  `muc_giam_gia` decimal(5,2) NOT NULL COMMENT 'Phần trăm giảm giá',
  `trang_thai` tinyint(1) DEFAULT 1 COMMENT '1: hoạt động, 0: tắt',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`id`, `ten_chuong_trinh`, `mo_ta`, `ngay_bat_dau`, `ngay_ket_thuc`, `muc_giam_gia`, `trang_thai`, `created_at`) VALUES
(1, 'Giờ vàng cuối tuần', 'Giảm giá 10% tất cả sản phẩm vào các ngày Thứ 6,7,CN từ 19h-23h', '2025-01-01 00:00:00', '2025-12-31 23:59:59', 10.00, 1, '2025-08-17 00:00:00'),
(2, 'Khuyến mãi mùa hè', 'Giảm giá 20% tất cả sản phẩm chăm sóc da', '2025-06-01 00:00:00', '2025-08-31 23:59:59', 20.00, 1, '2025-05-15 00:00:00'),
(3, 'Combo tiết kiệm', 'Mua 2 sản phẩm được giảm thêm 10%', '2025-01-01 00:00:00', '2025-12-31 23:59:59', 10.00, 1, '2025-01-01 00:00:00'),
(4, 'Ngày vàng thương hiệu', 'Giảm 30% sản phẩm của Laneige', '2025-09-15 00:00:00', '2025-09-17 23:59:59', 30.00, 0, '2025-08-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `ma_don` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `sdt` varchar(20) NOT NULL,
  `dia_chi` text NOT NULL,
  `phuong_xa` varchar(100) DEFAULT NULL,
  `khu_vuc` varchar(100) NOT NULL,
  `tong_tien` decimal(10,2) NOT NULL,
  `thoi_gian_dat` datetime DEFAULT current_timestamp(),
  `trang_thai` varchar(50) DEFAULT 'Chờ xác nhận',
  `ghi_chu` text DEFAULT NULL,
  `hinh_thuc_thanh_toan` varchar(50) DEFAULT NULL,
  `cancel_reason` text DEFAULT NULL,
  `cancel_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `ma_don`, `user_id`, `ho_ten`, `sdt`, `dia_chi`, `phuong_xa`, `khu_vuc`, `tong_tien`, `thoi_gian_dat`, `trang_thai`, `ghi_chu`, `hinh_thuc_thanh_toan`, `cancel_reason`, `cancel_time`) VALUES
(1, 'BC25081408254432CA06', 1, 'Nguyễn TN', 'sdjcb', 'sdmnc', '', '4', 700000.00, '2025-08-14 13:25:44', 'Đang xử lý', '0', 'MoMo', NULL, '2025-08-15 08:00:51'),
(2, 'BC2508140835336C9325', 1, 'Nguyễn Thiên Nam', '098777654', '78 võ oanh', '98', '4', 370000.00, '2025-08-14 13:35:33', 'Đang xử lý', '0', 'Bank Transfer', NULL, '2025-08-15 08:00:51'),
(3, 'BC2508140836205A7FB6', 1, 'Nguyễn Thiên Nam', 'sdjcb', 'sjdfkuej', 'dnjbchhs', 'dsjbcv', 300000.00, '2025-08-14 13:36:20', 'Đang xử lý', '0', 'COD', NULL, '2025-08-15 08:00:51'),
(4, 'BC25081408384908944B', 1, 'Nguyễn Thiên Nam', 'jbzbcx', 'smjbcs', 'jsdbc', 'nsdhjbc', 400000.00, '2025-08-14 13:38:49', 'Đang xử lý', '0', 'Bank Transfer', NULL, '2025-08-15 08:00:51'),
(5, 'BC250814152506691657', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường 14', 'Quận 4', 1415000.00, '2025-08-14 20:25:06', 'Đang xử lý', '0', 'COD', NULL, '2025-08-15 08:00:51'),
(6, 'BC250814153702103BF3', 5, 'Thiên Nam', '03881901703', 'v cbv vb g', 'Phường 5', 'Quận 3', 715000.00, '2025-08-14 20:37:02', 'Đang xử lý', '0', 'Bank Transfer', NULL, '2025-08-15 08:00:51'),
(7, 'BC250814163342B9A711', 5, 'Thiên Nam', '03881901704', '2 võ oanhhh', 'Phường Trường Thọ', 'Quận Thủ Đức', 215000.00, '2025-08-14 21:33:42', 'Đang xử lý', '0', 'COD', NULL, '2025-08-15 08:00:51'),
(8, 'BC250814183654004F86', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Tân Phú (Q7)', 'TP.HCM', 735000.00, '2025-08-14 23:36:54', 'Đang xử lý', '0', 'Bank Transfer', NULL, '2025-08-15 08:00:51'),
(9, 'BC250814190238426087', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Bình Thạnh', 'TP.HCM', 255000.00, '2025-08-15 00:02:38', 'Đang xử lý', '0', 'COD', NULL, '2025-08-15 08:00:51'),
(10, 'BC250814192159B6A3CE', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường An Phú', 'TP.HCM', 135000.00, '2025-08-15 00:21:59', 'Đang xử lý', '0', 'COD', NULL, '2025-08-15 08:00:51'),
(11, 'BC2508141924553FAF6D', 5, 'Thiên Nam', '03881901704', '2 võ oanhh', 'Phường Nguyễn Thái Bình', 'TP.HCM', 255000.00, '2025-08-15 00:24:55', 'Đang xử lý', '0', 'Bank Transfer', NULL, '2025-08-15 08:00:51'),
(12, 'BC2508150535158B5B34', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường Bình Khánh', 'TP.HCM', 265000.00, '2025-08-15 10:35:15', 'Đang xử lý', '0', 'Bank Transfer', NULL, '2025-08-15 08:00:51'),
(13, 'BC250815061324C32763', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường Tân Định', 'TP.HCM', 265000.00, '2025-08-15 11:13:24', 'Chờ xác nhận', '0', 'COD', NULL, '2025-08-15 08:31:42'),
(14, 'BC250815063640563D05', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Bình An', 'TP.HCM', 265000.00, '2025-08-15 11:36:40', 'Đang xử lý', '0', 'COD', NULL, '2025-08-15 08:00:51'),
(15, 'BC2508150713016D0BD9', 5, 'zatinzz', '03349565833', '12 võ oanh', 'Phường Thảo Điền', 'TP.HCM', 235000.00, '2025-08-15 12:13:01', 'Đã hủy', '0', 'COD', 'hủy', '2025-08-15 08:30:59'),
(16, 'BC250815095615472F29', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Tân Định', 'TP.HCM', 175000.00, '2025-08-15 14:56:15', 'Đã hủy', '0', 'COD', 'toi khong biet', '2025-08-15 08:25:08'),
(17, 'BC250815102831BAB92C', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Tân Định', 'TP.HCM', 265000.00, '2025-08-15 15:04:44', 'Đã hủy', '0', 'MoMo', '', '2025-08-15 08:35:44'),
(18, 'BC250815153946444AC0', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Thủ Thiêm', 'TP.HCM', 375000.00, '2025-08-15 15:39:46', 'Đang xử lý', '0', 'Bank Transfer', NULL, '2025-08-15 08:40:05'),
(19, 'BC250815172921691177', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Phạm Ngũ Lão', 'TP.HCM', 195000.00, '2025-08-15 17:03:12', 'Đang xử lý', '0', 'Bank Transfer', NULL, NULL),
(20, 'BC2508151738017B3980', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Thạnh Mỹ Lợi', 'TP.HCM', 135000.00, '2025-08-15 17:38:01', 'Đã hủy', '0', 'MoMo', '', NULL),
(21, 'BC2508151749134CC107', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Thủ Thiêm', 'TP.HCM', 265000.00, '2025-08-15 17:18:57', 'Đang xử lý', '0', 'Bank Transfer', NULL, NULL),
(22, 'BC250815181046AE276A', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Thảo Điền', 'TP.HCM', 195000.00, '2025-08-15 17:40:24', 'Hoàn tất', '0', 'MoMo', '', NULL),
(23, 'BC25081518250015BE7C', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Bình An', 'TP.HCM', 265000.00, '2025-08-15 18:05:00', 'Hoàn tất', '0', 'Bank Transfer', NULL, NULL),
(24, 'BC250815183108582FCF', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Thạnh Mỹ Lợi', 'TP.HCM', 515000.00, '2025-08-15 18:07:08', 'Hoàn tất', '0', 'Bank Transfer', NULL, NULL),
(25, 'BC250816193345CC30AA', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường An Phú', 'TP.HCM', 265000.00, '2025-08-16 19:33:45', 'Đã hủy', '0', 'Bank Transfer', 'deo thich don nay', '2025-08-16 12:34:03'),
(26, 'BC2508171312466DE743', 7, 'Nemm', '0388190290', '45 hoàng hoa thám', 'Phường Thảo Điền', 'TP.HCM', 315000.00, '2025-08-17 13:12:46', 'Đã hủy', '0', 'Bank Transfer', 'đặt test', '2025-08-17 06:13:32');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `san_pham_id` int(11) NOT NULL,
  `so_luong` int(11) NOT NULL,
  `don_gia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `san_pham_id`, `so_luong`, `don_gia`) VALUES
(1, 1, 2, 1, 120000.00),
(2, 1, 1, 1, 150000.00),
(3, 1, 3, 1, 250000.00),
(4, 1, 10, 1, 180000.00),
(5, 2, 2, 1, 120000.00),
(6, 2, 3, 1, 250000.00),
(7, 3, 4, 1, 180000.00),
(8, 3, 2, 1, 120000.00),
(9, 4, 3, 1, 250000.00),
(10, 4, 1, 1, 150000.00),
(11, 5, 1, 2, 150000.00),
(12, 5, 3, 2, 250000.00),
(13, 5, 5, 5, 80000.00),
(14, 5, 6, 1, 200000.00),
(15, 6, 3, 2, 250000.00),
(16, 6, 6, 1, 200000.00),
(17, 7, 18, 1, 200000.00),
(18, 8, 2, 3, 120000.00),
(19, 8, 4, 2, 180000.00),
(20, 9, 2, 2, 120000.00),
(21, 10, 2, 1, 120000.00),
(22, 11, 2, 2, 120000.00),
(23, 12, 3, 1, 250000.00),
(24, 13, 3, 1, 250000.00),
(25, 14, 3, 1, 250000.00),
(26, 15, 2, 1, 120000.00),
(27, 15, 9, 1, 100000.00),
(28, 16, 5, 2, 80000.00),
(29, 17, 3, 1, 250000.00),
(30, 18, 4, 2, 180000.00),
(31, 19, 4, 1, 180000.00),
(32, 20, 2, 1, 120000.00),
(33, 21, 3, 1, 250000.00),
(34, 22, 4, 1, 180000.00),
(35, 23, 3, 1, 250000.00),
(36, 24, 3, 2, 250000.00),
(37, 25, 3, 1, 250000.00),
(38, 26, 1, 2, 150000.00);

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--

CREATE TABLE `san_pham` (
  `id` int(11) NOT NULL,
  `ten_san_pham` varchar(100) NOT NULL,
  `thuong_hieu` varchar(100) DEFAULT NULL,
  `phan_loai` varchar(50) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `thanh_phan` text DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `han_su_dung` date DEFAULT NULL,
  `gia` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `ma_danh_muc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `san_pham`
--

INSERT INTO `san_pham` (`id`, `ten_san_pham`, `thuong_hieu`, `phan_loai`, `mo_ta`, `thanh_phan`, `hinh_anh`, `han_su_dung`, `gia`, `is_available`, `ma_danh_muc`) VALUES
(1, 'Kem dưỡng trắng sáng 50g', 'Sakura', 'Dưỡng da và cấp ẩm', 'Kem dưỡng chứa tinh chất vitamin C và niacinamide giúp làm sáng và đều màu da, giảm thâm nám, mang lại làn da rạng rỡ.', 'Niacinamide, Glutathione,Nước, Glycerin, Paraben, Hương hoa nhẹ', 'sp01.jpg', '2026-12-31', 150000.00, 1, 1),
(2, 'Sữa rửa mặt chiết xuất trà xanh 100ml', 'Sakura', 'Làm sạch và tẩy trang', 'Sữa rửa mặt chiết xuất trà xanh giúp làm sạch sâu, kiểm soát dầu thừa, giảm mụn, phù hợp da dầu mụn.', 'Chiết xuất trà xanh, Salicylic acid,Nước, SLS, Phenoxyethanol, Mùi trà xanh tự nhiên', 'sp02.jpg', '2026-06-30', 120000.00, 1, 1),
(3, 'Serum dưỡng ẩm Eso 30ml', 'Elixir', 'Dưỡng da và cấp ẩm', 'Serum chứa Hyaluronic Acid cao cấp cấp ẩm nhanh, giúp da mềm mịn và săn chắc, giảm nếp nhăn nhỏ.', 'Hyaluronic Acid, Vitamin E,Nước, Propylene Glycol, Ethylhexylglycerin, Hương thơm nhẹ', 'sp03.jpg', '2026-08-31', 250000.00, 1, 1),
(4, 'Tinh chất trị mụn Acnes 20ml', 'AcneCare', 'Trị mụn, giảm thâm, làm sáng da', 'Gel trị mụn chứa chiết xuất trà xanh, bạc hà giảm sưng tấy, kiểm soát dầu, giúp mụn nhanh khỏi.', 'Salicylic Acid, Tea Tree Oil,Nước, Alcohol, Benzophenone-4, Mùi trà thảo mộc', 'sp04.jpg', '2026-09-30', 180000.00, 1, 1),
(5, 'Mặt nạ đất sét trị dầu 100g', 'PureSkin', 'Mặt nạ, dưỡng da chuyên sâu', 'Đất sét Bentonite giúp làm sạch lỗ chân lông, kiểm soát dầu thừa, giảm mụn đầu đen, phù hợp da dầu, mụn.', 'Kaolin, Bentonite,Nước, Paraben, Hương đất sét nhẹ', 'sp05.jpg', '2026-12-15', 80000.00, 1, 4),
(6, 'Kem chống nắng SPF 50 50g', 'SunSafe', 'Chống nắng, bảo vệ da', 'Kem chống nắng vật lý SPF cao, bảo vệ da khỏi tia UVA/UVB, thích hợp cho da nhạy cảm, không nhờn rít.', 'Zinc Oxide, Titanium Dioxide,Nước, Glycerin, Phenoxyethanol, Hương nhẹ', 'sp06.jpg', '2027-01-10', 200000.00, 1, 1),
(7, 'Tinh dầu dưỡng da mặt 15ml', 'GlowOil', 'Dưỡng da và cấp ẩm', 'Dầu dưỡng chứa Rosehip, Jojoba giúp dưỡng mềm, giảm tổn thương, làm sáng da, phù hợp da khô, mất cân bằng.', 'Rosehip Oil, Jojoba Oil,Dầu tự nhiên, Không, Mùi hoa tự nhiên', 'sp07.jpg', '2026-11-30', 350000.00, 1, 1),
(8, 'Son môi dưỡng ẩm 4g', 'Sakura', 'Dưỡng môi và dưỡng mắt', 'Son dưỡng môi chiết xuất dầu Jojoba,Vitamin E, giữ ẩm, làm mềm môi, có màu nhẹ tự nhiên.', 'Dầu Jojoba, Vitamin E,Không, Không đặc biệt, Hương vani nhẹ', 'sp08.jpg', '2027-05-20', 120000.00, 1, 2),
(9, 'Xịt khoáng cấp nước 150ml', 'AquaFresh', 'Dưỡng da và cấp ẩm', 'Xịt khoáng từ nước khoáng tự nhiên, làm dịu, cung cấp độ ẩm tức thì cho da khô, da nhạy cảm.', 'Nước thiên nhiên, Khoáng chất,Nước, Không, Mùi nhẹ dễ chịu', 'sp09.jpg', '2026-10-30', 100000.00, 1, 1),
(10, 'Kem quầng thâm mắt 15g', 'BrightEye', 'Dưỡng môi và dưỡng mắt', 'Kem chứa Vitamin K hỗ trợ giảm quầng thâm, làm mờ vết sưng, làm sáng da quanh mắt.', 'Caffeine, Vitamin K,Nước, Glycerin, Phenoxyethanol, Không mùi', 'sp10.jpg', '2026-12-31', 180000.00, 1, 2),
(11, 'Dung dịch tẩy trang 200ml', 'PureClean', 'Làm sạch và tẩy trang', 'Tẩy trang micellar nhẹ dịu, làm sạch lớp trang điểm, bụi bẩn, phù hợp mọi loại da.', 'Glycerin, Nước hoa cam,Nước, Dầu khoáng, Không, Hương cam nhẹ', 'sp16.jpg', '2026-09-15', 90000.00, 1, 1),
(12, 'Kem đặc trị mụn thâm 10g', 'Acne Solution', 'Trị mụn, giảm thâm, làm sáng da', 'Kem trị mụn chứa Benzoyl Peroxide, Aloe Vera giúp trị mụn, giảm thâm, làm dịu da.', 'Benzoyl Peroxide, Aloe Vera,Kem, Glycerin, Không, Không', 'sp17.webp', '2026-12-31', 180000.00, 1, 1),
(13, 'Mặt nạ từ collagen 100g', 'CollagenPlus', 'Mặt nạ, dưỡng da chuyên sâu', 'Mặt nạ collagen giúp nâng đỡ, làm săn chắc, giảm nếp nhăn, cải thiện đàn hồi da.', 'Collagen, Hyaluronic Acid,Dầu, Gel, Không, Không', 'sp18.webp', '2026-11-20', 160000.00, 1, 4),
(14, 'Phấn phủ kiềm dầu 15g', 'MatteFinish', 'Trang điểm', 'Phấn phủ khoáng chất giúp kiểm soát dầu, giữ lớp trang điểm lâu dài, phù hợp da dầu, bóng nhờn.', 'Khoáng chất tự nhiên,Phấn, Nước khoáng, Không, Không', 'sp19.png', '2027-03-15', 130000.00, 1, 2),
(15, 'Nước Hoa Hồng cấp ẩm 200ml', 'FreshRose', 'Dưỡng da và cấp ẩm', 'Nước hoa hồng chứa chiết xuất hoa quả, vitamin B3 bình thường da, cấp ẩm tức thì.', 'Chiết xuất hoa hồng, Vitamin B5,Nước, Phenoxyethanol, Hương hoa hồng', 'sp20.jpeg', '2028-02-28', 150000.00, 1, 1),
(16, 'Dầu tẩy trang micellar 150ml', 'ClearSkin', 'Làm sạch và tẩy trang', 'Công thức micellar nhẹ dịu, làm sạch lớp trang điểm kể cả mascara và son bền màu, phù hợp da nhạy cảm.', 'Công thức micellar, chiết xuất Aloe Vera,Nước, Dầu khoáng, Không, Hương cam nhẹ', 'sp21.webp', '2027-08-31', 140000.00, 1, 1),
(17, 'Tinh chất cấp nước cao cấp 50ml', 'HydratePlus', 'Dưỡng da và cấp ẩm', 'Tinh chất chứa Hyaluronic Acid và chiết xuất thiên nhiên giúp cung cấp độ ẩm cao, làm mềm mịn và đàn hồi da.', 'Hyaluronic Acid, Aloe Vera,Nước, Glycerin, Ethylhexylglycerin, Không', 'sp22.webp', '2028-05-15', 280000.00, 1, 1),
(18, 'Kem mắt sáng da 20g', 'BrightEyes', 'Dưỡng môi và dưỡng mắt', 'Kem chứa Vitamin K và Chiết xuất lô hội giúp giảm quầng thâm, sưng phồng, mang lại vùng mắt sáng khỏe.', 'Vitamin K, Chiết xuất lô hội,Kem, Glycerin, Phenoxyethanol, Không', 'sp23.webp', '2028-01-10', 200000.00, 1, 2),
(19, 'Sữa dưỡng thể chiết xuất trà xanh 400ml', 'GreenBloom', 'Dưỡng da và cấp ẩm', 'Sữa dưỡng chứa trà xanh và dầu Shea giúp dưỡng ẩm, chống oxy hóa, làm sáng và mịn da toàn thân.', 'Chiết xuất trà xanh, Dầu Shea,Kem, Dầu tự nhiên, Không, Mùi trà xanh tự nhiên', 'sp24.jpg', '2028-07-01', 160000.00, 1, 1),
(20, 'Dưỡng môi hương dâu 4g', 'Sakura', 'Dưỡng môi và dưỡng mắt', 'Dưỡng môi chứa dầu Jojoba, Vitamin E, hương dâu nhẹ, giữ ẩm, mềm môi tự nhiên.', 'Dầu Jojoba, Vitamin E,Không, Không, Hương dâu, vani nhẹ', 'sp25.jpg', '2028-06-15', 70000.00, 1, 2),
(21, 'Xịt khoáng dịu nhẹ cho em bé 150ml', 'BabyFresh', 'Dưỡng da và cấp ẩm', 'Nước khoáng tự nhiên phù hợp cho làn da nhạy cảm của bé, giúp làm dịu, cấp ẩm, chống oxy hóa.', 'Nước khoáng tự nhiên,Nước, Không, Không có', 'sp26.jpg', '2028-03-20', 80000.00, 1, 1),
(22, 'Kem dưỡng trắng da ban đêm 50g', 'Sakura', 'Dưỡng da và cấp ẩm', 'Kem chứa AHA, Vitamin C giúp tái tạo, làm sáng da ban đêm, giảm thâm nám, đều màu da.', 'AHA, Vitamin C,Kem, Glycerin, Paraben, Không', 'sp27.jpg', '2028-09-01', 270000.00, 1, 1),
(23, 'Dầu dưỡng tóc phục hồi 100ml', 'HairRevive', 'Chăm sóc thân thể và tóc', 'Dầu argan, Vitamin E giúp phục hồi tóc hư tổn, làm mềm mượt, giảm gãy rụng, phù hợp tóc khô, hư tổn.', 'Dầu argan, Vitamin E,Dầu tự nhiên, Không, Không', 'sp28.png', '2028-02-28', 250000.00, 1, 3),
(24, 'Mặt nạ giấy dưỡng da 25g', 'SkinMask', 'Mặt nạ, dưỡng da chuyên sâu', 'Mặt nạ giấy chứa chiết xuất trà xanh, vitamin E cung cấp độ ẩm, làm sáng da, giảm mụn đầu đen.', 'Chiết xuất trà xanh, Vitamin E,Tấm mặt nạ giấy, Không, Không', 'sp29.png', '2028-05-10', 50000.00, 1, 4),
(25, 'Tinh dầu massage body 200ml', 'RelaxOil', 'Chăm sóc thân thể và tóc', 'Tinh dầu hoa lavender, Jojoba giúp thư giãn, giảm căng thẳng, kích thích tuần hoàn máu, dùng cho massage thư giãn.', 'Dầu hoa lavender, Jojoba,Dầu nền, Không, Hương lavender', 'sp30.jpg', '2028-04-15', 300000.00, 1, 3),
(26, 'Gel dưỡng nẻ môi ban đêm 15g', 'LipCare', 'Dưỡng môi và dưỡng mắt', 'Gel chứa nha đam, vitamin E giúp giảm nứt nẻ, giữ ẩm, làm môi mềm mịn, dùng ban đêm.', 'Chiết xuất nha đam,Vitamin E,Không, Không, Hương nhẹ', 'sp31.jpg', '2028-10-01', 60000.00, 1, 2),
(27, 'Nước hoa hồng hoa quả 200ml', 'FruityTonic', 'Dưỡng da và cấp ẩm', 'Nước hoa hồng chiết xuất quả mọng, Vitamin B3, giúp cân bằng pH, se khít lỗ chân lông, dưỡng ẩm da.', 'Chiết xuất quả mọng, Vitamin B3,Nước, Phenoxyethanol, Hương trái cây tươi mới', 'sp32.webp', '2028-03-05', 150000.00, 1, 1),
(28, 'Tinh chất trị thâm quầng 15ml', 'DarkCircle', 'Dưỡng môi và dưỡng mắt', 'Tinh chất chứa Vitamin K, chiết xuất trà xanh giúp giảm quầng thâm, làm sáng và đều màu da vùng quanh mắt.', 'Vitamin K, Chiết xuất trà xanh,Nước, Glycerin, Phenoxyethanol, Không', 'sp33.jpg', '2028-08-20', 200000.00, 1, 2),
(29, 'Kem chống nắng ban ngày SPF 30 50g', 'SunShield', 'Chống nắng, bảo vệ da', 'Kem chống nắng vật lý SPF 30 giúp bảo vệ da khỏi tia UVA/UVB, không nhờn rít, phù hợp dùng hàng ngày.', 'Zinc Oxide, Chiết xuất trà xanh,Kem, Nước, Phenoxyethanol, Hương nhẹ', 'sp34.png', '2028-07-10', 180000.00, 1, 1),
(30, 'Sữa rửa mặt than hoạt tính 120g', 'BlackPurity', 'Làm sạch và tẩy trang', 'Sữa rửa mặt chứa than hoạt tính giúp làm sạch sâu, kiểm soát dầu, da sạch bóng, phù hợp da dầu mụn.', 'Than hoạt tính, Salicylic acid, Nước, SLS, Paraben, Mùi than tự nhiên', 'sp35.jpg', '2028-04-20', 130000.00, 1, 1),
(31, 'Dầu gội thảo mộc 250ml', 'HerbalClean', 'Chăm sóc thân thể và tóc', 'Dầu gội chiết xuất bạc hà, tràm trà giúp làm sạch, giảm gàu, phù hợp da đầu nhạy cảm.', 'Chiết xuất bạc hà, Tràm trà,Nước, SLS, Paraben, Mùi bạc hà', 'sp11.jpeg', '2026-11-15', 130000.00, 1, 3),
(32, 'Kem dưỡng body sông hơi 200g', 'SilkSkin', 'Chăm sóc thân thể và tóc', 'Kem dưỡng chứa dầu dừa, Vitamin E giúp cung cấp độ ẩm sâu, làm mịn, sáng da toàn thân.', 'Dầu dừa, Vitamin E,Kem, Dầu nền, Không, Không', 'sp12.jpg', '2027-02-28', 170000.00, 1, 3),
(33, 'Xịt chống rạn da 200ml', 'Rasta', 'Chăm sóc thân thể và tóc', 'Xịt chứa chiết xuất nha đam và Vitamin E giúp cung cấp độ ẩm, giảm rạn da, thích hợp phụ nữ mang thai.', 'Chiết xuất cây nha đam, Vitamin E,Nước, Dầu nền, Không, Không', 'sp13.webp', '2026-08-30', 220000.00, 1, 3),
(34, 'Sữa tắm ngừa mụn 300ml', 'ClearSkin', 'Chăm sóc thân thể và tóc', 'Sữa tắm chứa Salicylic, trà xanh giúp làm sạch sâu, kiểm soát dầu, phù hợp da dầu mụn.', 'Salicylic acid, Chiết xuất trà xanh,Nước, SLS, Phenoxyethanol, Mùi trà xanh tự nhiên', 'sp14.png', '2026-07-31', 110000.00, 1, 3),
(35, 'Kem trị nám 30g', 'LilyWhite', 'Trị mụn, giảm thâm, làm sáng da', 'Kem chứa Niacinamide, chiết xuất cam thảo giúp giảm nám, sáng đều màu da, hỗ trợ cải thiện sắc tố da.', 'Niacinamide, Chiết xuất cam thảo,Kem, Glycerin, Paraben, Không mùi', 'sp15.png', '2026-11-30', 300000.00, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `san_pham_khuyen_mai`
--

CREATE TABLE `san_pham_khuyen_mai` (
  `id` int(11) NOT NULL,
  `khuyen_mai_id` int(11) NOT NULL,
  `san_pham_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `san_pham_khuyen_mai`
--

INSERT INTO `san_pham_khuyen_mai` (`id`, `khuyen_mai_id`, `san_pham_id`, `created_at`) VALUES
(1, 2, 1, '2025-06-01 00:00:00'),
(2, 2, 3, '2025-06-01 00:00:00'),
(3, 2, 6, '2025-06-01 00:00:00'),
(4, 2, 76, '2025-06-01 00:00:00'),
(5, 3, 2, '2025-01-01 00:00:00'),
(6, 3, 4, '2025-01-01 00:00:00'),
(7, 3, 77, '2025-01-01 00:00:00'),
(8, 4, 76, '2025-08-01 00:00:00'),
(9, 4, 79, '2025-08-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `thanh_toan`
--

CREATE TABLE `thanh_toan` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `phuong_thuc` varchar(50) NOT NULL,
  `trang_thai` varchar(50) DEFAULT 'Chờ thanh toán',
  `ngay_thanh_toan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thanh_toan`
--

INSERT INTO `thanh_toan` (`id`, `order_id`, `phuong_thuc`, `trang_thai`, `ngay_thanh_toan`) VALUES
(1, 1, 'MoMo', 'Chờ thanh toán', NULL),
(2, 2, 'Bank Transfer', 'Chờ thanh toán', NULL),
(3, 3, 'COD', 'Chờ thanh toán', NULL),
(4, 4, 'Bank Transfer', 'Chờ thanh toán', NULL),
(5, 5, 'COD', 'Chờ thanh toán', NULL),
(6, 6, 'Bank Transfer', 'Chờ thanh toán', NULL),
(7, 7, 'COD', 'Chờ thanh toán', NULL),
(8, 8, 'Bank Transfer', 'Chờ thanh toán', NULL),
(9, 9, 'COD', 'Chờ thanh toán', NULL),
(10, 10, 'COD', 'Chờ thanh toán', NULL),
(11, 11, 'Bank Transfer', 'Chờ thanh toán', NULL),
(12, 12, 'Bank Transfer', 'Chờ thanh toán', NULL),
(13, 13, 'COD', 'Chờ thanh toán', NULL),
(14, 14, 'COD', 'Chờ thanh toán', NULL),
(15, 15, 'COD', 'Chờ thanh toán', NULL),
(16, 16, 'COD', 'Chờ thanh toán', NULL),
(17, 17, 'MoMo', 'Chờ thanh toán', NULL),
(18, 18, 'Bank Transfer', 'Chờ thanh toán', NULL),
(19, 19, 'Bank Transfer', 'Chờ thanh toán', NULL),
(20, 20, 'MoMo', 'Chờ thanh toán', NULL),
(21, 21, 'Bank Transfer', 'Chờ thanh toán', NULL),
(22, 22, 'MoMo', 'Chờ thanh toán', NULL),
(23, 23, 'Bank Transfer', 'Chờ thanh toán', NULL),
(24, 24, 'Bank Transfer', 'Chờ thanh toán', NULL),
(25, 25, 'Bank Transfer', 'Chờ thanh toán', NULL),
(26, 26, 'Bank Transfer', 'Chờ thanh toán', NULL);

-- --------------------------------------------------------
--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `fullname` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `phone`, `address`, `password`, `role`, `blocked`, `fullname`, `reset_token`, `reset_expires`) VALUES
(1, 'nemakira002@gmail.com', '03881901703', '2 võ oanhh', '$2y$10$A3/arVBhQi1DqCshmTwHMu2Nps7v.iVAt/8LHE2U9KaDUhNFvalxC', 'user', 0, 'Nguyễn Thiên Nam', '65fffd95375dc1158503fa44775e08218c2627f678221757545878b041a5c08c', '2025-09-15 08:06:09'),
(2, 'namnt0119@ut.edu.vn', NULL, NULL, '$2y$10$owAQlLxeq8janUPR9r1fcudYPIHmQutaTC7sN/juTK9.OiLdbcmbe', 'user', 0, 'Thiên Nam', '137722b9df1f6ffd3b7c8b4b6c17249515560ff4f058f8e5acb5e8d4f5de7c99', '2025-08-11 17:15:43'),
(4, 'thiennamng208@gmail.com', NULL, NULL, '$2y$10$oLErsGyd9hPYJEARvvUQJ.WtnDL6e.IONN5n/mejGyD6sx.EC6ybi', 'user', 0, 'NAM', 'e919fc4022696ef3ca27fc4eced504429e7e64fd696be0ffca18995cba49fa97', '2025-08-11 19:03:29'),
(5, 'thiennamng308@gmail.com', '03881901704', '2 võ oanh', '$2y$10$zh.zqiMp6RW3RBWMwTyxZOeZDGmLBhrQeRlYxQvnXf8Zrpbixc6l6', 'user', 0, 'Thiên Nam', '87a5d2589cb3a2bbf8399e9e4f9cf2521680d7dbdb7a8948a23ae178dddba86f', '2025-08-12 17:37:15'),
(6, 'thiennamng08@gmail.com', NULL, NULL, '$2y$10$K1BpaL9AZ5neK0mkNnh79.ryD53mxMhFAV25fW4O9UAk3POuzCsgu', 'user', 0, 'Nguyễn Thiên Namm', '533776fefc5aea694de3c7e6efa52803549751d6d8dffce74b7eb9a9f12f402d', '2025-08-11 19:18:50'),
(7, 'trinhlagi@gmail.com', '0388190290', '45 hoàng hoa thám', '$2y$10$pRXTCqTIBORy50okXLdMJ.3fB2Coa3nJv5.UWpmxEpwnvbmBdgJMO', 'user', 0, 'Nemm', NULL, NULL),
(8, 'nam1@gmail.com', NULL, NULL, '$2y$10$awHuhYxJ3CmzOxfBEpaTNuhGunbqsPhl5lxadb3zk5.ovLern77tW', 'user', 0, 'NAM', NULL, NULL),
(9, '1233@gmail.com', NULL, NULL, '$2y$10$KS3UpaSBs4Uk06nF3SawoupOJUx/a4ER3LBQbWiqBdJFfafnJXy2C', 'admin', 0, 'za', NULL, NULL),
(10, 'customer1@gmail.com', '0912345678', '123 Đường Lê Lợi, Q1, TP.HCM', '$2y$10$zh.zqiMp6RW3RBWMwTyxZOeZDGmLBhrQeRlYxQvnXf8Zrpbixc6l6', 'user', 0, 'Nguyễn Thị A', NULL, NULL),
(11, 'customer2@gmail.com', '0987654321', '456 Đường Nguyễn Huệ, Q1, TP.HCM', '$2y$10$zh.zqiMp6RW3RBWMwTyxZOeZDGmLBhrQeRlYxQvnXf8Zrpbixc6l6', 'user', 0, 'Trần Văn B', NULL, NULL),
(12, 'customer3@gmail.com', '0909123456', '789 Đường CMT8, Q3, TP.HCM', '$2y$10$zh.zqiMp6RW3RBWMwTyxZOeZDGmLBhrQeRlYxQvnXf8Zrpbixc6l6', 'user', 0, 'Lê Thị C', NULL, NULL),
(13, 'admin2@gmail.com', '0978123456', '101 Đường Hai Bà Trưng, Q1, TP.HCM', '$2y$10$zh.zqiMp6RW3RBWMwTyxZOeZDGmLBhrQeRlYxQvnXf8Zrpbixc6l6', 'admin', 0, 'Admin 2', NULL, NULL);
--
-- --------------------------------------------------------
-- Table structure for table `lien_he`
--

CREATE TABLE `lien_he` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'ID người dùng nếu đã đăng nhập',
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `tieu_de` varchar(255) DEFAULT NULL,
  `noi_dung` text NOT NULL,
  `ngay_gui` datetime DEFAULT current_timestamp(),
  `trang_thai` enum('chua_xu_ly','dang_xu_ly','da_xu_ly') DEFAULT 'chua_xu_ly',
  `ghi_chu` text DEFAULT NULL COMMENT 'Ghi chú của admin khi xử lý',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sau khi bảng users đã được tạo, thêm ràng buộc khóa ngoại
ALTER TABLE `lien_he` 
ADD CONSTRAINT `lien_he_ibfk_1` 
FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Indexes for dumped tables
--

--
-- Indexes for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `san_pham_id` (`san_pham_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`ma_danh_muc`);

--
-- Indexes for table `kho_hang`
--
ALTER TABLE `kho_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `san_pham_id` (`san_pham_id`);

--
-- Indexes for table `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_don` (`ma_don`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `san_pham_id` (`san_pham_id`);

--
-- Indexes for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sanpham_danhmuc` (`ma_danh_muc`);

--
-- Indexes for table `san_pham_khuyen_mai`
--
ALTER TABLE `san_pham_khuyen_mai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `khuyen_mai_id` (`khuyen_mai_id`),
  ADD KEY `san_pham_id` (`san_pham_id`);

--
-- Indexes for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `danh_gia`
--
ALTER TABLE `danh_gia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `ma_danh_muc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kho_hang`
--
ALTER TABLE `kho_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `san_pham_khuyen_mai`
--
ALTER TABLE `san_pham_khuyen_mai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `danh_gia_ibfk_1` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `danh_gia_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kho_hang`
--
ALTER TABLE `kho_hang`
  ADD CONSTRAINT `kho_hang_ibfk_1` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`);

--
-- Constraints for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `fk_sanpham_danhmuc` FOREIGN KEY (`ma_danh_muc`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `san_pham_khuyen_mai`
--
ALTER TABLE `san_pham_khuyen_mai`
  ADD CONSTRAINT `san_pham_khuyen_mai_ibfk_1` FOREIGN KEY (`khuyen_mai_id`) REFERENCES `khuyen_mai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `san_pham_khuyen_mai_ibfk_2` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD CONSTRAINT `thanh_toan_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
