-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: cinema_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `admin_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `is_super` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Super Admin','admin@cinema.vn','$2y$12$aIw6DOouAVgkL8FVa854p.2BUDz5i8aKQFz2pPbfC0tJo0WW/Eu1u','0909123456',1,1,NULL,'2025-11-30 04:56:47','2025-11-30 05:03:28'),(2,'Admin','admin@example.com','$2y$12$NafuKrdWu9WGn/IDOoMMCe1y4c4MS966iecQwh58Iwp6A7sj3tMKa','0123456789',1,1,NULL,'2025-12-04 06:23:32','2025-12-04 06:23:32');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('ghien-cine-cache-abc@gmail.com|127.0.0.1','i:1;',1765331804),('ghien-cine-cache-abc@gmail.com|127.0.0.1:timer','i:1765331804;',1765331804),('ghien-cine-cache-admin|admin@cinema.com|127.0.0.1','i:2;',1765332975),('ghien-cine-cache-admin|admin@cinema.com|127.0.0.1:timer','i:1765332975;',1765332975),('ghien-cine-cache-admin|admin@cinema.v|127.0.0.1','i:1;',1765294069),('ghien-cine-cache-admin|admin@cinema.v|127.0.0.1:timer','i:1765294069;',1765294069);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `cate_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`cate_id`),
  UNIQUE KEY `uq_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (3,'Hài hước'),(1,'Hành động - Phiêu lưu'),(5,'Hoạt hình - Gia đình'),(6,'Khoa học viễn tưởng - Giả tưởng'),(4,'Kinh dị - Giật gân'),(2,'Tình cảm - Lãng mạn');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cinemas`
--

