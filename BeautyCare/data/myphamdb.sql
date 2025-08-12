-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2025 at 05:17 PM
-- Server version: 8.0.37
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `myphamdb`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_gia`
--

CREATE TABLE `danh_gia` (
  `id` int NOT NULL,
  `san_pham_id` int NOT NULL,
  `user_id` int NOT NULL,
  `so_sao` tinyint(1) DEFAULT NULL,
  `binh_luan` text COLLATE utf8mb4_general_ci,
  `ngay_danh_gia` datetime DEFAULT CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc`
--

CREATE TABLE `danh_muc` (
  `ma_danh_muc` int NOT NULL,
  `ten_danh_muc` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danh_muc`
--

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`) VALUES
(1, 'Dưỡng da'),
(2, 'Trang điểm'),
(3, 'Chăm sóc tóc'),
(4, 'Mặt nạ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `kho_hang`
--

CREATE TABLE `kho_hang` (
  `id` int NOT NULL,
  `san_pham_id` int NOT NULL,
  `so_luong_ton` int NOT NULL DEFAULT '0',
  `vi_tri_kho` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `ma_don` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `ho_ten` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `sdt` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `dia_chi` text COLLATE utf8mb4_general_ci NOT NULL,
  `phuong_xa` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `khu_vuc` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tong_tien` decimal(10,2) NOT NULL,
  `thoi_gian_dat` datetime DEFAULT CURRENT_TIMESTAMP,
  `trang_thai` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Đang xử lý',
  `ghi_chu` text COLLATE utf8mb4_general_ci,
  `hinh_thuc_thanh_toan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `san_pham_id` int NOT NULL,
  `so_luong` int NOT NULL,
  `don_gia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `id` int NOT NULL,
  `ten_san_pham` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `thuong_hieu` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phan_loai` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mo_ta` text COLLATE utf8mb4_general_ci,
  `thanh_phan` text COLLATE utf8mb4_general_ci,
  `hinh_anh` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `han_su_dung` date DEFAULT NULL,
  `gia` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) DEFAULT '1',
  `ma_danh_muc` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `san_pham`
--

INSERT INTO `san_pham` (`id`, `ten_san_pham`, `thuong_hieu`, `phan_loai`, `mo_ta`, `thanh_phan`, `hinh_anh`, `han_su_dung`, `gia`, `is_available`, `ma_danh_muc`) VALUES
(1, 'Kem dưỡng trắng sáng 50g', 'Sakura', 'Dưỡng da và cấp ẩm', 'Kem dưỡng chứa tinh chất vitamin C và niacinamide giúp làm sáng và đều màu da, giảm thâm nám, mang lại làn da rạng rỡ.', 'Niacinamide, Glutathione,Nước, Glycerin, Paraben, Hương hoa nhẹ', 'sp01.jpg', '2026-12-31', 150000.00, 1, 1),
(2, 'Sữa rửa mặt chiết xuất trà xanh 100ml', 'Sakura', 'Làm sạch và tẩy trang', 'Sữa rửa mặt chiết xuất trà xanh giúp làm sạch sâu, kiểm soát dầu thừa, giảm mụn, phù hợp da dầu mụn.', 'Chiết xuất trà xanh, Salicylic acid,Nước, SLS, Phenoxyethanol, Mùi trà xanh tự nhiên', 'sp02.jpg', '2026-06-30', 120000.00, 1, 1),
(3, 'Serum dưỡng ẩm Eso 30ml', 'Elixir', 'Dưỡng da và cấp ẩm', 'Serum chứa Hyaluronic Acid cao cấp cấp ẩm nhanh, giúp da mềm mịn và săn chắc, giảm nếp nhăn nhỏ.', 'Hyaluronic Acid, Vitamin E,Nước, Propylene Glycol, Ethylhexylglycerin, Hương thơm nhẹ', 'sp03.jpg', '2026-08-31', 250000.00, 1, 1),
(4, 'Tinh chất trị mụn 20ml', 'AcneCare', 'Trị mụn, giảm thâm, làm sáng da', 'Gel trị mụn chứa chiết xuất trà xanh, bạc hà giảm sưng tấy, kiểm soát dầu, giúp mụn nhanh khỏi.', 'Salicylic Acid, Tea Tree Oil,Nước, Alcohol, Benzophenone-4, Mùi trà thảo mộc', 'sp04.jpg', '2026-09-30', 180000.00, 1, 1),
(5, 'Mặt nạ đất sét trị dầu 100g', 'PureSkin', 'Mặt nạ, dưỡng da chuyên sâu', 'Đất sét Bentonite giúp làm sạch lỗ chân lông, kiểm soát dầu thừa, giảm mụn đầu đen, phù hợp da dầu, mụn.', 'Kaolin, Bentonite,Nước, Paraben, Hương đất sét nhẹ', 'sp05.jpg', '2026-12-15', 80000.00, 1, 4),
(6, 'Kem chống nắng SPF 50 50g', 'SunSafe', 'Chống nắng, bảo vệ da', 'Kem chống nắng vật lý SPF cao, bảo vệ da khỏi tia UVA/UVB, thích hợp cho da nhạy cảm, không nhờn rít.', 'Zinc Oxide, Titanium Dioxide,Nước, Glycerin, Phenoxyethanol, Hương nhẹ', 'sp06.jpg', '2027-01-10', 200000.00, 1, 1),
(7, 'Tinh dầu dưỡng da mặt 15ml', 'GlowOil', 'Dưỡng da và cấp ẩm', 'Dầu dưỡng chứa Rosehip, Jojoba giúp dưỡng mềm, giảm tổn thương, làm sáng da, phù hợp da khô, mất cân bằng.', 'Rosehip Oil, Jojoba Oil,Dầu tự nhiên, Không, Mùi hoa tự nhiên', 'sp07.jpg', '2026-11-30', 350000.00, 1, 1),
(8, 'Son môi dưỡng ẩm 4g', 'Sakura', 'Dưỡng môi và dưỡng mắt', 'Son dưỡng môi chiết xuất dầu Jojoba,Vitamin E, giữ ẩm, làm mềm môi, có màu nhẹ tự nhiên.', 'Dầu Jojoba, Vitamin E,Không, Không đặc biệt, Hương vani nhẹ', 'sp08.jpg', '2027-05-20', 120000.00, 1, 2),
(9, 'Xịt khoáng cấp nước 150ml', 'AquaFresh', 'Dưỡng da và cấp ẩm', 'Xịt khoáng từ nước khoáng tự nhiên, làm dịu, cung cấp độ ẩm tức thì cho da khô, da nhạy cảm.', 'Nước thiên nhiên, Khoáng chất,Nước, Không, Mùi nhẹ dễ chịu', 'sp09.jpg', '2026-10-30', 100000.00, 1, 1),
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
(35, 'Kem trị nám 30g', 'LilyWhite', 'Trị mụn, giảm thâm, làm sáng da', 'Kem chứa Niacinamide, chiết xuất cam thảo giúp giảm nám, sáng đều màu da, hỗ trợ cải thiện sắc tố da.', 'Niacinamide, Chiết xuất cam thảo,Kem, Glycerin, Paraben, Không mùi', 'sp15.jpg', '2026-11-30', 300000.00, 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanh_toan`
--

CREATE TABLE `thanh_toan` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `phuong_thuc` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `trang_thai` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Chờ thanh toán',
  `ngay_thanh_toan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `fullname` varchar(255) DEFAULT NULL
  `id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'user',
  `blocked` tinyint(1) NOT NULL DEFAULT '0',
  `fullname` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `email`, `phone`, `address`, `password`, `role`, `blocked`, `fullname`) VALUES
(1, 'nemakira001@gmail.com', '03881901703', '2 võ oanh', '$2y$10$A3/arVBhQi1DqCshmTwHMu2Nps7v.iVAt/8LHE2U9KaDUhNFvalxC', 'user', 0, 'Nguyễn Thiên Namm');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `danh_gia`
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
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_don` (`ma_don`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `san_pham_id` (`san_pham_id`);

--
-- Chỉ mục cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sanpham_danhmuc` (`ma_danh_muc`);

--
-- Chỉ mục cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `ma_danh_muc` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kho_hang`
--
ALTER TABLE `kho_hang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `fk_danhgia_sanpham` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_danhgia_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `kho_hang`
--
ALTER TABLE `kho_hang`
  ADD CONSTRAINT `fk_khohang_sanpham` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_orderitems_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orderitems_sanpham` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`);

--
-- Constraints for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `fk_sanpham_danhmuc` FOREIGN KEY (`ma_danh_muc`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD CONSTRAINT `fk_thanhtoan_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
