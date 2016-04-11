-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.20 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table supplified.dberror
CREATE TABLE IF NOT EXISTS `dberror` (
  `error_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `error_code` varchar(255) DEFAULT NULL,
  `error_message` varchar(255) DEFAULT NULL,
  `error_query` text,
  `error_time` datetime DEFAULT NULL,
  PRIMARY KEY (`error_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table supplified.order_header
CREATE TABLE IF NOT EXISTS `order_header` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_prefix` varchar(5) DEFAULT NULL,
  `order_num` varchar(15) DEFAULT NULL,
  `order_type` varchar(50) DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT NULL,
  `payment_status` enum('pending','paid','cod','partial_paid') DEFAULT NULL,
  `total_discount` float DEFAULT NULL,
  `total_payable_amount` float DEFAULT NULL,
  `total_paid_amount` float DEFAULT NULL,
  `discounted_price_total` float DEFAULT NULL,
  `payment_method` enum('cod','paytm','prepay','tpsl','tbd') DEFAULT NULL,
  `sub_total` float DEFAULT NULL,
  `user_name` varchar(256) DEFAULT NULL,
  `user_email` varchar(256) DEFAULT NULL,
  `user_phone` varchar(20) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL,
  `shipping_name` varchar(256) DEFAULT NULL,
  `shipping_address` text,
  `shipping_city_id` varchar(100) DEFAULT NULL,
  `shipping_state_id` varchar(100) DEFAULT NULL,
  `shipping_pincode` int(11) DEFAULT NULL,
  `billing_name` varchar(256) DEFAULT NULL,
  `billing_email` varchar(256) DEFAULT NULL,
  `billing_address` text,
  `billing_city_id` varchar(100) DEFAULT NULL,
  `billing_state_id` varchar(100) DEFAULT NULL,
  `billing_pincode` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `coupon_code` varchar(20) DEFAULT NULL,
  `coupon_text` varchar(512) DEFAULT NULL,
  `is_processed` tinyint(1) DEFAULT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `bank_transaction_id` varchar(50) DEFAULT NULL,
  `transaction_time` datetime DEFAULT NULL,
  `gateway_name` varchar(50) DEFAULT NULL,
  `bankname` varchar(50) DEFAULT NULL,
  `payment_mod` varchar(50) DEFAULT NULL,
  `source_url` varchar(255) DEFAULT NULL,
  `order_source` varchar(50) DEFAULT NULL,
  `source_type` varchar(50) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `source_name` varchar(50) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table supplified.order_line
CREATE TABLE IF NOT EXISTS `order_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `subscribed_product_id` int(11) DEFAULT NULL,
  `base_product_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `price` float DEFAULT NULL,
  `discounted_price` float DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `total_price` float DEFAULT NULL,
  `product_name` varchar(256) DEFAULT NULL,
  `discount_type` enum('price','item') DEFAULT NULL,
  `discount_qty` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table supplified.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `title_of_review` text,
  `review` text NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1',
  `ip_address` text,
  `submited_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