DROP TABLE IF EXISTS `cinemas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cinemas` (
  `cinema_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cinema_name` varchar(150) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=hoạt động, 0=ngừng',
  PRIMARY KEY (`cinema_id`),
  UNIQUE KEY `uq_name_address` (`cinema_name`,`address`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cinemas`
--

LOCK TABLES `cinemas` WRITE;
/*!40000 ALTER TABLE `cinemas` DISABLE KEYS */;
INSERT INTO `cinemas` VALUES (1,'Rạp phim Vincom Center','Tầng 5, Vincom Center, Hà Nội','02499998888',1),(2,'Rạp phim Landmark 81','Vinhomes Landmark 81, TP.HCM','02899997777',1);
/*!40000 ALTER TABLE `cinemas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `combos`
--

DROP TABLE IF EXISTS `combos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `combos` (
  `combo_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `combo_name` varchar(150) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `price` int(10) unsigned NOT NULL COMMENT 'VNĐ',
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`combo_id`),
  UNIQUE KEY `uq_combo_name` (`combo_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `combos`
--

LOCK TABLES `combos` WRITE;
/*!40000 ALTER TABLE `combos` DISABLE KEYS */;
INSERT INTO `combos` VALUES (1,'Combo Solo','1 ly nước ngọt (700ml) + 1 bắp rang cỡ nhỏ',80000,'cb_solo.png',1),(2,'Combo Couple','2 ly nước ngọt + 1 bắp rang cỡ vừa',80000,'cb_couple.png',1),(3,'Combo Family','3 ly nước + 1 bắp lớn + khoai tây chiên',160000,'cb_family.png',1),(4,'Combo Premium','2 nước ngọt + bắp caramel + xúc xích phô mai',145000,'cb_premium.png',1),(5,'Combo Student','1 bắp thường + 1 nước ngọt (ưu đãi SV)',50000,'cb_student.png',1),(6,'Combo Classic','1 bắp tự chọn + 1 nước',60000,'cb_student.png',1),(7,'Combo VIP','2 nước ép + bắp caramel + sushi mini',200000,'cb_VIP.png',1);
/*!40000 ALTER TABLE `combos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'0001_01_01_000002_create_jobs_table',1),(3,'2025_11_26_144437_create_users_table',1),(4,'2025_11_26_144438_create_categories_table',1),(5,'2025_11_26_144438_create_cinemas_table',1),(6,'2025_11_26_144438_create_combos_table',1),(7,'2025_11_26_144438_create_movies_table',1),(8,'2025_11_26_144438_create_rooms_table',1),(9,'2025_11_26_144438_create_seats_table',1),(10,'2025_11_26_144438_create_shows_table',1),(11,'2025_11_26_144439_create_reservations_table',1),(12,'2025_11_26_144440_create_reservation_combos_table',1),(13,'2025_11_26_144440_create_reservation_seats_table',1),(14,'2025_11_26_144441_create_payments_table',1),(15,'2025_11_26_144441_create_promocode_table',1),(16,'2025_11_26_144441_create_seat_holds_table',1),(17,'2025_11_30_114538_create_admins_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movies`
--

DROP TABLE IF EXISTS `movies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movies` (
  `movie_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `cate_id` tinyint(3) unsigned DEFAULT NULL,
  `director` varchar(150) DEFAULT NULL,
  `duration` smallint(5) unsigned NOT NULL COMMENT 'phút',
  `description` text DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `early_premiere_date` date DEFAULT NULL COMMENT 'Ngày chiếu sớm/chiếu đặc biệt (trước release_date)',
  `poster` varchar(255) DEFAULT NULL,
  `trailer` varchar(255) DEFAULT NULL,
  `rating` tinyint(3) unsigned DEFAULT NULL COMMENT 'x10, ví dụ 45 = 4.5',
  `age_limit` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0=P,13=T13,16=T16,18=T18',
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '1=sắp chiếu,2=đang chiếu,3=kết thúc',
  `created_at` date NOT NULL DEFAULT curdate(),
  PRIMARY KEY (`movie_id`),
  UNIQUE KEY `movies_slug_unique` (`slug`),
  KEY `idx_title` (`title`),
  KEY `idx_release` (`release_date`),
  KEY `idx_status` (`status`),
  KEY `movies_cate_id_foreign` (`cate_id`),
  CONSTRAINT `movies_cate_id_foreign` FOREIGN KEY (`cate_id`) REFERENCES `categories` (`cate_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movies`
--

LOCK TABLES `movies` WRITE;
/*!40000 ALTER TABLE `movies` DISABLE KEYS */;
INSERT INTO `movies` VALUES (1,'Tử Chiến Trên Không','tử-chiến-trên-không-1',1,'Lê Nhật Quang',118,'Tử Chiến Trên Không kể về Bình, chuyên viên cảnh vệ hàng không, vô tình rơi vào cuộc đối đầu sinh tử khi chuyến bay anh đi bị nhóm không tặc do Long cầm đầu khống chế. Trong 15 phút sau khi cất cánh, máy bay trở thành chiến trường. Bình cùng phi hành đoàn và hành khách phải phối hợp chống trả, ngăn chặn âm mưu tàn độc của bọn cướp, bảo vệ tính mạng mọi người giữa bầu trời không lối thoát.','2025-09-19',NULL,'Tử chiến trên không.jpg','https://www.youtube.com/watch?v=iJ6lKh698Js',43,16,3,'2025-08-15'),(2,'Cậu Bé Cá Heo và Bí Mật 7 Đại Dương','cậu-bé-cá-heo-và-bí-mật-7-đại-dương-2',5,'Mohammad Kheirandish',96,'Cậu bé cá heo và những người bạn đồng hành bắt đầu cuộc hành trình mới đầy nguy hiểm để giải cứu Majid khỏi nanh vuốt cá voi hoang dã. Cuộc hành trình đã vô tình khiến cậu bé cá heo phát hiện ra lọ thuốc bí ẩn mà cha cậu phát minh và Majid đã biến thành sinh vật quái dị sau khi uống nó. Với sự giúp đỡ của mọi người, cậu bé cá heo đã đánh bại Majid thành công và mang bình yên trở lại cho dân làng và biển cả.','2025-10-03',NULL,'Cậu bé cá heo và bí mật 7 đại dương.png','https://www.youtube.com/watch?v=EqK34MmMqrE',40,0,3,'2025-09-03'),(3,'Bịt Mắt Bắt Nai','bịt-mắt-bắt-nai-3',4,'Hoàng Thơ',92,'Trang - một nhân viên bất động sản bị cưỡng bức. Cô lo sợ bạn trai Hiệp sẽ chia tay nên đã \"dụ\" anh đến một homestay để cầu hôn. Tại đây, cô hoảng loạn khi gặp Long - chủ homestay, người giống hệt kẻ đã hãm hại mình; bên cạnh đó Ngọc - vợ của Long cũng là nạn nhân của tên này. Khi Trang âm thầm tìm hiểu sự thật, thì mọi thứ càng lại càng phức tạp hơn và có một âm mưu đen tối đang chờ đợi tất cả bọn họ.','2025-10-31',NULL,'Bịt mắt bắt nai.png','https://www.youtube.com/watch?v=ABdyHbWAPIQ',45,16,2,'2025-10-01'),(4,'Cục Vàng Của Ngoại','cục-vàng-của-ngoại-4',2,'Khương Ngọc',119,'Lấy cảm hứng từ những ký ức tuổi thơ ngọt ngào, “Cục Vàng Của Ngoại” mang đến câu chuyện ấm áp về tình bà cháu trong một xóm nhỏ chan chứa nghĩa tình.','2025-10-17',NULL,'Cục vàng của ngoại.jpg','https://www.youtube.com/watch?v=Yy2QGcImoKI&t=1s',46,13,2,'2025-10-01'),(5,'Cải Mả','cải-mả-5',4,'Thắng Vũ',115,'Khi đại gia đình ông Quang trở về quê để thực hiện nghi lễ cải táng đã bị trì hoãn quá lâu, họ không chỉ đối diện với những nghi thức tâm linh, mà còn vô tình khơi dậy vòng xoáy nghiệp báo truyền đời.','2025-10-31',NULL,'Cải mả.png','https://www.youtube.com/watch?v=b_0KRi-6xRg',45,16,2,'2025-10-01'),(6,'Phá Đám Sinh Nhật Mẹ','phá-đám-sinh-nhật-mẹ-6',2,'Nguyễn Thanh Bình',91,'Bị giang hồ đe doạ, một người con trai đã làm đám ma giả cho mẹ mình để lừa tiền bảo hiểm. nhưng kế hoạch bất hiếu điên rồ của anh liên tục bị phá đám bởi từ người lạ đến người quen, nhất là khi ngày anh đưa mẹ vào hòm lại tình cờ là ngày sinh nhật 60 tuổi của bà.','2025-10-30',NULL,'Phá đám sinh nhật mẹ.jpg','https://www.youtube.com/watch?v=dBsJYwaBbLA&t=2s',40,16,2,'2025-10-01'),(7,'Tình Người Duyên Ma 2025','tình-người-duyên-ma-2025-7',3,'Choosak Iamsook',104,'Lấy cảm hứng từ truyền thuyết dân gian Thái Lan về hồn ma Mae Nak, Tình Người Duyên Ma: Nhắm Mak Yêu Luôn kể câu chuyện tình vượt thời gian giữa nàng Nak và chàng Mak. Xuyên không đến 200 năm sau, Nak bất ngờ được vào vai nữ chính trong chính bộ phim về truyền thuyết của mình. Tình cờ thay, vai nam chính lại được thủ bởi Mak - lúc này đã là một nam diễn viên nổi tiếng toàn quốc. Ở đây, Nak phải chinh phục lại trái tim Mak trong vòng 30 ngày mà không được dùng đến ma lực, để có thể ở bên anh trọn đời trọn kiếp.','2025-11-07',NULL,'Tình người duyên ma 2025.png','https://www.youtube.com/watch?v=xUhwAp08Q08',39,13,2,'2025-10-01'),(8,'Quỷ Tha Ma Bắt: Thai Chiêu Tài','quỷ-tha-ma-bắt-thai-chiêu-tài-8',4,'Trần Nhân Kiên',104,'Nhơn, một doanh nhân thành đạt nhờ thủ đoạn và mưu mẹo, tìm đến thứ tà thuật mang tên “Thai Chiêu Tài” để giữ lấy tài khí đã vô tình khơi dậy những ám ảnh từ quá khứ và sang chấn liên thế hệ.','2025-11-07',NULL,'Thai chiêu tài.jpg','https://www.youtube.com/watch?v=2kANaFSWCgQ',46,18,2,'2025-10-15'),(9,'Truy Tìm Long Diên Hương','truy-tìm-long-diên-hương-9',3,'Dương Minh Chiến',103,'Báu vật làng biển Long Diên Hương bị đánh cắp, mở ra cuộc hành trình truy tìm đầy kịch tính. Không chỉ có võ thuật mãn nhãn, bộ phim còn mang đến tiếng cười, sự gắn kết và những giá trị nhân văn của con người làng chài.','2025-11-14',NULL,'Truy tìm long diên hương.jpg','https://www.youtube.com/watch?v=aTVcY0QlWAE&t=2s',44,16,2,'2025-10-15'),(10,'Mộ Đom Đóm','mộ-đom-đóm-10',5,'Isao Takahata',89,'Bộ phim được đặt trong bối cảnh giai đoạn cuối chiến tranh thế giới thứ 2 ở Nhật, kể về câu chuyện cảm động về tình anh em của hai đứa trẻ mồ côi là Seita và Setsuko. Hai anh em mất mẹ trong một trận bom dữ dội của không quân Mỹ khi cha của chúng đang chiến đấu cho Hải quân Nhật. Hai đứa bé phải vật lộn giữa nạn đói, giữa sự thờ ơ của những người xung quanh...','2025-11-07',NULL,'Mộ đom đóm.png',NULL,41,0,3,'2025-10-15'),(11,'Trái Tìm Què Quặt','trái-tìm-què-quặt-11',1,'Nguyễn Quốc Công',102,'Một vụ án mạng tàn bạo làm chấn động thị trấn yên bình...','2025-11-07',NULL,'Trái tim què quặt.jpg','https://www.youtube.com/watch?v=vAZnD-kG68g&t=1s',42,18,2,'2025-10-15'),(12,'Quán Kỳ Nam','quán-kỳ-nam-12',2,'Lê Nhật Quang (Leon Le)',120,'Với sự nâng đỡ của người chú quyền lực, Khang được giao cho công việc dịch cuốn “Hoàng Tử Bé” và dọn vào căn hộ bỏ trống ở khu chung cư cũ...','2025-11-28',NULL,'Quán kỳ nam.jpg','https://www.youtube.com/watch?v=0X0C-9nSNwQ',NULL,16,2,'2025-10-15'),(13,'Bẫy Tiền','bẫy-tiền-13',1,'Oscar Dương',120,'Khi một vụ lừa đảo qua điện thoại bất ngờ ập đến, Đăng Thức tưởng chừng nắm trong tay cuộc sống ổn định bỗng bị cuốn vào vòng xoáy nguy hiểm giữa tiền bạc, tình thân và niềm tin...','2025-11-22',NULL,'Bẫy tiền.jpg','https://www.youtube.com/watch?v=hmcD4eg8Do8',40,13,2,'2025-11-01'),(14,'Cưới vợ cho cha','cưới-vợ-cho-cha-14',2,'Nguyễn Ngọc Lâm',112,'Tại một ngôi làng nhỏ yên bình ở miền Tây Việt Nam, ông Sáu Sều - chủ quán karaoke cà phê - sống một mình và mong ngóng từng ngày đứa con trai Út Tùng trở về thăm nhà sau thời gian làm việc ở Sài Gòn...','2025-11-21',NULL,'Cưới vợ cho cha.png','https://www.youtube.com/watch?v=pXCRj4OMLg0',41,0,2,'2025-11-01'),(15,'Wicked: Phần 2','wicked-phần-2-15',5,'Jon M. Chu',138,'Chương cuối của câu chuyện bắt đầu khi Elphaba và Glinda đã xa cách, mỗi người đang sống với hậu quả từ những lựa chọn của riêng mình...','2025-11-21',NULL,'Wikced_2.png',NULL,42,0,2,'2025-11-01'),(16,'Thế Hệ Kỳ Tích (Bà Đừng Buồn Con)','the-he-ky-tich-ba-dung-buon-con-16',2,'Hoàng Nam',120,'Chàng sinh viên Tiến mang trong mình giấc mơ tạo ra tựa game vươn tầm thế giới...','2025-12-12',NULL,'Thế hệ kỳ tích.png',NULL,NULL,13,1,'2025-11-01'),(17,'Ai Thương Ai Mến','ai-thương-ai-mến-17',2,'Thu Trang',120,'Bộ phim lấy bối cảnh miền Tây sông nước năm 1960, kể về hành trình cuộc đời của Mến...','2026-01-01',NULL,'Ai thương ai mến.jpg',NULL,NULL,13,1,'2025-11-01'),(18,'Hoàng Tử Quỷ','hoang-tu-quy-18',4,'Trần Hữu Tấn',120,'Hoàng Tử Quỷ xoay quanh Thân Đức - một hoàng tử được sinh ra nhờ tà thuật...','2025-12-05',NULL,'Hoàng tử quỷ.png','https://www.youtube.com/watch?v=8sN-kdDxPSM',NULL,16,2,'2025-11-01'),(24,'Quái Thú Vô Hình: Vùng Đất Chết Chóc','quai-thu-vo-hinh-vung-dat-chet-choc-24',1,'Joshua Watkins, Dan Trachtenberg, Richard Cowan',110,'Quái Thú Vô Hình: Vùng Đất Chết Chóc với sự tham gia của Elle Fanning và Dimitrius Schuster-Koloamatangi, được đặt trong bối cảnh tương lai trên một hành tinh xa xôi, nơi một Predator trẻ tuổi (Schuster-Koloamatangi), bị dòng tộc của mình xa lánh, và sau đó tìm thấy một đồng minh không ngờ là Thia (Fanning) và bắt đầu một hành trình đầy nguy hiểm để tìm kiếm kẻ thù mạnh nhất. Bộ phim do Dan Trachtenberg đạo diễn và được sản xuất bởi John Davis, Dan Trachtenberg, Marc Toberoff, Ben Rosenblatt, Brent O’Connor.','2026-01-15','2026-01-01','1765302342_chó cưng đừng sợ.jpg','https://www.youtube.com/watch?v=AzBi73ddou4',NULL,16,1,'2025-12-10');
/*!40000 ALTER TABLE `movies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `payment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `booking_code` char(12) DEFAULT NULL,
  `amount` int(10) unsigned NOT NULL,
  `payment_method` tinyint(3) unsigned NOT NULL COMMENT '1=Momo,2=VNPay,3=ZaloPay...',
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '1=pending,2=success,3=failed',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `uq_order` (`order_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promo_user_usage`
--

DROP TABLE IF EXISTS `promo_user_usage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promo_user_usage` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `promo_code` varchar(20) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `booking_code` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_promo_user` (`promo_code`,`user_id`),
  KEY `fk_promo_code` (`promo_code`),
  KEY `fk_user_id` (`user_id`),
  KEY `idx_booking_code` (`booking_code`),
  CONSTRAINT `fk_booking_code` FOREIGN KEY (`booking_code`) REFERENCES `reservations` (`booking_code`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_promo_code` FOREIGN KEY (`promo_code`) REFERENCES `promocode` (`promo_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promo_user_usage`
--

LOCK TABLES `promo_user_usage` WRITE;
/*!40000 ALTER TABLE `promo_user_usage` DISABLE KEYS */;
INSERT INTO `promo_user_usage` VALUES (1,'NEWYEAR2026',28,'25VI0GIVSB','2025-12-09 10:00:50','2025-12-09 10:00:50'),(2,'VIP50',28,'25P39VM00D','2025-12-10 18:02:56','2025-12-10 18:02:56');
/*!40000 ALTER TABLE `promo_user_usage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promocode`
--

DROP TABLE IF EXISTS `promocode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promocode` (
  `promo_code` varchar(20) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `discount_type` tinyint(3) unsigned NOT NULL COMMENT '1=percent,2=amount',
  `discount_value` int(10) unsigned NOT NULL,
  `min_order_value` int(10) unsigned NOT NULL DEFAULT 0,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `usage_limit` int(10) unsigned DEFAULT 0,
  `used_count` int(10) unsigned NOT NULL DEFAULT 0,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`promo_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promocode`
--

LOCK TABLES `promocode` WRITE;
/*!40000 ALTER TABLE `promocode` DISABLE KEYS */;
INSERT INTO `promocode` VALUES ('FLAT10000','Giảm 10,000 VNĐ',2,10000,0,'2025-11-01','2025-12-31',100,0,0),('HOLIDAY30','Giảm 30% cuối tuần',1,30,0,'2025-11-01','2025-12-31',200,1,1),('NEWYEAR2026',NULL,1,20,0,'2025-12-15','2026-01-05',100,9,1),('VIP50','Giảm 50% cho combo VIP',1,50,0,'2025-11-05','2025-12-31',50,1,1),('XMAS2025','Giảm giá Giáng Sinh',2,20000,0,'2025-12-20','2025-12-31',500,0,1);
/*!40000 ALTER TABLE `promocode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservation_combos`
--

DROP TABLE IF EXISTS `reservation_combos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservation_combos` (
  `booking_code` char(12) NOT NULL,
  `combo_id` tinyint(3) unsigned NOT NULL,
  `quantity` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `combo_price` int(10) unsigned NOT NULL,
  PRIMARY KEY (`booking_code`,`combo_id`),
  KEY `reservation_combos_combo_id_foreign` (`combo_id`),
  CONSTRAINT `reservation_combos_booking_code_foreign` FOREIGN KEY (`booking_code`) REFERENCES `reservations` (`booking_code`) ON DELETE CASCADE,
  CONSTRAINT `reservation_combos_combo_id_foreign` FOREIGN KEY (`combo_id`) REFERENCES `combos` (`combo_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation_combos`
--

LOCK TABLES `reservation_combos` WRITE;
/*!40000 ALTER TABLE `reservation_combos` DISABLE KEYS */;
INSERT INTO `reservation_combos` VALUES ('25CA5PMCE1',2,1,80000),('25EUU2OINN',1,1,80000),('25EUU2OINN',2,1,80000),('25EUU2OINN',3,1,160000),('25EUU2OINN',4,1,145000),('25EUU2OINN',5,1,50000),('25EUU2OINN',6,1,60000),('25EUU2OINN',7,1,200000),('25NLBZMMMU',4,1,145000),('25P39VM00D',2,1,80000),('25PLGVMGZH',4,1,145000),('25QIRUSK3U',2,1,80000),('25XTFDSCI7',2,1,80000);
/*!40000 ALTER TABLE `reservation_combos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservation_seats`
--

DROP TABLE IF EXISTS `reservation_seats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservation_seats` (
  `booking_code` char(12) NOT NULL,
  `seat_id` int(10) unsigned NOT NULL,
  `seat_price` int(10) unsigned NOT NULL,
  PRIMARY KEY (`booking_code`,`seat_id`),
  KEY `reservation_seats_seat_id_foreign` (`seat_id`),
  CONSTRAINT `reservation_seats_booking_code_foreign` FOREIGN KEY (`booking_code`) REFERENCES `reservations` (`booking_code`) ON DELETE CASCADE,
  CONSTRAINT `reservation_seats_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`seat_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation_seats`
--

LOCK TABLES `reservation_seats` WRITE;
/*!40000 ALTER TABLE `reservation_seats` DISABLE KEYS */;
INSERT INTO `reservation_seats` VALUES ('25BAY51UNQ',104,50000),('25CA5PMCE1',105,50000),('25CA5PMCE1',106,50000),('25EUU2OINN',175,50000),('25EUU2OINN',176,50000),('25HPO5S7FX',115,80000),('25HPO5S7FX',116,80000),('25NLBZMMMU',45,80000),('25NLBZMMMU',75,60000),('25P39VM00D',46,80000),('25P39VM00D',47,80000),('25PLGVMGZH',105,50000),('25PLGVMGZH',106,50000),('25PLGVMGZH',107,50000),('25QIRUSK3U',165,50000),('25QIRUSK3U',166,50000),('25VI0GIVSB',107,50000),('25VI0GIVSB',108,50000),('25VYBNXD3Q',126,50000),('25VYBNXD3Q',127,50000),('25XBZ0ZTNK',103,50000),('25XBZ0ZTNK',104,50000),('25XTFDSCI7',115,80000),('25XTFDSCI7',116,80000),('25XTFDSCI7',135,60000),('25ZMYIAPZU',117,80000),('25ZRKCTXUY',167,50000);
/*!40000 ALTER TABLE `reservation_seats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservations` (
  `booking_code` varchar(50) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `show_id` varbinary(16) NOT NULL,
  `total_amount` int(10) unsigned NOT NULL COMMENT 'VNĐ',
  `status` varchar(20) DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`booking_code`),
  KEY `idx_user` (`user_id`),
  KEY `idx_show` (`show_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `reservations_show_id_foreign` FOREIGN KEY (`show_id`) REFERENCES `shows` (`show_id`) ON DELETE CASCADE,
  CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
INSERT INTO `reservations` VALUES ('25BAY51UNQ',28,'SHOW20251211002\0',0,'paid','2025-12-09 16:56:08'),('25CA5PMCE1',28,'SHOW20251211002\0',0,'paid','2025-12-09 16:52:04'),('25EUU2OINN',28,'SHOW20251205002',0,'paid','2025-12-05 21:00:56'),('25HPO5S7FX',28,'SHOW20251211002\0',160000,'paid','2025-12-09 17:16:45'),('25NLBZMMMU',28,'SHOW20251212002',285000,'paid','2025-12-09 21:05:24'),('25P39VM00D',28,'SHOW20251212002',120000,'paid','2025-12-11 01:01:54'),('25PLGVMGZH',9,'SHOW20251206001',0,'paid','2025-12-05 21:59:20'),('25QIRUSK3U',28,'SHOW20251205002',180000,'paid','2025-12-05 20:57:05'),('25VI0GIVSB',28,'SHOW20251211002\0',0,'paid','2025-12-09 17:00:50'),('25VYBNXD3Q',28,'SHOW20251208002',100000,'paid','2025-12-08 15:06:19'),('25XBZ0ZTNK',28,'SHOW20251206001',100000,'paid','2025-12-05 23:09:25'),('25XTFDSCI7',28,'SHOW20251206001',300000,'paid','2025-12-05 21:13:21'),('25ZMYIAPZU',9,'SHOW20251206001',80000,'paid','2025-12-05 21:57:30'),('25ZRKCTXUY',28,'SHOW20251205002',50000,'paid','2025-12-05 20:51:02');
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rooms` (
  `room_code` char(6) NOT NULL,
  `cinema_id` smallint(5) unsigned NOT NULL,
  `room_name` varchar(100) DEFAULT NULL,
  `room_type` tinyint(3) unsigned NOT NULL COMMENT '1=Normal,2=VIP,3=IMAX',
  `total_seats` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`room_code`),
  UNIQUE KEY `uq_room_cinema` (`cinema_id`,`room_code`),
  CONSTRAINT `rooms_cinema_id_foreign` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`cinema_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES ('R101',1,'Phòng 1',0,80),('R102',1,'Phòng 2',0,60),('R103',1,'Phòng 3',0,90),('R104',1,'Phòng 4',0,80),('R105',1,'Phòng 5',0,120),('R201',2,'Hall A',0,80),('R202',2,'Hall B',0,100),('R203',2,'Hall C',0,120),('R204',2,'Hall D',0,120);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seat_holds`
--

DROP TABLE IF EXISTS `seat_holds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seat_holds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `show_id` varbinary(16) NOT NULL,
  `seat_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_hold` (`show_id`,`seat_id`),
  KEY `idx_expires` (`expires_at`),
  KEY `seat_holds_seat_id_foreign` (`seat_id`),
  KEY `seat_holds_expires_at_index` (`expires_at`),
  CONSTRAINT `seat_holds_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`seat_id`) ON DELETE CASCADE,
  CONSTRAINT `seat_holds_show_id_foreign` FOREIGN KEY (`show_id`) REFERENCES `shows` (`show_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seat_holds`
--

LOCK TABLES `seat_holds` WRITE;
/*!40000 ALTER TABLE `seat_holds` DISABLE KEYS */;
INSERT INTO `seat_holds` VALUES (21,'0535f200-c845-11',789,7,'2025-11-27 13:06:57',NULL),(23,'052e66ad-c845-11',267,7,'2025-11-27 13:37:36',NULL),(34,'0535f2db-c845-11',432,7,'2025-11-27 18:23:24',NULL),(35,'0535f3af-c845-11',537,7,'2025-11-28 06:00:27',NULL),(36,'052e67b7-c845-11',81,7,'2025-11-28 07:03:12',NULL),(44,'0535fc28-c845-11',744,7,'2025-11-30 12:35:00',NULL),(59,'052e6dc0-c845-11',317,7,'2025-11-30 15:31:26',NULL),(71,'SHOW20251130002',103,7,'2025-12-01 09:05:41',NULL),(72,'SHOW20251202001',23,7,'2025-12-01 15:34:01',NULL),(83,'SHOW20251204001',15,7,'2025-12-03 18:32:34',NULL),(87,'SHOW20251204002',25,7,'2025-12-04 04:24:21',NULL),(88,'SHOW20251204002',26,7,'2025-12-04 04:24:21',NULL),(98,'SHOW20251205001\0',25,7,'2025-12-04 09:54:47',NULL),(108,'SHOW20251205002',148,9,'2025-12-05 10:27:26',NULL),(117,'SHOW20251205002',175,28,'2025-12-05 14:08:50',NULL),(118,'SHOW20251205002',176,28,'2025-12-05 14:08:50',NULL),(123,'SHOW20251206001',107,9,'2025-12-05 15:08:49',NULL),(124,'SHOW20251206001',106,9,'2025-12-05 15:08:49',NULL),(125,'SHOW20251206001',105,9,'2025-12-05 15:08:49',NULL),(143,'SHOW20251206001',108,28,'2025-12-05 16:34:17',NULL),(144,'SHOW20251208002',126,28,'2025-12-08 08:15:34',NULL),(145,'SHOW20251208002',127,28,'2025-12-08 08:15:34',NULL),(166,'SHOW20251212003',33,28,'2025-12-09 01:56:55',NULL),(179,'SHOW20251211002\0',117,28,'2025-12-09 10:30:57',NULL),(182,'SHOW20260101001',36,28,'2025-12-10 10:48:16',NULL),(183,'SHOW20251212002',46,28,'2025-12-10 18:11:22',NULL),(184,'SHOW20251212002',47,28,'2025-12-10 18:11:23',NULL);
/*!40000 ALTER TABLE `seat_holds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seats`
--

DROP TABLE IF EXISTS `seats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seats` (
  `seat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_code` char(6) NOT NULL,
  `seat_num` char(4) NOT NULL COMMENT 'ví dụ A1, E10',
  `seat_type` tinyint(3) unsigned NOT NULL COMMENT '1=Regular,2=VIP,3=Couple',
  `default_price` int(10) unsigned NOT NULL COMMENT 'VNĐ',
  PRIMARY KEY (`seat_id`),
  UNIQUE KEY `uq_seat_room` (`room_code`,`seat_num`),
  CONSTRAINT `seats_room_code_foreign` FOREIGN KEY (`room_code`) REFERENCES `rooms` (`room_code`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=851 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seats`
--

LOCK TABLES `seats` WRITE;
/*!40000 ALTER TABLE `seats` DISABLE KEYS */;
INSERT INTO `seats` VALUES (1,'R101','A1',1,50000),(2,'R101','A2',1,50000),(3,'R101','A3',1,50000),(4,'R101','A4',1,50000),(5,'R101','A5',1,50000),(6,'R101','A6',1,50000),(7,'R101','A7',1,50000),(8,'R101','A8',1,50000),(9,'R101','A9',1,50000),(10,'R101','A10',1,50000),(11,'R101','B1',1,50000),(12,'R101','B2',1,50000),(13,'R101','B3',1,50000),(14,'R101','B4',1,50000),(15,'R101','B5',1,50000),(16,'R101','B6',1,50000),(17,'R101','B7',1,50000),(18,'R101','B8',1,50000),(19,'R101','B9',1,50000),(20,'R101','B10',1,50000),(21,'R101','C1',1,50000),(22,'R101','C2',1,50000),(23,'R101','C3',1,50000),(24,'R101','C4',1,50000),(25,'R101','C5',1,50000),(26,'R101','C6',1,50000),(27,'R101','C7',1,50000),(28,'R101','C8',1,50000),(29,'R101','C9',1,50000),(30,'R101','C10',1,50000),(31,'R101','D1',1,50000),(32,'R101','D2',1,50000),(33,'R101','D3',1,50000),(34,'R101','D4',1,50000),(35,'R101','D5',1,50000),(36,'R101','D6',1,50000),(37,'R101','D7',1,50000),(38,'R101','D8',1,50000),(39,'R101','D9',1,50000),(40,'R101','D10',1,50000),(41,'R101','E1',2,80000),(42,'R101','E2',2,80000),(43,'R101','E3',2,80000),(44,'R101','E4',2,80000),(45,'R101','E5',2,80000),(46,'R101','E6',2,80000),(47,'R101','E7',2,80000),(48,'R101','E8',2,80000),(49,'R101','E9',2,80000),(50,'R101','E10',2,80000),(51,'R101','F1',1,50000),(52,'R101','F2',1,50000),(53,'R101','F3',1,50000),(54,'R101','F4',1,50000),(55,'R101','F5',1,50000),(56,'R101','F6',1,50000),(57,'R101','F7',1,50000),(58,'R101','F8',1,50000),(59,'R101','F9',1,50000),(60,'R101','F10',1,50000),(61,'R101','G1',1,50000),(62,'R101','G2',1,50000),(63,'R101','G3',1,50000),(64,'R101','G4',1,50000),(65,'R101','G5',1,50000),(66,'R101','G6',1,50000),(67,'R101','G7',1,50000),(68,'R101','G8',1,50000),(69,'R101','G9',1,50000),(70,'R101','G10',1,50000),(71,'R101','H1',3,60000),(72,'R101','H2',3,60000),(73,'R101','H3',3,60000),(74,'R101','H4',3,60000),(75,'R101','H5',3,60000),(76,'R101','H6',3,60000),(77,'R101','H7',3,60000),(78,'R101','H8',3,60000),(79,'R101','H9',3,60000),(80,'R101','H10',3,60000),(81,'R102','A1',1,50000),(82,'R102','A2',1,50000),(83,'R102','A3',1,50000),(84,'R102','A4',1,50000),(85,'R102','A5',1,50000),(86,'R102','A6',1,50000),(87,'R102','A7',1,50000),(88,'R102','A8',1,50000),(89,'R102','A9',1,50000),(90,'R102','A10',1,50000),(91,'R102','B1',1,50000),(92,'R102','B2',1,50000),(93,'R102','B3',1,50000),(94,'R102','B4',1,50000),(95,'R102','B5',1,50000),(96,'R102','B6',1,50000),(97,'R102','B7',1,50000),(98,'R102','B8',1,50000),(99,'R102','B9',1,50000),(100,'R102','B10',1,50000),(101,'R102','C1',1,50000),(102,'R102','C2',1,50000),(103,'R102','C3',1,50000),(104,'R102','C4',1,50000),(105,'R102','C5',1,50000),(106,'R102','C6',1,50000),(107,'R102','C7',1,50000),(108,'R102','C8',1,50000),(109,'R102','C9',1,50000),(110,'R102','C10',1,50000),(111,'R102','D1',2,80000),(112,'R102','D2',2,80000),(113,'R102','D3',2,80000),(114,'R102','D4',2,80000),(115,'R102','D5',2,80000),(116,'R102','D6',2,80000),(117,'R102','D7',2,80000),(118,'R102','D8',2,80000),(119,'R102','D9',2,80000),(120,'R102','D10',2,80000),(121,'R102','E1',1,50000),(122,'R102','E2',1,50000),(123,'R102','E3',1,50000),(124,'R102','E4',1,50000),(125,'R102','E5',1,50000),(126,'R102','E6',1,50000),(127,'R102','E7',1,50000),(128,'R102','E8',1,50000),(129,'R102','E9',1,50000),(130,'R102','E10',1,50000),(131,'R102','F1',3,60000),(132,'R102','F2',3,60000),(133,'R102','F3',3,60000),(134,'R102','F4',3,60000),(135,'R102','F5',3,60000),(136,'R102','F6',3,60000),(137,'R102','F7',3,60000),(138,'R102','F8',3,60000),(139,'R102','F9',3,60000),(140,'R102','F10',3,60000),(141,'R103','A1',1,50000),(142,'R103','A2',1,50000),(143,'R103','A3',1,50000),(144,'R103','A4',1,50000),(145,'R103','A5',1,50000),(146,'R103','A6',1,50000),(147,'R103','A7',1,50000),(148,'R103','A8',1,50000),(149,'R103','A9',1,50000),(150,'R103','A10',1,50000),(151,'R103','B1',1,50000),(152,'R103','B2',1,50000),(153,'R103','B3',1,50000),(154,'R103','B4',1,50000),(155,'R103','B5',1,50000),(156,'R103','B6',1,50000),(157,'R103','B7',1,50000),(158,'R103','B8',1,50000),(159,'R103','B9',1,50000),(160,'R103','B10',1,50000),(161,'R103','C1',1,50000),(162,'R103','C2',1,50000),(163,'R103','C3',1,50000),(164,'R103','C4',1,50000),(165,'R103','C5',1,50000),(166,'R103','C6',1,50000),(167,'R103','C7',1,50000),(168,'R103','C8',1,50000),(169,'R103','C9',1,50000),(170,'R103','C10',1,50000),(171,'R103','D1',1,50000),(172,'R103','D2',1,50000),(173,'R103','D3',1,50000),(174,'R103','D4',1,50000),(175,'R103','D5',1,50000),(176,'R103','D6',1,50000),(177,'R103','D7',1,50000),(178,'R103','D8',1,50000),(179,'R103','D9',1,50000),(180,'R103','D10',1,50000),(181,'R103','E1',2,80000),(182,'R103','E2',2,80000),(183,'R103','E3',2,80000),(184,'R103','E4',2,80000),(185,'R103','E5',2,80000),(186,'R103','E6',2,80000),(187,'R103','E7',2,80000),(188,'R103','E8',2,80000),(189,'R103','E9',2,80000),(190,'R103','E10',2,80000),(191,'R103','F1',1,50000),(192,'R103','F2',1,50000),(193,'R103','F3',1,50000),(194,'R103','F4',1,50000),(195,'R103','F5',1,50000),(196,'R103','F6',1,50000),(197,'R103','F7',1,50000),(198,'R103','F8',1,50000),(199,'R103','F9',1,50000),(200,'R103','F10',1,50000),(201,'R103','G1',1,50000),(202,'R103','G2',1,50000),(203,'R103','G3',1,50000),(204,'R103','G4',1,50000),(205,'R103','G5',1,50000),(206,'R103','G6',1,50000),(207,'R103','G7',1,50000),(208,'R103','G8',1,50000),(209,'R103','G9',1,50000),(210,'R103','G10',1,50000),(211,'R103','H1',1,50000),(212,'R103','H2',1,50000),(213,'R103','H3',1,50000),(214,'R103','H4',1,50000),(215,'R103','H5',1,50000),(216,'R103','H6',1,50000),(217,'R103','H7',1,50000),(218,'R103','H8',1,50000),(219,'R103','H9',1,50000),(220,'R103','H10',1,50000),(221,'R103','I1',3,60000),(222,'R103','I2',3,60000),(223,'R103','I3',3,60000),(224,'R103','I4',3,60000),(225,'R103','I5',3,60000),(226,'R103','I6',3,60000),(227,'R103','I7',3,60000),(228,'R103','I8',3,60000),(229,'R103','I9',3,60000),(230,'R103','I10',3,60000),(231,'R104','A1',1,50000),(232,'R104','A2',1,50000),(233,'R104','A3',1,50000),(234,'R104','A4',1,50000),(235,'R104','A5',1,50000),(236,'R104','A6',1,50000),(237,'R104','A7',1,50000),(238,'R104','A8',1,50000),(239,'R104','A9',1,50000),(240,'R104','A10',1,50000),(241,'R104','B1',1,50000),(242,'R104','B2',1,50000),(243,'R104','B3',1,50000),(244,'R104','B4',1,50000),(245,'R104','B5',1,50000),(246,'R104','B6',1,50000),(247,'R104','B7',1,50000),(248,'R104','B8',1,50000),(249,'R104','B9',1,50000),(250,'R104','B10',1,50000),(251,'R104','C1',1,50000),(252,'R104','C2',1,50000),(253,'R104','C3',1,50000),(254,'R104','C4',1,50000),(255,'R104','C5',1,50000),(256,'R104','C6',1,50000),(257,'R104','C7',1,50000),(258,'R104','C8',1,50000),(259,'R104','C9',1,50000),(260,'R104','C10',1,50000),(261,'R104','D1',1,50000),(262,'R104','D2',1,50000),(263,'R104','D3',1,50000),(264,'R104','D4',1,50000),(265,'R104','D5',1,50000),(266,'R104','D6',1,50000),(267,'R104','D7',1,50000),(268,'R104','D8',1,50000),(269,'R104','D9',1,50000),(270,'R104','D10',1,50000),(271,'R104','E1',2,80000),(272,'R104','E2',2,80000),(273,'R104','E3',2,80000),(274,'R104','E4',2,80000),(275,'R104','E5',2,80000),(276,'R104','E6',2,80000),(277,'R104','E7',2,80000),(278,'R104','E8',2,80000),(279,'R104','E9',2,80000),(280,'R104','E10',2,80000),(281,'R104','F1',1,50000),(282,'R104','F2',1,50000),(283,'R104','F3',1,50000),(284,'R104','F4',1,50000),(285,'R104','F5',1,50000),(286,'R104','F6',1,50000),(287,'R104','F7',1,50000),(288,'R104','F8',1,50000),(289,'R104','F9',1,50000),(290,'R104','F10',1,50000),(291,'R104','G1',1,50000),(292,'R104','G2',1,50000),(293,'R104','G3',1,50000),(294,'R104','G4',1,50000),(295,'R104','G5',1,50000),(296,'R104','G6',1,50000),(297,'R104','G7',1,50000),(298,'R104','G8',1,50000),(299,'R104','G9',1,50000),(300,'R104','G10',1,50000),(301,'R104','H1',3,60000),(302,'R104','H2',3,60000),(303,'R104','H3',3,60000),(304,'R104','H4',3,60000),(305,'R104','H5',3,60000),(306,'R104','H6',3,60000),(307,'R104','H7',3,60000),(308,'R104','H8',3,60000),(309,'R104','H9',3,60000),(310,'R104','H10',3,60000),(311,'R105','A1',1,50000),(312,'R105','A2',1,50000),(313,'R105','A3',1,50000),(314,'R105','A4',1,50000),(315,'R105','A5',1,50000),(316,'R105','A6',1,50000),(317,'R105','A7',1,50000),(318,'R105','A8',1,50000),(319,'R105','A9',1,50000),(320,'R105','A10',1,50000),(321,'R105','B1',1,50000),(322,'R105','B2',1,50000),(323,'R105','B3',1,50000),(324,'R105','B4',1,50000),(325,'R105','B5',1,50000),(326,'R105','B6',1,50000),(327,'R105','B7',1,50000),(328,'R105','B8',1,50000),(329,'R105','B9',1,50000),(330,'R105','B10',1,50000),(331,'R105','C1',1,50000),(332,'R105','C2',1,50000),(333,'R105','C3',1,50000),(334,'R105','C4',1,50000),(335,'R105','C5',1,50000),(336,'R105','C6',1,50000),(337,'R105','C7',1,50000),(338,'R105','C8',1,50000),(339,'R105','C9',1,50000),(340,'R105','C10',1,50000),(341,'R105','D1',1,50000),(342,'R105','D2',1,50000),(343,'R105','D3',1,50000),(344,'R105','D4',1,50000),(345,'R105','D5',1,50000),(346,'R105','D6',1,50000),(347,'R105','D7',1,50000),(348,'R105','D8',1,50000),(349,'R105','D9',1,50000),(350,'R105','D10',1,50000),(351,'R105','E1',1,50000),(352,'R105','E2',1,50000),(353,'R105','E3',1,50000),(354,'R105','E4',1,50000),(355,'R105','E5',1,50000),(356,'R105','E6',1,50000),(357,'R105','E7',1,50000),(358,'R105','E8',1,50000),(359,'R105','E9',1,50000),(360,'R105','E10',1,50000),(361,'R105','F1',1,50000),(362,'R105','F2',1,50000),(363,'R105','F3',1,50000),(364,'R105','F4',1,50000),(365,'R105','F5',1,50000),(366,'R105','F6',1,50000),(367,'R105','F7',1,50000),(368,'R105','F8',1,50000),(369,'R105','F9',1,50000),(370,'R105','F10',1,50000),(371,'R105','G1',2,80000),(372,'R105','G2',2,80000),(373,'R105','G3',2,80000),(374,'R105','G4',2,80000),(375,'R105','G5',2,80000),(376,'R105','G6',2,80000),(377,'R105','G7',2,80000),(378,'R105','G8',2,80000),(379,'R105','G9',2,80000),(380,'R105','G10',2,80000),(381,'R105','H1',1,50000),(382,'R105','H2',1,50000),(383,'R105','H3',1,50000),(384,'R105','H4',1,50000),(385,'R105','H5',1,50000),(386,'R105','H6',1,50000),(387,'R105','H7',1,50000),(388,'R105','H8',1,50000),(389,'R105','H9',1,50000),(390,'R105','H10',1,50000),(391,'R105','I1',1,50000),(392,'R105','I2',1,50000),(393,'R105','I3',1,50000),(394,'R105','I4',1,50000),(395,'R105','I5',1,50000),(396,'R105','I6',1,50000),(397,'R105','I7',1,50000),(398,'R105','I8',1,50000),(399,'R105','I9',1,50000),(400,'R105','I10',1,50000),(401,'R105','J1',1,50000),(402,'R105','J2',1,50000),(403,'R105','J3',1,50000),(404,'R105','J4',1,50000),(405,'R105','J5',1,50000),(406,'R105','J6',1,50000),(407,'R105','J7',1,50000),(408,'R105','J8',1,50000),(409,'R105','J9',1,50000),(410,'R105','J10',1,50000),(411,'R105','K1',1,50000),(412,'R105','K2',1,50000),(413,'R105','K3',1,50000),(414,'R105','K4',1,50000),(415,'R105','K5',1,50000),(416,'R105','K6',1,50000),(417,'R105','K7',1,50000),(418,'R105','K8',1,50000),(419,'R105','K9',1,50000),(420,'R105','K10',1,50000),(421,'R105','L1',3,60000),(422,'R105','L2',3,60000),(423,'R105','L3',3,60000),(424,'R105','L4',3,60000),(425,'R105','L5',3,60000),(426,'R105','L6',3,60000),(427,'R105','L7',3,60000),(428,'R105','L8',3,60000),(429,'R105','L9',3,60000),(430,'R105','L10',3,60000),(431,'R201','A1',1,50000),(432,'R201','A2',1,50000),(433,'R201','A3',1,50000),(434,'R201','A4',1,50000),(435,'R201','A5',1,50000),(436,'R201','A6',1,50000),(437,'R201','A7',1,50000),(438,'R201','A8',1,50000),(439,'R201','A9',1,50000),(440,'R201','A10',1,50000),(441,'R201','B1',1,50000),(442,'R201','B2',1,50000),(443,'R201','B3',1,50000),(444,'R201','B4',1,50000),(445,'R201','B5',1,50000),(446,'R201','B6',1,50000),(447,'R201','B7',1,50000),(448,'R201','B8',1,50000),(449,'R201','B9',1,50000),(450,'R201','B10',1,50000),(451,'R201','C1',1,50000),(452,'R201','C2',1,50000),(453,'R201','C3',1,50000),(454,'R201','C4',1,50000),(455,'R201','C5',1,50000),(456,'R201','C6',1,50000),(457,'R201','C7',1,50000),(458,'R201','C8',1,50000),(459,'R201','C9',1,50000),(460,'R201','C10',1,50000),(461,'R201','D1',1,50000),(462,'R201','D2',1,50000),(463,'R201','D3',1,50000),(464,'R201','D4',1,50000),(465,'R201','D5',1,50000),(466,'R201','D6',1,50000),(467,'R201','D7',1,50000),(468,'R201','D8',1,50000),(469,'R201','D9',1,50000),(470,'R201','D10',1,50000),(471,'R201','E1',2,80000),(472,'R201','E2',2,80000),(473,'R201','E3',2,80000),(474,'R201','E4',2,80000),(475,'R201','E5',2,80000),(476,'R201','E6',2,80000),(477,'R201','E7',2,80000),(478,'R201','E8',2,80000),(479,'R201','E9',2,80000),(480,'R201','E10',2,80000),(481,'R201','F1',1,50000),(482,'R201','F2',1,50000),(483,'R201','F3',1,50000),(484,'R201','F4',1,50000),(485,'R201','F5',1,50000),(486,'R201','F6',1,50000),(487,'R201','F7',1,50000),(488,'R201','F8',1,50000),(489,'R201','F9',1,50000),(490,'R201','F10',1,50000),(491,'R201','G1',1,50000),(492,'R201','G2',1,50000),(493,'R201','G3',1,50000),(494,'R201','G4',1,50000),(495,'R201','G5',1,50000),(496,'R201','G6',1,50000),(497,'R201','G7',1,50000),(498,'R201','G8',1,50000),(499,'R201','G9',1,50000),(500,'R201','G10',1,50000),(501,'R201','H1',3,60000),(502,'R201','H2',3,60000),(503,'R201','H3',3,60000),(504,'R201','H4',3,60000),(505,'R201','H5',3,60000),(506,'R201','H6',3,60000),(507,'R201','H7',3,60000),(508,'R201','H8',3,60000),(509,'R201','H9',3,60000),(510,'R201','H10',3,60000),(511,'R202','A1',1,50000),(512,'R202','A2',1,50000),(513,'R202','A3',1,50000),(514,'R202','A4',1,50000),(515,'R202','A5',1,50000),(516,'R202','A6',1,50000),(517,'R202','A7',1,50000),(518,'R202','A8',1,50000),(519,'R202','A9',1,50000),(520,'R202','A10',1,50000),(521,'R202','B1',1,50000),(522,'R202','B2',1,50000),(523,'R202','B3',1,50000),(524,'R202','B4',1,50000),(525,'R202','B5',1,50000),(526,'R202','B6',1,50000),(527,'R202','B7',1,50000),(528,'R202','B8',1,50000),(529,'R202','B9',1,50000),(530,'R202','B10',1,50000),(531,'R202','C1',1,50000),(532,'R202','C2',1,50000),(533,'R202','C3',1,50000),(534,'R202','C4',1,50000),(535,'R202','C5',1,50000),(536,'R202','C6',1,50000),(537,'R202','C7',1,50000),(538,'R202','C8',1,50000),(539,'R202','C9',1,50000),(540,'R202','C10',1,50000),(541,'R202','D1',1,50000),(542,'R202','D2',1,50000),(543,'R202','D3',1,50000),(544,'R202','D4',1,50000),(545,'R202','D5',1,50000),(546,'R202','D6',1,50000),(547,'R202','D7',1,50000),(548,'R202','D8',1,50000),(549,'R202','D9',1,50000),(550,'R202','D10',1,50000),(551,'R202','E1',1,50000),(552,'R202','E2',1,50000),(553,'R202','E3',1,50000),(554,'R202','E4',1,50000),(555,'R202','E5',1,50000),(556,'R202','E6',1,50000),(557,'R202','E7',1,50000),(558,'R202','E8',1,50000),(559,'R202','E9',1,50000),(560,'R202','E10',1,50000),(561,'R202','F1',2,80000),(562,'R202','F2',2,80000),(563,'R202','F3',2,80000),(564,'R202','F4',2,80000),(565,'R202','F5',2,80000),(566,'R202','F6',2,80000),(567,'R202','F7',2,80000),(568,'R202','F8',2,80000),(569,'R202','F9',2,80000),(570,'R202','F10',2,80000),(571,'R202','G1',1,50000),(572,'R202','G2',1,50000),(573,'R202','G3',1,50000),(574,'R202','G4',1,50000),(575,'R202','G5',1,50000),(576,'R202','G6',1,50000),(577,'R202','G7',1,50000),(578,'R202','G8',1,50000),(579,'R202','G9',1,50000),(580,'R202','G10',1,50000),(581,'R202','H1',1,50000),(582,'R202','H2',1,50000),(583,'R202','H3',1,50000),(584,'R202','H4',1,50000),(585,'R202','H5',1,50000),(586,'R202','H6',1,50000),(587,'R202','H7',1,50000),(588,'R202','H8',1,50000),(589,'R202','H9',1,50000),(590,'R202','H10',1,50000),(591,'R202','I1',1,50000),(592,'R202','I2',1,50000),(593,'R202','I3',1,50000),(594,'R202','I4',1,50000),(595,'R202','I5',1,50000),(596,'R202','I6',1,50000),(597,'R202','I7',1,50000),(598,'R202','I8',1,50000),(599,'R202','I9',1,50000),(600,'R202','I10',1,50000),(601,'R202','J1',3,60000),(602,'R202','J2',3,60000),(603,'R202','J3',3,60000),(604,'R202','J4',3,60000),(605,'R202','J5',3,60000),(606,'R202','J6',3,60000),(607,'R202','J7',3,60000),(608,'R202','J8',3,60000),(609,'R202','J9',3,60000),(610,'R202','J10',3,60000),(611,'R203','A1',1,50000),(612,'R203','A2',1,50000),(613,'R203','A3',1,50000),(614,'R203','A4',1,50000),(615,'R203','A5',1,50000),(616,'R203','A6',1,50000),(617,'R203','A7',1,50000),(618,'R203','A8',1,50000),(619,'R203','A9',1,50000),(620,'R203','A10',1,50000),(621,'R203','B1',1,50000),(622,'R203','B2',1,50000),(623,'R203','B3',1,50000),(624,'R203','B4',1,50000),(625,'R203','B5',1,50000),(626,'R203','B6',1,50000),(627,'R203','B7',1,50000),(628,'R203','B8',1,50000),(629,'R203','B9',1,50000),(630,'R203','B10',1,50000),(631,'R203','C1',1,50000),(632,'R203','C2',1,50000),(633,'R203','C3',1,50000),(634,'R203','C4',1,50000),(635,'R203','C5',1,50000),(636,'R203','C6',1,50000),(637,'R203','C7',1,50000),(638,'R203','C8',1,50000),(639,'R203','C9',1,50000),(640,'R203','C10',1,50000),(641,'R203','D1',1,50000),(642,'R203','D2',1,50000),(643,'R203','D3',1,50000),(644,'R203','D4',1,50000),(645,'R203','D5',1,50000),(646,'R203','D6',1,50000),(647,'R203','D7',1,50000),(648,'R203','D8',1,50000),(649,'R203','D9',1,50000),(650,'R203','D10',1,50000),(651,'R203','E1',1,50000),(652,'R203','E2',1,50000),(653,'R203','E3',1,50000),(654,'R203','E4',1,50000),(655,'R203','E5',1,50000),(656,'R203','E6',1,50000),(657,'R203','E7',1,50000),(658,'R203','E8',1,50000),(659,'R203','E9',1,50000),(660,'R203','E10',1,50000),(661,'R203','F1',1,50000),(662,'R203','F2',1,50000),(663,'R203','F3',1,50000),(664,'R203','F4',1,50000),(665,'R203','F5',1,50000),(666,'R203','F6',1,50000),(667,'R203','F7',1,50000),(668,'R203','F8',1,50000),(669,'R203','F9',1,50000),(670,'R203','F10',1,50000),(671,'R203','G1',2,80000),(672,'R203','G2',2,80000),(673,'R203','G3',2,80000),(674,'R203','G4',2,80000),(675,'R203','G5',2,80000),(676,'R203','G6',2,80000),(677,'R203','G7',2,80000),(678,'R203','G8',2,80000),(679,'R203','G9',2,80000),(680,'R203','G10',2,80000),(681,'R203','H1',1,50000),(682,'R203','H2',1,50000),(683,'R203','H3',1,50000),(684,'R203','H4',1,50000),(685,'R203','H5',1,50000),(686,'R203','H6',1,50000),(687,'R203','H7',1,50000),(688,'R203','H8',1,50000),(689,'R203','H9',1,50000),(690,'R203','H10',1,50000),(691,'R203','I1',1,50000),(692,'R203','I2',1,50000),(693,'R203','I3',1,50000),(694,'R203','I4',1,50000),(695,'R203','I5',1,50000),(696,'R203','I6',1,50000),(697,'R203','I7',1,50000),(698,'R203','I8',1,50000),(699,'R203','I9',1,50000),(700,'R203','I10',1,50000),(701,'R203','J1',1,50000),(702,'R203','J2',1,50000),(703,'R203','J3',1,50000),(704,'R203','J4',1,50000),(705,'R203','J5',1,50000),(706,'R203','J6',1,50000),(707,'R203','J7',1,50000),(708,'R203','J8',1,50000),(709,'R203','J9',1,50000),(710,'R203','J10',1,50000),(711,'R203','K1',1,50000),(712,'R203','K2',1,50000),(713,'R203','K3',1,50000),(714,'R203','K4',1,50000),(715,'R203','K5',1,50000),(716,'R203','K6',1,50000),(717,'R203','K7',1,50000),(718,'R203','K8',1,50000),(719,'R203','K9',1,50000),(720,'R203','K10',1,50000),(721,'R203','L1',3,60000),(722,'R203','L2',3,60000),(723,'R203','L3',3,60000),(724,'R203','L4',3,60000),(725,'R203','L5',3,60000),(726,'R203','L6',3,60000),(727,'R203','L7',3,60000),(728,'R203','L8',3,60000),(729,'R203','L9',3,60000),(730,'R203','L10',3,60000),(731,'R204','A1',1,50000),(732,'R204','A2',1,50000),(733,'R204','A3',1,50000),(734,'R204','A4',1,50000),(735,'R204','A5',1,50000),(736,'R204','A6',1,50000),(737,'R204','A7',1,50000),(738,'R204','A8',1,50000),(739,'R204','A9',1,50000),(740,'R204','A10',1,50000),(741,'R204','B1',1,50000),(742,'R204','B2',1,50000),(743,'R204','B3',1,50000),(744,'R204','B4',1,50000),(745,'R204','B5',1,50000),(746,'R204','B6',1,50000),(747,'R204','B7',1,50000),(748,'R204','B8',1,50000),(749,'R204','B9',1,50000),(750,'R204','B10',1,50000),(751,'R204','C1',1,50000),(752,'R204','C2',1,50000),(753,'R204','C3',1,50000),(754,'R204','C4',1,50000),(755,'R204','C5',1,50000),(756,'R204','C6',1,50000),(757,'R204','C7',1,50000),(758,'R204','C8',1,50000),(759,'R204','C9',1,50000),(760,'R204','C10',1,50000),(761,'R204','D1',1,50000),(762,'R204','D2',1,50000),(763,'R204','D3',1,50000),(764,'R204','D4',1,50000),(765,'R204','D5',1,50000),(766,'R204','D6',1,50000),(767,'R204','D7',1,50000),(768,'R204','D8',1,50000),(769,'R204','D9',1,50000),(770,'R204','D10',1,50000),(771,'R204','E1',1,50000),(772,'R204','E2',1,50000),(773,'R204','E3',1,50000),(774,'R204','E4',1,50000),(775,'R204','E5',1,50000),(776,'R204','E6',1,50000),(777,'R204','E7',1,50000),(778,'R204','E8',1,50000),(779,'R204','E9',1,50000),(780,'R204','E10',1,50000),(781,'R204','F1',1,50000),(782,'R204','F2',1,50000),(783,'R204','F3',1,50000),(784,'R204','F4',1,50000),(785,'R204','F5',1,50000),(786,'R204','F6',1,50000),(787,'R204','F7',1,50000),(788,'R204','F8',1,50000),(789,'R204','F9',1,50000),(790,'R204','F10',1,50000),(791,'R204','G1',2,80000),(792,'R204','G2',2,80000),(793,'R204','G3',2,80000),(794,'R204','G4',2,80000),(795,'R204','G5',2,80000),(796,'R204','G6',2,80000),(797,'R204','G7',2,80000),(798,'R204','G8',2,80000),(799,'R204','G9',2,80000),(800,'R204','G10',2,80000),(801,'R204','H1',1,50000),(802,'R204','H2',1,50000),(803,'R204','H3',1,50000),(804,'R204','H4',1,50000),(805,'R204','H5',1,50000),(806,'R204','H6',1,50000),(807,'R204','H7',1,50000),(808,'R204','H8',1,50000),(809,'R204','H9',1,50000),(810,'R204','H10',1,50000),(811,'R204','I1',1,50000),(812,'R204','I2',1,50000),(813,'R204','I3',1,50000),(814,'R204','I4',1,50000),(815,'R204','I5',1,50000),(816,'R204','I6',1,50000),(817,'R204','I7',1,50000),(818,'R204','I8',1,50000),(819,'R204','I9',1,50000),(820,'R204','I10',1,50000),(821,'R204','J1',1,50000),(822,'R204','J2',1,50000),(823,'R204','J3',1,50000),(824,'R204','J4',1,50000),(825,'R204','J5',1,50000),(826,'R204','J6',1,50000),(827,'R204','J7',1,50000),(828,'R204','J8',1,50000),(829,'R204','J9',1,50000),(830,'R204','J10',1,50000),(831,'R204','K1',1,50000),(832,'R204','K2',1,50000),(833,'R204','K3',1,50000),(834,'R204','K4',1,50000),(835,'R204','K5',1,50000),(836,'R204','K6',1,50000),(837,'R204','K7',1,50000),(838,'R204','K8',1,50000),(839,'R204','K9',1,50000),(840,'R204','K10',1,50000),(841,'R204','L1',3,60000),(842,'R204','L2',3,60000),(843,'R204','L3',3,60000),(844,'R204','L4',3,60000),(845,'R204','L5',3,60000),(846,'R204','L6',3,60000),(847,'R204','L7',3,60000),(848,'R204','L8',3,60000),(849,'R204','L9',3,60000),(850,'R204','L10',3,60000);
/*!40000 ALTER TABLE `seats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('N1EpYxZrBp8wwZKe5dO2aMsFIog9yhvKtMS1ZTkK',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNVlyV3h4T3ZQRmF0cmtwcWZPMnZlc1JHR3RTbGFabThyV01QMEc4RSI7czo2OiJzdGF0dXMiO3M6NTc6IkLhuqFuIMSRw6MgxJHEg25nIHh14bqldCB0aMOgbmggY8O0bmcuIEjhurluIGfhurdwIGzhuqFpISI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjE6e2k6MDtzOjY6InN0YXR1cyI7fX19',1765385241),('oEX7V5b9Oeg1hdZ75SvxdMiafZj12PFjjxmOog70',28,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiV2hIQ283bllWUGZ4UGZnZ1M4b09aaGJBekZ4ZGt2dXdBRXRHYklmOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjg7czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1765389925);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shows`
--

DROP TABLE IF EXISTS `shows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shows` (
  `show_id` varbinary(16) NOT NULL,
  `movie_id` smallint(5) unsigned DEFAULT NULL,
  `cinema_id` smallint(5) unsigned NOT NULL,
  `room_code` char(6) NOT NULL,
  `show_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `remaining_seats` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`show_id`),
  UNIQUE KEY `uq_unique_show` (`cinema_id`,`room_code`,`show_date`,`start_time`),
  KEY `idx_cinema_date` (`cinema_id`,`show_date`),
  KEY `idx_movie_date` (`movie_id`,`show_date`),
  KEY `idx_date` (`show_date`),
  KEY `shows_room_code_foreign` (`room_code`),
  CONSTRAINT `shows_cinema_id_foreign` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`cinema_id`) ON DELETE CASCADE,
  CONSTRAINT `shows_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE SET NULL,
  CONSTRAINT `shows_room_code_foreign` FOREIGN KEY (`room_code`) REFERENCES `rooms` (`room_code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shows`
--

LOCK TABLES `shows` WRITE;
/*!40000 ALTER TABLE `shows` DISABLE KEYS */;
INSERT INTO `shows` VALUES ('052e6561-c845-11',3,1,'R101','2025-11-27','10:00:00','11:32:00',80),('052e65bf-c845-11',3,1,'R102','2025-11-27','14:00:00','15:32:00',60),('052e6618-c845-11',3,1,'R103','2025-11-27','18:00:00','19:32:00',90),('052e66ad-c845-11',3,1,'R104','2025-11-27','20:30:00','22:02:00',80),('052e6706-c845-11',3,1,'R105','2025-11-27','22:30:00','00:02:00',120),('052e675c-c845-11',3,1,'R101','2025-11-28','10:00:00','11:32:00',80),('052e67b7-c845-11',3,1,'R102','2025-11-28','14:00:00','15:32:00',60),('052e680d-c845-11',3,1,'R103','2025-11-28','18:00:00','19:32:00',90),('052e688e-c845-11',3,1,'R104','2025-11-28','20:30:00','22:02:00',80),('052e68ea-c845-11',3,1,'R105','2025-11-28','22:30:00','00:02:00',120),('052e6956-c845-11',3,1,'R101','2025-11-29','10:00:00','11:32:00',80),('052e69ac-c845-11',3,1,'R102','2025-11-29','14:00:00','15:32:00',60),('052e6a01-c845-11',3,1,'R103','2025-11-29','18:00:00','19:32:00',90),('052e6a58-c845-11',3,1,'R104','2025-11-29','20:30:00','22:02:00',80),('052e6ab4-c845-11',3,1,'R105','2025-11-29','22:30:00','00:02:00',120),('052e6b04-c845-11',3,1,'R101','2025-11-30','10:00:00','11:32:00',80),('052e6b5b-c845-11',3,1,'R102','2025-11-30','14:00:00','15:32:00',60),('052e6bb4-c845-11',3,1,'R103','2025-11-30','18:00:00','19:32:00',90),('052e6c0f-c845-11',3,1,'R104','2025-11-30','20:30:00','22:02:00',80),('052e6dc0-c845-11',3,1,'R105','2025-11-30','22:30:00','00:02:00',120),('0535ef7d-c845-11',3,2,'R201','2025-11-27','09:30:00','11:02:00',80),('0535f057-c845-11',3,2,'R202','2025-11-27','13:00:00','14:32:00',100),('0535f12d-c845-11',3,2,'R203','2025-11-27','17:00:00','18:32:00',120),('0535f200-c845-11',3,2,'R204','2025-11-27','20:00:00','21:32:00',120),('0535f2db-c845-11',3,2,'R201','2025-11-28','09:30:00','11:02:00',80),('0535f3af-c845-11',3,2,'R202','2025-11-28','13:00:00','14:32:00',100),('0535f489-c845-11',3,2,'R203','2025-11-28','17:00:00','18:32:00',120),('0535f55f-c845-11',3,2,'R204','2025-11-28','20:00:00','21:32:00',120),('0535f637-c845-11',3,2,'R201','2025-11-29','09:30:00','11:02:00',80),('0535f70f-c845-11',3,2,'R202','2025-11-29','13:00:00','14:32:00',100),('0535f7e5-c845-11',3,2,'R203','2025-11-29','17:00:00','18:32:00',120),('0535f8bf-c845-11',3,2,'R204','2025-11-29','20:00:00','21:32:00',120),('0535f99a-c845-11',3,2,'R201','2025-11-30','09:30:00','11:02:00',80),('0535fa7a-c845-11',3,2,'R202','2025-11-30','13:00:00','14:32:00',100),('0535fb4f-c845-11',3,2,'R203','2025-11-30','17:00:00','18:32:00',120),('0535fc28-c845-11',3,2,'R204','2025-11-30','20:00:00','21:32:00',120),('SHOW20251130001',4,1,'R101','2025-11-30','14:30:00','16:22:00',80),('SHOW20251130002',4,1,'R102','2025-12-01','18:00:00','20:00:00',60),('SHOW20251202001',3,1,'R101','2025-12-02','09:00:00','10:32:00',80),('SHOW20251203001',3,1,'R102','2025-12-03','15:00:00','16:32:00',60),('SHOW20251204001',3,1,'R101','2025-12-04','02:00:00','03:32:00',78),('SHOW20251204002',3,1,'R101','2025-12-04','11:30:00','13:02:00',78),('SHOW20251205001\0',3,1,'R101','2025-12-05','14:00:00','15:32:00',80),('SHOW20251205002',9,1,'R103','2025-12-05','21:00:00','22:43:00',85),('SHOW20251206001',7,1,'R102','2025-12-06','00:12:00','01:56:00',51),('SHOW20251208001',5,1,'R101','2025-12-08','15:00:00','16:55:00',80),('SHOW20251208002',8,1,'R102','2025-12-08','16:00:00','17:44:00',58),('SHOW20251211002\0',4,1,'R102','2025-12-11','13:00:00','14:59:00',53),('SHOW20251211003',4,2,'R201','2025-12-11','08:00:00','09:59:00',80),('SHOW20251212002',3,1,'R101','2025-12-12','14:00:00','15:32:00',76),('SHOW20251212003',3,1,'R101','2025-12-12','17:00:00','18:32:00',80),('SHOW20251212004',13,1,'R102','2025-12-12','14:00:00','16:00:00',60),('SHOW20251213001',4,2,'R202','2025-12-13','07:00:00','08:59:00',100),('SHOW20260101001',24,1,'R101','2026-01-01','20:00:00','21:50:00',80),('SHOW20260101002',24,2,'R201','2026-01-01','20:00:00','21:50:00',80),('�6j�$H',3,1,'R101','2025-12-02','14:00:00','15:32:00',80);
/*!40000 ALTER TABLE `shows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `ava` varchar(255) DEFAULT NULL,
  `provider` varchar(20) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `provider_avatar` text DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uq_email` (`email`),
  UNIQUE KEY `provider_id` (`provider_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (9,'Lộc Tấn','latanloc24012005@gmail.com','$2y$12$kh/XHRbvrGlTZY3oTVuvnObOk6z.KysbnAligidOBgIIbB8neFzDS',NULL,NULL,'BUhYZJuNC49oG0f1UosqOpVGbOPNtMMmjxAtX8SQ9cGSI5P63lvwhCoN9zr6',NULL,NULL,NULL,NULL,'google','113375472102773138662','https://lh3.googleusercontent.com/a/ACg8ocJBX5EsFBC70uCCnKMrM_Lw7e5qjS7CxRDH1MyJvs5TwDnuPg=s96-c',1,'2025-12-04 05:23:13','2025-12-05 08:59:07'),(28,'abc','bung24203@gmail.com','$2y$12$tUwtVkhdomXiduGoUBjOmOgQP/ErQ5XJjKup7smzgkXUkFwphsmp.',NULL,'2025-12-08 22:01:32','ksXzSvgwiluXS2lfhicwxaMYMsModCqO3UztknNnjbRwgaRTtSxerhzSImlE','230912','2025-12-09 17:20:44','0987654320','avatars/HmhDDJ5h4oifvY8TnEWRLNKifGNiFopjWjxysR4T.png',NULL,NULL,NULL,1,'2025-12-05 08:28:53','2025-12-10 17:00:52'),(29,'abc','thuy2005254@gmail.com','$2y$12$sFO7/Whd9ZFatTbiOPECX.HsVCxkPpR09Dhag9l.CuPlQY1fsxX7m',NULL,NULL,NULL,'261946','2025-12-08 15:08:05','0987654322',NULL,NULL,NULL,NULL,2,'2025-12-08 08:03:05','2025-12-08 08:03:05'),(31,'Nguyễn Thy','kiogit30@gmail.com','$2y$12$VYM/OolS0X7MxKdU7X7yvejlm9jrLuIsRllI6s5mvjFKeoWxn02Xq',NULL,NULL,NULL,'754872','2025-12-08 16:26:19','0987765432',NULL,NULL,NULL,NULL,2,'2025-12-08 09:21:19','2025-12-08 09:21:19'),(32,'abc','26A4041202@hvnh.edu.vn','$2y$12$bQi4ykaUWk5PWCYf/s2NIO2QqCuyS0ZSa70OIPuKpmHMYmDLkkxuu',NULL,'2025-12-08 15:29:43',NULL,NULL,NULL,'0987654332',NULL,NULL,NULL,NULL,1,'2025-12-08 15:14:23','2025-12-08 15:29:43');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-11  1:06:23
