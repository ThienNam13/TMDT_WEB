-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2025 at 04:27 PM
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
(2, 11, 2, 5, 3, 'khong on lam', '2025-08-15 00:29:05', 'uploads/reviews/1755192545_689e1ce13b2d5.jpg'),
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
(15, 24, 78, 5, 5, 'Tóc mềm mượt hơn hẳn sau lần gội đầu tiên', '2025-08-16 14:20:33', 'uploads/reviews/sp78.jpg'),
(16, 19, 79, 1, 3, 'Hiệu quả tốt nhưng giá hơi cao so với chất lượng', '2025-08-16 16:45:12', NULL),
(17, 18, 80, 5, 4, 'Kem dưỡng ẩm tốt, mùi thơm dễ chịu', '2025-08-16 18:10:55', 'uploads/reviews/review80.jpg'),
(18, 17, 81, 1, 5, 'Hương thơm sang trọng, lưu hương cả ngày', '2025-08-16 19:25:40', NULL),
(19, 35, 6, 1, 5, 'san pham ok', '2025-08-20 12:05:17', 'uploads/reviews/1755666317_68a5578d895a1.jpg'),
(20, 36, 2, 1, 4, 'san pham okk', '2025-08-20 12:10:03', 'uploads/reviews/1755666603_68a558ab46ebe.jpg');

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
(2, 4, 29, 'A2'),
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
(15, 85, 24, 'A4'),
(16, 2, 48, NULL),
(17, 3, 48, NULL),
(18, 6, 46, NULL),
(19, 7, 50, NULL),
(20, 9, 50, NULL),
(21, 11, 50, NULL),
(22, 12, 50, NULL),
(23, 15, 50, NULL),
(24, 16, 50, NULL),
(25, 17, 50, NULL),
(26, 19, 50, NULL),
(27, 21, 50, NULL),
(28, 22, 50, NULL),
(29, 27, 50, NULL),
(30, 29, 50, NULL),
(31, 35, 50, NULL),
(32, 46, 50, NULL),
(33, 47, 50, NULL),
(34, 54, 50, NULL),
(35, 56, 50, NULL),
(36, 57, 50, NULL),
(37, 58, 50, NULL),
(38, 70, 50, NULL),
(39, 71, 50, NULL),
(40, 74, 50, NULL),
(41, 75, 50, NULL),
(42, 8, 50, NULL),
(43, 10, 50, NULL),
(44, 14, 50, NULL),
(45, 18, 50, NULL),
(46, 20, 50, NULL),
(47, 26, 50, NULL),
(48, 28, 50, NULL),
(49, 48, 50, NULL),
(50, 49, 50, NULL),
(51, 55, 50, NULL),
(52, 59, 50, NULL),
(53, 60, 50, NULL),
(54, 61, 50, NULL),
(55, 72, 50, NULL),
(56, 73, 50, NULL),
(57, 25, 50, NULL),
(58, 31, 50, NULL),
(59, 32, 50, NULL),
(60, 33, 50, NULL),
(61, 34, 50, NULL),
(62, 50, 50, NULL),
(63, 51, 50, NULL),
(64, 63, 50, NULL),
(65, 64, 50, NULL),
(66, 65, 50, NULL),
(67, 5, 50, NULL),
(68, 13, 50, NULL),
(69, 24, 50, NULL),
(70, 52, 50, NULL),
(71, 53, 50, NULL),
(72, 66, 50, NULL),
(73, 67, 50, NULL),
(74, 68, 50, NULL),
(75, 69, 54, NULL);

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
(2, 'Khuyến mãi mùa hè', 'Giảm giá 20% tất cả sản phẩm chăm sóc da', '2025-06-01 00:00:00', '2025-08-31 23:59:59', 20.00, 0, '2025-05-15 00:00:00'),
(3, 'Combo tiết kiệm trong 3 ngày cuối tuần!', 'Mua 2 sản phẩm được giảm thêm 10%', '2025-01-01 00:00:00', '2025-12-31 23:59:59', 10.00, 0, '2025-01-01 00:00:00'),
(4, 'Ngày vàng thương hiệu', 'Giảm 30% sản phẩm của Laneige', '2025-09-15 00:00:00', '2025-09-17 23:59:59', 30.00, 0, '2025-08-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lien_he`
--

CREATE TABLE `lien_he` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'ID người dùng nếu đã đăng nhập',
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `tieu_de` varchar(255) DEFAULT NULL,
  `noi_dung` text NOT NULL,
  `ngay_gui` datetime DEFAULT current_timestamp(),
  `trang_thai` enum('chua_xu_ly','dang_xu_ly','da_xu_ly') DEFAULT 'chua_xu_ly',
  `ghi_chu` text DEFAULT NULL COMMENT 'Ghi chú của admin khi xử lý'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(10, 'BC250814192159B6A3CE', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường An Phú', 'TP.HCM', 135000.00, '2025-08-15 00:21:59', 'Đang giao', '0', 'COD', '', '2025-08-15 08:00:51'),
(11, 'BC2508141924553FAF6D', 5, 'Thiên Nam', '03881901704', '2 võ oanhh', 'Phường Nguyễn Thái Bình', 'TP.HCM', 255000.00, '2025-08-15 00:24:55', 'Hoàn tất', '0', 'Bank Transfer', '', '2025-08-15 08:00:51'),
(12, 'BC2508150535158B5B34', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường Bình Khánh', 'TP.HCM', 265000.00, '2025-08-15 10:35:15', 'Hoàn tất', '0', 'Bank Transfer', '', '2025-08-15 08:00:51'),
(13, 'BC250815061324C32763', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường Tân Định', 'TP.HCM', 265000.00, '2025-08-15 11:13:24', 'Đã hủy', '0', 'COD', '', '2025-08-15 08:31:42'),
(14, 'BC250815063640563D05', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Bình An', 'TP.HCM', 265000.00, '2025-08-15 11:36:40', 'Đang giao', '0', 'COD', '', '2025-08-15 08:00:51'),
(15, 'BC2508150713016D0BD9', 5, 'zatinzz', '03349565833', '12 võ oanh', 'Phường Thảo Điền', 'TP.HCM', 235000.00, '2025-08-15 12:13:01', 'Đã hủy', '0', 'COD', 'hủy', '2025-08-15 08:30:59'),
(16, 'BC250815095615472F29', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Tân Định', 'TP.HCM', 175000.00, '2025-08-15 14:56:15', 'Đã hủy', '0', 'COD', 'toi khong biet', '2025-08-15 08:25:08'),
(17, 'BC250815102831BAB92C', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Tân Định', 'TP.HCM', 265000.00, '2025-08-15 15:04:44', 'Đã hủy', '0', 'MoMo', '', '2025-08-15 08:35:44'),
(18, 'BC250815153946444AC0', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Thủ Thiêm', 'TP.HCM', 375000.00, '2025-08-15 15:39:46', 'Đang xử lý', '0', 'Bank Transfer', NULL, '2025-08-15 08:40:05'),
(19, 'BC250815172921691177', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Phạm Ngũ Lão', 'TP.HCM', 195000.00, '2025-08-15 17:03:12', 'Hoàn tất', '0', 'Bank Transfer', '', NULL),
(20, 'BC2508151738017B3980', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Thạnh Mỹ Lợi', 'TP.HCM', 135000.00, '2025-08-15 17:38:01', 'Đã hủy', '0', 'MoMo', '', NULL),
(21, 'BC2508151749134CC107', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Thủ Thiêm', 'TP.HCM', 265000.00, '2025-08-15 17:18:57', 'Hoàn tất', '0', 'Bank Transfer', '', NULL),
(22, 'BC250815181046AE276A', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Thảo Điền', 'TP.HCM', 195000.00, '2025-08-15 17:40:24', 'Hoàn tất', '0', 'MoMo', '', NULL),
(23, 'BC25081518250015BE7C', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Bình An', 'TP.HCM', 265000.00, '2025-08-15 18:05:00', 'Hoàn tất', '0', 'Bank Transfer', NULL, NULL),
(24, 'BC250815183108582FCF', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Thạnh Mỹ Lợi', 'TP.HCM', 515000.00, '2025-08-15 18:07:08', 'Hoàn tất', '0', 'Bank Transfer', NULL, NULL),
(25, 'BC250816193345CC30AA', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường An Phú', 'TP.HCM', 265000.00, '2025-08-16 19:33:45', 'Đã hủy', '0', 'Bank Transfer', 'khong thich don nay', '2025-08-16 12:34:03'),
(26, 'BC2508171312466DE743', 7, 'Nemm', '0388190290', '45 hoàng hoa thám', 'Phường Thảo Điền', 'TP.HCM', 315000.00, '2025-08-17 13:12:46', 'Đã hủy', '0', 'Bank Transfer', 'đặt test', '2025-08-17 06:13:32'),
(27, 'BC2508172353034D6E79', 1, 'Nguyễn Thiên Nam', '03881901703', '123 Lý Thường Kiệt', 'Phường Thủ Thiêm', 'TP.HCM', 315000.00, '2025-08-17 23:53:03', 'Đã hủy', '0', 'MoMo', 'tétt', '2025-08-17 16:53:20'),
(28, 'BC250819165253E9A60C', 5, 'Thiên Nam', '03881901704', '2 võ oanh', 'Phường Bình An', 'TP.HCM', 195000.00, '2025-08-19 16:52:53', 'Chờ xác nhận', '0', 'Bank Transfer', NULL, NULL),
(29, 'BC250820103804748E50', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường An Phú', 'TP.HCM', 265000.00, '2025-08-20 10:38:04', 'Hoàn tất', '0', 'Bank Transfer', '', NULL),
(30, 'BC250820110321FE3FA3', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường Bến Thành', 'TP.HCM', 265000.00, '2025-08-20 11:03:21', 'Yêu cầu trả hàng', '0', 'MoMo', 'k co', NULL),
(31, 'BC2508201112343276C6', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường An Khánh', 'TP.HCM', 95000.00, '2025-08-20 11:12:34', 'Đã hủy', '0', 'COD', 'k dung', '2025-08-20 04:13:18'),
(32, 'BC250820112431472F7C', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường Thảo Điền', 'TP.HCM', 135000.00, '2025-08-20 11:24:31', 'Hoàn tất', '0', 'Bank Transfer', '', NULL),
(33, 'BC2508201133452B7AFA', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường Thảo Điền', 'TP.HCM', 135000.00, '2025-08-20 11:33:45', 'Đã hủy', '0', 'MoMo', 'k thich', '2025-08-20 04:34:01'),
(34, 'BC250820114004E2D650', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường Long Bình', 'TP.HCM', 195000.00, '2025-08-20 11:40:04', 'Đang xử lý', '0', 'COD', '', NULL),
(35, 'BC250820120034784CF5', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường Đa Kao', 'TP.HCM', 815000.00, '2025-08-20 12:00:34', 'Hoàn tất', '0', 'Bank Transfer', '', NULL),
(36, 'BC250820120853397028', 1, 'Nguyễn Thiên Nam', '03881901703', '2 võ oanhh', 'Phường Cô Giang', 'TP.HCM', 135000.00, '2025-08-20 12:08:53', 'Hoàn tất', '0', 'MoMo', '', NULL);

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
(38, 26, 1, 2, 150000.00),
(39, 27, 1, 2, 150000.00),
(40, 28, 85, 1, 180000.00),
(41, 29, 3, 1, 250000.00),
(42, 30, 3, 1, 250000.00),
(43, 31, 5, 1, 80000.00),
(44, 32, 2, 1, 120000.00),
(45, 33, 2, 1, 120000.00),
(46, 34, 4, 1, 180000.00),
(47, 35, 6, 4, 200000.00),
(48, 36, 2, 1, 120000.00);

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
(2, 'Sữa rửa mặt trà xanh 100ml', 'Sakura', 'Làm sạch và tẩy trang', 'Sữa rửa mặt chiết xuất trà xanh giúp làm sạch sâu, kiểm soát dầu thừa, giảm mụn, phù hợp da dầu mụn.', 'Chiết xuất trà xanh, Salicylic acid,Nước, SLS, Phenoxyethanol, Mùi trà xanh tự nhiên', 'sp02.jpg', '2026-06-30', 120000.00, 1, 1),
(3, 'Serum dưỡng ẩm Eso 30ml', 'Elixir', 'Dưỡng da và cấp ẩm', 'Serum chứa Hyaluronic Acid cao cấp cấp ẩm nhanh, giúp da mềm mịn và săn chắc, giảm nếp nhăn nhỏ.', 'Hyaluronic Acid, Vitamin E,Nước, Propylene Glycol, Ethylhexylglycerin, Hương thơm nhẹ', 'sp03.jpg', '2026-08-31', 250000.00, 1, 1),
(4, 'Tinh chất trị mụn Acnes 20ml', 'AcneCare', 'Trị mụn, giảm thâm, làm sáng da', 'Gel trị mụn chứa chiết xuất trà xanh, bạc hà giảm sưng tấy, kiểm soát dầu, giúp mụn nhanh khỏi.', 'Salicylic Acid, Tea Tree Oil,Nước, Alcohol, Benzophenone-4, Mùi trà thảo mộc', 'sp04.jpg', '2026-09-30', 180000.00, 1, 1),
(5, 'Mặt nạ đất sét trị dầu 100g', 'PureSkin', 'Mặt nạ, dưỡng da chuyên sâu', 'Đất sét Bentonite giúp làm sạch lỗ chân lông, kiểm soát dầu thừa, giảm mụn đầu đen, phù hợp da dầu, mụn.', 'Kaolin, Bentonite,Nước, Paraben, Hương đất sét nhẹ', 'sp05.jpg', '2026-12-15', 80000.00, 1, 4),
(6, 'Kem chống nắng SPF 50 50g', 'SunSafe', 'Chống nắng, bảo vệ da', 'Kem chống nắng vật lý SPF cao, bảo vệ da khỏi tia UVA/UVB, thích hợp cho da nhạy cảm, không nhờn rít.', 'Zinc Oxide, Titanium Dioxide,Nước, Glycerin, Phenoxyethanol, Hương nhẹ', 'sp06.jpg', '2027-01-10', 200000.00, 1, 1),
(7, 'Tinh dầu dưỡng da mặt 15ml', 'GlowOil', 'Dưỡng da và cấp ẩm', 'Dầu dưỡng chứa Rosehip, Jojoba giúp dưỡng mềm, giảm tổn thương, làm sáng da, phù hợp da khô, mất cân bằng.', 'Rosehip Oil, Jojoba Oil,Dầu tự nhiên, Không, Mùi hoa tự nhiên', 'sp07.jpg', '2026-11-30', 350000.00, 1, 1),
(8, 'Son môi dưỡng ẩm 4g', 'Sakura', 'Dưỡng môi và dưỡng mắt', 'Son dưỡng môi chiết xuất dầu Jojoba,Vitamin E, giữ ẩm, làm mềm môi, có màu nhẹ tự nhiên.', 'Dầu Jojoba, Vitamin E,Không, Không đặc biệt, Hương vani nhẹ', 'sp08.jpg', '2027-05-20', 120000.00, 1, 2),
(9, 'Xịt khoáng cấp nước 150ml aqua fresh', 'AquaFresh', 'Dưỡng da và cấp ẩm', 'Xịt khoáng từ nước khoáng tự nhiên, làm dịu, cung cấp độ ẩm tức thì cho da khô, da nhạy cảm.', 'Nước thiên nhiên, Khoáng chất,Nước, Không, Mùi nhẹ dễ chịu', 'sp09.jpg', '2026-10-30', 100000.00, 1, 1),
(10, 'Kem quầng thâm mắt 15g', 'BrightEye', 'Dưỡng môi và dưỡng mắt', 'Kem chứa Vitamin K hỗ trợ giảm quầng thâm, làm mờ vết sưng, làm sáng da quanh mắt.', 'Caffeine, Vitamin K,Nước, Glycerin, Phenoxyethanol, Không mùi', 'sp10.jpg', '2026-12-31', 180000.00, 1, 2),
(11, 'Dung dịch tẩy trang 200ml', 'PureClean', 'Làm sạch và tẩy trang', 'Tẩy trang micellar nhẹ dịu, làm sạch lớp trang điểm, bụi bẩn, phù hợp mọi loại da.', 'Glycerin, Nước hoa cam,Nước, Dầu khoáng, Không, Hương cam nhẹ', 'sp16.jpg', '2026-09-15', 90000.00, 1, 1),
(12, 'Kem đặc trị mụn thâm 10g', 'Acne Solution', 'Trị mụn, giảm thâm, làm sáng da', 'Kem trị mụn chứa Benzoyl Peroxide, Aloe Vera giúp trị mụn, giảm thâm, làm dịu da.', 'Benzoyl Peroxide, Aloe Vera,Kem, Glycerin, Không, Không', 'sp17.jpg', '2026-12-31', 180000.00, 1, 1),
(13, 'Mặt nạ từ collagen 100g', 'CollagenPlus', 'Mặt nạ, dưỡng da chuyên sâu', 'Mặt nạ collagen giúp nâng đỡ, làm săn chắc, giảm nếp nhăn, cải thiện đàn hồi da.', 'Collagen, Hyaluronic Acid,Dầu, Gel, Không, Không', 'sp18.jpg', '2026-11-20', 160000.00, 1, 4),
(14, 'Phấn phủ kiềm dầu 15g', 'MatteFinish', 'Trang điểm', 'Phấn phủ khoáng chất giúp kiểm soát dầu, giữ lớp trang điểm lâu dài, phù hợp da dầu, bóng nhờn.', 'Khoáng chất tự nhiên,Phấn, Nước khoáng, Không, Không', 'sp19.jpg', '2027-03-15', 130000.00, 1, 2),
(15, 'Nước Hoa Hồng cấp ẩm 200ml', 'FreshRose', 'Dưỡng da và cấp ẩm', 'Nước hoa hồng chứa chiết xuất hoa quả, vitamin B3 bình thường da, cấp ẩm tức thì.', 'Chiết xuất hoa hồng, Vitamin B5,Nước, Phenoxyethanol, Hương hoa hồng', 'sp20.jpg', '2028-02-28', 150000.00, 1, 1),
(16, 'Dầu tẩy trang micellar 150ml', 'ClearSkin', 'Làm sạch và tẩy trang', 'Công thức micellar nhẹ dịu, làm sạch lớp trang điểm kể cả mascara và son bền màu, phù hợp da nhạy cảm.', 'Công thức micellar, chiết xuất Aloe Vera,Nước, Dầu khoáng, Không, Hương cam nhẹ', 'sp21.jpg', '2027-08-31', 140000.00, 1, 1),
(17, 'Tinh chất cấp nước cao cấp 50ml', 'HydratePlus', 'Dưỡng da và cấp ẩm', 'Tinh chất chứa Hyaluronic Acid và chiết xuất thiên nhiên giúp cung cấp độ ẩm cao, làm mềm mịn và đàn hồi da.', 'Hyaluronic Acid, Aloe Vera,Nước, Glycerin, Ethylhexylglycerin, Không', 'sp22.jpg', '2028-05-15', 280000.00, 1, 1),
(18, 'Kem mắt sáng da 20g', 'BrightEyes', 'Dưỡng môi và dưỡng mắt', 'Kem chứa Vitamin K và Chiết xuất lô hội giúp giảm quầng thâm, sưng phồng, mang lại vùng mắt sáng khỏe.', 'Vitamin K, Chiết xuất lô hội,Kem, Glycerin, Phenoxyethanol, Không', 'sp23.jpg', '2028-01-10', 200000.00, 1, 2),
(19, 'Sữa dưỡng thể chiết xuất trà xanh 400ml', 'GreenBloom', 'Dưỡng da và cấp ẩm', 'Sữa dưỡng chứa trà xanh và dầu Shea giúp dưỡng ẩm, chống oxy hóa, làm sáng và mịn da toàn thân.', 'Chiết xuất trà xanh, Dầu Shea,Kem, Dầu tự nhiên, Không, Mùi trà xanh tự nhiên', 'sp24.jpg', '2028-07-01', 160000.00, 1, 1),
(20, 'Dưỡng môi hương dâu 4g', 'Sakura', 'Dưỡng môi và dưỡng mắt', 'Dưỡng môi chứa dầu Jojoba, Vitamin E, hương dâu nhẹ, giữ ẩm, mềm môi tự nhiên.', 'Dầu Jojoba, Vitamin E,Không, Không, Hương dâu, vani nhẹ', 'sp25.jpg', '2028-06-15', 70000.00, 1, 2),
(21, 'Xịt khoáng dịu nhẹ cho em bé 150ml', 'BabyFresh', 'Dưỡng da và cấp ẩm', 'Nước khoáng tự nhiên phù hợp cho làn da nhạy cảm của bé, giúp làm dịu, cấp ẩm, chống oxy hóa.', 'Nước khoáng tự nhiên,Nước, Không, Không có', 'sp26.jpg', '2028-03-20', 80000.00, 1, 1),
(22, 'Kem dưỡng trắng da ban đêm 50g', 'Sakura', 'Dưỡng da và cấp ẩm', 'Kem chứa AHA, Vitamin C giúp tái tạo, làm sáng da ban đêm, giảm thâm nám, đều màu da.', 'AHA, Vitamin C,Kem, Glycerin, Paraben, Không', 'sp27.jpg', '2028-09-01', 270000.00, 1, 1),
(23, 'Dầu dưỡng tóc phục hồi 100ml', 'HairRevive', 'Chăm sóc thân thể và tóc', 'Dầu argan, Vitamin E giúp phục hồi tóc hư tổn, làm mềm mượt, giảm gãy rụng, phù hợp tóc khô, hư tổn.', 'Dầu argan, Vitamin E,Dầu tự nhiên, Không, Không', 'sp28.jpg', '2028-02-28', 250000.00, 1, 3),
(24, 'Mặt nạ giấy dưỡng da 25g', 'SkinMask', 'Mặt nạ, dưỡng da chuyên sâu', 'Mặt nạ giấy chứa chiết xuất trà xanh, vitamin E cung cấp độ ẩm, làm sáng da, giảm mụn đầu đen.', 'Chiết xuất trà xanh, Vitamin E,Tấm mặt nạ giấy, Không, Không', 'sp29.jpg', '2028-05-10', 50000.00, 1, 4),
(25, 'Tinh dầu massage body 200ml', 'RelaxOil', 'Chăm sóc thân thể và tóc', 'Tinh dầu hoa lavender, Jojoba giúp thư giãn, giảm căng thẳng, kích thích tuần hoàn máu, dùng cho massage thư giãn.', 'Dầu hoa lavender, Jojoba,Dầu nền, Không, Hương lavender', 'sp30.jpg', '2028-04-15', 300000.00, 1, 3),
(26, 'Gel dưỡng nẻ môi ban đêm 15g', 'LipCare', 'Dưỡng môi và dưỡng mắt', 'Gel chứa nha đam, vitamin E giúp giảm nứt nẻ, giữ ẩm, làm môi mềm mịn, dùng ban đêm.', 'Chiết xuất nha đam,Vitamin E,Không, Không, Hương nhẹ', 'sp31.jpg', '2028-10-01', 60000.00, 1, 2),
(27, 'Nước hoa hồng hoa quả 200ml', 'FruityTonic', 'Dưỡng da và cấp ẩm', 'Nước hoa hồng chiết xuất quả mọng, Vitamin B3, giúp cân bằng pH, se khít lỗ chân lông, dưỡng ẩm da.', 'Chiết xuất quả mọng, Vitamin B3,Nước, Phenoxyethanol, Hương trái cây tươi mới', 'sp32.jpg', '2028-03-05', 150000.00, 1, 1),
(28, 'Tinh chất trị thâm quầng 15ml', 'DarkCircle', 'Dưỡng môi và dưỡng mắt', 'Tinh chất chứa Vitamin K, chiết xuất trà xanh giúp giảm quầng thâm, làm sáng và đều màu da vùng quanh mắt.', 'Vitamin K, Chiết xuất trà xanh,Nước, Glycerin, Phenoxyethanol, Không', 'sp33.jpg', '2028-08-20', 200000.00, 1, 2),
(29, 'Kem chống nắng ban ngày SPF 30 50g', 'SunShield', 'Chống nắng, bảo vệ da', 'Kem chống nắng vật lý SPF 30 giúp bảo vệ da khỏi tia UVA/UVB, không nhờn rít, phù hợp dùng hàng ngày.', 'Zinc Oxide, Chiết xuất trà xanh,Kem, Nước, Phenoxyethanol, Hương nhẹ', 'sp34.jpg', '2028-07-10', 180000.00, 1, 1),
(30, 'Sữa rửa mặt than hoạt tính 120g', 'BlackPurity', 'Làm sạch và tẩy trang', 'Sữa rửa mặt chứa than hoạt tính giúp làm sạch sâu, kiểm soát dầu, da sạch bóng, phù hợp da dầu mụn.', 'Than hoạt tính, Salicylic acid, Nước, SLS, Paraben, Mùi than tự nhiên', 'sp.jpg', '2028-04-20', 130000.00, 1, 1),
(31, 'Dầu gội thảo mộc 250ml', 'HerbalClean', 'Chăm sóc thân thể và tóc', 'Dầu gội chiết xuất bạc hà, tràm trà giúp làm sạch, giảm gàu, phù hợp da đầu nhạy cảm.', 'Chiết xuất bạc hà, Tràm trà,Nước, SLS, Paraben, Mùi bạc hà', 'sp11.jpg', '2026-11-15', 130000.00, 1, 3),
(32, 'Kem dưỡng body sông hơi 200g', 'SilkSkin', 'Chăm sóc thân thể và tóc', 'Kem dưỡng chứa dầu dừa, Vitamin E giúp cung cấp độ ẩm sâu, làm mịn, sáng da toàn thân.', 'Dầu dừa, Vitamin E,Kem, Dầu nền, Không, Không', 'sp12.jpg', '2027-02-28', 170000.00, 1, 3),
(33, 'Xịt chống rạn da 200ml', 'Rasta', 'Chăm sóc thân thể và tóc', 'Xịt chứa chiết xuất nha đam và Vitamin E giúp cung cấp độ ẩm, giảm rạn da, thích hợp phụ nữ mang thai.', 'Chiết xuất cây nha đam, Vitamin E,Nước, Dầu nền, Không, Không', 'sp13.jpg', '2026-08-30', 220000.00, 1, 3),
(34, 'Sữa tắm ngừa mụn 300ml', 'ClearSkin', 'Chăm sóc thân thể và tóc', 'Sữa tắm chứa Salicylic, trà xanh giúp làm sạch sâu, kiểm soát dầu, phù hợp da dầu mụn.', 'Salicylic acid, Chiết xuất trà xanh,Nước, SLS, Phenoxyethanol, Mùi trà xanh tự nhiên', 'sp14.jpg', '2026-07-31', 110000.00, 1, 3),
(35, 'Kem trị nám 30g', 'LilyWhite', 'Trị mụn, giảm thâm, làm sáng da', 'Kem chứa Niacinamide, chiết xuất cam thảo giúp giảm nám, sáng đều màu da, hỗ trợ cải thiện sắc tố da.', 'Niacinamide, Chiết xuất cam thảo,Kem, Glycerin, Paraben, Không mùi', 'sp15.jpg', '2026-11-30', 300000.00, 1, 1),
(46, 'Kem dưỡng ẩm ban ngày', 'Cetaphil', 'Dưỡng da', 'Giữ ẩm suốt 24h, phù hợp da nhạy cảm', 'Glycerin, Panthenol, Tocopherol', 'sp38.jpg', '2027-01-01', 320000.00, 1, 1),
(47, 'Tinh chất phục hồi da', 'The Ordinary', 'Dưỡng da', 'Giảm kích ứng, phục hồi hàng rào bảo vệ da', 'Niacinamide, Zinc PCA', 'sp39.jpg', '2026-12-31', 280000.00, 1, 1),
(48, 'Son kem lì màu cam đất', 'Romand', 'Trang điểm', 'Màu sắc trẻ trung, chất son mịn', 'Dimethicone, Pigment Orange 5', 'sp40.jpg', '2026-08-15', 210000.00, 1, 2),
(49, 'Phấn má hồng dạng kem', '3CE', 'Trang điểm', 'Tạo hiệu ứng má hồng tự nhiên', 'Silicone, Wax, Pigments', 'sp41.jpg', '2026-09-30', 270000.00, 1, 2),
(50, 'Dầu gội thảo dược', 'Herbal Essences', 'Chăm sóc tóc', 'Làm sạch nhẹ nhàng, hương thơm dễ chịu', 'Aloe Vera, Chamomile, Water', 'sp42.jpg', '2026-11-30', 180000.00, 1, 3),
(51, 'Kem xả dưỡng tóc mềm mượt', 'Pantene', 'Chăm sóc tóc', 'Giúp tóc suôn mượt, dễ chải', 'Pro-V Complex, Silicones', 'sp43.jpg', '2027-03-20', 190000.00, 1, 3),
(52, 'Mặt nạ đất sét thải độc', 'Kiehl\'s', 'Mặt nạ', 'Làm sạch sâu, giảm dầu thừa', 'Amazonian Clay, Aloe Extract', 'sp44.jpg', '2026-10-10', 450000.00, 1, 4),
(53, 'Mặt nạ giấy dưỡng trắng', 'Mediheal', 'Mặt nạ', 'Giúp da sáng đều màu, cấp ẩm tức thì', 'Niacinamide, Hyaluronic Acid', 'sp45.jpg', '2026-12-01', 40000.00, 1, 4),
(54, 'Kem dưỡng mắt chống thâm', 'Laneige', 'Dưỡng da', 'Giảm quầng thâm, cấp ẩm vùng mắt', 'Caffeine, Peptides, Shea Butter', 'sp46.jpg', '2027-05-15', 620000.00, 1, 1),
(55, 'Son dưỡng môi có màu', 'Burt\'s Bees', 'Trang điểm', 'Dưỡng môi mềm mịn, màu nhẹ nhàng', 'Beeswax, Shea Butter, Pigments', 'sp47.jpg', '2026-10-01', 150000.00, 1, 2),
(56, 'Kem dưỡng tái tạo da ban đêm', 'Vichy', 'Dưỡng da', 'Tái tạo da khi ngủ, giảm nếp nhăn', 'Retinol, Vitamin E, Thermal Water', 'sp48.jpg', '2027-02-28', 680000.00, 1, 1),
(57, 'Serum cấp nước sâu', 'L\'Oréal Paris', 'Dưỡng da', 'Cấp ẩm tức thì, da căng bóng', 'Hyaluronic Acid, Glycerin', 'sp49.jpg', '2026-12-31', 420000.00, 1, 1),
(58, 'Kem dưỡng trắng da toàn thân', 'Nivea', 'Dưỡng da', 'Làm sáng da, dưỡng ẩm lâu dài', 'Vitamin C, Collagen, Water', 'sp50.jpg', '2027-06-30', 250000.00, 1, 1),
(59, 'Son thỏi satin màu hồng đất', 'Charlotte Tilbury', 'Trang điểm', 'Màu sắc sang trọng, dưỡng môi nhẹ', 'Wax, Pigments, Oils', 'sp51.jpg', '2026-08-15', 850000.00, 1, 2),
(60, 'Mascara làm dày mi', 'Maybelline', 'Trang điểm', 'Tạo hiệu ứng mi dày, không lem', 'Beeswax, Carbon Black, Polymers', 'sp52.jpg', '2026-09-10', 280000.00, 1, 2),
(61, 'Kem nền dạng lỏng', 'Fenty Beauty', 'Trang điểm', 'Che phủ tốt, phù hợp nhiều tông da', 'Dimethicone, Pigments, SPF', 'sp53.jpg', '2026-10-01', 720000.00, 1, 2),
(62, 'Bảng mắt 9 màu', 'Etude House', 'Trang điểm', 'Tông màu tự nhiên, dễ phối', 'Mica, Talc, Pigments', 'sp54.jpg', '2026-11-01', 390000.00, 1, 2),
(63, 'Xịt dưỡng tóc bóng mượt', 'Tresemme', 'Chăm sóc tóc', 'Giúp tóc bóng khỏe, dễ tạo kiểu', 'Silicone, Argan Oil, Water', 'sp55.jpg', '2027-01-01', 210000.00, 1, 3),
(64, 'Kem ủ tóc phục hồi', 'Moroccanoil', 'Chăm sóc tóc', 'Phục hồi tóc hư tổn, cấp ẩm sâu', 'Argan Oil, Keratin, Panthenol', 'sp56.jpg', '2026-12-15', 650000.00, 1, 3),
(65, 'Serum dưỡng tóc mềm mượt', 'L\'Oréal', 'Chăm sóc tóc', 'Giảm xơ rối, bảo vệ tóc khỏi nhiệt', 'Silicone, Vitamin E, Oils', 'sp57.jpg', '2027-03-20', 320000.00, 1, 3),
(66, 'Mặt nạ ngủ dưỡng ẩm', 'Laneige', 'Mặt nạ', 'Cấp nước suốt đêm, da mềm mịn', 'Beta-Glucan, Mineral Water', 'sp58.jpg', '2027-04-10', 480000.00, 1, 4),
(67, 'Mặt nạ giấy collagen', 'Dr.Jart+', 'Mặt nạ', 'Tăng độ đàn hồi, giảm nếp nhăn', 'Collagen, Hyaluronic Acid', 'sp59.jpg', '2026-12-01', 55000.00, 1, 4),
(68, 'Mặt nạ đất sét kiểm soát dầu', 'Innisfree', 'Mặt nạ', 'Giảm dầu thừa, làm sạch lỗ chân lông', 'Volcanic Clay, Green Tea', 'sp60.jpg', '2026-11-30', 320000.00, 1, 4),
(69, 'Mặt nạ giấy trà xanh', 'The Face Shop', 'Mặt nạ', 'Làm dịu da, giảm kích ứng', 'Green Tea Extract, Glycerin', 'sp61.jpg', '2026-10-10', 40000.00, 1, 4),
(70, 'Kem dưỡng chống lão hóa', 'Olay', 'Dưỡng da', 'Giảm nếp nhăn, tăng độ đàn hồi', 'Peptides, Retinol, Vitamin B3', 'sp62.jpg', '2027-05-15', 580000.00, 1, 1),
(71, 'Gel dưỡng ẩm cho da dầu', 'Neutrogena', 'Dưỡng da', 'Thấm nhanh, không gây nhờn rít', 'Water, Glycerin, Dimethicone', 'sp63.jpg', '2026-09-30', 330000.00, 1, 1),
(72, 'Son tint bóng nhẹ', 'Peripera', 'Trang điểm', 'Màu tự nhiên, dưỡng môi', 'Water, Pigments, Oils', 'sp64.jpg', '2026-08-01', 180000.00, 1, 2),
(73, 'Kem lót kiềm dầu', 'Benefit', 'Trang điểm', 'Giữ lớp nền lâu trôi, giảm bóng dầu', 'Silicone, Vitamin E', 'sp65.jpg', '2026-10-20', 620000.00, 1, 2),
(74, 'Xịt khoáng dưỡng da', 'Avene', 'Dưỡng da', 'Làm dịu da, cấp nước tức thì', 'Thermal Spring Water', 'sp66.jpg', '2027-01-01', 290000.00, 1, 1),
(75, 'Kem dưỡng da tay', 'Bath & Body Works', 'Dưỡng da', 'Dưỡng ẩm, hương thơm dễ chịu', 'Shea Butter, Vitamin E', 'sp67.jpg', '2026-12-31', 150000.00, 1, 1),
(76, 'Kem dưỡng da ban đêm', 'Laneige', 'Dưỡng da', 'Kem dưỡng ban đêm giúp phục hồi da', 'Water, Glycerin, Niacinamide', 'sp76.jpg', '2027-12-31', 450000.00, 1, 1),
(77, 'Son lì cao cấp', 'Dior', 'Trang điểm', 'Son lì không trôi, lâu phai', 'Pigments, Wax, Oils', 'sp77.jpg', '2027-06-30', 650000.00, 1, 2),
(78, 'Dầu gội phục hồi tóc', 'Kerastase', 'Chăm sóc tóc', 'Phục hồi tóc hư tổn từ sâu bên trong', 'Keratin, Argan Oil', 'sp78.jpg', '2027-09-15', 550000.00, 1, 3),
(79, 'Mặt nạ ngủ dưỡng ẩm', 'Laneige', 'Mặt nạ', 'Dưỡng ẩm suốt đêm cho làn da căng mịn', 'Hyaluronic Acid, Mineral Water', 'sp79.jpg', '2027-08-20', 380000.00, 1, 4),
(80, 'Kem dưỡng body', 'Vaseline', 'Chăm sóc cơ thể', 'Dưỡng ẩm toàn thân, mềm mịn da', 'Petroleum Jelly, Glycerin', 'sp80.jpg', '2027-11-30', 120000.00, 1, 5),
(81, 'Nước hoa hương hoa', 'Chanel', 'Nước hoa', 'Hương thơm sang trọng, lưu hương lâu', 'Alcohol, Fragrance', 'sp81.jpg', '2028-05-15', 2500000.00, 1, 6),
(82, 'Cọ trang điểm cao cấp', 'Real Techniques', 'Dụng cụ làm đẹp', 'Bộ cọ đa năng, chất lượng cao', 'Synthetic Bristles', 'sp82.jpg', '2028-01-01', 850000.00, 1, 7),
(83, 'Serum vitamin C', 'The Ordinary', 'Dưỡng da', 'Làm sáng da, giảm thâm nám', 'Vitamin C, Hyaluronic Acid', 'sp83.jpg', '2027-04-30', 320000.00, 1, 1),
(84, 'Phấn mắt nhiều màu', 'Urban Decay', 'Trang điểm', 'Bảng phấn mắt đa dạng màu sắc', 'Mica, Talc, Pigments', 'sp84.jpg', '2027-10-15', 1200000.00, 1, 2),
(85, 'Dầu xả dưỡng tóc', 'Pantene', 'Chăm sóc tóc', 'Làm mượt tóc, giảm gãy rụng', 'Pro-Vitamin Complex', 'sp85.jpg', '2027-07-20', 180000.00, 1, 3);

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
(26, 26, 'Bank Transfer', 'Chờ thanh toán', NULL),
(27, 27, 'MoMo', 'Chờ thanh toán', NULL),
(28, 28, 'Bank Transfer', 'Chờ thanh toán', NULL),
(29, 29, 'Bank Transfer', 'Chờ thanh toán', NULL),
(30, 30, 'MoMo', 'Chờ thanh toán', NULL),
(31, 31, 'COD', 'Chờ thanh toán', NULL),
(32, 32, 'Bank Transfer', 'Chờ thanh toán', NULL),
(33, 33, 'MoMo', 'Chờ thanh toán', NULL),
(34, 34, 'COD', 'Chờ thanh toán', NULL),
(35, 35, 'Bank Transfer', 'Chờ thanh toán', NULL),
(36, 36, 'MoMo', 'Chờ thanh toán', NULL);

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
(1, 'nemakira002@gmail.com', '03881901703', '2 võ oanhh', '$2y$10$A3/arVBhQi1DqCshmTwHMu2Nps7v.iVAt/8LHE2U9KaDUhNFvalxC', 'user', 0, 'Nguyễn Thiên Nam', NULL, NULL),
(2, 'namnt0119@ut.edu.vn', NULL, NULL, '$2y$10$owAQlLxeq8janUPR9r1fcudYPIHmQutaTC7sN/juTK9.OiLdbcmbe', 'user', 0, 'Thiên Nam', '975a9da2091e3a00f700708f3dedf1373fc28e0a81a305d9138d0ee72e1c7c06', '2025-08-19 18:55:11'),
(4, 'thiennamng208@gmail.com', NULL, NULL, '$2y$10$oLErsGyd9hPYJEARvvUQJ.WtnDL6e.IONN5n/mejGyD6sx.EC6ybi', 'user', 0, 'NAM', 'e919fc4022696ef3ca27fc4eced504429e7e64fd696be0ffca18995cba49fa97', '2025-08-11 19:03:29'),
(5, 'thiennamng308@gmail.com', '03881901704', '2 võ oanh', '$2y$10$zh.zqiMp6RW3RBWMwTyxZOeZDGmLBhrQeRlYxQvnXf8Zrpbixc6l6', 'admin', 0, 'Thiên Nam', 'c768d3ae630beee8f62f0ddb9b35622618c069e0cb0850111f20cab657c60e07', '2025-08-19 17:23:22'),
(6, 'thiennamng08@gmail.com', NULL, NULL, '$2y$10$K1BpaL9AZ5neK0mkNnh79.ryD53mxMhFAV25fW4O9UAk3POuzCsgu', 'user', 0, 'Nguyễn Thiên Namm', '533776fefc5aea694de3c7e6efa52803549751d6d8dffce74b7eb9a9f12f402d', '2025-08-11 19:18:50'),
(7, 'trinhlagi@gmail.com', '0388190290', '45 hoàng hoa thám', '$2y$10$pRXTCqTIBORy50okXLdMJ.3fB2Coa3nJv5.UWpmxEpwnvbmBdgJMO', 'user', 0, 'Nemm', NULL, NULL),
(8, 'nam1@gmail.com', NULL, NULL, '$2y$10$awHuhYxJ3CmzOxfBEpaTNuhGunbqsPhl5lxadb3zk5.ovLern77tW', 'user', 0, 'NAM', NULL, NULL),
(9, '1233@gmail.com', NULL, NULL, '$2y$10$KS3UpaSBs4Uk06nF3SawoupOJUx/a4ER3LBQbWiqBdJFfafnJXy2C', 'admin', 0, 'za', NULL, NULL),
(10, 'customer1@gmail.com', '0912345678', '123 Đường Lê Lợi, Q1, TP.HCM', '$2y$10$zh.zqiMp6RW3RBWMwTyxZOeZDGmLBhrQeRlYxQvnXf8Zrpbixc6l6', 'user', 0, 'Nguyễn Thị A', NULL, NULL),
(11, 'customer2@gmail.com', '0987654321', '456 Đường Nguyễn Huệ, Q1, TP.HCM', '$2y$10$zh.zqiMp6RW3RBWMwTyxZOeZDGmLBhrQeRlYxQvnXf8Zrpbixc6l6', 'user', 0, 'Trần Văn B', NULL, NULL),
(12, 'customer3@gmail.com', '0909123456', '789 Đường CMT8, Q3, TP.HCM', '$2y$10$zh.zqiMp6RW3RBWMwTyxZOeZDGmLBhrQeRlYxQvnXf8Zrpbixc6l6', 'user', 0, 'Lê Thị C', NULL, NULL),
(13, 'admin2@gmail.com', '0978123456', '101 Đường Hai Bà Trưng, Q1, TP.HCM', '$2y$10$zh.zqiMp6RW3RBWMwTyxZOeZDGmLBhrQeRlYxQvnXf8Zrpbixc6l6', 'admin', 0, 'Admin 2', NULL, NULL),
(14, 'tester123@gmail.com', NULL, NULL, '$2y$10$Ov8gCuSerCwOdK.MTpspw.W7/DM2VbIprOl2rb3M9mp76D8wizr4i', 'user', 0, 'Nguyễn Văn Test', NULL, NULL),
(15, 'ngotuanne0948@gmail.com', NULL, NULL, '$2y$10$TXVp3wK0R0/BtwOzmtbJxOfqfctxRtOoN7CupMDz0gaCZRdnwxf5.', 'user', 0, 'Nguyễn Tài Khoản', '016dc3aae6e042535e74f2d76897b5e4931d7016d469ca96a9e99c9b97c7222d', '2025-08-19 12:47:28');

--
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
-- Indexes for table `lien_he`
--
ALTER TABLE `lien_he`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `ma_danh_muc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kho_hang`
--
ALTER TABLE `kho_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lien_he`
--
ALTER TABLE `lien_he`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
-- Constraints for table `lien_he`
--
ALTER TABLE `lien_he`
  ADD CONSTRAINT `lien_he_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
